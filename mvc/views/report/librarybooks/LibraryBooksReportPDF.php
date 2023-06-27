<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <h3><?=$this->lang->line('librarybooksreport_report_for')?> - <?=$this->lang->line('librarybooksreport_librarybooks');?></h3>
     <div class="col-sm-12">
        <h5 class="pull-left">
            <?php if($status == 1) {
                echo $this->lang->line('librarybooksreport_status')." : ";
                echo $this->lang->line('librarybooksreport_available');
            } elseif($status == 2) {
                echo $this->lang->line('librarybooksreport_status')." : ";
                echo $this->lang->line('librarybooksreport_unavailable');
            } elseif($bookname != '0') {
                echo $this->lang->line('librarybooksreport_bookname')." : ".$bookfullname;
            } elseif ($subjectcode != '0') {
                echo $this->lang->line('librarybooksreport_subjectcode')." : ".$subjectcode;
            } elseif($rackNo != '0') {
                echo $this->lang->line('librarybooksreport_rackNo')." : ".$rackNo;
            } ?>
        </h5>
    </div>
    <div class="col-sm-12">
        <?php if(count($books)) { ?>
        <div id="hide-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?=$this->lang->line('slno')?></th>
                        <th><?=$this->lang->line('librarybooksreport_bookname')?></th>
                        <th><?=$this->lang->line('librarybooksreport_author')?></th>
                        <th><?=$this->lang->line('librarybooksreport_subjectcode')?></th>
                        <th><?=$this->lang->line('librarybooksreport_rackNo')?></th>
                        <th><?=$this->lang->line('librarybooksreport_status')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=0; 
                        foreach($books as $book) { $i++?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$book['bookname']?></td>
                                <td><?=$book['author']?></td>
                                <td><?=$book['subjectcode']?></td>
                                <td><?=$book['rackNo']?></td>
                                <td><?=$book['status']?></td>
                            </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
            <br/>
            <div class="notfound">
                <?=$this->lang->line('librarybooksreport_data_not_found')?>
            </div>
        <?php } ?>
    </div>
    <div class="text-center footerAll">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
</body>
</html>

