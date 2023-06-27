<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-gears"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_setting')?></li>
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
                <legend class="setting-legend"><?=$this->lang->line('setting_site_configaration')?></legend>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('sname') ? 'has-error' : ''?>" >
                            <div class="col-sm-12">
                                <label for="sname"><?=$this->lang->line("setting_school_name")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="set your site title here"></i>
                                </label>
                                <input type="text" class="form-control" id="sname" name="sname" value="<?=set_value('sname', $setting->sname)?>" />
                                <span class="control-label"><?=form_error('sname'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('phone') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="phone"><?=$this->lang->line("setting_school_phone")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization phone number here"></i>
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $setting->phone)?>" >
                                <span class="control-label"><?=form_error('phone'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('email') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="email"><?=$this->lang->line("setting_school_email")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization email address here"></i>
                                </label>
                                <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $setting->email)?>" >
                                <span class="control-label"><?=form_error('email'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('address') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="address"><?=$this->lang->line("setting_school_address")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization address here"></i>
                                </label>
                                <textarea class="form-control" style="resize:none;" id="address" name="address"><?=set_value('address', $setting->address)?></textarea>
                                <span class="control-label">
                                    <?=form_error('address'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                      
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('footer') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="footer"><?=$this->lang->line("setting_school_footer")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set site footer text here"></i>
                                </label>
                                <input type="text" class="form-control" id="footer" name="footer" value="<?=set_value('footer', $setting->footer)?>" >
                                <span class="control-label">
                                    <?=form_error('footer'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('currency_code') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="currency_code">
                                    <?=$this->lang->line("setting_school_currency_code")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set organization currency code like USD or GBP"></i>
                                </label>
                                <input type="text" class="form-control" id="currency_code" name="currency_code" value="<?=set_value('currency_code', $setting->currency_code)?>" >
                                <span class="control-label">
                                    <?=form_error('currency_code'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('currency_symbol') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="currency_symbol"><?=$this->lang->line("setting_school_currency_symbol")?> &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set organization currency system here like $ or £"></i>
                                </label>
                                <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="<?=set_value('currency_symbol', $setting->currency_symbol)?>" >
                                <span class="control-label">
                                    <?=form_error('currency_symbol'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('language_status')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label><?=$this->lang->line("setting_language")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable language for top section"></i>
                                </label>
                                <?php
                                    $languageArray[0] = $this->lang->line('setting_enable');
                                    $languageArray[1] = $this->lang->line('setting_disable');
                                    echo form_dropdown("language_status", $languageArray, set_value("language_status",$setting->language_status), "id='language_status' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('language_status'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('lang') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="lang"><?=$this->lang->line("setting_dafault_language")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Select organization default language here"></i>
                                </label>
                                <?php
                                    echo form_dropdown("language", array("english" => $this->lang->line("setting_english"),
                                    "bengali" => $this->lang->line("setting_bengali"),
                                    "arabic" => $this->lang->line("setting_arabic"),
                                    "chinese" => $this->lang->line("setting_chinese"),
                                    "french" => $this->lang->line("setting_french"),
                                    "german" => $this->lang->line("setting_german"),
                                    "hindi" => $this->lang->line("setting_hindi"),
                                    "indonesian" => $this->lang->line("setting_indonesian"),
                                    "italian" => $this->lang->line("setting_italian"),
                                    "portuguese" => $this->lang->line("setting_portuguese"),
                                    "romanian" => $this->lang->line("setting_romanian"),
                                    "russian" => $this->lang->line("setting_russian"),
                                    "spanish" => $this->lang->line("setting_spanish"),
                                    "thai" => $this->lang->line("setting_thai"),
                                    "turkish" => $this->lang->line("setting_turkish"),
                                    ),
                                    set_value("lang", $setting->language), "id='lang' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?=form_error('lang'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('school_year') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="school_year"><?=$this->lang->line("setting_school_default_school_year")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Select school running academic year"></i>
                                </label>
                                <?php
                                    $array = array(
                                        "0" => $this->lang->line("setting_school_select_school_year")  
                                    );
                                    if(count($schoolyears)) {
                                        foreach ($schoolyears as $key => $schoolyear) {
                                            if($schoolyear->schooltype == 'semesterbase') {
                                                $array[$schoolyear->schoolyearID] = $schoolyear->schoolyeartitle.' ('.$schoolyear->schoolyear.')'; 
                                            } else {
                                                $array[$schoolyear->schoolyearID] = $schoolyear->schoolyear;    
                                            }
                                        }
                                         
                                    }
                                    echo form_dropdown("school_year", $array, set_value("school_year",$setting->school_year), "id='school_year' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?=form_error('school_year'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('attendance') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="attendance"><?=$this->lang->line("setting_school_default_attendance")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Select school default attendance system"></i></label>
                                <?php
                                    $array = array(
                                        "0" => $this->lang->line("setting_school_select_attendance"),
                                        "day" => $this->lang->line("setting_school_select_day_attendance"),
                                        "subject" => $this->lang->line("setting_school_select_subject_attendance")
                                    );
                                    echo form_dropdown("attendance", $array, set_value("attendance",$setting->attendance), "id='attendance' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?=form_error('attendance'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=(form_error('auto_invoice_generate') || form_error('automation')) ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="auto_invoice_generate"><?=$this->lang->line("setting_school_auto_invoice_generate")?>
                                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Enable/Disable library fee, hostel fee, transport fee invoice in student account automatically. it's will be added monthly wise."></i>
                                </label>
                                <div class="row">
                                    <div id="autoinvoicediv" class="">
                                        <?php
                                            $array = array(
                                                "0" => $this->lang->line("setting_school_no"),
                                                "1" => $this->lang->line("setting_school_yes")
                                            );
                                            echo form_dropdown("auto_invoice_generate", $array, set_value("auto_invoice_generate",$setting->auto_invoice_generate), "id='auto_invoice_generate' class='form-control select2'");
                                        ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php
                                            $dayArray = array();
                                            for($i =1; $i<=28; $i++) {
                                                $dayArray[$i] = $i;
                                            }
                                            echo form_dropdown("automation", $dayArray, set_value("automation",$setting->automation), "id='automation' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                                <span class="control-label">
                                    <?php 
                                        if(form_error('auto_invoice_generate')) {
                                            echo form_error('auto_invoice_generate');
                                        } else  {
                                            echo form_error('automation'); 
                                        }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('note') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="note"><?=$this->lang->line("setting_school_note")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Enable/Disable module helper note"></i>
                                </label>
                                <?php
                                    $noteArray[1] = $this->lang->line('setting_enable');
                                    $noteArray[0] = $this->lang->line('setting_disable');
                                    echo form_dropdown("note", $noteArray, set_value("note",$setting->note), "id='note' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?=form_error('note'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('weekends')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                            <label><?=$this->lang->line('setting_weekends')?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Select Weekends"></i></label>
                                <?php
                                    $array = array(
                                        "0" => $this->lang->line("setting_sunday"),
                                        "1" => $this->lang->line("setting_monday"),
                                        "2" => $this->lang->line("setting_tuesday"),
                                        "3" => $this->lang->line("setting_wednesday"),
                                        "4" => $this->lang->line("setting_thursday"),
                                        "5" => $this->lang->line("setting_friday"),
                                        "6" => $this->lang->line("setting_saturday")
                                    );

                                    if(isset($setting->weekends)) {
                                        $expHoliday = explode(',', $setting->weekends);
                                    } else {
                                        $expHoliday = [];
                                    }

                                    echo form_multiselect("weekends[]", $array, set_value('weekends', $expHoliday), "id='weekends' class='form-control'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('weekends[]'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('frontendorbackend') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="frontendorbackend"><?=$this->lang->line("setting_school_frontend")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Enable/Disable frontend site"></i>
                                </label>
                                    <?php
                                        echo form_dropdown("frontendorbackend", array("YES" => $this->lang->line("setting_school_yes"),
                                        "NO" => $this->lang->line("setting_school_no"),
                                        ),
                                        set_value("frontendorbackend", $setting->frontendorbackend), "id='frontendorbackend' class='form-control select2'");
                                    ?>
                                <span class="control-label">
                                    <?=form_error('frontendorbackend'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('google_analytics') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="google_analytics"><?=$this->lang->line("setting_school_google_analytics")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set site google_analytics code"></i>
                                </label>
                                <input type="text" class="form-control" id="google_analytics" name="google_analytics" value="<?=set_value('google_analytics', $setting->google_analytics)?>" >
                                <span class="control-label">
                                    <?php echo form_error('google_analytics'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('profile_edit')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label><?=$this->lang->line("setting_school_profile_edit")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable for profile edit"></i>
                                </label>
                                <?php
                                    $profileEditArray[1] = $this->lang->line('setting_enable');
                                    $profileEditArray[0] = $this->lang->line('setting_disable');
                                    echo form_dropdown("profile_edit", $profileEditArray, set_value("profile_edit",$setting->profile_edit), "id='profile_edit' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('profile_edit'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('time_zone') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="time_zone"><?=$this->lang->line("setting_school_time_zone")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Select your region time zone. We define a time zone as a region where the same standard time is used"></i>
                                </label>
                                    <?php
                                        $path = APPPATH."config/timezones_class.php";
                                        if(@include($path)) {
                                            $timezones_cls = new Timezones();
                                            $timezones = $timezones_cls->get_timezones();
                                            unset($timezones['']);
                                            $selectTimeZone['none'] = $this->lang->line('setting_school_select_time_zone');
                                            $timeZones = array_merge($selectTimeZone, $timezones);

                                            echo form_dropdown("time_zone", $timeZones, set_value("time_zone", $setting->time_zone), "id='time_zone' class='form-control select2'");
                                        }
                                    ?>
                                <span class="control-label">
                                    <?=form_error('time_zone'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('ex_class') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="ex_class"><?=$this->lang->line("setting_graduate_class")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="a graduate or former student class of a specific school"></i>
                                </label>
                                    <?php
                                        $ex_classArray['0'] = $this->lang->line('setting_select_graduate_class');
                                        if(count($classes)) {
                                            foreach($classes as $class) {
                                                $ex_classArray[$class->classesID] = $class->classes;
                                            }
                                        }
                                        echo form_dropdown("ex_class", $ex_classArray, set_value("ex_class", $setting->ex_class), "id='ex_class' class='form-control select2'");
                                    ?>
                                <span class="control-label">
                                    <?=form_error('ex_class'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group <?=form_error('photo') ? 'has-error' : ''?>">
                            <div class="col-sm-12">
                                <label for="photo"><?=$this->lang->line("setting_logo")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set organization logo here"></i>
                                </label>
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                            <span class="fa fa-remove"></span>
                                            <?=$this->lang->line('setting_clear')?>
                                        </button>
                                        <div class="btn btn-success image-preview-input">
                                            <span class="fa fa-repeat"></span>
                                            <span class="image-preview-input-title">
                                            <?=$this->lang->line('setting_file_browse')?></span>
                                            <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                        </div>
                                    </span>
                                </div>
                                <span class="control-label">
                                    <?=form_error('photo'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('setting_auto_update')?></legend>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('auto_update_notification')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label><?=$this->lang->line("setting_auto_update_notification")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable for auto update notification. only main system admin can see the update notification"></i>
                                </label>
                                <?php
                                    $autoupdateArray[1] = $this->lang->line('setting_enable');
                                    $autoupdateArray[0] = $this->lang->line('setting_disable');
                                    echo form_dropdown("auto_update_notification", $autoupdateArray, set_value("auto_update_notification",$setting->auto_update_notification), "id='auto_update_notification' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('auto_update_notification'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('setting_captcha')?></legend>
                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('captcha_status')) { echo 'has-error'; } ?>">
                        <div class="col-sm-12">
                            <label><?=$this->lang->line("setting_captcha")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Check for disable captcha in login"></i>
                            </label>
                            <?php
                                $captchaArray[0] = $this->lang->line('setting_enable');
                                $captchaArray[1] = $this->lang->line('setting_disable');
                                echo form_dropdown("captcha_status", $captchaArray, set_value("captcha_status",$setting->captcha_status), "id='captcha_status' class='form-control select2'");
                            ?>

                            <span class="control-label">
                                <?php echo form_error('captcha_status'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('recaptcha_site_key')) { echo 'has-error'; } ?>" id="recaptcha_site_key_id">
                        <div class="col-sm-12">
                            <label for="recaptcha_site_key">
                                <?=$this->lang->line("setting_school_recaptcha_site_key")?>
                                &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Set recaptcha site key. Becareful If it's invalid then you cann't login."></i>
                            </label>
                            <input type="text" class="form-control" id="recaptcha_site_key" name="recaptcha_site_key" value="<?=set_value('recaptcha_site_key', $setting->recaptcha_site_key)?>" >
                            <span class="control-label">
                                <?php echo form_error('recaptcha_site_key'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('recaptcha_secret_key')) { echo 'has-error'; } ?>" id="recaptcha_secret_key_id" >
                        <div class="col-sm-12">
                            <label for="recaptcha_secret_key"><?=$this->lang->line("setting_school_recaptcha_secret_key")?>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Set recaptcha secret key. Becareful If it's invalid then you cann't login."></i>
                            </label>
                            <input type="text" class="form-control" id="recaptcha_secret_key" name="recaptcha_secret_key" value="<?=set_value('recaptcha_secret_key', $setting->recaptcha_secret_key)?>" >
                            <span class="control-label">
                                <?php echo form_error('recaptcha_secret_key'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line('setting_attendance_notification')?></legend>
                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('attendance_notification')) { echo 'has-error'; } ?>">
                        <div class="col-sm-12">
                            <label><?=$this->lang->line("setting_attendance_notification")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Select Attendance Notification"></i>
                            </label>
                            <?php
                                $array = array(
                                    "none" => $this->lang->line("setting_none"), 
                                    "email" => $this->lang->line("setting_email"), 
                                    "sms" => $this->lang->line("setting_sms")
                                );

                                echo form_dropdown("attendance_notification", $array, set_value("attendance_notification",$setting->attendance_notification), "id='attendance_notification' class='form-control select2'");
                            ?>
                            <span class="control-label">
                                <?php echo form_error('attendance_notification'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4" id="mainSmsDiv">
                    <div class="form-group <?php if(form_error('attendance_smsgateway')) { echo 'has-error'; } ?>" id="attendance_smsgateway_div">
                        <div class="col-sm-12">
                            <label><?=$this->lang->line("setting_attendance_smsgateway")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Select Sms Gateway"></i>
                            </label>
                            <?php
                                $array = array(
                                    "0" => $this->lang->line("setting_select_sms_gateway"), 
                                    "clickatell" => $this->lang->line("setting_clickatell"), 
                                    "twilio" => $this->lang->line("setting_twilio"), 
                                    "bulk" => $this->lang->line("setting_bulk"),
                                    "msg91" => $this->lang->line("setting_msg91")
                                );

                                echo form_dropdown("attendance_smsgateway", $array, set_value("attendance_smsgateway",$setting->attendance_smsgateway), "id='attendance_smsgateway' class='form-control select2'");
                            ?>
                            <span class="control-label">
                                <?php echo form_error('attendance_smsgateway'); ?>
                            </span>
                        </div>
                    </div> 
                </div>

                <div class="col-sm-4">
                    <div class="form-group <?php if(form_error('attendance_notification_template')) { echo 'has-error'; } ?>" id="attendance_notification_template_div">
                        <div class="col-sm-12">
                            <label><?=$this->lang->line("setting_attendance_notification_template")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Select Attendance Notification Template"></i>
                            </label>
                            <?php
                                $attendanceNotificationArray = array(
                                    "0" => $this->lang->line("setting_select_template"), 
                                );

                                if(count($attendance_notification_templates)) {
                                    foreach ($attendance_notification_templates as $attendance_notification_template) {
                                        $attendanceNotificationArray[$attendance_notification_template->mailandsmstemplateID] =  $attendance_notification_template->name;
                                    }
                                }

                                echo form_dropdown("attendance_notification_template", $attendanceNotificationArray, set_value("attendance_notification_template", $setting->attendance_notification_template), "id='attendance_notification_template' class='form-control select2'");
                            ?>
                            <span class="control-label">
                                <?php echo form_error('attendance_notification_template'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group">
                <div class="col-sm-8">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_setting")?>" >
                </div>
            </div>
        </div>
    </form>
</div>

<div class="box" style="margin-bottom: 40px" >
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-th-large"></i> <?=$this->lang->line('backend_theme_setting')?></h3>

    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                
                <ul class="list-unstyled clearfix">
                    <?php 
                        if(count($themes)) {
                            foreach ($themes as $theme) {
                    ?>
                    
                    <li class="backendThemeMainWidht" style="float:left; padding: 5px;">
                        <a id="<?=$theme->themesID?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$theme->themename?>"

                         data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4); cursor: pointer;" class="clearfix full-opacity-hover backendThemeEvent">
                            <div>
                                <span class="backendThemeHeadHeight" style="display:block; width: 20%; float: left; background-color: <?=$theme->topcolor?>" >
                                    
                                </span>

                                <span class="backendThemeHeadHeight" style="display:block; width: 80%; float: left; background-color: <?=$theme->topcolor?>">
                                </span>
                            </div>

                            <div>
                                <span class="backendThemeBodyHeight" style="display:block; width: 20%; float: left; background-color: <?=$theme->leftcolor?>">
                                </span>
                                <span class="backendThemeBodyHeight" style="display:block; width: 80%; float: left; background: #f4f5f7" id="themeBodyContent-<?=strtolower(str_replace(' ', '', $theme->themename))?>">
                                <?php  ?>
                                        <?php if($setting->backend_theme == strtolower(str_replace(' ', '', $theme->themename)))  {?>
                                        <center class="backendThemeBodyMargin">
                                            <button type="button" class="btn btn-danger">
                                                <i  class="fa fa-check-circle"></i>
                                            </button>
                                        </center>
                                        <?php } ?>
                                </span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px">
                            <?=$theme->themename?>
                        </p>
                    </li>


                    <?php            
                            }
                        }
                    ?>
                </ul>

            </div>
        </div>
    </div>
</div>

<?php if(form_error('recaptcha_site_key') || form_error('recaptcha_secret_key')) { ?>
<script type="text/javascript">
    $('#recaptcha_site_key_id').show(); 
    $('#recaptcha_secret_key_id').show();  
</script>
<?php } ?>

<script type="text/javascript">

    <?php if($this->data["siteinfos"]->attendance_notification == 'sms') {?>
        $("#mainSmsDiv").show();
        $("#attendance_smsgateway_div").show();
        $("#attendance_notification_template_div").show();
    <?php } elseif ($this->data["siteinfos"]->attendance_notification == 'email') { ?>
        $("#mainSmsDiv").hide();
        $("#attendance_smsgateway_div").hide();
        $("#attendance_notification_template_div").show();
    <?php  } else {  ?>
        $("#mainSmsDiv").hide();
        $("#attendance_smsgateway_div").hide();
        $("#attendance_notification_template_div").hide();
    <?php } ?>

    <?php if($attendance_notification == 'sms') { ?>
        $("#mainSmsDiv").show();
        $("#attendance_smsgateway_div").show();
        $("#attendance_notification_template_div").show();
    <?php } elseif ($attendance_notification == 'email') { ?>
        $("#mainSmsDiv").hide();
        $("#attendance_smsgateway_div").hide();
        $("#attendance_notification_template_div").show();
    <?php  } else {  ?>
        $("#mainSmsDiv").hide('slow');
        $("#attendance_smsgateway_div").hide();
        $("#attendance_notification_template_div").hide();
    <?php } ?>

    $(document).on('change', "#attendance_notification", function() {
        var value = $(this).val();
        if(value == 'sms') {
            $("#mainSmsDiv").show('slow');
            $("#attendance_smsgateway_div").show('slow');
            $("#attendance_notification_template_div").show('slow');
        } else if(value == 'email') {
            $("#mainSmsDiv").hide('slow');
            $("#attendance_smsgateway_div").hide('slow');
            $("#attendance_notification_template_div").show('slow');
        } else {
            $("#mainSmsDiv").hide('slow');
            $("#attendance_smsgateway_div").hide('slow');
            $("#attendance_notification_template_div").hide('slow');
        }

        if(value == 'sms' || value =='email') {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('setting/getTemplate')?>",
                data: {"value" : value},
                dataType: "html",
                success: function(data) {
                   $('#attendance_notification_template').html(data);
                }
            });
        }
    });

    $(document).ready(function() {
        $('.backendThemeEvent').click(function() {
            var id = $(this).attr('id');
            if(id) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('setting/backendtheme')?>",
                    data: "id=" + id,
                    dataType: "html",
                    success: function(data) {
                        $('#headStyleCSSLink').attr('href', "<?=base_url('assets/inilabs/themes/')?>"+data+"/style.css");
                        $('#headInilabsCSSLink').attr('href', "<?=base_url('assets/inilabs/themes/')?>"+data+"/inilabs.css");
                        
                        $html = '<center class="backendThemeBodyMargin"><button type="button" class="btn btn-danger"><i  class="fa fa-check-circle"></i></button></center>';
                        $('.backendThemeBodyMargin').hide();
                        $('#themeBodyContent-'+data).html($html);
                        if(data) {
                            toastr["success"]("<?=$this->lang->line('menu_success');?>")
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "500",
                                "hideDuration": "500",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }
                    }
                });
            }
        });
    });

    

    $('#captcha_status').change(function() {
        var captcha_status = $(this).val();
        if(captcha_status == 0) {
            $('#recaptcha_site_key_id').show(300); 
            $('#recaptcha_secret_key_id').show(300);  
        } else {
            $('#recaptcha_site_key_id').hide(300); 
            $('#recaptcha_secret_key_id').hide(300); 
        }
    });

    <?php if($captcha_status == 0) { ?>
            $('#recaptcha_site_key_id').show(300); 
            $('#recaptcha_secret_key_id').show(300);
       <?php } else { ?>
            $('#recaptcha_site_key_id').hide(300); 
            $('#recaptcha_secret_key_id').hide(300); 
    <?php } ?>


    <?php if($setting->auto_invoice_generate) { ?>
        $('#automation').show();
        $('#autoinvoicediv').addClass('col-sm-6');
    <?php } else { ?>
        $('#automation').hide();
        $('#autoinvoicediv').addClass('col-sm-12');
    <?php } ?> 

    $('#auto_invoice_generate').change(function() {
        var aig = $(this).val();
        
        if(aig == 1) {
            $('#s2id_automation').show(1000);
            $("#auto_invoice_generate").fadeIn("slow", function() {
                $('#autoinvoicediv').removeClass('col-sm-12');
                $('#autoinvoicediv').addClass('col-sm-6');
            });
        } else {
            $('#s2id_automation').hide(1000);
            $("#auto_invoice_generate").fadeIn("slow", function() {
                $('#autoinvoicediv').removeClass('col-sm-6');
                $('#autoinvoicediv').addClass('col-sm-12');
            });

            
        }
    });


    $(document).on('click', '#close-preview', function(){ 
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
               $('.image-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.image-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );    
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        // Create the preview image
        $(".image-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
    });

    $( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );

    $('#weekends').select2();

</script>