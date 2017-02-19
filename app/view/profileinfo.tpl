    <div id = "tabs">
        <ul id = "tabslist">
            <li><a href="#" class="activelink"> Personal Information </a></li>
            <li><a href="#"> Education </a></li>
            <li><a href="#"> Experience </a></li>
            <li><a href="#"> Attachments </a></li>
            <li><a href="#"> Contacts </a></li>
            <li><a href="#">General questions</a></li>
        </ul>
        <div id = "tabcontent">
            <form class = "appforms active" id = "personalinformation">
                <label> Upload Resume*:</label> <button name = "browse" class="mandatory"> Browse </button><br><br>
<!--
                <label> First Name*:</label> <input type="text" class="mandatory" name="firstname"><br>
                <label> Last Name*:</label> <input type="text" class="mandatory" name="lastname"><br>
-->
                <label> Phone Number*:</label> <input type="text" name="phonenumber"><br>
                <label> Address:</label><textarea name="address" rows="4" cols="22"></textarea><br>
                <label> Country:</label> <input type="text" name="country"><br>
                <label> City:</label> <input type="text" name="city"><br>
                <label> State:</label>
                <select name="state">
                    <option>California</option>
                    <option>Virginia</option>
                    <option>Washington</option>
                </select><br>
                <div class="buttonpanel">
                    <button name="save&continue"> Save and Continue </button>
                </div>
            </form>
            <form class = "appforms" id = "education">
                <label> University Name*:</label><input type="text" name="university"><br>
                <label> Major:</label><input type="text" name="major"><br>
                <label> Degree*:</label> <input type="text" name="degree"><br>
                <label> GPA:</label> <input type="text" name="gpa"><br>
                <label> Graduation Year*:</label> <input type="text" name="graduationyear"><br>
                <div class="buttonpanel">
                    <button name="Add"> Add </button><br>
                    <button name="save&continue"> Save and Continue </button>
                </div>
            </form>
            <form class = "appforms" id = "experience">
                <label> Company Name*: </label> <input type="text" name="companyname"><br>
                <label> From: </label> <input type="text" name="from"><br>
                <label> To: </label><input type="text" name="to"><br>
                <label> Job Title*:</label> <input type="text" name="jobtitle"><br>
                <label> Responsibilities: </label><textarea name="responsibilities" rows="4" cols="21"></textarea><br>
                <div class="buttonpanel">
                    <button name="Add"> Add </button><br>
                    <button name="save&continue"> Save and Continue </button>
                </div>
            </form>
            <form class = "appforms" id = "attachments">
                <label> Upload Coverletter:</label> <button name = "browse" class="mandatory"> Browse </button><br><br>
                <label> Other materials:</label> <button name = "browse" class="mandatory"> Browse </button><br><br>
            </form>
            <form class = "appforms" id = "contacts">
                <label> Import your LinkedIn contacts file:</label> <button name = "browse" class="mandatory"> Browse </button><br><br>
            </form>
        </div>
    </div>

