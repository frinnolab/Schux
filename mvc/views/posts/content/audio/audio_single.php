<div class="item__play__box" id="plybox<?=$rand?>">
    <div class="item__header">
    	<div class="playing">
	    	<span class="npAction">
	    		<?=$this->lang->line('posts_now_playing')?>...
	    	</span>
	    	<span class="npTitle">
	    		<?=namesorting($audioContent->file_title, 60)?>
	    	</span>
	    </div>

		<audio preload id="audio<?=$rand?>" controls="controls" src="<?=base_url('uploads/gallery/'.$audioContent->file_name)?>" >Your browser does not support HTML5 Audio!</audio>
    </div>
</div>&nbsp;<p></p>