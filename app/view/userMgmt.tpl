    <div id = "usermgmtbody">
    <div id = "usermgmtcontent">
        <ul id = "tabslist">
            <li><a href="#" class="activelink"> All Users </a></li>
            <li><a href="#"> Admin Requests </a></li>
        </ul>  
        <table id = "allusers" class = "appforms active">
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email ID</th>
                <th>User Type</th>
            </tr>
            <?php foreach($users as &$row){?>
            <tr>
                <td><?=$row['id']?></td>
                <td><?=$row['uname']?></td>
                <td><?=$row['fname']?></td>
                <td><?=$row['lname']?></td>
                <td><?=$row['email']?></td>
                <td><?=$row['user_type']?></td>                
            </tr>
            <?php } ?>
        </table>
       
        <table id = "adminrequests" class = "appforms">
        <?php if($adminReqUsers != null) {?>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>Approve Admin Requests</th>
            </tr>
            <?php foreach($adminReqUsers as &$row){?>
            <tr>
                <form id= "approveReq" action="<?=BASE_URL?>/editUserType" method="POST">
                <td><?=$row['id']?></td>
                <td><?=$row['uname']?></td>
                <td><input type ="submit" name = "submit" value="<?='Approve '.$row['id']?>"><input type="submit" name="submit" value="<?='Reject '.$row['id']?>"></td>  
                </form>    
            </tr>
            <?php } ?>
            <?php } else { echo('No admin requests'); }?>
        </table>
        
        
            
        </div>        
</div>