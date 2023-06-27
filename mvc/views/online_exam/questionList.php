<div id="hide-table">
    <?php if(count($questions)) {?>
    <table id="example1" class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
            <th class="col-sm-8"><?=$this->lang->line('online_exam_question')?></th>
            <th class="col-sm-2"><?=$this->lang->line('online_exam_question_type')?></th>
            <th class="col-sm-1"><?=$this->lang->line('action')?></th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach ($questions as $key => $question) {
        ?>
                <tr>
                    <td data-title="<?=$this->lang->line('slno')?>"><?=$key+1?></td>
                    <td data-title="<?=$this->lang->line('online_exam_question')?>">
                        <?php
                        if(strlen($question->question) > 25)
                            echo substr(strip_tags($question->question), 0, 25)."...";
                        else
                            echo substr(strip_tags($question->question), 0, 25);
                        ?>
                    </td>
                    <td data-title="<?=$this->lang->line('online_exam_question_type')?>">
                        <?=isset($types[$question->typeNumber]) ? $types[$question->typeNumber]->name : ''; ?>
                    </td>
                    <td data-title="<?=$this->lang->line('action')?>"><button onclick="javascript:void(0);addQuestion(<?=$question->questionBankID?>)" class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='<?=$this->lang->line('online_exam_add_question')?>'><i class='fa fa-plus'></i> </button></td>
                </tr>
        <?php
            }
        ?>

        </tbody>
    </table>
    <?php } ?>
</div>