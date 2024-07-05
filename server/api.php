<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once 'config.php';
require_once 'Users.php';
$usersObj = new Users($conn);
// Get the request method
$method = $_SERVER['REQUEST_METHOD'];
// Get the requested endpoint
$endpoint = $_SERVER['PATH_INFO'];
// Set the response content type
header('Content-Type: application/json');
// Process the request
switch ($method) {
    case 'GET':
        if ($endpoint === '/get/user') {
            $id = $_GET['mobile_number'] ?? null;
            if ($id) {
                $user = $usersObj->getUsersByMobile($id);
                if (isset($user['error'])) {
                    http_response_code(500);
                    echo json_encode(['error' => $user['error']]);
                } else {
                    if ($user) {
                        echo json_encode($user);
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'User not found',
                        'errorCode'=> 404
                    ]);
                    }
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Mobile number not provided']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
    case 'POST':
        if ($endpoint === '/set/user') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $usersObj->addUser($data);
            echo json_encode(['success' => $result]);
        }else if ($endpoint === '/login') {
            // Parse incoming JSON data
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate input
            if (!isset($data['mobile_number']) || !isset($data['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Mobile number or password not provided']);
                break;
            }

            // Attempt login
            $mobile_number = $data['mobile_number'];
            $password = $data['password'];
            $loginResult = $usersObj->login($mobile_number, $password);

            // Return login result
            echo json_encode($loginResult);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
        case 'PUT':
            if ($endpoint === '/update/user') {
                $headers = apache_request_headers();
                $token = $headers['Authorization'] ?? null;
                
                if ($token) {
                    // Verify access token
                    $decoded = $usersObj->verifyAccessToken($token);
                    if ($decoded) {
                        // Access token valid, proceed with update
                        $data = json_decode(file_get_contents('php://input'), true);
                        $result = $usersObj->updateUser($decoded->user_id, $data); // Example function to update user
                        echo json_encode(['success' => $result]);
                    } else {
                        http_response_code(401);
                        echo json_encode(['error' => 'Unauthorized']);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'Access token not provided']);
                }
            }
            break;
    
}
?>