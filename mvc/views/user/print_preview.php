<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <div class="profileArea">
    <?php featureheader($siteinfos);?>
    <div class="mainArea">
      <div class="areaTop">
        <div class="studentImage">
          <img class="studentImg" src="<?=pdfimagelink($user->photo)?>" alt="">
        </div>
        <div class="studentProfile">
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('user_name')?></div>
            <div class="single_value">: <?=$user->name?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('user_type')?></div>
            <div class="single_value">: <?=count($user) ? $user->usertype : '' ?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('user_sex')?></div>
            <div class="single_value">: <?=$user->sex?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('user_dob')?></div>
            <div class="single_value">: <?php if($user->dob) { echo date("d M Y", strtotime($user->dob)); } ?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('user_phone')?></div>
            <div class="single_value">: <?=$user->phone?></div>
          </div>
        </div>
      </div>
      <div class="areaBottom">
        <table class="table table-bordered">
          <tr>
            <td width="30%"><?=$this->lang->line('user_jod')?></td>
            <td width="70%"><?php if($user->jod) { echo date("d M Y", strtotime($user->jod)); } ?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('user_religion')?></td>
            <td width="70%"><?=$user->religion?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('user_email')?></td>
            <td width="70%"><?=$user->email?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('user_address')?></td>
            <td width="70%"><?=$user->address?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('user_username')?></td>
            <td width="70%"><?=$user->username?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <?php featurefooter($siteinfos)?>
</body>
</html>