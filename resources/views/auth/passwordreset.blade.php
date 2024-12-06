<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Alert for success or error -->
                <div id="alert-container"></div>

                <!-- Reset Password Form -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Reset Password</h1>
                        <form id="reset-password-form">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      document.getElementById('reset-password-form').addEventListener('submit', function(e) {
    e.preventDefault();  // Prevent the default form submission

    const form = this;  // 'this' refers to the form element
    const formData = new FormData(form);  // Create FormData object using the form

    fetch("{{ route('password.update') }}", {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,  // CSRF Token
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Log the response to check if it has the expected message

        let alertContainer = document.getElementById('alert-container');
        alertContainer.innerHTML = ''; // Clear any previous messages

        if (data.message) {
            const alertType = data.message && data.message.includes('reset') ? 'success' : 'danger';
    alertContainer.innerHTML = `
        <div class="alert alert-${alertType} alert-dismissible fade show" role="alert">
            ${data.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
    </script>
</body>
</html>
