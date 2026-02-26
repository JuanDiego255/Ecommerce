<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $tenantTitle ?? config('app.name'))</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .public-nav { background: #fff; border-bottom: 1px solid #e9ecef; padding: .75rem 0; }
        .public-nav .brand { font-weight: 700; font-size: 1.1rem; color: #212529; text-decoration: none; }
        .public-footer { background: #fff; border-top: 1px solid #e9ecef; padding: 1.5rem 0; font-size: .85rem; color: #6c757d; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="public-nav">
        <div class="container">
            <a href="{{ url('/') }}" class="brand">
                {{ $tenantTitle ?? config('app.name') }}
            </a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="public-footer">
        <div class="container text-center">
            &copy; {{ date('Y') }} {{ $tenantTitle ?? config('app.name') }}
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
