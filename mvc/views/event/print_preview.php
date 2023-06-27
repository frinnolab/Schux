<!DOCTYPE html>
<html lang="en">
<head>
</head>
  <body>
    <div class="mainArea">
      <?php featureheader($siteinfos);?>
      <div class="noticeArea">
        <div class="date">
          <div class="left"><b><?=$this->lang->line('event_fdate')?>:- </b><?=date("d M Y", strtotime($event->fdate));?></div>
          <div class="right"><b><?=$this->lang->line('event_tdate')?>:- </b><?=date("d M Y", strtotime($event->tdate));?></div>
        </div>
        <h3 class="title"><?=$event->title;?></h3>
        <?php echo $event->details; ?>
      </div>
      <div class="footerTop">
        <div class="signatureArea">
          <p class="userName"><?=$userName?></p>
          <p>(<?=$usertype?>, <?=$siteinfos->sname?>)</p>
        </div>
      </div>
    </div>
  </body>
</html>
