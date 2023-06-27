<!DOCTYPE html>
<html lang="en">
<head>
</head>
  <body>
    <div class="mainArea">
      <?php featureheader($siteinfos);?>
      <div class="noticeArea">
        <div class="date">
          <div class="left"><b><?=$this->lang->line('holiday_fdate')?>:- </b><?=date("d M Y", strtotime($holiday->fdate));?></div>
          <div class="right"><b><?=$this->lang->line('holiday_tdate')?>:- </b><?=date("d M Y", strtotime($holiday->tdate));?></div>
        </div>
        <h3 class="title"><?=$holiday->title;?></h3>
        <?php echo $holiday->details; ?>
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
