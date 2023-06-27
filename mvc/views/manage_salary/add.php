
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-beer"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("manage_salary/index/$usertypeID")?>"><?=$this->lang->line('menu_manage_salary')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_manage_salary')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                    if(form_error('salary'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="salary" class="col-sm-2 control-label">
                            <?=$this->lang->line("manage_salary_salary")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array("0" => $this->lang->line('manage_salary_select_salary'), '1' => $this->lang->line('manage_salary_monthly_salary'), '2' => $this->lang->line('manage_salary_hourly_salary'));
                                echo form_dropdown("salary", $array, set_value("salary"), "id='salary' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('salary'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('template'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="template" class="col-sm-2 control-label">
                            <?=$this->lang->line("manage_salary_template")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array(0 => $this->lang->line("manage_salary_select_template"));

                                if(count($alltemplate)) {
                                    if($salaryID == 1) {
                                        foreach ($alltemplate as $key => $alltemplateValue) {
                                            $array[$alltemplateValue->salary_templateID] = $alltemplateValue->salary_grades;
                                        }
                                    } elseif($salaryID == 2) {
                                        foreach ($alltemplate as $key => $alltemplateValue) {
                                            $array[$alltemplateValue->hourly_templateID] = $alltemplateValue->hourly_grades;
                                        }
                                    }
                                }

                                echo form_dropdown("template", $array, set_value("template"), "id='template' class='form-control select2'");

                            ?> 
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('template'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_salary_template")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

$('#salary').change(function(event) {
    var salary = $(this).val();
    if(salary === '0') {
        $('#template').html("<?="<option value='0'>".$this->lang->line('manage_salary_select_template')."</option>"?>");
    } else {
        $.ajax({
            async: false,
            type: 'POST',
            url: "<?=base_url('manage_salary/templatecall')?>",
            data: "salary=" + salary,
            dataType: "html",
            success: function(data) {
               $('#template').html(data);
            }
        });
    }
});

</script>
