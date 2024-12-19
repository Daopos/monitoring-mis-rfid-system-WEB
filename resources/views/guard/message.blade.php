@extends('layouts.guardlayout')

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
        #searchBar {
}

.new-message-indicator {
    width: 10px;
    height: 10px;
    background-color: red;
    border-radius: 50%;
    position: absolute;
    top: 5px;
    right: 5px;
}

.user-item {
    position: relative; /* Ensure the red dot appears in the top-right corner */
}

.user-item.active {
    background-color: #e7e7e7;
}

/* Hide user items that don't match the search */
.user-item.hidden {
    display: none;
}
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="messages-container">
            <!-- Homeowners List (Left Sidebar) -->
            <div class="user-list">
                    <form id="searchForm" class="mb-3">
                        <div class="input-group">
                            <input
                                type="text"
                                id="searchBar"
                                class="form-control"
                                placeholder="Search homeowners..."
                                name="query"
                            />
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                @foreach($homeOwners as $hOwner)
                    <div class="user-item {{ isset($homeOwner) && $homeOwner->id == $hOwner->id ? 'active' : '' }}{{ $hOwner->hasUnreadMessages('guard')  ? 'has-new-message' : '' }}"
                         onclick="window.location.href='{{ route('guard.messages.show', $hOwner->id) }}'">
                         <div class="user-info">
                            <span class="name">{{ $hOwner->fname }} {{ $hOwner->lname }}</span>
                            @if($hOwner->unreadMessagesCount('guard') > 0)
                            <span class="badge unread-badge">{{ $hOwner->unreadMessagesCount('admin') }}</span>
                        @endif
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
                            <div class="message {{ $message->sender_role == 'guard' ? 'sent' : 'received' }}">
                                <p>{{ $message->message }}</p>
                                 <!-- Display date and time -->
                            <small class="text-muted">
                                {{ $message->created_at->format('d M Y, h:i A') }}
                            </small>

                            <!-- Show "Seen" only for the admin messages that are seen -->
                            @if($message->sender_role === 'guard' && $message->is_seen)
                                <small class="text-success">Seen</small>
                            @endif
                            </div>

                        @endforeach
                    </div>

                    <!-- Message form -->
                    <form class="message-form" action="{{ route('guard.messages.send', $homeOwner->id) }}" method="POST">
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

    </div><script>
        document.addEventListener("DOMContentLoaded", function () {
           // Function to filter homeowners
           function filterHomeowners(event) {
               event.preventDefault(); // Prevent form from submitting and reloading the page
               const query = document.getElementById('searchBar').value.toLowerCase();
               const homeowners = document.querySelectorAll('.user-item');

               homeowners.forEach(function (homeowner) {
                   const name = homeowner.querySelector('.name').textContent.toLowerCase();
                   if (name.includes(query)) {
                       homeowner.style.display = ''; // Show matching items
                   } else {
                       homeowner.style.display = 'none'; // Hide non-matching items
                   }
               });
           }

           // Attach filter function to the form submit event
           const searchForm = document.getElementById('searchForm');
           searchForm.addEventListener('submit', filterHomeowners);

           function scrollToBottom() {
        const messagesContainer = document.querySelector('.messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Automatically scroll to the bottom when the page loads
    scrollToBottom();
       });


//    document.querySelector('.message-form').addEventListener('submit', function (e) {
//        e.preventDefault(); // Prevent form from reloading the page

//        const messageInput = this.querySelector('input[name="message"]');
//        const message = messageInput.value.trim();
//        if (!message) return;

//        fetch(this.action, {
//            method: 'POST',
//            headers: {
//                'Content-Type': 'application/json',
//                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//            },
//            body: JSON.stringify({ message }),
//        })
//            .then((response) => response.json())
//            .then((data) => {
//                // Add the new message to the message list
//                const messagesContainer = document.querySelector('.messages');
//                messagesContainer.innerHTML += `
//                    <div class="message sent">
//                        <p>${data.message}</p>
//                        <small class="text-muted">${new Date(data.created_at).toLocaleString()}</small>
//                    </div>
//                `;

//                messageInput.value = ''; // Clear the input
//            })
//            .catch((error) => console.error('Error:', error));
//    });

   </script>
@endsection
