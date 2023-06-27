<?php if(count($profile)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?php if(!permissionChecker('parents_view') && permissionChecker('parents_add')) { echo btn_sm_add('parents/add', $this->lang->line('add_parents')); } ?>

                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php echo btn_add_pdf('parents/print_preview/'.$profile->parentsID, $this->lang->line('pdf_preview')); ?>

                <?php if(permissionChecker('parents_edit')) { echo btn_sm_edit('parents/edit/'.$profile->parentsID, $this->lang->line('edit')); } 
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("parents/index")?>"><?=$this->lang->line('menu_parents')?></a></li>
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

                        <p class="text-muted text-center"><?=isset($usertypes[$profile->usertypeID]) ? $usertypes[$profile->usertypeID] : '' ?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('parents_phone')?></b> <a class="pull-right"><?=$profile->phone?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('parents_profile')?></a></li>
                        <?php if((permissionChecker('parents_add') && permissionChecker('parents_delete')) || ($this->session->userdata('usertypeID') == $profile->usertypeID && $this->session->userdata('loginuserID') == $profile->parentsID)) {  ?>
                            <li><a href="#children" data-toggle="tab"><?=$this->lang->line('parents_children')?></a></li>
                            <li><a href="#document" data-toggle="tab"><?=$this->lang->line('parents_document')?></a></li>
                        <?php } ?>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            <div class="panel-body profile-view-dis">
                                <div class="row">
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("parents_father_name")?> </span>: <?=$profile->father_name?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("parents_father_profession")?> </span>: <?=$profile->father_profession?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("parents_mother_name")?> </span>: <?=$profile->mother_name?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("parents_mother_profession")?> </span>: <?=$profile->mother_profession?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("father_phone")?> </span>: <?=$profile->father_phone?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("father_alternate_phone")?> </span>: <?=$profile->father_alternate_phone?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("mother_phone")?> </span>: <?=$profile->mother_phone?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("mother_alternate_phone")?> </span>: <?=$profile->mother_alternate_phone?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("father_country")?> </span>: <?=$allcountry[$profile->father_country]?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("mother_country")?> </span>: <?=$allcountry[$profile->mother_country]?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("parents_email")?> </span>: <?=$profile->email?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("parents_address")?> </span>: <?=$profile->address?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("parents_username")?> </span>: <?=$profile->username?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if((permissionChecker('parents_add') && permissionChecker('parents_delete')) || ($this->session->userdata('usertypeID') == $profile->usertypeID && $this->session->userdata('loginuserID') == $profile->parentsID)) {  ?>
                            <div class="tab-pane" id="children">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('parents_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('parents_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('parents_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('parents_classes')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('parents_section')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($childrens)) {$i = 1; foreach($childrens as $children) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?=$i?>  
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('parents_photo')?>">
                                                        <?=profileimage($children->photo)?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('parents_name')?>">
                                                        <?=$children->srname?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('parents_roll')?>">
                                                        <?=$children->srroll?>
                                                    </td> 
                                                    <td data-title="<?=$this->lang->line('parents_classes')?>">
                                                        <?=isset($classes[$children->srclassesID]) ? $classes[$children->srclassesID] : ''?>
                                                    </td> 
                                                    <td data-title="<?=$this->lang->line('parents_section')?>">
                                                        <?=isset($sections[$children->srsectionID]) ? $sections[$children->srsectionID] : ''?>
                                                    </td>
                                                </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="document">
                                <?php if(permissionChecker('parents_add')) { ?>
                                    <input class="btn btn-success btn-sm" style="margin-bottom: 10px" type="button" value="<?=$this->lang->line('parents_add_document')?>" data-toggle="modal" data-target="#documentupload">
                                <?php } ?>
                                    <div id="hide-table">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?=$this->lang->line('slno')?></th>
                                                    <th><?=$this->lang->line('parents_title')?></th>
                                                    <th><?=$this->lang->line('parents_date')?></th>
                                                    <th><?=$this->lang->line('action')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(count($documents)) { $i = 1; foreach ($documents as $document) {  ?>
                                                    <tr>
                                                        <td data-title="<?=$this->lang->line('slno')?>">
                                                            <?php echo $i; ?>
                                                        </td>

                                                        <td data-title="<?=$this->lang->line('parents_title')?>">
                                                            <?=$document->title?>
                                                        </td>

                                                        <td data-title="<?=$this->lang->line('parents_date')?>">
                                                            <?=date('d M Y', strtotime($document->create_date))?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('action')?>">
                                                            <?php
                                                                if((permissionChecker('parents_add') && permissionChecker('parents_delete')) || ($this->session->userdata('usertypeID') == 4 && $this->session->userdata('loginuserID') == $profile->parentsID)) {
                                                                    echo btn_download('parents/download_document/'.$document->documentID.'/'.$profile->parentsID, $this->lang->line('download'));
                                                                }

                                                                if(permissionChecker('parents_add') && permissionChecker('parents_delete')) {
                                                                    echo btn_delete_show('parents/delete_document/'.$document->documentID.'/'.$profile->parentsID, $this->lang->line('delete'));
                                                                } 
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php $i++; } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(permissionChecker('parents_add')) { ?>
        <form id="documentUploadDataForm" class="form-horizontal" enctype="multipart/form-data" action="<?=base_url('parents/documentUpload')?>" role="form" method="post">
            <div class="modal fade" id="documentupload">
              <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"><?=$this->lang->line('parents_document_upload')?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" >
                            <label for="title" class="col-sm-2 control-label">
                                <?=$this->lang->line("parents_title")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title')?>" >
                            </div>
                            <span class="col-sm-4 control-label" id="title_error">
                            </span>
                        </div>

                        <div class="form-group">
                           <label for="file" class="col-sm-2 control-label">
                                <?=$this->lang->line("parents_file")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                            <span class="fa fa-remove"></span>
                                            <?=$this->lang->line('parents_clear')?>
                                        </button>
                                        <div class="btn btn-success image-preview-input">
                                            <span class="fa fa-repeat"></span>
                                            <span class="image-preview-input-title">
                                            <?=$this->lang->line('parents_file_browse')?></span>
                                            <input type="file" id="file" name="file"/>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <span class="col-sm-4 control-label" id="file_error">
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                        <input type="button" id="uploadfile" class="btn btn-success" value="<?=$this->lang->line("parents_upload")?>" />
                    </div>
                </div>
              </div>
            </div>
        </form>


        <script type="text/javascript">
            $(document).on('click', '#uploadfile', function() {
                var title = $('#title').val();
                var file = $('#file').val();
                var error = 0;

                if(title == '' || title == null) {
                    error++;
                    $('#title_error').html("<?=$this->lang->line('parents_title_required')?>");
                    $('#title_error').parent().addClass('has-error');
                } else {
                    $('#title_error').html('');
                    $('#title_error').parent().removeClass('has-error');
                }

                if(file == '' || file == null) {
                    error++;
                    $('#file_error').html("<?=$this->lang->line('parents_file_required')?>");
                    $('#file_error').parent().addClass('has-error');
                } else {
                    $('#file_error').html('');
                    $('#file_error').parent().removeClass('has-error');
                }

                if(error == 0) {
                    var parentsID = "<?=$profile->parentsID?>";
                    var formData = new FormData($('#documentUploadDataForm')[0]);
                    formData.append("parentsID", parentsID);
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: "<?=base_url('parents/documentUpload')?>",
                        data: formData,
                        async: false,
                        dataType: "html",
                        success: function(data) {
                            var response = jQuery.parseJSON(data);
                            if(response.status) {
                                $('#title_error').html();
                                $('#title_error').parent().removeClass('has-error');

                                $('#file_error').html();
                                $('#file_error').parent().removeClass('has-error');
                                location.reload();
                            } else {
                                if(response.errors['title']) {
                                    $('#title_error').html(response.errors['title']);
                                    $('#title_error').parent().addClass('has-error');
                                } else {
                                    $('#title_error').html();
                                    $('#title_error').parent().removeClass('has-error');
                                }
                                
                                if(response.errors['file']) {
                                    $('#file_error').html(response.errors['file']);
                                    $('#file_error').parent().addClass('has-error');
                                } else {
                                    $('#file_error').html();
                                    $('#file_error').parent().removeClass('has-error');
                                }
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            });     

            $(function() {
                var closebtn = $('<button/>', {
                    type:"button",
                    text: 'x',
                    id: 'close-preview',
                    style: 'font-size: initial;',
                });
                closebtn.attr("class","close pull-right");

                $('.image-preview').popover({
                    trigger:'manual',
                    html:true,
                    title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
                    content: "There's no image",
                    placement:'bottom'
                });

                $('.image-preview-clear').click(function(){
                    $('.image-preview').attr("data-content","").popover('hide');
                    $('.image-preview-filename').val("");
                    $('.image-preview-clear').hide();
                    $('.image-preview-input input:file').val("");
                    $(".image-preview-input-title").text("<?=$this->lang->line('parents_file_browse')?>");
                });

                $(".image-preview-input input:file").change(function (){
                    var img = $('<img/>', {
                        id: 'dynamic',
                        width:250,
                        height:200,
                        overflow:'hidden'
                    });

                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(".image-preview-input-title").text("<?=$this->lang->line('parents_file_browse')?>");
                        $(".image-preview-clear").show();
                        $(".image-preview-filename").val(file.name);
                    }
                    reader.readAsDataURL(file);
                });
            });
        </script>
    <?php } ?>


    <!-- email modal starts here -->
    <form class="form-horizontal" role="form" action="<?=base_url('parents/send_mail');?>" method="post">
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
            var parentsID = "<?=$profile->parentsID?>";
            var error = 0;

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
                    url: "<?=base_url('parents/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&parentsID=" + parentsID+ "&message=" + message,
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
    </script>
<?php } ?>