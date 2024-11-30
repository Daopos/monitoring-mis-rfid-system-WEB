@extends('layouts.guardlayout')

{{-- @section('title', 'Custom Orders') --}}

@section('styles')
    <link href="{{ asset('/css/guarddashboard.css') }}" rel="stylesheet">
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
        @endif
    </div>
    <form id="rfid-form" method="POST" action="{{ route('gate-monitors.store') }}">
        @csrf
        <input type="hidden" name="owner_id" id="owner_id" value="">
    </form>
</div>
<script>
    $(document).ready(function() {
        var rfidBuffer = '';

        // Function to handle RFID scan
        function handleRFIDScan(ownerId) {
            console.log('Scanned RFID:', ownerId); // Log the RFID data
            $('#owner_id').val(ownerId); // Set the scanned RFID value in the hidden input
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
</script>
@endsection
