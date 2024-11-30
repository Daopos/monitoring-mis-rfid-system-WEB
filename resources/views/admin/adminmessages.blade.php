@extends('layouts.adminlayout')

@section('styles')
    <style>
        .messages-container {
            display: flex;
            height: 80vh;
            background-color: #f7f7f7;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .user-list {
            width: 30%;
            border-right: 1px solid #ddd;
            padding: 20px;
            background-color: #ffffff;
            overflow-y: auto;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .user-item:hover {
            background-color: #f1f1f1;
        }

        .user-item.active {
            background-color: #e7e7e7;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-info .name {
            font-weight: bold;
            color: #333;
        }

        .user-info .last-message {
            color: #888;
            font-size: 12px;
        }

        .message-window {
            width: 70%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: #ffffff;
        }

        .message-header {
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            max-height: 60vh;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 60%;
        }

        .message.sent {
            background-color: #d1e7dd;
            margin-left: auto;
        }

        .message.received {
            background-color: #f8d7da;
        }

        .message-form {
            display: flex;
        }

        .message-form input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }

        .message-form button {
            padding: 10px 20px;
            border-radius: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message-form button:hover {
            background-color: #0056b3;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="messages-container">
            <!-- Homeowners List (Left Sidebar) -->
            <div class="user-list">
                @foreach($homeOwners as $hOwner)
                    <div class="user-item {{ isset($homeOwner) && $homeOwner->id == $hOwner->id ? 'active' : '' }}"
                         onclick="window.location.href='{{ route('admin.messages.show', $hOwner->id) }}'">
                        <div class="user-info">
                            <span class="name">{{ $hOwner->fname }} {{ $hOwner->lname }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Window (Right Side) -->
            @if(isset($homeOwner))
                <div class="message-window">
                    <h4>Conversation with {{ $homeOwner->fname }} {{ $homeOwner->lname }}</h4>

                    <!-- Messages list -->
                    <div class="messages">
                        @foreach($messages as $message)
                            <div class="message {{ $message->sender_role == 'admin' ? 'sent' : 'received' }}">
                                {{ $message->message }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Message form -->
                    <form class="message-form" action="{{ route('admin.messages.send', $homeOwner->id) }}" method="POST">
                        @csrf
                        <input type="text" name="message" placeholder="Type a message..." required>
                        <button type="submit">Send</button>
                    </form>
                </div>
            @else
                <div class="message-window">
                    <p>Please select a homeowner to view and send messages.</p>
                </div>
            @endif
        </div>

    </div>
@endsection
