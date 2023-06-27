
<div class="callout callout-info">
    <p>
        <b>Note : </b>Select promotional academic year and class where you want to promote.<br/>
        <b>For Normal : </b> No need to full fill student pass mark in exam. <br/>
        <b>For Advance : </b> You may change each subject pass mark. Also Specifically you can select semester which student must pass within those criteria.
    </p>
</div>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-promotion"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_promotion')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12">

                            <div class="col-sm-12 list-group-item list-group-item-warning">
                                <div class="col-sm-3">
                                    <div class="form-group <?=form_error('schoolyear') ? 'has-error' : ''?>">
                                        <label for="schoolyear" class="control-label">
                                            <?=$this->lang->line('promotion_school_year')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array();
                                            foreach ($schoolyears as $schoolyear) {
                                                $array[$schoolyear->schoolyearID] = $schoolyear->schoolyear;
                                            }

                                            $array[$siteinfos->school_year] = $array[$siteinfos->school_year].' (Default)';
                                            echo form_dropdown("schoolyear", $array, set_value("schoolyear", $schoolyearID), "id='schoolyear' class='form-control select2'");
                                        ?>
                                        <span class="control-label">
                                            <?php echo form_error('schoolyear'); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group <?=form_error('classesID') ? 'has-error' : ''?>">
                                        <label for="classesID" class="control-label">
                                            <?=$this->lang->line("promotion_classes")?> <span class="text-red">*</span>
                                        </label>

                                        <?php
                                            $array = array("0" => $this->lang->line("promotion_select_class"));
                                            foreach ($classes as $classa) {
                                                $array[$classa->classesID] = $classa->classes;
                                            }
                                            echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                        ?>
                                        <span class="control-label">
                                            <?php echo form_error('classesID'); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group <?=form_error('jschoolyear') ? 'has-error' : ''?>">
                                        <label for="jschoolyear" class="control-label">
                                            <?=$this->lang->line('promotion_promotion')?> <?=$this->lang->line('promotion_school_year')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array();
                                            foreach ($schoolyears as $schoolyear) {
                                                if($schoolyear->schoolyearID == $schoolyearID) continue;
                                                $array[$schoolyear->schoolyearID] = $schoolyear->schoolyear;
                                            }

                                            if(isset($array[$siteinfos->school_year])) {
                                                $array[$siteinfos->school_year] = $array[$siteinfos->school_year].' (Default)';
                                            }
                                            echo form_dropdown("jschoolyear", $array, set_value("jschoolyear"), "id='jschoolyear' class='form-control select2'");
                                        ?>
                                        <span class="control-label">
                                            <?php echo form_error('jschoolyear'); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group <?=form_error('jclassesID') ? 'has-error' : ''?>">
                                        <label for="jclassesID" class="control-label">
                                            <?=$this->lang->line('promotion_promotion')?> <?=$this->lang->line("promotion_classes")?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array();
                                            foreach ($classes as $classa) {
                                                if($classa->classesID != $set) {
                                                    $array[$classa->classesID] = $classa->classes;
                                                }
                                            }
                                            echo form_dropdown("jclassesID", $array, set_value("jclassesID"), "id='jclassesID' class='form-control select2'");
                                        ?>
                                        <span class="control-label">
                                            <?php echo form_error('jclassesID'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6 col-sm-offset-3" style="margin-top: 18px;">
                            <div class="list-group-item list-group-item-warning now-check-type">
                                <div class="row">
                                    <div class="col-sm-5 col-sm-offset-2">
                                        <input tabindex="11" type="radio" id="square-radio-1" name="promotionType" value="normal" checked />
                                        <label for="square-radio-1">
                                            <?=$this->lang->line('promotion_type_normal')?>
                                            <i class="fa fa-question-circle fa-stack-1x"></i>
                                        </label>
                                    </div>

                                    <div class="col-sm-5">
                                        <input tabindex="12" type="radio" id="square-radio-2"  name="promotionType" value="advance"/>
                                        <label for="square-radio-2">
                                            <?=$this->lang->line('promotion_type_advance')?>
                                            <i class="fa fa-question-circle fa-stack-1x"></i>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div id="examdiv" class="col-sm-12" style="margin-top: 18px;">
                            <div class="list-group-item list-group-item-warning examhight" >
                                <div class="form-horizontal">

                                    <div class="form-group">
                                        <label for="classesID" class="col-sm-2 control-label">
                                            <?=$this->lang->line('promotion_exam')?>
                                        </label>
                                        <div class="col-sm-10 now-check-exam" style="margin-top:5px">
                                            <?php
                                                $c = 1;
                                                foreach ($exams as $key => $exam) {
                                                    echo "<input class='form-control' tabindex='".$exam->examID."' type='checkbox' id='square-checkbox-$exam->examID' name='exams[$exam->examID]' checked/> ";
                                                    echo '<label for="square-checkbox-'.$exam->examID.'">'.$exam->exam.'</label> &nbsp;&nbsp;';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="markdiv" class="col-sm-12">
                            <div class="col-sm-12 list-group-item list-group-item-warning" style="margin-top: 18px;">
                                    <?php
                                        $bb = 1;
                                        $mark=array();
                                        if(count($subjects)) {
                                            foreach ($subjects as $key => $subject) {
                                    ?>
                                        <div class="col-sm-3">
                                            <div class="<?php echo form_error($subject->subject) ? 'form-group has-error' : 'form-group'; ?>" >
                                                <label for="<?php echo $subject->subjectID; ?>" class="control-label">
                                                    <?=$subject->subject?> <?=$this->lang->line('promotion_pass_mark')?>
                                                </label>
                                                <input type="text" class="form-control" id="<?php echo $subject->subjectID;?>" name="<?php echo "subject[".$subject->subjectID."]";?>" value="<?php echo set_value($subject->subjectID, $subject->passmark)?>" >
                                            </div>
                                        </div>
                                    <?php } } ?>
                            </div>
                        </div>

                            <div class="col-sm-offset-4 col-sm-4 col-xs-12" style="margin-top: 18px;">
                                <input type="submit" class="btn btn-success col-sm-12 col-xs-12" value="<?=$this->lang->line("add_mark_setting")?>" >
                            </div>
                    </div>
                </div>
            </form>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
  var examHight = $('.examhight').height();
  var markPercentageHight = $('.percentageheight').height();

  examHight = parseInt(examHight);
  markPercentageHight = parseInt(markPercentageHight);

  if (examHight > markPercentageHight) {
    $('.percentageheight').css("height", examHight);
    $('.examhight').css("height", examHight);
  } else {
    $('.examhight').css("height", markPercentageHight);
    $('.percentageheight').css("height", markPercentageHight);
  }

  $('.select2').select2();
  $('#classesID').change(function () {
    var classesID = $(this).val();
    var schoolyearID = $('#schoolyear').val();
    if (classesID == 0) {
      $('#hide-table').hide();
    } else {
      $.ajax({
        type : 'POST',
        url : "<?=base_url('promotion/promotion_list')?>",
        data : {"id" : classesID, "year" : schoolyearID},
        dataType : "html",
        success : function (data) {
          window.location.href = data;
        }
      });
    }
  });

  $("#square-radio-1").prop('checked', true);
  $('#examdiv').hide();
  $('#markdiv').hide();
  $('input[name="promotionType"]').on('ifClicked', function (event) {
    var value = $(this).val();
    if (value == 'advance') {
      $('#examdiv').show("slow");
      $('#markdiv').show("slow");
      $('#nonsubbuttoncall').hide("slow");
    } else {
      $('#examdiv').hide("slow");
      $('#markdiv').hide("slow");
      $('#nonsubbuttoncall').show("slow");
    }
  });

  $(document).ready(function () {
    $('.now-check-type input').iCheck({
      checkboxClass : 'icheckbox_square-green',
      radioClass : 'iradio_square-green',
      increaseArea : '20%'
    });
  });

  $(document).ready(function () {
    $('.now-check-exam input, .now-check-percentage input').iCheck({
      checkboxClass : 'icheckbox_square-green',
      radioClass : 'iradio_square-red',
    });
  });
</script>
