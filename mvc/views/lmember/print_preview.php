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
                    <img class="studentImg" src="<?=pdfimagelink($student->photo)?>" alt="">
                </div>
                <div class="studentProfile">
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('lmember_name')?></div>
                        <div class="single_value">: <?=$student->srname?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('lmember_type')?></div>
                        <div class="single_value">: <?=isset($usertypes[$student->usertypeID]) ? $usertypes[$student->usertypeID] : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('lmember_registerNO')?></div>
                        <div class="single_value">: <?=$student->srregisterNO?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('lmember_roll')?></div>
                        <div class="single_value">: <?=$student->srroll?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('lmember_classes')?></div>
                        <div class="single_value">: <?=count($classes) ? $classes->classes : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('lmember_section')?></div>
                        <div class="single_value">: <?=count($section) ? $section->section : ''?></div>
                    </div>
                </div>
            </div>
            <div class="areaBottom">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_dob')?></td>
                        <td width="70%"><?php if($student->dob) { echo date("d M Y", strtotime($student->dob)); } ?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_sex')?></td>
                        <td width="70%"><?=$student->sex?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_email')?></td>
                        <td width="70%"><?=$student->email?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_phone')?></td>
                        <td width="70%"><?=$student->phone?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_libraryID')?></td>
                        <td width="70%"><?=count($lmember) ? $lmember->lID : 'N/A'?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_libraryFee')?></td>
                        <td width="70%"><?=count($lmember) ? $lmember->lbalance : 'N/A'?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_joinDate')?></td>
                        <td width="70%"><?php if(count($lmember)) { if($lmember->ljoindate) { echo date('d M Y',strtotime($lmember->ljoindate)); } } else { echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_religion')?></td>
                        <td width="70%"><?=$student->religion?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_bloodgroup')?></td>
                        <td width="70%"><?=$student->bloodgroup?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?=$this->lang->line('lmember_address')?></td>
                        <td width="70%"><?=$student->address?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php featurefooter($siteinfos);?>
</body>
</html>