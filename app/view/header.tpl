<?php
    function isSelected($curPage, $pageName){
        if($curPage == $pageName)
            return 'class = "selectednav" ';         
    }
    
    function isGuestLogin(){
       return isset($_SESSION['user']) && $_SESSION['user'] == 'guest';
    }
   
    function accountDropDown(){
     echo '
                    <div class = "account">
                        <button class="dropdown">Account</button>
                        <div class = "dropdownContent">
                            <a href="'.BASE_URL.'/home"> Home </a>
                            <a href="'.BASE_URL.'/applications">Applications</a>
			    <a href="'.BASE_URL.'/targets">Targets</a>	
                            <a href="'.BASE_URL.'/jobs"> Jobs</a>
                            <a href="'.BASE_URL.'/background">Background</a>
                            <a href="'.BASE_URL.'/profile">Profile Info</a>                                
                            <a href="'.BASE_URL.'/signout"> Sign Out</a>
                        </div>
                    </div>';

    }
    function createHeader($pageName){
        if($pageName == "home"){
            echo '<h1> Apply Today</h1>';
            if(isGuestLogin()){
                include_once SYSTEM_PATH.'/view/signin.tpl';
            }
            else{
                accountDropDown(); 
            }
        }
        else if(($pageName == "signup") || isGuestLogin()){
            echo'
            <h1> Apply Today</h1>
            <div id="signupnav">
               Already have an account?
               <a href="'.BASE_URL.'/home"> Sign In here</a>
            </div>';
        }
        else{
            accountDropDown(); 
            $user_mgmt = '';
            if($_SESSION['isAdmin'] == true) {
                $user_mgmt = '<li><a id = "user_mgmt"  '.isSelected("userMgmt", $pageName).' href="'.BASE_URL.'/userMgmt">Users</a></li>';
            }
            else {
                $user_mgmt = '';
            }
            
          echo'
            <nav>
                <ul>
                    <li><a id ="applications" '.isSelected("applications", $pageName).' href="'.BASE_URL.'/applications">Applications</a></li>
                    <li><a id ="mytargets"  '.isSelected("targets", $pageName).' href="'.BASE_URL.'/targets">My Targets</a></li>
                    <li><a id ="jobs"  '.isSelected("jobs", $pageName).' href="'.BASE_URL.'/jobs">Jobs</a></li>
                    <li><a id = "background"  '.isSelected("background", $pageName).' href="'.BASE_URL.'/background">Background</a></li>
		    <li><a id = "profile"  '.isSelected("profile", $pageName).' href="'.BASE_URL.'/profile">Profile Info</a></li>'.$user_mgmt.'</ul></nav>';
	   
        }
    }    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your personal job application manager</title>
    <link rel = "stylesheet" type = "text/css" href = "<?= BASE_URL?>/public/css/styles.css">
    <script type="text/javascript" src="<?= BASE_URL?>/public/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src = "<?= BASE_URL?>/public/js/scripts.js"></script>
    <?php if($pageName == 'd3Calendar'): ?>
	<script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
	<script type="text/javascript" src="<?= BASE_URL ?>/public/js/d3Calendar.js"></script>
    <?php endif; ?> 	
    <script type="text/javascript">
	var baseURL = '<?= BASE_URL ?>';
    </script>
</head>
<body>
    <header>
        <div id = "logo">
            <a href="<?=BASE_URL?>/home">
                <img src="<?= BASE_URL ?>/public/img/at.png" alt="logo"/>
            </a>
        </div>
        <?= createHeader($pageName)?>
       
    </header>
