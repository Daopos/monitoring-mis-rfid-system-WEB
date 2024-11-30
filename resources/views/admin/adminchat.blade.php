@extends('layouts.adminlayout')

@section('title', 'Chat with Home Owner')

@section('styles')
    <style>
        .chat-box {
            max-height: 70vh;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
        }
        .message-sent {
            background-color: #d4edda;
            text-align: right;
            display: flex;
            justify-content: end;
        }
        .message-received {
            background-color: #f8d7da;
            text-align: left;
            display: flex;
            justify-content: start;
        }
        /* .chat-shape {
            width: 200px;
            background-color: aqua;
        } */
    </style>
@endsection

@section('content')
    <div class="container">
        <h2>Chat with Home Owner</h2>

        <!-- Chat Box -->
        <div class="chat-box" id="chat-box">
            @foreach($messages as $message)
                <div class="chat-message {{ $message->sender_role === 'home_owner' ? 'message-received' : 'message-sent' }}">
                    <div class="chat-shape">
                        <p>{{ $message->message }}</p>
                        <small>{{ $message->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Send Message Form -->
        <form action="{{ route('messages.send') }}" method="POST">
            @csrf
            <input type="hidden" name="home_owner_id" value="{{ $homeOwnerId }}">
            <div class="d-flex">
                <textarea class="form-control" name="message" rows="3" placeholder="Type your message..." required></textarea>
                <button type="submit" class="btn btn-primary w-25">Send</button>
            </div>
        </form>
    </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var chatBox = document.getElementById('chat-box');
                setTimeout(function() {
                    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom of the chat box
                }, 100); // Adjust the timeout as necessary
            });
        </script>
@endsection
