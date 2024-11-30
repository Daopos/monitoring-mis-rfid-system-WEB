@extends('layouts.adminlayout')

@section('styles')
    <link href="{{ asset('/css/adminrfidreg.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="registration-body">
        <div class="form-box">
            <h1>{{ isset($homeOwner) ? 'Edit Homeowner' : 'Registration' }}</h1>
            <form method="POST" action="{{ isset($homeOwner) ? route('homeowner.update', $homeOwner->id) : route('admin.homeownerformreg') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($homeOwner))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="image">Image</label>
                    <input class="form-control" id="image" type="file" name="image">
                    @if(isset($homeOwner) && $homeOwner->image)
                        <img src="{{ asset('storage/' . $homeOwner->image) }}" alt="Homeowner Image" class="img-thumbnail mt-2" style="width: 100px; height: auto;">
                    @endif
                </div>

                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input class="form-control" id="fname" type="text" name="fname" value="{{ old('fname', $homeOwner->fname ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input class="form-control" id="lname" type="text" name="lname" value="{{ old('lname', $homeOwner->lname ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input class="form-control" id="phone" type="text" name="phone" value="{{ old('phone', $homeOwner->phone ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $homeOwner->email ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control" id="password" type="password" name="password" placeholder="Leave blank to keep current password">
                </div>

                <div class="form-group">
                    <label for="rfid">RFID</label>
                    <input class="form-control" id="rfid" type="text" name="rfid" value="{{ old('rfid', $homeOwner->rfid ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input class="form-control" id="birthdate" type="date" name="birthdate" value="{{ old('birthdate', $homeOwner->birthdate ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="phase">Phase</label>
                    <select class="form-select" name="phase">
                        <option value="" disabled {{ old('phase', $homeOwner->phase ?? '') === '' ? 'selected' : '' }}>Select Phase</option>
                        <option value="1" {{ old('phase', $homeOwner->phase ?? '') == 1 ? 'selected' : '' }}>One</option>
                        <option value="2" {{ old('phase', $homeOwner->phase ?? '') == 2 ? 'selected' : '' }}>Two</option>
                        <option value="3" {{ old('phase', $homeOwner->phase ?? '') == 3 ? 'selected' : '' }}>Three</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="" disabled {{ old('gender', $homeOwner->gender ?? '') === '' ? 'selected' : '' }}>Select Gender</option>
                        <option value="1" {{ old('gender', $homeOwner->gender ?? '') == 1 ? 'selected' : '' }}>Male</option>
                        <option value="2" {{ old('gender', $homeOwner->gender ?? '') == 2 ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="position">Position</label>
                    <input class="form-control" id="position" type="text" name="position" value="{{ old('position', $homeOwner->position ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="block">Block</label>
                    <input class="form-control" id="block" type="text" name="block" value="{{ old('block', $homeOwner->block ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="lot">Lot</label>
                    <input class="form-control" id="lot" type="text" name="lot" value="{{ old('lot', $homeOwner->lot ?? '') }}">
                </div>

                {{-- <div class="form-group">
                    <label for="number">House Number</label>
                    <input class="form-control" id="number" type="text" name="number" value="{{ old('number', $homeOwner->number ?? '') }}">
                </div> --}}

                <input class="btn btn-primary mt-3" type="submit" value="{{ isset($homeOwner) ? 'Update' : 'Submit' }}" />
            </form>
        </div>

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

        @if(session('success'))
            <script>
                toastr.success('{{ session('success') }}', 'Success!');
            </script>
        @endif
    </div>
@endsection
