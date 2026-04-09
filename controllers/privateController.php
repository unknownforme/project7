<?php 
class privateController {

    private $auth;
    private $path;
    private $db_obj;
    private $dir_backs;

    public function __construct($auth, $path, $db_obj, $dir_backs) {
        $this->auth = $auth;
        $this->path = $path;
        $this->db_obj = $db_obj;
        $this->dir_backs = $dir_backs;
    }

    public function edit($id = null) {
        if (!isset($id)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }
        require_once "models/edit.php";
    }


    public function prisoners($search = null) {
        $prisoners = $this->db_obj->getAllPrisoners($search);
        require_once "models/prisoners.php";
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
    
    public function cells() {
        require_once "models/cells.php";   
    }
    
    public function history($search = null) {
        $history = $this->db_obj->getHistory();
        require_once "models/cells.php";   
    }

    public function account() {

        $name = $this->auth->getUsername();
        $email = $this->auth->getEmail();

        require_once "models/account.php";
    }

    public function editroles($id) {
        if(!$this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            header("location:" . $this->dir_backs . "overview");
        }

        try {
            $this->auth->admin()->addRoleForUserById($id, \Delight\Auth\Role::ADMIN);
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown user ID');
        }
    }

    public function logout() {
        $this->auth->logOut();
        header("location: home");
        exit;
    }

    public function overview($search_query = null) {
        require_once "models/overview.php";
    }
}