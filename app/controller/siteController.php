<?php
/* 
 * Main controller file for the site. Used for navigation between pages.
 */
include_once '../global.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$action = $_GET["action"];
$pc = new SiteController();
$pc->route($action);

class SiteController{
    public function route($action){
        switch ($action){
            case "home":
                $this-> home();
                break;
            case "signinProcess":
                $un = $_POST["un"];
                $pw = $_POST["pw"];
                $this->processSignIn($un, $pw);
                break;
            case 'signupProcess':             
                $this->signup_process($_GET['email_id'],$_GET['uname'],$_GET['fname'],$_GET['lname'],$_GET['password']);
                break;
            case 'signout':
                $this->signoutProcess();
                break;
            case "signup":
                $this->signup();
                break;
            case "targets":
                $this-> targets();
                break;
            case "background":
                $this-> background();
                break;
            case "profile":
                $this-> profile();
                break;
            case "turker":
                $this->processTurker();
                break;   	
            case "error":
                $this->goToError();
                break;
            default:
                header('Location: '.BASE_URL);
                exit();
        }
    }
    //home page
    public function home(){
        $pageName = "home";
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/home.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
	
    //signout process
    public function signoutProcess(){
        $_SESSION['user'] = 'guest';
        header('LOCATION: '.BASE_URL."/home");
    }

    //signup page
    public function signup(){
        $pageName = "signup";
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/signup.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
	
	/*Signup processing*/
	  public function signup_process($email, $uname, $fname, $lname, $pwd){
          if(User::loadByUsername($uname) !== null) {
              $json = array("status" => "unavailable", "attrName" => "uname");
          }
          else if(User::loadByEmail($email)!==null) {
              $json = array("status" => "unavailable", "attrName" => "email");
          }
          else {
              $json = array("status" => "available");
          }
          
		  header('Content_Type: application/json');
		  echo json_encode($json);
	  }
    
    //targets page
    public function targets(){
        $pageName = "targets";
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/targets.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
   
    //profileinfo page
    public function background(){
        $pageName = "background";
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/profileinfo.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
	
	//profile page
    public function profile(){
		$creator_id = $_SESSION['user'];
        $pageName = "profile";
		$event = new Event();
		$usr = new User();
		$current_usr = $usr->loadByUsername($creator_id);
        
        //Convert user object to array
        $user_detail = array();
        $user_detail['id'] = $current_usr->get('id');
        $user_detail['fname'] = $current_usr->get('fname');
		$user_detail['lname'] = $current_usr->get('lname');
        $user_detail['uname'] = $current_usr->get('uname');
        $user_detail['email'] = $current_usr->get('email');
        $user_detail['password'] = $current_usr->get('password');
        $user_detail['user_type'] = $current_usr->get('user_type');
        
        $evts = $event->getAllEventsById($current_usr->getId());
		
		$folwr = new User();
		$job = new SuggestedJobs();
		$evt = new EventTypes();
		$results = array();
		
		foreach ($evts as $row){
			$result_row = array($folwr->loadById($row->get('user'))->get('email'), 
								$evt->loadById($row->get('event_type'))->get('name'), 
								$job->loadById($row->get('job'))->get('title'), 
								$job->loadById($row->get('job'))->get('link_url'),
								$row->get('timestamp')
			);
			array_push($results, $result_row);
		}
		
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/profile.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }

    //function to process sign in action
    public function processSignIn($un=null, $pw=null){
        //validations for username and password
        if($un == null) {
            $error = "User name cannot be empty.";
            $this->goToError($error);
            exit();
        }
        if($pw == null){
            $error = "Password cannot be empty.";
            $this->goToError($error);
            exit();
        }
        $pageName = "applications";
        $obj = User::loadByUsername($un);
        
        if($obj == null){
            $error = "User name not found.";
            $this->goToError($error);
            exit();
        }
        else if($obj->get('password') == $pw){
           
           $_SESSION['user'] = $un;
           $creator_id = $_SESSION['user'];        
            $userType = $obj->get('user_type'); 
            if($userType == 'admin') {
               $_SESSION['isAdmin'] = true;
            }
            else {
                $_SESSION['isAdmin'] = false;
            }
            
           header('LOCATION: '.BASE_URL."/applications");
        }
        else{
            $error = "Password is incorrect.";
            $this->goToError($error);
            exit();
        }
    }    
    
    //function to call error page
    public  function goToError($error){
        $pageName = "home";
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/error.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
	
    
}