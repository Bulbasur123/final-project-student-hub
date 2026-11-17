<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/tasks.php';

security_headers();

/*
 * TaskFlow API
 * GET  -> current board, statistics, and CSRF token
 * POST -> add, toggle, delete, or clear completed tasks
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    try {
        $tasks = load_tasks();

        json_response([
            'ok' => true,
            'tasks' => $tasks,
            'stats' => task_stats($tasks),
            'csrf_token' => csrf_token(),
        ]);
    } catch (Throwable $exception) {
        error_log('Student Hub tasks: ' . $exception->getMessage());
        json_response([
            'ok' => false,
            'error' => 'Task storage is unavailable. Please try again.',
        ], 500);
    }
}

if ($method !== 'POST') {
    header('Allow: GET, POST');
    json_response(['ok' => false, 'error' => 'Method not allowed.'], 405);
}

$input = $_POST;
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (stripos($contentType, 'application/json') !== false) {
    $input = json_body();
}

$submittedToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($input['csrf'] ?? '');

if (!verify_csrf(is_string($submittedToken) ? $submittedToken : null)) {
    json_response([
        'ok' => false,
        'error' => 'Your session expired. Refresh the page and try again.',
    ], 403);
}

$action = isset($input['action']) && is_string($input['action']) ? $input['action'] : '';
$message = 'Task updated.';
$createdId = null;

try {
    $tasks = load_tasks();

    switch ($action) {
        case 'add':
            $title = isset($input['title']) && is_string($input['title']) ? trim($input['title']) : '';

            if ($title === '') {
                throw new InvalidArgumentException('Give your task a title.');
            }

            if (text_length($title) > TASKFLOW_MAX_TITLE_LENGTH) {
                throw new InvalidArgumentException('Keep the title under 120 characters.');
            }

            if (count($tasks) >= TASKFLOW_MAX_TASKS) {
                throw new InvalidArgumentException('This list is full. Delete a task first.');
            }

            $priority = isset($input['priority']) && is_string($input['priority'])
                ? strtolower($input['priority'])
                : 'medium';

            if (!in_array($priority, TASKFLOW_PRIORITIES, true)) {
                $priority = 'medium';
            }

            $due = isset($input['due']) && is_string($input['due']) ? trim($input['due']) : '';

            if ($due === '') {
                $due = null;
            } elseif (!is_valid_date($due)) {
                throw new InvalidArgumentException('Enter a valid due date.');
            }

            $createdId = bin2hex(random_bytes(8));
            $tasks[] = [
                'id' => $createdId,
                'title' => $title,
                'priority' => $priority,
                'due' => $due,
                'done' => false,
                'created_at' => date(DATE_ATOM),
            ];
            $message = 'Task added.';
            break;

        case 'toggle':
            $id = isset($input['id']) && is_string($input['id']) ? $input['id'] : '';
            $toggled = false;

            foreach ($tasks as &$task) {
                if ($task['id'] === $id) {
                    $task['done'] = !$task['done'];
                    $message = $task['done'] ? 'Task completed. Nice.' : 'Task moved back to your list.';
                    $toggled = true;
                    break;
                }
            }
            unset($task);

            if (!$toggled) {
                throw new InvalidArgumentException('That task could not be found.');
            }
            break;

        case 'delete':
            $id = isset($input['id']) && is_string($input['id']) ? $input['id'] : '';
            $before = count($tasks);

            $tasks = array_values(array_filter($tasks, static function (array $task) use ($id): bool {
                return $task['id'] !== $id;
            }));

            if (count($tasks) === $before) {
                throw new InvalidArgumentException('That task could not be found.');
            }

            $message = 'Task deleted.';
            break;

        case 'clear_completed':
            $before = count($tasks);

            $tasks = array_values(array_filter($tasks, static function (array $task): bool {
                return empty($task['done']);
            }));

            if (count($tasks) === $before) {
                throw new InvalidArgumentException('There are no completed tasks to clear.');
            }

            $message = 'Completed tasks cleared.';
            break;

        default:
            throw new InvalidArgumentException('Unknown action.');
    }

    $tasks = sort_tasks($tasks);
    save_tasks($tasks);

    $response = [
        'ok' => true,
        'tasks' => $tasks,
        'stats' => task_stats($tasks),
        'message' => $message,
    ];

    if ($createdId !== null) {
        $response['created_id'] = $createdId;
    }

    json_response($response);
} catch (InvalidArgumentException $exception) {
    json_response(['ok' => false, 'error' => $exception->getMessage()], 422);
} catch (Throwable $exception) {
    error_log('Student Hub tasks: ' . $exception->getMessage());
    json_response([
        'ok' => false,
        'error' => 'Task storage is unavailable. Please try again.',
    ], 500);
}
