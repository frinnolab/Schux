<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('classesreport/pdf/'.$classID.'/'.$sectionID);
            echo btn_printReport('classesreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('classesreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('classesreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

    <div class="box">
        <div class="box-header bg-gray">
            <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
            <?=$this->lang->line('classesreport_report_for')?> <?=$this->lang->line('classesreport_class')?> - <?=$class->classes?> ( <?=$sectionName?> )</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <div id="printablediv">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12" style="margin-bottom: 25px;">
                        <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                    </div>

                    <div class="col-sm-6">
                        <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px black solid">
                            <div class="box-header bg-gray with-border">
                                <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_class_info")?></h3>
                                <ol class="breadcrumb">
                                    <li><i class="fa fa-info fa-2x"></i></li>
                                </ol>
                            </div>
                            <div class="box-body">
                                <span class='text-blue'><?=$this->lang->line("classesreport_class_number_of_students")?> : <?=count($students)?></span><br>
                                <span class='text-blue'><?=$this->lang->line("classesreport_class_total_subject_assigned")?> : <?=count($subjects)?></span>
                            </div>
                        </div>

                        <br>

                        <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px green solid">
                            <div class="box-header bg-gray with-border">
                                <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_subject_and_teachers")?></h3>
                                <ol class="breadcrumb">
                                    <li><i class="fa fa-book fa-2x"></i></li>
                                </ol>
                            </div>
                            <div class="box-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <?=$this->lang->line("classesreport_subject")?>
                                            </th>
                                            <th>
                                                <?=$this->lang->line("classesreport_teacher")?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($subjects as $subject ) { ?>
                                        <tr>
                                            <td>
                                                <?=$subject->subject?>
                                            </td>
                                            <td>
                                                <?php
                                                    if(isset($routines[$subject->subjectID]) && count($routines[$subject->subjectID])) {
                                                        foreach ($routines[$subject->subjectID] as $teacherID) {
                                                            if(isset($teachers[$teacherID])) {
                                                                echo "<a class='text-blue'>".$teachers[$teacherID]->name."</a><br/>";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <br>

                        <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px blue solid">
                            <div class="box-header bg-gray with-border">
                                <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_feetype_details")?></h3>
                                <ol class="breadcrumb">
                                    <li><i class="fa fa-pie-chart fa-2x"></i></li>
                                </ol>
                            </div>
                            <div class="box-body">

                                <?php if(count($students)) { ?>
                                <div id="feetype_chart">
                                </div>
                                <?php } else { ?>
                                    <?=$this->lang->line("classesreport_chart_not_found")?>
                                <?php }?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px red solid">
                            <div class="box-header bg-gray with-border">
                                <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_class_teacher")?></h3>
                                <ol class="breadcrumb">
                                    <li><i class="fa icon-teacher fa-2x"></i></li>
                                </ol>
                            </div>
                            <div class="box-body">
                                <?php
                                    if(isset($teachers[$class->teacherID])) {
                                        $teacher = $teachers[$class->teacherID];
                                ?>
                                <section class="panel">
                                  <div class="profile-db-head bg-maroon-light">
                                      <a>
                                        <?=img(imagelink($teacher->photo));?>
                                      </a>
                                    <h1><?=$teacher->name?></h1>
                                  </div>
                                  <table class="table table-hover">
                                      <tbody>
                                          <tr>
                                            <td>
                                              <i class="fa fa-phone text-maroon-light" ></i>
                                            </td>
                                            <td><?=$this->lang->line('classesreport_phone')?></td>
                                            <td><?=$teacher->phone?></td>
                                          </tr>
                                          <tr>
                                              <td>
                                                <i class="fa fa-envelope text-maroon-light"></i>
                                              </td>
                                              <td><?=$this->lang->line('classesreport_email')?></td>
                                            <td><?=$teacher->email?></td>
                                          </tr>
                                          <tr>
                                            <td>
                                              <i class=" fa fa-globe text-maroon-light"></i>
                                            </td>
                                            <td><?=$this->lang->line('classesreport_address')?></td>
                                            <td><?=$teacher->address?></td>
                                          </tr>
                                      </tbody>
                                  </table>
                                </section>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>

                        <br>

                        <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px orange solid">
                            <div class="box-header bg-gray with-border">
                                <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_feetypes_collection")?></h3>
                                <ol class="breadcrumb">
                                    <li><i class="fa fa-dollar fa-2x"></i></li>
                                </ol>
                            </div>
                            <div class="box-body">
                                <?php if(count($feetypes)) {?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <?=$this->lang->line("classesreport_feetype")?>
                                            </th>
                                            <th>
                                                <?=$this->lang->line("classesreport_collection")?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($feetypes as $feetype => $collection ) {

                                        ?>
                                        <tr>
                                            <td>
                                                <?=$feetype?>
                                            </td>
                                            <td>
                                                <?=$collection?>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 text-center footerAll">
                        <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                    </div>
                </div><!-- row -->
            </div><!-- Body -->
        </div>
    </div>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('classesreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('classesreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('classesreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("classesreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("classesreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("classesreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("classesreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">

    $(function () {
        $('#feetype_chart').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:f}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y:f} ',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Amount',
                colorByPoint: true,
                data: [{
                    name: '<?=$this->lang->line("classesreport_collection")?>',
                    y: <?=$collectionAmount?>
                }, {
                    name: '<?=$this->lang->line("classesreport_due")?>',
                    y: <?=$dueAmount?>,
                    sliced: true,
                    selected: true
                }]
            }]
        });
    });

    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('classesreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'     : $('#to').val(), 
            'subject'     : $('#subject').val(), 
            'message'     : $('#message').val(), 
            'classesID'     : <?=$classID;?>, 
            'sectionID'     : <?=$sectionID;?>, 
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('classesreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('classesreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('classesreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('classesreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('classesreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
                        }

                        if(response.message) {
                            toastr["error"](response.message)
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
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });

</script>
