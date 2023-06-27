<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-ini-emailsetting"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_emailsetting')?></li>
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
                <legend class="setting-legend"><?=$this->lang->line('emailsetting_email_setting')?></legend>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group <?php if(form_error('email_engine')) { echo 'has-error'; } ?>">
                            <div class="col-sm-12">
                                <label><?=$this->lang->line("emailsetting_email_engine")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Select Email Engine"></i>
                                </label>
                                <?php
                                    $array = array(
                                        "select" => $this->lang->line("emailsetting_select"), 
                                        "sendmail" => $this->lang->line("emailsetting_send_mail"), 
                                        "smtp" => $this->lang->line("emailsetting_smtp")
                                    );

                                    echo form_dropdown("email_engine", $array, set_value("email_engine",$emailsetting->email_engine), "id='email_engine' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('email_engine'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 mainsmtpDIV">
                        <div class="form-group <?=form_error('smtp_username') ? 'has-error' : ''?>" >
                            <div class="col-sm-12">
                                <label for="smtp_username"><?=$this->lang->line("emailsetting_smtp_username")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="set your smtp username"></i>
                                </label>
                                <input type="text" class="form-control" id="smtp_username" name="smtp_username" value="<?=set_value('smtp_username', $emailsetting->smtp_username)?>" />
                                <span class="control-label"><?=form_error('smtp_username'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 mainsmtpDIV">
                        <div class="form-group <?=form_error('smtp_password') ? 'has-error' : ''?>" >
                            <div class="col-sm-12">
                                <label for="smtp_password"><?=$this->lang->line("emailsetting_smtp_password")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="set your smtp password"></i>
                                </label>
                                <input type="text" class="form-control" id="smtp_password" name="smtp_password" value="<?=set_value('smtp_password', $emailsetting->smtp_password)?>" />
                                <span class="control-label"><?=form_error('smtp_password'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="row">                    
                    <div class="col-sm-4 mainsmtpDIV">
                        <div class="form-group <?=form_error('smtp_server') ? 'has-error' : ''?>" >
                            <div class="col-sm-12">
                                <label for="smtp_server"><?=$this->lang->line("emailsetting_smtp_server")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="set your smtp server"></i>
                                </label>
                                <input type="text" class="form-control" id="smtp_server" name="smtp_server" value="<?=set_value('smtp_server', $emailsetting->smtp_server)?>" />
                                <span class="control-label"><?=form_error('smtp_server'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 mainsmtpDIV">
                        <div class="form-group <?=form_error('smtp_port') ? 'has-error' : ''?>" >
                            <div class="col-sm-12">
                                <label for="smtp_port"><?=$this->lang->line("emailsetting_smtp_port")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="set your smtp port"></i>
                                </label>
                                <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?=set_value('smtp_port', $emailsetting->smtp_port)?>" />
                                <span class="control-label"><?=form_error('smtp_port'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 mainsmtpDIV">
                        <div class="form-group <?=form_error('smtp_security') ? 'has-error' : ''?>" >
                            <div class="col-sm-12">
                                <label for="smtp_security"><?=$this->lang->line("emailsetting_smtp_security")?>
                                    &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="set your smtp security"></i>
                                </label>
                                <input type="text" class="form-control" id="smtp_security" name="smtp_security" value="<?=set_value('smtp_security', $emailsetting->smtp_security)?>" />
                                <span class="control-label"><?=form_error('smtp_security'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <div class="col-sm-8">
                            <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_emailsetting")?>" >
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script type="text/javascript">

    $('.select2').select2();

    $('.mainsmtpDIV').hide();


    var set_email_engine = "<?=set_value('email_engine')?>";
    if(set_email_engine == 'smtp') {
        $(".mainsmtpDIV").show('slow');
    } else if(set_email_engine == 'sendmail') {
        $(".mainsmtpDIV").hide('slow');
    } else if(set_email_engine == 'select') {
        $('.mainsmtpDIV').hide();
    } else {
        <?php if($emailsetting->email_engine == 'smtp') { ?>
            $('.mainsmtpDIV').show();
        <?php } ?>
    }

    $(document).on('change', "#email_engine", function() {
        var get_email_engine = $(this).val();
        if(get_email_engine == 'smtp') {
            $(".mainsmtpDIV").show('slow');
        } else {
            $(".mainsmtpDIV").hide('slow');
        }
    });

</script>