<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titles = Title::with('questions')->get();
        return view('admin.quiz.index', compact('titles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.quiz.create');
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
        {
            $data = $request->all();   
            $title = Title::create([
                'title' => $request->input('title'),
            ]);
        
            foreach ($data['questions'] as $k => $questionData) {
                $question = $title->questions()->create([
                    'question' => $questionData['question'],
                    'dropdown' => $questionData['type'],
                ]);
        
                if ($questionData['type'] == '3') {
                    foreach ($questionData['answers']['answer'] as $key => $file) {
                        $directory = 'uploads';
                        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($directory, $filename);            
                        $isCorrect = in_array($key+1, $questionData['answers']['isCorrect']) ? '1' : '0';
                        $question->answers()->create([
                            'answer' => $filename,
                            'isCorrect' => $isCorrect,
                        ]);
                    }
                } else {
                    foreach ($questionData['answers']['answer'] as $key => $answerText) {
                        $isCorrect = in_array($key+1, $questionData['answers']['isCorrect']) ? '1' : '0';
                        $question->answers()->create([
                            'answer' => $answerText,
                            'isCorrect' => $isCorrect,
                        ]);
                    }
                }
            }
            return new JsonResponse(['message' => 'Quiz Created Successfully']);
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = Title::with('questions.answers')->findOrFail($id);
        $data = [
            'title' => $title,
            'questions' => $title->questions,
        ];
        return view('admin.quiz.create',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        // dd($data);
        $title = Title::findOrFail($id);
        $title->update([
            'title' => $request->input('title'),
        ]);
    
        foreach ($data['questions'] as $k => $questionData) {
            $question = $title->questions()->updateOrCreate(
                ['id' => $questionData['questionid']], 
                [
                    'question' => $questionData['question'],
                    'dropdown' => $questionData['type'],
                ]
            );

            $answerObj = $question->answers();
            $existingAnswerIds = $answerObj->pluck('id')->toArray();
    
            foreach ($questionData['answers']['answer'] as $key => $value) {
                $isCorrect = in_array($key+1, $questionData['answers']['isCorrect']) ? '1' : '0';
                $idArray = $questionData['answers']['id'][$key];
    
                if ($questionData['type'] == '3') {
                    $directory = 'uploads';
                    $filename = uniqid() . '.' . $value->getClientOriginalExtension();
                    $existingFile = $answerObj->where('answer', $filename)->first();
                    if (!$existingFile) {
                        $value->move($directory, $filename);
                    }

                    if ($idArray && in_array($idArray, $existingAnswerIds)) {
                        $answerObj->where('id', $idArray)->update([
                            'answer' => $filename,
                            'isCorrect' => $isCorrect,
                            'question_id' => $question->id,
                        ]);
                    } else {
                        $answerObj->create([
                            'answer' => $filename,
                            'isCorrect' => $isCorrect,
                            'question_id' => $question->id,
                        ]);
                    }
                } else {
                    if ($idArray && in_array($idArray, $existingAnswerIds)) {
                        $dbData = $answerObj->find($idArray);
                        $dbData->update([
                            'answer' => $value,
                            'isCorrect' => $isCorrect,
                            'question_id' => $question->id,
                        ]);
                    } else {
                        $answerObj->create([
                            'answer' => $value,
                            'isCorrect' => $isCorrect,
                            'question_id' => $question->id,
                        ]);
                    }
                }
            }
        }
        if (!empty($request->deletedQuestions)) {
            $deletedIds = explode(',', $request->deletedQuestions);
            $title->questions()->whereIn('id', $deletedIds)->delete();
        }

        if (!empty($request->deletedAnswers)) {
            $deletedAnswerIds = explode(',', $request->deletedAnswers);
            Answer::whereIn('id', $deletedAnswerIds)->delete();
        }
        return new JsonResponse(['message' => 'Quiz Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $title = Title::find($id);
        if ($title) {
            $title->delete();
        }
        return redirect()->route('quiz.index');
    }
}