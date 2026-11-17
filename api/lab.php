<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

security_headers();

/*
 * Lab Playground API
 * GET  -> encode, decode, profile
 * POST -> input (raw JSON), welcome (AJAX form)
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    $action = isset($_GET['action']) && is_string($_GET['action']) ? $_GET['action'] : '';

    if ($action === 'encode') {
        $student = [
            'name' => 'Maria',
            'age' => 21,
            'course' => 'IT',
        ];

        json_response($student);
    }

    if ($action === 'decode') {
        $source = '{"name":"Maria","age":21,"email":"maria@example.com"}';
        $object = json_decode($source);
        $array = json_decode($source, true);

        if (!is_object($object) || !is_array($array)) {
            json_response(['status' => 'error', 'message' => 'The sample JSON could not be decoded.'], 500);
        }

        json_response([
            'object' => $object->name,
            'array' => $array['email'],
        ]);
    }

    if ($action === 'profile') {
        json_response([
            'id' => 1,
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'status' => 'active',
        ]);
    }

    if ($action === 'post') {
        json_response([
            'id' => 1,
            'title' => 'A quiet place to start',
            'body' => 'Small, consistent steps make a project easier to finish. This post comes from the same-origin demo API.',
            'userId' => 1,
        ]);
    }

    json_response([
        'status' => 'error',
        'message' => 'Unknown lab action. Use encode, decode, profile, or post.',
    ], 404);
}

if ($method !== 'POST') {
    header('Allow: GET, POST');
    json_response(['status' => 'error', 'message' => 'Method not allowed.'], 405);
}

$action = isset($_GET['action']) && is_string($_GET['action']) ? $_GET['action'] : '';
$input = json_body();

if ($action === 'input') {
    $username = isset($input['username']) && is_string($input['username']) ? trim($input['username']) : '';
    $password = isset($input['password']) && is_string($input['password']) ? trim($input['password']) : '';

    if ($username === '' || $password === '') {
        json_response([
            'status' => 'error',
            'message' => 'Send both username and password as JSON.',
        ], 422);
    }

    json_response([
        'username' => $username,
        'password' => $password,
        'output' => 'Username: ' . $username . PHP_EOL . 'Password: ' . $password,
    ]);
}

if ($action === 'welcome') {
    $name = isset($input['name']) && is_string($input['name']) ? trim($input['name']) : '';

    if ($name === '') {
        json_response([
            'status' => 'error',
            'message' => 'Please enter your name.',
        ], 422);
    }

    if (text_length($name) > 80) {
        json_response([
            'status' => 'error',
            'message' => 'Name must be 80 characters or fewer.',
        ], 422);
    }

    json_response([
        'status' => 'success',
        'message' => 'Welcome, ' . $name . '!',
    ]);
}

if ($action === 'post') {
    $title = isset($input['title']) && is_string($input['title']) ? trim($input['title']) : '';
    $body = isset($input['body']) && is_string($input['body']) ? trim($input['body']) : '';

    if ($title === '' || $body === '') {
        json_response([
            'status' => 'error',
            'message' => 'Send both a title and a body.',
        ], 422);
    }

    if (text_length($title) > 120 || text_length($body) > 500) {
        json_response([
            'status' => 'error',
            'message' => 'The title or body is too long.',
        ], 422);
    }

    json_response([
        'id' => 101,
        'title' => $title,
        'body' => $body,
        'userId' => 1,
        'created_at' => date(DATE_ATOM),
    ], 201);
}

json_response([
    'status' => 'error',
    'message' => 'Unknown lab action. Use input, welcome, or post.',
], 404);
