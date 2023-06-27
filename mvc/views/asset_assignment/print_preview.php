<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <div class="profileArea">
    <?php featureheader($siteinfos);?>
    <div class="mainArea">
    <?php 
        if((int)$asset_assignment->usertypeID && (int)$asset_assignment->check_out_to && isset($user[$asset_assignment->check_out_to])) { $users = $user[$asset_assignment->check_out_to]; ?>

      <div class="areaTop">
        <div class="studentImage">
          <img class="studentImg" src="<?=pdfimagelink($users->photo)?>" alt="">
        </div>
        <div class="studentProfile">
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_assignment_name')?></div>
            <div class="single_value">: <?=$users->name?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_assignment_usertypeID')?></div>
            <div class="single_value">: <?=isset($usertypes[$users->usertypeID]) ? $usertypes[$users->usertypeID] : ''?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_assignment_gender')?></div>
            <div class="single_value">: <?=$users->sex?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_assignment_dob')?></div>
            <div class="single_value">: <?php if($users->dob) { echo date("d M Y", strtotime($users->dob)); } ?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('asset_assignment_phone')?></div>
            <div class="single_value">: <?=$users->phone?></div>
          </div>
        </div>
      </div>
    <?php } ?>
      <div class="areaBottom">
        <h3><?=$this->lang->line('asset_assignment_information')?></h3>
        <table class="table table-bordered">
            <?php if((int)$asset_assignment->usertypeID && $asset_assignment->check_out_to == '0') { ?>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_usertypeID")?></td>
                <td> <?=isset($usertypes[$asset_assignment->usertypeID]) ? $usertypes[$asset_assignment->usertypeID] : ''?></td>
            </tr>
            <?php } ?>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_assetID")?></td>
                <td> <?=$asset_assignment->description; ?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_assigned_quantity")?></td>
                <td> <?=$asset_assignment->assigned_quantity; ?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_due_date")?></td>
                <td> <?=isset($asset_assignment->due_date) ?  date('d M Y', strtotime($asset_assignment->due_date)) : ''; ?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_check_out_date")?></td>
                <td> <?=isset($asset_assignment->check_out_date) ? date('d M Y', strtotime($asset_assignment->check_out_date)) : ''?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_check_in_date")?></td>
                <td> <?=isset($asset_assignment->check_in_date) ? date('d M Y', strtotime($asset_assignment->check_in_date)) : '';?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_status")?></td>
                <td> 
                    <?php
                        if($asset_assignment->status == 1) {
                            echo $this->lang->line('asset_assignment_checked_out');
                        } elseif($asset_assignment->status == 2) {
                            echo $this->lang->line('asset_assignment_in_storage');
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_note")?></td>
                <td> <?=$asset_assignment->note?></td>
            </tr>
            <tr>
                <td width="30%"><?=$this->lang->line("asset_assignment_location")?></td>
                <td> <?=$asset_assignment->location?></td>
            </tr>
        </table>
      </div>
    </div>
  </div>
  <?php featurefooter($siteinfos)?>
</body>
</html>