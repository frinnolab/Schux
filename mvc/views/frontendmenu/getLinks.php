

<?php $rand = rand('9', '9999'); $rand2 = rand('9999', '99999999'); ?>
<li class="mjs-nestedSortable-leaf" id="<?=md5($rand.'-'.$rand2)?>">
	<div class="panel panel-default ui-sortable-handle <?=$rand?>" data-id="<?=$rand?>" data-type-id="3" data-rand="<?=md5($rand.'-'.$rand2)?>">
		<div class="panel-heading ui-sortable-handle" role="tab" id="menu-item-heading-<?=$rand?>">
		  	<h4 class="panel-title">
			    <a class="menu-click-button-menu collapsed menu-title-<?=$rand?>" role="button" data-toggle="collapse" data-parent="#menu-item" href="#menu-item-collapse-<?=$rand?>" aria-expanded="false" aria-controls="menu-item-collapse-<?=$rand?>">
			    	<span class="menu-manage-title-text"><?=empty($urlLinkText) ? '('.$this->lang->line('frontendmenu_pending').')' : $urlLinkText ?></span>
			    	<span class="pull-right"><?=$this->lang->line('frontendmenu_custom_link')?></span>
			      	<i class="fa fa-angle-right"></i>
			    </a>
			 </h4>
		</div>
		<div id="menu-item-collapse-<?=$rand?>" class="panel-collapse collapse ui-sortable-handle" role="tabpanel" aria-labelledby="menu-item-heading-<?=$rand?>">
			<div class="panel-body ui-sortable-handle">
				<form class="menu-page">
		    		<div class="form-group">
		    			<label for="url-label-<?=$rand?>"><?=$this->lang->line('frontendmenu_url')?></label>
		    			<input type="text" name="" class="form-control url-control-field" id="url-label-<?=$rand?>" value="<?=$urlLinkField?>" data-title="menu-title-<?=$rand?>">
		    		</div>

		    		<div class="form-group">
		    			<label for="label-<?=$rand?>"><?=$this->lang->line('frontendmenu_navigation_label')?></label>
		    			<input type="text" name="" class="form-control lable-text" id="label-<?=$rand?>" value="<?=$urlLinkText?>" data-title="menu-title-<?=$rand?>">
		    		</div>

		    		<div class="form-group ui-sortable-handle">
		    			<div class="move ui-sortable-handle">
		    				<span><?=$this->lang->line('frontendmenu_move')?></span>
		    				<a href="#" rand-info="<?=$rand?>" class="data-up-one"><?=$this->lang->line('frontendmenu_up_one')?></a>
		    				<a href="#" rand-info="<?=$rand?>" class="data-down-one"><?=$this->lang->line('frontendmenu_down_one')?></a>
		    			</div>
		    		</div>
		    		<div class="form-group ui-sortable-handle">
		    			<a class="Data-remove" href="#" data-title="<?=$rand?>"><?=$this->lang->line('frontendmenu_remove')?></a>
		    			<span>|</span>
		    			<a old-url-title="<?=$urlLinkField?>" old-title="<?=empty($urlLinkText) ? '('.$this->lang->line('frontendmenu_pending').')' : $urlLinkText ?>" class="Data-cancel" rand-info="<?=$rand?>" href="#"><?=$this->lang->line('frontendmenu_cancel')?></a>
		    		</div>
		    	</form>
			</div>
		</div>
	</div>		
