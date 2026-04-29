<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Clients List</title>
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
        background: #6366f1;
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .container {
        padding: 2rem;
    }

    .success {
        background: #dcfce7;
        color: #166534;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .warning {
        background: #fef3c7;
        color: #92400e;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #1e293b;
        border-radius: 10px;
        overflow: hidden;
    }

    th {
        background: #334155;
        padding: 1rem;
        text-align: left;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #334155;
        font-size: 0.9rem;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .badge {
        background: #6366f1;
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 99px;
        font-size: 0.75rem;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <h1>⚡ Clients</h1>
        <a href="{{ route('admin.clients.create') }}">➕ Add Client</a>
    </nav>

    <div class="container">
        @if(session('success'))
        <div class="success">{{ session('success') }}</div>
        @endif

        @if(session('warning'))
        <div class="warning">{{ session('warning') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Age</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $i => $client)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->city }}</td>
                    <td>{{ $client->age }}</td>
                    <td><span class="badge">{{ $client->role }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#64748b; padding:2rem;">
                        No clients yet. Create one!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
