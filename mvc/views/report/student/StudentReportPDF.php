<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <div class="box-header bg-gray">
        <h3> <?=$this->lang->line('studentreport_report_for')?> - <?=ucwords($reportfor)?> ( <?=$reportTitle?> ) </h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="pull-left">
                    <?=$this->lang->line('studentreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID]->classes : $this->lang->line('studentreport_select_all_class')?>
                </h5>  
                <h5 class="pull-right">
                    <?=$this->lang->line('studentreport_section')?> : <?=isset($sections[$sectionID]) ? $sections[$sectionID]->section : $this->lang->line('studentreport_select_all_section')?>
                </h5>  
            </div>
            <div class="col-sm-12">
                <?php if(count($students)) { ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('studentreport_slno')?></th>
                                <th><?=$this->lang->line('studentreport_photo')?></th>
                                <th><?=$this->lang->line('studentreport_name')?></th>
                                <th><?=$this->lang->line('studentreport_register')?></th>
                                <?php if($classesID == 0) { ?>
                                    <th><?=$this->lang->line('studentreport_class')?></th>
                                <?php } if($sectionID == 0 || $sectionID == '') { ?>
                                <th><?=$this->lang->line('studentreport_section')?></th>
                                <?php } ?>
                                <th><?=$this->lang->line('studentreport_roll')?></th>
                                <th><?=$this->lang->line('studentreport_email')?></th>
                                <th><?=$this->lang->line('studentreport_phone')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                $flag = 0;
                                foreach($students as $student) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>

                                    <td>
                                        <?=profileimage($student->photo)?>
                                    </td>
                                    <td><?=$student->srname?></td>
                                    <td><?=$student->srregisterNO?></td>
                                    <?php if($classesID == 0) { ?>
                                        <td>
                                            <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID]->classes : '' ?>
                                        </td>
                                    <?php } if($sectionID == 0 || $sectionID == '') { ?>
                                        <td>
                                            <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID]->section : '' ?>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <?php echo $student->srroll; ?>
                                    </td>
                                    <td>
                                        <?php echo $student->email; ?>
                                    </td>
                                    <td>
                                        <?php echo $student->phone; ?>
                                    </td>
                               </tr>
                            <?php $i++; } ?>
                        </tbody>
                    </table>
                <?php } else {  ?>
                    <div class="notfound">
                        <?php echo $this->lang->line('studentreport_student_not_found'); ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</body>
</html>