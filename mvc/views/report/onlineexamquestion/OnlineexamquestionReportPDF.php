<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <?php if($typeID == 1) { ?>
        <?=reportheader($siteinfos,$schoolyearsessionobj,true)?>
        <div class="headerInfo">
            <h4 class="pull-left text-bold"><?=$this->lang->line('onlineexamquestionreport_exam')?> : <?=$exam->name?></h4>
            <h4 class="pull-right text-bold"><?=$this->lang->line('onlineexamquestionreport_marks')?> : <?=$getMark?></h4>
        </div>
        <div>
            <?php if(count($questions)) {
                $i = 0;
                foreach($questions as $question) {
                    $optionCount = $question->totalOption;
                    $i++; ?>
                    <div class="clearfix">
                        <div class="question-body">
                            <div class="questionlabel"><b><?=$i?>.</b> <?=$question->question?></div>
                            <div class="questionvalue"> 
                                <?=$question->mark != "" ? $question->mark.' '.$this->lang->line('onlineexamquestionreport_mark') : ''?>
                            </div>
                        </div>

                        <?php if($question->upload != '') { ?>
                            <div>
                                <img style="width:250px;height:150px;padding-left: 20px;margin: 10px 0px" src="<?=base_url('uploads/images/'.$question->upload)?>" alt="">
                            </div>
                        <?php } ?>

                        <div class="question-answer">
                            <table class="table">
                                <tr>
                                <?php
                                    $oc = 1;
                                    $tdCount = 0;
                                    $questionoptions = isset($question_options[$question->questionBankID]) ? $question_options[$question->questionBankID] : [];
                                    if(count($questionoptions)) {
                                        $optionLabel = 'A';
                                        foreach ($questionoptions as $option) {
                                            if($optionCount >= $oc) { $oc++; ?>
                                            <td>
                                                <div class="optionValue">
                                                    <span><?=$optionLabel?>.</span>
                                                    <span><?=$option->name?></span>
                                                </div>
                                                <div class="optionImage">
                                                    <?php
                                                        if(!is_null($option->img) && $option->img != "") { ?>
                                                            <img class="questionimg" src="<?=base_url('uploads/images/'.$option->img)?>"/>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                            </td>
                                            <?php
                                                $optionLabel++;
                                            }
                                            $tdCount++;
                                            if($tdCount == 2) {
                                                $tdCount = 0;
                                                echo "</tr><tr>";
                                            }
                                        }
                                    }
                                ?>
                                </tr>
                            </table>
                        </div>
                    </div>
            <?php } } else { ?>
            <div class="notfound">
                <p><b class="text-info"><?=$this->lang->line('onlineexamquestionreport_data_not_found')?></b></p>
            </div>
            <?php } ?>
        </div>
        <?=reportfooter($siteinfos,$schoolyearsessionobj)?>
    <?php } elseif($typeID == 2) { ?>
        <?=reportheader($siteinfos,$schoolyearsessionobj,true)?>
        <div class="headerInfo">
            <h4 class="pull-left text-bold"><?=$this->lang->line('onlineexamquestionreport_exam')?> : <?=$exam->name?></h4>
            <h4 class="pull-right text-bold"><?=$this->lang->line('onlineexamquestionreport_duration')?> : <?=($exam->duration) ? $exam->duration." ".$this->lang->line('onlineexamquestionreport_minute') : 'N/A'?></h4>
        </div>
        <?php if(count($questions)) { ?>
        <div class="userInfo">
            <div class="singleUser">
                <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_name')?></div>
                <div class="userValue">: </div>
            </div>
            <div class="singleUser">
                <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_class')?></div>
                <div class="userValue">: </div>
            </div>
            <div class="singleUser">
                <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_roll')?></div>
                <div class="userValue">: </div>
            </div>
            <div class="singleUser">
                <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_subject')?></div>
                <div class="userValue">: <?=isset($subjects[$exam->subjectID]) ? $subjects[$exam->subjectID] : '' ?></div>
            </div>
        </div>
        <?php } ?>
        <div class="fullWidth">
            <?php 
            if(count($questions)) {
                $f = false;
                $i = 0;
                $j = 0; 
                echo '<div class="halfWidth">';
                foreach($questions as $question) { 
                    if(($j != 0) && (($j%25) == 0)) {
                        $f = true;
                    }

                    if($f) {
                        echo '</div>';
                        echo '<div class="halfWidth">';
                        $f = false;
                    }

                    $optionCount = $question->totalOption; $i++; $j++; ?>
                     <div class="clearfix2">
                        <div class="question-body">
                            <label><b><?=$i?>.</b></label>
                        </div>
                        <div class="question-answer">
                        <?php
                            $oc = 1;
                            $questionoptions = isset($question_options[$question->questionBankID]) ? $question_options[$question->questionBankID] : [];
                            if(count($questionoptions)) {
                                $optionLabel = 'A';
                                foreach ($questionoptions as $option) {
                                    if($optionCount >= $oc) { $oc++; ?>
                                        <div class="optionLabel"><?=$optionLabel?></div>
                                    <?php
                                        $optionLabel++;
                                    }
                                }
                            } else {
                                $optionLabel = 'A';
                                if($question->typeNumber == 3) {
                                    if($optionCount > 0) {
                                        for ($k=1; $k <= $optionCount; $k++) { ?>
                                        <div class="singleFil">
                                            <div class="single_label"><?=$optionLabel?>.</div>
                                            <div class="singleFilup">&nbsp;</div>
                                        </div>
                                        <?php
                                        $optionLabel++;
                                        }
                                    }
                                }
                            }
                        ?>
                        </div>
                    </div>
                    <?php 
                }
                echo '</div>';
            } else { ?>
                <div class="notfound">
                    <p><?=$this->lang->line('onlineexamquestionreport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
        <?=reportfooter($siteinfos,$schoolyearsessionobj)?>
    <?php } elseif($typeID == 3) { ?>
        <?=reportheader($siteinfos,$schoolyearsessionobj,true)?>
        <div class="headerInfo">
            <h4 class="pull-left text-bold"><?=$this->lang->line('onlineexamquestionreport_exam')?> : <?=$exam->name?></h4>
            <h4 class="pull-right text-bold"><?=$this->lang->line('onlineexamquestionreport_duration')?> : <?=($exam->duration) ? $exam->duration." ".$this->lang->line('onlineexamquestionreport_minute') : 'N/A'?></h4>
        </div>
        <div class="fullWidth">
        <?php 
        if(count($questions)) {
            $f = false;
            $i = 0;
            $j = 0; 
            echo '<div class="halfWidth">';
            foreach($questions as $question) {
                if(($j != 0) && (($j%25) == 0)) {
                    $f = true;
                }

                if($f) {
                    echo '</div>';
                    echo '<div class="halfWidth">';
                    $f = false;
                }
                $optionCount = $question->totalOption;

                $questionAnswers = isset($answers[$question->questionBankID]) ? $answers[$question->questionBankID] : [];
                if($question->typeNumber == 1 || $question->typeNumber == 2) {
                    $questionAnswers = pluck($questionAnswers, 'optionID');
                }

                $i++;
                $j++;
                ?>
                <div class="clearfix2 clearfix3">
                    <div class="question-body">
                        <label><b><?=$i?>.</b></label>
                    </div>
                    <div class="question-answer">
                    <?php
                        $oc = 1;
                        $questionoptions = isset($question_options[$question->questionBankID]) ? $question_options[$question->questionBankID] : [];
                        if(count($questionoptions)) {
                            $optionLabel = 'A';
                            foreach ($questionoptions as $option) {
                                $ansClass = '';
                                if(in_array($option->optionID, $questionAnswers)) {
                                    $ansClass = 'checked';
                                }

                                if($optionCount >= $oc) { $oc++; ?>
                                    <div class="<?=$ansClass?> optionLabel"><?=$optionLabel?></div>
                                <?php
                                    $optionLabel++;
                                }
                            }
                        } else {
                            $optionLabel = 'A';
                            if($question->typeNumber == 3) {
                                if($optionCount > 0) {
                                    for ($k=0; $k < $optionCount; $k++) { ?>
                                    <div class="singleFil">
                                        <div class="single_label"><?=$optionLabel?>.</div>
                                        <div class="singleFilup"><?=$questionAnswers[$k]->text?></div>
                                    </div>
                                    <?php
                                    $optionLabel++;
                                    }
                                }
                            }
                        }
                    ?>
                    </div>
                </div>
                <?php 
            }
            echo '</div>';
        } else { ?>
            <div class="notfound">
                <p><?=$this->lang->line('onlineexamquestionreport_data_not_found')?></p>
            </div>
        <?php } ?>
        </div>
        <?=reportfooter($siteinfos,$schoolyearsessionobj)?>
    <?php } else { ?>
        <div class="notfound">
            <p><?=$this->lang->line('onlineexamquestionreport_data_not_found')?></p>
        </div>
    <?php } ?>
</body>
</html>