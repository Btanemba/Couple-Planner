<?php

// Simple API entry point for Vercel
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Basic API endpoints
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove /api prefix if present
$path = preg_replace('/^\/api/', '', $path);

// Add debug info (remove in production)
error_log("API Debug - Path: " . $path . ", Method: " . $method . ", URI: " . $_SERVER['REQUEST_URI']);

switch ($path) {
    case '/debug':
        echo json_encode([
            'path' => $path,
            'method' => $method,
            'uri' => $_SERVER['REQUEST_URI'],
            'server' => $_SERVER
        ]);
        break;
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

    case '/login':
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
