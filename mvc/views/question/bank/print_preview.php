<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
  <div class="profileArea">
    <?php featureheader($siteinfos);?>
    <div class="noticeArea">
   <?php
        $questionOptions = isset($options[$question->questionBankID]) ? $options[$question->questionBankID] : [];
        $questionAnswers = isset($answers[$question->questionBankID]) ? $answers[$question->questionBankID] : [];
        if($question->typeNumber == 1 || $question->typeNumber == 2) {
            $questionAnswers = pluck($questionAnswers, 'optionID');
        }

        if($question != '') { ?>
            <div style="float: left;width: 90%" class="question"><?=$question->question?></div>
            <div style="float: right;width: 10%"><?= $question->mark != "" ? $question->mark.' '.$this->lang->line('question_bank_mark') : ''?> </div>

            <?php if($question->upload != '') { ?>
            <div style="width: 100%">
                <img style="width:220px;height:120px;" src="<?=base_url('uploads/images/'.$question->upload)?>" alt="">
            </div>
            <?php } ?>
            
            <div style="width: 100%">
                <table class="table">
                    <tr>
                        <?php
                        $tdCount = 0;
                        foreach ($questionOptions as $option) {
                            $checked = '';
                            if(in_array($option->optionID, $questionAnswers)) {
                                $checked = 'checked="true"';
                            } ?>
                            <td class="singleQuestionBank">
                                <label for="option<?=$option->optionID?>">
                                    <div>
                                        <input class="questionBankChecked" id="option<?=$option->optionID?>" value="1" name="option" type="<?=$question->typeNumber == 1 ? 'radio' : 'checkbox'?>" <?=$checked?>> <?=$option->name?>
                                    </div>
                                    <?php
                                        if(!is_null($option->img) && $option->img != "") { ?>
                                        <div>
                                            <img src="<?=base_url('uploads/images/'.$option->img)?>" style="width: 100px;height: 80px"/>
                                        </div>
                                        <?php } ?>
                                </label>
                            </td>
                            <?php
                                $tdCount++;
                                if($tdCount == 2) {
                                    $tdCount = 0;
                                    echo "</tr><tr>";
                                }
                            } ?>
                    </tr>
                </table>
                <?php
                 if($question->typeNumber == 3) {
                    foreach ($questionAnswers as $answerKey => $answer) { ?>
                    <div class="fillinBox">
                        <div class="fillinLabel"><?=$answerKey+1?> . </div>
                        <div class="fillinValue"><?=$answer->text?></div>
                    </div>
                <?php } } ?>
            </div>
        <?php } ?>
    </div>
  </div>
    <?=featurefooter($siteinfos);?>
</body>
</html>
