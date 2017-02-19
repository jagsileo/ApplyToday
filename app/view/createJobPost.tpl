<div id="jobscontent">
    <form id="createJobPost" action="<?=BASE_URL?>/jobs/create/process/" method="POST">
    <h2> Create Job Post</h2> 
    <p> * fields are mandatory </p>
    <div id="createJobPostDiv">
        <label>Job Title*: <input type="text" name="title" value="" required></label>
        <label>Company and Location*: <input type="text" name="location" value="" required></label>
        <label>Job Summary*: <textarea name="summary" rows="4" cols="100" required></textarea></label>
        <label>Job Description*: <textarea name="job_desc" rows="10" cols="100" required></textarea></label>
        <label>link_url*: <textarea name="link_url" rows="2" cols="100" required></textarea></label>
        <input type="submit" value="Create"> 
        <a href="<?=BASE_URL?>/jobs"><button type="button" formnovalidate="true"> Cancel </button></a> 
    </div>
    
    
    </form>
    
</div>
