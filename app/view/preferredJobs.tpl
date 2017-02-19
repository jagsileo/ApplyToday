
    <div id="jobscontent">
        <form id = "leftnav" action="<?=BASE_URL ?>/jobs" method="GET">
            <ul id="leftnavul">
                <li><button name="jobsType" class="activelink" value="preferred">Preferred Jobs</button></li>
                <li><button name="jobsType"  value="suggested">Suggested Jobs</button></li>
            </ul>
        </form>
        <div id="jobsdiv">
                <?php foreach($resultList as $job): ?>
                    <div>
                    <h3><a href = "<?=$job['detailUrl'] ?>"><?= $job['jobTitle']?></a></h3>
                    <p>Company: <?=$job['company']?></p>
                    <p>Location: <?= $job['location']?></p> 
                    <p>Posting Date: <?= $job['date']?></p>  
                    </div>
                <?php endforeach; ?>            

            </div>
    </div>

