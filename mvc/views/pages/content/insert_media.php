<h2><?=$this->lang->line('pages_insert_media')?></h2>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class=""><a data-toggle="tab" href="#insertMediaUpload"><?=$this->lang->line('pages_upload_files')?></a></li>
        <li class="active"><a data-toggle="tab" href="#insertMediaUploadLibrary"><?=$this->lang->line('pages_media_library')?></a></li>
    </ul>

    <div class="tab-content no-padd">
        <div id="insertMediaUpload" class="tab-pane">
            <div class="drop--files">
                <form id="insert-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                    <input type="text" name="media_gallery_type" value="1" style="display: none">
                    <input type="text" name="focus_id" value="insertMedia" style="display: none">
                    <div class="fileupload-input-group">
                        <span class="fileupload-input-group-btn">
                            <div class="fileupload-image-preview-input">
                                <?=$this->lang->line('pages_select_file')?>
                                <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif, .mp3,audio/*, video/mp4,video/x-m4v,video/*" name="file"/>
                            </div>
                        </span>
                    </div>
                </form>
                <div class="post--upload--info">
                    <p><?=$this->lang->line('pages_maximum_upload_file_size')?></p>
                </div>
            </div>
        </div>

        <div id="insertMediaUploadLibrary" class="tab-pane clearfix active">
            <div class="media-library-left pos-rel pull-left">
                <div class="header--select clearfix">
                    <div class="search-box-right pull-right">
                        <form action="#" method="POST">
                            <input type="search" name="media-search" placeholder="<?=$this->lang->line('pages_search_media_items')?>...">
                        </form>
                    </div>
                </div>
                <?php 
           
                    if(count($media_gallerys_all)) { 
                        echo '<div class="attached-preview">';
                            echo '<ul class="insert_media_type">';
                        foreach ($media_gallerys_all as $media_gallerys_all_key => $media_gallerys_val) {
                            if($media_gallerys_val->media_gallery_type == 1) {
                ?>       
                                <li class="insert_media_image" onclick="getFileInfo(this, 'insert_media_type', 'image',  'single', 'insert_media_hidden_field', true, 'insert_media');" id ="<?=$media_gallerys_val->media_galleryID?>">
                                    <div class="thumb">
                                        <img src="<?=base_url('uploads/gallery/'.$media_gallerys_val->file_name)?>" alt="<?=$media_gallerys_val->file_alt_text?>">
                                    </div>
                                </li>
                <?php
                            } elseif($media_gallerys_val->media_gallery_type == 2) {
                ?>
                                <li class="insert_media_audio" onclick="getFileInfo(this, 'insert_media_type', 'audio', 'single', 'insert_media_hidden_field', true, 'insert_media');" id ="<?=$media_gallerys_val->media_galleryID?>">
                                    <div class="thumb">
                                        <i class="fa fa-file-audio-o"></i>
                                    </div>
                                    <div class="video-title">
                                        <p><?=namesorting($media_gallerys_val->file_original_name, 50)?></p>
                                    </div>
                                </li>

                <?php
                            } elseif($media_gallerys_val->media_gallery_type == 3) {
                ?>
                                <li class="insert_media_video" onclick="getFileInfo(this, 'insert_media_type', 'video', 'single', 'insert_media_hidden_field', true, 'insert_media');" id ="<?=$media_gallerys_val->media_galleryID?>">
                                    <div class="thumb">
                                        <i class="fa fa-file-video-o"></i>
                                    </div>
                                    <div class="video-title">
                                        <p><?=namesorting($media_gallerys_val->file_original_name, 50)?></p>
                                    </div>
                                </li>


                <?php
                            
                            } 
                        }
                            echo '</ul>';  
                        echo '</div>';
                        echo '<input type="text" id="insert_media_hidden_field" style="display:none">';

                    } else { 
                ?>
                    <div class="drop--files">
                        <form id="insert-fileupload-list" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                            <input type="text" name="media_gallery_type" value="1" style="display: none">
                            <input type="text" name="focus_id" value="insertMedia" style="display: none">
                            <div class="fileupload-input-group">
                                <span class="fileupload-input-group-btn">
                                    <div class="fileupload-image-preview-input">
                                        <?=$this->lang->line('pages_select_file')?>
                                        <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif, .mp3,audio/*, video/mp4,video/x-m4v,video/*" name="file"/>
                                    </div>
                                </span>
                            </div>
                        </form>
                        <div class="post--upload--info">
                            <p><?=$this->lang->line('pages_maximum_upload_file_size')?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="media-library-right pull-left">
                <div class="attached-details" id="insert_media_type">
                    

                </div>
            </div>
        </div>

        <div class="footer--upload">
            <a data-dismiss="modal" id="insert_into_page" onclick="setFileToEditor(this, 'insert_media_hidden_field', 'insert_media_type');"  href="#"><?=$this->lang->line('pages_insert_into_page')?></a>
        </div>
    </div>
</div>