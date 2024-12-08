<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="shortcut icon" href="/img/agllogo.png" type="image/x-icon">

    <link rel="stylesheet" href="/css/adminlogin.css">
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
</head>

<body>
    <div class="logo">
        <img src="/img/logo.png" alt="">
    </div>

    <div class="building">
        <img src="/img/building.png" alt="" width="1200">
    </div>
    <div class="logincard">
        <form method="POST" action="{{ route('guard.login') }}">
            @csrf
            <h1>Guard</h1>
            <input name="username" type="text" placeholder="Username"  autocomplete="off">
            <input name="password" type="password" placeholder="Password"  autocomplete="off">
            <button type="submit">Log in</button>
        </form>
    </div>
    @if(session('error'))
    <script>
    toastr.error('{{ session('error') }}', 'Error!');
</script>

@endif
</body>

</html>
