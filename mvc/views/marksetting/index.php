<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-futbol-o"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-marksettings"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_marksetting')?></li>
        </ol>
    </div><!-- /.box-header -->

    <!-- form start -->
    <div class="box-body">
        <style type="text/css">
            .setting-fieldset {
                border: 1px solid #DBDEE0 !important;
                padding: 15px !important;
                margin: 0 0 25px 0 !important;
                box-shadow: 0px 0px 0px 0px #000;
            }
            .setting-legend {
                font-size: 1.1em !important;
                font-weight: bold !important;
                text-align: left !important;
                width: auto;
                color: #428BCA;
                padding: 5px 15px;
                border: 1px solid #DBDEE0 !important;
                margin: 0px;
            }
            
            .margintop {
                margin-top: 20px;
            }

            .margintopbottom {
                margin-top: 15px;
                margin-bottom: 10px;
            }

            .singlebox {
                padding: 10px;
                border: 1px solid #ddd
            }

            .singlebox .singleboxheader {
                border-bottom: 1px solid #ddd;
                margin-left: -10px;
                margin-right: -10px;
                padding: 5px;
                padding-top: 0px;
                margin-top: -3px;
            }

            .singlebox .checkbox {
                margin-left: 5px;
            }

            .singleboxtwo .checkbox {
                margin-left: 17px;
            }

            .classexamDiv {
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 15px;
            }

            .classexamheader {
                margin: 0px;
                border-bottom: 1px solid #ddd;
                margin-left: -15px;
                margin-right: -15px;
                padding: 5px 15px;
                margin-top: -15px;
                margin-bottom: 15px;
            }
        </style>
        
        <fieldset class="form-horizontal setting-fieldset">
            <legend class="setting-legend"><?=$this->lang->line("marksetting_mark_type")?></legend>
            <div class="row">
                <div class="col-sm-4">
                    <label><?=$this->lang->line("marksetting_mark_type")?>&nbsp; <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Please select mark type"></i>
                    </label>
                    <?php
                        $marktypeArray[0] = $this->lang->line('marksetting_global');
                        $marktypeArray[1] = $this->lang->line('marksetting_class_wise');
                        $marktypeArray[2] = $this->lang->line('marksetting_exam_wise');
                        $marktypeArray[3] = $this->lang->line('marksetting_exam_wise_individual');
                        $marktypeArray[4] = $this->lang->line('marksetting_subject_wise');
                        $marktypeArray[5] = $this->lang->line('marksetting_class_exam_wise');
                        $marktypeArray[6] = $this->lang->line('marksetting_class_exam_subject_wise');
                        echo form_dropdown("marktypeID", $marktypeArray, set_value("marktypeID", $siteinfos->marktypeID), "id='marktypeID' class='form-control select2'");
                    ?>
                    <span class="control-label">
                        <?=form_error('marktypeID'); ?>
                    </span>
                </div>
            </div>
        </fieldset>

        <form class="form-horizontal mainmarktypeID" id="mainmarktypeID0" role="form" method="post">
            <input type="hidden" name="marktypeID" class="marktypeID" value="0">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_exam")?></legend>
                <div class="row">
                    <?php
                        if(count($exams)) {
                            $checkexamArr = isset($examArr[0]) ? $examArr[0] : [];
                            foreach ($exams as $exam) {
                                $checkbox = (in_array($exam->examID, $checkexamArr)) ? true : false;
                                echo '<div class="col-sm-3">';
                                    echo '<div class="checkbox">';
                                        echo '<label>';
                                            echo '<input class="globalexam" type="checkbox" value="0_'.$exam->examID.'" '.set_checkbox('exams[]', '0_'.$exam->examID, $checkbox).' name="exams[]"> &nbsp;';
                                            echo $exam->exam;
                                        echo '</label>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
            </fieldset>
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_mark_percentage")?></legend>
                <div class="row">
                    <?php
                        if(count($markpercentages)) {
                            $checkmarkpercentageArr = isset($markpercentageArr[0]) ? $markpercentageArr[0] : [];
                            foreach ($markpercentages as $markpercentage) {
                                $checkbox = (in_array($markpercentage->markpercentageID, $checkmarkpercentageArr)) ? true : false;
                                echo '<div class="col-sm-3">';
                                    echo '<div class="checkbox">';
                                        echo '<label>';
                                            echo '<input class="globalmarkpercentage" type="checkbox" '.set_checkbox('markpercentages[]', '0_'.$markpercentage->markpercentageID, $checkbox).' value="0_'.$markpercentage->markpercentageID.'" name="markpercentages[]"> &nbsp;';
                                            echo $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                        echo '</label>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                </div>
            </div>
        </form>

        <form class="form-horizontal mainmarktypeID" id="mainmarktypeID1" role="form" method="post">
            <input type="hidden" name="marktypeID" class="marktypeID">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_exam")?></legend>
                <div class="row">
                    <?php
                        if(count($exams)) {
                            $checkexamArr = isset($examArr[1]) ? $examArr[1] : [];
                            foreach ($exams as $exam) {
                                $checkbox = (in_array($exam->examID, $checkexamArr)) ? true : false;
                                echo '<div class="col-sm-3">';
                                    echo '<div class="checkbox">';
                                        echo '<label>';
                                            echo '<input class="classwiseexam" type="checkbox" '.set_checkbox('exams[]', '1_'.$exam->examID, $checkbox).' value="1_'.$exam->examID.'" name="exams[]"> &nbsp;';
                                            echo $exam->exam;
                                        echo '</label>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
            </fieldset>
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_class_wise")?></legend>
                <div class="row">
                    <?php 
                    $i=0; $checkclasspercentageArr = isset($classpercentageArr[1]) ? $classpercentageArr[1] : [];
                    if(count($classes)) { foreach ($classes as $class) { $i++;
                        $margintop = (($i>4) ? 'margintop' : '');
                        echo '<div class="col-sm-3 '.$margintop.'">';
                            echo '<div class="singlebox">';
                            echo "<h4 class='singleboxheader'>".$class->classes."</h4>";
                            if(count($markpercentages)) {
                                $checkclasspercentageArray = isset($checkclasspercentageArr[$class->classesID]) ? $checkclasspercentageArr[$class->classesID] : [];
                                foreach ($markpercentages as $markpercentage) {
                                    $checkbox = (in_array($markpercentage->markpercentageID, $checkclasspercentageArray)) ? true : false;
                                    echo '<div class="checkbox">';
                                        echo '<label>';
                                            $classmarkpercentagevalue = '1_'.$class->classesID.'_'.$markpercentage->markpercentageID;
                                            echo '<input class="classwisemarkpercentage" type="checkbox" '.set_checkbox('markpercentages[]', $classmarkpercentagevalue, $checkbox).' value="'.$classmarkpercentagevalue.'" name="markpercentages[]"> &nbsp;';
                                            echo $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                        echo '</label>';
                                    echo '</div>';
                                }
                            }
                            echo '</div>';
                        echo '</div>';
                    } } ?>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                </div>
            </div>
        </form>

        <form class="form-horizontal mainmarktypeID" id="mainmarktypeID2" role="form" method="post">
            <input type="hidden" name="marktypeID" class="marktypeID">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_exam_wise")?></legend>
                <div class="row">
                    <?php 
                    $i=0; $checkexamArr     = isset($examArr[2]) ? $examArr[2] : [];
                    $checkexampercentageArr = isset($exampercentageArr[2]) ? $exampercentageArr[2] : [];
                    if(count($exams)) { foreach ($exams as $exam) { $i++;
                        $checkbox  = (in_array($exam->examID, $checkexamArr)) ? true : false; 
                        $margintop = (($i>4) ? 'margintop' : '');
                        echo '<div class="col-sm-3 '.$margintop.'">';
                            echo '<div class="singlebox">';
                                echo "<h4 class='singleboxheader'><input class='examwiseexam' ".set_checkbox('exams[]', '2_'.$exam->examID, $checkbox)." type='checkbox' id='exams' value='2_".$exam->examID."' name='exams[]'/> ".$exam->exam."</h4>";
                                if(count($markpercentages)) {
                                    $checkexampercentageArray = isset($checkexampercentageArr[$exam->examID]) ? $checkexampercentageArr[$exam->examID] : [];
                                    foreach ($markpercentages as $markpercentage) {
                                        $checkbox = (in_array($markpercentage->markpercentageID, $checkexampercentageArray)) ? true : false;
                                        echo '<div class="checkbox">';
                                            echo '<label>';
                                                $exammarkpercentagevalue = '2_'.$exam->examID.'_'.$markpercentage->markpercentageID;
                                                echo '<input class="examwisemarkpercentage examwisemarkpercentage'.$exam->examID.'" type="checkbox" '.set_checkbox('markpercentages[]', $exammarkpercentagevalue, $checkbox).' value="'.$exammarkpercentagevalue.'" name="markpercentages[]"> &nbsp;';
                                                echo $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                            echo '</label>';
                                        echo '</div>';
                                    }
                                }
                            echo '</div>';
                        echo '</div>';
                    } } ?>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                </div>
            </div>
        </form>

        <form class="form-horizontal mainmarktypeID" id="mainmarktypeID3" role="form" method="post">
            <input type="hidden" name="marktypeID" class="marktypeID">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_exam_wise_individual")?></legend>
                <div class="row">
                    <?php $i=0; $checkexamArr = isset($examArr[3]) ? $examArr[3] : [];
                    $checkexampercentageArr   = isset($exampercentageArr[3]) ? $exampercentageArr[3] : [];
                    if(count($exams)) { foreach ($exams as $exam) { $i++;
                        $checkbox  = (in_array($exam->examID, $checkexamArr)) ? true : false;
                        $margintop = (($i>4) ? 'margintop' : '');
                        echo '<div class="col-sm-3 '.$margintop.'">';
                            echo '<div class="singlebox">';
                                echo "<h4 class='singleboxheader'><input class='examindividualexam' ".set_checkbox('exams[]', '3_'.$exam->examID, $checkbox)." type='checkbox' id='exams' value='3_".$exam->examID."' name='exams[]'/> ".$exam->exam."</h4>";
                                if(count($markpercentages)) {
                                    $checkexampercentageArray = isset($checkexampercentageArr[$exam->examID]) ? $checkexampercentageArr[$exam->examID] : [];
                                    foreach ($markpercentages as $markpercentage) {
                                        $checkbox = (in_array($markpercentage->markpercentageID, $checkexampercentageArray)) ? true : false;
                                        echo '<div class="checkbox">';
                                            echo '<label>';
                                                $exammarkpercentagevalue = '3_'.$exam->examID.'_'.$markpercentage->markpercentageID;
                                                echo '<input class="examindividualmarkpercentage examindividualmarkpercentage'.$exam->examID.'" type="checkbox" '.set_checkbox('markpercentages[]', $exammarkpercentagevalue, $checkbox).' value="'.$exammarkpercentagevalue.'" name="markpercentages[]"> &nbsp;';
                                                echo $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                            echo '</label>';
                                        echo '</div>';
                                    }
                                }
                            echo '</div>';
                        echo '</div>';
                    } } ?>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                </div>
            </div>
        </form>

        <form class="form-horizontal mainmarktypeID" id="mainmarktypeID4" role="form" method="post">
            <input type="hidden" name="marktypeID" class="marktypeID">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_subject_wise")?></legend>
                <div class="row">
                    <?php $i=0; 
                    $checksubjectpercentageArr   = isset($subjectpercentageArr[4]) ? $subjectpercentageArr[4] : [];
                    if(count($classes)) { foreach ($classes as $class) { $i++;
                        $margintop = (($i>4) ? 'margintop' : '');
                        echo '<div class="col-sm-3 '.$margintop.'">';
                            echo '<div class="singlebox singleboxtwo">';
                            echo "<h4 class='singleboxheader'>".$class->classes."</h4>";
                            if(isset($subjects[$class->classesID]) && count($subjects[$class->classesID])) {
                                foreach ($subjects[$class->classesID] as $subject) {
                                    echo "<h5><i class='fa fa-arrow-right'></i> ".$subject->subject."</h5>";
                                    $checkmarkpercentageArr = isset($checksubjectpercentageArr[$class->classesID][$subject->subjectID]) ? $checksubjectpercentageArr[$class->classesID][$subject->subjectID] : [];
                                    if(count($markpercentages)) {
                                        foreach ($markpercentages as $markpercentage) {
                                            $checkbox = (in_array($markpercentage->markpercentageID, $checkmarkpercentageArr)) ? true : false;
                                            echo '<div class="checkbox">';
                                                echo '<label>';
                                                    $classsubjectmarkpercentagevalue = '4_'.$class->classesID.'_'.$subject->subjectID.'_'.$markpercentage->markpercentageID;
                                                    echo '<input type="checkbox" '.set_checkbox('markpercentages[]', $classsubjectmarkpercentagevalue, $checkbox).' value="'.$classsubjectmarkpercentagevalue.'" name="markpercentages[]"> &nbsp;';
                                                    echo $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                                echo '</label>';
                                            echo '</div>';
                                        }
                                    }
                                }
                            }
                            echo '</div>';
                        echo '</div>';
                    } } ?>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                </div>
            </div>
        </form>

        <form class="form-horizontal mainmarktypeID" id="mainmarktypeID5" role="form" method="post">
            <input type="hidden" name="marktypeID" class="marktypeID">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_class_exam_wise")?></legend>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $checkclassexampercentageArr = isset($classexampercentageArr[5]) ? $classexampercentageArr[5] : [];
                        if(count($classes)) { foreach($classes as $class) {
                            echo "<div class='classexamDiv'>";
                                echo "<h4 class='classexamheader'><i class='fa fa-arrow-right'></i> ".$class->classes."</h4>";
                                echo "<div class='row'>";
                                    if(count($exams)) { $i=0;
                                        foreach ($exams as $exam) { $i++;
                                        $checkbox  = (isset($checkclassexampercentageArr[$class->classesID][$exam->examID])) ? true : false;
                                        $margintop = (($i>4) ? 'margintop' : '');
                                        echo '<div class="col-sm-3 '.$margintop.'">';
                                            echo '<div class="singlebox">';
                                                echo "<h4 class='singleboxheader'><input class='classexamexam' ".set_checkbox('exams[]', '5_'.$class->classesID.'_'.$exam->examID, $checkbox)." data-classexam='".$class->classesID.$exam->examID."' type='checkbox' value='5_".$class->classesID.'_'.$exam->examID."' name='exams[]'/> ".$exam->exam."</h4>";

                                                $checkmarkpercentageArr = isset($checkclassexampercentageArr[$class->classesID][$exam->examID]) ? $checkclassexampercentageArr[$class->classesID][$exam->examID] : [];
                                                if(count($markpercentages)) {
                                                    foreach ($markpercentages as $markpercentage) {
                                                        $checkbox = (in_array($markpercentage->markpercentageID, $checkmarkpercentageArr)) ? true : false;
                                                        echo '<div class="checkbox">';
                                                            echo '<label>';
                                                                $classexammarkpercentagevalue = '5_'.$class->classesID.'_'.$exam->examID.'_'.$markpercentage->markpercentageID;
                                                                echo '<input class="classexammarkpercentage classexammarkpercentage'.$class->classesID.$exam->examID.'" type="checkbox" '.set_checkbox('markpercentages[]', $classexammarkpercentagevalue, $checkbox).' value="'.$classexammarkpercentagevalue.'" name="markpercentages[]"> &nbsp;';
                                                                echo $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                                            echo '</label>';
                                                        echo '</div>';
                                                    }
                                                }
                                            echo '</div>';
                                        echo '</div>';
                                        }
                                    }
                                echo '</div>';
                            echo '</div>';
                        } } ?>
                    </div>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                </div>
            </div>
        </form>

        <form class="form-horizontal mainmarktypeID" id="mainmarktypeID6" role="form" method="post">
            <input type="hidden" name="marktypeID" class="marktypeID">
            <fieldset class="setting-fieldset">
                <legend class="setting-legend"><?=$this->lang->line("marksetting_class_exam_wise")?></legend>
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        $checkclassexamsubjectpercentageArr = isset($classexamsubjectpercentageArr[6]) ? $classexamsubjectpercentageArr[6] : [];
                        if(count($classes)) { foreach($classes as $class) {
                            echo "<div class='classexamDiv'>";
                                echo "<h4 class='classexamheader'><i class='fa fa-arrow-right'></i> ".$class->classes."</h4>";
                                echo "<div class='row'>";
                                    if(count($exams)) { $i=0;
                                        foreach ($exams as $exam) { $i++;
                                        $checkbox  = (isset($checkclassexamsubjectpercentageArr[$class->classesID][$exam->examID])) ? true : false;
                                        $margintop = (($i>4) ? 'margintop' : '');
                                        echo '<div class="col-sm-3 '.$margintop.'">';
                                            echo '<div class="singlebox">';
                                                echo "<h4 class='singleboxheader'><input class='classexamsubjectexam' ".set_checkbox('exams[]', '6_'.$class->classesID.'_'.$exam->examID, $checkbox)." data-classexam='".$class->classesID.$exam->examID."' type='checkbox' value='6_".$class->classesID.'_'.$exam->examID."' name='exams[]'/> ".$exam->exam."</h4>";
                                                if(isset($subjects[$class->classesID]) && count($subjects[$class->classesID])) {
                                                foreach ($subjects[$class->classesID] as $subject) {
                                                    echo "<h5><i class='fa fa-arrow-right'></i> ".$subject->subject."</h5>";
                                                    $checkmarkpercentageArr = isset($checkclassexamsubjectpercentageArr[$class->classesID][$exam->examID][$subject->subjectID]) ? $checkclassexamsubjectpercentageArr[$class->classesID][$exam->examID][$subject->subjectID] : [];

                                                    if(count($markpercentages)) {
                                                        foreach ($markpercentages as $markpercentage) {
                                                            $checkbox = (in_array($markpercentage->markpercentageID, $checkmarkpercentageArr)) ? true : false;
                                                            echo '<div class="checkbox">';
                                                                echo '<label>';
                                                                    $classexamsubjectmarkpercentagevalue = '6_'.$class->classesID.'_'.$exam->examID.'_'.$subject->subjectID.'_'.$markpercentage->markpercentageID;
                                                                    echo '<input class="classexamsubjectmarkpercentage classexamsubjectmarkpercentage'.$class->classesID.$exam->examID.'" type="checkbox" '.set_checkbox('markpercentages[]', $classexamsubjectmarkpercentagevalue, $checkbox).' value="'.$classexamsubjectmarkpercentagevalue.'" name="markpercentages[]"> &nbsp;';
                                                                    echo $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                                                echo '</label>';
                                                            echo '</div>';
                                                        }
                                                    }
                                                } }
                                            echo '</div>';
                                        echo '</div>';
                                        }
                                    }
                                echo '</div>';
                            echo '</div>';
                        } } ?>
                    </div>
                </div>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                </div>
            </div>
        </form>

    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $('.mainmarktypeID').hide();
    
    <?php if(set_value("marktypeID") || (set_value("marktypeID")==0 && set_value("marktypeID") !=NULL)) { ?>
        $('#mainmarktypeID<?=set_value("marktypeID")?>').show('slow');
        $('.marktypeID').val(<?=set_value("marktypeID")?>);
    <?php } else { ?>
        $('#mainmarktypeID<?=$siteinfos->marktypeID?>').show('slow');
        $('.marktypeID').val(<?=$siteinfos->marktypeID?>);
    <?php } ?>
    
    $('#marktypeID').change(function() {
        var marktypeID = $(this).val();
        $('.marktypeID').val(marktypeID);
        
        $('.mainmarktypeID').hide('slow');
        marktypeID = parseInt(marktypeID);
        $('#mainmarktypeID'+marktypeID).show('slow');
    });

    $('.globalmarkpercentage').attr('disabled', true);
    <?php if((isset($markpercentageArr[0]) && count($markpercentageArr[0])) || (set_value('exams[]'))) { ?>
        $('.globalmarkpercentage').attr('disabled', false);
    <?php } ?>
    $('.globalexam').click(function() {
        if($(this).prop("checked") == true) {
            $('.globalmarkpercentage').removeAttr('disabled');
        } else {
            if($('.globalexam').is(':checked') == false) {
                $('.globalmarkpercentage').attr('disabled', true);
            }
        }
    });


    $('.classwisemarkpercentage').attr('disabled', true);
    <?php if((isset($markpercentageArr[1]) && count($markpercentageArr[1])) || (set_value('exams[]'))) { ?>
        $('.classwisemarkpercentage').attr('disabled', false);
    <?php } ?>
    $('.classwiseexam').click(function() {
        if($(this).prop("checked") == true) {
            $('.classwisemarkpercentage').removeAttr('disabled');
        } else {
            if($('.classwiseexam').is(':checked') == false) {
                $('.classwisemarkpercentage').attr('disabled', true);
            }
        }
    });


    $('.examwiseexam').each(function(key, value) {
        var exam   = $(value).val();
        var res    = exam.split("_");
        var examID = res[1];
        if($(value).attr('checked') == 'checked') {
            $('.examwisemarkpercentage'+examID).removeAttr('disabled');
        } else {
            $('.examwisemarkpercentage'+examID).attr('disabled', true);
        }
    });
    $('.examwiseexam').click(function() {
        var exam   = $(this).val();
        var res    = exam.split("_");
        var examID = res[1];
        if($(this).prop("checked") == true) {
            $('.examwisemarkpercentage'+examID).removeAttr('disabled');
        } else {
            if(($('.examwiseexam').is(':checked') == false) && ($(this).prop("checked") == false)) {
                $('.examwisemarkpercentage').attr('disabled', true);
            } else {
                $('.examwisemarkpercentage'+examID).attr('disabled', true);
            }
        }
    });

    $('.examindividualexam').each(function(key, value) {
        var exam   = $(value).val();
        var res    = exam.split("_");
        var examID = res[1];
        if($(value).attr('checked') == 'checked') {
            $('.examindividualmarkpercentage'+examID).removeAttr('disabled');
        } else {
            $('.examindividualmarkpercentage'+examID).attr('disabled', true);
        }
    });
    $('.examindividualexam').click(function() {
        var exam   = $(this).val();
        var res    = exam.split("_");
        var examID = res[1];
        if($(this).prop("checked") == true) {
            $('.examindividualmarkpercentage'+examID).removeAttr('disabled');
        } else {
            if(($('.examindividualexam').is(':checked') == false) && ($(this).prop("checked") == false)) {
                $('.examindividualmarkpercentage').attr('disabled', true);
            } else {
                $('.examindividualmarkpercentage'+examID).attr('disabled', true);
            }
        }
    });

    $('.classexamexam').each(function(key, value) {
        var classexam = $(value).data('classexam');
        console.log(classexam);
        if($(value).attr('checked') == 'checked') {
            $('.classexammarkpercentage'+classexam).removeAttr('disabled');
        } else {
            $('.classexammarkpercentage'+classexam).attr('disabled', true);
        }
    });
    $('.classexamexam').click(function() {
        var classexam = parseInt($(this).data('classexam'));
        if($(this).prop("checked") == true) {
            $('.classexammarkpercentage'+classexam).removeAttr('disabled');
        } else {
            if(($('.classexamexam').is(':checked') == false) && ($(this).prop("checked") == false)) {
                $('.classexammarkpercentage').attr('disabled', true);
            } else {
                $('.classexammarkpercentage'+classexam).attr('disabled', true);
            }
        }
    });

    $('.classexamsubjectexam').each(function(key, value) {
        var classexam = $(value).data('classexam');
        if($(value).attr('checked') == 'checked') {
            $('.classexamsubjectmarkpercentage'+classexam).removeAttr('disabled');
        } else {
            $('.classexamsubjectmarkpercentage'+classexam).attr('disabled', true);
        }
    });
    $('.classexamsubjectexam').click(function() {
        var classexam = parseInt($(this).data('classexam'));
        if($(this).prop("checked") == true) {
            $('.classexamsubjectmarkpercentage'+classexam).removeAttr('disabled');
        } else {
            if(($('.classexamsubjectexam').is(':checked') == false) && ($(this).prop("checked") == false)) {
                $('.classexamsubjectmarkpercentage').attr('disabled', true);
            } else {
                $('.classexamsubjectmarkpercentage'+classexam).attr('disabled', true);
            }
        }
    });
</script>