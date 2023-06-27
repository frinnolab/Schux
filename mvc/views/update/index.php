
<div class="row">
    <div class="col-sm-4 ">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-refresh"></i> <?=$this->lang->line('panel_title')?></h3>
            </div>
            <div class="box-body">
                <form role="form" role="form" method="post" enctype="multipart/form-data">
                    <div class="<?=form_error('file') ? 'form-group has-error' : 'form-group' ?>">
                        <label for="file"><?=$this->lang->line("update_file")?> <span class="text-red">*</span></label>
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    <?=$this->lang->line('update_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title">
                                    <?=$this->lang->line('update_file_browse')?></span>
                                    <input id="uploadBtn" type="file" name="file"/>
                                </div>
                            </span>
                        </div>
                    </div>

                    <input id="update" type="submit" class="btn btn-success" value="<?=$this->lang->line("update_update")?>" >
                </form>
            </div>
        </div>
    </div> 
    <div class="col-sm-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-table"></i> <?=$this->lang->line('update_update_history')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_update')?></li>
                </ol>
            </div>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('update_slno')?></th>
                                <th class="col-sm-3"><?=$this->lang->line('update_date')?></th>
                                <th class="col-sm-3"><?=$this->lang->line('update_version')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('update_status')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('update_action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($updates)) { $i = 1; foreach($updates as $update) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('update_slno')?>">
                                        <?=$i?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('update_date')?>">
                                        <?=date("d M Y", strtotime($update->date));?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('update_version')?>">
                                        <?=$update->version?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('update_status')?>">
                                        <?=($update->status) ? '<span class="text-success">'.$this->lang->line('update_success').'</span>' : '<span class="text-danger">'.$this->lang->line('update_failed').'</span>'?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('update_action')?>">
                                        <a href="#log" id="<?=$update->updateID?>" class="btn btn-success btn-xs mrg getloginfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-check-square-o" data-toggle="tooltip" data-placement="top" data-original-title="<?=$this->lang->line('update_log')?>"></i></a>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="log">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('update_updatelog')?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="logcontent"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.getloginfobtn').click(function() {
        var updateID =  $(this).attr('id');
        if(updateID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('update/getloginfo')?>",
                data: {'updateID' : updateID},
                dataType: "html",
                success: function(data) {
                    $('#logcontent').html(data);
                }
            });
        }
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

            $('.image-preview-clear').click(function(){
                $('.image-preview').attr("data-content","").popover('hide');
                $('.image-preview-filename').val("");
                $('.image-preview-clear').hide();
                $('.image-preview-input input:file').val("");
                $(".image-preview-input-title").text("<?=$this->lang->line('update_file_browse')?>");
            });

            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('update_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                $('.content').css('padding-bottom', '100px');
            }
            reader.readAsDataURL(file);
        });
    });
</script>