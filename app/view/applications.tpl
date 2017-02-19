
    <div id = "applicationsbody">
    <div id = "applicationscontent">
        <div id="search">
            <input type="text" name="searchbox" placeholder="Search applications"><button> > </button>
        </div>
        <form id ="EditApplications" action="<?=BASE_URL ?>/editApplications">
            <input type="submit" class = "editApplications" value="Add / Edit Applications">
        </form>
	<form id ="vizApplications" action="<?=BASE_URL ?>/applications/calendar">
            <input type="submit" class = "editApplications" value="Visualization View">
        </form>
        <table>
            <tr>
                <th>Job ID / Link</th>
                <th>Company Name</th>
                <th>Position</th>
                <th>Applied Date</th>
                <th>Resume Version</th>
                <th>Contact</th>
                <th>Status</th>
            </tr>
            <?php foreach($apps as &$row){?>
            <tr>
                <td><a href="<?='http://'.$row['job_url']?>"><?=$row['id']?></a></td>
                <td><?=$row['company_name']?></td>
                <td><?=$row['position']?></td>
                <td><?=$row['applied_date']?></td>
                <td><?=$row['resume_version']?></td>
                <td><?=$row['contact']?></td>
                <td><?=$row['status']?></td>
            </tr>
            <?php } ?>
        </table>
        <div id="paginationDiv">
            <ul id='pages'>
            <?php if(!empty($total_pages)) for($i=1; $i<=$total_pages; $i++) if($i == 1){?>
            <li class='active'  id="<?= $i?>"><button class="pageNum"><?= $i?></button></li>
            <?php } else{?>
            <li id="<?= $i?>"><button class="pageNum"><?= $i?></button></li>
            <?php } ?>
            </ul>
        </div>
        </div>
        <div id="sidebar">
			<div id="application_viz">
				<img src="<?= BASE_URL ?>/public/img/graph.PNG" alt="weekly activity">
			</div>

			<div id="activity_feed">
				<div id="activity_feed_title">
					Activity Feed:
				</div>

				<?php


					for ($i=0; $i<sizeof($results); $i++){
						echo '<b>'.$results[$i][0].'</b> ';
						echo $results[$i][1].' ';
						echo '<a href="'.$results[$i][3].'">'.$results[$i][2].'</a> on ';
						echo $results[$i][4].'<br>';
					}
				?>

			</div>
		</div>
    </div>
