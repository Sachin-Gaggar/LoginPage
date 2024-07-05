<?php
class Users
{
    private $conn;
    
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    
    public function getUsersByMobile($id)
    {
        $query = "SELECT * FROM Users WHERE mobile_number = $id";
        $result = mysqli_query($this->conn, $query);
        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            return ['error' => mysqli_error($this->conn)];
        }
    }
    
    public function addUser($data)
    {
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $mobile_number = $data['mobile_number'];
        $password = $data['password'];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO Users (first_name, last_name, mobile_number, password) 
                  VALUES ('$first_name', '$last_name', '$mobile_number', '$hashed_password')";
        $result = mysqli_query($this->conn, $query);
        if ($result) {
            return true;
        } else {
            if (mysqli_errno($this->conn) == 1062) {
                return ['error' => 'Mobile number already exists'];
            } else {
                return ['error' => mysqli_error($this->conn)];
            }
        }
    }
    
    public function login($mobile_number, $password)
    {
        $user = $this->getUsersByMobile($mobile_number);
    
        if (isset($user['error'])) {
            return ['error' => $user['error']];
        }
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $payload = ['user_id' => $user['id'], 'mobile_number' => $user['mobile_number']];
                $secret_key = 'myKeyForEncoding';
                $access_token = $this->generateJWT($payload, $secret_key);
                
                return ['success' => true, 'access_token' => $access_token,"data"=>["first_name"=>$user["first_name"],"last_name"=>$user["last_name"]]];
            } else {
                return ['error' => 'Invalid password'];
            }
        } else {
            return ['error' => 'User not found'];
        }
    }
    public function updateUser($user_id, $data)
    {
        // Example function to update user details
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        
        $query = "UPDATE Users SET first_name = ?, last_name = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $first_name, $last_name, $user_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyAccessToken($token)
    {
        $secret_key = 'myKeyForEncoding';
        try {
            $decoded = JWT::decode($token, $secret_key, array('HS256'));
            return $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
    private function generateJWT($payload, $secret_key)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret_key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

}
?>
