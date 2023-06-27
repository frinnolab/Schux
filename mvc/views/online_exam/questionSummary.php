<?php
    if(count($onlineExamQuestions)) {
        ?>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="col-sm-1">#</th>
                <th class="col-sm-8"><?=$this->lang->line('online_exam_question')?></th>
                <th class="col-sm-2"><?=$this->lang->line('online_exam_question_mark')?></th>
                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            foreach ($onlineExamQuestions as $key => $onlineExamQuestion) {
                $question = isset($questions[$onlineExamQuestion->questionID]) ? $questions[$onlineExamQuestion->questionID] : '';
                if($question != '') {

                    ?>
                    <tr>
                        <td data-title="<?=$this->lang->line('slno')?>"><?=$key+1?></td>
                        <td data-title="<?=$this->lang->line('online_exam_question')?>">
                            <?php
                                if(strlen($question->question) > 15)
                                    echo substr(strip_tags($question->question), 0, 15).'...';
                                else
                                    echo strip_tags($question->question);
                            ?>
                        </td>
                        <td data-title="<?=$this->lang->line('online_exam_question_mark')?>">
                            <?php
                                if($question->mark != "") {
                                    $total += $question->mark;
                                    echo $question->mark;
                                }
                            ?>
                        </td>
                        <td>
                            <span class="pull-right"><button onclick="javascript:void(0);removeQuestion(<?=$onlineExamQuestion->onlineExamQuestionID?>)" class="btn btn-danger btn-xs mrg"><i class='fa fa-trash-o'></i></button>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <td colspan="2"><?=$this->lang->line('online_exam_total')?></td>
                <td colspan="2"><?=$total?></td>
            </tr>
            </tbody>
        </table>
        <?php
    } else {
        echo "<p class='text-center'>".$this->lang->line('online_exam_no_question')."</p>";
    }
?>
