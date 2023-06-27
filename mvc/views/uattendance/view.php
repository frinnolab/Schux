<?php
    $monthArray = array(
      "01" => "jan",
      "02" => "feb",
      "03" => "mar",
      "04" => "apr",
      "05" => "may",
      "06" => "jun",
      "07" => "jul",
      "08" => "aug",
      "09" => "sep",
      "10" => "oct",
      "11" => "nov",
      "12" => "dec"
    );
?>
<?php if(count($user)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?php if(!permissionChecker('uattendance_view') && permissionChecker('uattendance_add')) { echo btn_sm_add('uattendance/add', $this->lang->line('uattendance_add')); } ?>

                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('uattendance/print_preview/'.$user->userID, $this->lang->line('pdf_preview')) 
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("uattendance/index")?>"><?=$this->lang->line('menu_uattendance')?></a></li>
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
                        <?=profileviewimage($user->photo)?>
                        <h3 class="profile-username text-center"><?=$user->name?></h3>
                        <p class="text-muted text-center"><?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : ''?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('uattendance_sex')?></b> <a class="pull-right"><?=$user->sex?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('uattendance_dob')?></b> <a class="pull-right"><?=date('d M Y',strtotime($user->dob))?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('uattendance_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#attendance" data-toggle="tab"><?=$this->lang->line('uattendance_attendance')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="attendance">
                            <div class="userDIV">
                                <table class="attendance_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <?php
                                                for($i=1; $i<=31; $i++) {
                                                   echo  "<th>".$this->lang->line('uattendance_'.$i)."</th>";
                                                }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $holidayCount = 0;
                                            $weekendayCount = 0;
                                            $leavedayCount = 0;
                                            $presentCount = 0;
                                            $lateexcuseCount = 0;
                                            $lateCount = 0;
                                            $absentCount = 0;

                                            $schoolyearstartingdate = $schoolyearsessionobj->startingdate;
                                            $schoolyearendingdate = $schoolyearsessionobj->endingdate;
                                            $allMonths = get_month_and_year_using_two_date($schoolyearstartingdate, $schoolyearendingdate);
                                            $holidaysArray = explode('","', $holidays);

                                            foreach($allMonths as $yearKey => $months) {
                                                foreach ($months as $month) {
                                                    $monthyear = $month."-".$yearKey;
                                                    if(isset($attendancesArray[$monthyear])) {
                                                        echo "<tr>";
                                                        echo "<td>".ucwords($monthArray[$month])."</td>";
                                                        for ($i=1; $i <= 31; $i++) {    
                                                            $acolumnname = 'a'.$i;
                                                            $d = sprintf('%02d',$i);

                                                            $date = $d."-".$month."-".$yearKey;
                                                            if(in_array($date, $holidaysArray)) {
                                                                $holidayCount++;
                                                                echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                            } elseif (in_array($date, $getWeekendDays)) {
                                                                $weekendayCount++;
                                                                echo "<td class='ini-bg-info'>".'W'."</td>";
                                                            } elseif(in_array($date, $leaveapplications)) {
                                                                $leavedayCount++;
                                                                echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                            } else {
                                                                $textcolorclass = '';
                                                                $val = false;
                                                                if(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'P') {
                                                                    $presentCount++;
                                                                    $textcolorclass = 'ini-bg-success';
                                                                } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'LE') {
                                                                    $lateexcuseCount++;
                                                                    $textcolorclass = 'ini-bg-success';
                                                                } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'L') {
                                                                    $lateCount++;
                                                                    $textcolorclass = 'ini-bg-success';
                                                                } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'A') {
                                                                    $absentCount++;
                                                                    $textcolorclass = 'ini-bg-danger';
                                                                } elseif((isset($attendancesArray[$monthyear]) && ($attendancesArray[$monthyear]->$acolumnname == NULL || $attendancesArray[$monthyear]->$acolumnname == ''))) {
                                                                    $textcolorclass = 'ini-bg-secondary';
                                                                    $defaultVal = 'N/A';
                                                                    $val = true;
                                                                }

                                                                if($val) {
                                                                    echo "<td class='".$textcolorclass."'>".$defaultVal."</td>";
                                                                } else {
                                                                    echo "<td class='".$textcolorclass."'>".$attendancesArray[$monthyear]->$acolumnname."</td>";
                                                                }

                                                            }
                                                        }
                                                        echo "</tr>";
                                                    } else {
                                                        $monthyear = $month."-".$yearKey;
                                                        echo "<tr>";
                                                        echo "<td>".ucwords($monthArray[$month])."</td>";
                                                        for ($i=1; $i <= 31; $i++) {    
                                                            $acolumnname = 'a'.$i;
                                                            $d = sprintf('%02d',$i);

                                                            $date = $d."-".$month."-".$yearKey;
                                                            if(in_array($date, $holidaysArray)) {
                                                                $holidayCount++;
                                                                echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                            } elseif (in_array($date, $getWeekendDays)) {
                                                                $weekendayCount++;
                                                                echo "<td class='ini-bg-info'>".'W'."</td>";
                                                            } elseif(in_array($date, $leaveapplications)) {
                                                                $leavedayCount++;
                                                                echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                            } else {
                                                                $textcolorclass = 'ini-bg-secondary';
                                                                echo "<td class='".$textcolorclass."'>".'N/A'."</td>";
                                                            }
                                                        }
                                                        echo "</tr>";
                                                    }
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <p class="totalattendanceCount">
                                    <?=$this->lang->line('uattendance_total_holiday')?>:<?=$holidayCount?>, 
                                    <?=$this->lang->line('uattendance_total_weekenday')?>:<?=$weekendayCount?>, 
                                    <?=$this->lang->line('uattendance_total_leaveday')?>:<?=$leavedayCount?>, 
                                    <?=$this->lang->line('uattendance_total_present')?>:<?=$presentCount?>, 
                                    <?=$this->lang->line('uattendance_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                    <?=$this->lang->line('uattendance_total_late')?>:<?=$lateCount?>, 
                                    <?=$this->lang->line('uattendance_total_absent')?>:<?=$absentCount?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- email modal starts here -->
    <form class="form-horizontal" role="form" action="<?=base_url('user/send_mail');?>" method="post">
        <div class="modal fade" id="mail">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
                </div>
                <div class="modal-body">
                
                    <?php 
                        if(form_error('to')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="to" class="col-sm-2 control-label">
                            <?=$this->lang->line("to")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="to_error">
                        </span>
                    </div>

                    <?php 
                        if(form_error('subject')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="subject_error">
                        </span>

                    </div>

                    <?php 
                        if(form_error('message')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
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
    <!-- email end here -->    

    <script language="javascript" type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
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
            var to = $('#to').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var id = "<?=$user->userID;?>";
            var error = 0;

            $("#to_error").html("");
            if(to == "" || to == null) {
                error++;
                $("#to_error").html("");
                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
            } else {
                if(check_email(to) == false) {
                    $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
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
                    url: "<?=base_url('uattendance/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message,
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

        $('.userDIV').mCustomScrollbar({
            axis:"x"
        });

    </script>
<?php } ?>

