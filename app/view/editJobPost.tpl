<div id="jobscontent">
    <form id="editJobPost" action="<?=BASE_URL?>/jobs/edit/<?= $id ?>/process/" method="POST">
    <h2> Edit Job Post</h2> 
    <p> * fields are mandatory </p>
    <div id="editJobPostDiv">
        <label>Job Title*: <input type="text" name="title" value="<?= $job['title']?>" required></label>
        <label>Company and Location*: <input type="text" name="location" value="<?= $job['location']?>" required></label>
        <label>Job Summary*: <textarea name="summary" rows="4" cols="100" required><?= $job['summary']?></textarea></label>
        <label>Job Description*: <textarea name="job_desc" rows="10" cols="100" required><?= $job['job_desc']?></textarea></label>
        <label>link_url*: <textarea name="link_url" rows="2" cols="100" required><?= $job['link_url']?></textarea></label>
        <input type="submit" value="Submit"> 
        <a href="<?=BASE_URL?>/jobs"><button type="button" formnovalidate="true"> Cancel </button></a> 
    </div>
    </form>
</div>
