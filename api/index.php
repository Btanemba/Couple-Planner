<?php

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// If this is an API endpoint (starts with /api/), serve API responses
if (strpos($path, '/api/') === 0) {
    // Remove /api prefix for API endpoints
    $apiPath = preg_replace('/^\/api/', '', $path);
    $apiPath = trim($apiPath, '/');
    $method = $_SERVER['REQUEST_METHOD'];

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    if ($method === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    switch ($apiPath) {
        case '/':
        case '':
            echo json_encode([
                'message' => 'Couple Planner API',
                'version' => '1.0.0',
                'status' => 'active'
            ]);
            break;

    case '/register':
    case 'register':
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed', 'received_method' => $method, 'path' => $path]);
            break;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        // Basic validation
        if (!isset($input['email']) || !isset($input['password']) || !isset($input['name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            break;
        }

        // Simulate successful registration
        echo json_encode([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => [
                'id' => rand(1000, 9999),
                'name' => $input['name'],
                'email' => $input['email']
            ]
        ]);
        break;

    case 'login':
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed', 'received_method' => $method, 'path' => $path]);
            break;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        // Basic validation
        if (!isset($input['email']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing email or password']);
            break;
        }

        // Simulate successful login
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'token' => 'dummy-jwt-token-' . time(),
            'user' => [
                'id' => 1234,
                'name' => 'Demo User',
                'email' => $input['email']
            ]
        ]);
        break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
    exit();
}

// For non-API requests, serve a simple welcome page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#764ba2">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icon-192.svg">
    <title>Couple Planner 💕</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .container { text-align: center; padding: 2rem; max-width: 400px; }
        h1 { font-size: 2.5rem; margin-bottom: 1rem; }
        p { font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9; }
        .btn {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 50px;
            border: 2px solid rgba(255,255,255,0.3);
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        .features { margin-top: 2rem; }
        .feature {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💕 Couple Planner</h1>
        <p>Plan amazing dates and trips together</p>

        <div id="auth-forms">
            <button class="btn" onclick="showLoginForm()">Login</button>
            <button class="btn" onclick="showRegisterForm()">Register</button>
        </div>
        <div id="login-form" style="display:none; margin-top:2rem;">
            <h3>Login</h3>
            <input type="email" id="loginEmail" placeholder="Email" style="margin-bottom:10px;width:100%;padding:10px;">
            <input type="password" id="loginPassword" placeholder="Password" style="margin-bottom:10px;width:100%;padding:10px;">
            <button class="btn" onclick="submitLogin()">Submit</button>
        </div>
        <div id="register-form" style="display:none; margin-top:2rem;">
            <h3>Register</h3>
            <input type="text" id="regName" placeholder="Name" style="margin-bottom:10px;width:100%;padding:10px;">
            <input type="email" id="regEmail" placeholder="Email" style="margin-bottom:10px;width:100%;padding:10px;">
            <input type="password" id="regPassword" placeholder="Password" style="margin-bottom:10px;width:100%;padding:10px;">
            <button class="btn" onclick="submitRegister()">Submit</button>
        </div>
        <div id="result" class="result" style="display:none;margin-top:20px;"></div>
        <div class="features" style="margin-top:2rem;">
            <div class="feature">🗓️ Plan romantic dates</div>
            <div class="feature">✈️ Organize trips together</div>
            <div class="feature">💝 Create shared memories</div>
            <div class="feature">📱 Install as app on your phone</div>
        </div>
    </div>

    <script>
        function showLoginForm() {
            document.getElementById('login-form').style.display = 'block';
            document.getElementById('register-form').style.display = 'none';
            document.getElementById('result').style.display = 'none';
        }
        function showRegisterForm() {
            document.getElementById('register-form').style.display = 'block';
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('result').style.display = 'none';
        }
        function showResult(message, isError = false) {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = message;
            resultDiv.className = isError ? 'result error' : 'result success';
            resultDiv.style.display = 'block';
        }
        async function submitRegister() {
            const name = document.getElementById('regName').value;
            const email = document.getElementById('regEmail').value;
            const password = document.getElementById('regPassword').value;
            if (!name || !email || !password) {
                showResult('Please fill in all registration fields', true);
                return;
            }
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    showResult(`✅ Registration successful! User: ${data.user?.name || name}`, false);
                } else {
                    showResult(`❌ Registration failed: ${data.message || data.error || 'Unknown error'}`, true);
                }
            } catch (error) {
                showResult(`❌ Connection error: ${error.message}`, true);
            }
        }
        async function submitLogin() {
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            if (!email || !password) {
                showResult('Please fill in all login fields', true);
                return;
            }
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    showResult(`✅ Login successful! Welcome: ${data.user?.name || 'User'}`, false);
                } else {
                    showResult(`❌ Login failed: ${data.message || data.error || 'Unknown error'}`, true);
                }
            } catch (error) {
                showResult(`❌ Connection error: ${error.message}`, true);
            }
        }
        // PWA installation prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            deferredPrompt = e;
            const installBtn = document.createElement('a');
            installBtn.href = '#';
            installBtn.className = 'btn';
            installBtn.textContent = '📱 Install App';
            installBtn.onclick = () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    deferredPrompt = null;
                    installBtn.style.display = 'none';
                });
            };
            document.querySelector('.container').appendChild(installBtn);
        });
    </script>
</body>
</html><?php
