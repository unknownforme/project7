<?php 
class publicController {

    private $dir_backs;

    public function __construct($dir_backs) {
        $this->dir_backs = $dir_backs;
    }

    public function home() {
        require_once "models/home.php";
    }

    public function login($auth) {
        $last_email = filter_input(INPUT_GET, "email", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        require_once "models/login.php";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $auth->login($_POST['email'], $_POST['password']);
                echo 'User is logged in';
                header("location: " . $this->dir_backs . "overview");
                exit;

            }
            catch (\Delight\Auth\EmailNotVerifiedException | \Delight\Auth\InvalidPasswordException | \Delight\Auth\InvalidEmailException $e) {
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
                header("location: login?error=2&email=" . $email);
                exit();
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                die('Too many requests');
            }
        }
    }
}