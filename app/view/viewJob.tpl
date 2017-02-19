<div id="viewJobContent">
    <div id = "titlepanel">
        <img src="<?= BASE_URL ?>/public/img/<?= $job['img_url']?>" alt="company thumbnail">
        <h2><?= $job['title']?></h2>
        <a href="<?=$job['link_url']?>"><button id="apply"> Apply to this Job</button></a>
        <h3><p><?= $job['location']?></h3>
    </div>
    <hr>
    <?php if($isAdmin) { ?>
        <a href="<?= BASE_URL?>/jobs/edit/<?= $job['id']?>"><button name="button" class='deleteButton' value="delete"> Delete this post </button></a>
    <?php } ?>
    <div id="descpanel">
      <p> <?= nl2br($job['job_desc'])?></p>
    </div>
     </p>       
</div>
