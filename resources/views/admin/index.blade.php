@extends('layouts.admin')
@section('title','Admin | Dashboard')
@section('content')
<div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <a href=""><li class="breadcrumb-item active">Dashboard</li></a> 
              </ol>
            </div>
          </div>
        </div>
      </div>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{$totalquiz}}</h3>
                  <p>Total Quiz</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{route('quiz.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
@endsection