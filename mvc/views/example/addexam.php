<div class="row">
    <div class="col-sm-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-puzzle-piece"></i> Question Bank Lang</h3>


                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> Dashboard Lang</a></li>
                    <li><a href="<?=base_url("examschedule/index")?>"> <!-- add exam url --> Exam Lang</a></li>
                    <li class="active"><?=$this->lang->line('menu_add')?> Exam lang</li>
                </ol>
            </div><!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="<?php echo form_error('levelID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="levelID" class="control-label">
                                            Exam Lang
                                        </label>
                                        <?php
                                            $array = array('0' => 'Select Level Lang');
                                            echo form_dropdown("levelID", $array, set_value("levelID"), "id='levelID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="<?php echo form_error('groupID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="groupID" class="control-label">
                                            Group Lang
                                        </label>
                                        <?php
                                            $array = array("0" => 'Select Group Lang');
                                            
                                            echo form_dropdown("groupID", $array, set_value("groupID"), "id='groupID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class='col-sm-12'>
                        <div id="hide-table">
                            <table id="example1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-sm-2"># Lang</th>
                                        <th class="col-sm-9">Question Lang</th>
                                        <th class="col-sm-1">Action Lang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-title="# Lang">1</td>
                                        <td data-title="Question Lang">AAAAAAA</td>
                                        <td data-title="Action Lang"><?=btn_add_show('example/addexam/#', 'Add Question Lang')?></td>
                                    </tr>
                                    <tr>
                                        <td data-title="# Lang">1</td>
                                        <td data-title="Question Lang">BBBBBBBB</td>
                                        <td data-title="Action Lang"><?=btn_add_show('example/addexam/#', 'Add Question Lang')?></td>
                                    </tr>
                                    <tr>
                                        <td data-title="# Lang">1</td>
                                        <td data-title="Question Lang">CCCCCCCCC</td>
                                        <td data-title="Action Lang"><?=btn_add_show('example/addexam/#', 'Add Question Lang')?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-puzzle-piece"></i> Associated Question Lang</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-sm-4">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-puzzle-piece"></i> Exam Info Lang</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="info-box">
                            <p>
                                <span>Exam Title Lang : </span>
                                Online Exam 1 
                            </p>
                            <p>
                                <span>Date Lang : </span>
                                11 Mar 2017 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-puzzle-piece"></i> Question Summary Lang</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">#</th>
                                    <th class="col-sm-8">Question Lang</th>
                                    <th class="col-sm-2">Mark Lang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-title="">1</td>
                                    <td data-title="Question Lang">A A A A A</td>
                                    <td data-title="Mark">5</td>
                                </tr>
                                <tr>
                                    <td data-title="">2</td>
                                    <td data-title="Question Lang">B B B B B</td>
                                    <td data-title="Mark">10</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>