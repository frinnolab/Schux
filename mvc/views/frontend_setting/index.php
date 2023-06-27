

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-asterisk"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_frontend_setting')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->

    <style type="text/css">
        .setting-fieldset {
            border: 1px solid #DBDEE0 !important;
            padding: 15px !important;
            margin: 0 0 25px 0 !important;
            box-shadow: 0px 0px 0px 0px #000;
        }

        .setting-legend {
            font-size: 1.1em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            color: #428BCA;
            padding: 5px 15px;
            border: 1px solid #DBDEE0 !important;
            margin: 0px;
        }
    </style>


    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
        <div class="box-body">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('frontend_setting_frontend_configaration')?></legend>
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('login_menu_status')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="login_menu_status"><?=$this->lang->line("frontend_setting_login_menu_status")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable login menu for frontend top menu"></i>
                                </label>
                                <?php
                                    $loginMenuStatusArray[1] = $this->lang->line('frontend_setting_enable');
                                    $loginMenuStatusArray[0] = $this->lang->line('frontend_setting_disable');
                                    echo form_dropdown("login_menu_status", $loginMenuStatusArray, set_value("login_menu_status", $frontend_setting->login_menu_status), "id='login_menu_status' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('login_menu_status'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('teacher_email_status')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="teacher_email_status"><?=$this->lang->line("frontend_setting_teacher_email")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable teacher email for frontend teahcer list"></i>
                                </label>
                                <?php
                                    $teacherEmailStatusArray[1] = $this->lang->line('frontend_setting_enable');
                                    $teacherEmailStatusArray[0] = $this->lang->line('frontend_setting_disable');
                                    echo form_dropdown("teacher_email_status", $teacherEmailStatusArray, set_value("teacher_email_status", $frontend_setting->teacher_email_status), "id='teacher_email_status' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('teacher_email_status'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('teacher_phone_status')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="teacher_phone_status"><?=$this->lang->line("frontend_setting_teacher_phone")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable teacher phone for frontend teahcer list"></i>
                                </label>
                                <?php
                                    $teacherPhoneStatusArray[1] = $this->lang->line('frontend_setting_enable');
                                    $teacherPhoneStatusArray[0] = $this->lang->line('frontend_setting_disable');
                                    echo form_dropdown("teacher_phone_status", $teacherPhoneStatusArray, set_value("teacher_phone_status", $frontend_setting->teacher_phone_status), "id='teacher_phone_status' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('teacher_phone_status'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('online_admission_status') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="online_admission_status"><?=$this->lang->line("frontend_setting_onlineadmission")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Enable/Disable for Online Admission"></i>
                                </label>
                                <?php
                                    $onlineadmissionArray[1] = $this->lang->line('frontend_setting_enable');
                                    $onlineadmissionArray[0] = $this->lang->line('frontend_setting_disable');
                                    echo form_dropdown("online_admission_status", $onlineadmissionArray, set_value("online_admission_status",$frontend_setting->online_admission_status), "id='online_admission_status' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?=form_error('online_admission_status'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('description') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="description"><?=$this->lang->line("frontend_setting_description")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set frontend footer short description"></i>
                                </label>
                                <textarea class="form-control" style="resize:none;" id="description" name="description"><?=set_value('description', $frontend_setting->description)?></textarea>
                                <span class="control-label">
                                    <?=form_error('description'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('frontend_setting_social')?></legend>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('facebook')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="facebook"><?=$this->lang->line("frontend_setting_facebook")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Facebook Link for frontend"></i>
                                </label>
                                <input type="text" class="form-control" id="facebook" name="facebook" value="<?=set_value('facebook', $frontend_setting->facebook)?>" >
                                <span class="control-label">
                                    <?php echo form_error('facebook'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('twitter')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="twitter">
                                    <?=$this->lang->line("frontend_setting_twitter")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Twitter Link for frontend"></i>
                                </label>
                                <input type="text" class="form-control" id="twitter" name="twitter" value="<?=set_value('twitter', $frontend_setting->twitter)?>" >
                                <span class="control-label">
                                    <?php echo form_error('twitter'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('linkedin')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="linkedin">
                                    <?=$this->lang->line("frontend_setting_linkedin")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Linkedin Link for frontend"></i>
                                </label>
                                <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?=set_value('linkedin', $frontend_setting->linkedin)?>" >
                                <span class="control-label">
                                    <?php echo form_error('linkedin'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('youtube')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="youtube">
                                    <?=$this->lang->line("frontend_setting_youtube")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Youtube Link for frontend"></i>
                                </label>
                                <input type="text" class="form-control" id="youtube" name="youtube" value="<?=set_value('youtube', $frontend_setting->youtube)?>" >
                                <span class="control-label">
                                    <?php echo form_error('youtube'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('google')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label for="google">
                                    <?=$this->lang->line("frontend_setting_google")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Google + Link for frontend"></i>
                                </label>
                                <input type="text" class="form-control" id="google" name="google" value="<?=set_value('google', $frontend_setting->google)?>" >
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('google'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group">
                <div class="col-sm-8">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_frontend_setting")?>" >
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $('.select2').select2();
</script>