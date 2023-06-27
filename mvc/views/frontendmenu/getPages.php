
<?php if(count($pageArrays)) { ?> 
	<?php foreach ($pageArrays as $pageArray) { $rand = rand('9', '9999'); $rand2 = rand('9999', '99999999'); ?>

		<li class="mjs-nestedSortable-leaf" id="<?=md5($rand.'-'.$rand2)?>">
			<div class="panel panel-default ui-sortable-handle <?=$pageArray->pagesID.'-'.$rand?>" data-id="<?=$pageArray->pagesID?>" data-type-id="1" data-rand="<?=md5($rand.'-'.$rand2)?>">
				<div class="panel-heading ui-sortable-handle" role="tab" id="menu-item-heading-<?=$pageArray->pagesID.'-'.$rand?>">
				  	<h4 class="panel-title">
					    <a class="menu-click-button-menu collapsed menu-title-<?=$pageArray->pagesID.'-'.$rand?>" role="button" data-toggle="collapse" data-parent="#menu-item" href="#menu-item-collapse-<?=$pageArray->pagesID.'-'.$rand?>" aria-expanded="false" aria-controls="menu-item-collapse-<?=$pageArray->pagesID.'-'.$rand?>">
					    	<span class="menu-manage-title-text"><?=$pageArray->title?></span>
					    	<span class="pull-right"><?=$this->lang->line('frontendmenu_page')?></span>
					      	<i class="fa fa-angle-right"></i>
					    </a>
					 </h4>
				</div>
				<div id="menu-item-collapse-<?=$pageArray->pagesID.'-'.$rand?>" class="panel-collapse collapse ui-sortable-handle" role="tabpanel" aria-labelledby="menu-item-heading-<?=$pageArray->pagesID.'-'.$rand?>">
					<div class="panel-body ui-sortable-handle">
						<form class="menu-page">
				    		<div class="form-group">
				    			<label for="label-<?=$pageArray->pagesID.'-'.$rand?>"><?=$this->lang->line('frontendmenu_navigation_label')?></label>
				    			<input type="text" name="" class="form-control lable-text" id="label-<?=$pageArray->pagesID.'-'.$rand?>" value="<?=$pageArray->title?>" data-title="menu-title-<?=$pageArray->pagesID.'-'.$rand?>">
				    		</div>
				    		<div class="form-group ui-sortable-handle">
				    			<div class="move ui-sortable-handle">
				    				<span><?=$this->lang->line('frontendmenu_move')?></span>
				    				<a href="#" rand-info="<?=$pageArray->pagesID.'-'.$rand?>" class="data-up-one"><?=$this->lang->line('frontendmenu_up_one')?></a>
				    				<a href="#" rand-info="<?=$pageArray->pagesID.'-'.$rand?>" class="data-down-one"><?=$this->lang->line('frontendmenu_down_one')?></a>
				    			</div>
				    		</div>
				    		<div class="form-group ui-sortable-handle">
				    			<div class="link-to-original ui-sortable-handle">
				    				<span><?=$this->lang->line('frontendmenu_orginal')?>:</span>
				    				<a target="_blank" href="<?=base_url('frontend/page/'.$pageArray->url)?>"><?=$pageArray->title?></a>
				    			</div>
				    		</div>
				    		<div class="form-group ui-sortable-handle">
				    			<a class="Data-remove" href="#" data-title="<?=$pageArray->pagesID.'-'.$rand?>"><?=$this->lang->line('frontendmenu_remove')?></a>
				    			<span>|</span>
				    			<a old-title="<?=$pageArray->title?>" class="Data-cancel" rand-info="<?=$pageArray->pagesID.'-'.$rand?>" href="#"><?=$this->lang->line('frontendmenu_cancel')?></a>
				    		</div>
				    	</form>
					</div>
				</div>
			</div>
		</li>		
	<?php } ?>
<?php } ?>