<div id="printablediv">
    <div class="box">
        <div class="col-sm-12">
            <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
        </div>

        <h2 style="margin-bottom: -12px;margin-left: 10px"><?=$this->lang->line('classesreport_report_for')?> <?=$this->lang->line('classesreport_class')?> - <?=$class->classes?> ( <?=$sectionName?> )</h2>

        <!-- form start -->
        <div class="box-body">
            <div class="row">

                <div class="col-sm-6 pull-left">
                    <div class="box box-solid classinfo">
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_class_info")?></h3>
                        </div>
                        <div class="box-body">
                            <span><?=$this->lang->line("classesreport_class_number_of_students")?> : <?=count($students)?></span><br>
                            <span><?=$this->lang->line("classesreport_class_total_subject_assigned")?> : <?=count($subjects)?></span>
                        </div>
                    </div>

                    <br>

                    <div class="box box-solid subjectandteacher">
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_subject_and_teachers")?></h3>
                        </div>
                        <div class="box-body">
                            <table>
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
                </div>

                <div class="col-sm-6 pull-right">
                    <div class="box box-solid classteacher" >
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_class_teacher")?></h3>
                        </div>
                        <div class="box-body">
                            <?php if(isset($teachers[$class->teacherID])) {
                                    $teacher = $teachers[$class->teacherID]; ?>
                            <div class="profile">
                                    <div class="border_image">
                                        <img src="<?=imagelink($teacher->photo)?>" alt="">
                                    </div>
                                <h1><?=$teacher->name?></h1>
                              </div>
                              <table class="table table-hover">
                                  <tbody>
                                      <tr>
                                        <td><?=$this->lang->line('classesreport_phone')?></td>
                                        <td><?=$teacher->phone?></td>
                                      </tr>
                                      <tr>
                                          <td><?=$this->lang->line('classesreport_email')?></td>
                                        <td><?=$teacher->email?></td>
                                      </tr>
                                      <tr>
                                        <td><?=$this->lang->line('classesreport_address')?></td>
                                        <td><?=$teacher->address?></td>
                                      </tr>
                                  </tbody>
                              </table>
                            <?php } ?>
                        </div>
                    </div>

                    <br>

                    <div class="box box-solid free_collection">
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("classesreport_feetypes_collection")?></h3>
                        </div>
                        <div class="box-body">
                            <?php if(count($feetypes)) { ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line("classesreport_feetype")?></th>
                                        <th><?=$this->lang->line("classesreport_collection")?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($feetypes as $feetype => $collection ) { ?>
                                        <tr>
                                            <td><?=$feetype?></td>
                                            <td><?=$collection?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>
                   
                </div>

            </div><!-- row -->
        </div><!-- Body -->
        <hr class="hr">
        <div class="col-sm-12">
            <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
        </div>
    </div>
</div>
