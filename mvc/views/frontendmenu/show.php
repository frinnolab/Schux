
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
        	<a data-toggle="tab" href="#edit-menus" aria-expanded="true">
        		<?=$this->lang->line("frontendmenu_edit_menus")?>
        	</a>
        </li>
        <li>
        	<a data-toggle="tab" href="#manage-locations" aria-expanded="true">
        		<?=$this->lang->line("frontendmenu_manage_locations")?>
        	</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="edit-menus" class="tab-pane active">
        	<div class="row">
        		<div class="col-sm-12">
	        		<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
	        			<div class="form-group">
							<label for="topbar-menu-select-property" class="col-sm-2 control-label top-menu-color"><?=$this->lang->line('frontendmenu_select_a_menu_to_edit')?>:</label>
							<div class="col-sm-4">
								<select class="form-control top-meu-select-property" id="topbar-menu-select-property">
									<?php if(count($fmenus)) foreach ($fmenus as $fmenu) { ?>
										<option>
											<?php
												echo $fmenu->menu_name;
												if($fmenu->topbar == 1 && $fmenu->social == 1) {
													echo ' ('.$this->lang->line('frontendmenu_top_menu').', '.$this->lang->line('frontendmenu_social_links_menu').')';
												} elseif($fmenu->topbar == 1) {
													echo ' ('.$this->lang->line('frontendmenu_top_menu').')';
												} elseif($fmenu->social == 1) {
													echo ' ('.$this->lang->line('frontendmenu_social_links_menu').')';
												}
											?>
										</option>
									<?php } ?>
								</select>
							</div>
	                        <button type="submit" class="btn btn-menu-add-type"><?=$this->lang->line('frontendmenu_select')?></button>
						</div>
					</form>
        		</div>
        	</div>

        	<div class="row">
				<div class="col-sm-4">
					<div class="panel-group" id="menu-settings" aria-multiselectable="true">
						<div class="panel panel-default">
						    <div class="panel-heading" role="tab" id="page-heading-pages">
						      <h4 class="panel-title">
						        <a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse-pages" aria-expanded="true" aria-controls="menu-settings-collapse-pages" class="menu-click-button">
						        	<?=$this->lang->line('frontendmenu_pages')?>	
						          	<i class="fa fa-angle-right"></i>
						        </a>
						      </h4>
						    </div>
						    <div id="menu-settings-collapse-pages" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="page-heading-pages">
							    <div class="panel-body">
							        <ul class="nav nav-tabs">
									    <li class="active"><a href="#view-all" data-toggle="tab"><?=$this->lang->line('frontendmenu_view_all')?></a></li>
									</ul>

									<div class="tab-content">
									    <div class="tab-pane active" id="most-recent">
											<div class="menu-settings-header with-border pages-all-checkbox">
												<?php if($pages) { foreach ($pages as $page) { ?>
													<div class="form-group">
														<input class="pages-list-checked" type="checkbox" id="page-<?=$page->pagesID?>">
														<label for="page-<?=$page->pagesID?>"><?=$page->title?></label>
													</div>
												<?php } } ?>
											</div>
											<div class="menu-settings-footer clearfix">
												<a href="#" class="select-btn pull-left unchecked" id="pages-select-all"><?=$this->lang->line('frontendmenu_select_all')?></a>
												<input type="button" class="submit-btn pull-right pages-add" value="<?=$this->lang->line('frontendmenu_add_to_menu')?>">
											</div>
									    </div>
							      	</div>
							    </div>
							</div>
						</div>

						<div class="panel panel-default">
						    <div class="panel-heading" role="tab" id="page-heading-posts">
						      <h4 class="panel-title">
						        <a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse-posts" aria-expanded="false" aria-controls="menu-settings-collapse-posts" class="menu-click-button">
						        	<?=$this->lang->line('frontendmenu_posts')?>	
						          	<i class="fa fa-angle-right"></i>
						        </a>
						      </h4>
						    </div>
						    <div id="menu-settings-collapse-posts" class="panel-collapse collapse" role="tabpanel" aria-labelledby="page-heading-posts">
							    <div class="panel-body">
							        <ul class="nav nav-tabs">
									    <li class="active"><a href="#view-all" data-toggle="tab"><?=$this->lang->line('frontendmenu_view_all')?></a></li>
									</ul>

									  <!-- Tab panes -->
									<div class="tab-content">
									    <div class="tab-pane active" id="most-recent">
											<div class="menu-settings-header with-border posts-all-checkbox">
												<?php if($posts) { foreach ($posts as $post) { ?>
													<div class="form-group">
														<input class="posts-list-checked" type="checkbox" id="post-<?=$post->postsID?>">
														<label for="post-<?=$post->postsID?>"><?=$post->title?></label>
													</div>
												<?php } } ?>
											</div>
											<div class="menu-settings-footer clearfix">
												<a href="#" class="select-btn pull-left unchecked" id="posts-select-all"><?=$this->lang->line('frontendmenu_select_all')?></a>
												<input type="button" class="submit-btn pull-right posts-add" value="<?=$this->lang->line('frontendmenu_add_to_menu')?>">
											</div>
									    </div>
							      	</div>
							    </div>
							</div>
						</div>

						<div class="panel panel-default">
						    <div class="panel-heading" role="tab" id="page-heading-links">
						     	<h4 class="panel-title">
							       	<a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse-links" aria-expanded="false" aria-controls="menu-settings-collapse-links" class="menu-click-button">
										<?=$this->lang->line('frontendmenu_custom_links')?>
										<i class="fa fa-angle-right"></i>
							        </a>
						      	</h4>
						    </div>
						    <div id="menu-settings-collapse-links" class="panel-collapse collapse" role="tabpanel" aria-labelledby="page-heading-links">
								<div class="panel-body">
									<div class="menu-settings-header">
										<div class="form-group">
											<label for="menu-settings-url"><?=$this->lang->line('frontendmenu_url')?></label>
											<input type="text" name="menu-settings-url" id="menu-settings-url" class="form-control url-link-field" value="http://">
										</div>
										<div class="form-group">
											<label for="menu-settings-link"><?=$this->lang->line('frontendmenu_link_text')?></label>
											<input type="text" name="menu-settings-link" id="menu-settings-link" class="form-control url-link-text">
										</div>
									</div>
									<div class="menu-settings-footer clearfix">
										<input type="button" class="submit-btn pull-right links-add" value="<?=$this->lang->line('frontendmenu_add_to_menu')?>">
									</div>
								</div>
						    </div>
						</div>
					</div>
				</div>

				<div class="col-sm-8">
					<div id="menu-management">
						<!-- <form action="#" method="POST"> -->
							<div class="menu-management-header">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="menu-label-italic-style" for="menu-name"><?=$this->lang->line('frontendmenu_menu_name')?></label>
											<input type="text" name="" value="<?=$activefmenu->menu_name?>" class="form-control" id="menu-name">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="publishing-menu-action pull-right">
											<input type="button" name="save-name" class="save-menu submit-menu" value="<?=$this->lang->line('frontendmenu_save_menu')?>" >
										</div>
									</div>
								</div>
							</div>
							<div id="menu-post-body">
								<h3><?=$this->lang->line('frontendmenu_menu_structure')?></h3>
								<p><?=$this->lang->line('frontendmenu_menu_structure_desc')?></p>
								<div id="menu-edit">
									<div class="panel-group" id="menu-item" role="tablist" aria-multiselectable="true">
										<ol class="sortable <?=!empty($show) ? 'ui-sortable' : ''?>">
											<?=$show?>
										</ol>

									</div>
								</div>

								<div class="menu-settings">
									<h3><?=$this->lang->line('frontendmenu_menu_settings')?></h3>
									<fieldset class="menu-settings-list">
									    <legend class="menu-settings-list-name"><?=$this->lang->line('frontendmenu_display_location')?></legend>
									    <div class="menu-settings-input">
									        <input <?=($activefmenu->topbar == 1) ? ' checked="checked" ' : ''?> type="checkbox"  name="menu-locations_top" id="locations-top" value="1">
									        <label for="locations-top"><?=$this->lang->line('frontendmenu_top_menu')?></label>
									    </div>
									    <div class="menu-settings-input">
									        <input <?=($activefmenu->social == 1) ? ' checked="checked" ' : ''?>  type="checkbox" name="menu_locations_social" id="locations-social" value="1">
									        <label for="locations-social"><?=$this->lang->line('frontendmenu_social_links_menu')?></label>
									    </div>
									</fieldset>
								</div>
								<div class="menu-management-footer clearfix">
									<a href="#" class="delete-btn pull-left"><?=$this->lang->line('frontendmenu_delete_menu')?></a>
									<input type="button" class="save-menu pull-right submit-menu" value="<?=$this->lang->line('frontendmenu_save_menu')?>">
								</div>
							</div>
						<!-- </form> -->
					</div>
				</div>
			</div>
        </div>

        <div id="manage-locations" class="tab-pane"> 
        	<?php
        		$topBarMenuSelect = 0;
        		$socialMenuSelect = 0;
                $allMenus['0'] = '— '.$this->lang->line('frontendmenu_select_a_menu').' —';
                if(count($fmenus)) {
                	foreach ($fmenus as $fmenu) {
                		$allMenus[$fmenu->fmenuID] = $fmenu->menu_name;
                		if($fmenu->topbar == 1) {
                			$topBarMenuSelect = $fmenu->fmenuID;
                		}

                		if($fmenu->social == 1) {
                			$socialMenuSelect = $fmenu->fmenuID;
                		}
                	}
                }
            ?>
        	<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="<?=base_url('frontendmenu/managelocation')?>">
        		<div class="form-group">
                    <label for="top_menu_list" class="col-sm-2 control-label menu-label-for-mange-location">
                        <?=$this->lang->line("frontendmenu_top_menu")?>
                    </label>
                    <div class="col-sm-3">
                        <?php
                            echo form_dropdown("top_menu_list", $allMenus, set_value("top_menu_list", $topBarMenuSelect), "id='top_menu_list' class='form-control'");
                        ?>

                    </div>
                </div>

                <div class="form-group">
                    <label for="social_menu_list" class="col-sm-2 control-label menu-label-for-mange-location">
                        <?=$this->lang->line("frontendmenu_social_links_menu")?>
                    </label>
                    <div class="col-sm-3">
                        <?php
                            echo form_dropdown("social_menu_list", $allMenus, set_value("social_menu_list", $socialMenuSelect), "id='social_menu_list' class='form-control'");
                        ?>
                    </div>
                </div>
                <div>
                	<button type="submit" class="btn save-menu"><?=$this->lang->line('frontendmenu_save_changes')?></button>
                </div>
            </form>
        </div>
    </div>
</div>





<script type="text/javascript">
	function dd(data) {
		console.log(data);
	}

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

	$('#posts-select-all').click(function(e) {
		e.preventDefault();
		if($('#posts-select-all').hasClass('checked')) {
			$('.posts-list-checked').prop( "checked", false);
			$('#posts-select-all').removeClass('checked');
			$('#posts-select-all').addClass('unchecked');
		} else {
			$('.posts-list-checked').prop( "checked", true);
			$('#posts-select-all').removeClass('unchecked');
			$('#posts-select-all').addClass('checked');
		}
	});

	$('ol.sortable').nestedSortable({
		forcePlaceholderSize: true,
		handle: 'div',
		items: 'li',
		placeholder: 'placeholder',
		toleranceElement: '> div',
		maxLevels: 3,
		isTree: true,

		// forcePlaceholderSize: true,
		// handle: 'div',
		// helper:	'clone',
		// items: 'li',
		// opacity: .6,
		// placeholder: 'placeholder',
		// revert: 250,
		// tabSize: 25,
		// tolerance: 'pointer',
		// toleranceElement: '> div',
		// maxLevels: 4,
		// isTree: true,
		// expandOnHover: 700,
		// startCollapsed: false,
		// change: function(){
		// 	console.log('Relocated item');
		// }
	});

	$(document).on('click', '.pages-add', function() {
		var selected = [];
		$('.pages-all-checkbox input:checked').each(function() {
		    selected.push($(this).attr('id'));
		});

		if(selected.length > 0) {
			$.ajax({
	            type: 'POST',
	            url: "<?=base_url('frontendmenu/getpages')?>",
	            data: { 'pages' : selected },
	            dataType: "html",
	            success: function(data) {
	            	$('.sortable').append(data);
	            	$('.pages-list-checked').prop( "checked", false);
	            }
	        });
		}
	});

	$(document).on('click', '.posts-add', function() {
		var selected = [];
		$('.posts-all-checkbox input:checked').each(function() {
		    selected.push($(this).attr('id'));
		});

		if(selected.length > 0) {
			$.ajax({
	            type: 'POST',
	            url: "<?=base_url('frontendmenu/getposts')?>",
	            data: { 'posts' : selected },
	            dataType: "html",
	            success: function(data) {
	            	$('.sortable').append(data);
	            	$('.posts-list-checked').prop( "checked", false);
	            }
	        });
		}
	});

	$(document).on('click', '.links-add', function() {
		var url_link_field = $('.url-link-field').val();
		var url_link_text = $('.url-link-text').val();

		var error = 0;

		if(url_link_field == 'http://' || url_link_field == 'https://') {
			error++;
			$('.url-link-field').addClass('menu-has-error');
		} else {
			$('.url-link-field').removeClass('menu-has-error');
		}

		if(error == 0) {
			$.ajax({
	            type: 'POST',
	            url: "<?=base_url('frontendmenu/getlinks')?>",
	            data: { 'url_link_field' : url_link_field, 'url_link_text' : url_link_text },
	            dataType: "html",
	            success: function(data) {
	            	$('.sortable').append(data);
	            	$('.url-link-field').val('http://');
	            	$('.url-link-text').val('');
	            }
	        });
		}
	});

	$(document).on('keyup', '#menu-settings-link', function(e) {
		if($(this).val() != '') {
			if($(this).val().length > 253) {
				var str = $(this).val();
				str = str.substring(0, str.length - 1);
				$(this).val(str);
				toastr["error"]("<?=$this->lang->line('frontendmenu_error_label')?>")
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

	$(document).on('keyup', '.lable-text', function(e) {
		var id = $(this).attr('data-title');
		if($(this).val() == '') {
			$('.'+id+' span.menu-manage-title-text').html("(<?=$this->lang->line('frontendmenu_no_label')?>)");
		} else {
			if($(this).val().length > 10) {
				var str = $(this).val();
				str = str.substring(0, str.length - 1);
				$('.'+id+' span.menu-manage-title-text').html(str);
				$(this).val(str);
				$(this).attr('value', str);

				toastr["error"]("<?=$this->lang->line('frontendmenu_error_label')?>")
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
			} else {
				$('.'+id+' span.menu-manage-title-text').html($(this).val());
				$(this).attr('value', $(this).val());
			}
		}
	});

	$(document).on('keyup', '.url-control-field', function(e) {
		if($(this).val() != '') {
			$(this).attr('value', $(this).val());
		}
	})

	$(document).on('click', '.Data-remove', function(e) {
		e.preventDefault();
		var id = $(this).attr('data-title');
		$('.'+id).slideUp("normal", function() { $('.'+id).parent().remove(); } );
	});

	$(document).on('click', '.Data-cancel', function(e) {
		e.preventDefault();
		var rand = $(this).attr('rand-info');
		var oldtitle = $(this).attr('old-title');
		$('#label-'+rand).val(oldtitle);
		$('.menu-title-'+rand+' span.menu-manage-title-text').text(oldtitle);
		

		var oldurltitle = $(this).attr('old-url-title');
		if (typeof oldurltitle !== typeof undefined && oldurltitle !== false) {
			$('#url-label-'+rand).val(oldurltitle);
		}

		$('.menu-title-'+rand).addClass('collapsed');
		$('.menu-title-'+rand).attr('aria-expanded', 'false');

		$('#menu-item-collapse-'+rand).removeClass('in');
		$('#menu-item-collapse-'+rand).addClass('collapse');
	});

	$(document).on('click', '.data-up-one', function(e) {
		e.preventDefault();
		var rand = $(this).attr('rand-info');
		var current = $('.'+rand).parent();
		var previous = current.prev('li');
		
		if(previous.length !== 0){
	    	current.insertBefore(previous);	
	  	}
	});

	$(document).on('click', '.data-down-one', function(e) {
		e.preventDefault();
		var rand = $(this).attr('rand-info');
		var current = $('.'+rand).parent();
		var next = current.next('li');
		
		if(next.length !== 0){
	    	current.insertAfter(next);	
	  	}
	});

	$(document).on('click', '.submit-menu', function() {
		var activeMenuID = "<?=$activefmenu->fmenuID?>";
		var loop = 1;
		var loop2 = 1;
		var loop3 = 1;
		var arr = [];

		$('ol.sortable > li.mjs-nestedSortable-branch').each(function(index, value) {
			var link = '';
			var navValue = $(value).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');
			if($(value).children().attr('data-type-id') == 3) {
				navValue = $(value).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
				link = $(value).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');
			}
			
			arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value).children().attr('data-type-id'), 'menu_parentID' : '0', 'menu_pagesID' : $(value).children().attr('data-id'), 'menu_label' : navValue, 'menu_link' : link, 'menu_orderID' : loop, 'menu_rand' : $(value).children().attr('data-rand'), 'menu_rand_parentID' : '0' });
			loop++;

	       $(value).find('li.mjs-nestedSortable-branch').each(function(index2, value2) {
	       		var link2 = '';
	       		var navValue2 = $(value2).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');
	       		if($(value2).children().attr('data-type-id') == 3) {
					navValue2 = $(value2).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
					link2 = $(value2).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');
				}

	       		arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value2).children().attr('data-type-id'), 'menu_parentID' : $(value).children().attr('data-id'), 'menu_pagesID' : $(value2).children().attr('data-id'), 'menu_label' : navValue2, 'menu_link' : link2, 'menu_orderID' : loop2, 'menu_rand' : $(value2).children().attr('data-rand'), 'menu_rand_parentID' : $(value).children().attr('data-rand') });
	       		loop2++;

	       		$(value2).find('li.mjs-nestedSortable-leaf').each(function(index3, value3) {
	       			var link3 = '';
	       			var navValue3 = $(value3).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');
		       		if($(value3).children().attr('data-type-id') == 3) {
						navValue3 = $(value3).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
						link3 = $(value3).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');
					}

	       			arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value3).children().attr('data-type-id'), 'menu_parentID' : $(value2).children().attr('data-id'), 'menu_pagesID' : $(value3).children().attr('data-id'), 'menu_label' : navValue3, 'menu_link' : link3, 'menu_orderID' : loop3, 'menu_rand' : $(value3).children().attr('data-rand'), 'menu_rand_parentID' : $(value2).children().attr('data-rand') });
	       			loop3++;
	       		});
	       });

	    });

	    $('ol.sortable > li.mjs-nestedSortable-leaf').each(function(index, value) {
	    	var link = '';
	    	var navValue = $(value).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');
       		if($(value).children().attr('data-type-id') == 3) {
				navValue = $(value).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
				link = $(value).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');	
			}

	    	arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value).children().attr('data-type-id'), 'menu_parentID' : '0', 'menu_pagesID' : $(value).children().attr('data-id'), 'menu_label' : navValue, 'menu_link' : link, 'menu_orderID' : loop, 'menu_rand' : $(value).children().attr('data-rand'), 'menu_rand_parentID' : '0' });
	    	loop++;
	    });
	    
	    $('ol.sortable > li.mjs-nestedSortable-branch > ol > li.mjs-nestedSortable-leaf').each(function(index, value) {
	    	link = '';
	    	var navValue = $(value).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');

       		if($(value).children().attr('data-type-id') == 3) {
				navValue = $(value).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
				link = $(value).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');
			}

	       	arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value).children().attr('data-type-id'), 'menu_parentID' : $(value).parent().parent().children().attr('data-id'), 'menu_pagesID' : $(value).children().attr('data-id'), 'menu_label' : navValue, 'menu_link' : link, 'menu_orderID' : loop2, 'menu_rand' : $(value).children().attr('data-rand'), 'menu_rand_parentID' : $(value).parent().parent().children().attr('data-rand') });
	       	loop2++;
	    });

	    if(arr.length > 0) {
	    	var locationtop = 0;
	    	if ($('#locations-top').is(":checked")) {
		    	locationtop = parseInt($('#locations-top').val());
			}

			var locationsocial = 0;
			if ($('#locations-social').is(":checked")) {
		    	locationsocial = parseInt($('#locations-social').val());
			}

			$.ajax({
	            type: 'POST',
	            url: "<?=base_url('frontendmenu/savemenu')?>",
	            data: { 'elements' : arr, 'locationtop' : locationtop, 'locationsocial' : locationsocial },
	            dataType: "html",
	            success: function(data) {
	            	dd(data);
	      //       	var response = JSON.parse(data);
	      //       	if (response.status == false) {
       //               	var responseerror = response.errors;
       //                  if(responseerror.length > 0) {
       //                  	var i;
							// for (i = 0; i < responseerror.length; i++) { 
	      //                       toastr["error"](responseerror[i])
	      //                       toastr.options = {
	      //                         "closeButton": true,
	      //                         "debug": false,
	      //                         "newestOnTop": false,
	      //                         "progressBar": false,
	      //                         "positionClass": "toast-top-right",
	      //                         "preventDuplicates": false,
	      //                         "onclick": null,
	      //                         "showDuration": "500",
	      //                         "hideDuration": "500",
	      //                         "timeOut": "5000",
	      //                         "extendedTimeOut": "1000",
	      //                         "showEasing": "swing",
	      //                         "hideEasing": "linear",
	      //                         "showMethod": "fadeIn",
	      //                         "hideMethod": "fadeOut"
	      //                       }
							// }
       //                  }
       //              } else {
       //                  toastr["success"](response.message)
       //                  toastr.options = {
       //                      "closeButton": true,
       //                      "debug": false,
       //                      "newestOnTop": false,
       //                      "progressBar": false,
       //                      "positionClass": "toast-top-right",
       //                      "preventDuplicates": false,
       //                      "onclick": null,
       //                      "showDuration": "500",
       //                      "hideDuration": "500",
       //                      "timeOut": "5000",
       //                      "extendedTimeOut": "1000",
       //                      "showEasing": "swing",
       //                      "hideEasing": "linear",
       //                      "showMethod": "fadeIn",
       //                      "hideMethod": "fadeOut"
       //                  }
       //              }
	            }
	        });
		    
	    }
	});

	


</script>