<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-user-secret"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr>
                            <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                            <th class="col-sm-6"><?=$this->lang->line('take_exam_name')?></th>
                            <th class="col-sm-2"><?=$this->lang->line('take_exam_examstatus')?></th>
                            <th class="col-sm-2"><?=$this->lang->line('take_exam_duration')?></th>
                            <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(count($onlineExams)) {$i = 0; foreach($onlineExams as $onlineExam) { 
                            if($usertypeID == '3') {
                                if((($student->srclassesID == $onlineExam->classID) || ($onlineExam->classID == '0')) && (($student->srsectionID == $onlineExam->sectionID) || ($onlineExam->sectionID == '0')) && (($student->srstudentgroupID == $onlineExam->studentGroupID) || ($onlineExam->studentGroupID == '0')) && (($onlineExam->subjectID == '0') || (in_array($onlineExam->subjectID, $userSubjectPluck)))) { $i++;

                                    $currentdate = 0;
                                    if($onlineExam->examTypeNumber == '4') {
                                        $presentDate = strtotime(date('Y-m-d'));
                                        $examStartDate = strtotime($onlineExam->startDateTime);
                                        $examEndDate = strtotime($onlineExam->endDateTime);
                                    } elseif($onlineExam->examTypeNumber == '5') {
                                        $presentDate = strtotime(date('Y-m-d H:i:s'));
                                        $examStartDate = strtotime($onlineExam->startDateTime);
                                        $examEndDate = strtotime($onlineExam->endDateTime);
                                    }

                                    $lStatusRunning = FALSE;
                                    $lStatusExpire = FALSE;
                                    $lStatusTaken = FALSE;
                                    $lStatusTodayOnly = FALSE;

                                    $examLabel = $this->lang->line('take_exam_anytime');
                                    if($onlineExam->examTypeNumber == '4' || $onlineExam->examTypeNumber == '5') {
                                        if($presentDate < $examStartDate) {
                                            $examLabel = $this->lang->line('take_exam_upcoming');
                                        } elseif($presentDate > $examStartDate && $presentDate < $examEndDate) {
                                            $examLabel = $this->lang->line('take_exam_running');
                                            $lStatusRunning = TRUE;
                                        } elseif($presentDate == $examStartDate && $presentDate == $examEndDate) {
                                            $examLabel = $this->lang->line('take_exam_today_only');
                                            $lStatusTodayOnly = TRUE;
                                        } elseif($presentDate > $examStartDate && $presentDate > $examEndDate) {
                                            $examLabel = $this->lang->line('take_exam_expired');
                                            $lStatusExpire = TRUE;
                                        }
                                    } else {
                                        $lStatusRunning = TRUE;
                                    }

                                    if($lStatusRunning) {
                                        if(isset($examStatus[$onlineExam->onlineExamID])) {
                                            $examLabel = $this->lang->line('take_exam_taken');
                                            $lStatusTaken = TRUE;
                                        }
                                    } elseif($lStatusExpire) {
                                        if(isset($examStatus[$onlineExam->onlineExamID])) {
                                            $examLabel = $this->lang->line('take_exam_taken');
                                            $lStatusTaken = TRUE;
                                        }
                                    } elseif($lStatusTodayOnly) {
                                        if(isset($examStatus[$onlineExam->onlineExamID])) {
                                            $examLabel = $this->lang->line('take_exam_taken');
                                            $lStatusTaken = TRUE;
                                        }
                                    }

                                    if($lStatusTaken) {
                                        if($onlineExam->examStatus == 2) {
                                            $examLabel = $this->lang->line('take_exam_retaken');
                                        }
                                    }

                                    ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('take_exam_name')?>">
                                            <?php
                                            if(strlen($onlineExam->name) > 50)
                                                echo strip_tags(substr($onlineExam->name, 0, 50)."...");
                                            else
                                                echo strip_tags(substr($onlineExam->name, 0, 50));
                                            ?> - <?=$examLabel?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('take_exam_examstatus')?>">
                                        <?php 
                                            if($onlineExam->examStatus == 1) {
                                                echo $this->lang->line('take_exam_onetime');
                                            } elseif($onlineExam->examStatus == 2) {
                                                echo $this->lang->line('take_exam_multipletime');
                                            }
                                        ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('take_exam_duration')?>">
                                            <?php echo $onlineExam->duration; ?>
                                        </td>
                                        <?php //if(permissionChecker('take_exam_edit') || permissionChecker('take_exam_delete') || permissionChecker('take_exam_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <button class="btn btn-primary btn-sm" onclick="newPopup('<?=base_url('take_exam/instruction/'.$onlineExam->onlineExamID)?>')"><?=$this->lang->line('panel_title')?></button>
                                        </td>
                                        <?php //} ?>
                                    </tr>
                                    <?php } } } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">

    function newPopup(url) {
        window.open(url,'_blank',"width=1000,height=650,toolbar=0,location=0,scrollbars=yes");
        runner();
    }

    function runner()
    {
        url = localStorage.getItem('redirect_url');
        if(url) {
            localStorage.clear();
            window.location = url;
        }
        setTimeout(function() {
            runner();
        }, 500);

    }
 </script>