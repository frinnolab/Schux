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
            <div class="single_label"><?=$this->lang->line('student_name')?></div>
            <div class="single_value">: <?=$student->srname?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('student_type')?></div>
            <div class="single_value">: <?=count($usertype) ? $usertype->usertype : '';?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('student_registerNO')?></div>
            <div class="single_value">: <?=$student->srregisterNO?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('student_roll')?></div>
            <div class="single_value">: <?=$student->srroll?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('student_classes')?></div>
            <div class="single_value">: <?=count($class) ? $class->classes : ''?></div>
          </div>
          <div class="singleItem">
            <div class="single_label"><?=$this->lang->line('student_section')?></div>
            <div class="single_value">: <?=count($section) ? $section->section : ''?></div>
          </div>
        </div>
      </div>
      <div class="areaBottom">
        <table class="table table-bordered">
          <tr>
            <td width="30%"><?=$this->lang->line('student_studentgroup')?></td>
            <td width="70%"><?=isset($studentgroups[$student->srstudentgroupID]) ? $studentgroups[$student->srstudentgroupID] : '' ?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_optionalsubject')?></td>
            <td width="70%"><?=isset($optionalSubjects[$student->sroptionalsubjectID]) ? $optionalSubjects[$student->sroptionalsubjectID] : '' ?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_dob')?></td>
            <td width="70%"><?php if($student->dob) { echo date("d M Y", strtotime($student->dob)); } ?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_sex')?></td>
            <td width="70%"><?=$student->sex?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_bloodgroup')?></td>
            <td width="70%"><?=$student->bloodgroup?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_religion')?></td>
            <td width="70%"><?=$student->religion?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_email')?></td>
            <td width="70%"><?=$student->email?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_phone')?></td>
            <td width="70%"><?=$student->phone?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_state')?></td>
            <td width="70%"><?=$student->state?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_country')?></td>
            <td width="70%"><?php if(isset($allcountry[$student->country])) { echo $allcountry[$student->country]; } ?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_remarks')?></td>
            <td width="70%"><?=$student->remarks?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_username')?></td>
            <td width="70%"><?=$student->username?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_extracurricularactivities')?></td>
            <td width="70%"><?=$student->extracurricularactivities?></td>
          </tr>
          <tr>
            <td width="30%"><?=$this->lang->line('student_address')?></td>
            <td width="70%"><?=$student->address?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <?php featurefooter($siteinfos);?>
</body>
</html>