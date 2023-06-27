<?php if($audioStatus == FALSE) { ?>
<li class="active">
	<div class="playlist__item" onclick="audioPlaylist(this,'plybox<?=$rand?>','<?=base_url('uploads/gallery/'.$audioContent->file_name)?>','audio<?=$rand?>','playlist<?=$rand?>');">
		<div class="item__sl"><?=$i.'.'?></div>
		<div class="item__title"><?=namesorting($audioContent->file_title, 60)?></div>
		<div class="item__length"><?=$audioContent->file_length?></div>
	</div>
</li>
<?php } else { ?>
<li class="">
	<div class="playlist__item" onclick="audioPlaylist(this,'plybox<?=$rand?>','<?=base_url('uploads/gallery/'.$audioContent->file_name)?>','audio<?=$rand?>','playlist<?=$rand?>');">
		<div class="item__sl"><?=$i.'.'?></div>
		<div class="item__title"><?=namesorting($audioContent->file_title, 60)?></div>
		<div class="item__length"><?=$audioContent->file_length?></div>
	</div>
</li>
<?php }  ?> 