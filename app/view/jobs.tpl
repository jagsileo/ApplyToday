
    <div id="jobscontent">
        <form id = "leftnav" action="<?=BASE_URL ?>/jobs" method="GET">
            <ul id="leftnavul">
                <li><button name="jobsType" value="preferred">Preferred Jobs  </button></li>
                <li><button name="jobsType" class="activelink" value="suggested">Suggested Jobs</button></li>
            </ul>
        </form>
        <div id="jobsdiv">
            <p> source: https://www.linkedin.com/jobs/ </p>
            <?php if($isAdmin){ ?>
            <form action="<?= BASE_URL?>/jobs/create/" method="POST">
                <input type="submit" name="addJob" value="Add a job">
            </form>
            <?php }$index = 0; ?>
            <?php foreach($jobs as &$row){
				
			?>
            
            <div class="jobdesc">
                <form action="<?= BASE_URL?>/jobs/edit/<?= $row['id']?>" method="POST">
                <img src="<?= BASE_URL ?>/public/img/<?= $row['img_url']?>" alt="company thumbnail">
                <h4><a href="<?= BASE_URL?>/jobs/details/<?=$row['id']?>"><?= $row['title']?></a> 
				<button id="job_like_unlike_button" class="like_unlike_button" value=<?=$_SESSION['user']."|".$row['id']?>><?=$likes[$index]?></button>
					<?php $index++; ?>  
                    <?php if($isAdmin){ ?>
                        <button name="button" class='editButton' value="edit"> Edit </button>
                        <button name="button" class='deleteButton' value="delete"> Delete </button>
                    <?php } ?>
                </h4>
                <p><?= $row['location']?> <br>
                    <?= $row['summary']?> <br>
					
                </p>
                </form>   
            </div>
            <?php } ?>  
        </div>
    </div>

