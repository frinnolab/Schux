<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<div class="profileArea">	
    <?php featureheader($siteinfos);?>
    <div class="noticeArea">
      <h3 class="title"><?=$instruction->title;?></h3>
      <p><?=$instruction->content; ?></p>
    </div>
</div>
   <?php featurefooter($siteinfos);?>
</body>
</html>
