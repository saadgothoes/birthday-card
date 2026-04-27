<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Client</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #0f172a;
        color: #f1f5f9;
    }

    .navbar {
        background: #1e293b;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #334155;
    }

    .navbar h1 {
        font-size: 1.2rem;
        color: #6366f1;
    }

    .navbar a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .card {
        background: #1e293b;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #334155;
    }

    .card h2 {
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        color: #f1f5f9;
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
    }

    input:focus {
        border-color: #6366f1;
    }

    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
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
    }

    button:hover {
        background: #4f46e5;
    }

    .error {
        color: #f87171;
        font-size: 0.8rem;
        margin-top: -1rem;
        margin-bottom: 0.8rem;
    }

    .success {
        background: #dcfce7;
        color: #166534;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <h1>⚡ Admin Panel</h1>
        <a href="{{ route('admin.clients.index') }}">← Back to Clients</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>➕ Create New Client</h2>

            @if(session('success'))
            <div class="success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.clients.store') }}">
                @csrf

                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                @error('name') <p class="error">{{ $message }}</p> @enderror

                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required>
                @error('email') <p class="error">{{ $message }}</p> @enderror

                <div class="row">
                    <div>
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+92 300 0000000"
                            required>
                        @error('phone') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label>Age</label>
                        <input type="number" name="age" value="{{ old('age') }}" placeholder="25" required>
                        @error('age') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <label>City</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="Lahore" required>
                @error('city') <p class="error">{{ $message }}</p> @enderror

                <button type="submit">✅ Create Client & Send Email</button>
            </form>
        </div>
    </div>
</body>

</html>