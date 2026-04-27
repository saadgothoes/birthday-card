<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #0f172a;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .card {
        background: #1e293b;
        padding: 2.5rem;
        border-radius: 12px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    }

    h2 {
        color: #f1f5f9;
        text-align: center;
        margin-bottom: 0.5rem;
        font-size: 1.6rem;
    }

    p.sub {
        color: #64748b;
        text-align: center;
        margin-bottom: 2rem;
        font-size: 0.9rem;
    }

    label {
        display: block;
        color: #94a3b8;
        margin-bottom: 0.4rem;
        font-size: 0.85rem;
    }

    input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 8px;
        color: #f1f5f9;
        font-size: 0.95rem;
        margin-bottom: 1.2rem;
        outline: none;
        transition: border 0.2s;
    }

    input:focus {
        border-color: #6366f1;
    }

    button {
        width: 100%;
        padding: 0.85rem;
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s;
    }

    button:hover {
        background: #4f46e5;
    }

    .error {
        background: #fee2e2;
        color: #b91c1c;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }
    </style>
</head>

<body>
    <div class="card">
        <h2>🔐 Super Admin</h2>
        <p class="sub">Sign in to your admin panel</p>

        @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>

            <button type="submit">Login →</button>
        </form>
    </div>
</body>

</html>