<?php if(count($profile)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                    echo btn_add_pdf('promotion/print_preview/'.$profile->studentID."/".$profile->classesID.'/'.$passschoolyearID, $this->lang->line('pdf_preview'))
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("promotion/index")?>"><?=$this->lang->line('menu_promotion')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>
        </div>
    </div>

    <div id="printablediv">
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?=profileviewimage($profile->photo)?>
                        <h3 class="profile-username text-center"><?=$profile->name?></h3>
                        <p class="text-muted text-center"><?=$usertype->usertype?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('mark_registerNO')?></b> <a class="pull-right"><?=$profile->srregisterNO?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('mark_roll')?></b> <a class="pull-right"><?=$profile->srroll?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('mark_classes')?></b> <a class="pull-right"><?=count($class) ? $class->classes : ''?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('mark_section')?></b> <a class="pull-right"><?=count($section) ? $section->section : ''?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#mark" data-toggle="tab"><?=$this->lang->line('promotion_mark_summary')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="mark">
                            <?php
                                if(isset($studentStatus['exams']) && count($studentStatus)) {
                                    echo "<h4>".$this->lang->line('promotion_subject_status')."</h4><br>";

                                    foreach ($studentStatus['exams'] as $examID => $subject) {
                                        echo '<div style="border-top:1px solid #23292F; border-left:1px solid #23292F; border-right:1px solid #23292F; border-bottom:1px solid #23292F;" class="box" id="e'.$exams[$examID].'">';
                                            echo '<div class="box-header" style="background-color:#FFFFFF;">';
                                                echo '<h3 class="box-title" style="color:#23292F;">';
                                                    echo $exams[$examID];
                                                echo '</h3>';
                                            echo '</div>';
                                            echo '<div class="box-body scrollDiv" style="border-top:1px solid #23292F;">';
                                                echo "<table class=\"table table-striped table-bordered\">";
                                                    echo "<thead>";
                                                        echo "<tr>";
                                                            echo "<th>".$this->lang->line('promotion_subject')."</th>";
                                                            echo "<th>".$this->lang->line('promotion_pass_mark')."</th>";
                                                            echo "<th>".$this->lang->line('promotion_have_mark')."</th>";
                                                            echo "<th>".$this->lang->line('promotion_diff_mark')."</th>";
                                                        echo "</tr>";
                                                    echo "</thead>";
                                                    echo "<tbody>";
                                                        foreach ($subject as $key => $value) {
                                                            echo "<tr>";
                                                                echo "<td>".$value['subject']."</td>";
                                                                echo "<td>".$value['passmark']."</td>";
                                                                echo "<td>".$value['havemark']."</td>";
                                                                echo "<td>".abs($value['havemark']-$value['passmark'])."</td>";
                                                            echo "</tr>";
                                                        }
                                                    echo "</tbody>";
                                                echo "</table>";
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                    echo "<br>";
                                }
                            ?>

                            <h4><?=$this->lang->line('promotion_mark_status')?></h4>
                            <br>
                            <?php
                                $optionalsubjectID = $profile->sroptionalsubjectID;

                                if(count($marksettings)) {
                                    foreach ($marksettings as $examID => $marksetting) {
                                        if(in_array($examID, $promotionExams)) {
                                            echo '<div style="border-top:1px solid #23292F; border-left:1px solid #23292F; border-right:1px solid #23292F; border-bottom:1px solid #23292F;" class="box" id="e' . $examID . '">';
                                                echo '<div class="box-header" style="background-color:#FFFFFF;">';
                                                    echo '<h3 class="box-title" style="color:#23292F;">';
                                                        echo( isset($exams[ $examID ]) ? $exams[ $examID ] : '' );
                                                    echo '</h3>';
                                                echo '</div>';

                                                echo '<div class="box-body mark-bodyID" style="border-top:1px solid #23292F;">';
                                                    echo "<table class=\"table table-striped table-bordered\" >";
                                                        echo "<thead>";
                                                            echo "<tr>";
                                                                echo "<th class='text-center' rowspan='2' style='background-color:#395C7F;color:#fff;' data-title='".$this->lang->line("mark_subject")."'>";
                                                                    echo $this->lang->line("mark_subject");
                                                                echo "</th>";

                                                                foreach ( $marksetting as $subjectID => $markpercentageArr ) {
                                                                    foreach ( $markpercentageArr[ ( ( $settingmarktypeID == 4 ) || ( $settingmarktypeID == 6 ) ) ? 'unique' : 'own' ] as $markpercentageID ) {
                                                                        $markpercentagetypelabel =  isset($markpercentages[ $markpercentageID ]) ? $markpercentages[ $markpercentageID ]->markpercentagetype : '';
                                                                        echo "<th colspan='2' class=' text-center' style='background-color:#395C7F;color:#fff;' data-title='".$markpercentagetypelabel."'>";
                                                                            echo $markpercentagetypelabel;
                                                                        echo "</th>";
                                                                    }
                                                                    break;
                                                                }
                                                                echo "<th colspan='3' class='text-center' style='background-color:#395C7F;color:#fff;' data-title='".$this->lang->line("mark_total")."'>";
                                                                    echo $this->lang->line("mark_total");
                                                                echo "</th>";
                                                            echo "</tr>";
                                                            foreach ( $marksetting as $subjectID => $markpercentageArr ) {
                                                                echo "<tr>";
                                                                    foreach ( $markpercentageArr[ ( ( $settingmarktypeID == 4 ) || ( $settingmarktypeID == 6 ) ) ? 'unique' : 'own' ] as $markpercentageID ) {
                                                                        echo "<th class='text-center' data-title='".$this->lang->line('mark_obtained_mark')."'>";
                                                                            echo $this->lang->line("mark_obtained_mark");
                                                                        echo "</th>";

                                                                        echo "<th class='text-center' data-title='".$this->lang->line('mark_highest_mark')."'>";
                                                                            echo $this->lang->line("mark_highest_mark");
                                                                        echo "</th>";
                                                                    }
                                                                    echo "<th class='text-center' data-title='".$this->lang->line('mark_mark')."'>";
                                                                        echo $this->lang->line("mark_mark");
                                                                    echo "</th>";
                                                                    echo "<th class='text-center' data-title='".$this->lang->line('mark_point')."'>";
                                                                        echo $this->lang->line("mark_point");
                                                                    echo "</th>";
                                                                    echo "<th class='text-center' data-title='".$this->lang->line('mark_grade')."'>";
                                                                        echo $this->lang->line("mark_grade");
                                                                    echo "</th>";
                                                                echo "</tr>";
                                                                break;
                                                            }
                                                        echo "</thead>";
                                                        echo "<tbody>";
                                                            $totalMark           = 0;
                                                            $totalFinalMark      = 0;
                                                            $totalSubject        = 0;
                                                            $averagePoint        = 0;
                                                            $opmarkpercentageArr = [];
                                                            foreach ( $marksetting as $subjectID => $markpercentageArr ) {
                                                                if ( $subjectID == $optionalsubjectID ) {
                                                                    $opmarkpercentageArr = $markpercentageArr;
                                                                }
                                                                if ( !in_array($subjectID, $optionalsubjectArr) ) {
                                                                    $totalSubject++;
                                                                    echo "<tr>";
                                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_subject')."'>";
                                                                            echo isset($subjects[ $subjectID ]) ? $subjects[ $subjectID ]->subject : '';
                                                                        echo "</td>";

                                                                        $subjectfinalmark = isset($subjects[ $subjectID ]) ? (int) $subjects[ $subjectID ]->finalmark : 0;
                                                                        $totalSubjectMark = 0;
                                                                        $percentageMark   = 0;
                                                                        foreach ( $markpercentageArr[ ( ( $settingmarktypeID == 4 ) || ( $settingmarktypeID == 6 ) ) ? 'unique' : 'own' ] as $markpercentageID ) {
                                                                            $f = false;
                                                                            if ( isset($markpercentageArr['own']) && in_array($markpercentageID,
                                                                                    $markpercentageArr['own']) ) {
                                                                                $f              = true;
                                                                                $percentageMark += ( isset($markpercentages[ $markpercentageID ]) ? $markpercentages[ $markpercentageID ]->percentage : 0 );
                                                                            }

                                                                            echo "<td class='text-black' data-title='".$this->lang->line('mark_mark')."'>";
                                                                                if ( isset($marks[ $examID ][ $subjectID ][ $markpercentageID ]) && $f ) {
                                                                                    echo $marks[ $examID ][ $subjectID ][ $markpercentageID ];
                                                                                    $totalSubjectMark += $marks[ $examID ][ $subjectID ][ $markpercentageID ];
                                                                                } else {
                                                                                    if ( $f ) {
                                                                                        echo 'N/A';
                                                                                    }
                                                                                }
                                                                            echo "</td>";

                                                                            echo "<td class='text-black' data-title='".$this->lang->line('mark_highest_mark')."'>";
                                                                                if ( isset($highestmarks[ $examID ][ $subjectID ][ $markpercentageID ]) && ( $highestmarks[ $examID ][ $subjectID ][ $markpercentageID ] != -1 ) && $f ) {
                                                                                    echo $highestmarks[ $examID ][ $subjectID ][ $markpercentageID ];
                                                                                } else {
                                                                                    if ( $f ) {
                                                                                        echo 'N/A';
                                                                                    }
                                                                                }
                                                                            echo "</td>";
                                                                        }
                                                                        $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);

                                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_mark')."'>";
                                                                            echo $totalSubjectMark;
                                                                            $totalMark        += $totalSubjectMark;
                                                                            $totalFinalMark   += $finalpercentageMark;
                                                                            $totalSubjectMark = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                                                        echo "</td>";

                                                                        if ( count($grades) ) {
                                                                            foreach ( $grades as $grade ) {
                                                                                if ( ( $grade->gradefrom <= $totalSubjectMark ) && ( $grade->gradeupto >= $totalSubjectMark ) ) {
                                                                                    echo "<td class='text-black' data-title='".$this->lang->line('mark_point')."'>";
                                                                                        echo $grade->point;
                                                                                        $averagePoint += $grade->point;
                                                                                    echo "</td>";
                                                                                    echo "<td class='text-black' data-title='".$this->lang->line('mark_grade')."'>";
                                                                                        echo $grade->grade;
                                                                                    echo "</td>";
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo "<td class='text-black' data-title='".$this->lang->line('mark_point')."'>";
                                                                                echo 'N/A';
                                                                            echo '</td>';
                                                                            echo "<td class='text-black' data-title='".$this->lang->line('mark_grade')."'>";
                                                                                echo 'N/A';
                                                                            echo '</td>';
                                                                        }
                                                                    echo "</tr>";
                                                                }
                                                            }

                                                            if ( ( $optionalsubjectID > 0 ) && count($opmarkpercentageArr) ) {
                                                                $totalSubject++;
                                                                echo "<tr>";
                                                                    echo "<td class='text-black' data-title='".$this->lang->line('mark_subject')."'>";
                                                                        echo isset($subjects[ $optionalsubjectID ]) ? $subjects[ $optionalsubjectID ]->subject : '';
                                                                    echo "</td>";
                                                                    $subjectfinalmark = isset($subjects[ $optionalsubjectID ]) ? $subjects[ $optionalsubjectID ]->finalmark : 0;

                                                                    $totalSubjectMark = 0;
                                                                    $percentageMark   = 0;
                                                                    foreach ( $opmarkpercentageArr[ ( ( $settingmarktypeID == 4 ) || ( $settingmarktypeID == 6 ) ) ? 'unique' : 'own' ] as $markpercentageID ) {
                                                                        $f = false;
                                                                        if ( isset($opmarkpercentageArr['own']) && in_array($markpercentageID, $opmarkpercentageArr['own']) ) {
                                                                            $f              = true;
                                                                            $percentageMark += ( isset($markpercentages[ $markpercentageID ]) ? $markpercentages[ $markpercentageID ]->percentage : 0 );
                                                                        }

                                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_mark')."'>";
                                                                            if ( isset($marks[ $examID ][ $optionalsubjectID ][ $markpercentageID ]) && $f ) {
                                                                                echo $marks[ $examID ][ $optionalsubjectID ][ $markpercentageID ];
                                                                                $totalSubjectMark += $marks[ $examID ][ $optionalsubjectID ][ $markpercentageID ];
                                                                            } else {
                                                                                if ( $f ) {
                                                                                    echo 'N/A';
                                                                                }
                                                                            }
                                                                        echo "</td>";

                                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_highest_mark')."'>";
                                                                            if ( isset($highestmarks[ $examID ][ $optionalsubjectID ][ $markpercentageID ]) && ( $highestmarks[ $examID ][ $optionalsubjectID ][ $markpercentageID ] != -1 ) && $f ) {
                                                                                echo $highestmarks[ $examID ][ $optionalsubjectID ][ $markpercentageID ];
                                                                            } else {
                                                                                if ( $f ) {
                                                                                    echo 'N/A';
                                                                                }
                                                                            }
                                                                        echo "</td>";
                                                                    }
                                                                    $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);

                                                                    echo "<td class='text-black' data-title='".$this->lang->line('mark_mark')."'>";
                                                                        echo $totalSubjectMark;
                                                                        $totalMark      += $totalSubjectMark;
                                                                        $totalFinalMark += $finalpercentageMark;

                                                                        $totalSubjectMark = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                                                    echo "</td>";

                                                                    if ( count($grades) ) {
                                                                        foreach ( $grades as $grade ) {
                                                                            if ( ( $grade->gradefrom <= $totalSubjectMark ) && ( $grade->gradeupto >= $totalSubjectMark ) ) {
                                                                                echo "<td class='text-black' data-title='".$this->lang->line('mark_point')."'>";
                                                                                    echo $grade->point;
                                                                                    $averagePoint += $grade->point;
                                                                                echo "</td>";
                                                                                    echo "<td class='text-black' data-title='".$this->lang->line('mark_grade')."'>";
                                                                                    echo $grade->grade;
                                                                                echo "</td>";
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_point')."'>";
                                                                            echo 'N/A';
                                                                        echo '</td>';
                                                                        echo "<td class='text-black' data-title='".$this->lang->line('mark_grade')."'>";
                                                                            echo 'N/A';
                                                                        echo '</td>';
                                                                    }
                                                                echo "</tr>";
                                                            }
                                                        echo "</tbody>";
                                                    echo "</table>";

                                                    echo '<div class="box-footer" style="padding-left:0px;">';
                                                        echo '<p class="text-black">' . $this->lang->line('mark_total_marks') . ' : <span class="text-red text-bold">' . ini_round($totalFinalMark) . '</span>';
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->lang->line('mark_total_obtained_marks') . ' : <span class="text-red text-bold">' . ini_round($totalMark) . '</span>';
                                                            $totalAverageMark = $totalMark / $totalSubject;
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->lang->line('mark_total_average_marks') . ' : <span class="text-red text-bold">' . ini_round($totalAverageMark) . '</span>';

                                                            $totalmarkpercentage = markCalculationView($totalMark, $totalFinalMark);
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->lang->line('mark_total_average_marks_percetage') . ' : <span class="text-red text-bold">' . ini_round($totalmarkpercentage) . '</span>';

                                                            $gpaAveragePoint = $averagePoint / $totalSubject;
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->lang->line('mark_gpa') . ' : <span class="text-red text-bold">' . ini_round($gpaAveragePoint) . '</span>';
                                                        echo '</p>';
                                                    echo '</div>';
                                                echo '</div>';
                                            echo "</div>";
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form class="form-horizontal" role="form" action="<?=base_url('student/send_mail');?>" method="post">
        <div class="modal fade" id="mail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="to" class="col-sm-2 control-label">
                                <?=$this->lang->line("to")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                            </div>
                            <span class="col-sm-4 control-label" id="to_error">
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="col-sm-2 control-label">
                                <?=$this->lang->line("subject")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                            </div>
                            <span class="col-sm-4 control-label" id="subject_error">
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-2 control-label">
                                <?=$this->lang->line("message")?>
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                        <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script language="javascript" type="text/javascript">
      function printDiv(divID) {
        var divElements = document.getElementById(divID).innerHTML;
        var oldPage     = document.body.innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
      }

      function closeWindow() {
        location.reload();
      }

      function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
          $("#to_error").html('');
          $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
          status = true;
        }
        return status;
      }

      $('#send_pdf').click(function() {
        var to        = $('#to').val();
        var subject   = $('#subject').val();
        var message   = $('#message').val();
        var studentID = "<?=$profile->studentID;?>";
        var classesID = "<?=$profile->srclassesID;?>";
        var schoolyearID = "<?=$passschoolyearID;?>";
        var error     = 0;

        $("#to_error").html("");
        if(to == "" || to == null) {
          error++;
          $("#to_error").html("");
          $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
          if(check_email(to) == false) {
            error++
          }
        }

        if(subject == "" || subject == null) {
          error++;
          $("#subject_error").html("");
          $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
          $("#subject_error").html("");
        }

        if(error == 0) {
          $('#send_pdf').attr('disabled','disabled');
          $.ajax({
            type: 'POST',
            url: "<?=base_url('promotion/send_mail')?>",
            data: 'to='+ to + '&subject=' + subject + "&message=" + message + "&studentID=" + studentID+ "&classesID=" + classesID + "&schoolyearID=" + schoolyearID,
            dataType: "html",
            success: function(data) {
              var response = JSON.parse(data);
              if (response.status == false) {
                $('#send_pdf').removeAttr('disabled');
                $.each(response, function(index, value) {
                  if(index != 'status') {
                    toastr["error"](value)
                    toastr.options = {
                      "closeButton": true,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": false,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "500",
                      "hideDuration": "500",
                      "timeOut": "5000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                    }
                  }
                });
              } else {
                location.reload();
              }
            }
          });
        }
      });

      $('.mark-bodyID').mCustomScrollbar({
        axis:"x"
      });

    </script>
<?php } ?>
