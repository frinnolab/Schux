
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-classreport"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_classreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
                
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("classesreport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $array = array("0" => $this->lang->line("classesreport_please_select"));
                        if(count($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("classesreport_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("classesreport_please_select"); ?></option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <button id="get_classreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("classesreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_classreport"></div>


<script type="text/javascript">

    $('.select2').select2();
    
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        

        $('.reportPage-header').remove();
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        <?php $image = base_url('uploads/images/'.$siteinfos->photo);?>
        var footer = "<center><img src='<?=$image?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('classesreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=$image?>' style='width:50px;' /></center><center class='title'><?=$siteinfos->sname?></center><center class='title-desc'><?=$siteinfos->address?></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }
    

    $(document).on('change','#classesID', function() {
        $('#load_classreport').html('');
        var id = $(this).val();
        if(id == 0) {
            $('#sectionID').html('<option value="">'+"<?=$this->lang->line("classesreport_please_select")?>"+'</option>');
            $('#sectionID').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('classesreport/getSection')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

    $(document).on('click','#get_classreport', function() {
        var error=0;;
        var field = {
            'classesID'     : $('#classesID').val(), 
            'sectionID'     : $('#sectionID').val(), 
        };

        if(!parseInt(field['classesID'])) {
            field['classesID'] = parseInt(field['classesID']);
        }

        if (field['classesID'] === 0) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#classesDiv').removeClass('has-error');
        }

        if(error === 0) {
            makingPostDataPreviousofAjaxCall(field);
        }

    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('classesreport/getClassReport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_classreport').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#'+key).parent().addClass('has-error');
                }
            }
        }
    }

</script>
