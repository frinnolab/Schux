<h2><?=$this->lang->line('pages_attachment_details')?></h2>
<div class="attached-info">
    <div class="attached-thumb">
    	<i class="fa fa-file-video-o"></i>
    </div>
    <div class="attached-thumb-details">                               
        <div class="filename"><?=$image_info->file_original_name?></div>
        <div class="uploaded"><?=date('M d, Y', strtotime($image_info->file_upload_date))?></div>

        <div class="file-size"><?=$image_info->file_size?></div>
        
        <div class="dimensions"><?=$image_info->file_width_height?></div>
    
        <button type="button" class="delete-attachment" id="<?=$image_info->media_galleryID?>" onclick="deleteFileInfo(this)"><?=$this->lang->line('pages_delete_permanently')?></button>
    </div>
</div>
<?php $getAllDataInSession = $this->session->userdata('media_gallery_stroge'); ?>
<div class="attached-form">
    <form>
        <input type="text" name="hidden-id-field" id="hidden-id-field" value="<?=$image_info->media_galleryID?>" style="display:none">
        <div class="form-group">
            <label for="file_url"><?=$this->lang->line('pages_url')?></label>
            <input type="file_url" readonly="readonly" name="file_url" id="file_url" value="<?=base_url('uploads/gallery/'.$image_info->file_name)?>">
        </div>
        <div class="form-group">
            <label for="file_title"><?=$this->lang->line('pages_title')?></label>
            <input type="text" name="file_title" id="file_title" value="<?=isset($getAllDataInSession[$image_info->media_galleryID]['file_title']) ? $getAllDataInSession[$image_info->media_galleryID]['file_title'] : $image_info->file_title?>">
        </div>
        <div class="form-group hide">
            <label for="file_artist"><?=$this->lang->line('pages_artist')?></label>
            <input type="text" name="file_artist" id="file_artist" value="<?=isset($getAllDataInSession[$image_info->media_galleryID]['file_artist']) ? $getAllDataInSession[$image_info->media_galleryID]['file_artist'] : $image_info->file_artist?>">
        </div>
        <div class="form-group hide">
            <label for="file_album"><?=$this->lang->line('pages_album')?></label>
            <input type="text" name="file_album" id="file_album" value="<?=isset($getAllDataInSession[$image_info->media_galleryID]['file_album']) ? $getAllDataInSession[$image_info->media_galleryID]['file_album'] : $image_info->file_album?>">
        </div>
        <div class="form-group">
            <label for="file_caption"><?=$this->lang->line('pages_caption')?></label>
            <textarea id="file_caption" name="file_caption"><?=isset($getAllDataInSession[$image_info->media_galleryID]['file_caption']) ? $getAllDataInSession[$image_info->media_galleryID]['file_caption'] : $image_info->file_caption?></textarea>
        </div>
        <div class="form-group hide">
            <label for="file_alt_text"><?=$this->lang->line('pages_alt_text')?></label>
            <input type="text" name="file_alt_text" id="file_alt_text" value="<?=isset($getAllDataInSession[$image_info->media_galleryID]['file_alt_text']) ? $getAllDataInSession[$image_info->media_galleryID]['file_alt_text'] : $image_info->file_alt_text?>">
        </div>
        <div class="form-group">
            <label for="file_description"><?=$this->lang->line('pages_description')?></label>
            <textarea id="file_description" name="file_description"><?=isset($getAllDataInSession[$image_info->media_galleryID]['file_description']) ? $getAllDataInSession[$image_info->media_galleryID]['file_description'] : $image_info->file_description?></textarea>
        </div>
    </form>
</div>