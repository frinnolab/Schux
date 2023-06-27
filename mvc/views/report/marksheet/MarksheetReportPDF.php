<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
</head>
<body>
	<div class="mainmarksheetreport">
	    <div class="col-sm-12">
	        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
	    </div>
	    <h3> <?=$this->lang->line('marksheetreport_report_for')?> - <?=$this->lang->line('marksheetreport_marksheet');?></h3>
	    <?php if($classesID > 0) { ?>
	    <div class="col-sm-12">
	        <h5 class="pull-left"><?=$this->lang->line('marksheetreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?></h5>                         
	        <h5 class="pull-right"><?=$this->lang->line('marksheetreport_section')?> : <?=isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('marksheetreport_all_section')?></h5>                        
	    </div>
	    <?php } ?>
	    <div class="col-sm-12">
	        <div class="marksheetreult">
	            <?php if(count($studentGrades)) { foreach($studentGrades as $studentRoll => $studentPoint) { ?>
	            <div class="span"><?=$studentRoll?> [<?=$studentPoint?>] , </div>
	            <?php } } else { ?>
					<div class="notfound">
                        <?php echo $this->lang->line('marksheetreport_data_not_found'); ?>
                    </div>
	            <?php } ?>
	        </div>
	    </div>
	    <div class="col-sm-12 text-center report-footer">
	        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
	    </div>
</div>
</body>
</html>