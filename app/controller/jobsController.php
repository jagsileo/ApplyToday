<?php

/* 
 * Main controller file for tasks related to applications. Contains functions for
 * create, edit and delete job post operations.
 */
include_once '../global.php';

//get the page that we want to load
$action = $_GET["action"];

//instantiate a jobsController and route it
$jc = new JobsController();
$jc->route($action);

class JobsController{    
    public function route($action){
        switch($action){
            case "jobs":
                $jobsType = $_GET["jobsType"];
                if($jobsType == "preferred"){
                    $this->preferredJobs();
                }
                else {
                    $this->suggestedJobs();
                }
                break;
            case "editJobPost":
                if($this->isAdminLogin()){
                    $jobid = $_GET["id"];
                    $operation = $_POST['button'];
                    if($operation == "edit"){
                        $this->editJobPost($jobid);
                    }else{
                        $this->deleteJobPost($jobid);
                    }
                }
                else{
                    header("LOCATION ".BASE_URL."/home.tpl");
                }
                break;
            case "editJobProcess":
                if($this->isAdminLogin()){
                    $id= $_GET['id'];
                    $this->editJobProcess($id);
                }
                else{
                    header("LOCATION ".BASE_URL."/home.tpl");
                }
                break;
            case "createJobPost":
                if($this->isAdminLogin()){
                    $this->createJobPost();
                }
                else{
                    header("LOCATION ".BASE_URL."/home.tpl");
                }
                break;
            case "createJobProcess":
                if($this->isAdminLogin()){
                    $this->createJobProcess();
                }
                else{
                    header("LOCATION ".BASE_URL."/home.tpl");
                }
                break;
            case "viewJobPost":
                $jobid=$_GET["id"];
                $this->viewJobPost($jobid);
                break;
	    case "like":
                $evnt=$_GET["button"]; 
		$usr=$_GET["usr"];
		$jb=$_GET["jb_id"];
                $this->processLikes($evnt, $usr, $jb);
                break;
            default:
                header('Location: '.BASE_URL);
                exit();
        }
    }
    public function isAdminLogin(){
        if(isset($_SESSION['user']) && $_SESSION['user'] == 'admin'){
            return true;
        }
        else{
            return false;
        }
    }
    //placeholder for preferred jobs page, yet to be implemented
    public function preferredJobs(){
        $pageName = "preferredJobs";
        $resultList = $this->getJobsList(); 
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/preferredJobs.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
    
    public function getJobsList() {
        $endpoint = "http://service.dice.com/api/rest/jobsearch/v1/simple.json?text=java&country=CA&pgcnt=5";
        $contents = file_get_contents($endpoint);
	$obj = json_decode($contents);
        return json_decode(json_encode($obj->{'resultItemList'}), True); //stdClass Object to Array
    }
    
    //function to call list view in suggested jobs page
    public function suggestedJobs(){
	$result = SuggestedJobs::getAllJobs();
        $jobs = array();
	$likes = array();
        foreach($result as &$row){
            $jobs[] = $this->getAllCols($row);
			$evt = new Event();
			$evt = $evt->getEventByUserAndJob(1, User::loadByUsername($_SESSION['user'])->getId(), 1, $this->getAllCols($row)['id'])[0];
			if (is_null($evt)){
				array_push($likes, "Like");
			}
			else{
				array_push($likes, 'Unlike');
			}
        } 
        
        //set isAdmin
        $isAdmin = $this->isAdminLogin();
        $pageName = "jobs";
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/jobs.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
    
    //helper function to convert a result object to an array
    public function getAllCols($row){
        $item = array();
        $item["id"] = $row ->get("id");
        $item["title"] = $row ->get("title");
        $item["location"] = $row->get("location");
        $item["summary"] = $row ->get("summary");
        $item["job_desc"] = $row->get("job_desc");
        $item["img_url"] = $row -> get("img_url");
        $item["link_url"] = $row->get("link_url");
        return $item;
    }
    
    //function to call edit view in suggested jobs page
    public function editJobPost($id){
        $pageName = "EditJob";
        $result = SuggestedJobs::loadById($id);
        if($result != NULL){
            $job = $this->getAllCols($result);
        }
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/editJobPost.tpl';
	include_once SYSTEM_PATH.'/view/footer.tpl';
    }
    
    //function to process edit view in suggested jobs page
    public function editJobProcess($id){
        $title = $_POST["title"];
        $location = $_POST["location"];
        $summary = $_POST["summary"];
        $job_desc = $_POST["job_desc"];
	$link_url = $_POST["link_url"];
        
	$job = SuggestedJobs::loadById($id);
	$job->set("title", $title);
	$job->set("summary", $summary);
	$job->set("location", $location);
        $job->set("job_desc", $job_desc);
	$job->set("link_url", $link_url);

	$job->save();
        header("LOCATION: ".BASE_URL."/jobs");
    }
    
    //function to call create view in suggested jobs page
    public function createJobPost(){
        $pageName = "CreateJob";
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/createJobPost.tpl';
	include_once SYSTEM_PATH.'/view/footer.tpl';
    }
    
    //function to process create view in suggested jobs page
    public function createJobProcess(){
        $job = new SuggestedJobs();
        $job->set("title", $_POST["title"]);
	$job->set("summary", $_POST["summary"]);
        $job->set("job_desc", $_POST["job_desc"]);
	$job->set("location", $_POST["location"]);
	$job->set("link_url", $_POST["link_url"]);
        
        $job->save();
        header("LOCATION: ".BASE_URL."/jobs");
    }
    
    //function to process delete view in suggested jobs page
    public function deleteJobPost($id){
        $obj = new SuggestedJobs();
        $obj->deleteById($id);
        header("LOCATION: ".BASE_URL."/jobs");
    }
    
    //function to call detail view in suggested jobs page
    public function viewJobPost($id){
        $isAdmin = $this->isAdminLogin();
        $pageName = "ViewJob";
        $result = SuggestedJobs::loadById($id);
        $job = $this->getAllCols($result);
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/viewJob.tpl';
	include_once SYSTEM_PATH.'/view/footer.tpl';
    }
	
	//function to process likes and unlikes
    public function processLikes($event, $user, $job_id){ 
		//var Event = new Event();
		$usr = new User();
		$usr=User::loadByUsername($user);
        $evt = new Event();
		
		if ($event==="Like"){	
			$evt = $evt->getEventByUserAndJob(1, $usr->getId(), 8, $job_id)[0];
			if (is_null($evt)){ 
				$evt = new Event();
				$evt->set('event_type', 1);
				$evt->set('user', $usr->getId());
				$evt->set('job',$job_id);
				$evt->save(); 
			}
			else {
				$evt = Event::loadById($evt->getId());
				$evt->setId($evt->getId());
				$evt->set('event_type', 1);
				$evt->save(); 
			}
			
			$response = array("status"=>"liked");
		}
		else if ($event==="Unlike"){
			$evt = $evt->getEventByUserAndJob(1, $usr->getId(), 1, $job_id)[0];
			if (is_null($evt)){
				$evt = new Event();
				$evt->set('event_type', 8);
				$evt->set('user', $usr->getId());
				$evt->set('job',$job_id);
				$evt->save();
			}
			else{
				$evt = Event::loadById($evt->getId()); $evt->setId($evt->getId());
				$evt->set('event_type', 8);
				$evt->save();
			}
			
			$response = array("status"=>"unliked");
		}
		else{
			$response = array("status"=>"error");
		}
		header('Content_Type: application/json');
		echo json_encode($response);
    }
} 

