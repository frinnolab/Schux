<div class="col-sm-12 do-not-refresh" style="padding: 10px">
    <div class="callout callout-danger">
        <h4><?=$this->lang->line('take_exam_warning')?></h4>
        <p><?=$this->lang->line('take_exam_page_refresh')?></p>
    </div>
</div>

<section class="panel">
    <div class="panel-body">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <?=profileviewimage($student->photo)?>
                            <h3 class="profile-username text-center"><?=$student->name?></h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_registerNO')?></b> <a class="pull-right"><?=$student->srregisterNO?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_roll')?></b> <a class="pull-right"><?=$student->srroll?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_class')?></b> <a class="pull-right"><?=count($class) ? $class->classes : ''?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_section')?></b> <a class="pull-right"><?=count($section) ? $section->section : ''?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('take_exam_exam_info')?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="profile">
                                <div class="panel-body profile-view-dis">
                                    <div class="row" style="min-height: 250px">
                                        <h2>
                                            <?php 
                                                if(count($onlineExam) && count($userExamCheck)) {
                                                    if($onlineExam->markType == 5) {
                                                        $percentage = 0;
                                                        if($userExamCheck->totalObtainedMark > 0 && $userExamCheck->totalMark > 0) {
                                                            $percentage = (($userExamCheck->totalObtainedMark/$userExamCheck->totalMark)*100);
                                                        } 

                                                        if($percentage >= $onlineExam->percentage) {
                                                            echo '<span class="text-green">'. $this->lang->line('take_exam_pass') . '</span>';
                                                        } else {
                                                            echo '<span class="text-red">'. $this->lang->line('take_exam_fail') . '</span>';
                                                        }
                                                    } elseif($onlineExam->markType == 10) {
                                                        if($userExamCheck->totalObtainedMark >= $onlineExam->percentage) {
                                                            echo '<span class="text-green">'. $this->lang->line('take_exam_pass') . '</span>';
                                                        } else {
                                                            echo '<span class="text-red">'. $this->lang->line('take_exam_fail') . '</span>';
                                                        }
                                                    }
                                                } 
                                            ?>
                                        </h2>
                                        <?php if(count($userExamCheck)) { ?>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><?=$this->lang->line('take_exam_total_question')?> : <?=$userExamCheck->totalQuestion?></td>
                                                <td><?=$this->lang->line('take_exam_total_answer')?> : <?=$userExamCheck->totalAnswer?></td>
                                            </tr>
                                            <tr>
                                                <td><?=$this->lang->line('take_exam_total_current_answer')?> : <?=$userExamCheck->totalCurrectAnswer?></td>
                                                <td><?=$this->lang->line('take_exam_total_mark')?> : <?=$userExamCheck->totalMark?></td>
                                            </tr> 
                                            <tr>
                                                <td><?=$this->lang->line('take_exam_total_obtained_mark')?> : <?=$userExamCheck->totalObtainedMark?></td>
                                                <td><?=$this->lang->line('take_exam_total_percentage')?> : <?=$userExamCheck->totalPercentage?> % </td>
                                            </tr>
                                        </table>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>