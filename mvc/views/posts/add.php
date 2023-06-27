<!-- fa-thumb-tack -->
<form method="POST">
    <div class="row">
        <div class="col-md-8">
            <div class="add--page--wrap">
                <h3><?=$this->lang->line('posts_add_new_page')?></h3>

                <div class="form-group <?=form_error('title') ? 'has-error' : '' ?>">
                    <input type="text" id="title" value="<?=set_value('title')?>" name="title" class="form-control" placeholder="<?=$this->lang->line('posts_enter_title_here')?>">
                    <span class="errors"><?php echo form_error('title'); ?></span>
                    
                    <div class="permalink-area" <?=($send_url['status']) ? 'style="display:block"' : 'style="display:none"' ?>>
                        <span class="permalink-text"><?=$this->lang->line('posts_permalink')?>:</span> 
                        <span class="permalink"><?=base_url('frontend/post/')?><span class="editable-permalink-name"><?=($send_url['status']) ? $send_url['url'] : '' ?></span><input id="url" name="url" class="url" type="text" value="<?=($send_url['status']) ? $send_url['url'] : '' ?>" style="display: none" ></span>
                        <span><input id="permalink-edit" type="button" value="<?=$this->lang->line('posts_sm_edit')?>"></span>
                        <span id="editable-permalink-section" style="display: none">
                            <span id="save-permalink">
                                <input type="button" value="<?=$this->lang->line('posts_ok')?>">
                            </span>
                            <span class="cancel-permalink" id="cancel-permalink"><?=$this->lang->line('posts_cancel')?></span>
                        </span>
                    </div>
                    <span class="form-group has-error ">
                        <span class="errors">
                            <?php echo form_error('url'); ?>
                        </span>
                    </span>
                </div>
                <div class="form-group has-error">
                    <span class="add--media" data-toggle="modal" data-target="#mediaLibrary"><i class="fa fa-camera"></i><?=$this->lang->line('posts_add_media')?></span>
            
                    <textarea id="write-content" name="content"><?=set_value('content')?></textarea>
                    <span class="errors"><?php echo form_error('content'); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <aside class="page--right--sidebar">
                <div class="bg-white mb-15">
                    <div class="sidebar--header">
                        <h4><?=$this->lang->line('posts_publish')?></h4>
                        <span id="publish" class="flipup">
                            <i class="fa fa-caret-up"></i>
                        </span>
                    </div>
                    <div class="sidebar-body" id="publish-box">
                        <div class="save-preview-btn clearfix">

                            <button type="submit" id="preview" class="save-preview-btn pull-right clearfix" value="preview" name="submit"><?=$this->lang->line('posts_preview')?></button>

                            <?php
                                if($send_status == 'draft')
                                    echo '<button type="submit" class="btn-save-status pull-left" value="draft" name="submit">'.$this->lang->line('posts_save_draft').'</button>';
                                elseif($send_status == 'review')
                                    echo '<button type="submit" class="btn-save-status pull-left" value="review" name="submit">'.$this->lang->line('posts_save_as_pending').'</button>';
                            ?>

                            
                        </div>
                        <div class="post-status">
                            <i class="fa fa-map-pin"></i>
                            <?=$this->lang->line('posts_status')?>:
                            <span id="status-message" class="bold">
                                <?php
                                    if($send_status == 'draft')
                                        echo $this->lang->line('posts_draft');
                                    elseif($send_status == 'review')
                                        echo $this->lang->line('posts_pending_review');
                                ?>
                                
                            </span>
                            <a href="#" class="post-edit" id="draft-edit"><?=$this->lang->line('posts_edit')?></a>
                            <div class="post-status-select" id="draft-edit-show">
                                <?php 
                                    $statusArray = array(
                                        'review' => $this->lang->line('posts_pending_review'),
                                        'draft' => $this->lang->line('posts_draft')
                                    );
                                    echo form_dropdown("status", $statusArray, set_value("status", 'draft'), "id='status'"); 
                                ?>
                                <a href="#" id="save-status" class="save-post-status"><?=$this->lang->line('posts_ok')?></a>
                                <a href="#" id="cancle-draft" class="cancel-post-status"><?=$this->lang->line('posts_cancel')?></a>
                            </div>
                        </div>
                        <div class="post-status">
                            <i class="fa fa-eye"></i>
                            <?=$this->lang->line('posts_visibility')?>:
                            <span id="visibility-message" class="bold">
                                <?php 
                                    if($send_visibility == 1) 
                                        echo $this->lang->line('posts_public');
                                    elseif($send_visibility == 2) 
                                        echo $this->lang->line('posts_password_protected');
                                    elseif($send_visibility == 3) 
                                        echo $this->lang->line('posts_private'); 
                                ?>
                                
                            </span>
                            <a href="#" class="post-edit" id="visibility-edit"><?=$this->lang->line('posts_edit')?></a>
                            <div class="post-status-select" id="visibility-edit-show">
                                <span id="visibility-all-data-set">
                                    <div class="form-group ini-dis-flex">
                                        <input class="visibility" type="radio" id="public" name="visibility" value="1" checked <?=set_radio("visibility", 1)?>>
                                        <label for="public"><?=$this->lang->line('posts_public')?></label>
                                    </div>
                                    <div class="form-group ini-dis-flex">
                                        <input class="visibility" type="radio" name="visibility" id="protected" value="2" <?=set_radio("visibility", 2)?>>
                                        <label for="protected"><?=$this->lang->line('posts_password_protected')?></label>
                                    </div>
                                    <div class="form-group <?=form_error('protected_password') ? 'has-error' : '' ?>" <?=($send_visibility == 2) ? '' : 'style="display: none"'?> >
                                        <label for="protected_password"><?=$this->lang->line('posts_password')?>:</label>
                                        <br>
                                        <input type="text" name="protected_password" id="protected_password" class="form-control password-text-field" value="<?=set_value('protected_password')?>">
                                    </div>
                                    <div class="form-group ini-dis-flex">
                                        <input class="visibility" type="radio" name="visibility" value="3" id="private" <?=set_radio("visibility", 3)?>>
                                        <label for="private"><?=$this->lang->line('posts_private')?></label>
                                    </div>
                                </span>
                                <a href="#" id="save-visibility" class="save-post-status"><?=$this->lang->line('posts_ok')?></a>
                                <a href="#" id="cancel-visibility" class="cancel-post-status"><?=$this->lang->line('posts_cancel')?></a>
                            </div>
                        </div>
                        <div class="post-status">
                            <i class="fa fa-calendar-o"></i>
                            <?=$this->lang->line('posts_publish')?>: 
                            <span id="publish_message" class="bold">
                                <?php
                                    if($send_publish) {
                                        if($send_date_status == 'same')
                                            echo $this->lang->line('posts_immediately');
                                        else 
                                            echo $send_date_status;
                                    }

                                ?>
                            </span>
                            <a href="#" class="post-edit" id="publish-edit"><?=$this->lang->line('posts_edit')?></a>
                            <div class="post-status-select" id="publish-edit-show">
                                <?php
                                    $monthArray = array(
                                        '01' => '01-Jan',
                                        '02' => '02-Feb',
                                        '03' => '03-Mar',
                                        '04' => '04-Apr',
                                        '05' => '05-May',
                                        '06' => '06-Jun',
                                        '07' => '07-Jul',
                                        '08' => '08-Aug',
                                        '09' => '09-Sep',
                                        '10' => '10-Oct',
                                        '11' => '11-Nov',
                                        '12' => '12-Dec',
                                    );
                                    $addClass = '';
                                    if(form_error('publish_month') || form_error('publish_day') || form_error('publish_year')) {
                                        $addClass = 'date-error-color';
                                    }

                                    
                                    echo form_dropdown("publish_month", $monthArray, set_value("publish_month", date("m")), "id='publish_month' class='".$addClass."'"); 
                                ?>
                                <label class="sr-only">Day</label>
                                <input id="publish_day" name="publish_day" type="text" value="<?=set_value('publish_day', date("d"))?>" size="2" class="<?=$addClass?>" maxlength="2">
                                <label class="sr-only">year</label>
                                <input id="publish_year" name="publish_year" type="text" value="<?=set_value('publish_year', date("Y"))?>" size="4" class="<?=$addClass?>" maxlength="4">
                                <span>@</span>
                                <input id="publish_hour" name="publish_hour" type="text" value="<?=set_value('publish_hour', date("H"))?>" size="2" class="<?=$addClass?>" maxlength="2">
                                <span>:</span>
                                <input id="publish_minute" name="publish_minute" type="text" value="<?=set_value('publish_minute', date("i"))?>" size="2" class="<?=$addClass?>" maxlength="2">
                                
                                <a href="#" id="save-publish" class="save-post-status"><?=$this->lang->line('posts_ok')?></a>
                                <a href="#" id="cancel-publish" class="cancel-post-status"><?=$this->lang->line('posts_cancel')?></a>
                            </div>
                        </div>
                    </div>
                    <div id="publish-box-footer" class="publish-btn text-right">
                        <input type="submit" name="submit" value="<?=$this->lang->line('posts_publish')?>">
                    </div>
                </div>

                <div class="bg-white mb-15">
                    <div class="sidebar--header">
                        <h4><?=$this->lang->line('posts_categories')?></h4>
                        <span id="category" class="flipup">
                            <i class="fa fa-caret-up"></i>
                        </span>
                    </div>
                    <div class="sidebar-body" id="category-box">
                        <div class="">
                            <div class="all-category-list">
                
                            <?php
                                if(count($posts_categories)) {

                                    foreach ($posts_categories as $pCkey => $posts_categorie) {
                            ?>
                                    <div class="form-group ini-dis-flex">
                                        <input id="<?=$posts_categorie->posts_categories?>" class="categories" name="categories[]" value="<?=$posts_categorie->posts_categoriesID?>" type="checkbox"  <?php if(count($send_category)) { if(in_array($posts_categorie->posts_categoriesID, $send_category)) { echo 'checked'; } } ?> >
                                        <label for="<?=$posts_categorie->posts_categories?>"><?=$posts_categorie->posts_categories?></label>
                                    </div>
                            <?php
                                    }
                                }
                            ?>
                            </div>
                            
                            <span id="category-add" class="featured-image-btn">
                                <b>+ <?=$this->lang->line('posts_add_new_category')?></b>
                            </span>
                            
                            <div style="margin-top:10px" id="category-edit-show" class="post-status-select">
                                <div class="form-group">
                                    <input type="text" name="categoryitem" id="categoryitem" class="form-control password-text-field">
                                </div>

                                <a id="save-category" class="save-post-status text-t" href="#"><?=$this->lang->line('posts_add_new_category')?></a>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="bg-white mb-15">
                    <div class="sidebar--header">
                        <h4><?=$this->lang->line('posts_featured_image')?></h4>
                        <span id="featuredimage" class="flipup">
                            <i class="fa fa-caret-up"></i>
                        </span>
                    </div>
                    <div class="sidebar-body" id="featuredimage-box">
                        <div class="feauret-img-show" <?=count($send_featured_image) ? 'data-toggle="modal" data-target="#SetFeaturedImage"' : '' ?> >
                            <?php
                                if(count($send_featured_image)) {
                                    echo '<img src="'.base_url('uploads/gallery/'.$send_featured_image->file_name).'" widht="100%">';
                                }
                            ?>
                        </div>
                        <input type="text" id="featured_image" name="featured_image" class="hide" value="<?=set_value('featured_image')?>">
                        <span class="featured-image-btn <?=count($send_featured_image) ? 'hide' : ''?>" data-toggle="modal" data-target="#SetFeaturedImage" id="set-featured-img"><?=$this->lang->line('posts_set_featured_image')?></span>
                        <span class="featured-image-btn <?=!count($send_featured_image) ? 'hide' : ''?>" onclick="removeFeatureImage(this, 'featured_image', 'feauret-img-show')"  id="remove-set-featured-img"><?=$this->lang->line('posts_remove_featured_image')?></span>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</form>

<div class="modal fade mediaLibrary" id="mediaLibrary">
  <div class="modal-dialog">
    <div class="modal-content">
        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="modal-body">
            <div class="nav-tabs-custom media-sidebar clearfix">
                <ul class="pull-left media-sidebar-nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#insertMedia"><?=$this->lang->line('posts_insert_media')?></a></li>
                    <li class=""><a data-toggle="tab" href="#createGallery"><?=$this->lang->line('posts_create_gallery')?></a></li>
                    <li class=""><a data-toggle="tab" href="#createAudioPlaylist"><?=$this->lang->line('posts_create_audio_playlist')?></a></li>
                    <li class=""><a data-toggle="tab" href="#createVideoPlaylist"><?=$this->lang->line('posts_create_video_playlist')?></a></li>
                    <li class=""><a data-toggle="tab" href="#featuredImage"><?=$this->lang->line('posts_featured_image')?></a></li>
                </ul>

                <div class="tab-content media-sidebar-content pull-left">
                    <!-- Start Insert Media -->
                    <div id="insertMedia" class="tab-pane active">
                        <h2><?=$this->lang->line('posts_insert_media')?></h2>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#insertMediaUpload"><?=$this->lang->line('posts_upload_files')?></a></li>
                                <li><a data-toggle="tab" href="#insertMediaUploadLibrary"><?=$this->lang->line('posts_media_library')?></a></li>
                            </ul>

                            <div class="tab-content no-padd">
                                <div id="insertMediaUpload" class="tab-pane active">
                                    <div class="drop--files">
                                        <form id="insert-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                            <input type="text" name="media_gallery_type" value="1" style="display: none">
                                            <input type="text" name="focus_id" value="insertMedia" style="display: none">
                                            <div class="fileupload-input-group">
                                                <span class="fileupload-input-group-btn">
                                                    <div class="fileupload-image-preview-input">
                                                        <?=$this->lang->line('posts_select_file')?>
                                                        <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif, .mp3,audio/*, video/mp4,video/x-m4v,video/*" name="file"/>
                                                    </div>
                                                </span>
                                            </div>
                                        </form>
                                        <div class="post--upload--info">
                                            <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                        </div>
                                    </div>
                                </div>

                                <div id="insertMediaUploadLibrary" class="tab-pane clearfix">
                                    <div class="media-library-left pos-rel pull-left">
                                        <div class="header--select clearfix">
                                            <div class="search-box-right pull-right">
                                                <form action="#" method="POST">
                                                    <input type="search" name="media-search" placeholder="<?=$this->lang->line('posts_search_media_items')?>...">
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
                                                                <?=$this->lang->line('posts_select_file')?>
                                                                <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif, .mp3,audio/*, video/mp4,video/x-m4v,video/*" name="file"/>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </form>
                                                <div class="post--upload--info">
                                                    <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
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
                                    <a data-dismiss="modal" id="insert_into_page" onclick="setFileToEditor(this, 'insert_media_hidden_field', 'insert_media_type');"  href="#"><?=$this->lang->line('posts_insert_into_page')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Close Insert Media -->

                    <!-- Start Create Gallery -->
                    <div id="createGallery" class="tab-pane">
                        <h2><?=$this->lang->line('posts_create_gallery')?></h2>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#createGalleryMediaUpload"><?=$this->lang->line('posts_upload_files')?></a></li>
                                <li><a data-toggle="tab" href="#createGalleryMediaUploadLibrary"><?=$this->lang->line('posts_media_library')?></a></li>
                            </ul>

                            <div class="tab-content no-padd">
                                <div id="createGalleryMediaUpload" class="tab-pane active">
                                    <div class="drop--files">
                                        <form id="create_gallery-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                            <input type="text" name="media_gallery_type" value="2" style="display: none">
                                            <input type="text" name="focus_id" value="createGallery" style="display: none">
                                            <div class="fileupload-input-group">
                                                <span class="fileupload-input-group-btn">
                                                    <div class="fileupload-image-preview-input">
                                                        <?=$this->lang->line('posts_select_file')?>
                                                        <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif" name="file"/>
                                                    </div>
                                                </span>
                                            </div>
                                        </form>
                                        <div class="post--upload--info">
                                            <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                        </div>
                                    </div>
                                </div>

                                <div id="createGalleryMediaUploadLibrary" class="tab-pane clearfix">
                                    <div class="media-library-left pos-rel pull-left">
                                        <div class="header--select clearfix">
                                            <div class="search-box-right pull-right">
                                                <form action="#" method="POST">
                                                    <input type="search" name="media-search" placeholder="<?=$this->lang->line('posts_search_media_items')?>...">
                                                </form>
                                            </div>
                                        </div>
                                        <?php 
                                            if(count($media_gallerys_images)) { 
                                                echo '<div class="attached-preview">';
                                                    echo '<ul class="create_gallery_type">';
                                                foreach ($media_gallerys_images as $media_gallerys_image_key => $media_gallerys_image) {
                                        ?>       
                                                    <li class="create_gallery_image" onclick="getFileInfo(this, 'create_gallery_type', 'image',  'multi', 'create_gallery_hidden_field', false, 'create_gallery');" id ="<?=$media_gallerys_image->media_galleryID?>">
                                                        <div class="thumb">
                                                            <img src="<?=base_url('uploads/gallery/'.$media_gallerys_image->file_name)?>" alt="<?=$media_gallerys_image->file_alt_text?>">
                                                        </div>
                                                    </li>
                                        <?php 
                                                }
                                                    echo '</ul>';  
                                                echo '</div>';
                                                echo '<input type="text" id="create_gallery_hidden_field" style="display:none">';

                                            } else { ?>
                                            <div class="drop--files">
                                                <form id="create_gallery-fileupload-list" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                                    <input type="text" name="media_gallery_type" value="2" style="display: none">
                                                    <input type="text" name="focus_id" value="createGallery" style="display: none">
                                                    <div class="fileupload-input-group">
                                                        <span class="fileupload-input-group-btn">
                                                            <div class="fileupload-image-preview-input">
                                                                <?=$this->lang->line('posts_select_file')?>
                                                                <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif" name="file"/>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </form>
                                                <div class="post--upload--info">
                                                    <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="media-library-right pull-left">
                                        <div class="attached-details" id="create_gallery_type">

                                        </div>
                                    </div>
                                </div>

                                <div class="footer--upload">
                                    <a data-dismiss="modal" id="create_a_new_gallery" onclick="setFileToEditor(this, 'create_gallery_hidden_field', 'create_gallery_type');"  href="#"><?=$this->lang->line('posts_create_a_new_gallery')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Close Create Gallery -->

                    <!-- Start Create Audio Playlist -->
                    <div id="createAudioPlaylist" class="tab-pane">
                        <h2><?=$this->lang->line('posts_create_audio_playlist')?></h2>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#createAudioPlaylistMediaUpload"><?=$this->lang->line('posts_upload_files')?></a></li>
                                <li><a data-toggle="tab" href="#createAudioPlaylistMediaUploadLibrary"><?=$this->lang->line('posts_media_library')?></a></li>
                            </ul>

                            <div class="tab-content no-padd">
                                <div id="createAudioPlaylistMediaUpload" class="tab-pane active">
                                    <div class="drop--files">
                                        <form id="create_audio_playlist-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                            <input type="text" name="media_gallery_type" value="3" style="display: none">
                                            <input type="text" name="focus_id" value="createAudioPlaylist" style="display: none">
                                            <div class="fileupload-input-group">
                                                <span class="fileupload-input-group-btn">
                                                    <div class="fileupload-image-preview-input">
                                                        <?=$this->lang->line('posts_select_file')?>
                                                        <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept=".mp3,audio/*" name="file"/>
                                                    </div>
                                                </span>
                                            </div>
                                        </form>
                                        <div class="post--upload--info">
                                            <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                        </div>
                                    </div>
                                </div>

                                <div id="createAudioPlaylistMediaUploadLibrary" class="tab-pane clearfix">
                                    <div class="media-library-left pos-rel pull-left">
                                        <div class="header--select clearfix">
                                            <div class="search-box-right pull-right">
                                                <form action="#" method="POST">
                                                    <input type="search" name="media-search" placeholder="<?=$this->lang->line('posts_search_media_items')?>...">
                                                </form>
                                            </div>
                                        </div>
                                        <?php 
                                            if(count($media_gallerys_audios)) { 
                                                echo '<div class="attached-preview">';
                                                    echo '<ul class="create_video_playlist_type">';
                                                foreach ($media_gallerys_audios as $media_gallerys_audio_key => $media_gallerys_audio) {
                                        ?>       
                                                    <li class="create_audio_playlist_audio" onclick="getFileInfo(this, 'create_audio_playlist_type', 'audio', 'multi', 'create_audio_playlist_hidden_field', false, 'create_audio_playlist');" id ="<?=$media_gallerys_audio->media_galleryID?>">
                                                        <div class="thumb">
                                                            <i class="fa fa-file-audio-o"></i>
                                                        </div>
                                                        <div class="video-title">
                                                            <p><?=namesorting($media_gallerys_audio->file_original_name, 50)?></p>
                                                        </div>
                                                    </li>
                                        <?php 
                                                }
                                                    echo '</ul>';  
                                                echo '</div>';
                                                echo '<input type="text" id="create_audio_playlist_hidden_field" style="display:none">';

                                            } else { ?>
                                            <div class="drop--files">
                                                <form id="create_audio_playlist-fileupload-list" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                                    <input type="text" name="media_gallery_type" value="3" style="display: none">
                                                    <input type="text" name="focus_id" value="createAudioPlaylist" style="display: none">
                                                    <div class="fileupload-input-group">
                                                        <span class="fileupload-input-group-btn">
                                                            <div class="fileupload-image-preview-input">
                                                                <?=$this->lang->line('posts_select_file')?>
                                                                <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept=".mp3,audio/*" name="file"/>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </form>
                                                <div class="post--upload--info">
                                                    <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="media-library-right pull-left">
                                        <div class="attached-details" id="create_audio_playlist_type">

                                        </div>
                                    </div>
                                </div>

                                <div class="footer--upload">
                                    <a data-dismiss="modal" id="create_a_new_playlist" onclick="setFileToEditor(this, 'create_audio_playlist_hidden_field', 'create_video_playlist_type');" href="#"><?=$this->lang->line('posts_create_a_new_playlist')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Close Create Audio Playlist -->

                    <!-- Start Create Video Playlist -->
                    <div id="createVideoPlaylist" class="tab-pane">
                        <h2><?=$this->lang->line('posts_create_video_playlist')?></h2>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#createVideoPlaylistMediaUpload"><?=$this->lang->line('posts_upload_files')?></a></li>
                                <li><a data-toggle="tab" href="#createVideoPlaylistMediaUploadLibrary"><?=$this->lang->line('posts_media_library')?></a></li>
                            </ul>

                            <div class="tab-content no-padd">
                                <div id="createVideoPlaylistMediaUpload" class="tab-pane active">
                                    <div class="drop--files">
                                        <form id="create_video_playlist-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                            <input type="text" name="media_gallery_type" value="4" style="display: none">
                                            <input type="text" name="focus_id" value="createVideoPlaylist" style="display: none">
                                            <div class="fileupload-input-group">
                                                <span class="fileupload-input-group-btn">
                                                    <div class="fileupload-image-preview-input">
                                                        <?=$this->lang->line('posts_select_file')?>
                                                        <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="video/mp4,video/x-m4v,video/*" name="file"/>
                                                    </div>
                                                </span>
                                            </div>
                                        </form>
                                        <div class="post--upload--info">
                                            <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                        </div>
                                    </div>
                                </div>

                                <div id="createVideoPlaylistMediaUploadLibrary" class="tab-pane clearfix">
                                    <div class="media-library-left pos-rel pull-left">
                                        <div class="header--select clearfix">
                                            <div class="search-box-right pull-right">
                                                <form action="#" method="POST">
                                                    <input type="search" name="media-search" placeholder="<?=$this->lang->line('posts_search_media_items')?>...">
                                                </form>
                                            </div>
                                        </div>
                                        <?php 
                                            if(count($media_gallerys_videos)) { 
                                                echo '<div class="attached-preview">';
                                                    echo '<ul class="create_video_playlist_type">';
                                                foreach ($media_gallerys_videos as $media_gallerys_video_key => $media_gallerys_video) {
                                        ?>       
                                                    <li class="create_video_playlist_video" onclick="getFileInfo(this, 'create_video_playlist_type', 'video', 'multi', 'create_video_playlist_hidden_field', false, 'create_video_playlist');" id ="<?=$media_gallerys_video->media_galleryID?>">
                                                        <div class="thumb">
                                                            <i class="fa fa-file-video-o"></i>
                                                        </div>
                                                        <div class="video-title">
                                                            <p><?=namesorting($media_gallerys_video->file_original_name, 50)?></p>
                                                        </div>
                                                    </li>
                                        <?php 
                                                }
                                                    echo '</ul>';  
                                                echo '</div>';
                                                echo '<input type="text" id="create_video_playlist_hidden_field" style="display:none">';

                                            } else { ?>
                                            <div class="drop--files">
                                                <form id="create_video_playlist-fileupload-list" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                                    <input type="text" name="media_gallery_type" value="4" style="display: none">
                                                    <input type="text" name="focus_id" value="createVideoPlaylist" style="display: none">
                                                    <div class="fileupload-input-group">
                                                        <span class="fileupload-input-group-btn">
                                                            <div class="fileupload-image-preview-input">
                                                                <?=$this->lang->line('posts_select_file')?>
                                                                <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="video/mp4,video/x-m4v,video/*" name="file"/>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </form>
                                                <div class="post--upload--info">
                                                    <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="media-library-right pull-left">
                                        <div class="attached-details" id="create_video_playlist_type">

                                        </div>
                                    </div>
                                </div>

                                <div class="footer--upload">
                                    <a data-dismiss="modal" id="create_a_new_video_playlist" onclick="setFileToEditor(this, 'create_video_playlist_hidden_field', 'create_video_playlist_type');" href="#"><?=$this->lang->line('posts_create_a_new_video_playlist')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Close Create Video Playlist -->

                    <!-- Start Create Featured Image -->
                    <div id="featuredImage" class="tab-pane">
                        <h2><?=$this->lang->line('posts_featured_image')?></h2>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#featuredImageMediaUpload"><?=$this->lang->line('posts_upload_files')?></a></li>
                                <li><a data-toggle="tab" href="#featuredImageMediaUploadLibrary"><?=$this->lang->line('posts_media_library')?></a></li>
                            </ul>

                            <div class="tab-content no-padd">
                                <div id="featuredImageMediaUpload" class="tab-pane active">
                                    <div class="drop--files">
                                        <form id="featured_image-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                            <input type="text" name="media_gallery_type" value="2" style="display: none">
                                            <input type="text" name="focus_id" value="featuredImage" style="display: none">
                                            <div class="fileupload-input-group">
                                                <span class="fileupload-input-group-btn">
                                                    <div class="fileupload-image-preview-input">
                                                        <?=$this->lang->line('posts_select_file')?>
                                                        <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif" name="file"/>
                                                    </div>
                                                </span>
                                            </div>
                                        </form>
                                        <div class="post--upload--info">
                                            <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                        </div>
                                    </div>
                                </div>

                                <div id="featuredImageMediaUploadLibrary" class="tab-pane clearfix">
                                    <div class="media-library-left pos-rel pull-left">
                                        <div class="header--select clearfix">
                                            <div class="search-box-right pull-right">
                                                <form action="#" method="POST">
                                                    <input type="search" name="media-search" placeholder="<?=$this->lang->line('posts_search_media_items')?>...">
                                                </form>
                                            </div>
                                        </div>
                                        <?php 
                                            if(count($media_gallerys_images)) { 
                                                echo '<div class="attached-preview">';
                                                    echo '<ul class="featured_image_type">';
                                                foreach ($media_gallerys_images as $media_gallerys_image_key => $media_gallerys_image) {
                                        ?>       
                                                    <li class="featured_image_image_info" onclick="getFileInfo(this, 'featured_image_type', 'image',  'single', 'featured_image_hidden_field', false, 'featured_image');" id ="<?=$media_gallerys_image->media_galleryID?>">
                                                        <div class="thumb">
                                                            <img src="<?=base_url('uploads/gallery/'.$media_gallerys_image->file_name)?>" alt="<?=$media_gallerys_image->file_alt_text?>">
                                                        </div>
                                                    </li>
                                        <?php 
                                                }
                                                    echo '</ul>';  
                                                echo '</div>';
                                                echo '<input type="text" id="featured_image_hidden_field" style="display:none">';

                                            } else { ?>
                                            <div class="drop--files">
                                                <form id="featured_image-fileupload-list" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                                    <input type="text" name="media_gallery_type" value="2" style="display: none">
                                                    <input type="text" name="focus_id" value="featuredImage" style="display: none">
                                                    <div class="fileupload-input-group">
                                                        <span class="fileupload-input-group-btn">
                                                            <div class="fileupload-image-preview-input">
                                                                <?=$this->lang->line('posts_select_file')?>
                                                                <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif" name="file"/>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </form>
                                                <div class="post--upload--info">
                                                    <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="media-library-right pull-left">
                                        <div class="attached-details" id="featured_image_type">
                                            

                                        </div>
                                    </div>
                                </div>

                                <div class="footer--upload">
                                    <a data-dismiss="modal" id="set_featured_image" onclick="setFileToEditor(this, 'featured_image_hidden_field', 'featured_image_type');" href="#"><?=$this->lang->line('posts_set_featured_image')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Close Create Featured Image -->
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="modal fade mediaLibrary new-pattern" id="SetFeaturedImage">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-body">
                <div id="setfeaturedImage" class="tab-pane">
                    <h2><?=$this->lang->line('posts_featured_image')?></h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#setfeaturedImageMediaUpload"><?=$this->lang->line('posts_upload_files')?></a></li>
                            <li><a data-toggle="tab" href="#setfeaturedImageMediaUploadLibrary"><?=$this->lang->line('posts_media_library')?></a></li>
                        </ul>

                        <div class="tab-content no-padd">
                            <div id="setfeaturedImageMediaUpload" class="tab-pane active">
                                <div class="drop--files">
                                    <form id="set_featured_image-fileupload" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                        <input type="text" name="media_gallery_type" value="2" style="display: none">
                                        <input type="text" name="focus_id" value="setfeaturedImage" style="display: none">
                                        <div class="fileupload-input-group">
                                            <span class="fileupload-input-group-btn">
                                                <div class="fileupload-image-preview-input">
                                                    <?=$this->lang->line('posts_select_file')?>
                                                    <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif" name="file"/>
                                                </div>
                                            </span>
                                        </div>
                                    </form>
                                    <div class="post--upload--info">
                                        <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                    </div>
                                </div>
                            </div>

                            <div id="setfeaturedImageMediaUploadLibrary" class="tab-pane clearfix">
                                <div class="media-library-left pos-rel pull-left">
                                    <div class="header--select clearfix">
                                        <div class="search-box-right pull-right">
                                            <form action="#" method="POST">
                                                <input type="search" name="media-search" placeholder="<?=$this->lang->line('posts_search_media_items')?>...">
                                            </form>
                                        </div>
                                    </div>
                                    <?php 
                                        if(count($media_gallerys_images)) { 
                                            echo '<div class="attached-preview">';
                                                echo '<ul class="set_featured_image_type">';
                                            foreach ($media_gallerys_images as $media_gallerys_image_key => $media_gallerys_image) {
                                    ?>       
                                                <li class="set_featured_image_image_info" onclick="getFileInfo(this, 'set_featured_image_type', 'image',  'single', 'set_featured_image_hidden_field', false, 'set_featured_image');" id ="<?=$media_gallerys_image->media_galleryID?>">
                                                    <div class="thumb">
                                                        <img src="<?=base_url('uploads/gallery/'.$media_gallerys_image->file_name)?>" alt="<?=$media_gallerys_image->file_alt_text?>">
                                                    </div>
                                                </li>
                                    <?php 
                                            }
                                                echo '</ul>';  
                                            echo '</div>';
                                            echo '<input type="text" id="set_featured_image_hidden_field" style="display:none">';

                                        } else { ?>
                                        <div class="drop--files">
                                            <form id="set_featured_image-fileupload-list" class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
                                                <input type="text" name="media_gallery_type" value="2" style="display: none">
                                                <input type="text" name="focus_id" value="setfeaturedImage" style="display: none">
                                                <div class="fileupload-input-group">
                                                    <span class="fileupload-input-group-btn">
                                                        <div class="fileupload-image-preview-input">
                                                            <?=$this->lang->line('posts_select_file')?>
                                                            <input onchange="fileUpload(this);" class="fileupload-event-image" type="file" accept="image/png, image/jpeg, image/gif" name="file"/>
                                                        </div>
                                                    </span>
                                                </div>
                                            </form>
                                            <div class="post--upload--info">
                                                <p><?=$this->lang->line('posts_maximum_upload_file_size')?></p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="media-library-right pull-left">
                                    <div class="attached-details" id="set_featured_image_type">
                                        

                                    </div>
                                </div>
                            </div>

                            <div class="footer--upload">
                                <a id="set_featured_image" data-dismiss="modal" onclick="setFileToEditor(this, 'set_featured_image_hidden_field', 'set_featured_image_type');" href="#"><?=$this->lang->line('posts_set_featured_image')?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="margin-bottom: 50px"></div>


<?php if($this->session->flashdata('pageSubmitType')) { 
    $url = base_url('frontend/post/'.$this->session->flashdata('sesPageUrl'));
    redirect($url);
} ?>

<script type="text/javascript">

    $(document).on('blur', '#file_title, #file_artist, #file_album, #file_caption, #file_alt_text, #file_description', function(){
        var file_title          = $('#file_title').val();
        var file_artist         = $('#file_artist').val();
        var file_album          = $('#file_album').val();
        var file_caption        = $('#file_caption').val();
        var file_alt_text       = $('#file_alt_text').val();
        var file_description    = $('#file_description').val();
        var hidden_id_field    = $('#hidden-id-field').val();

        var setFileInfoBind = { 
            'hidden_id_field' : hidden_id_field,
            'file_title' : file_title, 
            'file_artist' : file_artist, 
            'file_album' : file_album,
            'file_caption' : file_caption,
            'file_alt_text' : file_alt_text,
            'file_description' : file_description 
        } ;

        $.ajax({
            type: 'POST',
            url: "<?=base_url('posts/setFileInfo')?>",
            data: setFileInfoBind,
            dataType: "html"
        });

    });

    function stringPermalink(title) {
        title = title.trim();
        title = title.toLowerCase();
        title = title.replace(/[^a-zA-Z0-9]/g,' ');

        var newString = '';
        var f = 0;
        for (var i = 0, len = title.length; i < len; i++) {
            if(title[i] != ' ') {
                newString += title[i];
                f = 0; 
            } else if( title[i] == ' ' && f == 0) {
                newString += '-';
                f = 1;
            }
        }
        return newString;
    }

    $(document).ready(function() {
        $("#title").blur(function(){ 
            var title = $(this).val();
            if(title != '') {
                var url = $('#url').val();
                if(url == '') {
                    title = stringPermalink(title);

                    $('.permalink-area').show();
                    $('.editable-permalink-name').text(title);
                    $('#url').val(title);
                }
            }
        });

        $('#permalink-edit').click(function() {
            $('#editable-permalink-section').show();
            $('#permalink-edit').hide();
            $('.editable-permalink-name').hide();

            var editablePermalinkName = $('.editable-permalink-name').text();
            editablePermalinkName = stringPermalink(editablePermalinkName);
            $('#url').val(editablePermalinkName).show();
            $('.permalink').removeClass('permalink').addClass('permalink-edit');
        });

        $('#save-permalink').click(function() {
            var url = $('#url').val();
            url = stringPermalink(url);
            $('.editable-permalink-name').text(url).show();
            $('#url').val(url).show();
            $('#url').hide();
            $('#editable-permalink-section').hide();
            $('#permalink-edit').show();
            $('.permalink-edit').removeClass('permalink-edit').addClass('permalink');
        });

        $('#cancel-permalink').click(function() {
            $('.editable-permalink-name').show();
            $('#url').hide();
            $('#editable-permalink-section').hide();
            $('#permalink-edit').show();
            $('.permalink-edit').removeClass('permalink-edit').addClass('permalink');
        });
    });


    function audioPlaylist(ele, playBox, track, playerID, playlistID) {
        $('#' + playerID).attr('src', track);
        document.getElementById(playerID).play();
        $('#' + playlistID).find('li.active').removeClass('active');
        $(ele).parent().addClass('active'); 
        var trackName = $(ele).find('div.item__title').text();
        $('#'+playBox).find('span.npTitle').text(trackName);
    }


    function videoPlaylist(ele, playBox, track, playerID, playlistID) {
        $('#' + playerID).attr('src', track);
        document.getElementById(playerID).play();
        $('#' + playlistID).find('li.active').removeClass('active');
        $(ele).parent().addClass('active'); 
    }                  

    function setFileToEditor(ele, hiddenfield, ulClass) {
        var hiddenfield = $('#' + hiddenfield).val();
        var id = $(ele).attr('id');
        if((hiddenfield != '' && hiddenfield != null) && (ulClass !='' && ulClass != null)) {
            var setAllDataBind = {
                'allID' : hiddenfield,
                'ulclass_type' : ulClass,
                'media_type' : 1, 
                'send_status' : 'defined', 
                'file_title' : $('#file_title').val(),
                'file_artist' : $('#file_artist').val(),
                'file_album' : $('#file_album').val(),
                'file_caption' : $('#file_caption').val(),
                'file_alt_text' : $('#file_alt_text').val(),
                'file_description' : $('#file_description').val(),
            }

            $.ajax({
                dataType: "json", 
                type: 'POST',
                url: "<?=base_url('posts/setFileToEditor')?>",
                data: setAllDataBind,
                dataType: "html",
                success: function(data) {
                    var response = jQuery.parseJSON(data);

                    if(response.status) {
                        $('#insertMedia').children().remove();
                        $('#createGallery').children().remove();
                        $('#createAudioPlaylist').children().remove();
                        $('#createVideoPlaylist').children().remove();
                        $('#featuredImage').children().remove();
                        $('#setfeaturedImage').children().remove();
                        $('#setSliderImages').children().remove();

                        $('#insertMedia').append(response.insert_media_content);
                        $('#createGallery').append(response.create_gallery_content);
                        $('#createVideoPlaylist').append(response.create_video_playlist_content);
                        $('#createAudioPlaylist').append(response.create_audio_playlist_content);
                        $('#featuredImage').append(response.featured_image_content);
                        $('#setfeaturedImage').append(response.set_featured_image_content);
                        $('#setSliderImages').append(response.set_slider_images_content);
                    }

                    if(id == 'insert_into_page') {
                        if(response.status) {
                            var oldData = $('.note-editable').html();
                            $('#write-content').summernote('code', oldData + response.content);
                        }
                    } else if(id == 'create_a_new_gallery') {
                        if(response.status) {
                            var oldData = $('.note-editable').html();
                            $('#write-content').summernote('code', oldData + response.content);
                        }
                    } else if(id == 'create_a_new_playlist') {
                        if(response.status) {
                            var oldData = $('.note-editable').html();
                            $('#write-content').summernote('code', oldData + response.content);
                        }
                    } else if(id == 'create_a_new_video_playlist') {
                        if(response.status) {
                            var oldData = $('.note-editable').html();
                            $('#write-content').summernote('code', oldData + response.content);
                        }
                    } else if(id == 'set_featured_image') {
                        if(response.status) {
                            var img = response.content;
                            // var rep = img.replace('height', 'xxx');
                            $('#featured_image').val(hiddenfield);
                            $('.feauret-img-show').html(img).attr('data-toggle', 'modal').attr('data-target', '#SetFeaturedImage');
                            $('#set-featured-img').addClass('hide');
                            $('#remove-set-featured-img').removeClass('hide');
                        }
                    } else if(id == 'set_slider_images') {
                        $('#hidden_slider_images').val(hiddenfield);
                        $('.slider-pluck').children().remove();
                        $('.slider-pluck').html(response.content);
                    }
                }
            });


        } else {
            toastr["error"]("Please select one.")
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "500",
                "hideDuration": "500",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    }
    
    function fileUpload(ele) {
        var getID =  $(ele).parent().parent().parent().parent().attr('id');
        var formData = new FormData($('#' + getID)[0]);
        
        $.ajax({
            dataType: "json",           
            type: 'POST',
            url: "<?=base_url('posts/fileUpload')?>",
            data: formData,
            async: true,
            dataType: "html",
            success: function(data) {
                var response = jQuery.parseJSON(data);
                if(response.status == true) {
                    $('#insertMedia').children().remove();
                    $('#createGallery').children().remove();
                    $('#createAudioPlaylist').children().remove();
                    $('#createVideoPlaylist').children().remove();
                    $('#featuredImage').children().remove();
                    $('#setfeaturedImage').children().remove();
                    $('#setSliderImages').children().remove();

                    $('#insertMedia').append(response.insert_media_content);
                    $('#createGallery').append(response.create_gallery_content);
                    $('#createVideoPlaylist').append(response.create_video_playlist_content);
                    $('#createAudioPlaylist').append(response.create_audio_playlist_content);
                    $('#featuredImage').append(response.featured_image_content);
                    $('#setfeaturedImage').append(response.set_featured_image_content);
                    $('#setSliderImages').append(response.set_slider_images_content);
                } else {
                    toastr["error"](response.message)
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "500",
                        "hideDuration": "500",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    };

    function getFileInfo(ele, activeType, filetype, status, hiddenfield, combind, combindname) {
        var id = $(ele).attr('id');
        if($(ele).hasClass("selected") == false) {
            if(combind == true) {
                $('.'+combindname+'_image').find('button:first').remove();
                $('.'+combindname+'_image').removeClass('selected');

                $('.'+combindname+'_audio').find('button:first').remove();
                $('.'+combindname+'_audio').removeClass('selected');

                $('.'+combindname+'_video').find('button:first').remove();
                $('.'+combindname+'_video').removeClass('selected');
            }

            if(filetype == 'image') {
                var media_type = 1;
                var selectData = '<button type="button" class="check"><span class="media-modal-icon"></span><span class="sr-only">Deselect</span></button>';
                var childrenData = $(ele).children().html();
                if(status == 'single') {
                    $('.'+$(ele).attr('class')).find('button:first').remove();
                    $('.'+$(ele).attr('class')).removeClass('selected');
                    $(ele).addClass('selected');
                    $(ele).children().html(childrenData+selectData);

                    $('#'+hiddenfield).val('');
                    $('#'+hiddenfield).val(id);
                } else if(status == 'multi') {
                    $(ele).addClass('selected');
                    $(ele).children().html(childrenData+selectData);
                    var getHiddenData = $('#'+hiddenfield).val();
                    $('#'+hiddenfield).val(getHiddenData + ','+id);
                }
            } else if(filetype == 'audio' || filetype == 'video') {
                if(filetype == 'audio') { var icon = 'fa fa-file-audio-o'; var media_type = 2; } else { icon = 'fa fa-file-video-o'; var media_type = 3; }
                var icon_name = '<i class="'+icon+'"></i>';
                var selectData = '<button type="button" class="check"><span class="media-modal-icon"></span><span class="sr-only">Deselect</span></button>';
                var childrenData = $(ele).find('div:last').html();
                var video_name = '<div class="video-title">' + $(ele).find('div:last').html() + '</div>';
                if(status == 'single') {
                    $('.'+$(ele).attr('class')).find('button:first').remove();
                    $('.'+$(ele).attr('class')).removeClass('selected');
                    $(ele).addClass('selected');
                    $(ele).html('<div class="thumb">'+icon_name + selectData +'</div>' + video_name);

                    $('#'+hiddenfield).val('');
                    $('#'+hiddenfield).val(id);
                } else if(status == 'multi') {
                    $(ele).addClass('selected');
                    $(ele).html('<div class="thumb">'+icon_name + selectData +'</div>' + video_name);
                    var getHiddenData = $('#'+hiddenfield).val();
                    $('#'+hiddenfield).val(getHiddenData + ','+id);
                }
            }


            if (typeof $('#file_url').val() == 'undefined') {
                var setAllDataBind = {"id":id, "media_type" : media_type, 'send_status' : 'undefined'} ;
            } else {
                var setAllDataBind = {
                    'id' : id, 
                    'media_type' : media_type, 
                    'send_status' : 'defined', 
                    'file_title' : $('#file_title').val(),
                    'file_artist' : $('#file_artist').val(),
                    'file_album' : $('#file_album').val(),
                    'file_caption' : $('#file_caption').val(),
                    'file_alt_text' : $('#file_alt_text').val(),
                    'file_description' : $('#file_description').val(),
                };
            }


            $.ajax({
                dataType: "json", 
                type: 'POST',
                url: "<?=base_url('posts/getFileInfo')?>",
                data: setAllDataBind,
                dataType: "html",
                success: function(data) {
                    var response = jQuery.parseJSON(data);
                    if(response.file_status == true) {
                        $('#' + activeType).children().remove();
                        $('#' + activeType).html(response.content);
                    }
                },
            });
        } else {
            $('#' + activeType).children().remove();
            if(filetype == 'image') {
                if(status == 'single') {
                    $(ele).find('button:first').remove();
                    $(ele).removeClass('selected');
                    $('#'+hiddenfield).val('');
                } else if(status == 'multi') {
                    $(ele).find('button:first').remove();
                    $(ele).removeClass('selected');

                    var getHiddenData = $('#'+hiddenfield).val();
                    var setHiddenData = getHiddenData.replace(","+id, '');
                    $('#' + hiddenfield).val(setHiddenData);
                }
            } else if(filetype == 'audio' || filetype == 'video') {
                if(status == 'single') {
                    $(ele).find('button:first').remove();
                    $(ele).removeClass('selected');
                    $('#'+hiddenfield).val('');
                } else if(status == 'multi') {
                    $(ele).find('button:first').remove();
                    $(ele).removeClass('selected');

                    var getHiddenData = $('#'+hiddenfield).val();
                    var setHiddenData = getHiddenData.replace(","+id, '');
                    $('#' + hiddenfield).val(setHiddenData);
                }
            }
        }
    }

    function deleteFileInfo(ele) {
        var id = $(ele).attr('id');
        if(id) {
            $.ajax({
                dataType: "json", 
                type: "POST",
                url: "<?=base_url('posts/deleteFileInfo')?>",
                data: { "id":id },
                dataType: "html",
                success: function(data) {
                    var response = jQuery.parseJSON(data);
                    if(response.status == true) {
                        $('#insertMedia').children().remove();
                        $('#createGallery').children().remove();
                        $('#createAudioPlaylist').children().remove();
                        $('#createVideoPlaylist').children().remove();
                        $('#featuredImage').children().remove();
                        $('#setfeaturedImage').children().remove();
                        $('#setSliderImages').children().remove();

                        $('#insertMedia').append(response.insert_media_content);
                        $('#createGallery').append(response.create_gallery_content);
                        $('#createAudioPlaylist').append(response.create_audio_playlist_content);
                        $('#createVideoPlaylist').append(response.create_video_playlist_content);
                        $('#featuredImage').append(response.featured_image_content);
                        $('#setfeaturedImage').append(response.set_featured_image_content);
                        $('#setSliderImages').append(response.set_slider_images_content);
                    } else {
                        toastr["error"](response.message)
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "500",
                            "hideDuration": "500",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                }
            });
        }
    }


    (function($) {

        $(document).on('click', '#category-add', function() {
            $('#category-edit-show').toggle();
        });

        $(".post-status-select").hide();
        $('.post-edit').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $('#'+id +'-show').show();
            $('#' + id).hide();
        });

        $('.save-post-status').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            if(id == 'save-status') {
                var saveStatusValue = $('#status').val();

                if(saveStatusValue == 'draft') {
                    saveStatusValue = "<?=$this->lang->line('posts_draft')?>";
                    btnSaveStatus = "draft";
                    btnInSaveStatus = "<?=$this->lang->line('posts_save_draft')?>";
                } else if(saveStatusValue == 'review') {
                    saveStatusValue = "<?=$this->lang->line('posts_pending_review')?>";
                    btnSaveStatus = "review";
                    btnInSaveStatus = "<?=$this->lang->line('posts_save_as_pending')?>";
                }

                $('.btn-save-status').val(btnSaveStatus);
                $('.btn-save-status').html(btnInSaveStatus);
                $('#status-message').text(saveStatusValue);
                $('#draft-edit-show').hide();
                $('#draft-edit').show();
            } else if(id == 'save-visibility') {
                var saveVisibilityValue = $('input[name=visibility]:checked').val();
                if(saveVisibilityValue == 1) {
                    saveVisibilityValue = "<?=$this->lang->line('posts_public')?>";
                } else if(saveVisibilityValue == 2) {
                    saveVisibilityValue = "<?=$this->lang->line('posts_password_protected')?>";
                } else if(saveVisibilityValue == 3) {
                    saveVisibilityValue = "<?=$this->lang->line('posts_private')?>";
                }
                $('#visibility-message').text(saveVisibilityValue);
                $('#visibility-edit-show').hide();
                $('#visibility-edit').show();
            } else if(id == 'save-publish') {
                var publish_year = $('#publish_year').val();
                var publish_month = $('#publish_month').val();
                var publish_day = $('#publish_day').val();
                var publish_hour = $('#publish_hour').val();
                var publish_minute = $('#publish_minute').val();
                
                var get_date = publish_year+Math.abs(publish_month)+Math.abs(publish_day)+Math.abs(publish_hour)+Math.abs(publish_minute);
                get_date = parseInt(get_date);
                var currentdate = new Date(); 
                var current_date = currentdate.getFullYear()+''+(currentdate.getMonth()+1)+currentdate.getDate()+currentdate.getHours()+currentdate.getMinutes();
                current_date = parseInt(current_date);
                
                var get_date_convator = publish_day+'/'+publish_month+'/'+publish_year+' '+publish_hour+':'+publish_minute;

                if(validDateChecker(get_date_convator)) {
                    if(current_date !== get_date) {
                        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                        if (typeof monthNames[Math.abs(parseInt(publish_month) -1)] !== 'undefined' && monthNames[Math.abs(parseInt(publish_month) -1)] !== null) {
                            $('#publish_message').text(monthNames[Math.abs(parseInt(publish_month) -1)]+' '+publish_day+', '+publish_year+' @ '+publish_hour+':'+publish_minute);
                        } else {
                            $('#publish_message').text("<?=$this->lang->line('posts_immediately')?>");
                        }
                    } else {
                        $('#publish_message').text("<?=$this->lang->line('posts_immediately')?>");
                    }
                    var publish_year = $('#publish_year').removeClass('date-error-color');
                    var publish_month = $('#publish_month').removeClass('date-error-color');
                    var publish_day = $('#publish_day').removeClass('date-error-color');
                    var publish_hour = $('#publish_hour').removeClass('date-error-color');
                    var publish_minute = $('#publish_minute').removeClass('date-error-color');
                    $('#publish-edit-show').hide();
                    $('#publish-edit').show();
                } else {
                    $('#publish_message').text("");
                    var publish_year = $('#publish_year').addClass('date-error-color');
                    var publish_month = $('#publish_month').addClass('date-error-color');
                    var publish_day = $('#publish_day').addClass('date-error-color');
                    var publish_hour = $('#publish_hour').addClass('date-error-color');
                    var publish_minute = $('#publish_minute').addClass('date-error-color');
                }
            } else if(id == 'save-category') {
                var category = $('#categoryitem').val();
                $('#categoryitem').val('');
                if(category != '' && category != null) {
                    $.ajax({
                        dataType: "json", 
                        type: 'POST',
                        url: "<?=base_url('posts/addCategory')?>",
                        data: {'category' : category},
                        dataType: "html",
                        success: function(data) {
                            var response = jQuery.parseJSON(data);
                            if(response.status) {
                                $('.all-category-list').append(response.content);
                            }
                        }
                    });
                }
            }
        });


        function validDateChecker(dt) {
            try {
                /*"dd/MM/yyyy HH:mm"*/
                var isValidDate = false;
                var arr1 = dt.split('/');
                var year=0;var month=0;var day=0;var hour=0;var minute=0;var sec=0;
                if(arr1.length == 3)
                {
                    var arr2 = arr1[2].split(' ');
                    if(arr2.length == 2)
                    {
                        var arr3 = arr2[1].split(':');
                        try{
                            year = parseInt(arr2[0],10);
                            month = parseInt(arr1[1],10);
                            day = parseInt(arr1[0],10);
                            hour = parseInt(arr3[0],10);
                            minute = parseInt(arr3[1],10);
                            //sec = parseInt(arr3[0],10);
                            sec = 0;
                            var isValidTime=false;
                            if(hour >=0 && hour <=23 && minute >=0 && minute<=59 && sec >=0 && sec<=59)
                                isValidTime=true;
                            else if(hour ==24 && minute ==0 && sec==0)
                                isValidTime=true;

                            if(isValidTime)
                            {
                                var isLeapYear = false;
                                if(year % 4 == 0)
                                     isLeapYear = true;

                                if((month==4 || month==6|| month==9|| month==11) && (day>=0 && day <= 30))
                                        isValidDate=true;
                                else if((month!=2) && (day>=0 && day <= 31))
                                        isValidDate=true;

                                if(!isValidDate){
                                    if(isLeapYear)
                                    {
                                        if(month==2 && (day>=0 && day <= 29))
                                            isValidDate=true;
                                    }
                                    else
                                    {
                                        if(month==2 && (day>=0 && day <= 28))
                                            isValidDate=true;
                                    }
                                }
                            }
                        }
                        catch(er){isValidDate = false;}
                    }

                }

                return isValidDate;
            }
            catch (err) { return isValidDate; }
        }

        $('#visibility-all-data-set input').on('change', function() {
            var getValue = $('input[name=visibility]:checked', '#visibility-all-data-set').val();
            if(getValue == 2) {
                $('#protected_password').parent().show();
            } else {
                $('#protected_password').parent().hide();
            }
        });

        $('.cancel-post-status').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            if(id == 'cancle-draft') {
                $('#draft-edit-show').hide();
                $('#draft-edit').show();
            } else if(id == 'cancel-visibility') {
                $('#visibility-edit-show').hide();
                $('#visibility-edit').show();
            } else if(id == 'cancel-publish') {
                $('#publish-edit-show').hide();
                $('#publish-edit').show();
            }
        });

        $('.flipup').click(function() {
            var id = $(this).attr('id');
            if(id == 'publish') {
                if($('#' + id).children().attr('class') == 'fa fa-caret-up') {
                    $('#publish-box').hide();
                    $('#publish-box-footer').hide();
                    $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
                } else {
                    $('#publish-box').show();
                    $('#publish-box-footer').show();
                    $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
                }
            } else if(id == 'attribute') {
                if($('#' + id).children().attr('class') == 'fa fa-caret-up') {
                    $('#attribute-box').hide();
                    $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
                } else {
                    $('#attribute-box').show();
                    $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
                }
            } else if(id == 'featuredimage') {
                if($('#' + id).children().attr('class') == 'fa fa-caret-up') {
                    $('#featuredimage-box').hide();
                    $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
                } else {
                    $('#featuredimage-box').show();
                    $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
                }
            } else if(id == 'sliderimages') {
                if($('#' + id).children().attr('class') == 'fa fa-caret-up') {
                    $('#sliderimages-box').hide();
                    $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
                } else {
                    $('#sliderimages-box').show();
                    $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
                }
            } else if(id == 'category') {
                if($('#' + id).children().attr('class') == 'fa fa-caret-up') {
                    $('#category-box').hide();
                    $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
                } else {
                    $('#category-box').show();
                    $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
                }
            }

        });

        $('#set-featured-img').click(function(e) {
            e.preventDefault();
        });
    }(jQuery));

    function removeFeatureImage(ele, hiddenfield, imageSetClass) {
        $('.feauret-img-show').children().remove();
        $('#featured_image').val('');
        $('.feauret-img-show').removeAttr('data-toggle data-target');
        $('#set-featured-img').removeClass('hide');
        $('#remove-set-featured-img').addClass('hide');
    }

    $(document).on('click', '.slider-delete', function() {
        var id = $(this).attr('id');
        var getHiddenData = $('#hidden_slider_images').val();
        var setHiddenData = getHiddenData.replace(","+id, '');
        $('#hidden_slider_images').val(setHiddenData);
        $(this).parent().remove();
    });
</script>