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
        require_once "models/login.php";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $auth->login($_POST['email'], $_POST['password']);
                echo 'User is logged in';
                header("location:" . $this->dir_backs . "overview");

            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                die('Wrong email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                die('Wrong password');
            }
            catch (\Delight\Auth\EmailNotVerifiedException $e) {
                die('Email not verified');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                die('Too many requests');
            }
        }
    }
}