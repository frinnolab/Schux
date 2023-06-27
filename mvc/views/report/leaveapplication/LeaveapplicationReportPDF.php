<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <div>
        <h3><?=$this->lang->line('leaveapplicationreport_report_for')?> - <?=$this->lang->line('leaveapplicationreport_leaveapllication')?></h3>
    </div>
    <div>
        <h5 class="pull-left">
        <?php if($fromdate && $todate) { ?>
            <?=$this->lang->line('leaveapplicationreport_fromdate')?> : <?=date('d M Y',$fromdate)?>
        <?php } elseif($categoryID) { ?>
            <?=$this->lang->line('leaveapplicationreport_category')?> : <?=$categoryName?>
        <?php } elseif($usertypeID) { ?>
            <?=$this->lang->line('leaveapplicationreport_role')?> : <?=isset($usertypes[$usertypeID]) ? $usertypes[$usertypeID] : ''?>
        <?php } ?>
        </h5>  
        <h5 class="pull-right">
        <?php if($fromdate && $todate) { ?>
            <?=$this->lang->line('leaveapplicationreport_todate')?> : <?=date('d M Y',$todate)?>
        <?php } elseif($statusID) { ?>
            <?=$this->lang->line('leaveapplicationreport_status')?> : <?=$statusName?>
        <?php } elseif((int)$usertypeID && (int)$userID) { ?>
                <?=$this->lang->line('leaveapplicationreport_user')?> : <?=isset($userObejct[$usertypeID][$userID]) ? $userObejct[$usertypeID][$userID]->name : '' ?>
        <?php } ?>
        </h5>  
    </div>
    <div>
        <?php if (count($leaveapplications)) { ?>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th><?=$this->lang->line('leaveapplicationreport_slno')?></th>
                    <th><?=$this->lang->line('leaveapplicationreport_applicant')?></th>
                    <th><?=$this->lang->line('leaveapplicationreport_role')?></th>
                    <?php if($usertypeID == 3) { ?>
                        <th><?=$this->lang->line('leaveapplicationreport_class')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_section')?></th>
                    <?php } ?>
                    <th><?=$this->lang->line('leaveapplicationreport_category')?></th>
                    <th><?=$this->lang->line('leaveapplicationreport_date')?></th>
                    <th><?=$this->lang->line('leaveapplicationreport_schdule')?></th>
                    <th><?=$this->lang->line('leaveapplicationreport_days')?></th>
                    <th><?=$this->lang->line('leaveapplicationreport_status')?></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 0; foreach($leaveapplications as $leaveapplication) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_applicant')?>"><?=isset($userObejct[$leaveapplication->create_usertypeID][$leaveapplication->create_userID]) ? $userObejct[$leaveapplication->create_usertypeID][$leaveapplication->create_userID]->name : '' ?></td>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_role')?>"><?=isset($usertypes[$leaveapplication->create_usertypeID]) ? $usertypes[$leaveapplication->create_usertypeID] : ''?></td>
                    <?php if($usertypeID == 3) { ?>
                        <td data-title="<?=$this->lang->line('leaveapplicationreport_class')?>"><?=isset($classes[$leaveapplication->srclassesID]) ? $classes[$leaveapplication->srclassesID] : ''?></td>
                        <td data-title="<?=$this->lang->line('leaveapplicationreport_section')?>"><?=isset($sections[$leaveapplication->srsectionID]) ? $sections[$leaveapplication->srsectionID] : ''?></td>
                    <?php } ?>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_category')?>"><?=$leaveapplication->leavecategory?></td>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_date')?>"><?=date('d M Y',strtotime($leaveapplication->apply_date))?></td>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_schdule')?>"><?=date('d M Y',strtotime($leaveapplication->from_date))?> - <?=date('d M Y',strtotime($leaveapplication->to_date))?></td>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_days')?>"><?=$leaveapplication->leave_days?></td>
                    <td data-title="<?=$this->lang->line('leaveapplicationreport_status')?>">
                        <?php 
                            if($leaveapplication->status == 1) {
                                echo "Approved";
                            } elseif($leaveapplication->status == '0') {
                                echo "Delined";
                            } else {
                                echo "Pending";
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>   
            <div class="notfound">
                <p><b class="text-info"><?=$this->lang->line('leaveapplicationreport_data_not_found')?></b></p>
            </div>
        <?php } ?>
    </div>
    <div>
        <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
    </div>
</body>
</html>