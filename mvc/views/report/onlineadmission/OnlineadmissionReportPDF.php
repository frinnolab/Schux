<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div style="margin-bottom: 50px;">
        <div class="row">
            <div class="reportPage-header">
                <span class="header"><img class="logo" src="<?=base_url('uploads/images/'.$siteinfos->photo)?>"></span>
                <p class="title"><?=$siteinfos->sname?></p>
                <p class="title-desc"><?=$siteinfos->address?></p>
                <p class="title-desc"><?=$this->lang->line('onlineadmissionreport_academicyear'). ' : '.$schoolyearName?></p>
            </div>
            <div style="margin-bottom: -15px">
                <h3><?=$this->lang->line('onlineadmissionreport_report_for')?> - <?=$this->lang->line('onlineadmissionreport_onlineadmission')?></h3>
            </div>

            <div>
                <?php if(((int)$admissionID) && ($admissionID > 0)) { ?>
                    <h5 class="pull-left"><?=$this->lang->line('onlineadmissionreport_admissionID')?> : <?=$admissionID?></h5>   
                <?php } elseif(((int)$schoolyearID) && ($schoolyearID > 0)) { ?>
                        <div class="singlelabel" style="text-align: left;">
                            <h5><?=$this->lang->line('onlineadmissionreport_academicyear')?> : <?=$schoolyearName?></h5>
                        </div>
                        <div class="singlelabel" style="text-align: center;">
                            <?php $f = FALSE; if((int)$classesID && $status != '10') { $f = TRUE;?>
                                <h5><?=$this->lang->line('onlineadmissionreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?></h5>
                            <?php } else {
                                echo "<h5></h5>";
                            } ?>
                        </div>
                        <div class="singlelabel" style="text-align: right;">
                            <?php if($f && ($status !='10')) { ?>
                                <h5><?=$this->lang->line('onlineadmissionreport_status')?> : <?=isset($checkstatus[$status]) ? $checkstatus[$status] : ''?></h5>
                            <?php } elseif((int)$classesID) { ?>
                                <h5><?=$this->lang->line('onlineadmissionreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?></h5>
                            <?php } elseif(((int)$status || $status == 0)) { ?>
                                <h5><?=$this->lang->line('onlineadmissionreport_status')?> : <?=isset($checkstatus[$status]) ? $checkstatus[$status] : ''?></h5>
                            <?php } else {
                                echo "<h5></h5>";
                            } ?>
                        </div>
                <?php } ?>
            </div>

            <div class="accountledgerreport">
                <?php if(count($onlineadmissions)) { ?>
                <table> 
                    <thead>
                        <tr>
                            <th><?=$this->lang->line('slno')?></th> 
                            <th><?=$this->lang->line('onlineadmissionreport_photo')?></th>
                            <th><?=$this->lang->line('onlineadmissionreport_name')?></th>
                            <?php if($admissionID == 0) { ?>
                                <th><?=$this->lang->line('onlineadmissionreport_admissionID')?></th>
                            <?php } ?>
                            <?php if(!(int)$classesID) { ?>
                                <th><?=$this->lang->line('onlineadmissionreport_class')?></th>
                            <?php } ?>
                            <th><?=$this->lang->line('onlineadmissionreport_gender')?></th>
                            <?php if($phone == 1) { ?>
                                <th><?=$this->lang->line('onlineadmissionreport_phone')?></th>
                            <?php }?>
                            <?php if($status == 10) { ?>
                                <th><?=$this->lang->line('onlineadmissionreport_status')?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; foreach($onlineadmissions as $onlineadmission) { $i++; ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=profileimage($onlineadmission->photo)?></td>
                                <td><?=$onlineadmission->name?></td>
                                <?php if($admissionID == 0) { ?>
                                    <td>
                                        <?php 
                                            $admissionIDlen = strlen($onlineadmission->onlineadmissionID);
                                            $boxLimit = 8;

                                            if($admissionIDlen >= $boxLimit) {
                                                $boxLimit += 2;
                                            }

                                            $zerolength = ($boxLimit - $admissionIDlen);
                                            if($zerolength > 0) {
                                                for($i=1; $i <= $zerolength; $i++) {
                                                    echo 0;
                                                }
                                            }
                                            $admissionIDArray = str_split($onlineadmission->onlineadmissionID);
                                            if(count($admissionIDArray)) {
                                                foreach ($admissionIDArray as $value) {
                                                    echo $value;
                                                }
                                            }
                                        ?>
                                    </td>
                                <?php } ?>
                                <?php if(!(int)$classesID) { ?>
                                    <td>
                                        <?=isset($classes[$onlineadmission->classesID]) ? $classes[$onlineadmission->classesID] : ''?>
                                    </td>
                                <?php } ?>
                                <td><?=$onlineadmission->sex?></td>
                                <?php if($phone == 1) { ?>
                                    <td><?=$onlineadmission->phone?></td>
                                <?php } ?>
                                <?php if($status == 10) { ?>
                                    <td><?=isset($checkstatus[$onlineadmission->status]) ? $checkstatus[$onlineadmission->status] : ''?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    <div class="notfound">
                        <p><b class="text-info"><?=$this->lang->line('onlineadmissionreport_data_not_found')?></b></p>
                    </div>
                <?php } ?>
            </div>
            <div class="text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</body>
</html>