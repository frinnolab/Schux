<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <h3><?=$this->lang->line('librarybookissuereport_report_for')?> - <?=$this->lang->line('librarybookissuereport_librarybooksissue');?></h3>
     <div class="col-sm-12">
        <h5 class="pull-left">
            <?php
                $f = FALSE;
                if($fromdate != '0' && $todate != '0') {
                    echo $this->lang->line('librarybookissuereport_fromdate')." : ";
                    echo date('d M Y',$fromdate);
                } elseif($lID != '') {
                    echo $this->lang->line('librarybookissuereport_libraryID')." : ";
                    echo $lID;
                } elseif($typeID !=0) {
                    echo $this->lang->line('librarybookissuereport_type')." : ";
                    if($typeID == 1) {
                        echo $this->lang->line('librarybookissuereport_issuedate');
                    } elseif($typeID == 2) {
                        echo $this->lang->line('librarybookissuereport_returndate');
                    } elseif($typeID == 3) {
                        echo $this->lang->line('librarybookissuereport_duedate');
                    }
                } else {
                    echo $this->lang->line('librarybookissuereport_class')." : ";
                    echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('librarybookissuereport_all_class');
                    $f = TRUE;
                }
            ?>
        </h5>                         
        <h5 class="pull-right">
            <?php
                if($fromdate != '0' && $todate != '0') {
                    echo $this->lang->line('librarybookissuereport_todate')." : ";
                    echo date('d M Y',$todate);
                } else {
                    if($f) {
                        echo $this->lang->line('librarybookissuereport_section')." : ";
                        echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('librarybookissuereport_all_section');
                    }
                }
            ?>
        </h5>
    </div>
    <div class="col-sm-12">
        <?php if(count($getLibrarybookissueReports)) { ?>
            <table>
                <thead>
                <tr>
                    <th><?=$this->lang->line('slno')?></th>
                    <th><?=$this->lang->line('librarybookissuereport_libraryID')?></th>
                    <?php 
                        if($typeID == 0) {
                            echo '<th>'.$this->lang->line('librarybookissuereport_issuedate').'</th>';
                            echo '<th>'.$this->lang->line('librarybookissuereport_duedate').'</th>';
                            echo '<th>'.$this->lang->line('librarybookissuereport_returndate').'</th>';
                        } elseif($typeID == 1) {
                            echo '<th>'.$this->lang->line('librarybookissuereport_issuedate').'</th>';
                        } elseif($typeID == 2) {
                            echo '<th>'.$this->lang->line('librarybookissuereport_returndate').'</th>';
                        } elseif($typeID == 3) {
                            echo '<th>'.$this->lang->line('librarybookissuereport_duedate').'</th>';
                        }
                    ?>
                    <th><?=$this->lang->line('librarybookissuereport_name')?></th>
                    <th><?=$this->lang->line('librarybookissuereport_registerNO')?></th>
                    <th><?=$this->lang->line('librarybookissuereport_subject_code')?></th>
                    <th><?=$this->lang->line('librarybookissuereport_book')?></th>
                    <th><?=$this->lang->line('librarybookissuereport_serial')?></th>
                    <th><?=$this->lang->line('librarybookissuereport_status')?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i= 0;
                foreach($getLibrarybookissueReports as $getLibrarybookissueReport) { $i++; ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$getLibrarybookissueReport->lID?></td>
                        <?php 
                        if($typeID == 0) {
                            if(isset($getLibrarybookissueReport->issue_date)) {
                                echo '<td>'.date('d M Y', strtotime($getLibrarybookissueReport->issue_date)).'</td>';
                            } else {
                                echo '<td data-title="'.$this->lang->line('librarybookissuereport_issuedate').'"></td>';
                            }

                            if(isset($getLibrarybookissueReport->due_date)) {
                                echo '<td>'.date('d M Y', strtotime($getLibrarybookissueReport->due_date)).'</td>';
                            } else {
                                echo '<td></td>';
                            }

                            if(isset($getLibrarybookissueReport->return_date)) {
                                echo '<td>'.date('d M Y', strtotime($getLibrarybookissueReport->return_date)).'</td>';
                            } else {
                                echo '<td></td>';
                            }
                        } elseif($typeID == 1) {
                            if(isset($getLibrarybookissueReport->issue_date)) {
                                echo '<td>'.date('d M Y', strtotime($getLibrarybookissueReport->issue_date)).'</td>';
                            } else {
                                echo '<td></td>';
                            }
                        } elseif($typeID == 2) {
                            if(isset($getLibrarybookissueReport->return_date)) {
                                echo '<td>'.date('d M Y', strtotime($getLibrarybookissueReport->return_date)).'</td>';
                            } else {
                                echo '<td></td>';
                            }
                        } elseif ($typeID == 3) {
                            if(isset($getLibrarybookissueReport->due_date)) {
                                echo '<td>'.date('d M Y', strtotime($getLibrarybookissueReport->due_date)).'</td>';
                            } else {
                                echo '<td></td>';
                            }
                        }
                        ?>
                        <td><?=$getLibrarybookissueReport->srname?></td>
                        <td><?=$getLibrarybookissueReport->srregisterNO?></td>
                        <td><?=isset($books[$getLibrarybookissueReport->bookID]) ? $books[$getLibrarybookissueReport->bookID]->subject_code : '' ?></td>
                        <td><?=isset($books[$getLibrarybookissueReport->bookID]) ? $books[$getLibrarybookissueReport->bookID]->book : '' ?></td>
                        <td><?=$getLibrarybookissueReport->serial_no?></td>

                        <?php
                            echo "<td>";
                                if($getLibrarybookissueReport->return_date != '') {
                                    echo $this->lang->line('librarybookissuereport_return') ;
                                } else {
                                    echo $this->lang->line('librarybookissuereport_non_return') ;
                                }
                            echo "</td>";
                        ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="notfound">
                <?=$this->lang->line('librarybookissuereport_data_not_found')?>
            </div>
        <?php } ?>
    </div>
    <div class="col-sm-12 text-center footerAll">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
</body>
</html>

