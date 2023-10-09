<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                'title_id' => $title->id,
            ]);

            if ($questionData['type'] == '3') {
                foreach ($questionData['answers']['answer'] as $key => $file) {
                    $directory = 'uploads';
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($directory, $filename);
                    $isCorrect = $key == $questionData['answers']['isCorrect'][0] ? '1' : '0';

                    $question->answers()->create([
                        'answer' => $filename,
                        'isCorrect' => $isCorrect,
                        'question_id' => $question->id,
                    ]);
                }
            } else {
                foreach ($questionData['answers']['answer'] as $key => $answerText) {
                    $isCorrect = in_array($key, array_keys($questionData['answers']['isCorrect'])) ? '1' : '0';
                    $question->answers()->create([
                        'answer' => $answerText,
                        'isCorrect' => $isCorrect,
                        'question_id' => $question->id,
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
        
        $title = Title::findOrFail($id);
        $title->update([
            'title' => $request->input('title'),
        ]);

        foreach ($data['questions'] as $k => $questionData) {
            $question = $title->questions();

            if ($question) {
                $questionDetail = $question->find($questionData['questionid']);

                if(!empty($questionDetail)){
                    $questionDetail->update([
                        'question' => $questionData['question'],
                        'dropdown' => $questionData['type'],
                    ]);

                }else{
                    $questionDetail = $question->create([
                        'question' => $questionData['question'],
                        'dropdown' => $questionData['type'],
                    ]);
                }
                
                $answerObj = new Answer;
                if($questionData['type'] != 3 && !empty(($questionData['answers']['answer']))) {
                    foreach ($questionData['answers']['answer'] as $key => $value) {
                        $isCorrect = in_array($key, array_keys($questionData['answers']['isCorrect'])) ? '1' : '0';
                        $idArray = $questionData['answers']['id'][$key];
                        // dump($idArray, $value);
                        if(!empty($questionData['answers']['id'])){
                            $answerObj->whereNotIn('id', $questionData['answers']['id'])->where('question_id',  $questionDetail->id)->delete();
                        }
                        if(!empty($idArray)) {
                            $answerObj->where('id', $idArray)->update([
                                'answer' => $value,
                                'isCorrect' => $isCorrect,
                                'question_id' => $questionDetail->id,
                            ]);
                        }else{
                            // dd($answerObj);
                            $answerObj->create( [
                                'answer' => $value,
                                'isCorrect' => $isCorrect,
                                'question_id' => $questionDetail->id,
                            ]);
                        }
                    }                    
                }else if($questionData['type'] == 3 && !empty(($questionData['answers']['answer']))) {
                    foreach ($questionData['answers']['answer'] as $key => $value) {
                        $directory = 'uploads';
                        $filename = uniqid() . '.' . $value->getClientOriginalExtension();
                        $value->move($directory, $filename);
                        $isCorrect = $key == $questionData['answers']['isCorrect'][0] ? '1' : '0';
                        // $isCorrect = in_array($key, array_keys($questionData['answers']['isCorrect'])) ? '1' : '0';
                        $idArray = $questionData['answers']['id'][$key];

                        if(!empty($idArray)) {
                            $answerObj->where('id', $idArray)->update([
                                'answer' => $filename,
                                'isCorrect' => $isCorrect,
                                'question_id' => $questionDetail->id,
                            ]);
                        }else{
                            // dd($answerObj);
                            $answerObj->create( [
                                'answer' => $filename,
                                'isCorrect' => $isCorrect,
                                'question_id' => $questionDetail->id,
                            ]);
                        }
                    }
                }
            }
            if (!empty($request->deletedQuestions)) {
                $deletedIds = explode(',', $request->deletedQuestions);            
                Answer::whereIn('question_id', $deletedIds)->delete();            
                Question::whereIn('id', $deletedIds)->delete();
            }
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