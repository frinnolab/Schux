<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-beer"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_manage_salary')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="<?php echo form_error('role') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="role" class="control-label">
                                            <?=$this->lang->line('manage_salary_role')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line('manage_salary_select_role'));
                                            foreach ($roles as $role) {
                                                if($role->usertypeID != 3 && $role->usertypeID != 4) {
                                                    $array[$role->usertypeID] = $role->usertype;
                                                }
                                            }
                                            echo form_dropdown("role", $array, set_value("role", $setrole), "id='role' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <?php if(count($users)) { ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('manage_salary_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('manage_salary_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('manage_salary_email')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('manage_salary_jod')?></th>
                                <?php if(permissionChecker('manage_salary_add') || permissionChecker('manage_salary_edit') || permissionChecker('manage_salary_delete') || permissionChecker('manage_salary_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($users as $user) { if($user->usertypeID == 1) { $userID = $user->systemadminID; } elseif($user->usertypeID == 2) { $userID = $user->teacherID; } else { $userID = $user->userID; }  ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('manage_salary_photo')?>">
                                        <?=profileimage($user->photo)?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('manage_salary_name')?>">
                                        <?=$user->name?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('manage_salary_email')?>">
                                        <?=$user->email?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('manage_salary_jod')?>">
                                        <?=date('d M Y', strtotime($user->jod))?>
                                    </td>

                                    <?php if(permissionChecker('manage_salary_add') || permissionChecker('manage_salary_edit') || permissionChecker('manage_salary_delete') || permissionChecker('manage_salary_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php 
                                                if(in_array($userID, $managesalary)) {
                                                    echo btn_view('manage_salary/view/'.$userID.'/'.$user->usertypeID, $this->lang->line('view'));
                                                    echo btn_edit('manage_salary/edit/'.$userID.'/'.$user->usertypeID, $this->lang->line('edit'));
                                                    echo btn_delete('manage_salary/delete/'.$userID.'/'.$user->usertypeID, $this->lang->line('delete'));
                                                } else {
                                                    echo btn_add('manage_salary/add/'.$userID.'/'.$user->usertypeID, $this->lang->line('add'));
                                                }
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".select2").select2();

    $('#role').change(function() {
        var role = $(this).val();
        if(role == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('manage_salary/role_list')?>",
                data: "id=" + role,
                dataType: "html",
                success: function(data) {  
                    window.location.href = data;
                }
            });
        }
    });
</script>