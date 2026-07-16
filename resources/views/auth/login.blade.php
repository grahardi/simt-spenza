<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIMT SMP Negeri 1 Turen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(160deg, #4b0082 0%, #2d1b4e 55%, #1a1030 100%);
            padding: 24px 16px;
        }
        .login-card {
            width: 100%; max-width: 380px;
            background: #fff; border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,.25);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(160deg, #4b0082, #6a1fb8);
            padding: 32px 24px 24px;
            text-align: center;
        }
        .login-logo {
            width: 88px; height: 88px; object-fit: contain;
            background: #fff; border-radius: 50%;
            padding: 8px; box-shadow: 0 6px 16px rgba(0,0,0,.25);
        }
        .login-header h1 {
            color: #fff; font-size: 16px; font-weight: 700;
            margin: 14px 0 2px;
        }
        .login-header p {
            color: rgba(255,255,255,.75); font-size: 12px; margin: 0;
        }
        .login-body { padding: 28px 28px 32px; }
        .login-body .form-label { font-size: 13px; font-weight: 600; color: #444; }
        .login-body .form-control {
            border-radius: 10px; padding: 10px 14px; font-size: 14px;
            border: 1px solid #e0e0e0;
        }
        .login-body .form-control:focus {
            border-color: #4b0082; box-shadow: 0 0 0 3px rgba(75,0,130,.12);
        }
        .btn-login {
            background: #4b0082; color: #fff; border: none;
            border-radius: 10px; padding: 11px; font-weight: 600; font-size: 14px;
            width: 100%; transition: background .15s ease;
        }
        .btn-login:hover { background: #3a0066; color: #fff; }
        .login-footer {
            text-align: center; font-size: 11px; color: #999;
            padding: 0 28px 22px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo-smpn1turen.jpg') }}" alt="Logo SMP Negeri 1 Turen" class="login-logo">
            <h1>SMP NEGERI 1 TUREN</h1>
            <p>Sistem Informasi Manajemen Terpadu</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger py-2 small mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nomor ID</label>
                    <input type="text" name="user" class="form-control" value="{{ old('user') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label small" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} SMP Negeri 1 Turen &middot; Kanti Raras Trus Dumadi
        </div>
    </div>
</body>
</html>
