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
                    <img class="studentImg" src="<?=pdfimagelink($applicant->photo)?>" alt="">
                </div>
                <div class="studentProfile">
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_name')?></div>
                        <div class="single_value">: <?= ucwords($allUser[$leaveapplication->applicationto_usertypeID][$leaveapplication->applicationto_userID]->name) ?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_type')?></div>
                        <div class="single_value">: <?=isset($usertypes[$leaveapplication->applicationto_usertypeID]) ? $usertypes[$leaveapplication->applicationto_usertypeID] : ''?></div>
                    </div>
                    <?php if($applicant->usertypeID == 4) { ?>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_phone')?></div>
                            <div class="single_value">: <?= $allUser[$leaveapplication->applicationto_usertypeID][$leaveapplication->applicationto_userID]->phone?></div>
                        </div>
                    <?php } elseif($applicant->usertypeID == 3) { ?> 
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_registerNO')?></div>
                            <div class="single_value">: <?=$applicant->srregisterNO?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_roll')?></div>
                            <div class="single_value">: <?=$applicant->srroll?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_class')?></div>
                            <div class="single_value">: <?=$applicant->srclasses?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_section')?></div>
                            <div class="single_value">: <?=$applicant->srsection?></div>
                        </div>
                    <?php } else { ?>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_gender')?></div>
                            <div class="single_value">: <?= $allUser[$leaveapplication->applicationto_usertypeID][$leaveapplication->applicationto_userID]->sex ?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_dob')?></div>
                            <div class="single_value">: <?=date('d M Y',strtotime($allUser[$leaveapplication->applicationto_usertypeID][$leaveapplication->applicationto_userID]->dob))?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('leaveapplication_phone')?></div>
                            <div class="single_value">: <?= $allUser[$leaveapplication->applicationto_usertypeID][$leaveapplication->applicationto_userID]->phone?></div>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="areaBottom">
                <div class="bottomInfo">
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_create_date')?></div>
                        <div class="single_value">: <?=date("d M Y",strtotime($leaveapplication->apply_date));?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_schedule')?></div>
                        <div class="single_value">: <?=date("d M Y",strtotime($leaveapplication->from_date)). " on ". $leaveapplication->from_time;?> to <br /> <?=date("d M Y",strtotime($leaveapplication->to_date)). " on ". $leaveapplication->to_time;?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_availableleavedays')?></div>
                        <div class="single_value">: <?=$leaveapplication->leaveavabledays;?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_leavedays')?></div>
                        <div class="single_value">: <?=isset($daysArray['leavedayCount']) ? $daysArray['leavedayCount'] : '';?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_holidays')?></div>
                        <div class="single_value">: <?=isset($daysArray['holidayCount']) ? $daysArray['holidayCount'] : '';?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_weekenddays')?></div>
                        <div class="single_value">: <?=isset($daysArray['weekenddayCount']) ? $daysArray['weekenddayCount'] : '';?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_total_days')?></div>
                        <div class="single_value">: <?=isset($daysArray['totaldayCount']) ? $daysArray['totaldayCount'] : '';?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_category')?></div>
                        <div class="single_value">: <?=$leaveapplication->category;?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_od_status')?></div>
                        <div class="single_value">: 
                            <?php if($leaveapplication->od_status == null || $leaveapplication->od_status == 0) { ?>
                                <?=$this->lang->line('leaveapplication_od_status_no');?>
                            <?php } else { ?>
                                <?=$this->lang->line('leaveapplication_od_status_yes');?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('leaveapplication_application_status')?></div>
                        <div class="single_value">: 
                            <?php if($leaveapplication->status == null) { 
                                    echo $this->lang->line('leaveapplication_status_pending');
                                } elseif($leaveapplication->status == 1) { 
                                    echo $this->lang->line('leaveapplication_status_approved');
                                } else {
                                    echo $this->lang->line('leaveapplication_status_declined');
                                } 
                            ?>
                        </div>
                    </div>
                </div>
                <div class="bottomDescribe">
                    <p><?=$leaveapplication->reason?></p>
                </div>
            </div>
        </div>
    </div>
    <?=featurefooter($siteinfos)?>
</body>
</html>