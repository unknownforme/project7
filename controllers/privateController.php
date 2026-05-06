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

    public function prisonerdossier($id = null) {
        if (!isset($id)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }

        $is_jailed = $this->db_obj->isCurrentlyJailed($id);

        $list = [
            "name" => "naam", 
            "bsn" => "bsn", 
            "nationality" => "nationaliteit", 
            "gender" => "gender", 
            "length" => "lengte", 
        ];
        $type_request = filter_input(INPUT_GET, "edit", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        if (!empty($type_request)) {
            if (!$this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
                header("location: " . $this->dir_backs . "prisonerdossier/$id"); 
                exit;
            }
        }

        
        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($type_request)) {
            $clean_input = [];
            $clean_input["date"] = filter_input(INPUT_POST, "date", FILTER_SANITIZE_SPECIAL_CHARS);

            foreach ($list as $key => $value) {
                $clean_input[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $clean_input['date'] = strtotime($clean_input['date']);
            $this->db_obj->updatePrisoner($id, $clean_input['name'], $clean_input['bsn'], $clean_input['nationality'], $clean_input['gender'], $clean_input['length'], $clean_input['date']);
            header("location: " . $this->dir_backs . "prisonerdossier/" . $id);
            exit;
        } 

        $prisoner_info = $this->db_obj->getPrisoner($id);
        $prisoner_info['date_of_birth'] = explode(" ", $prisoner_info['date_of_birth']);
        $prisoner_info['date_of_birth'] = $prisoner_info['date_of_birth'][0];
        $prisoner_history = $this->db_obj->getPrisonerArrests($id);
        require_once "models/prisonerdossier.php";
    }

    public function prisonerinfo($id) {
        $is_jailed = $this->db_obj->isCurrentlyJailed($id);
        require_once "models/prisonerinfo.php";
        var_dump($this->db_obj->getCellByPrisonerId($id));
    }

    public function checkups() {
        require_once "models/checkups.php";
    }

    public function addcheckup() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $date = intval(filter_input(INPUT_POST, "date", FILTER_SANITIZE_SPECIAL_CHARS));
            $info = intval(filter_input(INPUT_POST, "info", FILTER_SANITIZE_SPECIAL_CHARS));
            $prisoner_id = intval(filter_input(INPUT_POST, "prisoner_id", FILTER_SANITIZE_SPECIAL_CHARS));
            
        }
        require_once "models/addcheckup.php";
    }

    public function cipierrequests() {
        //move is 0, free is 1
        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
            header("location: " . $this->dir_backs . "home?error=3");
            exit;
        }
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        if (isset($id)) {
            $this->db_obj->fullfillRequest($id);
            header("location: " . $this->dir_backs . "cipierrequests");
            exit;
        }
        $all_requests = $this->db_obj->getAllRequests();
        require_once "models/cipierrequests.php";
    }

    public function moveprisoner ($id) {
        if (!isset($id)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // move prisoner
            $cell_id = intval(filter_input(INPUT_POST, "cell", FILTER_SANITIZE_SPECIAL_CHARS));
            if ($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
                $this->db_obj->movePrisoner($id, $cell_id);
            } else {
                // TODO: a cipier request
                $this->db_obj->addRequest(1, $id, $cell_id);
            }
            header("location: " . $this->dir_backs . "prisonerdossier/" . $id);
            exit;
        }
        $cells = $this->db_obj->getCells(true);
        require_once "models/moveprisoner.php";
    }

    public function freeprisoner($id) {
        if (!isset($id)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // free prisoner
            if ($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
                $this->db_obj->freePrisoner($id);
            } else {
                // TODO: a cipier request
                $this->db_obj->addRequest(0, $id);
            }
            header("location: " . $this->dir_backs . "prisonerdossier/" . $id);
            exit;
        }
        require_once "models/freeprisoner.php";
    }

    public function prisoners($search = null) {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $status = filter_input(INPUT_POST, "arrest_status", FILTER_SANITIZE_SPECIAL_CHARS);
            header("location: ". $this->dir_backs ."prisoners?arrest_status=" . $status);
        }
        $prisoner_status = filter_input(INPUT_GET, "arrest_status", FILTER_SANITIZE_SPECIAL_CHARS) ?? "arrested";
        $prisoners = $this->db_obj->getAllPrisoners($search, $prisoner_status);
        require_once "models/prisoners.php"; 
    }

    public function register() {
        $last_email = filter_input(INPUT_GET, "email", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        $last_username = filter_input(INPUT_GET, "username", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (strlen($_POST['password']) < 8 || $_POST['password'] == strtolower($_POST['password'])) {
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
                $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
                echo "your account was shorter than 8 characters or didnt contain an uppercase character";
                header("location: register?email=" . $email . "&username=" . $username . "&error=1");
                exit;
            }
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
        require_once "models/register.php";
    }

    public function updateaccount() {
        require_once "models/register.php";
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);

            if (!empty($password)) {
                try {
                    $this->auth->changePasswordWithoutOldPassword($password);
                    echo 'Password has been changed';
                }
                catch (\Delight\Auth\NotLoggedInException $e) {
                    die('Not logged in');
                }
                catch (\Delight\Auth\InvalidPasswordException $e) {
                    die('Invalid password(s)');
                }
                catch (\Delight\Auth\TooManyRequestsException $e) {
                    die('Too many requests');
                }
            }

            if (!empty($email)) {
                try {
                    $auth->changeEmail($email);
                }
                catch (\Delight\Auth\InvalidEmailException $e) {
                    die('Invalid email address');
                }
                catch (\Delight\Auth\UserAlreadyExistsException $e) {
                    die('Email address already exists');
                }
                catch (\Delight\Auth\EmailNotVerifiedException $e) {
                    die('Account not verified');
                }
                catch (\Delight\Auth\NotLoggedInException $e) {
                    die('Not logged in');
                }
                catch (\Delight\Auth\TooManyRequestsException $e) {
                    die('Too many requests');
                }
            }

            if (!empty($username)) {
                try {
                    $auth->changeUsername($username);

                    echo 'Username has been changed';
                }
                catch (\Delight\Auth\NotLoggedInException $e) {
                    die('Not logged in');
                }
                catch (\Delight\Auth\TooManyRequestsException $e) {
                    die('Too many requests');
                }
            }
        }

    }
    
    public function cells($wing = "A") {
        $wing = (in_array($wing, ["A", "B", "C"])) ? $wing : "A";
        $cells = $this->db_obj->getCellsAndPrisoners($wing);
        require_once "models/cells.php";   
    }
    
    public function history($search = null) {
        $history = $this->db_obj->getHistory($search);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (empty($_POST['search'])) {
                header("location: " . $this->dir_backs . "history");
                exit;
            }
            header("location: " . $this->dir_backs . "history/" . $_POST['search']);
            exit;
        }
        require_once "models/history.php";   
    }

    public function addprisoner() {
        //only boss and dev can go here
        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }
        $list = [
            "name" => "naam", 
            "bsn" => "bsn", 
            "nationality" => "nationaliteit", 
            "gender" => "gender", 
            "length" => "lengte", 
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $clean_input = [];
            $clean_input["date"] = filter_input(INPUT_POST, "date", FILTER_SANITIZE_SPECIAL_CHARS);

            foreach ($list as $key => $value) {
                $clean_input[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $clean_input['date'] = strtotime($clean_input['date']);
            $this->db_obj->addPrisoner($clean_input['name'], $clean_input['bsn'], $clean_input['nationality'], $clean_input['gender'], $clean_input['length'], $clean_input['date']);
        }
        
        require_once "models/addprisoner.php";
    }

    public function addarrest() {
        //only boss and dev can go here
        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }

        $list = [
            "bsn" => "bsn", 
            "reason" => "reden", 
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $cell = filter_input(INPUT_POST, 'cell', FILTER_SANITIZE_SPECIAL_CHARS);
            $bsn = intval(filter_input(INPUT_POST, 'bsn', FILTER_SANITIZE_SPECIAL_CHARS));
            $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_SPECIAL_CHARS);
            $time_jailed = strtotime(filter_input(INPUT_POST, 'time_jailed', FILTER_SANITIZE_SPECIAL_CHARS));
            $time_to_release = strtotime(filter_input(INPUT_POST, 'time_to_release', FILTER_SANITIZE_SPECIAL_CHARS));

            if (!isset($bsn, $cell, $reason, $time_jailed, $time_to_release) || $time_jailed > $time_to_release || $bsn == 0) {
                header("location: " . $this->dir_backs . "overview");
                exit;
            }
            $this->db_obj->addPrisonerHistory($bsn, $cell, $reason, $time_jailed, $time_to_release);

        }
        $cells = $this->db_obj->getCells(true);
        require_once "models/addarrest.php";
    }

    public function account() {

        $name = $this->auth->getUsername();
        $email = $this->auth->getEmail();

        require_once "models/account.php";
    }

    public function bevrijdcel() {
        
        require_once "models/freecel.php";
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
        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::ADMIN)) {
            header("location: home");
            exit;
        }

        $users = $this->db_obj->getAllUsers();

        require_once "models/users.php";
    }

    public function editroles($id = null) {
        //the function is before the check, but that doesnt matter as its called after the role check
        function addOrRemoveRole($auth, $id, $role, $remove) {
            if ($remove == TRUE) {
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
        if (!$this->auth->hasAnyRole(\Delight\Auth\Role::ADMIN)) {
            header("location: " . $this->dir_backs . "home");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            foreach($roles as $role => $delightrole) {
                $remove = TRUE;
                if (array_key_exists($role, $_POST)) {
                    $remove = FALSE;
                }
                addOrRemoveRole($this->auth, $id, $delightrole, $remove);
            }    
            header("location: " . $this->dir_backs . "users");
        }
        $user = $this->db_obj->getUser($id);
        require_once "models/editroles.php";
    }
}