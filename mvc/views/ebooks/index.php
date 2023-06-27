<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-ebook"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_ebooks')?></li>
        </ol>
    </div><!-- /.box-header -->
    
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
               <h5 class="page-header">
                    <?php if(permissionChecker('ebooks_add')) { ?>
                        <a href="<?=base_url('ebooks/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?> 
                </h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ebooks_list">
                    <div class="row">
                    <?php if(count($ebooks)) { $j=0; foreach($ebooks as $ebook) { $j++; ?>
                        <div class="col-lg-3 col-sm-4 col-md-3 col-xs-12">
                            <div class="single_ebooks">
                                <div class="thumbnail">
                                  <?php 
                                    $img = FCPATH.'uploads/ebooks/'.$ebook->cover_photo;
                                    if(file_exists($img)) {
                                        $cover_photo = base_url('uploads/ebooks/'.$ebook->cover_photo);
                                    } else {
                                        $cover_photo = base_url('uploads/ebooks/default.jpg');
                                    }
                                  ?>
                                  <img src="<?=$cover_photo?>" alt="<?=$ebook->name?>">
                                  <div class="caption">
                                    <h5><?=namesorting($ebook->name,20)?></h5>
                                    <p><?=namesorting($ebook->author,20)?></p>
                                  </div>
                                </div>
                                <ul>
                                <?php if(permissionChecker('ebooks_view')) { ?>
                                    <li><a href="<?=base_url('ebooks/view/'.$ebook->ebooksID)?>"><i class="fa fa-eye"></i></a></li>
                                <?php } ?>
                                <?php if(permissionChecker('ebooks_edit')) { ?>
                                    <li><a href="<?=base_url('ebooks/edit/'.$ebook->ebooksID)?>"><i class="fa fa-edit"></i></a></li>
                                <?php } ?>
                                <?php if(permissionChecker('ebooks_delete')) { ?>
                                    <li><a href="<?=base_url('ebooks/delete/'.$ebook->ebooksID)?>"><i class="fa fa-trash"></i></a></li>
                                <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php 
                        // if($j==4) {
                        //     $j = 0;
                        //     echo "</div><div class='row'>";
                        // } 
                    } } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><?=$this->pagination->create_links();?></div>
                    </div>
                </div>
            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->