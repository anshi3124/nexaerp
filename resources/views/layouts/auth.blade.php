<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NexaERP')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .auth-header .logo {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .auth-header .logo span { color: #ffd700; }
        .auth-header p { margin: 0; opacity: 0.85; font-size: 0.9rem; }
        .auth-body { padding: 2rem; }
        .form-control {
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            background: #f8fafc;
            color: #667eea;
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 0.7rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(102,126,234,0.4);
        }
        .demo-card {
            background: #f0f4ff;
            border: 1.5px dashed #667eea;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .demo-card h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .demo-card .credential {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 0.2rem;
        }
        .demo-card .credential span:first-child { color: #64748b; }
        .demo-card .credential span:last-child {
            font-weight: 600;
            color: #334155;
            font-family: monospace;
        }
        .auth-footer {
            text-align: center;
            padding: 1rem 2rem;
            background: #f8fafc;
            font-size: 0.8rem;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #374151;
            margin-bottom: 0.4rem;
        }
        .invalid-feedback { font-size: 0.82rem; }
        .alert { border-radius: 8px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        @yield('content')
        <div class="auth-footer">
            &copy; {{ date('Y') }} NexaERP — Business Management Platform
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>