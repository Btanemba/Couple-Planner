<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

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

// For non-API requests, serve the Laravel application
require_once __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
