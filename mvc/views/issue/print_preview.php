<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="profileArea">
        <?php featureheader($siteinfos);?>
        <div class="mainArea">
            <div class="areaTop">
                <div class="studentImage">
                    <img class="studentImg" src="<?=pdfimagelink($student->photo)?>" alt="">
                </div>
                <div class="studentProfile">
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('issue_name')?></div>
                        <div class="single_value">: <?=$student->srname?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('issue_type')?></div>
                        <div class="single_value">: <?=count($usertype) ? $usertype->usertype : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('issue_registerNO')?></div>
                        <div class="single_value">: <?=$student->srregisterNO?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('issue_roll')?></div>
                        <div class="single_value">: <?=$student->srroll?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('issue_classes')?></div>
                        <div class="single_value">: <?=count($classes) ? $classes->classes : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('issue_section')?></div>
                        <div class="single_value">: <?=count($section) ? $section->section : ''?></div>
                    </div>
                </div>
            </div>
            <div class="areaBottom">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%"><?=$this->lang->line("issue_book")?></th>
                            <td width="70%"><?php  echo $book->book; ?></td>
                        </tr>

                        <tr>
                            <th width="30%"><?=$this->lang->line("issue_author")?></th>
                            <td width="70%"><?php  echo $book->author; ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("issue_serial_no")?></th>
                            <td width="70%"><?php  echo $book->serial_no; ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("issue_issue_date")?></th>
                            <td width="70%"><?=date("d M Y", strtotime($book->issue_date));?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("issue_due_date")?></th>
                            <td width="70%"><?=date("d M Y", strtotime($book->due_date));?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("issue_return_date")?></th>
                            <td width="70%">
                                <?php
                                    if(!$book->return_date == "" && !empty($book->return_date)) {
                                    echo date("d M Y", strtotime($book->return_date));
                                    }
                                ?>               
                            </td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("issue_note")?></th>
                            <td width="70%"><?php  echo $book->note; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php featurefooter($siteinfos);?>
</body>
</html>
