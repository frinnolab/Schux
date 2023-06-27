<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
  <div class="mainArea">
    <?php featureheader($siteinfos);?>
    <div class="noticeArea">
      <p><b><?=$this->lang->line('notice_date')?>:- </b><?=date("d M Y", strtotime($notice->date));?></p>
      <h3 class="title"><?=$notice->title;?></h3>
      <?php echo $notice->notice; ?>
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