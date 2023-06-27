<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
    <?php if(count($complain)) { ?>
        <div class="profileArea">
            <?=featureheader($siteinfos)?>
            <div class="mainArea">
                <?php if(count($user)) { ?>
                    <div class="areaTop">
                        <div class="studentImage">
                            <img class="studentImg" src="<?=base_url('uploads/images/'.$user->photo)?>" alt="">
                        </div>
                        <div class="studentProfile">
                            <div class="singleItem">
                                <div class="single_label"><?=$this->lang->line('complain_name')?></div>
                                <div class="single_value">: 
                                    <?php 
                                        if($user->usertypeID == 3) {
                                            echo $user->srname;
                                        } else {
                                            echo $user->name;
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php if($user->usertypeID == 2) { ?>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_designation')?></div>
                                    <div class="single_value">: <?=$user->designation?></div>
                                </div>
                            <?php } else { ?>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_type')?></div>
                                    <div class="single_value">: <?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : ''?></div>
                                </div>
                            <?php } ?>

                            <?php if($user->usertypeID == 4) { ?>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_phone')?></div>
                                    <div class="single_value">: <?=$user->phone?></div>
                                </div>
                            <?php } elseif($user->usertypeID == 3) { ?> 
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_registerNO')?></div>
                                    <div class="single_value">: <?=$user->srregisterNO?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_roll')?></div>
                                    <div class="single_value">: <?=$user->srroll?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_class')?></div>
                                    <div class="single_value">: <?=count($classes) ? $classes->classes : ''?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_section')?></div>
                                    <div class="single_value">: <?=count($section) ? $section->section : ''?></div>
                                </div>
                            <?php } else { ?>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_gender')?></div>
                                    <div class="single_value">: <?=$user->sex?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_dob')?></div>
                                    <div class="single_value">: <?=date('d M Y',strtotime($user->dob))?></div>
                                </div>
                                <div class="singleItem">
                                    <div class="single_label"><?=$this->lang->line('complain_phone')?></div>
                                    <div class="single_value">: <?=$user->phone?></div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                <?php } ?>

                <div class="areaBottom">
                    <div class="bottomInfo">
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('complain_create_date')?></div>
                            <div class="single_value">: <?=date("d M Y - h:i:s A",strtotime($complain->create_date));?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('complain_modify_date')?></div>
                            <div class="single_value">: <?=date("d M Y - h:i:s A",strtotime($complain->modify_date));?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('complain_complainer_name')?></div>
                            <div class="single_value">: <?=count($createinfo) ? $createinfo->name : ''?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('complain_complainer_role')?></div>
                            <div class="single_value">: <?=count($createinfo) ? $createinfo->usertype : ''?></div>
                        </div>
                    </div>
                    <div class="bottomDescribe">
                        <h3><?=$complain->title?></h3>
                        <p><?=$complain->description?></p>
                    </div>
                </div>
            </div>
        </div>
        <?=featurefooter($siteinfos)?>
    <?php } ?>
</body>
</html>