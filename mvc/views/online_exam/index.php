<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-slideshare"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                    <?php if(permissionChecker('online_exam_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('online_exam/add') ?>">
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
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-5"><?=$this->lang->line('online_exam_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('online_exam_examStatus')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('online_exam_published')?></th>
                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                    <?php if(permissionChecker('online_exam_edit') || permissionChecker('online_exam_delete') || permissionChecker('online_exam_view')) { ?>
                                        <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($online_exams)) {$i = 0; foreach($online_exams as $online_exam) { 
                                $showStatus = FALSE; 
                                if($usertypeID == '3') {
                                    if(count($student)) {
                                        if((($student->srclassesID == $online_exam->classID) || ($online_exam->classID == '0')) && (($student->srsectionID == $online_exam->sectionID) || ($online_exam->sectionID == '0')) && (($student->srstudentgroupID == $online_exam->studentGroupID) || ($online_exam->studentGroupID == '0')) && ($online_exam->published == 1)) {

                                            if(isset($opsubject[$online_exam->subjectID])) {
                                                if($online_exam->subjectID == $student->sroptionalsubjectID) {
                                                    $showStatus = TRUE;
                                                    $i++;
                                                }
                                            } else {
                                                $showStatus = TRUE;
                                                $i++; 
                                            }
                                        }
                                    }
                                } else { 
                                    $i++; 
                                    $showStatus = TRUE;
                                }

                                if($showStatus) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('online_exam_name')?>">
                                        <?php
                                            if(strlen($online_exam->name) > 25)
                                                echo strip_tags(substr($online_exam->name, 0, 25)."...");
                                            else
                                                echo strip_tags(substr($online_exam->name, 0, 25));
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('online_exam_published')?>">
                                    <?php 
                                        if($online_exam->examStatus == '1') {
                                            echo $this->lang->line('online_exam_onetime');
                                        } else {
                                            echo $this->lang->line('online_exam_multipletime');
                                        } 
                                    ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('online_exam_published')?>">
                                    <?php 
                                        if($online_exam->published == '1') {
                                            echo "<span class='btn btn-success btn-xs'>".$this->lang->line('online_exam_yes')."</span>";
                                        } else {
                                            echo "<span class='btn btn-danger btn-xs'>".$this->lang->line('online_exam_no')."</span>";
                                        } 
                                    ?>
                                    </td>
                                    <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                        <?php if(permissionChecker('online_exam_edit') || permissionChecker('online_exam_delete') || permissionChecker('online_exam_view')) { ?>
                                            <td data-title="<?=$this->lang->line('action')?>">
                                                <?php echo btn_extra('online_exam/addquestion/'.$online_exam->onlineExamID, $this->lang->line('addquestion'), 'online_exam_add') ?>
                                                <?php echo btn_edit('online_exam/edit/'.$online_exam->onlineExamID, $this->lang->line('edit')) ?>
                                                <?php echo btn_delete('online_exam/delete/'.$online_exam->onlineExamID, $this->lang->line('delete')) ?>
                                            </td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php } } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>