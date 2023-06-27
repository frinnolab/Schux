<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-connectdevelop"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_pages')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('pages_add')) {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('pages/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-5"><?=$this->lang->line('pages_title')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('pages_template')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('pages_date')?></th>
                                <?php if(permissionChecker('pages_edit') || permissionChecker('pages_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($pagess)) {$i = 1; foreach($pagess as $pages) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('pages_title')?>">
                                        <?=namesorting($pages->title, 40)?>
                                        <?php if($pages->status == 2 || $pages->status == 4) {
                                                echo '<b>--'. ucfirst(pageStatus($pages->status, FALSE)).'</b>';
                                            }
                                        ?>
                                        <?php if(strtotime($pages->publish_date) > strtotime(date('Y-m-d H:i:s'))) {
                                                echo '<b>--' .$this->lang->line('pages_scheduled').'</b>';
                                            }
                                        ?>

                                        <?php if($pages->visibility == 2 || $pages->visibility == 3) {
                                                echo '<b>--';
                                                pageVisibility($pages->visibility, FALSE);
                                                echo '</b>';
                                            }
                                        ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('pages_template')?>">
                                        <?=ucfirst($pages->template)?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('pages_date')?>">
                                        <?=date('d M Y h:i A', strtotime($pages->publish_date))?>
                                    </td>
                                  <?php if(permissionChecker('pages_edit') || permissionChecker('pages_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('pages/edit/'.$pages->pagesID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('pages/delete/'.$pages->pagesID, $this->lang->line('delete')) ?>
                                        </td>
                                  <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>