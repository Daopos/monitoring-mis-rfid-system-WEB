@extends('layouts.adminlayout')

{{-- @section('title', 'Custom Orders') --}}

@section('styles')
    <link href="{{ asset('/css/adminpayment.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="todolist-container">
        <div class="reg-nav">
            <h1 class="display-1 ">Payment Reminder</h1>
        </div>
        <div class="todolist-body">
            <div class="todo">
                <h4>Residents Information</h4>
            </div>
        </div>

        <div class="collecting-container ">
            <div class="mt-2 d-flex flex-column gap-3">
                <div class="d-flex flex-column rfidbox">
                    <label for=""></label>
                    <input type="Firstname" placeholder="Name:">
                </div>
            </div>
            <div class="mt-2 d-flex flex-column gap-3">
                <div class="d-flex flex-column rfidbox">
                    <label for=""></label>
                    <input type="Firstname" placeholder="Phase:">
                </div>
            </div>
            <div class="mt-2 d-flex flex-column gap-3">
                <div class="d-flex flex-column rfidbox">
                    <label for=""></label>
                    <input type="Firstname" placeholder="Mobile number:">
                </div>
            </div>
            <div class="mt-2 d-flex flex-column gap-3">
                <div class="d-flex flex-column rfidbox">
                    <label for=""></label>
                    <input type="Firstname" placeholder="Email:">
                </div>
            </div>


        </div>
        <div class=" mt-5 todolist-body">
            <div class="todo1">
                <h4>Completed Task</h4>
            </div>
        </div>
        <div class="collecting-container">
            <div>
                <h4>Reminder date:</h4>
            </div>
            <div class="collecting-box">
                <div class="d-flex">

                    <div class="d-flex flex-column">
                        <h4>05/22/2024</h4>

                    </div>
                </div>

            </div>

        </div>

    </div>


    </div>
@endsection
