@extends('layouts.guardlayout')

{{-- @section('title', 'Custom Orders') --}}

@section('styles')
    <link href="{{ asset('/css/guarddashboard.css') }}" rel="stylesheet">
    <style>

        .webcamContainer {
            background-color: #000;
            display: flex;
            justify-content: center
        }
        .webcam-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ensures the video is vertically centered */
        }

        #webcam {
            width: 640px; /* Increase width */
            height: 480px; /* Increase height */
            border: 2px solid #000; /* Optional: Add a border for better visibility */
            border-radius: 10px; /* Optional: Rounded corners */
        }
    </style>
@endsection
@section('content')
<div class="body-container">
    <div class="rfid-container">
        <h1>RFID MONITORING</h1>
        <div class="rfid-card">
            <h2 id="time-display"></h2>
            <h2 id="date-display"></h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @if (session('homeOwner'))
                <div class="homeowner-info" id="homeowner-info">
                    @if (session('homeOwner')->image_url)
                        <img src="{{ session('homeOwner')->image_url }}" alt="Homeowner Image" class="homeowner-image">
                    @endif
                    <h5>Name: <span id="owner-name">{{ session('homeOwner')->fname }} {{ session('homeOwner')->lname }}</span></h5>
                    <h5>Position: <span id="owner-position">{{ session('homeOwner')->position ?? 'Resident' }}</span></h5>
                    <h5>Phase no: <span id="owner-phase">{{ session('homeOwner')->phase }}</span></h5>
                </div>
                <script>
                    $(document).ready(function() {
                        // Show the homeowner info for 10 seconds
                        $('#homeowner-info').fadeIn();
                        setTimeout(function() {
                            $('#homeowner-info').fadeOut();
                        }, 10000);
                    });
                </script>
            @endif
            @if (session('visitor'))
    <div class="homeowner-info" id="visitor-info">
        @if (session('visitor')->image_url)
            <img src="{{ session('visitor')->image_url }}" alt="Visitor Image" class="homeowner-image">
        @endif
        <h5>Name: <span id="visitor-name">{{ session('visitor')->name }} </span></h5>
        <h5>Visiting Homeowner: <span id="visitor-homeowner">{{ session('homeowner')->fname ?? 'Unknown' }} {{ session('homeowner')->lname ?? '' }}</span></h5>
        {{-- <h5>Entry Time: <span id="visitor-in-time">{{ session('visitor')->created_at ?? 'N/A' }}</span></h5> --}}
    </div>
    <script>
        $(document).ready(function() {
            $('#visitor-info').fadeIn();
            setTimeout(function() {
                $('#visitor-info').fadeOut();
            }, 10000);
        });
    </script>
@endif
@if (session('household'))
    <div class="homeowner-info" id="household-info">
        @if (session('household')->image_url)
            <img src="{{ session('household')->image_url }}" alt="Household Image" class="household-image">
        @endif
        <h5>Name: <span id="household-name">{{ session('household')->name }}</span></h5>
        <h5>Head of the family: <span id="household-address">{{ session('household')->homeOwner->fname ?? 'N/A' }} {{ session('household')->homeOwner->lname ?? 'N/A' }}</span></h5>
        {{-- <h5>Entry Time: <span id="household-in-time">{{ session('householdGateMonitor')->in ?? 'N/A' }}</span></h5> --}}
    </div>
    <script>
        $(document).ready(function() {
            $('#household-info').fadeIn();
            setTimeout(function() {
                $('#household-info').fadeOut();
            }, 10000);
        });
    </script>
@endif
        @endif
    </div>
    <form id="rfid-form" method="POST" action="{{ route('gate-monitors.store') }}">
        @csrf
        <input type="hidden" name="owner_id" id="owner_id" value="">
        <input type="hidden" id="captured-image" name="captured_image">
    </form>
</div>
<div class="webcamContainer">
    <video id="webcam" width="320" height="240" autoplay></video>
</div>
    <canvas id="canvas" style="display: none"></canvas>
<input type="hidden" id="captured-image" name="captured_image">
<script>
    $(document).ready(function() {
        var rfidBuffer = '';

        // Function to handle RFID scan
        function handleRFIDScan(ownerId) {
            console.log('Scanned RFID:', ownerId); // Log the RFID data
            $('#owner_id').val(ownerId); // Set the scanned RFID value in the hidden input

            // Automatically capture the image when RFID is scanned
            captureImage();

            $('#rfid-form').submit(); // Submit the form
        }

        // Listen for keypress events
        $(window).on('keypress', function(event) {
            console.log('Key pressed:', event.key); // Log each key press
            if (event.key === 'Enter') { // Assuming the RFID reader ends with Enter key
                console.log('Buffer before submit:', rfidBuffer); // Log buffer before submission
                handleRFIDScan(rfidBuffer.trim()); // Process and submit the RFID data
                rfidBuffer = ''; // Clear the buffer
            } else {
                rfidBuffer += event.key; // Add the scanned character to the buffer
            }
        });

        // Handle paste events if RFID reader supports it
        $(window).on('paste', function(event) {
            var pastedData = (event.originalEvent || event).clipboardData.getData('text');
            console.log('Pasted data:', pastedData); // Log pasted data
            handleRFIDScan(pastedData.trim()); // Process pasted data
        });

        // Live time and date
        function updateTime() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const timeString = now.toLocaleTimeString([], options);
            const dateString = now.toDateString();
            $('#time-display').text(timeString);
            $('#date-display').text(dateString);
        }

        setInterval(updateTime, 1000); // Update every second
        updateTime(); // Initial call to set time immediately
    });

    navigator.mediaDevices.getUserMedia({ video: true })
    .then(function(stream) {
        document.getElementById('webcam').srcObject = stream;
    })
    .catch(function(err) {
        console.error("Error accessing the webcam: ", err);
    });

    // Function to capture the image
    function captureImage() {
        var canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');
        var video = document.getElementById('webcam');

        // Draw the current frame from the video to the canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert the canvas to a data URL (base64 encoded image)
        var dataUrl = canvas.toDataURL('image/png');

        // Set the captured image to the hidden input
        document.getElementById('captured-image').value = dataUrl;

        // Optionally, display the image in an <img> element
        // var img = new Image();
        // img.src = dataUrl;
        // document.body.appendChild(img);  // For testing purposes
    }
</script>

@endsection
