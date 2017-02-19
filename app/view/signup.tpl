
    <div id="signupcontent">
        <div id = "signupbox">
                <form id="signupform" action="<?= BASE_URL ?>/addUser" method="POST">
                    <button> Connect with LinkedIn </button><hr>
                    <label> First Name*: </label><input class="signupfield" type="text" name="fname" id="signup_fname" required><br>
                    <label> Last Name*: </label><input class="signupfield" type="text" name="lname" id="signup_lname" required><br>
                    <label> User Name*: </label><input class="signupfield" type="text" name="uname" id="signup_uname" required><br>
                    <label> Email ID*: </label><input class="signupfield" type="text" name="email_id" id="signup_email_id" required><br>
                    <label> Password*: </label><input class="signupfield" id="password" type="password" name="password" required><br>
                    <label> Confirm Password*: </label><input class="signupfield" id="confirm_pwd" type="password" name="confirm_pwd" required><br><br>
                    <input type="radio" name="user_type" value="applicant" checked>Applicant<br>
                    <input type="radio" name="user_type" value="admin">Admin<br>
                    <label id="checkboxlabel"><input type="checkbox" name="t&c"> I agree with
                    <a href="#"> Apply Today</a> terms.</label>
                    <input type ="submit" id = "signupsubmit" value="Submit">
                </form>
        </div>
        <figure id="sideimg">
            <img src="<?= BASE_URL ?>/public/img/mainfeature.jpg" alt="feature image">
            <figcaption>https://pixabay.com/en/looking-for-a-job-work-silhouettes-1257233/</figcaption>
        </figure>
    </div>

