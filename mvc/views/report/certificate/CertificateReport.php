<div id="printablediv">
    <div class="box">
        <div class="box-header bg-gray">
            <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
            <?=$this->lang->line('certificatereport_report_for');?> <?=$this->lang->line('certificatereport_report');?> - 
            <?=$this->lang->line('certificatereport_class')?> <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?> <?=isset($sections[$sectionID]) ? "( ".$sections[$sectionID]." )" : ''?> </h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="pull-left">
                                <?php 
                                    echo $this->lang->line('certificatereport_class')." : ";
                                    echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('balancefeesreport_all_class');
                                ?>
                            </h5>                         
                            <h5 class="pull-right">
                                <?php
                                   echo $this->lang->line('certificatereport_section')." : ";
                                   echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('certificatereport_all_section');
                                ?>
                            </h5>                        
                        </div>
                    </div>
                    <?php if(count($students)) { ?>
                        <div id="hide-table">
                            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="col-sm-1">#</th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_photo')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('certificatereport_name')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('certificatereport_class')?></th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_section')?></th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_roll')?></th>
                                        <th class="col-sm-1"><?=$this->lang->line('certificatereport_action')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($students as $student) { ?>
                                        <tr>
                                            <td data-title="#">
                                                <?=$i?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('certificatereport_photo')?>">
                                                <?=profileimage($student->photo)?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('certificatereport_name')?>">
                                                <?=$student->srname; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('certificatereport_class')?>"><?=$classes[$student->srclassesID]; ?></td>
                                            <td data-title="<?=$this->lang->line('certificatereport_section')?>"><?=$sections[$student->srsectionID]; ?></td>
                                           
                                            <td data-title="<?=$this->lang->line('certificatereport_roll')?>">
                                                <?=$student->srroll; ?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('certificatereport_action')?>">
                                                <a class="btn btn-success btn-sm" target="_blank" href="<?=base_url('certificatereport/generate_certificate/'.$student->srstudentID .'/'.$student->usertypeID.'/'.$templateID.'/'.$student->srclassesID)?>"><?=$this->lang->line('certificatereport_generate_certificate')?></a>
                                            </td>
                                       </tr>
                                    <?php $i++; } ?> 
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('certificatereport_student_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>
