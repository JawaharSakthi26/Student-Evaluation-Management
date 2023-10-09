@extends('layouts.admin')
@section('title','Admin | Quiz')
@section('content')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">View Quiz</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active">View Quiz</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mx-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quiz List</h3>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                <th>Quiz Title</th>
                                <th>Number of Questions</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($titles as $title)
                                <tr>
                                    <td>{{ $title->title }}</td>
                                    <td>{{ $title->questions->count() }}</td>
                                    <td>
                                        <a href="{{ route('quiz.edit', $title->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{route('quiz.destroy',$title->id)}}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 