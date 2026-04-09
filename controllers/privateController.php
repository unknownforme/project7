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
        require_once "models/history.php";   
    }

    public function account() {

        $name = $this->auth->getUsername();
        $email = $this->auth->getEmail();

        require_once "models/account.php";
    }

    public function logout() {
        $this->auth->logOut();
        header("location: home");
        exit;
    }

    public function overview($search_query = null) {
        require_once "models/overview.php";
    }

    public function users() {
        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
            header("location: home");
            exit;
        }

        $users = $this->db_obj->getAllUsers();

        require_once "models/users.php";
    }

    public function editroles($id = null) {
        //the function is before the check, but that doesnt matter as its called after the role check
        function addOrRemoveRole($auth, $id, $role, $remove) {
            if ($remove == true) {
                try {
                    $auth->admin()->removeRoleForUserById($id, $role);
                }
                catch (\Delight\Auth\UnknownIdException $e) {
                    die('Unknown user ID');
                }
            } else {
                try {
                    $auth->admin()->addRoleForUserById($id, $role);
                }
                catch (\Delight\Auth\UnknownIdException $e) {
                    die('Unknown user ID');
                }
            }
        }
        $roles = [
            "director" => \Delight\Auth\Role::DIRECTOR,
            "admin" => \Delight\Auth\Role::ADMIN,
            "cipier" => \Delight\Auth\Role::EMPLOYEE,
        ];
        
        //only boss and dev can go here
        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            foreach($roles as $role => $delightrole) {
                $remove = TRUE;
                if (in_array($role, $_POST, true)) {
                    $remove = FALSE;
                }
                addOrRemoveRole($this->auth, $id, $role, $remove);
            }    
        }
        $user = $this->db_obj->getUser($id);
        require_once "models/editroles.php";
    }
}