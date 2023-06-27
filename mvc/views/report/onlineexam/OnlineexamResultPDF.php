
<div id="printablediv">
    <div class="box">
        <div class="col-sm-12">
            <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
        </div>

        <h2 style="margin: 10px 10px 0px;"><?=$this->lang->line('onlineexamreport_report_for')?> - <?=$this->lang->line('onlineexamreport_onlineexam')?></h2>

        <div class="box-body">
            <div class="row">
                <div class="col-sm-6 examdetails-head">
                    <div class="box box-solid examdetails">
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("onlineexamreport_examinformation")?></h3>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-info fa-2x"></i></li>
                            </ol>
                        </div>
                        <div class="box-body">               
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-blue">
                                                <?=$this->lang->line('onlineexamreport_exam')?> : <?=count($onlineexam) ? $onlineexam->name : ''?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class='text-blue'>
                                                <?php
                                                    echo $this->lang->line('onlineexamreport_status'). ' : ';  
                                                    if($onlineExamUserStatus->statusID == 5) {
                                                        echo $this->lang->line('onlineexamreport_passed');
                                                    } else {
                                                        echo $this->lang->line('onlineexamreport_failed');
                                                    }
                                                ?>
                                            </span>
                                        </td>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_rank')?> : <?=$rank?></span></td>
                                    </tr>
                                    <tr>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_question')?> : <?=$onlineExamUserStatus->totalQuestion?></span></td>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_answer')?> : <?=$onlineExamUserStatus->totalAnswer?></span></td>
                                    </tr>
                                    <tr>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_current_answer')?> : <?=$onlineExamUserStatus->totalCurrectAnswer?></span></td>  
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_mark')?> : <?=$onlineExamUserStatus->totalMark?></span></td> 
                                    </tr>
                                    <tr>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_totle_obtained_mark')?> : <?=$onlineExamUserStatus->totalObtainedMark?></span></td>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_total_percentage')?> : <?=$onlineExamUserStatus->totalPercentage?>%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 profile-head">
                    <div class="box box-solid profiledetails">
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("onlineexamreport_studentinformation")?></h3>
                        </div>
                        <div class="box-body">
                            <?php if(count($student)) { ?>
                                <div class="profile">
                                    <div class="border_image">
                                        <img class="profile-image" src="<?=imagelink($student->photo)?>" alt="">
                                    </div>
                                    <h1><?=$student->srname?></h1>
                                </div>

                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td><?=$this->lang->line('onlineexamreport_classes')?></td>
                                            <td><?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('onlineexamreport_section')?></td>
                                            <td><?=isset($section[$student->srsectionID]) ? $section[$student->srsectionID] : ''?></td>
                                        </tr>
                                        <?php if($onlineexam->subjectID > 0) { ?>
                                            <tr>
                                                <td><?=$this->lang->line('onlineexamreport_subject')?></td>
                                                <td><?=count($subject) ? $subject->subject : ''?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><?=$this->lang->line('onlineexamreport_phone')?></td>
                                            <td><?=$student->phone?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('onlineexamreport_email')?></td>
                                            <td><?=$student->email?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('onlineexamreport_address')?></td>
                                            <td><?=$student->address?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <hr class="hr">

                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>
