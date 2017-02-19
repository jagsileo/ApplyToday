    <div id = "tabs">
        <ul id = "tabslist">
            <li><a href="#" class="activelink"> Updates </a></li>
        </ul>
        <div id = "tabcontent_profile">
            
            <form class = "appforms active" id = "personalinformation">
                <label> ID:</label> <input type="text" name="id" id = "userid" 
                                                   value = "<?=$user_detail['id']?>" readonly> <br>
                <label> User Name:</label> <input type="text" name="uname" 
                                                   id = "username" value = "<?=$user_detail['uname']?>" readonly required> <br>
                <label> Email*:</label> <input type="text" class="mandatory" name="email"
                                               id = "email" value = "<?=$user_detail['email']?>" readonly required><br>
				<label> First Name*:</label> <input type="text" class="mandatory" name="fname" 
                                                    id = "firstname" value = "<?=$user_detail['fname']?>" readonly required><br>
                <label> Last Name*:</label> <input type="text" class="mandatory" name="lname"
                                                   id = "lastname" value = "<?=$user_detail['lname']?>" required readonly ><br>
                <label> Password*:</label> <input type="password" class="mandatory" name="password" 
                                                  id = "password" value = "<?=$user_detail['password']?>" readonly required><br>
                <label id = "user_type_label"> User Type*:</label> <input type="text" name="user_type" id = "user_type" 
                                                   value = "<?=$user_detail['user_type']?>" readonly><br>
                
                <input type="button" name="profileEdit" value ="Edit" id = "profileEditButton">
                <input type="submit" name="profileSave" value ="Save" id = "profileSaveButton">

            </form>    
        </div>
    </div>
	<div id="sidebar_profile">
		<div class = "appforms active" id = "feeds_profile">
                <div id="activity_feed_title_profile">
					Activity Feed:
				</div>
				
				<?php
					
					for ($i=0; $i<sizeof($results); $i++){
						echo '<b>'.$results[$i][0].'</b> ';
						echo $results[$i][1].'d ';
						echo '<a href="'.$results[$i][3].'">'.$results[$i][2].'</a> on ';
						echo $results[$i][4].'<br>';
					}
					
				?>
            </div>
	</div>

