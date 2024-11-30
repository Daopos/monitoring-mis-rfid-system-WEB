@extends('layouts.adminlayout')

{{-- @section('title', 'Custom Orders') --}}

@section('styles')
    <link href="{{ asset('/css/admintodolist.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="todolist-container">
        <div class="reg-nav">
            <h1 class="display-1 ">ToDo List</h1>
        </div>
        <div class="todolist-body">
            <div class="todo">
                <h4>Pending Task</h4>
            </div>
        </div>
        <div class="collecting-container">
            <div class="collecting-box">
                <div class="d-flex">
                    <img src="/img/garbage.png" alt="">
                    <div class="d-flex flex-column gap-3">
                        <h4>Collecting garbage</h4>
                        <h4>5/5/2024</h4>
                    </div>
                </div>
                <div class="btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="checkbox" checked autocomplete="off"> Checked
                    </label>
                </div>
            </div>
            <div class="collecting-box">
                <div class="d-flex">
                    <img src="/img/garbage.png" alt="">
                    <div class="d-flex flex-column gap-3">
                        <h4>Clean up drive</h4>
                        <h4>5/10/2024</h4>
                    </div>
                </div>
                <div class="btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="checkbox" checked autocomplete="off"> Checked
                    </label>
                </div>
            </div>
        </div>
        <div class=" mt-5 todolist-body">
            <div class="todo1">
                <h4>Completed Task</h4>
            </div>
        </div>
        <div class="collecting-container">
            <div class="collecting-box">
                <div class="d-flex">
                    <img src="/img/garbage.png" alt="">
                    <div class="d-flex flex-column gap-3">
                        <h4>Collecting garbage</h4>
                        <h4>5/5/2024</h4>
                    </div>
                </div>
                <div class="btn-group-toggle btn-checked" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="checkbox" checked autocomplete="off"> Checked
                    </label>
                </div>
            </div>

        </div>

    </div>
@endsection
