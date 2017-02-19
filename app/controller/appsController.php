<?php

/* 
 * Main controller file for tasks related to applications. Contains functions for
 * create, edit and delete application operations.
 */
include_once '../global.php';

//get the page that we want to load
$action = $_GET["action"];

//instantiate a jobsController and route it
$jc = new AppsController();
$jc->route($action);

class AppsController {
    public function route($action){
        switch($action){
            case "listApps":
                $this-> listApplications();
                break;
            case "addApp":
                $this->addApp();
                break;
            case "editApps":
		$this->editApps();
                break;
            case "editAppsProcess": 
                $appid = $_POST['id']; 
                $this->editAppsProcess($appid); 
                break;
            case "deleteApp":
                $appid = $_GET["id"];
                $this->deleteApp($appid);
                break;
			case "data":
                $this-> data();
                break;
	    case "calendar":
		$this->drawCalendar();
		break;
            default:
                header('Location: '.BASE_URL);
                exit();
        }   
    }
    
    //helper function to convert a result object to an array
     public function getAllCols($row){
        $item = array();
        $item["id"] = $row->get("id");
        $item["creator_id"] = $row->get("creator_id");
        $item["company_name"] = $row->get("company_name");
        $item["position"] = $row->get("position");
        $item["job_url"] = $row->get("job_url");
        $item["applied_date"] = $row->get("applied_date");
        $item["resume_version"] = $row->get("resume_version");
        $item["contact"] = $row->get("contact");
        $item["status"] = $row->get("status");
        return $item;
    }
    
    //helper function to get the SESSION user id.
    public function getUserID(){
        if(isset($_SESSION['user']) && $_SESSION['user'] != "guest"){
            return $_SESSION['user'];
        }
        else{
            header("LOCATION: ".BASE_URL."/home");
        }
    }
    
    //function to call list view in applications page
    public function listApplications(){
        $creator_id = $this->getUserID();
        $pageName = "applications";
        if (isset($_GET["page"])) { 
            $page  = $_GET["page"]; 
        } else { 
            $page=1; 
        }
        $totalRows = Applications::getNumOfRows($creator_id);
        $limit = 3;
        $total_pages = ceil($totalRows/$limit);
        $start_from = ($page-1)*$limit;
        $result = Applications::getAllApps($limit,$creator_id, $start_from);
        $apps = array();
	if($result != null){
       		 foreach($result as &$row){
            		$apps[] = $this->getAllCols($row);
        	 }  
	}

		$types = "(1,3,6,7,8,9,11)";
		$ev = new Event();
		$usr = new User();
		$current_usr = $usr->loadByUsername($creator_id);
		$evts = $ev->getEventsOfFriends(null, $types, $current_usr->getId());
		
		$folwr = new User();
		$job = new SuggestedJobs();
		$evt = new EventTypes();
		
		$results = array();
		
		if ($evts!= null){
			foreach ($evts as $row){
				$result_row = array($folwr->loadById($row->get('user'))->get('email'), 
						    $evt->loadById($row->get('event_type'))->get('name'), 
						    $job->loadById($row->get('job'))->get('title'), 
						    $job->loadById($row->get('job'))->get('link_url'),
						    $row->get('timestamp')
				);
				array_push($results, $result_row);
			}
		}		
		  
        if(!isset($_GET["page"])){
            include_once SYSTEM_PATH.'/view/header.tpl';
            include_once SYSTEM_PATH.'/view/applications.tpl';
            include_once SYSTEM_PATH.'/view/footer.tpl';
        }
        else{
            header('Content-Type: application/json');
            echo json_encode($apps); 
        }
    }
	
	//function to get application data for a user in JSON format
    public function data(){
        $creator_id = $this->getUserID();
        $pageName = "applications";
        if (isset($_GET["page"])) { 
            $page  = $_GET["page"]; 
        } else { 
            $page=1; 
        }
        $totalRows = Applications::getNumOfRows($creator_id);
        $limit = 1000;
        $total_pages = ceil($totalRows/$limit);
        $start_from = ($page-1)*$limit;
        $result = Applications::getAllApps($limit,$creator_id, $start_from);
        $apps = array();
	if($result != null){
        	foreach($result as &$row){
            		$apps[] = $this->getAllCols($row);
        	}  
	}
		
		//sort the apps array by date. This is done so the group by algorithm below works
		usort($apps, function($a, $b) {
			return strtotime($b["applied_date"]) - strtotime($a["applied_date"]);
		});
		
		$current_date = $apps[0]["applied_date"];
		$dataset = array();
		$values = array();
		
		/*
		* Group by algorithm: Give all records with the same date that date
		* as the key and the records as values to that particular date key
		*/
		for ($i=0; $i<count($apps); $i++){
		  if ($apps[$i]["applied_date"]==$current_date){
			array_push($values,$apps[$i]);
		  }
		  else{
			$temp = array("Key" => $current_date, "Values" => $values);
			array_push($dataset,$temp);
			$current_date = $apps[$i]["applied_date"];
			$values= array();
			array_push($values,$apps[$i]);
		  }
		}
		$temp = array("Key" => $current_date, "Values" => $values);
		array_push($dataset,$temp);
		
		header('Content-Type: application/json');
            echo json_encode($dataset);

    }
    
    //function to call edit view in applications page
    public function editApps(){
        $pageName = "applications";
        $creator_id = $this->getUserId();
        $totalRows = Applications::getNumOfRows($creator_id);
        $result = Applications::getAllApps($totalRows, $creator_id, 0);
        $apps = array();
	if($result != null){
        	foreach($result as &$row){
            		$apps[] = $this->getAllCols($row);
        	}   
	}
        include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/editApplications.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
    
    //function to process edit view in applications page
    public function editAppsProcess($appid){
        $company_name = $_POST["company_name"];
        $position = $_POST["position"];
        $job_url = $_POST["job_url"];
        $applied_date = $_POST["applied_date"];
        $resume_version = $_POST["resume_version"];
        $contact = $_POST["contact"];
        $status = $_POST["status"];  
        
        $app = Applications::loadById($appid);
        $app->set("company_name", $company_name);
        $app->set("position", $position);
        $app->set("job_url", $job_url);
        $app->set("applied_date", $applied_date);
        $app->set("resume_version", $resume_version);
        $app->set("contact", $contact);
        $app->set("status", $status);
        
        $app->save();
        //header("LOCATION: ".BASE_URL."/home");
        header('Content-Type: application/html');
        //$res = array("action", "success");
        $res = "<p>success</p>";
        //echo json_encode($res); 
        echo $res;
    }
    
    //function to process delete view in applications page
    public function deleteApp($id){
        $obj = new Applications();
        $obj->deleteById($id);
        header('Content-Type: application/json');
        $res = array("action", "success");
        echo json_encode($res);
    }
    
    //function to process create view in applications page
    public function addApp(){
        $app = new Applications();
        $creator_id = $this->getUserID();
        $app->set("creator_id", $creator_id);
        $app->set("company_name", $_POST["company_name"]);
        $app->set("position", $_POST["position"]);
        $app->set("job_url", $_POST["job_url"]);
        $app->set("applied_date", $_POST["applied_date"]);
        $app->set("resume_version", $_POST["resume_version"]);
        $app->set("contact", $_POST["contact"]);
        $app->set("status", $_POST["status"]);
        $app->save();
        header('Content-Type: editApplications/text');
        $resid = Applications::getLastRowId($creator_id);
	echo $resid;
	//$resobj = array("id", $resid);
        //echo json_encode($resobj);
        //header("LOCATION: ".BASE_URL."/editApplications");
    }
		
		//function to call calendar visualization
    public function drawCalendar(){
	$creator_id = $this->getUserID();
    	$pageName = "d3Calendar";
	include_once SYSTEM_PATH.'/view/header.tpl';
        include_once SYSTEM_PATH.'/view/d3Calendar.tpl';
        include_once SYSTEM_PATH.'/view/footer.tpl';
    }
   
}
