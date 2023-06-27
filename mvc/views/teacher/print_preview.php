<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <div class="profileArea">
    <?php featureheader($siteinfos)?>
    <div class="mainArea">
      <div class="areaTop">
        <div class="studentImage">
          <img class="studentImg" src="<?=pdfimagelink($teacher->photo)?>" alt="">
        </div>
        <div class="studentProfile">
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('teacher_name')?></div>
            <div class="single_value">: <?=$teacher->name?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('teacher_designation')?></div>
            <div class="single_value">: <?=$teacher->designation?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('teacher_sex')?></div>
            <div class="single_value">: <?=$teacher->sex?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('teacher_dob')?></div>
            <div class="single_value">: <?php if($teacher->dob) { echo date("d M Y", strtotime($teacher->dob)); } ?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('teacher_phone')?></div>
            <div class="single_value">: <?=$teacher->phone?></div>
          </div>
        </div>
      </div>
      <div class="areaBottom">
        <table class="table table-bordered">
          <tr>
            <td width="30%"><?=$this->lang->line('teacher_jod')?></td>
            <td width="70%"><?php if($teacher->jod) { echo date("d M Y", strtotime($teacher->jod)); } ?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('teacher_religion')?></td>
            <td width="70%"><?=$teacher->religion?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('teacher_email')?></td>
            <td width="70%"><?=$teacher->email?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('teacher_address')?></td>
            <td width="70%"><?=$teacher->address?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('teacher_username')?></td>
            <td width="70%"><?=$teacher->username?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <?php featurefooter($siteinfos)?>
</body>
</html>