<?php if(count($applicant)) { ?>
	<div class="well">
		<div class="row">
			<div class="col-sm-6">
	          	<button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
	          	<?php
	           		echo btn_add_pdf('leaveapply/print_preview/'.$leaveapply->leaveapplicationID, $this->lang->line('pdf_preview')) 
	          	?>
	          	<?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
		          	<?php if($leaveapply->status == null || $leaveapply->status == 0) { if(permissionChecker('leaveapply_edit')) { echo btn_sm_edit('leaveapply/edit/'.$leaveapply->leaveapplicationID, $this->lang->line('edit')); } }
		          	?>
	          	<?php } ?>
	          <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
	      </div>
	      <div class="col-sm-6">
	          <ol class="breadcrumb">
	              <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
	              <li><a href="<?=base_url("leaveapply/index")?>"><?=$this->lang->line('menu_leaveapply')?></a></li>
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
                    	<?=profileviewimage($applicant->photo)?>
                        <h3 class="profile-username text-center"><?= ucwords($userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->name) ?></h3>
                        <p class="text-muted text-center"><?=isset($usertypes[$leaveapply->applicationto_usertypeID]) ? $usertypes[$leaveapply->applicationto_usertypeID] : ''?></p>
                        <ul class="list-group list-group-unbordered">
                            <?php if($applicant->usertypeID == 4) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_phone')?></b> <a class="pull-right"><?=$userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->phone ?></a>
                                </li>
                            <?php } elseif($applicant->usertypeID == 3) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_registerNO')?></b> <a class="pull-right"><?=$applicant->srregisterNO?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_roll')?></b> <a class="pull-right"><?=$applicant->srroll?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_class')?></b> <a class="pull-right"><?=$applicant->srclasses?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_section')?></b> <a class="pull-right"><?=$applicant->srsection?></a>
                                </li>
                            <?php } else { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_gender')?></b> <a class="pull-right"><?=$userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->sex?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_dob')?></b> <a class="pull-right"><?=date('d M Y',strtotime($userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->dob))?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('leaveapply_phone')?></b> <a class="pull-right"><?=$userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->phone ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-mergin">
              	<div class="box box-primary box-height">
                	<div class="box-body box-profile">
                    	<h5><?=$this->lang->line('leaveapply_date')?> - <?=date('d M Y',strtotime($leaveapply->apply_date));?></h5>
                    	<p><?=$leaveapply->reason?></p>
                	</div>
              	</div>
            </div>
            <div class="col-sm-3">
              	<div class="box box-primary">
                	<div class="box-body box-profile">
                  		<div class="single_box">
                    		<strong><?=$this->lang->line('leaveapply_schedule');?></strong>
                    		<p class="text-muted"><?=date("d M Y",strtotime($leaveapply->from_date)). " on ". $leaveapply->from_time;?> to <br /> <?=date("d M Y",strtotime($leaveapply->to_date)). " on ". $leaveapply->to_time;?></p>
                  		</div>
	                  	<!-- <div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_availableleavedays');?></strong>
	                    	<p class="text-muted"><?=$leaveapply->leaveavabledays;?></p>
	                  	</div> -->
	                  	<div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_leavedays');?></strong>
	                    	<p class="text-muted"><?=isset($daysArray['leavedayCount']) ? $daysArray['leavedayCount'] : '';?></p>
	                  	</div>
	                  	<div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_holidays');?></strong>
	                    	<p class="text-muted"><?=isset($daysArray['holidayCount']) ? $daysArray['holidayCount'] : '';?></p>
	                  	</div>
	                  	<div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_weekenddays');?></strong>
	                    	<p class="text-muted"><?=isset($daysArray['weekenddayCount']) ? $daysArray['weekenddayCount'] : '';?></p>
	                  	</div>
	                  	<div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_totaldays');?></strong>
	                    	<p class="text-muted"><?=isset($daysArray['totaldayCount']) ? $daysArray['totaldayCount'] : '';?></p>
	                  	</div>
	                  	<div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_category');?></strong>
	                    	<p class="text-muted"><?=$leaveapply->category;?></p>
	                  	</div>
	                  	<div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_od_status');?></strong>
	                    	<p class="text-muted">
	                        	<?php if($leaveapply->od_status == null || $leaveapply->od_status == 0) { ?>
	                            	<?=$this->lang->line('leaveapply_od_status_no');?>
	                        	<?php } else { ?>
	                            	<?=$this->lang->line('leaveapply_od_status_yes');?>
	                        	<?php } ?>
	                    	</p>
	                  	</div>
	                  	<div class="single_box">
	                    	<strong><?=$this->lang->line('leaveapply_application_status');?></strong>
	                    	<p class="text-muted">
	                        	<?php if($leaveapply->status == null) { 
				                  echo $this->lang->line('leaveapply_pending');
				                 } elseif($leaveapply->status == 1) { 
				                  echo $this->lang->line('leaveapply_approved');
				                 } else { 
				                  echo $this->lang->line('leaveapply_declined');
				                } ?>
	                    	</p>
	                  	</div>
                	</div>
              	</div>
            </div>
        </div>
    </div>

    <form class="form-horizontal" role="form" action="<?=base_url('leaveapply/send_mail');?>" method="post">
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

	<script type="text/javascript">
	  	function printDiv(divID) {
	      	//Get the HTML of div
	      	var divElements = document.getElementById(divID).innerHTML;
		    //Get the HTML of whole page
		    var oldPage = document.body.innerHTML;

		    //Reset the page's HTML with div's HTML only
		    document.body.innerHTML ="<html><head><title></title></head><body>" +divElements + "</body>";

		    //Print Page
		    window.print();

		    //Restore orignal HTML
		    document.body.innerHTML = oldPage;
		    window.location.reload();
	  	}

  		function check_email(email) {
      		var status = false;
      		var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
      		if(email.search(emailRegEx) == -1) {
          		$("#to_error").html('');
          		$("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
      		} else {
          		status = true;
      		}
      		return status;
  		}


  		$("#send_pdf").click(function(){
      		var to = $('#to').val();
      		var subject = $('#subject').val();
      		var message = $('#message').val();
      		var leaveapplicationID = "<?=$leaveapply->leaveapplicationID;?>";
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
              		url: "<?=base_url('leaveapply/send_mail')?>",
              		data: 'to='+ to + '&subject=' + subject + "&leaveapplicationID=" + leaveapplicationID+ "&message=" + message,
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
<?php  } ?>
