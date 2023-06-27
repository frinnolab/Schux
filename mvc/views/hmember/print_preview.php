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
                        <div class="single_label"><?=$this->lang->line('hmember_name')?></div>
                        <div class="single_value">: <?=$student->srname?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('hmember_type')?></div>
                        <div class="single_value">: <?=isset($usertypes[$student->usertypeID]) ? $usertypes[$student->usertypeID] : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('hmember_registerNO')?></div>
                        <div class="single_value">: <?=$student->srregisterNO?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('hmember_roll')?></div>
                        <div class="single_value">: <?=$student->srroll?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('hmember_classes')?></div>
                        <div class="single_value">: <?=count($classes) ? $classes->classes : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('hmember_section')?></div>
                        <div class="single_value">: <?=count($section) ? $section->section : ''?></div>
                    </div>
                </div>
            </div>
            
            <div class="areaBottom">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_dob")?></th>
                            <td width="70%"><?php if($student->dob) { echo date("d M Y", strtotime($student->dob)); } ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_sex")?></th>
                            <td width="70%"><?php  echo $student->sex; ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_email")?></th>
                            <td width="70%"><?php  echo $student->email; ?></td>
                        </tr>

                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_phone")?></th>
                            <td width="70%"><?php  echo $student->phone; ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_hname")?></th>
                            <td width="70%"><?=count($hostel) ? $hostel->name : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_htype")?></th>
                            <td width="70%"><?=count($hostel) ? $hostel->htype : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_class_type")?></th>
                            <td width="70%"><?=count($category) ? $category->class_type : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_tfee")?></th>
                            <td width="70%"><?=count($hmember) ? $hmember->hbalance : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_hostel_address")?></th>
                            <td width="70%"><?=count($hostel) ? $hostel->address : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_joindate")?></th>
                            <td width="70%"><?php if(count($hmember)) { if($hmember->hjoindate) { echo date("d M Y", strtotime($hmember->hjoindate)); } } else { echo 'N/A'; }  ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_religion")?></th>
                            <td width="70%"><?php  echo $student->religion; ?></td>
                        </tr>
                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_bloodgroup")?></th>
                            <td width="70%"><?php if(isset($allbloodgroup[$student->bloodgroup])) { echo $student->bloodgroup; } ?></td>
                        </tr>

                        <tr>
                            <th width="30%"><?=$this->lang->line("hmember_address")?></th>
                            <td width="70%"><?php  echo $student->address; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php featurefooter($siteinfos);?>
</body>
</html>