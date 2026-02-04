<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
</head>
<body>

<h2>Login Admin</h2>

@if ($errors->any())
    <p style="color:red;">
        {{ $errors->first() }}
    </p>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div>
        <label>Username</label><br>
        <input type="text" name="username" required>
    </div>

    <br>

    <div>
        <label>Password</label><br>
        <input type="password" name="password" required>
    </div>

    <br>

    <button type="submit">Login</button>
</form>

</body>
</html>
