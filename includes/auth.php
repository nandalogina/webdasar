 
<?php
session_start();

class Auth {
    private $db;
    private $conn;

    public function __construct() {
        require_once 'config/database.php';
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function register($username, $email, $password, $fullname) {
        try {
            // Check if user already exists
            $check_query = "SELECT id FROM users WHERE email = :email OR username = :username";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(":email", $email);
            $check_stmt->bindParam(":username", $username);
            $check_stmt->execute();

            if($check_stmt->rowCount() > 0) {
                return "Username or Email already exists!";
            }

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $query = "INSERT INTO users (username, email, password, fullname, created_at) 
                      VALUES (:username, :email, :password, :fullname, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":fullname", $fullname);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $exception) {
            return "Error: " . $exception->getMessage();
        }
    }

    public function login($username, $password) {
        try {
            $query = "SELECT id, username, email, password, fullname, role FROM users 
                      WHERE username = :username OR email = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            if($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['fullname'] = $row['fullname'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['logged_in'] = true;
                    return true;
                }
            }
            return false;
        } catch(PDOException $exception) {
            return false;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>