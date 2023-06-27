<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php if (count($librarycards)) { ?>
    <div class="librarycardreport">
        <?php foreach($librarycards as $librarycard) { 
        if($type == 1) { ?>
            <div class="librarycardreport-frontend" style="height:290px;<?=($background == 1) ? "background-image:url(".base_url('uploads/default/idcard-border.png').") !important" : '' ?>">
                <h3><?=$siteinfos->sname?></h3>
                <div class="librarycardreport-image">
                    <img src='<?=imagelink($librarycard->photo)?>' alt="">
                </div>
                <div class="librarycardreport-info">
                    <div class="librarycardreport-item">
                        <div class="left"><b><?=$this->lang->line('librarycardreport_name')?></b></div>
                        <div class="right">: <?=$librarycard->srname?></div>
                    </div>
                    <div class="librarycardreport-item">
                        <div class="left"><b><?=$this->lang->line('librarycardreport_libraryID')?></b></div>
                        <div class="right">: <?=$librarycard->lID?></div>
                    </div>
                    <div class="librarycardreport-item">
                        <div class="left"><b><?=$this->lang->line('librarycardreport_class')?></b></div>
                        <div class="right">: <?=isset($classes[$librarycard->srclassesID]) ? $classes[$librarycard->srclassesID] : ''?></div>
                    </div>
                    <div class="librarycardreport-item">
                        <div class="left"><b><?=$this->lang->line('librarycardreport_section')?></b></div>
                        <div class="right">: <?=isset($sections[$librarycard->srsectionID]) ? $sections[$librarycard->srsectionID] : ''?></div>
                    </div>
                    <div class="librarycardreport-item">
                        <div class="left"><b><?=$this->lang->line('librarycardreport_roll')?></b></div>
                        <div class="right">: <?=$librarycard->srroll?></div>
                    </div>
                    <div class="librarycardreport-item">
                        <div class="left"><b><?=$this->lang->line('librarycardreport_joining_date')?></b></div>
                        <div class="right">: <?=date('d M Y',strtotime($librarycard->ljoindate))?></div>
                    </div>
                </div>
            </div>
        <?php } elseif($type = 2) { 
                
                $filename = $librarycard->lID;
                $text = $this->lang->line('librarycardreport_id')." : ".$librarycard->lID;

                $filepath = FCPATH.'uploads/libraryQRcode/'.$filename.'.png';
                if(!file_exists($filepath)) {
                    generate_qrcode($text,$filename,'libraryQRcode');
                }
            ?>
            <div class="librarycardreport-backend" style="height:290px;<?=($background == 1) ? "background-image:url(".base_url('uploads/default/idcard-border.png').") !important" : '' ?>">
                <h3><?=$this->lang->line('librarycardreport_valid_up')?> <?=date('F Y',strtotime($schoolyear->endingdate))?></h3>
                <h4><?=$this->lang->line('librarycardreport_please_return')?> : </h4>
                <p><?=$siteinfos->sname?></p>
                <div class="librarycardreport-schooladdress">
                    <?=$siteinfos->address?>
                </div>
                <div class="librarycardreport-bottom">
                    <div class="librarycardreport-qrcode">
                        <img style="width:90px;height: 80px; padding-top: 12px" src="<?=base_url('uploads/libraryQRcode/'.$filename.'.png')?>" alt="">
                    </div>
                    <div class="librarycardreport-session">
                        <span><?=$this->lang->line('librarycardreport_session')?>: <?=$schoolyear->schoolyear?></span>
                    </div>
                </div>
            </div>
        <?php } } ?>
    </div>
<?php } ?>
</body>
</html>