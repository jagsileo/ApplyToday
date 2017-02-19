6/**
 * Created by vrk19 on 9/23/2016.
 */
$(document).ready(function () {

    //to have the personal information tab highlighted
    $(".activelink").css("background-color", "white");
    $(".activelink").css("color", "#006699");

    //to hide account drop down options on random click.
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown')) {
            $(".dropdownContent").removeClass("dropdownShow");
        }
    }

    //listener for dropdown button
    $(".dropdown").click(function(){
        $(".dropdownContent").toggleClass("dropdownShow");
    });

    //navigating to corresponding forms in card layout
    $("#tabslist a").click(function () {
        var formid = $(this).text().toLowerCase().replace(/\s/g, '');
        console.log(formid);
        $(".activelink").css("background-color", "#006699");
        $(".activelink").css("color", "white");
        $("#tabslist").removeClass("activelink");
        $(this).css("background-color", "white");
        $(this).css("color", "#006699");
        var forms = $(".appforms");
        //debugger;
        $(this).addClass("activelink");
        $(".appforms").removeClass("active");
        $("#" + formid).addClass("active");
    });

    //to add a company to the companies list
    $("#addcompany").click(function () {
        var company = $("#companyname").val();
        var compname = "<div class = compname>" + company + "</div>";
        if(company === ""){
            $("#companyname").css("border-color", "red");
        }
        else{
            $("#companylist").prepend("<li>"+ compname + "<button class='close' id='"+company+"'>" + "\u00D7" + "</button>" + "</li>");
            $("#companyname").val("");
        }
    });

    //to delete a company from the list
    $("#companylist").on("click", function (e) {
        if(e.target.className === "close"){
            $("#"+e.target.id).parent().remove();
        }
    });


    //to change the style in nav bar
    $("nav").click(function (e) {
        $(".selectednav").removeClass("selectednav");
        $(e.target).addClass("selectednav");
    });

    //to navigate to profile info on signup submit
    $("#signupform").submit(function () {
        debugger;
       window.location = 'profileinfo.html';
    });

    //pagination listener in applications page.
    $("#paginationDiv").on("click", "button.pageNum", function(e){
        e.preventDefault();
        $pageNum = $(this).text();
        $(this).parent().siblings().removeClass("active");
        $(this).parent().addClass("active");
        $table = $(this).parent().parent().parent().parent().children("table");
        $th='<tr> <th>Job ID / Link</th> <th>Company Name</th> <th>Position</th> <th>Applied Date</th> <th>Resume Version</th> <th>Contact</th> <th>Status</th></tr>';
        $("#applications.table").html('');
        $.get(
                 baseURL+"/applications/",
                 {"page" : $pageNum},
                 function(data){
                     $table.html('');
                     $table.append($th);
                     $(data).each(function(){
                        $table.append('<tr><td><a href="'+this.job_url+'">'+this.id+'</a></td>'+
                                '<td>'+this.company_name+'</td>'+
                                '<td>'+this.position+'</td>'+
                                '<td>'+this.applied_date+'</td>'+
                                '<td>'+this.resume_version+'</td>'+
                                '<td>'+this.contact+'</td>'+
                                '<td>'+this.status+'</td></tr>');
                     });
                 },
                 "json"
        );
    });

    $(".customtable").on("click", ".edit", function(e){
        e.preventDefault();
       if($(this).siblings().hasClass("normalCell")){
            $(this).siblings().addClass("editedCell");
            $(this).siblings().removeClass("normalCell");
            $(this).attr("name", "editSubmit");
            $(this).attr("type", "button");
            $(this).html('&#9989');
            $(this).siblings().attr("contenteditable", "true");
            $(this).parent().children().first().attr("contenteditable", "false");
        }
        else{
            $(this).siblings().addClass("normalCell");
            $(this).siblings().removeClass("editedCell");
            $(this).attr("name", "button");
            $(this).attr("type", "submit");
            var id = $(this).attr("form");
            $(this).attr("form", "editApplication_" + id);
            $(this).html('&#9998');
            $(this).siblings().attr("contenteditable", "false");

            //set input fields
            $field = $(this).parent().children("input.company_name");
            $field.attr("value", $field.parent().children("div.company_name").text().trim());
            $field = $(this).parent().children("input.position");
            $field.attr("value", $field.parent().children("div.position").text().trim());
            $field = $(this).parent().children("input.job_url");
            $field.attr("value", $field.parent().children("div.job_url").text().trim());
            $field = $(this).parent().children("input.applied_date");
            $field.attr("value", $field.parent().children("div.applied_date").text().trim());
            $field = $(this).parent().children("input.resume_version");
            $field.attr("value", $field.parent().children("div.resume_version").text().trim());
            $field = $(this).parent().children("input.contact");
            $field.attr("value", $field.parent().children("div.contact").text().trim());
            $field = $(this).parent().children("input.status");
            $field.attr("value", $field.parent().children("div.status").text().trim());
            var appid = $(this).parent().children("div.id").text().trim();
            var dataArray = $("#editApplication_"+appid).serializeArray();
            var jsonObj = {};
            $.each(dataArray, function(){
               jsonObj[this.name] = this.value || '';
            });
            $.post(
                 baseURL+"/editApplications/process/",
                 jsonObj,
                 function(data){
                     //alert(data);
                 },
                 "html"
             );
        }
	 if(window.location.href == baseURL+'/applications/calendar?') {
             	drawCalendar(baseURL+'/applications/data', $("#viz_year_select").val());
         }
    });

    //Delete button in application page
    $(".customtable").on("click", ".delete", function(e){
        e.preventDefault();
        var parent = $(this).parent().parent();
        if(confirm("Do you want to delete this application?")){
            var appid = $(this).parent().children("div.id").text().trim();
            $.get(
                 baseURL+"/editApplications/delete/",
                 {"id": appid},
                 function(data){
                     parent.slideUp(300,function() {
			parent.remove();
                    });
                 },
                 "json"
             );
             if(window.location.href == baseURL+'/applications/calendar?') {
             	drawCalendar(baseURL+'/applications/data', $("#viz_year_select").val());
             }
        }
    });

    //listener for addApplication button in editApplications page.
    $(".customtable").on("click", "#addApp", function(e){
       e.preventDefault();
       var dataArray = $("#addApplication").serializeArray();
       var addRow = $(this).parent();
       var jsonObj = {};
       $.each(dataArray, function(){
          jsonObj[this.name] = this.value || '';
       });

       $.post(
                 baseURL+"/addApplication",
                 jsonObj,
                 function(id){
                            $('<form id="editApplication_'+id+'" action="" method="POST">'+
				'<div class="tablerow"><div class="tablecell normalCell id" contenteditable="false">'+id+'</div>'+
                                '<div class="tablecell normalCell company_name" contenteditable="false">'+jsonObj.company_name.replace(/\"/g, "")+'</div>'+
                                '<div class="tablecell normalCell position" contenteditable="false">'+jsonObj.position.replace(/\"/g, "")+'</div>'+
                                '<div class="tablecell normalCell job_url" contenteditable="false">'+jsonObj.job_url.replace(/\"/g, "")+'</div>'+
                                '<div class="tablecell normalCell applied_date" contenteditable="false">'+jsonObj.applied_date.replace(/\"/g, "")+'</div>'+
                                '<div class="tablecell normalCell resume_version" contenteditable="false">'+jsonObj.resume_version.replace(/\"/g, "")+'</div>'+
                                '<div class="tablecell normalCell contact" contenteditable="false">'+jsonObj.contact.replace(/\"/g, "")+'</div>'+
                                '<div class="tablecell normalCell status" contenteditable="false">'+jsonObj.status.replace(/\"/g, "")+'</div>'+
                                '<input class="id" type="hidden" name="id" value="'+id+'">'+
                                '<input class="company_name" type="hidden" name="company_name" value="'+jsonObj.company_name.replace(/\"/g, "")+'">'+
                                '<input class="position" type="hidden" name="position" value="'+jsonObj.position.replace(/\"/g, "")+'">'+
                                '<input class="job_url" type="hidden" name="job_url" value="'+jsonObj.job_url.replace(/\"/g, "")+'">'+
                                '<input class="applied_date" type="hidden" name="applied_date" value="'+jsonObj.applied_date.replace(/\"/g, "")+'">'+
                                '<input class="resume_version" type="hidden" name="resume_version" value="'+jsonObj.resume_version.replace(/\"/g, "")+'">'+
                                '<input class="contact" type="hidden" name="contact" value="'+jsonObj.contact.replace(/\"/g, "")+'">'+
                                '<input class="status" type="hidden" name="status" value="'+jsonObj.status.replace(/\"/g, "")+'">'+
                                '<button class="edit" form="'+id+'" name="edit" type="button" value="edit">&#9998</button>'+
                                '<button class="delete" form="'+id+'" name="delete" type="button" value="delete">&#10060</button></div></form>').insertBefore("form#addApplication");
                 },
                 "text"
        );
	addRow.children().children().val('');
 	if(window.location.href == baseURL+'/applications/calendar?') {
    		drawCalendar(baseURL+'/applications/data', $("#viz_year_select").val());
  	}
    });

    //to delete a job post.
    $(".deleteButton").click(function(){
       return confirm("Do you want to delete this post?");
    });

    //Password validation during signup.
    $("#confirm_pwd").blur(function() {
        var pwd = $("#password").val();
        if($(".signupfield").val() == "") {
            alert("All fields are mandatory");
        }

        else if(pwd.length < 8) {
            alert("Password should have atleast 8 characters");
        }

        else if($("#password").val() !== $("#confirm_pwd").val()) {
            alert("Password and Confirm Password do not match");
        }
    });

    //Sign up form validation.
    $("#signup_email_id").blur(function(){
		var email = $(this).val();
        var uname = $("#signup_uname").val();
		$.get("signup/process", {'email_id': email,'uname':uname}, function(data){
			if (data.status=="unavailable"){
                if(data.attrName == "uname") {
                    $("#signup_uname").addClass('error');
				    alert("Selected user name is not available!!!");
                }
                else if(data.attrName == "email") {
				    $("#signup_email_id").addClass('error');
                    alert("Selected email is not available!!!");
                }
			}
			else{
				$("#signup_email_id").removeClass('error');
                $("#signup_uname").removeClass('error');
			}
		}, "json");
	});

        //Unlike button in Suggested Jobs page.
	$(".like_unlike_button").click(function(event){
		event.preventDefault();
		var values = $(this).val().split("|");
		var clickedButton = $(this);
		console.log("Job "+values[1]+" (un)liked by "+values[0]);
		$.ajax({
			method: "GET",
			url: "jobs/like",
			data: { button: clickedButton.text(), usr: values[0], jb_id: values[1]}
		})
		.done(function(data, status){
			var result = $.parseJSON(data)['status'];
			if (result==="liked"){
				$(clickedButton).text('Unlike');
				/*alert("Job "+values[1]+" has been liked by "+values[0]+" !!!");*/
			}
			else if (result==="unliked"){
				$(clickedButton).text('Like');
				/*alert("Job "+values[1]+" has been unliked by "+values[0]+" !!!");*/
			}
			else{
				alert("Error (un)liking job!!! "+result);
			}
		});
	});

    //Listener for edit button in Profile page.
    $("#profileEditButton").click(function(event) {
        $('.mandatory').removeAttr("readonly");
    });


    //Listener for save button in Profile page.
    $("#profileSaveButton").click(function(event) {
        event.preventDefault();
        var uid = $("#userid").val();
        var uname = $("#username").val();
        var fname = $("#firstname").val();
        var lname = $("#lastname").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var user_type = $("#user_type").val();
        if(email == null || fname == null || lname == null || password == null) {
            alert("All fields are mandatory");
        }
        else if(password.length < 8) {
            alert('Password must have a minimum of 8 characters');
        }

        var dataString = 'id='+uid+'&lname='+lname+'&fname='+fname+'&uname='+uname+'&email='+email+'&password='+password+'&user_type';
        var evtName = "edited Profile";
        var event = 'name='+evtName+'&user='+uid;

        $.ajax({
            method:"POST",
            data: dataString,
            url: "saveProfile",
            success:function(event) {
                    $(".mandatory").prop("readonly", true);

            }
    });
    });

});
