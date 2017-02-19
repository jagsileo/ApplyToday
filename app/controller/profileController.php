<?php

/*
 * Controller file for tasks related to user. Contains functions for
 * create, edit user operations.
 */
include_once '../global.php';

//get the page that we want to load
$action = $_GET["action"];

//instantiate a ProfileController and route it
$pc = new ProfileController();
$pc->route($action);

class ProfileController {

    public function route($action){
        switch($action){
            case "addUser":
                $this->addUser();
                break;
            case "addTurker":
                $this->addTurker();
                break;
            case "userMgmt":
                $this->listUsers();
                break;

            case "saveProfile":
                $this->saveProfile();
                break;

            case "editUserType":
                $val = $_POST['submit'];
                $this->editUserType($val);
                break;


            default:
                header('Location: '.BASE_URL);
                exit();
        }
    }
     //to add a turker.
     public function addTurker() {
	$id = User::getLastRowId()+1;
	$uname = "Turker".$id;
	$email = $uname."@gmail.com";
        $user = new User();
        $user->set('fname', $uname);
        $user->set('lname', $uname);
        $user->set('uname', $uname);
        $user->set('email', $email);
        $user->set('password', "foobar");
        $user->set('user_type', 'applicant');
        $user->save();
	$pageName = "turker";
        include_once SYSTEM_PATH.'/view/turker.tpl';
    }

    //to add an user.
    public function addUser() {
        $user = new User();
        $user->set('fname', $_POST['fname']);
        $user->set('lname', $_POST['lname']);
        $user->set('uname', $_POST['uname']);
        $user->set('email', $_POST['email_id']);
        $user->set('password', $_POST['password']);
        $user->set('user_type', 'applicant');
        $user->save();
        //Create an event if admin request is made
        if($_POST['user_type'] == 'admin') {
            $latestUser = User::getLastRowId();
            $adminReq = new Event();
            $adminReq->set('user', $latestUser);
            $adminReq->set('event_type', '10'); //10 is request admin
            $adminReq->set('job', '');
            $adminReq->set('timestamp','');
            $adminReq->save();
        }

        $signUp = "success";
        header('Location: '.BASE_URL.'/home');
    }


    //helper function to convert a result object to an array
     public function getAllCols($row){
        $item = array();
        $item['id'] = $row->get('id');
        $item['fname'] = $row->get('fname');
        $item['lname'] = $row->get('lname');
        $item['uname'] = $row->get('uname');
        $item['email'] = $row->get('email');
        $item['user_type'] = $row->get('user_type');
        return $item;
    }

    //function to list users for admin
    public function listUsers() {
        $pageName = "userMgmt";
        if(isset($_SESSION['user']) && $_SESSION['user'] == "admin"){

        $result = User::getAllUsers();
        $users = array();
        foreach($result as $row){
            $users[] = $this->getAllCols($row);
        }

        $evt = EventTypes::getEventIdByName("requested admin");
        $adminReq = array();
        $adminReqUsers = array();
        $adminReqs = Event::getAllEventsByEventId($evt[0]->get('id'));
        foreach($adminReqs as $row) {
            $adminReq[] = $row->get('user');
        }
        foreach($adminReq as $userId) {
            $userObj = User::loadById($userId);
            $adminReqUsers[] = $this->getAllCols($userObj);
        }

        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/userMgmt.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
      }
      else {
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/home.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
      }
    }

    //function to save profile after editing under Profile Info page
    public function saveProfile() {
        $id = $_POST['id'];
        $uname = $_POST['uname'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $curr_usr = User::loadById($id);
        if( $uname == null || $fname ==null || $lname == null || $email ==null || $password == null) {
            $error = true;
        }
        else {

            $curr_usr->set('uname',$uname);
            $curr_usr->set('fname',$fname);
            $curr_usr->set('lname',$lname);
            $curr_usr->set('uname',$uname);
            $curr_usr->set('email',$email);
            $curr_usr->set('password',$password);
            $curr_usr->save();
            $user_detail = $this->getAllCols($curr_usr);
            include_once SYSTEM_PATH.'/view/header.tpl';
            include_once SYSTEM_PATH.'/view/profile.tpl';
            include_once SYSTEM_PATH.'/view/footer.tpl';
        }
    }

    //Function that handles Approve/reject admin requests
    public function editUserType($value) {
        $usrId = substr($value, strpos($value, " ") + 1);
        if(strpos($value, 'Approve') !== false) {

            $usrObj = User::loadById($usrId);
            $usrObj->set('user_type','admin');
            $usrObj->save();
            }
            $evts = Event::getAllEventsById($usrId);
            foreach($evts as $evt) {
                if($evt->get('event_type') == '10') {
                    Event::deleteById($evt->get('id'));
                    break;
                }
            }


         $this->listUsers();


    }

}
