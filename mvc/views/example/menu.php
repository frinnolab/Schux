
<!-- <div class="row">
	<div class="col-sm-4">
		<form action="">
			<div class="panel-group" id="menu-settings" aria-multiselectable="true">
			  	<div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="page-heading-one">
				      <h4 class="panel-title">
				        <a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse-one" aria-expanded="true" aria-controls="menu-settings-collapse-one" class="menu-click-button">
				          Pages
				          <i class="fa fa-angle-right"></i>
				        </a>
				      </h4>
				    </div>
				    <div id="menu-settings-collapse-one" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="page-heading-one">
					    <div class="panel-body">
					        <ul class="nav nav-tabs">
							    <li class="active"><a href="#view-all" data-toggle="tab">View All</a></li>
							</ul>


							<div class="tab-content">
							    <div class="tab-pane active" id="most-recent">
									<div class="menu-settings-header with-border">
										<?php if($pages) { foreach ($pages as $page) { ?>
											<div class="form-group">
												<input class="pages-list-checked" type="checkbox" id="<?=$page->pagesID?>">
												<label for="<?=$page->pagesID?>"><?=$page->title?></label>
											</div>
										<?php } } ?>
									</div>
									<div class="menu-settings-footer clearfix">
										<a href="#" class="select-btn pull-left unchecked" id="pages-select-all">Select All</a>
										<input type="submit" class="submit-btn pull-right" value="Add to Menu">
									</div>
							    </div>
					      	</div>
					    </div>
					</div>
				</div>
				<div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="page-heading-two">
				     	<h4 class="panel-title">
					       	<a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse" aria-expanded="false" aria-controls="menu-settings-collapse" class="menu-click-button">
								Custom links
								<i class="fa fa-angle-right"></i>
					        </a>
				      	</h4>
				    </div>
				    <div id="menu-settings-collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="page-heading-two">
						<div class="panel-body">
							<div class="menu-settings-header">
								<div class="form-group">
									<label for="menu-settings-url">URL</label>
									<input type="url" name="menu-settings-url" id="menu-settings-url" class="form-control" value="http://">
								</div>
								<div class="form-group">
									<label for="menu-settings-link">Link Text</label>
									<input type="text" name="menu-settings-link" id="menu-settings-link" class="form-control">
								</div>
							</div>
							<div class="menu-settings-footer clearfix">
								<input type="submit" class="submit-btn pull-right" value="Add to Menu">
							</div>
						</div>
				    </div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-8">
		<div id="menu-management">
			<form action="#" method="POST">
				<div class="menu-management-header">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="menu-name">Menu Name</label>
								<input type="text" name="" value="" class="form-control" id="menu-name">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="publishing-menu-action pull-right">
								<input type="submit" name="save-name" class="save-menu" value="Save Menu">
							</div>
						</div>
					</div>
				</div>
				<div id="menu-post-body">
					<h3>Menu Structure</h3>
					<p>Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</p>
					

					<div id="menu-edit">
						<div class="panel-group" id="menu-item" role="tablist" aria-multiselectable="true">

							<ol class="sortable">
								<li>
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="menu-item-heading-one">
										  <h4 class="panel-title">
											<a class="menu-click-button-menu collapsed" role="button" data-toggle="collapse" data-parent="#menu-item" href="#menu-item-collapse-one" aria-expanded="fasle" aria-controls="menu-item-collapse-one">
												Home 
												<i class="fa fa-angle-right"></i>
											</a>
										  </h4>
										</div>
										<div id="menu-item-collapse-one" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu-item-heading-one">
											 <div class="panel-body">
										    	<form class="menu-page">
										    		<div class="form-group">
										    			<label for="sample-page">Navigation Label</label>
										    			<input type="text" name="" class="form-control" id="sample-page" placeholder="Sample page">
										    		</div>
										    		<div class="form-group">
										    			<div class="move">
										    				<span>Move</span>
										    				<a href="#">Down One</a>
										    			</div>
										    		</div>
										    		<div class="form-group">
										    			<div class="link-to-original">
										    				<span>Orginal:</span>
										    				<a href="#">Sample Page</a>
										    			</div>
										    		</div>
										    		<div class="form-group">
										    			<a href="#">Remove</a>
										    			<span>|</span>
										    			<a href="#">Cancel</a>
										    		</div>
										    	</form>
											 </div>
										</div>
									</div>
								</li>

								<li>	
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="menu-item-heading-two">
										  	<h4 class="panel-title">
											    <a class="menu-click-button-menu collapsed" role="button" data-toggle="collapse" data-parent="#menu-item" href="#menu-item-collapse-two" aria-expanded="false" aria-controls="menu-item-collapse-two">
											      Hello Banlgadesh
											      <i class="fa fa-angle-right"></i>
											    </a>
											 </h4>
										</div>
										<div id="menu-item-collapse-two" class="panel-collapse collapse" role="tabpanel" aria-labelledby="menu-item-heading-two">
											<div class="panel-body">
												<form class="menu-page">
										    		<div class="form-group">
										    			<label for="sample-page">Navigation Label</label>
										    			<input type="text" name="" class="form-control" id="sample-page" placeholder="Sample page">
										    		</div>
										    		<div class="form-group">
										    			<div class="move">
										    				<span>Move</span>
										    				<a href="#">Up one</a>
										    				<a href="#">Under Sample Page</a>
										    				<a href="#">To the top</a>
										    			</div>
										    		</div>
										    		<div class="form-group">
										    			<div class="link-to-original">
										    				<span>Orginal:</span>
										    				<a href="#">Sample Page</a>
										    			</div>
										    		</div>
										    		<div class="form-group">
										    			<a href="#">Remove</a>
										    			<span>|</span>
										    			<a href="#">Cancel</a>
										    		</div>
										    	</form>
											</div>
										</div>
									</div>
								</li>
							</ol>



						</div>
					</div>



					<div class="menu-settings">
						<h3>Menu Settings</h3>
						<fieldset class="menu-settings-list">
						    <legend class="menu-settings-list-name">Auto add pages</legend>
						    <div class="menu-settings-input">
						        <input type="checkbox" name="auto-add-pages" id="auto-add-pages" value="1">
						        <label for="auto-add-pages">Automatically add new top-level pages to this menu</label>
						    </div>
						</fieldset>
						<fieldset class="menu-settings-list">
						    <legend class="menu-settings-list-name">Display location</legend>
						    <div class="menu-settings-input">
						        <input type="checkbox" name="menu-locations[top]" id="locations-top" value="3">
						        <label for="locations-top">Top Menu</label>
						        <span class="theme-location-set">(Currently set to: services)</span>
						    </div>
						    <div class="menu-settings-input">
						        <input type="checkbox" name="menu-locations[social]" id="locations-social" value="3">
						        <label for="locations-social">Social Links Menu</label>
						    </div>
						</fieldset>
					</div>
					<div class="menu-management-footer clearfix">
						<a href="#" class="delete-btn pull-left">Delete Menu</a>
						<input type="submit" class="submit-btn pull-right" value="Save Menu">
					</div>
				</div>
			</form>
		</div>
	</div>
</div> -->


<script type="text/javascript">


	$('#pages-select-all').click(function(e) {
		e.preventDefault();
		if($('#pages-select-all').hasClass('checked')) {
			$('.pages-list-checked').prop( "checked", false);
			$('#pages-select-all').removeClass('checked');
			$('#pages-select-all').addClass('unchecked');
		} else {
			$('.pages-list-checked').prop( "checked", true);
			$('#pages-select-all').removeClass('unchecked');
			$('#pages-select-all').addClass('checked');
		}
	});

</script>



<ol class="sortable">
	<li><div>Some content</div></li>
	<li>
		<div>Some content</div>
		<ol>
			<li><div>Some sub-item content</div></li>
			<li><div>Some sub-item content</div></li>
		</ol>
	</li>
	<li><div>Some content</div></li>
</ol>

<!-- <div class="sortable">
	<div>li1</div>
	<div>li2</div>
</div> -->


<!-- <ol class="sortable">
	<li><div>Dipok 1</div></li>
	<li><div>Dipok 2</div></li>
	<li>Dipok 3</li>
	<li>Dipok 4</li>
</ol> -->

<script type="text/javascript">
	
	$(document).ready(function(){

		$('.sortable').nestedSortable({
			handle: 'div',
			items: 'li',
			toleranceElement: '> div',
		});

	});
</script>
