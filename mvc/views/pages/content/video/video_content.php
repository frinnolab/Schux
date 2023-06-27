<?php if($videoStatus == FALSE) { ?>
<li class="active">
	<div class="playlist__item" onclick="videoPlaylist(this, 'playbox<?=$rand?>','<?=base_url('uploads/gallery/'.$videoContent->file_name)?>','video<?=$rand?>','playlist<?=$rand?>');">
		<div class="item__sl"><?=$i.'.'?></div>
		<div class="item__title"><?=namesorting($videoContent->file_title, 60)?></div>
		<div class="item__length"><?=$videoContent->file_length?></div>
	</div>
</li>
<?php } else { ?>
<li class="">
	<div class="playlist__item" onclick="videoPlaylist(this, 'playbox<?=$rand?>','<?=base_url('uploads/gallery/'.$videoContent->file_name)?>','video<?=$rand?>','playlist<?=$rand?>');">
		<div class="item__sl"><?=$i.'.'?></div>
		<div class="item__title"><?=namesorting($videoContent->file_title, 60)?></div>
		<div class="item__length"><?=$videoContent->file_length?></div>
	</div>
</li>
<?php }  ?> 