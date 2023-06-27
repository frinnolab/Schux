<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php if (count($idcards)) { ?>
    <div class="idcardreport">
        <?php foreach($idcards as $idcard) { 
        if($type == 1) { ?>
            <div class="idcardreport-frontend" style="height:290px;<?=($background == 1) ? "background-image:url(".base_url('uploads/default/idcard-border.png').") !important" : '' ?>">
                <h3><?=$siteinfos->sname?></h3>
                <div class="idcardreport-image">
                    <img src='<?=imagelink($idcard->photo)?>' alt="">
                </div>
                <?php if($usertypeID == 1) { ?>
                    <div class="idcardreport-info">
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_name')?></b></div>
                            <div class="right">: <?=$idcard->name?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_dob')?></b></div>
                            <div class="right">: <?=date('d M Y',strtotime($idcard->dob))?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_jod')?></b></div>
                            <div class="right">: <?=date('d M Y',strtotime($idcard->jod))?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_phone')?></b></div>
                            <div class="right">: <?=$idcard->phone?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_email')?></b></div>
                            <div class="right">:<?=$idcard->email?></div>
                        </div>
                    </div>
                <?php } elseif($usertypeID == 2) { ?>
                    <div class="idcardreport-info">
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_name')?></b></div>
                            <div class="right">: <?=$idcard->name?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_designation')?></b></div>
                            <div class="right">: <?=$idcard->designation?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_dob')?></b></div>
                            <div class="right">: <?=date('d M Y',strtotime($idcard->dob))?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_jod')?></b></div>
                            <div class="right">: <?=date('d M Y',strtotime($idcard->jod))?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_phone')?></b></div>
                            <div class="right">: <?=$idcard->phone?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_email')?></b></div>
                            <div class="right">:<?=$idcard->email?></div>
                        </div>
                    </div>
                <?php } elseif($usertypeID == 3) { ?>
                    <div class="idcardreport-info">
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_name')?></b></div>
                            <div class="right">: <?=$idcard->srname?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_registerNO')?></b></div>
                            <div class="right">: <?=$idcard->srregisterNO?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_class')?></b></div>
                            <div class="right">: <?=isset($classes[$idcard->srclassesID]) ? $classes[$idcard->srclassesID] : ''?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_section')?></b></div>
                            <div class="right">: <?=isset($sections[$idcard->srsectionID]) ? $sections[$idcard->srsectionID] : ''?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_roll')?></b></div>
                            <div class="right">: <?=$idcard->srroll?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_blood_group')?></b></div>
                            <div class="right">: <?=$idcard->bloodgroup?></div>
                        </div>
                    </div>
                <?php } elseif($usertypeID == 4) { ?>
                    <div class="idcardreport-info">
                        
                    </div>
                <?php }  else { ?>
                    <div class="idcardreport-info">
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_name')?></b></div>
                            <div class="right">: <?=$idcard->name?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_designation')?></b></div>
                            <div class="right">: <?=$idcard->designation?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_dob')?></b></div>
                            <div class="right">: <?=date('d M Y',strtotime($idcard->dob))?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_jod')?></b></div>
                            <div class="right">: <?=date('d M Y',strtotime($idcard->jod))?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_phone')?></b></div>
                            <div class="right">: <?=$idcard->phone?></div>
                        </div>
                        <div class="idcardreport-item">
                            <div class="left"><b><?=$this->lang->line('idcardreport_email')?></b></div>
                            <div class="right">:<?=$idcard->email?></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } elseif($type = 2) { 
                if($usertypeID == 1) {
                    $filename = $idcard->usertypeID.'-'.$idcard->systemadminID;
                    $text = $this->lang->line('idcardreport_type')." : ".'1'.',';
                    $text .= $this->lang->line('idcardreport_id')." : ".$idcard->systemadminID;
                } elseif($usertypeID == 2) {
                    $filename = $idcard->usertypeID.'-'.$idcard->teacherID;
                    $text = $this->lang->line('idcardreport_type')." : ".'2'.',';
                    $text .= $this->lang->line('idcardreport_id')." : ".$idcard->teacherID.',';
                } elseif($usertypeID == 3) {
                    $filename = $idcard->usertypeID.'-'.$idcard->studentID;
                    $text = $this->lang->line('idcardreport_type')." : ".'3'.',';
                    $text .= $this->lang->line('idcardreport_id')." : ".$idcard->srstudentID;
                } elseif($usertypeID == 4) {
                    $filename = $idcard->usertypeID.'-'.$idcard->parentsID;
                    $text = "invalid";
                } else {
                    $filename = $idcard->usertypeID.'-'.$idcard->userID;
                    $text = $this->lang->line('idcardreport_type')." : ".$idcard->usertypeID.',';
                    $text .= $this->lang->line('idcardreport_id')." : ".$idcard->userID;
                }

                $filepath = FCPATH.'uploads/idQRcode/'.$filename.'.png';
                if(!file_exists($filepath)) {
                    generate_qrcode($text,$filename);
                }
            ?>
            <div class="idcardreport-backend" style="height:290px;<?=($background == 1) ? "background-image:url(".base_url('uploads/default/idcard-border.png').") !important" : '' ?>">
                <h3><?=$this->lang->line('idcardreport_valid_up')?> <?=date('F Y',strtotime($schoolyear->endingdate))?></h3>
                <h4><?=$this->lang->line('idcardreport_please_return')?> : </h4>
                <p><?=$siteinfos->sname?></p>
                <div class="idcardreport-schooladdress">
                    <?=$siteinfos->address?>
                </div>
                <div class="idcardreport-bottom">
                    <div class="idcardreport-qrcode">
                        <img style="width:90px;height: 80px; padding-top: 12px" src="<?=base_url('uploads/idQRcode/'.$filename.'.png')?>" alt="">
                    </div>
                    <div class="idcardreport-session">
                        <span><?=$this->lang->line('idcardreport_session')?>: <?=$schoolyear->schoolyear?></span>
                    </div>
                </div>
            </div>
        <?php } } ?>
    </div>
<?php } ?>
</body>
</html>