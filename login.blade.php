<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pendaftaran </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f63b8981, #3382ea59);
            
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 400px;
            padding: 2rem;
            text-align: center;
        }
        .login-card h3 {
            margin-bottom: 1.5rem;
            color: #f63b89ff;
            font-weight: 600;
        }
        .btn-login {
            background-color: #f63b89ff;
            color: white;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #f63b89ff;
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="https://i.pinimg.com/736x/58/c2/53/58c253f9dbde6c2ed7f74eedc4ddc7a2.jpg" 
         alt="Logo Klinik" 
         width="80" 
         class="rounded-circle mb-3">
    <h3><i class="bi bi-hospital"></i> Login Pendaftaran Klinik</h3>

    @if(session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-login w-100 mt-3">Masuk</button>
    </form>

    <p class="mt-4 text-muted" style="font-size: 14px;">
       welcome to pendaftaran klinik 
    </p>
</div>

</body>
</html>
