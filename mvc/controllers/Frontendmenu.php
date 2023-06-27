<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontendmenu extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model('fmenu_m');
		$this->load->model('pages_m');
		$this->load->model("posts_m");
		$this->load->model("fmenu_relation_m");

		$language = $this->session->userdata('lang');
        $this->lang->load('frontendmenu', $language);
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/wp-menu/assets/css/style.css',
			),
			'js' => array(
				'assets/wp-menu/assets/js/script.js',
				'assets/wp-menu/assets/js/jquery-ui-1.12.1/jquery-ui.min.js',
				'assets/nestedSortable/jquery.mjs.nestedSortable.js',
			)
		);

		$this->data['fmenus'] = $this->fmenu_m->get_fmenu();
		$this->data['activefmenu'] = $this->fmenu_m->get_single_fmenu(array('status' => 1));
		$this->data['pages'] = $this->pages_m->get_pages();
		$this->data['posts'] = $this->posts_m->get_posts();

		$this->data['pluckpages'] = pluck($this->data['pages'], 'obj', 'pagesID');
		$this->data['pluckposts'] = pluck($this->data['posts'], 'obj', 'postsID');

		$activefmenuID = 0;
		$activefmenuName = '';
		if(count($this->data['activefmenu'])) {
			$activefmenuID = $this->data['activefmenu']->fmenuID;
			$activefmenuName = $this->data['activefmenu']->menu_name;
		} else {
			if(count($this->data['fmenus'])) {
				$activefmenuID = $this->data['fmenus'][0]->fmenuID;
				$activefmenuName = $this->data['fmenus'][0]->menu_name;
			}
		}

		$this->data['getactivefmenuID'] = $activefmenuID;
		$this->data['getactivefmenuName'] = $activefmenuName;
		$this->data['menushow'] = $this->ordermenu($this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $activefmenuID)));

		$this->data["subview"] = "frontendmenu/index";
        $this->load->view('_layout_main', $this->data);
	}

	public function getpages() {
		if($_POST) {
			$pages = $this->input->post('pages');
			$pageArrays = [];
			if(count($pages)) {
				foreach ($pages as $page) {
					$expPage = explode('-', $page);
					$pageID = $expPage[1];
					$pageArrays[] = $this->pages_m->get_single_pages(array('pagesID' => $pageID));
				}
				$this->data['pageArrays'] = $pageArrays;
				echo $this->load->view('frontendmenu/getPages', $this->data, true);
			}
		}
	}

	public function getposts() {
		if($_POST) {
			$posts = $this->input->post('posts');
			$postArrays = [];
			if(count($posts)) {
				foreach ($posts as $post) {
					$expPost = explode('-', $post);
					$postID = $expPost[1];
					$postArrays[] = $this->posts_m->get_single_posts(array('postsID' => $postID));
				}
				$this->data['postArrays'] = $postArrays;
				echo $this->load->view('frontendmenu/getPosts', $this->data, true);
			}
		}
	}

	public function getlinks() {
		if($_POST) {
			$urlLinkField = xssRemove($this->input->post('url_link_field'));
			$urlLinkText = xssRemove($this->input->post('url_link_text'));

			if(!empty($urlLinkField) && !empty($urlLinkText)) {
				if (strpos($urlLinkField, 'http://') === false && strpos($urlLinkField, 'https://') === false) {
					if($urlLinkField != '#') {
						$urlLinkField = 'http://'.$urlLinkField;	
					}
				}

				$this->data['urlLinkField'] = $urlLinkField;
				$this->data['urlLinkText'] = $urlLinkText;
				echo $this->load->view('frontendmenu/getLinks', $this->data, true);
			}
		}
	}

	public function managelocation() {
		$fmenus = $this->fmenu_m->get_fmenu();
		if($_POST) {
			if(count($this->fmenu_m->get_fmenu())) {
				$topMenuList =  $this->input->post('top_menu_list');
				$socialMenuList =  $this->input->post('social_menu_list');
				if(count($fmenus)) {
					foreach ($fmenus as $fmenu) {
						$this->fmenu_m->update_fmenu(array('topbar' => 0), $fmenu->fmenuID);
						$this->fmenu_m->update_fmenu(array('social' => 0), $fmenu->fmenuID);
					}
				}
				$this->fmenu_m->update_fmenu(array('topbar' => 1), $topMenuList);
				$this->fmenu_m->update_fmenu(array('social' => 1), $socialMenuList);
				
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			}
		}
		redirect(base_url('frontendmenu/index'));
	}

	protected $_fmenuID = 0;
	public function savemenu() {
		$retArray['status'] = FALSE;
		if($_POST) {
			if(count($this->fmenu_m->get_fmenu())) {
				$status = FALSE;
				$elements = $this->input->post('elements');
				$locationtop = $this->input->post('locationtop');
				$locationsocial = $this->input->post('locationsocial');
				$editmenuID = $this->input->post('editmenuID');
				$menuname = $this->input->post('menuname');

				if(empty($locationtop) || !is_numeric($locationtop)) {
					$locationtop = 0;
				}

				if(empty($locationsocial) || !is_numeric($locationsocial)) {
					$locationsocial = 0;
				}

				if((int)$editmenuID && $editmenuID > 0) {
					$this->_fmenuID = $editmenuID;
				}

				if(count($elements)) {
					$validation = $this->validation_save_menu($elements);
					if(count($validation)) {
						$retArray['status'] = FALSE;
						$retArray['errors'] = $validation;
						echo json_encode($retArray);
						exit;
					} else {
						$elements = $this->validation_xss_clen($elements);
						if(count($elements)) {

							$this->fmenu_relation_m->delete_fmenu_relation_by_array(array('fmenuID' => $this->_fmenuID));

							if($locationtop) {
								$this->fmenu_m->update_fmenu_by_array(array('topbar' => 0), array('topbar' => 1));
							}

							if($locationsocial) {
								$this->fmenu_m->update_fmenu_by_array(array('social' => 0), array('social' => 1));
							}

							$this->fmenu_m->update_fmenu(array('topbar' => $locationtop, 'social' => $locationsocial, 'menu_name' => $menuname, 'status' => 1), $this->_fmenuID);

							$this->fmenu_relation_m->insert_batch_fmenu_relation($elements);

							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						}
						$retArray['status'] = TRUE; 
						$retArray['message'] = $this->lang->line('menu_success');
						echo json_encode($retArray);
						exit;
					}
				} else {
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['errors'][] = $this->lang->line('frontendmenu_error_menu'); 
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['status'] = TRUE; 
			echo json_encode($retArray);
			exit;
		}
	}

	private function validation_save_menu($elements) {
		$validationerror = [];
		foreach ($elements as $key => $element) {
			if(strlen($element['menu_label']) > 253 ) {
				$validationerror[] = $this->lang->line('frontendmenu_error_label');
			} 
		}
		return $validationerror;
	}

	private function validation_xss_clen($elements) {
		$retArray = [];
		if(count($elements)) {
			foreach ($elements as $key => $element) {
				$this->_fmenuID = $element['fmenuID'];
				$element['menu_label'] = xssRemove($element['menu_label']);
				$element['menu_status'] = 1;
				if(!empty($element['menu_link'])) {
					$element['menu_link'] = xssRemove($element['menu_link']);
				} else {
					$element['menu_link'] = '#';
				}
				$retArray[] = $element;
			}
		}
		return $retArray;
	}

	private function ordermenu($elements) {
		$mergeelements = [];
		if(count($elements)) {
			$elements = json_decode(json_encode($elements), true);

			if(count($elements)) {
				foreach ($elements as $elementkey => $element) {
					if($element['menu_parentID'] == 0) {
						$mergeelements[] = $element;
						unset($elements[$elementkey]);
					}
				}

				foreach ($elements as $elementkey => $element) {
					if(count($mergeelements)) {
						foreach ($mergeelements as $mergeelementkey =>  $mergeelement) {
							if($element['menu_rand_parentID'] == $mergeelement['menu_rand']) {
								$mergeelements[$mergeelementkey]['child'][] = $element; 		
								unset($elements[$elementkey]);
							} 		
						}
					}
				}
				
				foreach ($elements as $elementkey => $element) {
					if(count($mergeelements)) {
						foreach ($mergeelements as $mergeelementkey =>  $mergeelement) {
							if(isset($mergeelement['child'])) {
								if(count($mergeelement['child'])) {
									foreach ($mergeelement['child'] as $secandlayerkey => $secandlayer) {
										if($secandlayer['menu_rand'] == $element['menu_rand_parentID']) {
											$mergeelements[$mergeelementkey]['child'][$secandlayerkey]['child'][] = $element; 
										}
									}
								}
							}		
						}
					}
				}
			}
		}


		$string = '';
		if(count($mergeelements)) {
			foreach ($mergeelements as $mergeelement) {
				if($mergeelement['menu_typeID'] == 1) {
					$this->data['mergeelement'] = $mergeelement;
					$string .= $this->load->view('frontendmenu/generatePages', $this->data, true);
				} elseif($mergeelement['menu_typeID'] == 2) {
					$this->data['mergeelement'] = $mergeelement;
					$string .= $this->load->view('frontendmenu/generatePosts', $this->data, true);
				} elseif($mergeelement['menu_typeID'] == 3) {
					$this->data['mergeelement'] = $mergeelement;
					$string .= $this->load->view('frontendmenu/generateLinks', $this->data, true);
				}

				if(isset($mergeelement['child'])) {
					$string .= '<ol>';
					foreach ($mergeelement['child'] as $mergeelementsec) {
						if($mergeelementsec['menu_typeID'] == 1) {
							$this->data['mergeelement'] = $mergeelementsec;
							$string .= $this->load->view('frontendmenu/generatePages', $this->data, true);
						} elseif($mergeelementsec['menu_typeID'] == 2) {
							$this->data['mergeelement'] = $mergeelementsec;
							$string .= $this->load->view('frontendmenu/generatePosts', $this->data, true);
						} elseif($mergeelementsec['menu_typeID'] == 3) {
							$this->data['mergeelement'] = $mergeelementsec;
							$string .= $this->load->view('frontendmenu/generateLinks', $this->data, true);
						}

						if(isset($mergeelementsec['child'])) {
							$string .= '<ol>';
							foreach ($mergeelementsec['child'] as $mergeelementthr) {
								if($mergeelementthr['menu_typeID'] == 1) {
									$this->data['mergeelement'] = $mergeelementthr;
									$string .= $this->load->view('frontendmenu/generatePages', $this->data, true);
									$string .= '</li>';
								} elseif($mergeelementthr['menu_typeID'] == 2) {
									$this->data['mergeelement'] = $mergeelementthr;
									$string .= $this->load->view('frontendmenu/generatePosts', $this->data, true);
									$string .= '</li>';
								} elseif($mergeelementthr['menu_typeID'] == 3) {
									$this->data['mergeelement'] = $mergeelementthr;
									$string .= $this->load->view('frontendmenu/generateLinks', $this->data, true);
									$string .= '</li>';
								}
							}
							$string .= '</ol>';
						}
						$string .= '</li>';
					}
					$string .= '</ol>';
				} 
				$string .= '</li>';
			}
		}

		return $string;
	}

	public function editmenutoggle() {
		if($_POST) {
			$fmenuID = $this->input->post('fmenuID');
			if($fmenuID != '') {
				if((int)$fmenuID) {
					$fmenus = $this->fmenu_m->get_fmenu();
					if(count($fmenus)) {
						foreach ($fmenus as  $fmenu) {
							$this->fmenu_m->update_fmenu(array('status' => 0), $fmenu->fmenuID);							
						}
					}
					$this->fmenu_m->update_fmenu(array('status' => 1), $fmenuID);
				}
			}
		}
	}

	public function deletefmenu() {
		if($_POST) {
			$fmenuID = $this->input->post('fmenuID');
			if($fmenuID != '') {
				if((int)$fmenuID) {
					$fmenus = $this->fmenu_m->delete_fmenu($fmenuID);
					$fmenurelation = $this->fmenu_relation_m->delete_fmenu_relation_by_array(array('fmenuID' => $fmenuID));
				}
			}
		}
	}

	public function createnewmenu() {
		$retArray['status'] = FALSE;
		if($_POST) {
			$menuName = $this->input->post('menuname');
			if(!empty($menuName)) {
				$menuName = xssRemove($menuName);
				if(strlen($menuName) <= 128) {
					$fmenu = $this->fmenu_m->get_fmenu();
					if(count($fmenu)) {
						$this->fmenu_m->update_fmenu_by_array(array('status' => 0), array('status' => 1));
					}

					$this->fmenu_m->insert_fmenu(array('menu_name' => $menuName, 'status' => 1, 'topbar' => 0, 'social' => 0));
					$retArray['status'] = TRUE;
					$retArray['message'] = $this->lang->line('menu_success');
				} else {
					$retArray['error'] = $this->lang->line('frontendmenu_error_menu_length');
				}
			} else {
				$retArray['error'] = $this->lang->line('frontendmenu_error_menu_required');
			}
		}
		
		echo json_encode($retArray);
		exit;
	}
}