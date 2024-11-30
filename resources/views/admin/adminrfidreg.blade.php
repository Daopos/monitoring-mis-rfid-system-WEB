@extends('layouts.adminlayout')

@section('styles')
    <link href="{{ asset('/css/adminrfidreg.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="registration-body">
        <div class="form-box">
            <h1>Registration</h1>
            <form method="POST" action="{{ route('admin.homeownerformreg') }}" enctype="multipart/form-data">
                @csrf

                <!-- Image Upload -->
                <div class="form-group">
                    <label for="image">Image</label>
                    <input class="form-control" id="image" type="file" name="image">
                </div>

                <!-- Document Image Upload -->
                <div class="form-group">
                    <label for="document_image">Document Image</label>
                    <input class="form-control" id="document_image" type="file" name="document_image">
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input class="form-control" id="fname" type="text" name="fname" value="{{ old('fname') }}" required>
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input class="form-control" id="lname" type="text" name="lname" value="{{ old('lname') }}" required>
                </div>

                <!-- Middle Name (optional) -->
                <div class="form-group">
                    <label for="mname">Middle Name</label>
                    <input class="form-control" id="mname" type="text" name="mname" value="{{ old('mname') }}">
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input class="form-control" id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control" id="password" type="password" name="password" required>
                </div>

                <!-- Birthdate -->
                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input class="form-control" id="birthdate" type="date" name="birthdate" value="{{ old('birthdate') }}" required>
                </div>

                <!-- Gender Dropdown -->
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option selected disabled>Select Gender</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Phase Dropdown -->
                <div class="form-group">
                    <label for="phase">Phase</label>
                    <select class="form-select" id="phase" name="phase" required>
                        <option selected disabled>Select Phase</option>
                        <option value="Phase 1" {{ old('phase') == 'Phase 1' ? 'selected' : '' }}>Phase 1</option>
                        <option value="Phase 2" {{ old('phase') == 'Phase 2' ? 'selected' : '' }}>Phase 2</option>
                        <option value="Phase 3" {{ old('phase') == 'Phase 3' ? 'selected' : '' }}>Phase 3</option>
                    </select>
                </div>

                <!-- Block Dropdown (1 to 50) -->
                <div class="form-group">
                    <label for="block">Block</label>
                    <select class="form-select" id="block" name="block" required>
                        <option selected disabled>Select Block</option>
                        @for ($i = 1; $i <= 50; $i++)
                            <option value="Block {{ $i }}" {{ old('block') == "Block $i" ? 'selected' : '' }}>Block {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Lot Dropdown (1 to 50) -->
                <div class="form-group">
                    <label for="lot">Lot</label>
                    <select class="form-select" id="lot" name="lot" required>
                        <option selected disabled>Select Lot</option>
                        @for ($i = 1; $i <= 50; $i++)
                            <option value="Lot {{ $i }}" {{ old('lot') == "Lot $i" ? 'selected' : '' }}>Lot {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- RFID (optional) -->
                <div class="form-group">
                    <label for="rfid">RFID</label>
                    <input class="form-control" id="rfid" type="text" name="rfid" value="{{ old('rfid') }}">
                </div>



                <!-- Submit Button -->
                <input class="btn btn-primary mt-3" type="submit" value="Submit">
            </form>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <script>
                toastr.error('There are validation errors. Please check your input.', 'Error!');
            </script>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Success!');
            </script>
        @endif
    </div>
@endsection
