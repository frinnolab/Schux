<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
</head>
<body>
  <div class="profileArea">
    <?php featureheader($siteinfos);?>
    <div class="mainArea">
      <div class="areaBottom">
        <h3><?=$this->lang->line('asset_asset_information')?></h3>
        <table class="table table-bordered">
          <tr>
            <td width="30%"><?=$this->lang->line('asset_serial')?></td>
            <td width="70%"><?=$asset->serial;?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('asset_description')?></td>
            <td width="70%"><?=$asset->adescription;?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('asset_status')?></td>
            <td width="70%">
                <?php
                    if($asset->status == 1) {
                        echo $this->lang->line('asset_status_checked_out');
                    } elseif($asset->status == 2) {
                        echo $this->lang->line('asset_status_checked_in');
                    }
                ?>
            </td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('asset_categoryID')?></td>
            <td width="70%"><?=$asset->category;?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('asset_locationID')?></td>
            <td width="70%"><?=$asset->location;?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('asset_condition')?></td>
            <td width="70%">
                <?php 
                    $arrayCondition = array(
                        0 => $this->lang->line('asset_select_condition'), 
                        1 => $this->lang->line('asset_condition_new'), 
                        2 => $this->lang->line('asset_condition_used')
                    );

                    echo isset($arrayCondition[$asset->asset_condition]) ? $arrayCondition[$asset->asset_condition] : '';
                ?>
            </td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('asset_create_date')?></td>
            <td width="70%"><?=date("d M Y", strtotime($asset->acreate_date));?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <?php featurefooter($siteinfos)?>
</body>
</html>