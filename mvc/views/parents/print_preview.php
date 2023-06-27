<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <div class="profileArea">
    <?=featureheader($siteinfos)?>
    <div class="mainArea">
      <div class="areaTop">
        <div class="studentImage">
          <img class="studentImg" src="<?=pdfimagelink($parents->photo)?>" alt="">
        </div>
        <div class="studentProfile">
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('parents_name')?></div>
            <div class="single_value">: <?=$parents->name?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('parents_type')?></div>
            <div class="single_value">: <?=count($usertype) ? $usertype->usertype : '' ?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('parents_phone')?></div>
            <div class="single_value">: <?=$parents->phone?></div>
          </div>
        </div>
      </div>
      <div class="areaBottom">
        <table class="table table-bordered">
          <tr>
            <td width="30%"><?=$this->lang->line('parents_father_name')?></td>
            <td width="70%"><?=$parents->father_name?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('parents_father_profession')?></td>
            <td width="70%"><?=$parents->father_profession?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('parents_mother_name')?></td>
            <td width="70%"><?=$parents->mother_name?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('parents_mother_profession')?></td>
            <td width="70%"><?=$parents->mother_profession?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('parents_email')?></td>
            <td width="70%"><?=$parents->email?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('parents_address')?></td>
            <td width="70%"><?=$parents->address?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('parents_username')?></td>
            <td width="70%"><?=$parents->username?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <?=featurefooter($siteinfos)?>
</body>
</html>