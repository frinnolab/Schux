<?php
    $days = [
        0 => $this->lang->line('sunday'),
        1 => $this->lang->line('monday'),
        2 => $this->lang->line('tuesday'),
        3 => $this->lang->line('wednesday'),
        4 => $this->lang->line('thursday'),
        5 => $this->lang->line('friday'),
        6 => $this->lang->line('saturday'),
    ];
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-routine"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_routine')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-header">
                    <?php if(permissionChecker('routine_add')) { ?>
                        <a href="<?php echo base_url('routine/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>

                    <?php if($this->session->userdata('usertypeID') != 3) { ?>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                            <?php
                                $array = array("0" => $this->lang->line("routine_select_classes"));
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                    <?php } ?>
                </h5>

                <?php if(count($routines) > 0 ) { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("routine_all_routine")?></a></li>
                            <?php foreach ($sections as $key => $section) {
                                echo '<li class=""><a data-toggle="tab" href="#'. $section->sectionID .'" aria-expanded="false">'. $this->lang->line("routine_section")." ".$section->section. " ( ". $section->category." )".'</a></li>';
                            } ?>
                        </ul>

                        <div class="tab-content" id="scrolling">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table-2">
                                    <table id="table" class="table table-bordered ">
                                        <tbody>
                                            <?php
                                                $flag  = 0;
                                                $map   = function($r) {return $r->day;};
                                                $count = array_count_values(array_map($map, $routines));
                                                $max   = max($count);
                                                foreach ($days as $dayKey => $day) {
                                                    $row_count = 0;
                                                    foreach ($routines as $routine) {
                                                        if($routine->day == $dayKey) {
                                                            if(!in_array($dayKey, $weekends)) {
                                                                if($flag == 0) {
                                                                    echo '<tr>';
                                                                    echo '<td>'.$day.'</td>';
                                                                    $flag = 1;
                                                                }
                                                                echo '<td class="text-center">';
                                                                    echo $routine->start_time.'-'.$routine->end_time.'<br/>';
                                                                    echo $routine->section.'<br/>';
                                                                    echo $routine->subject.'<br/>';
                                                                    echo $routine->name.'<br/>';
                                                                    echo $routine->room.'<br/>';
                                                                    echo '-------<br/>';
                                                                    echo $routine->combinedSubject.'<br/>';
                                                                    echo $routine->combinedTeacher.'<br/>';
                                                                    echo $routine->combinedRoom.'<br/>';
                                                                    if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                                                                        if(permissionChecker('routine_edit')) {
                                                                            echo btn_edit('routine/edit/'.$routine->routineID.'/'.$set, $this->lang->line('edit'));
                                                                        }

                                                                        if(permissionChecker('routine_delete')) {
                                                                            echo btn_delete('routine/delete/'.$routine->routineID.'/'.$set, $this->lang->line('delete'));
                                                                        }
                                                                    }
                                                                echo '</td>';
                                                                $row_count++;
                                                            }
                                                        }
                                                    }

                                                    if($flag == 1) {
                                                        while($row_count<$max) {
                                                            echo "<td class='text-center'>N/A</td>";
                                                            $row_count++;
                                                        }
                                                        echo '</tr>';
                                                        $flag = 0;
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <?php foreach ($sections as $key => $section) { ?>
                                <div id="<?=$section->sectionID?>" class="tab-pane">
                                    <div id="hide-table-2">
                                        <table id="table" class="table table-bordered ">
                                            <tbody>
                                                <?php
                                                    if(isset($allsection[$section->section]) && count($allsection[$section->section])) {
                                                        $map = function($r) {return $r->day;};
                                                        $count = array_count_values(array_map($map, $allsection[$section->section]));
                                                        $max = max($count);

                                                        $flag = 0;
                                                        foreach ($days as $dayKey => $day) {
                                                            $row_count = 0;
                                                            foreach($allsection[$section->section] as $routine) {
                                                                if($routine->day == $dayKey) {
                                                                    if(!in_array($dayKey, $weekends)) {
                                                                       if($flag == 0) {
                                                                            echo '<tr>';
                                                                            echo '<td>'.$day.'</td>';
                                                                            $flag = 1;
                                                                       }
                                                                        echo '<td class="text-center">';
                                                                            echo $routine->start_time.'-'.$routine->end_time.'<br/>';
                                                                            echo $routine->subject.'<br/>';
                                                                            echo $routine->room.'<br/>';
                                                                            echo $routine->name.'<br/>';
                                                                            if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                                                                                if(permissionChecker('routine_edit')) {
                                                                                    echo btn_edit('routine/edit/'.$routine->routineID.'/'.$set, $this->lang->line('edit'));
                                                                                }

                                                                                if(permissionChecker('routine_delete')) {
                                                                                    echo btn_delete('routine/delete/'.$routine->routineID.'/'.$set, $this->lang->line('delete'));
                                                                                }
                                                                            }
                                                                        echo '</td>';
                                                                        $row_count++;
                                                                    }
                                                                }
                                                            }

                                                            if($flag == 1) {
                                                                while($row_count<$max) {
                                                                    echo "<td class='text-center'>N/A</td>";
                                                                    $row_count++;
                                                                }
                                                                echo '</tr>';
                                                                $flag = 0;
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("routine_all_routine")?></a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table-2">
                                    <table id="table" class="table table-striped ">
                                        <tbody>
                                            <?php
                                                $flag = 0;
                                                foreach ($days as $dayKey => $day) {
                                                    if(!in_array($dayKey, $weekends)) {
                                                        echo '<tr>';
                                                            echo '<td>'.$day.'</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('routine/routine_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });

    $('.select2').select2();
    var mainWidth = $('html').width();
    if(mainWidth >= 980) {
        $('.tab-pane').mCustomScrollbar({
            axis:"x" // horizontal scrollbar
        });
    } 
</script>
