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
                    <img class="studentImg" src="<?=pdfimagelink($admissioninfo->photo)?>" alt="">
                </div>
                <div class="studentProfile">
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('onlineadmission_name')?></div>
                        <div class="single_value">: <?=$admissioninfo->name?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('onlineadmission_type')?></div>
                        <div class="single_value">: <?=$usertype->usertype?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('onlineadmission_admissionID')?></div>
                        <div class="single_value">: 
                            <?php
                                $admissionIDlen = strlen($admissioninfo->onlineadmissionID);
                                $boxLimit = 8;

                                if($admissionIDlen >= $boxLimit) {
                                    $boxLimit += 2;
                                }

                                $zerolength = ($boxLimit - $admissionIDlen);
                                if($zerolength > 0) {
                                    for($i=1; $i <= $zerolength; $i++) {
                                        echo '0';
                                    }
                                }
                                $admissionIDArray = str_split($admissioninfo->onlineadmissionID);
                                if(count($admissionIDArray)) {
                                    foreach ($admissionIDArray as $value) {
                                        echo $value;
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('onlineadmission_apply_classes')?></div>
                        <div class="single_value">: <?=count($classes) ? $classes->classes : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('onlineadmission_status')?></div>
                        <div class="single_value">: <?=($admissioninfo->status == 2) ? $this->lang->line('onlineadmission_waiting') : $this->lang->line('onlineadmission_new')?></div>
                    </div>
                </div>
            </div>
            <div class="areaBottom">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><?=$this->lang->line('onlineadmission_dob')?></td>
                        <td width="70%"><?php if($admissioninfo->dob) { echo date("d M Y", strtotime($admissioninfo->dob)); } ?></td>
                    </tr>
                    
                    <tr>
                        <td width="30%"><?=$this->lang->line("onlineadmission_gender")?></td>
                        <td width="70%"><?=$admissioninfo->sex?></td>
                    </tr>
                    
                    <tr>
                        <td width="30%"><?=$this->lang->line('onlineadmission_religion')?></td>
                        <td width="70%"><?=$admissioninfo->religion?></td>
                    </tr>
                    
                    <tr>
                        <td width="30%"><?=$this->lang->line('onlineadmission_email')?></td>
                        <td width="70%"><?=$admissioninfo->email?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('onlineadmission_phone')?></td>
                        <td width="70%"><?=$admissioninfo->phone?></td>
                    </tr>
                  
                    <tr>
                        <td width="30%"><?=$this->lang->line('onlineadmission_country')?></td>
                        <td width="70%"><?php if(isset($allcountry[$admissioninfo->country])) { echo $allcountry[$admissioninfo->country]; } ?></td>
                    </tr>
                    
                    <tr>
                        <td width="30%"><?=$this->lang->line('onlineadmission_address')?></td>
                        <td width="70%"><?=$admissioninfo->address?></td>
                  </tr>
                </table>
            </div>
        </div>
  </div>
  <?php featurefooter($siteinfos)?>
</body>
</html>