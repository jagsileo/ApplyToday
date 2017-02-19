
<div id="signindiv">
    <form id="signinform" action="<?= BASE_URL ?>/signin/process" method="POST">
        <input type="text" id="signinemailid" name = "un" placeholder="User Name">
        <input type="password" id="signinpassword" name = "pw" placeholder="password">
        <input type ="submit" id = "signin" value="Submit">
        <a id="signup" href="<?= BASE_URL ?>/signup">Create New Account</a>
        <div class="clearfloat">
		<a href="#">Forgot account?</a>&nbsp
		<a id="turker" href="<?=BASE_URL ?>/turker"> Get a turker ID </a>&nbsp
		<a href="<?=BASE_URL?>/jobs">View jobs as a guest</a>
	</div>
    </form>
</div>
