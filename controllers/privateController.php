<?php 
class privateController {

    private $auth;
    private $path;
    private $db_obj;

    public function __construct($auth, $path, $db_obj) {
        $this->auth = $auth;
        $this->path = $path;
        $this->db_obj = $db_obj;
    }

    public function overview($specific_prisoner = null) {
        
    }

    public function prisoners() {

    }

    public function register() {
        require_once "models/register.php";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username']);
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                die('Invalid email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                die('Invalid password');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                die('User already exists');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                die('Too many requests');
            }
        }
    }
    
    public function my_account() {
        
    }
}