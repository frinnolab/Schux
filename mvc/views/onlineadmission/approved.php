<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-onlineadmission"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("onlineadmission/index/".$admissioninfo->classesID)?>"><?=$this->lang->line('menu_onlineadmission')?></a></li>
            <li class="active"><?=$this->lang->line('onlineadmission_approve')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                    <div class="form-group <?=form_error('schoolyearID') ? ' has-error' : ''  ?>">
                        <label for="schoolyearID" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_schoolyear")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php 
                                $schoolyearArray['0'] = $this->lang->line('onlineadmission_select_schoolyear');
                                if(count($schoolyears)) {
                                    foreach ($schoolyears as $schoolyear) {
                                        $schoolyearArray[$schoolyear->schoolyearID] = $schoolyear->schoolyear;
                                    }
                                }
                                echo form_dropdown("schoolyearID", $schoolyearArray, set_value("schoolyearID", $admissioninfo->schoolyearID), "id='schoolyearID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('schoolyearID'); ?>
                        </span>

                    </div>

                    <div class="form-group <?=form_error('name') ? ' has-error' : ''  ?>">
                        <label for="name_id" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name_id" name="name" value="<?=set_value('name', $admissioninfo->name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('name'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('dob') ? ' has-error' : ''  ?>">
                        <label for="dob" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_dob")?>
                        </label>
                        <div class="col-sm-6">
                            <?php $dob = ''; if($admissioninfo->dob) { $dob = date("d-m-Y", strtotime($admissioninfo->dob)); }  ?>
                            <input type="text" class="form-control" id="dob" name="dob" value="<?=set_value('dob', $dob)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('dob'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('sex') ? ' has-error' : ''  ?>">
                        <label for="sex" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_gender")?>
                        </label>
                        <div class="col-sm-6">
                            <?php 
                                echo form_dropdown("sex", array($this->lang->line('onlineadmission_sex_male') => $this->lang->line('onlineadmission_sex_male'), $this->lang->line('onlineadmission_sex_female') => $this->lang->line('onlineadmission_sex_female')), set_value("sex", $admissioninfo->sex), "id='sex' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('sex'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('bloodgroup') ? ' has-error' : ''  ?>">
                        <label for="bloodgroup" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_bloodgroup")?>
                        </label>
                        <div class="col-sm-6">
                            <?php 
                                $bloodArray = array(
                                    '0' => $this->lang->line('onlineadmission_select_bloodgroup'),
                                    'A+' => 'A+',
                                    'A-' => 'A-',
                                    'B+' => 'B+',
                                    'B-' => 'B-',
                                    'O+' => 'O+',
                                    'O-' => 'O-',
                                    'AB+' => 'AB+',
                                    'AB-' => 'AB-'
                                );
                                echo form_dropdown("bloodgroup", $bloodArray, set_value("bloodgroup", $admissioninfo->bloodgroup), "id='bloodgroup' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('bloodgroup'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('religion') ? ' has-error' : ''  ?>">
                        <label for="religion" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_religion")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="religion" name="religion" value="<?=set_value('religion', $admissioninfo->religion)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('religion'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('email') ? ' has-error' : ''  ?>">
                        <label for="email" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_email")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $admissioninfo->email)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('email'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('phone') ? ' has-error' : ''  ?>">
                        <label for="phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $admissioninfo->phone)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('phone'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('address') ? ' has-error' : ''  ?>">
                        <label for="address" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_address")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="address" name="address" value="<?=set_value('address', $admissioninfo->address)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('address'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('state') ? ' has-error' : ''  ?>">
                        <label for="state" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_state")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="state" name="state" value="<?=set_value('state')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('state'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('country') ? ' has-error' : ''  ?>">
                        <label for="country" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_country")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $country['0'] = $this->lang->line('onlineadmission_select_country');
                                foreach ($allcountry as $allcountryKey => $allcountryit) {
                                    $country[$allcountryKey] = $allcountryit;
                                }
                            ?>
                            <?php 
                                echo form_dropdown("country", $country, set_value("country", $admissioninfo->country), "id='country' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('country'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('classesID') ? ' has-error' : ''  ?>">
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_classes")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                foreach ($classes as $classa) {
                                    if($classa->classesID == $admissioninfo->classesID) {
                                        $classArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $classArray, set_value("classesID", $admissioninfo->classesID), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('classesID'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('sectionID') ? ' has-error' : ''  ?>">
                        <label for="sectionID" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_section")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $sectionArray = array(0 => $this->lang->line("onlineadmission_select_section"));
                            if(count($sections)) {
                                foreach ($sections as $section) {
                                    $sectionArray[$section->sectionID] = $section->section;
                                }
                            }

                            echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('sectionID'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('studentGroupID') ? ' has-error' : ''  ?>">
                        <label for="studentGroupID" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_studentgroup")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $groupArray = array(0 => $this->lang->line("onlineadmission_select_studentgroup"));
                                if(count($studentgroups)) {
                                    foreach ($studentgroups as $studentgroup) {
                                        $groupArray[$studentgroup->studentgroupID] = $studentgroup->group;
                                    }
                                }
                                echo form_dropdown("studentGroupID", $groupArray, set_value("studentGroupID"), "id='studentGroupID' class='form-control select2'");
                            ?>

                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('studentGroupID'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('optionalSubjectID') ? ' has-error' : ''  ?>">
                        <label for="optionalSubjectID" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_optionalsubject")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $optionalSubjectArray = array(0 => $this->lang->line("onlineadmission_select_optionalsubject"));
                            if(count($optionalSubjects)) {
                                foreach ($optionalSubjects as $optionalSubject) {
                                    $optionalSubjectArray[$optionalSubject->subjectID] = $optionalSubject->subject;
                                }
                            }

                            echo form_dropdown("optionalSubjectID", $optionalSubjectArray, set_value("optionalSubjectID"), "id='optionalSubjectID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('optionalSubjectID'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('registerNO') ? ' has-error' : ''  ?>">
                        <label for="registerNO" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_registerNO")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="registerNO" name="registerNO" value="<?=set_value('registerNO')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('registerNO'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('roll') ? ' has-error' : ''  ?>">
                        <label for="roll" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_roll")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="roll" name="roll" value="<?=set_value('roll')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('roll'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('photo') ? ' has-error' : ''  ?>">
                        <label for="photo" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_photo")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('onlineadmission_clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('onlineadmission_file_browse')?></span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <span class="col-sm-4">
                            <?=form_error('photo'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('extraCurricularActivities') ? ' has-error' : ''  ?>">
                        <label for="extraCurricularActivities" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_extracurricularactivities")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="extraCurricularActivities" name="extraCurricularActivities" value="<?=set_value('extraCurricularActivities')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('extraCurricularActivities'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('remarks') ? ' has-error' : ''  ?>">
                        <label for="remarks" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_remarks")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="remarks" name="remarks" value="<?=set_value('remarks')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('remarks'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('username') ? ' has-error' : ''  ?>">
                        <label for="username" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_username")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="username" name="username" value="<?=set_value('username')?>" >
                        </div>
                         <span class="col-sm-4 control-label">
                            <?=form_error('username'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('password') ? ' has-error' : ''  ?>">
                        <label for="password" class="col-sm-2 control-label">
                            <?=$this->lang->line("onlineadmission_password")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="password" name="password" value="<?=set_value('password')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('password'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("onlineadmission_approved")?>" >
                        </div>
                    </div>
                </form>
                <?php if ($siteinfos->note==1) { ?>
                    <div class="callout callout-danger">
                        <p><b>Note:</b> Create teacher, class, section before then transfer a student.</p>
                    </div>
                <?php } ?>
            </div><!-- col-sm-8 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    $( ".select2" ).select2();
    $('#dob').datepicker({ startView: 2 });

    $('#username').keyup(function() {
        $(this).val($(this).val().replace(/\s/g, ''));
    });

    $('#classesID').change(function(event) {
        var classesID = $(this).val();
        if(classesID === '0') {
            $('#sectionID').val(0);
        } else {
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?=base_url('student/sectioncall')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('student/optionalsubjectcall')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data2) {
                    $('#optionalSubjectID').html(data2);
                }
            });
        }
    });

    $(document).on('click', '#close-preview', function(){ 
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
               $('.image-preview').popover('show');
               $('.content').css('padding-bottom', '130px');
            }, 
             function () {
               $('.image-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );    
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('onlineadmission_file_browse')?>");
        }); 
        // Create the preview image
        $(".image-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('onlineadmission_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '130px');
            }        
            reader.readAsDataURL(file);
        });  
    });
</script>
