@extends('layouts.admin')
@if (isset($data))
@section('title', 'Admin | Edit Quiz')
@else
@section('title', 'Admin | Add Quiz')
@endif
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if (isset($data))
                    <h1>Update Quiz</h1>
                    @else
                    <h1>Add Quiz</h1>
                    @endif
                </div>
            </div>
            <div id="error-container">
                <!-- Error messages will be displayed here -->
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            @if (isset($data))
                            <h3 class="card-title">Edit Quiz</h3>
                            @else
                            <h3 class="card-title">Create Quiz</h3>
                            @endif
                        </div>
                        @if (isset($data))
                        <form method="POST" action="{{ route('quiz.update', $data['title']->id) }}" id="quiz-form"
                            enctype="multipart/form-data">
                            @method('PUT')
                        @else
                        <form method="POST" action="{{ route('quiz.store') }}" id="quiz-form"
                            enctype="multipart/form-data">
                        @endif
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ isset($data['title']) ? $data['title']->title : '' }}"
                                    placeholder="Enter Title">
                                <input type="hidden" class="form-control" id="deletedQuestions" name="deletedQuestions"
                                    placeholder="Enter Question">
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div id="quiz-container">
                            <!-- All the fields will be appended here -->
                        </div>

                        <div class="card-footer">
                            <button type="button" class="btn btn-primary" id="addQuestion">Add Question</button>
                            @if (isset($data))
                            <button type="button" class="btn btn-success" id="submit-btn">Update</button>
                            @else
                            <button type="button" class="btn btn-success" id="submit-btn">Insert</button>
                            @endif
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var submitFlag = true;

    @if(isset($data))
        var questionData = @json($data['questions']);
        var formAction = '{{ route("quiz.update", $data["title"]->id) }}';
        $(document).ready(function() {
            questionData.forEach(function(question) {
                addQuestion(question);
            });
        }); 
    @else
        var formAction = '{{ route("quiz.store") }}'; 
    @endif
    var questionCount = 0

    function initializeValidation() {
        $('#quiz-form').validate({
            rules: {
                'title': {
                    required: true
                }
            },
            messages: {
                'title': {
                    required: 'Please enter a title'
                }
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.closest(".form-group").find(".error-message"));
                submitFlag = false;
            }
        });

        $('[id^="question-"]').each(function () {
            var questionId = $(this).attr('id').split('-')[1];
            $(this).find('.form-control').each(function () {
                var inputName = $(this).attr('name');
                if (inputName && inputName.startsWith('questions[' + questionId + ']')) {
                    $(this).rules('add', {
                        required: true,
                        messages: {
                            required: 'Please enter a question'
                        }
                    });
                }
            });

            $(`#answers-container-${questionId} .form-control`).each(function (index) {
                var answerInputName = $(this).attr('name');
                if (answerInputName) {
                    $(this).rules('add', {
                        required: true,
                        messages: {
                            required: 'Please enter at least one answer'
                        }
                    });
                }
            });
        });

        $.validator.addMethod("atleastOneQuestion", function (value, element) {
            var questionCount = $('[id^="question-"]').length;
            return questionCount > 0;
        }, "Please add at least one question.");

        var answerCount = $('[class^="answers-container"]').each(function(){
            var currentObj = $(this);
            currentObj.parent().find('.validate-answer').remove();
            var ansLength = currentObj.find('.input-group');
            var answerCount = ansLength.length;
            if(answerCount === 0){
                if(currentObj.parent().find('.question-textbox').val() != ''){
                    currentObj.parent().find('.remove-question').after('<div class="validate-answer text-danger">Please select atleast one answer</div>');
                    submitFlag = false;
                }
            }else if(answerCount != 0){
                var correctAnswer = currentObj.find('.input-group').find('.answer-check');
                var answerCountFlag = false;
                correctAnswer.each(function(index, value) {
                    var radioButtonChecked = $(this).is(':checked');
                    if (radioButtonChecked) {
                        console.log('Radio button is checked');
                        submitFlag = true;
                    } else {
                        currentObj.parent().find('.remove-question').after('<div class="validate-answer text-danger">Please select a correct answer for this question</div>')
                        submitFlag = false;
                    }
                });
            }
        });

        $('#title').rules('add', {
            atleastOneQuestion: true,
        });

    }

    function addQuestion(questionData) {
        questionCount++;
        var questionHtml = `
            <div class="card card-secondary mb-3" id="question-${questionCount}">
                <div class="card-header">
                    <h5 class="card-title">Question</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="question-${questionCount}-title">Question Text</label>
                        <input type="text" class="form-control question-textbox" id="question-${questionCount}-title" name="questions[${questionCount}][question]" value="${questionData ? questionData.question : ''}" placeholder="Enter Question">
                        <input type="hidden" class="form-control" id="question-${questionCount}-title" name="questions[${questionCount}][questionid]" value="${questionData ? questionData.id : ''}" placeholder="Enter Question">
                        <span class="error-message text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="question-${questionCount}-type">Question Type</label>
                        <select class="form-control type-select" id="question-${questionCount}-type"
                            name="questions[${questionCount}][type]">
                            <option value="1" ${questionData && questionData.dropdown == '1' ? 'selected' : ''}>Radio</option>
                            <option value="2" ${questionData && questionData.dropdown == '2' ? 'selected' : ''}>Checkbox</option>
                            <option value="3" ${questionData && questionData.dropdown == '3' ? 'selected' : ''}>File</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary add-answer" data-question-id="${questionCount}">Add Answer</button>
                    <button type="button" class="btn btn-danger remove-question" data-edit-id="${questionData ? questionData.id : ''}" data-question-id="${questionCount}">Remove Question</button>
                    <div class="answers-container mt-3" id="answers-container-${questionCount}">
                        <!-- Answers will be added here -->
                    </div>
                </div>
            </div>
        `;
        $("#quiz-container").append(questionHtml);
        initializeValidation();

        @if (isset($data))
        console.log(questionData.answers);
                if (questionData.answers && questionData.answers.length > 0) {
                    $(questionData.answers).each(function(answerIndex, answerValue){
                        addAnswer(questionCount, questionData.dropdown, answerValue); 
                    }); 
                }
        @endif
    }

    function addAnswer(questionId, questionType, answerValue) {
        var answerContainerId = `answers-container-${questionId}`;
        var answerHtml = '';
        var answer = answerValue;
        if (questionType == '1') {
            answerHtml = `
                <div class="form-group">
                    <label for="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}">Radio Answer</label>
                    <div class="input-group">
                        <div class="col-sm-1">
                            <input type="radio" name="questions[${questionId}][answers][isCorrect][]" value="" class="mt-2 answer-check" ${answer && answer.isCorrect == '1' ? 'checked' : ''}>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}" name="questions[${questionId}][answers][answer][]" value="${answer ? answer.answer : ''}" placeholder="Enter Radio Answer">
                            <input type="hidden" class="form-control" id="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}" name="questions[${questionId}][answers][id][]" value="${answer ? answer.id : ''}" placeholder="Enter Radio Answer">
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-danger remove-answer" data-answer-id="${$("#" + answerContainerId + " .form-group").length + 1}">Remove</button>
                        </div>
                    </div>
                </div>
            `;
        } else if (questionType == '2') {
            answerHtml = `
                <div class="form-group">
                    <label for="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}">Checkbox Answer</label>
                    <div class="input-group">
                        <div class="col-sm-1">
                            <input type="checkbox" name="questions[${questionId}][answers][isCorrect][]" value="${$("#" + answerContainerId + " .form-group").length + 1}" class="mt-2" ${answer && answer.isCorrect == '1' ? 'checked' : ''}>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}" name="questions[${questionId}][answers][answer][]" value="${answer ? answer.answer : ''}" placeholder="Enter Checkbox Answer">
                            <input type="hidden" class="form-control" id="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}" name="questions[${questionId}][answers][id][]" value="${answer ? answer.id : ''}" placeholder="Enter Radio Answer">
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-danger remove-answer"
                                data-answer-id="${$("#" + answerContainerId + " .form-group").length + 1}">Remove</button>
                        </div>
                    </div>
                </div>
            `;
        } else if (questionType == '3') {
            answerHtml = `
                <div class="form-group">
                    <label for="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}">File Answer</label>
                    <div class="input-group">
                        <div class="col-sm-1">
                            <input type="radio" name="questions[${questionId}][answers][isCorrect][]" value="${$("#" + answerContainerId + " .form-group").length + 1}" class="mt-2" ${answer && answer.isCorrect == '1' ? 'checked' : ''}>
                        </div>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}" name="questions[${questionId}][answers][answer][]">
                            <input type="hidden" class="form-control" id="question-${questionId}-answer-${$("#" + answerContainerId + " .form-group").length + 1}" name="questions[${questionId}][answers][id][]" value="${answer ? answer.id : ''}" placeholder="Enter Radio Answer">
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-danger remove-answer" data-answer-id="${$("#" + answerContainerId + " .form-group").length + 1}">Remove</button>
                        </div>
                    </div>
                </div>
            `;
        }
        $(`#${answerContainerId}`).append(answerHtml);
        initializeValidation();
    }

    $(document).on('change', '.type-select', function () {
        var questionId = $(this).attr('id').split('-')[1];
        var questionType = $(this).val();
        $(`#answers-container-${questionId}`).empty();
    });

    function removeQuestion(questionId) {
        $(`#question-${questionId}`).remove();
    }

    $(document).on('click', '.remove-answer', function () {
        var answerContainer = $(this).closest('.form-group');
        answerContainer.remove();
    });

    $('#addQuestion').click(function () {
        addQuestion();
    });

    $(document).on('click', '.add-answer', function () {
        var questionId = $(this).data('question-id');
        var questionType = $(`#question-${questionId}-type`).val();
        addAnswer(questionId, questionType, null);
    });

    $(document).on('click', '.remove-question', function () {
        var questionId = $(this).data('question-id');
        var editId = $(this).data('edit-id');
        if (editId) {
            var deletedIds = $('#deletedQuestions').val();
            if (deletedIds != "") {
                deletedIds = deletedIds + ',';
            }
            deletedIds += editId;
            $('#deletedQuestions').val(deletedIds);
        }
        removeQuestion(questionId);
    });

    $('#submit-btn').click(function (e) {
        e.preventDefault();
        initializeValidation();

        if(!submitFlag)
        {
            console.log(submitFlag);
            return false;
        }

        if ($('#quiz-form').valid()) { 
            var formData = new FormData($('#quiz-form')[0]);

            $.ajax({
                type: 'POST',
                url: formAction,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.message) {
                        window.location.href = '{{ route("quiz.index") }}';
                    }
                },
                error: function (data) {
                    if (data.responseJSON && data.responseJSON.errors) {
                        var errors = data.responseJSON.errors;
                        var errorHtml = ' <div class="alert alert-danger"><ul>';
                        $.each(errors, function (key, value) {
                            errorHtml += '<li>' + value + '</li>';
                        });
                        errorHtml += '</ul></div>';

                        $('#error-container').html(errorHtml);
                    }
                },
                cache: false,
            });
        }
    });
    $(document).ready(function () {
        initializeValidation();
    });
</script>
@endsection