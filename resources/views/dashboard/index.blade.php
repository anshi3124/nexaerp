<!DOCTYPE html>
<html>
<head>
    <title>Dashboard — NexaERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="mb-3">Welcome to NexaERP! 🎉</h2>
                <p>Logged in as: <strong>{{ auth()->user()->name }}</strong></p>
                <p>Email: <strong>{{ auth()->user()->email }}</strong></p>
                <p>Role: <strong>{{ auth()->user()->role->name ?? 'No Role' }}</strong></p>
                <hr>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="btn btn-danger">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</body>
</html>