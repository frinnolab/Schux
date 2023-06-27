<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <?php if(count($applicant)) { ?>
        <div class="profileArea">
            <?=featureheader($siteinfos)?>
                <div class="mainArea">
                    <div class="areaTop">
                        <div class="studentImage">
                            <img class="studentImg" src="<?=pdfimagelink($applicant->photo)?>" alt="">
                        </div>
                        <div class="studentProfile">
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_name')?></div>
                                <div class="single_value">: <?=ucwords($userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->name) ?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_type')?></div>
                                <div class="single_value">: <?=isset($usertypes[$leaveapply->applicationto_usertypeID]) ? $usertypes[$leaveapply->applicationto_usertypeID] : ''?></div>
                            </div>
                            <?php if($applicant->usertypeID == 4) { ?>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_phone')?></div>
                                    <div class="single_value">: <?= $userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->phone?></div>
                                </div>
                            <?php } elseif($applicant->usertypeID == 3) { ?> 
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_registerNO')?></div>
                                    <div class="single_value">: <?=$applicant->srregisterNO?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_roll')?></div>
                                    <div class="single_value">: <?=$applicant->srroll?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_class')?></div>
                                    <div class="single_value">: <?=$applicant->srclasses?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_section')?></div>
                                    <div class="single_value">: <?=$applicant->srsection?></div>
                                </div>
                            <?php } else { ?>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_gender')?></div>
                                    <div class="single_value">: <?= $userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->sex?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_dob')?></div>
                                    <div class="single_value">: <?=date('d M Y',strtotime($userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->dob))?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('leaveapply_phone')?></div>
                                    <div class="single_value">: <?= $userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->phone?></div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="areaBottom">
                        <div class="bottomInfo">
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_create_date')?></div>
                                <div class="single_value">: <?=date("d M Y",strtotime($leaveapply->apply_date));?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_schedule')?></div>
                                <div class="single_value">: <?=date("d M Y",strtotime($leaveapply->from_date)). " on ". $leaveapply->from_time;?> to <br /> <?=date("d M Y",strtotime($leaveapply->to_date)). " on ". $leaveapply->to_time;?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_availableleavedays')?></div>
                                <div class="single_value">: <?=$leaveapply->leaveavabledays;?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_leavedays')?></div>
                                <div class="single_value">: <?=isset($daysArray['leavedayCount']) ? $daysArray['leavedayCount'] : '';?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_holidays')?></div>
                                <div class="single_value">: <?=isset($daysArray['holidayCount']) ? $daysArray['holidayCount'] : '';?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_weekenddays')?></div>
                                <div class="single_value">: <?=isset($daysArray['weekenddayCount']) ? $daysArray['weekenddayCount'] : '';?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_totaldays')?></div>
                                <div class="single_value">: <?=isset($daysArray['totaldayCount']) ? $daysArray['totaldayCount'] : '';?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_category')?></div>
                                <div class="single_value">: <?=$leaveapply->category;?></div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_od_status')?></div>
                                <div class="single_value">: 
                                    <?php if($leaveapply->od_status == null || $leaveapply->od_status == 0) { ?>
                                        <?=$this->lang->line('leaveapply_od_status_no');?>
                                    <?php } else { ?>
                                        <?=$this->lang->line('leaveapply_od_status_yes');?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('leaveapply_application_status')?></div>
                                <div class="single_value">: 
                                    <?php if($leaveapply->status == null) { 
                                        echo $this->lang->line('leaveapply_pending');
                                    } elseif($leaveapply->status == 1) { 
                                        echo $this->lang->line('leaveapply_approved');
                                    } else { 
                                        echo $this->lang->line('leaveapply_declined');
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="bottomDescribe">
                            <p><?=$leaveapply->reason?></p>
                        </div>
                    </div>
                </div>
        </div>
        <?=featurefooter($siteinfos)?>
    <?php } ?>
</body>
</html>