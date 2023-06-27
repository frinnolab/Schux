<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-student"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_student')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if((($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) || ($this->session->userdata('usertypeID') != 3)) { ?>
                    <h5 class="page-header">
                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                            <?php if(permissionChecker('student_add')) { ?>
                                <a href="<?php echo base_url('student/add') ?>">
                                    <i class="fa fa-plus"></i>
                                    <?=$this->lang->line('add_title')?>
                                </a>
                                
                                <?php } ?>
                                <?php } ?>
                                
                        <?php if($this->session->userdata('usertypeID') != 3) { ?>
                            <div class="row" style="margin-top: 1rem;">
                                <div class="col-lg-4 col-md-4 col-xs-12" style="margin-bottom: 1rem;">
                                    <div class="col-md-8">
                                        <input type="text" name="search_student" id="search_student" style="border: 1px solid #09A3A3" placeholder="<?= $this->lang->line("search_student") ?>" class="form-control" value="<?= set_value('search_student', $name?: '' ) ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success" id="search">Search</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12">
                                    <?php
                                        $array = array("-" => $this->lang->line("filter"), "0" => $this->lang->line("not_admitted"), "1" => $this->lang->line("admitted"));
                                        
                                        echo form_dropdown("filter", $array, set_value("filter", $filter), "id='filter' class='form-control select2' style='border: 1px solid #09A3A3'");
                                    ?>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12">
                                    <?php
                                        $array = array("0" => $this->lang->line("student_select_class"));
                                        if(count($classes)) {
                                            foreach ($classes as $classa) {
                                                $array[$classa->classesID] = $classa->classes;
                                            }
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2' style='border: 1px solid #09A3A3'");
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </h5>
                <?php } ?>
                

                <?php if(count($students) > 0 ) { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("student_all_students")?></a></li>
                            <?php foreach ($sections as $key => $section) {
                                echo '<li class=""><a data-toggle="tab" href="#tab'.$section->classesID.$section->sectionID .'" aria-expanded="false">'. $this->lang->line("student_section")." ".$section->section. " ( ". $section->category." )".'</a></li>';
                            } ?>
                        </ul>



                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_email')?></th>
                                                <?php if(permissionChecker('student_edit')) { ?>
                                                    <th class="col-sm-1"><?=$this->lang->line('student_status')?></th>
                                                <?php } ?>
                                                <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($students)) {$i = 1; foreach($students as $student) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('student_photo')?>">
                                                        <?=profileimage($student->photo); ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('student_name')?>">
                                                        <?php echo $student->srname; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('student_roll')?>">
                                                        <?php echo $student->srroll; ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('student_email')?>">
                                                        <?php echo $student->email; ?>
                                                    </td>
                                                    <?php if(permissionChecker('student_edit')) { ?>
                                                    <td data-title="<?=$this->lang->line('student_status')?>">
                                                        <div class="onoffswitch-small" id="<?=$student->srstudentID?>">
                                                            <input type="checkbox" id="myonoffswitch<?=$student->srstudentID?>" class="onoffswitch-small-checkbox" name="paypal_demo" <?php if($student->active === '1') echo "checked='checked'"; ?>>
                                                            <label for="myonoffswitch<?=$student->srstudentID?>" class="onoffswitch-small-label">
                                                                <span class="onoffswitch-small-inner"></span>
                                                                <span class="onoffswitch-small-switch"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                    <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                        <td data-title="<?=$this->lang->line('action')?>">
                                                            <?php
                                                                echo btn_view('student/view/'.$student->srstudentID."/".$student->srclassesID, $this->lang->line('view'));
                                                                if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                                                                    echo btn_edit('student/edit/'.$student->srstudentID."/".$student->srclassesID, $this->lang->line('edit'));
                                                                    echo btn_delete('student/delete/'.$student->srstudentID."/".$student->srclassesID, $this->lang->line('delete'));
                                                                }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                               </tr>
                                            <?php $i++; }} ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <?php foreach ($sections as $key => $section) { ?>
                                    <div id="tab<?=$section->classesID.$section->sectionID?>" class="tab-pane">
                                        <div id="hide-table">
                                            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                        <th class="col-sm-2"><?=$this->lang->line('student_photo')?></th>
                                                        <th class="col-sm-2"><?=$this->lang->line('student_name')?></th>
                                                        <th class="col-sm-2"><?=$this->lang->line('student_roll')?></th>
                                                        <th class="col-sm-2"><?=$this->lang->line('student_email')?></th>
                                                        <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                            <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(count($allsection[$section->sectionID])) { $i = 1; foreach($allsection[$section->sectionID] as $student) { if($section->sectionID === $student->srsectionID) { ?>
                                                        <tr>
                                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                                <?php echo $i; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('student_photo')?>">
                                                                <?=profileimage($student->photo)?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('student_name')?>">
                                                                <?php echo $student->srname; ?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('student_roll')?>">
                                                                <?php echo $student->srroll; ?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('student_email')?>">
                                                                <?php echo $student->email; ?>
                                                            </td>
                                                            <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                                <td data-title="<?=$this->lang->line('action')?>">
                                                                    <?php
                                                                        echo btn_view('student/view/'.$student->srstudentID."/".$set, $this->lang->line('view'));
                                                                        if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                                                                            echo btn_edit('student/edit/'.$student->srstudentID."/".$set, $this->lang->line('edit'));
                                                                            echo btn_delete('student/delete/'.$student->srstudentID."/".$set, $this->lang->line('delete'));
                                                                        }
                                                                    ?>
                                                                </td>
                                                            <?php } ?>
                                                       </tr>
                                                    <?php $i++; }}} ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            <?php } ?>
                        </div>
                    </div> <!-- nav-tabs-custom -->
                <?php } else { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("student_all_students")?></a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('student_email')?></th>
                                                <?php if(permissionChecker('student_edit')) { ?>
                                                    <th class="col-sm-1"><?=$this->lang->line('student_status')?></th>
                                                <?php } ?>
                                                <?php if(permissionChecker('student_edit') || permissionChecker('student_delete') || permissionChecker('student_view')) { ?>
                                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- nav-tabs-custom -->
                <?php } ?>
            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    $(".select2").select2();
    var path = window.location.pathname
    var path_segments = path.split('/')

    if (path_segments[3] == 'index') {
        var classesID = path_segments[4] ? path_segments[4] : 'all'
        var filter = path_segments[5] ? path_segments[5] : 1
        var search_item = path_segments[6] ? path_segments[6] : 0
    } else {
        var classesID = path_segments[3] ? path_segments[3] : 'all'
        var filter = path_segments[4] ? path_segments[4] : 1
        var search_item = path_segments[5] ? path_segments[5] : 0
    }

    console.log(path_segments)
    $('#classesID').change(function() {
        let classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            console.log('changed')
            $.ajax({
                type: 'POST',
                url: "<?=base_url('student/student_list')?>",
                data: {id: classesID, isAdmitted: filter , name: search_item},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });

    $('#filter').change(function() {
        var filter = $(this).val();
        $.ajax({
            type: 'POST',
            url: "<?=base_url('student/student_list')?>",
            data: {id: classesID, isAdmitted: filter , name: search_item},
            dataType: "html",
            success: function(data) {
                window.location.href = data;
            }
        });
    });

    $('#search').click(function() {
        var search_item = $('#search_student').val();
        if(search_item == '') {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('student/student_list')?>",
                data: {id: classesID, isAdmitted: filter , name: search_item},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });


    var status = '';
    var id = 0;
    $('.onoffswitch-small-checkbox').click(function() {
        if($(this).prop('checked')) {
            status = 'chacked';
            id = $(this).parent().attr("id");
        } else {
            status = 'unchacked';
            id = $(this).parent().attr("id");
        }

        if((status != '' || status != null) && (id !='')) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('student/active')?>",
                data: "id=" + id + "&status=" + status,
                dataType: "html",
                success: function(data) {
                    if(data == 'Success') {
                        toastr["success"]("Success")
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
                    } else {
                        toastr["error"]("Error")
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
</script>


