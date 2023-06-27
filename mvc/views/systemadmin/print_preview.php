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
                    <img class="studentImg" src="<?=pdfimagelink($systemadmin->photo)?>" alt="">
                </div>
                <div class="studentProfile">
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('systemadmin_name')?></div>
                        <div class="single_value">: <?=$systemadmin->name?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('systemadmin_type2')?></div>
                        <div class="single_value">: <?=count($usertype) ? $usertype->usertype : '' ?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('systemadmin_sex')?></div>
                        <div class="single_value">: <?=$systemadmin->sex?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('systemadmin_dob')?></div>
                        <div class="single_value">: <?php if($systemadmin->dob) { echo date("d M Y", strtotime($systemadmin->dob)); } ?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('systemadmin_phone')?></div>
                        <div class="single_value">: <?=$systemadmin->phone?></div>
                    </div>
                </div>
            </div>
            <div class="areaBottom">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><?=$this->lang->line('systemadmin_jod')?></td>
                        <td width="70%"><?php if($systemadmin->jod) { echo date("d M Y", strtotime($systemadmin->jod)); } ?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('systemadmin_religion')?></td>
                        <td width="70%"><?=$systemadmin->religion?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('systemadmin_email')?></td>
                        <td width="70%"><?=$systemadmin->email?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('systemadmin_address')?></td>
                        <td width="70%"><?=$systemadmin->address?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('systemadmin_username')?></td>
                        <td width="70%"><?=$systemadmin->username?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php featurefooter($siteinfos)?>
</body>
</html>