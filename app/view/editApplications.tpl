
    <div id = "editApplicationsbody">
    <div id = "editApplicationscontent">
        <a id="finishupdate" href ="<?=BASE_URL?>/applications"><Button> Finish Update</button></a>
        <div id="search">
            <input type="text" name="searchbox" placeholder="Search applications"><button> > </button>
        </div>

        <div class="customtable">
            <div class="tableheader">
                <div class="tablecell">Job ID</div>
                <div class="tablecell">Company Name*</div>
                <div class="tablecell">Position* </div>
                <div class="tablecell">Job Link</div>
                <div class="tablecell">Applied Date*</div>
                <div class="tablecell">Resume Version</div>
                <div class="tablecell">Contact</div>
                <div class="tablecell">Status</div>
            </div>
            <?php foreach($apps as &$row){?>
                
            <form id="editApplication_<?=$row['id']?>" action="" method="POST">
                <div class="tablerow">
                <div class="tablecell normalCell id" contenteditable='false'>
                    <?=$row['id']?>                    
                </div>
                <div class="tablecell normalCell company_name" contenteditable='false'>
                    <?=$row['company_name']?>                   
                </div>
                <div class="tablecell normalCell position" contenteditable='false'>
                    <?=$row['position']?>                  
                </div>
                <div class="tablecell normalCell job_url" contenteditable='false'>
                    <?=$row['job_url']?>
                </div>
                <div class="tablecell normalCell applied_date" contenteditable='false'>
                    <?=$row['applied_date']?>   
                </div>
                <div class="tablecell normalCell resume_version" contenteditable='false'>
                    <?=$row['resume_version']?>
                </div>
                <div class="tablecell normalCell contact" contenteditable='false'>
                    <?=$row['contact']?>
                </div>
                <div class="tablecell normalCell status" contenteditable='false'>
                    <?=$row['status']?>
                </div>
                <input class="id" type="hidden" name="id" value="<?=$row['id']?>">    
                <input class="company_name" type="hidden" name="company_name" value="<?=$row['company_name']?>">
                <input class="position" type="hidden" name="position" value="<?=$row['position']?>">
                <input class="job_url" type="hidden" name="job_url" value="<?=$row['job_url']?>">    
                <input class="applied_date" type="hidden" name="applied_date" value="<?=$row['applied_date']?>">
                <input class="resume_version" type="hidden" name="resume_version" value="<?=$row['resume_version']?>">
                <input class="contact" type="hidden" name="contact" value="<?=$row['contact']?>">  
                <input class="status" type="hidden" name="status" value="<?=$row['status']?>">
                <button class='edit' form="<?=$row['id']?>" name='edit' type='button' value="edit">&#9998</button>
                <button class='delete' form="<?=$row['id']?>" name='delete' type='button' value="delete">&#10060</a>
            </div>
            </form>
            
            <?php } ?>
            <form id="addApplication" action="" method="POST">
                <div class="tablerow">
                    <div class="tablecell">
                        New
                    </div>
                    <div class="tablecell">
                        <input type="text" name="company_name" required>
                    </div>
                    <div class="tablecell">
                        <input type="text" name="position" required>
                    </div>
                    <div class="tablecell">
                        <textarea name="job_url"></textarea>
                    </div>
                    <div class="tablecell">
                        <input type="date" name="applied_date" required>
                    </div>
                    <div class="tablecell">
                        <input type="text" name="resume_version" style="max-width: 50px">
                    </div>
                    <div class="tablecell">
                        <input type="text" name="contact">
                    </div>
                    <div class="tablecell">
                        <select name="status">
                            <option>Applied</option>
                            <option>Selected</option>
                            <option>Rejected</option>
                        </select>
                    </div>
                    <button id="addApp" form="addApplication" type='submit' name='add' value='addApp'>&#9989</button>
                </div>
            </form>
        </div>
        </div>
    </div>

