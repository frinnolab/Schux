
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-issue"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_issue')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                    <?php if(permissionChecker('issue_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('issue/add') ?>">
                                <i class="fa fa-plus"></i>
                                <?=$this->lang->line('add_title')?>
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>
                
                <?php if($this->session->userdata('usertypeID') != 3 && $this->session->userdata('usertypeID') != 4) { ?>
                    <div class="col-lg-6 col-lg-offset-3 list-group">
                        <div class="list-group-item list-group-item-warning">
                            <form style="" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                <div class='form-group' >
                                    <label for="lid" class="col-sm-3 control-label">
                                        <?=$this->lang->line("issue_lid")?> <span class="text-red">*</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="lid" name="lid" value="<?=set_value('lid')?>" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="submit" class="btn btn-success iss-mar" value="<?=$this->lang->line('issue_search')?>" >
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-sm-12">
                    <div class="row">
                        <div id="hide-table">
                            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('slno')?></th>
                                        <th><?=$this->lang->line('issue_name')?></th>
                                        <th><?=$this->lang->line('issue_book')?></th>
                                        <th><?=$this->lang->line('issue_serial_no')?></th>
                                        <th><?=$this->lang->line('issue_issue_date')?></th>
                                        <th><?=$this->lang->line('issue_due_date')?></th>
                                        <th><?=$this->lang->line('issue_status')?></th>
                                        <?php  if(permissionChecker('issue_view') || permissionChecker('issue_edit')) { ?>
                                            <th class="col-lg-2"><?=$this->lang->line('action')?></th>
                                        <?php } ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($issues)) {$i = 1; foreach($issues as $issue) { 
                                        if($issue->return_date == "" || empty($issue->return_date)) {
                                    ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                <?php echo $i; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_name')?>">
                                                <?php echo $issue->name; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_book')?>">
                                                <?php echo $issue->book; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_serial_no')?>">
                                                <?php echo $issue->serial_no; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_issue_date')?>">
                                                <?php echo date("d M Y", strtotime($issue->issue_date)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_due_date')?>">
                                                <?php echo date("d M Y", strtotime($issue->due_date)); ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('issue_status')?>">
                                                <?php
                                                    $date = date("Y-m-d");
                                                    if(strtotime($date) > strtotime($issue->due_date)) {
                                                        echo '<button class="btn btn-xs btn-danger">';
                                                        echo $this->lang->line('issue_overdue');
                                                        echo '</button>';
                                                    }
                                                ?>  
                                            </td>
                                            <?php if(permissionChecker('issue_view') || permissionChecker('issue_edit')) { ?>
                                            <td data-title="<?=$this->lang->line('action')?>">
                                                <?php
                                                    echo btn_view('issue/view/'.$issue->issueID, $this->lang->line('view')).' ';
                                                    if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                                                        echo btn_edit('issue/edit/'.$issue->issueID."/".$issue->lID, $this->lang->line('edit'));
                                                    }
                                                ?>
                                                <?php
                                                    if(permissionChecker('issue_add') && permissionChecker('issue_edit')) {
                                                        if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                                                            if($issue->return_date == "" || empty($issue->return_date)) {
                                                                echo ' <a href="" class="btn btn-danger btn-xs mrg returnBtn" data-toggle="modal" data-target="#returnModal" data-issueID="'.$issue->issueID.'" data-lID="'.$issue->lID.'" >
                                                                        <i class="fa fa-mail-forward"></i>
                                                                    </a>';
                                                                // echo " ". btn_return('issue/returnbook/'.$issue->issueID."/".urlencode($issue->lID), $this->lang->line('return'));
                                                            }
                                                            $date = date("Y-m-d");
                                                            if(strtotime($date) > strtotime($issue->due_date)) {
                                                                echo '<a href="#invoice" class="btn btn-xs btn-danger mrg"  data-toggle="modal" rel="tooltip">';
                                                                echo '<i class="fa icon-invoice" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('issue_add_fine').'" ></i>';
                                                                echo '</a>';
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    <?php $i++; }}} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Return Condition -->
<form class="form-horizontal" role="form" id="return_book" method="post">
    <div class="modal fade" id="returnModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('issue_fine')?></h4>
            </div>
            <div class="modal-body">
                <?php 
                    if(form_error('return_condition')) 
                        echo "<div class='form-group has-error' >";
                    else     
                        echo "<div class='form-group' >";
                ?>
                    <label for="return_condition" class="col-sm-2 control-label">
                        <?=$this->lang->line("issue_return_condition")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <?php
                            $conditions = array(null => $this->lang->line('issue_select_return_condition'));
                            $options = array('Good' => 'Good', 'Bad' => 'Bad');
                            $conditions = array_merge($conditions, $options);
                            echo form_dropdown("return_condition", $conditions, set_value("return_condition"), "id='return_condition' class='form-control select2' required");
                        ?>
                    </div>
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('return_condition'); ?>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                
                <input type="submit" id="return_book_btn" class="btn btn-success" value="<?=$this->lang->line("issue_return_btn")?>" />
            </div>
        </div>
        </div>
    </div>
</form>
<!-- End Return Condition -->

<?php if(count($issues)) { ?>
    <!-- For invoice -->
    <form class="form-horizontal" role="form" action="<?=base_url('student/send_mail');?>" method="post">
        <div class="modal fade" id="invoice">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?=$this->lang->line('issue_fine')?></h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="libraryID" name="libraryID" value="<?=$libraryID?>" style="display:none" >
                    <div class="form-group">
                        <label for="amount" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_amount")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount')?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="amount_error">
                        </span>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                    <input type="button" id="add_invoice" class="btn btn-success" value="<?=$this->lang->line("issue_add_fine")?>" />
                </div>
            </div>
          </div>
        </div>
    </form>
    <!-- email end here -->

    <script type="text/javascript">
      $(document).ready(function(){
        $('#add_invoice').click(function() {
            var amount = $('#amount').val();
            var libraryID = $('#libraryID').val();
            if(amount != '') {
                if(amount.length < 21) {
                    var valid = !/^\s*$/.test(amount) && !isNaN(amount);
                    if(valid) {
                        $.ajax({
                            type: 'POST',
                            url: "<?=base_url('issue/add_invoice')?>",
                            data: {'libraryID' : libraryID, 'amount' : amount},
                            dataType: "html",
                            success: function(data) {
                                window.location.href = data;
                            }
                        });

                        $('#amount_error').html("");
                    } else {
                        $('#amount_error').html("<?=$this->lang->line('issue_amount_number_message');?>").css('color', 'red');
                    }
                } else {
                    $('#amount_error').html("<?=$this->lang->line('issue_amount_max_message');?>").css('color', 'red');
                }
            } else {
                $('#amount_error').html("<?=$this->lang->line('issue_amount_required_message');?>").css('color', 'red');
            }
        });

        let lID, issueID;

        $('.returnBtn').click(function(e){
            lID = $(this).data('lid')
            issueID = $(this).data('issueid')
        });

        $('#return_book_btn').on('click', function(e){
            e.preventDefault()
            let formData = {
                'lID'               : lID,
                'issueID'           : issueID,
                'return_condition'  : $('#return_condition').val(),
            };

            let current_url = window.location.href
            var pathParts = new URL(current_url).pathname.split('/');
            let part = pathParts.slice(1, 2).join('/') + '/';
            
            url = `/${part}issue/returnbook/${formData.issueID}/${encodeURIComponent(formData.lID)}?return_condition=${formData.return_condition}`
            
            let confirmation = confirm('you are return the book . This cannot be undone. are you sure?');

            if (confirmation) {
                window.location.href = url
            }
        });
      });
    </script>
<?php } ?>