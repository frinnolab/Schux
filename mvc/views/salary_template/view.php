<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calculator "></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("salary_template/index")?>"><?=$this->lang->line('menu_salary_template')?></a></li>
            <li class="active"><?=$this->lang->line('menu_view')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6" style="margin-bottom: 10px;">
                <div class="info-box">
                    <p style="margin:0 0 20px">
                        <span><?=$this->lang->line("salary_template_salary_grades")?></span>
                        <?=$salary_template->salary_grades?>
                    </p>

                    <p style="margin:0 0 20px">
                        <span><?=$this->lang->line("salary_template_basic_salary")?></span>
                        <?=number_format($salary_template->basic_salary, 2)?>
                    </p>

                    <p style="margin:0 0 20px">
                        <span><?=$this->lang->line("salary_template_overtime_rate")?></span>
                        <?=number_format($salary_template->overtime_rate, 2)?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="box" style="border: 1px solid #eee">
                    <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                        <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('salary_template_allowances')?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12" id="allowances">
                                <div class="info-box">
                                    <?php 
                                        if(count($salaryoptions)) { 
                                            foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                if($salaryoption->option_type == 1) {
                                    ?>
                                        <p>
                                            <span><?=$salaryoption->label_name?></span>
                                            <?=number_format($salaryoption->label_amount, 2)?>
                                        </p>
                                    <?php        
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

            <div class="col-sm-6">
                <div class="box" style="border: 1px solid #eee;">
                    <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                        <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('salary_template_deductions')?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12" id="deductions">
                                <div class="info-box">
                                    <?php 
                                        if(count($salaryoptions)) { 
                                            foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                if($salaryoption->option_type == 2) {
                                    ?>
                                        <p>
                                            <span><?=$salaryoption->label_name?></span>
                                            <?=number_format($salaryoption->label_amount, 2)?>
                                        </p>
                                    <?php        
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

        </div>

        <div class="row">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="box" style="border: 1px solid #eee;">
                    <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                        <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('salary_template_total_salary_details')?></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('salary_template_gross_salary')?></td>

                                <td class="col-sm-4" style="line-height: 36px"><?=number_format($grosssalary, 2)?></td>
                            </tr>

                            <tr>
                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('salary_template_total_deduction')?></td>

                                <td class="col-sm-4" style="line-height: 36px"><?=number_format($totaldeduction, 2)?></td>
                            </tr>

                            <tr>
                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('salary_template_net_salary')?></td>

                                <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($netsalary, 2)?></b></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>