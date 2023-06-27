

<?php $rand = rand('9', '9999'); $rand2 = rand('9999', '99999999'); ?>
<li class="<?=isset($mergeelement['child']) ? 'mjs-nestedSortable-branch mjs-nestedSortable-expanded' : 'mjs-nestedSortable-leaf'?>" id="<?=$mergeelement['menu_rand']?>">
<div class="panel panel-default ui-sortable-handle <?=$mergeelement['menu_pagesID']?>" data-id="<?=$mergeelement['menu_pagesID']?>" data-type-id="3" data-rand="<?=$mergeelement['menu_rand']?>">
	<div class="panel-heading ui-sortable-handle" role="tab" id="menu-item-heading-<?=$mergeelement['menu_pagesID']?>">
	  	<h4 class="panel-title">
		    <a class="menu-click-button-menu collapsed menu-title-<?=$mergeelement['menu_pagesID']?>" role="button" data-toggle="collapse" data-parent="#menu-item" href="#menu-item-collapse-<?=$mergeelement['menu_pagesID']?>" aria-expanded="false" aria-controls="menu-item-collapse-<?=$mergeelement['menu_pagesID']?>">
		    	<span class="menu-manage-title-text"><?=!isset($mergeelement['menu_label']) ? '('.$this->lang->line('frontendmenu_pending').')' : $mergeelement['menu_label'] ?></span>
		    	<span class="pull-right"><?=$this->lang->line('frontendmenu_custom_link')?></span>
		      	<i class="fa fa-angle-right"></i>
		    </a>
		 </h4>
	</div>
	<div id="menu-item-collapse-<?=$mergeelement['menu_pagesID']?>" class="panel-collapse collapse ui-sortable-handle" role="tabpanel" aria-labelledby="menu-item-heading-<?=$mergeelement['menu_pagesID']?>">
		<div class="panel-body ui-sortable-handle">
			<form class="menu-page">
	    		<div class="form-group">
	    			<label for="url-label-<?=$mergeelement['menu_pagesID']?>"><?=$this->lang->line('frontendmenu_url')?></label>
	    			<input type="text" name="" class="form-control url-control-field" id="url-label-<?=$mergeelement['menu_pagesID']?>" value="<?=$mergeelement['menu_link']?>" data-title="menu-title-<?=$mergeelement['menu_pagesID']?>">
	    		</div>

	    		<div class="form-group">
	    			<label for="label-<?=$mergeelement['menu_pagesID']?>"><?=$this->lang->line('frontendmenu_navigation_label')?></label>
	    			<input type="text" name="" class="form-control lable-text" id="label-<?=$mergeelement['menu_pagesID']?>" value="<?=$mergeelement['menu_label']?>" data-title="menu-title-<?=$mergeelement['menu_pagesID']?>">
	    		</div>

	    		<div class="form-group ui-sortable-handle">
	    			<div class="move ui-sortable-handle">
	    				<span><?=$this->lang->line('frontendmenu_move')?></span>
	    				<a href="#" rand-info="<?=$mergeelement['menu_pagesID']?>" class="data-up-one"><?=$this->lang->line('frontendmenu_up_one')?></a>
	    				<a href="#" rand-info="<?=$mergeelement['menu_pagesID']?>" class="data-down-one"><?=$this->lang->line('frontendmenu_down_one')?></a>
	    			</div>
	    		</div>
	    		<div class="form-group ui-sortable-handle">
	    			<a class="Data-remove" href="#" data-title="<?=$mergeelement['menu_pagesID']?>"><?=$this->lang->line('frontendmenu_remove')?></a>
	    			<span>|</span>
	    			<a old-url-title="<?=$mergeelement['menu_link']?>" old-title="<?=!isset($mergeelement['menu_label']) ? '('.$this->lang->line('frontendmenu_pending').')' : $mergeelement['menu_label'] ?>" class="Data-cancel" rand-info="<?=$mergeelement['menu_pagesID']?>" href="#"><?=$this->lang->line('frontendmenu_cancel')?></a>
	    		</div>
	    	</form>
		</div>
	</div>
</div>
		
