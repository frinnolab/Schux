<div id="printablediv">
    <div class="box">
        <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
                </div>
                <div class="box-header bg-gray">
                    <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('attendancereport_report_for')?> <?=$this->lang->line('attendancereport_attendance')?> - <?=$attendancetype?> ( <?=date('d M Y',strtotime($date))?> ) </h3>
                </div><!-- /.box-header -->
                <div class="col-sm-12">
                    <h5 class="pull-left"><?=$this->lang->line('attendancereport_class');?> : <?= $classesID != 0 ? (isset($class) ? $class[0]->classes :  '' ): 'All Classes'?></h5>
                    <?php if($sectionID == 0) {?>
                        <h5 class="pull-right"><?=$this->lang->line('attendancereport_section')?> : <?=$this->lang->line('attendancereport_select_all_section');?></h5>
                    <?php } else { ?>
                        <h5 class="pull-right"><?=$this->lang->line('attendancereport_section')?> : <?=isset($sections[$sectionID]) ? $sections[$sectionID]->section : '' ?></h5>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?php 
                    $attendancedate = date('d-m-Y',strtotime($date));
                    if(count($students)) { ?>
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-1"><?=$this->lang->line('attendancereport_slno')?></th>
                                    <th class="col-sm-1"><?=$this->lang->line('attendancereport_photo')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('attendancereport_name')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('attendancereport_registerNo')?></th>
                                    <?php  if($sectionID == 0 ) { ?>
                                        <th class="col-sm-2"><?=$this->lang->line('attendancereport_section')?></th>
                                    <?php } ?>
                                    <th class="col-sm-2"><?=$this->lang->line('attendancereport_roll')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('attendancereport_email')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('attendancereport_phone')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 1;
                                    $flag = 0;
                                    foreach($students as $student) {
                                        $userleaveapplications = isset($leaveapplications[$student->srstudentID]) ? $leaveapplications[$student->srstudentID] : [];

                                        if((in_array($attendancedate, $userleaveapplications) && ($typeSortForm != 'LA'))) {
                                            continue;
                                        } elseif(($typeSortForm == 'LA') && (!in_array($attendancedate, $userleaveapplications))) {
                                            continue;
                                        } elseif(isset($attendances[$student->srstudentID])) {
                                            $attendanceDay = $attendances[$student->srstudentID]->$day;
                                            if($typeSortForm == 'P' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                                continue;
                                            } elseif($typeSortForm == 'LE' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='P' || $attendanceDay =='L' )) {
                                                continue;
                                            } elseif($typeSortForm == 'L' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='P' )) {
                                                continue;
                                            } elseif($typeSortForm == 'A' && ($attendanceDay == 'P' || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                                continue;
                                            } 
                                        } elseif($typeSortForm == 'P' || $typeSortForm == 'LE' || $typeSortForm == 'L') {
                                            continue;
                                        }
                                        $flag = 1;
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $i; ?>
                                        </td>

                                        <td>
                                            <?=profileimage($student->photo)?>
                                        </td>
                                        <td>
                                            <?php echo $student->srname; ?>
                                        </td>
                                        <td>
                                            <?php echo $student->srregisterNO; ?>
                                        </td>
                                        <?php
                                            if($sectionID == 0 ) { ?>
                                            <td>
                                                <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID]->section : ''; ?>
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
                                <?php $i++; }
                                    if(!$flag) { ?>
                                        <tr>
                                            <td colspan="<?=$sectionID==0 ? '8' : '7'?>">
                                                <?=$this->lang->line('attendancereport_student_not_found')?>
                                            </td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                        <div class="notfound">
                            <p><b class="text-info"><?=$this->lang->line('attendancereport_student_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
                </div>

            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>
