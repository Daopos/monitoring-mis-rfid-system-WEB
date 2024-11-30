<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Monitors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<style>
    body {
        background: linear-gradient(180deg, #85C1E7 0%, #2F80ED 100%);
        min-height: 100vh;
        width: 100vw;
    }

    .body-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        padding: 20px;
    }

    .rfid-container {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .rfid-card {
        background-color: rgba(47, 128, 237, 0.8);
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin-top: 15px;
    }

    .homeowner-info {
        display: none;
        margin-top: 20px;
        background-color: rgba(47, 128, 237, 0.8);
        color: white;
        padding: 15px;
        border-radius: 10px;
    }

    .homeowner-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
    }
</style>
<body>
    <div class="body-container">
        <div class="rfid-container">
            <h1>RFID MONITORING</h1>
            <div class="rfid-card">
                <h2 id="time-display"></h2>
                <h2 id="date-display"></h2>
            </div>
            <pre>
            </pre>
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
                $('#homeowner-info').fadeIn();
                setTimeout(function() {
                    $('#homeowner-info').fadeOut();
                }, 10000);
            });
        </script>
    @endif
    @if (session('visitor'))
    <div class="visitor-info" id="visitor-info">
        @if (session('visitor')['image_url'])
            <img src="{{ session('visitor')['image_url'] }}" alt="Visitor Image" class="visitor-image">
        @endif
        <h5>Visitor Name: <span id="visitor-name">{{ session('visitor')['name'] ?? 'N/A' }} {{ session('visitor')['lname'] ?? 'N/A' }}</span></h5>
        <h5>Homeowner Name: <span id="homeowner-name">{{ session('homeowner')['fname'] ?? 'N/A' }} {{ session('homeowner')['lname'] ?? 'N/A' }}</span></h5>
        <h5>Purpose: <span id="visitor-purpose">{{ session('visitor')['purpose'] ?? 'N/A' }}</span></h5>
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
    <div class="household-info" id="household-info">
        @if (session('household')->image_url)
            <img src="{{ session('household')->image_url }}" alt="Household Image" class="household-image">
        @endif
        <h5>Name: <span id="household-name">{{ session('household')->name }}</span></h5>
        <h5>Phase no: <span id="household-phase">{{ session('household')->phase }}</span></h5>
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
</body>
</html>
