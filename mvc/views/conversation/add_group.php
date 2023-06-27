<?php
    $email = $this->session->userdata('email');
    $usertype=$this->session->userdata('usertype');
?>

<div class="box">
    <div class="box-body">
        <div class="row">
			<?php include_once 'sidebar.php'; ?>

			<div class="col-md-9">

				<div class="box box-primary">
                	<div class="box-header with-border">
                  		<h3 class="box-title"><?=$this->lang->line('compose_new')?></h3>
                  	</div>


                  	<div class="box-body">
                    	<form role="form" method="post" enctype="multipart/form-data">
                      		<div class="form-group <?=form_error('userGroup') ? 'has-error' : '' ?>">
                          		<select id="userGroup" class="Group form-control select2" name="userGroup">
                            		<option value="0"><?=$this->lang->line('select_group')?></option>
		                            <?php
		                              if(count($usertypes)) {
		                                foreach ($usertypes as $key => $usertype) {
		                                  echo '<option value="'.$usertype->usertypeID.'">'.$usertype->usertype.'</option>';
		                                }
		                              }
		                            ?>
                          		</select>
                          		<span class="control-label">
                              		<?php echo form_error('userGroup'); ?>
                          		</span>
                      		</div>


							<div id="classDiv" class="form-group <?=form_error('classID') ? 'has-error' : '' ?>" style="display:none;">
		                        <select id="classID" class="Group form-control select2" name="classID">
		                            <option value=""><?=$this->lang->line('select_class')?></option>
		                        </select>
	                          	<span id="selectDiv" class="control-label">
	                              <?php echo form_error('classID'); ?>
	                          	</span>
		                    </div>


		                    <div id="stdDiv" class="form-group" style="display:none;">
		                        <select id="studentID" class="Group form-control select2" name="studentID">
		                            <option value=""><?=$this->lang->line('select_student')?></option>
		                        </select>
								
		                        <span class="has-error" id="selectDiv">
		                            <?php echo form_error('studentID'); ?>
		                        </span>
		                    </div>


		                    <div id="adminDiv" class="form-group" style="display:none;">
	                          	<select id="systemadminID" class="Group form-control select2" name="systemadminID">
	                            	<option value=""><?=$this->lang->line('select_admin')?></option>
	                          	</select>
		                    
		                        <span class="has-error" id="selectDiv">
		                            <?php echo form_error('systemadminID'); ?>
		                        </span>
		                    </div>

		                    <div id="teacherDiv" class="form-group" style="display:none;">
	                          	<select id="teacherID" class="Group form-control select2" name="teacherID">
	                            	<option value=""><?=$this->lang->line('select_teacher')?></option>
	                          	</select>
		                        <span class="has-error" id="selectDiv">
									<?php echo form_error('teacherID'); ?>
		                        </span>
		                    </div>


		                    <div id="parentDiv" class="form-group" style="display:none;">
                          		<select id="parentID" class="Group form-control select2" name="parentID">
                            		<option value=""><?=$this->lang->line('select_parent')?></option>
                          		</select>
                   
		                        <span class="has-error" id="selectDiv">
									<?php echo form_error('parentID'); ?>
		                        </span>
                      		</div>

	                      	<div id="userDiv" class="form-group" style="display:none;">
	                          	<select id="userID" class="Group form-control select2" name="userID">
	                            	<option value=""><?=$this->lang->line('select_user')?></option>
	                          	</select>
	                       
	                        	<span class="has-error" id="selectDiv">
	                           		<?php echo form_error('userID'); ?></p>
	                        	</span>
	                      	</div>

                      		<div class="form-group <?=form_error('subject') ? 'has-error' : '' ?>">
                        		<input class="form-control" name="subject" value="<?=set_value('subject')?>" placeholder="<?=$this->lang->line('subject')?>"/>

                        		<span class="control-label">
                              		<?php echo form_error('subject'); ?>
                          		</span>
                          	</div>
                      
	                      	<div class="form-group <?=form_error('message') ? 'has-error' : '' ?>">
	                        	<textarea class="form-control" name="message" rows="10" placeholder="<?=$this->lang->line('message')?>"><?=set_value('message')?></textarea>

	                        	<span class="control-label">
                              		<?php echo form_error('message'); ?>
                          		</span>
                          	</div>


                          	<div class="form-group">
		                        <div class="btn btn-info btn-file">
		                          	<i class="fa fa-paperclip"></i> <?=$this->lang->line('attachment')?>
		                          	<input type="file" id="attachment" name="attachment"/>
		                        </div>
                        		<div class="col-sm-3" style="padding-left:0;">
                            		<input class="form-control"  id="uploadFile" placeholder="<?=$this->lang->line('choosefile');?>" disabled />
                        		</div>
                        		<div class="has-error">
                            		<p class="text-danger"> <?php echo form_error('attachment'); ?></p>
                        		</div>
                      		</div>

                      		<div class="pull-right">
                        		<button type="submit" value="send" name="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> <?=$this->lang->line('send')?></button>
                      		</div>

                      		<button type="submit" value="draft" name="submit" class="btn btn-warning"><i class="fa fa-times"></i> <?=$this->lang->line('draft')?></button>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
  	$('.select2').select2();
  	document.getElementById("attachment").onchange = function() {
      	document.getElementById("uploadFile").value = this.value;
  	};

  	$( "#userGroup" ).change(function() {
  		if($(this).val() == 1) {
		    $("#classDiv").hide();
		    $("#stdDiv").hide();
		    $("#teacherDiv").hide();
		    $("#parentDiv").hide();
		    $("#userDiv").hide();
		    $("#adminDiv").show();
		    $.ajax({
				type: 'POST',
				url: "<?=base_url('conversation/adminCall')?>",
				dataType: "html",
				success: function(data) {
					$('#systemadminID').html(data);
				}
		    });
  		} else if($(this).val() == 2) {
			$("#classDiv").hide();
			$("#stdDiv").hide();
			$("#adminDiv").hide();
			$("#parentDiv").hide();
			$("#userDiv").hide();
			$("#teacherDiv").show();
			$.ajax({
				type: 'POST',
				url: "<?=base_url('conversation/teacherCall')?>",
				dataType: "html",
				success: function(data) {
					$('#teacherID').html(data);
				}
			});
		} else if($(this).val() == 3) {
			$("#classDiv").show();
			$("#stdDiv").show();
			$("#adminDiv").hide();
			$("#teacherDiv").hide();
			$("#userDiv").hide();
			$("#parentDiv").hide();
			$.ajax({
				type: 'POST',
				url: "<?=base_url('conversation/classCall')?>",
				dataType: "html",
				success: function(data) {
					$('#classID').html(data);
				}
			});
		} else if($(this).val() == 4) {
			$("#classDiv").hide();
			$("#stdDiv").hide();
			$("#adminDiv").hide();
			$("#parentDiv").hide();
			$("#teacherDiv").hide();
			$("#userDiv").hide();
			$("#parentDiv").show();
			$.ajax({
				type: 'POST',
				url: "<?=base_url('conversation/parentCall')?>",
				dataType: "html",
				success: function(data) {
					$('#parentID').html(data);
				}
			});
  		} else {
			var id = $(this).val();
			$("#classDiv").hide();
			$("#stdDiv").hide();
			$("#adminDiv").hide();
			$("#parentDiv").hide();
			$("#teacherDiv").hide();
			$("#parentDiv").hide();
			$("#userDiv").show();
			$.ajax({
				type: 'POST',
				url: "<?=base_url('conversation/userCall')?>",
				data : {id : id},
				dataType: "html",
				success: function(data) {
					$('#userID').html(data);
				}
			});
		}
	});

	$('#classID').change(function(event) {
	    var classID = $(this).val();
	    if(classID === '0') {
	        $('#studentID').val(0);
	    } else {
	        $.ajax({
	            type: 'POST',
	            url: "<?=base_url('conversation/studentCall')?>",
	            data: "id=" + classID,
	            dataType: "html",
	            success: function(data) {
	               $('#studentID').html(data);
	            }
	        });
	    }
	});
</script>

<?php if($GroupID != 0) { ?>
<script>

	var GroupID = "<?=$GroupID?>";

  	if(GroupID == 1) {
	    $("#classDiv").hide();
	    $("#stdDiv").hide();
	    $("#teacherDiv").hide();
	    $("#parentDiv").hide();
	    $("#userDiv").hide();
	    $("#adminDiv").show();
	    $.ajax({
			type: 'POST',
			url: "<?=base_url('conversation/adminCall')?>",
			dataType: "html",
			success: function(data) {
				$('#systemadminID').html(data);
			}
	    });
  	} else if(GroupID == 2) {
		$("#classDiv").hide();
		$("#stdDiv").hide();
		$("#adminDiv").hide();
		$("#parentDiv").hide();
		$("#userDiv").hide();
		$("#teacherDiv").show();
		$.ajax({
			type: 'POST',
			url: "<?=base_url('conversation/teacherCall')?>",
			dataType: "html",
			success: function(data) {
				$('#teacherID').html(data);
			}
		});
	} else if(GroupID == 3) {
		$("#classDiv").show();
		$("#stdDiv").show();
		$("#adminDiv").hide();
		$("#teacherDiv").hide();
		$("#userDiv").hide();
		$("#parentDiv").hide();
		$.ajax({
			type: 'POST',
			url: "<?=base_url('conversation/classCall')?>",
			dataType: "html",
			success: function(data) {
				$('#classID').html(data);
			}
		});
	} else if(GroupID == 4) {
		$("#classDiv").hide();
		$("#stdDiv").hide();
		$("#adminDiv").hide();
		$("#parentDiv").hide();
		$("#teacherDiv").hide();
		$("#userDiv").hide();
		$("#parentDiv").show();
		$.ajax({
			type: 'POST',
			url: "<?=base_url('conversation/parentCall')?>",
			dataType: "html",
			success: function(data) {
				$('#parentID').html(data);
			}
		});
  	} else {
		var id = $(this).val();
		$("#classDiv").hide();
		$("#stdDiv").hide();
		$("#adminDiv").hide();
		$("#parentDiv").hide();
		$("#teacherDiv").hide();
		$("#parentDiv").hide();
		$("#userDiv").show();
		$.ajax({
			type: 'POST',
			url: "<?=base_url('conversation/userCall')?>",
			data : {id : id},
			dataType: "html",
			success: function(data) {
				$('#userID').html(data);
			}
		});
	}

</script>
<?php } ?>

