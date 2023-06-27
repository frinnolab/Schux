
<div class="row">
    <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
        <?php if(permissionChecker('activities_add')) { ?>
            <div class="col-lg-4">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-fighter-jet"></i> <?=$this->lang->line('panel_title')?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php $colors = array("maroon", "green", "aqua", "blue", "olive", "navy", "purple", "black");?>
                                <?php foreach($activitiescategories as $category) { ?>
                                    <?php $randIndex = array_rand($colors); ?>
                                    <a href="<?=base_url('activities/add/'.$category->activitiescategoryID)?>" class="col-sm-1 btn btn-app bg-<?=$colors[$randIndex];?>">
                                        <i class="fa <?=$category->fa_icon?>"></i> <?=$category->title?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if(count($activities) > 0) { $i = 0; foreach ($activities as $activity) { if(isset($user[$activity->usertypeID][$activity->userID])) { ?>
        <div class="col-md-8  <?php if(permissionChecker('activities_add')) { echo ' activity-padd-left '; } ?> <?php if($i > 0) { if(permissionChecker('activities_add')) { echo " col-lg-offset-4"; }} ?> <?php if(($i == 0) && (permissionChecker('activities_add')) && (($this->session->userdata('usertypeID') != 1) && $siteinfos->school_year != $this->session->userdata('defaultschoolyearID')) ) { echo " col-lg-offset-4"; } ?>" >
            <div class="box box-widget" style="margin-bottom: 25px;">
                <div class="box-header with-border social-media">
                    <div class="user-block">
                        <img class="img-circle" src="<?=isset($user[$activity->usertypeID][$activity->userID]) ?  imagelink($user[$activity->usertypeID][$activity->userID]->photo) : imagelink('default.png')?>" alt="User Image">
                        <span class="username"><a href="#"><?=$user[$activity->usertypeID][$activity->userID]->name?></a></span>
                        <span class="description"><?=$this->lang->line('activities_shared_publicly')?> - <?php echo date("l jS \of M Y h:i:s A", strtotime($activity->create_date));?></span>
                    </div>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                            <?php if (permissionChecker('activities_delete') && ($usertypeID == 1 || ($usertypeID == $activity->usertypeID && $userID == $activity->userID))) { ?>
                                <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" class="btn btn-box-tool" href="<?=base_url('activities/delete/'.$activity->activitiesID)?>"><i class="fa fa-times"></i></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>

                <div class="box-body">
                    <div class="container-fluid no-padding">
                        <div class="row">
                            <?php $status = TRUE; $k = 0; if(isset($activitiesmedia[$activity->activitiesID])) { foreach ($activitiesmedia[$activity->activitiesID] as $attachment) { ?>
                                <?php if(count($activitiesmedia[$activity->activitiesID]) == 2) { $status = FALSE; ?>
                                    <div class="col-md-6 no-padding">
                                        <span class="activity-icon-box"><i class="fa <?=isset($activitiescategories[$activity->activitiescategoryID]) ? $activitiescategories[$activity->activitiescategoryID]->fa_icon : ''?>"></i></span>
                                        <img style="width: 100%;height:300px" class="img-responsive pad" src="<?=base_url("uploads/activities/$attachment->attachment"); ?>" alt="Photo">
                                    </div>
                                <?php $k++; } elseif(count($activitiesmedia[$activity->activitiesID]) > 1) { if($status) { ?>
                                    <div class="col-md-4 no-padding">
                                        <span class="activity-icon-box"><i class="fa <?=isset($activitiescategories[$activity->activitiescategoryID]) ? $activitiescategories[$activity->activitiescategoryID]->fa_icon : ''?>"></i></span>
                                        <img style="width: 100%;height:200px" class="img-responsive pad" src="<?=base_url("uploads/activities/$attachment->attachment"); ?>" alt="Photo">
                                    </div>
                                <?php } } else { ?>
                                    <div class="col-md-12 no-padding">
                                        <span class="activity-icon-box"><i class="fa <?=isset($activitiescategories[$activity->activitiescategoryID]) ? $activitiescategories[$activity->activitiescategoryID]->fa_icon : ''?>"></i></span>
                                        <img style="width: 100%; max-height: 450px;" class="img-responsive pad" src="<?=base_url("uploads/activities/$attachment->attachment"); ?>" alt="Photo">
                                    </div>
                            <?php }}} ?>
                        </div>
                    </div>
                    <p><?=$activity->description?></p>
                    <p><?=$this->lang->line('activities_location')?> : <?=$activity->location?></p>
                    <p><?=$this->lang->line('activities_date')?> : <?=$activity->date?></p>
                    <p><?=$this->lang->line('activities_time_at')?> : <?=$activity->time_at?></p>
                    <?php if($activity->time_from) { ?>
                        <button type="button" class="btn btn-info btn-xs"><i class="fa fa-clock-o"></i> <?php echo date("h:i:s A", strtotime($activity->time_from));?></button> - 
                    <?php } ?>
                    <?php if($activity->time_to) { ?>
                        <button type="button" class="btn btn-info btn-xs"><i class="fa fa-clock-o"></i> <?php echo date("h:i:s A", strtotime($activity->time_to));?></button>
                    <?php } ?>
                    <!-- <?php if($activity->time_at) { ?>
                        <button type="button" class="btn btn-info btn-xs"><i class="fa fa-clock-o"></i> <?php echo date("h:i:s A", strtotime($activity->time_at));?></button>
                    <?php } ?> -->
                    <span class="pull-right text-muted">
                        <?php 
                            if(isset($activitiescomments[$activity->activitiesID])) {
                                if(count($activitiescomments[$activity->activitiesID]) == 1) { 
                                    echo count($activitiescomments[$activity->activitiesID]).' '.$this->lang->line('activities_comment');
                                } else {
                                    echo count($activitiescomments[$activity->activitiesID]).' '.$this->lang->line('activities_comments');
                                }
                            } 
                        ?>
                    </span>
                </div>

                <?php if(isset($activitiescomments[$activity->activitiesID])) { $i = 0; ?>
                    <div class="box-footer box-comments">
                        <?php foreach ($activitiescomments[$activity->activitiesID] as $comment) { if(isset($user[$comment->usertypeID][$comment->userID])) { ?>
                            <div class="box-comment">
                                <img class="img-circle img-sm" src="<?=imagelink($user[$comment->usertypeID][$comment->userID]->photo)?>">
                                <div class="comment-text">
                                    <span class="username">
                                        <?=$user[$comment->usertypeID][$comment->userID]->name?>
                                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                            <?php if (permissionChecker('activities_delete') && ($usertypeID == 1 || ($usertypeID == $activity->usertypeID && $userID == $activity->userID))) { ?>
                                                <a href="<?=base_url('activities/delete_comment/'.$comment->activitiescommentID)?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" style="margin-left: 5px; margin-top: -4px; font-size: 15px;" class="text-muted pull-right text-danger"><i class="fa fa-trash"></i></a> &nbsp;&nbsp;&nbsp;
                                            <?php } ?>
                                        <?php } ?>
                                        <span class="text-muted pull-right">
                                            <?php echo timelefter($comment->create_date); ?>
                                        </span> &nbsp;&nbsp;&nbsp;
                                    </span>
                                    <?=$comment->comment?>
                                </div>
                            </div>
                        <?php } } ?>
                    </div>
                <?php } ?>

                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                    <div class="box-footer">
                        <form action="<?=base_url('activities/index/'.$activity->activitiesID)?>" method="post">
                            <img class="img-responsive img-circle img-sm" src="<?=imagelink($this->session->userdata('photo'))?>">
                            <div class="img-push">
                                <input type="text" name="comment" class="form-control input-sm" placeholder="Press enter to post comment">
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php $i++; } } } ?>
</div>

