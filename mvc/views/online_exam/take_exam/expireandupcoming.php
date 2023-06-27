<div class="col-sm-12 do-not-refresh" style="padding-top: 10px">
    <div class="callout callout-danger">
        <h4><?=$this->lang->line('take_exam_warning')?></h4>
        <p><?=$this->lang->line('take_exam_page_refresh')?></p>
    </div>
</div>

<section class="panel">
    <div class="panel-body">
        <div id="printablediv" class="box-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <?=profileviewimage($student->photo)?>
                            <h3 class="profile-username text-center"><?=$student->name?></h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_registerNO')?></b> <a class="pull-right"><?=$student->srregisterNO?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_roll')?></b> <a class="pull-right"><?=$student->srroll?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_class')?></b> <a class="pull-right"><?=count($class) ? $class->classes : ''?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('take_exam_section')?></b> <a class="pull-right"><?=count($section) ? $section->section : ''?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('take_exam_exam_info')?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="profile">
                                <div class="panel-body profile-view-dis">
                                    <div class="row" style="min-height: 250px">
                                    <?php 
                                        if($examsubjectstatus) {
                                            if(count($onlineExam)) {
                                                if($onlineExam->published == 1) {
                                                    if($expirestatus) {
                                                        echo '<h2>'.$this->lang->line('take_exam_exam_expired').'<h2>';
                                                    } elseif($upcomingstatus) {
                                                        echo '<h2>'.$this->lang->line('take_exam_exam_upcoming').'</h2>';
                                                    }
                                                } else {
                                                    echo '<h2>'.$this->lang->line('take_exam_not_published').'</h2>';
                                                }
                                            } else {
                                                echo "<h2>".$this->lang->line('take_exam_exam_not_found')."</h2>";
                                            }
                                        } else {
                                            echo "<h2>".$this->lang->line('take_exam_not_allowed')."</h2>";
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>