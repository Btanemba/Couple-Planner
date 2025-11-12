<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Couple Planner 💕')</title>

    <!-- Mobile-first responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mobile-first base styles */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: #333;
        }

        .mobile-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .status-bar {
            background: #f8f9fa;
            padding: 8px 16px;
            font-size: 12px;
            color: #666;
            border-bottom: 1px solid #e9ecef;
        }

        .header {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            padding: 20px 16px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 16px;
            padding-bottom: 80px; /* Space for bottom nav */
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: #333;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-item {
            text-align: center;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 600;
            color: #ff6b6b;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .action-btn {
            padding: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
            transition: transform 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
        }

        .list-content {
            flex: 1;
        }

        .list-title {
            font-weight: 500;
            margin: 0 0 4px 0;
        }

        .list-subtitle {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            background: white;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
        }

        .nav-item {
            text-align: center;
            text-decoration: none;
            color: #666;
            font-size: 12px;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-item.active {
            color: #ff6b6b;
            background: #ffe6e6;
        }

        .nav-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .relationship-duration {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #ffeaa7, #fab1a0);
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .duration-main {
            font-size: 32px;
            font-weight: 600;
            color: #333;
        }

        .duration-subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 8px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-info {
            background: #cce7ff;
            border: 1px solid #b8daff;
            color: #004085;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        <div class="status-bar">
            <span>Couple Planner</span>
            <span style="float: right;">{{ now()->format('H:i') }}</span>
        </div>

        @yield('content')

        <nav class="bottom-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-icon">🏠</div>
                <div>Home</div>
            </a>
            <a href="{{ route('dates.index') }}" class="nav-item {{ request()->routeIs('dates.*') ? 'active' : '' }}">
                <div class="nav-icon">💕</div>
                <div>Dates</div>
            </a>
            <a href="{{ route('trips.index') }}" class="nav-item {{ request()->routeIs('trips.*') ? 'active' : '' }}">
                <div class="nav-icon">✈️</div>
                <div>Trips</div>
            </a>
            <a href="{{ route('couple.profile') }}" class="nav-item {{ request()->routeIs('couple.*') ? 'active' : '' }}">
                <div class="nav-icon">👫</div>
                <div>Profile</div>
            </a>
        </nav>
    </div>

    <script>
        // Mobile app utilities
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent zoom on iOS
            document.addEventListener('gesturestart', function (e) {
                e.preventDefault();
            });

            // Handle back button
            if (window.history && window.history.pushState) {
                window.addEventListener('popstate', function() {
                    // Handle Android back button
                });
            }
        });
    </script>
</body>
</html>
