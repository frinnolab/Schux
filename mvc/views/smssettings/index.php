
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-wrench"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_smssettings')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <div class="col-sm-12">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="<?php if($clickatell == 1) echo 'active'; ?>"><a data-toggle="tab" href="#clickatell" aria-expanded="true">Clickatell</a></li>
                            <li class="<?php if($twilio == 1) echo 'active'; ?>"><a data-toggle="tab" href="#twilio" aria-expanded="true">Twilio</a></li>
                            <li class="<?php if($bulk == 1) echo 'active'; ?>"><a data-toggle="tab" href="#bulk" aria-expanded="true">Bulk</a></li>
                            <li class="<?php if($msg91 == 1) echo 'active'; ?>"><a data-toggle="tab" href="#msg91" aria-expanded="true">MSG91</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="clickatell" class="tab-pane <?php if($clickatell == 1) echo 'active';?> ">
                                <br>
                                <div class="row">
                                    <div class="col-sm-10">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'clickatell'); ?>
                                            <?php
                                                if(form_error('clickatell_username'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="clickatell_username" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_username")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                  <?php if (empty($set_clickatell)): ?>
                                                    <input type="text" class="form-control" id="clickatell_username" name="clickatell_username" value="<?=set_value('clickatell_username')?>" >
                                                  <?php else: ?>
                                                    <input type="text" class="form-control" id="clickatell_username" name="clickatell_username" value="<?=set_value('clickatell_username', $set_clickatell['clickatell_username'])?>" >
                                                  <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('clickatell_username'); ?>
                                                </span>
                                            </div>

                                            <?php
                                                if(form_error('clickatell_password'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="clickatell_password" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_password")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                  <?php if (empty($set_clickatell)): ?>
                                                    <input type="text" class="form-control" id="clickatell_password" name="clickatell_password" value="<?=set_value('clickatell_password')?>" >
                                                  <?php else: ?>
                                                    <input type="text" class="form-control" id="clickatell_password" name="clickatell_password" value="<?=set_value('clickatell_password', $set_clickatell['clickatell_password'])?>" >
                                                  <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('clickatell_password'); ?>
                                                </span>
                                            </div>

                                            <?php
                                                if(form_error('clickatell_api_key'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="clickatell_api_key" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_api_key")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                    <?php if (empty($set_clickatell)): ?>
                                                      <input type="text" class="form-control" id="clickatell_api_key" name="clickatell_api_key" value="<?=set_value('clickatell_api_key')?>" >
                                                    <?php else: ?>
                                                      <input type="text" class="form-control" id="clickatell_api_key" name="clickatell_api_key" value="<?=set_value('clickatell_api_key', $set_clickatell['clickatell_api_key'])?>" >
                                                    <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('clickatell_api_key'); ?>
                                                </span>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id="twilio" class="tab-pane <?php if($twilio == 1) echo 'active'; ?>">
                                <br>
                                <div class="row">
                                    <div class="col-sm-10">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'twilio'); ?>
                                            <?php
                                                if(form_error('twilio_accountSID'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="twilio_accountSID" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_accountSID")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                  <?php if (empty($set_twilio)): ?>
                                                    <input type="text" class="form-control" id="twilio_accountSID" name="twilio_accountSID" value="<?=set_value('twilio_accountSID')?>" >
                                                  <?php else: ?>
                                                    <input type="text" class="form-control" id="twilio_accountSID" name="twilio_accountSID" value="<?=set_value('twilio_accountSID', $set_twilio['twilio_accountSID'])?>" >
                                                  <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('twilio_accountSID'); ?>
                                                </span>
                                            </div>

                                            <?php
                                                if(form_error('twilio_authtoken'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="twilio_authtoken" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_authtoken")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                  <?php if (empty($set_twilio)): ?>
                                                    <input type="text" class="form-control" id="twilio_authtoken" name="twilio_authtoken" value="<?=set_value('twilio_authtoken')?>" >
                                                  <?php else: ?>
                                                    <input type="text" class="form-control" id="twilio_authtoken" name="twilio_authtoken" value="<?=set_value('twilio_authtoken', $set_twilio['twilio_authtoken'])?>" >
                                                  <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('twilio_authtoken'); ?>
                                                </span>
                                            </div>

                                            <?php
                                                if(form_error('twilio_fromnumber'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="twilio_fromnumber" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_fromnumber")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                  <?php if (empty($set_twilio)): ?>
                                                    <input type="text" class="form-control" id="twilio_fromnumber" name="twilio_fromnumber" value="<?=set_value('twilio_fromnumber')?>" >
                                                  <?php else: ?>
                                                    <input type="text" class="form-control" id="twilio_fromnumber" name="twilio_fromnumber" value="<?=set_value('twilio_fromnumber', $set_twilio['twilio_fromnumber'])?>" >
                                                  <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('twilio_fromnumber'); ?>
                                                </span>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id="bulk" class="tab-pane <?php if($bulk == 1) echo 'active';?> ">
                                <br>
                                <div class="row">
                                    <div class="col-sm-10">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'bulk'); ?>
                                            <?php
                                                if(form_error('bulk_username'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="bulk_username" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_username")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                  <?php if (empty($set_bulk)): ?>
                                                    <input type="text" class="form-control" id="bulk_username" name="bulk_username" value="<?=set_value('bulk_username')?>" >
                                                  <?php else: ?>
                                                    <input type="text" class="form-control" id="bulk_username" name="bulk_username" value="<?=set_value('bulk_username', $set_bulk['bulk_username'])?>" >
                                                  <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('bulk_username'); ?>
                                                </span>
                                            </div>

                                            <?php
                                                if(form_error('bulk_password'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="bulk_password" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_password")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                  <?php if (empty($set_bulk)): ?>
                                                    <input type="text" class="form-control" id="bulk_password" name="bulk_password" value="<?=set_value('bulk_password')?>" >
                                                  <?php else: ?>
                                                    <input type="text" class="form-control" id="bulk_password" name="bulk_password" value="<?=set_value('bulk_password', $set_bulk['bulk_password'])?>" >
                                                  <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('bulk_password'); ?>
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id="msg91" class="tab-pane <?php if($msg91 == 1) echo 'active';?> ">
                                <br>
                                <div class="row">
                                    <div class="col-sm-10">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'msg91'); ?>
                                            <div class="form-group <?= form_error('msg91_authKey') ?'has-error':'' ?>" >
                                                <label for="msg91_authKey" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_authkey")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                    <?php if (empty($set_msg91)): ?>
                                                        <input type="text" class="form-control" id="msg91_authKey" name="msg91_authKey" value="<?=set_value('msg91_authKey')?>" >
                                                    <?php else: ?>
                                                        <input type="text" class="form-control" id="msg91_authKey" name="msg91_authKey" value="<?=set_value('msg91_authKey', $set_msg91['msg91_authKey'])?>" >
                                                    <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('msg91_authKey'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group <?= form_error('msg91_senderID') ?'has-error':'' ?>" >
                                                <label for="msg91_senderID" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("smssettings_senderID")?> <span class="text-red">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                    <?php if (empty($set_msg91)): ?>
                                                        <input type="text" class="form-control" id="msg91_senderID" name="msg91_senderID" value="<?=set_value('msg91_senderID')?>" >
                                                    <?php else: ?>
                                                        <input type="text" class="form-control" id="msg91_senderID" name="msg91_senderID" value="<?=set_value('msg91_senderID', $set_msg91['msg91_senderID'])?>" >
                                                    <?php endif; ?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('msg91_senderID'); ?>
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- nav-tabs-custom -->
                </div>


            </div> <!-- col-sm-12 -->

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
