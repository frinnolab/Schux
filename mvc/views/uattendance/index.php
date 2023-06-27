
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-user-secret"></i> <?=$this->lang->line('panel_title')?></h3>
       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_tattendance')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                 <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                    <?php if(permissionChecker('uattendance_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('uattendance/add') ?>">
                                <i class="fa fa-plus"></i> 
                                <?=$this->lang->line('add_title')?>
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>
                

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('uattendance_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('uattendance_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('uattendance_email')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('uattendance_usertype')?></th>
                                <?php if(permissionChecker('uattendance_view')) { ?>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($users)) { $i = 1; foreach($users as $user) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('uattendance_photo')?>">
                                        <?=profileimage($user->photo);?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('uattendance_name')?>">
                                        <?php echo $user->name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('uattendance_email')?>">
                                        <?php echo $user->email; ?>
                                    </td> 
                                    <td data-title="<?=$this->lang->line('uattendance_usertype')?>">
                                        <?=$user->usertype;?>
                                    </td>
                                    <?php if(permissionChecker('uattendance_view')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_view('uattendance/view/'.$user->userID, $this->lang->line('view')); ?>
                                    </td>
                                    <?php } ?>
                               </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('tattendance/student_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>