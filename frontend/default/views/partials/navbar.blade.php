    <nav class="navbar gray-bg mb-0">
        <div class="container">
    
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    <span class="lin-menu"></span>
                </button>
                @if(count($homepage))
                    <?php $hometype = (isset($homepage->pagesID) ? 'page' : (isset($homepage->postsID) ? 'post' : '')); ?>
                    <a class="navbar-brand" href="{{ base_url('frontend/'.$hometype.'/'.$homepage->url) }}"> {{ frontendColorStyle(namesorting($backend->sname, 20)) }} </a>
                @else
                    <a class="navbar-brand"> {{ frontendColorStyle(namesorting($backend->sname, 20)) }} </a>
                @endif
            </div>
        
            <?php $mobileFormet = ''; ?>
            <div class="collapse navbar-collapse show" id="main-navbar">
                <ul class="main-menu">
                    <?php
                        if(isset($menu['frontendTopbarMenus'])) {
                            if(count($menu['frontendTopbarMenus'])) {
                                $firstLi = FALSE;
                                $secondLi = FALSE;
                                $loginActiveStatusArray = [];
                                $countMenu = count($menu['frontendTopbarMenus']);
                                $i = 0;
                                foreach ($menu['frontendTopbarMenus'] as $frontendTopbarMenuKye => $frontendTopbarMenu) {
                                    if($frontendTopbarMenu['menu_typeID'] == 1) {
                                        if(!isset($frontendTopbarMenu['child'])) {
                                            if(isset($fpages[$frontendTopbarMenu['menu_pagesID']])) {
                                                echo '<li><a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].'</a></li>';
                                                $mobileFormet .= '<li><a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].'</a></li>';
                                            }
                                        } else {
                                            if(isset($fpages[$frontendTopbarMenu['menu_pagesID']])) {
                                                if((($countMenu-1) == $i) || (($countMenu-2) == $i) || (($countMenu-3) == $i)) {
                                                    echo '<li class="dropdown-left">';    
                                                } else {
                                                    echo '<li>';
                                                }
                                                    echo '<a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].' <i class="fa fa-angle-down"></i></a>';
                                                    $firstLi = TRUE;

                                                    $mobileFormet .= '<li>';
                                                    $mobileFormet .= '<a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].' <i class="fa fa-angle-down"></i></a>';
                                            }
                                        }
                                    } elseif($frontendTopbarMenu['menu_typeID'] == 2) {
                                        if(!isset($frontendTopbarMenu['child'])) {
                                            if(isset($fposts[$frontendTopbarMenu['menu_pagesID']])) {
                                                echo '<li><a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].'</a></li>';
                                                $mobileFormet .= '<li><a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].'</a></li>';
                                            }
                                        } else {
                                            if(isset($fposts[$frontendTopbarMenu['menu_pagesID']])) {
                                                if((($countMenu-1) == $i) || (($countMenu-2) == $i) || (($countMenu-3) == $i)) {
                                                    echo '<li class="dropdown-left">';    
                                                } else {
                                                    echo '<li>';
                                                }
                                                    echo '<a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].' <i class="fa fa-angle-down"></i></a>';
                                                    $firstLi = TRUE;

                                                    $mobileFormet .= '<li>';
                                                    $mobileFormet .= '<a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenu['menu_pagesID']]->url).'">'.$frontendTopbarMenu['menu_label'].' <i class="fa fa-angle-down"></i></a>';
                                            }
                                        }
                                    } elseif($frontendTopbarMenu['menu_typeID'] == 3) {
                                        if(!isset($frontendTopbarMenu['child'])) {
                                            echo '<li><a href="'.$frontendTopbarMenu['menu_link'].'">'.$frontendTopbarMenu['menu_label'].'</a></li>';
                                            $mobileFormet .= '<li><a href="'.$frontendTopbarMenu['menu_link'].'">'.$frontendTopbarMenu['menu_label'].'</a></li>';

                                            
                                            if($frontendTopbarMenu['menu_link'] == base_url('signin/index')) {
                                                $loginActiveStatusArray[] = TRUE;
                                            }
                                        } else {                                            
                                            if((($countMenu-1) == $i) || (($countMenu-2) == $i) || (($countMenu-3) == $i)) {
                                                echo '<li class="dropdown-left">';    
                                            } else {
                                                echo '<li>';    
                                            }
                                                echo '<a href="'.$frontendTopbarMenu['menu_link'].'">'.$frontendTopbarMenu['menu_label'].' <i class="fa fa-angle-down"></i></a>';
                                                $firstLi = TRUE;

                                                $mobileFormet .= '<li>';
                                                $mobileFormet .= '<a href="'.$frontendTopbarMenu['menu_link'].'">'.$frontendTopbarMenu['menu_label'].' <i class="fa fa-angle-down"></i></a>';

                                            if($frontendTopbarMenu['menu_link'] == base_url('signin/index')) {
                                                $loginActiveStatusArray[] = TRUE;
                                            }
                                        }
                                    }

                                    if(isset($frontendTopbarMenu['child'])) {
                                        echo '<ul class="sub-menu">';
                                        $mobileFormet .= '<ul class="sub-menu">';
                                            foreach ($frontendTopbarMenu['child'] as $frontendTopbarMenuSec) {
                                                if($frontendTopbarMenuSec['menu_typeID'] == 1) {
                                                    if(!isset($frontendTopbarMenuSec['child'])) {
                                                        if(isset($fpages[$frontendTopbarMenuSec['menu_pagesID']])) {
                                                            echo '<li><a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].'</a></li>';
                                                            $mobileFormet .= '<li><a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].'</a></li>';
                                                        }
                                                    } else {
                                                        if(isset($fpages[$frontendTopbarMenuSec['menu_pagesID']])) {
                                                            echo '<li>';
                                                                echo '<a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].' <i class="fa fa-angle-left"></i></a>';
                                                            $secondLi = TRUE;
                                                            $mobileFormet .= '<li>';
                                                            $mobileFormet .= '<a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].' <i class="fa fa-angle-left"></i></a>';
                                                        }
                                                    }
                                                } elseif($frontendTopbarMenuSec['menu_typeID'] == 2) {
                                                    if(!isset($frontendTopbarMenuSec['child'])) {
                                                        if(isset($fposts[$frontendTopbarMenuSec['menu_pagesID']])) {
                                                            echo '<li><a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].'</a></li>';
                                                            $mobileFormet .= '<li><a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].'</a></li>';
                                                        }
                                                    } else {
                                                        if(isset($fposts[$frontendTopbarMenuSec['menu_pagesID']])) {
                                                            echo '<li>';
                                                                echo '<a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].' <i class="fa fa-angle-left"></i></a>';
                                                            $secondLi = TRUE;
                                                            $mobileFormet .= '<li>'; 
                                                            $mobileFormet .= '<a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenuSec['menu_pagesID']]->url).'">'.$frontendTopbarMenuSec['menu_label'].' <i class="fa fa-angle-left"></i></a>';
                                                        }
                                                    }
                                                } elseif($frontendTopbarMenuSec['menu_typeID'] == 3) {
                                                    if(!isset($frontendTopbarMenuSec['child'])) {
                                                        echo '<li><a href="'.$frontendTopbarMenuSec['menu_link'].'">'.$frontendTopbarMenuSec['menu_label'].'</a></li>';
                                                        $mobileFormet .= '<li><a href="'.$frontendTopbarMenuSec['menu_link'].'">'.$frontendTopbarMenuSec['menu_label'].'</a></li>';

                                                        if($frontendTopbarMenuSec['menu_link'] == base_url('signin/index')) {
                                                            $loginActiveStatusArray[] = TRUE;
                                                        }
                                                    } else {
                                                        echo '<li>';
                                                            echo '<a href="'.$frontendTopbarMenuSec['menu_link'].'">'.$frontendTopbarMenuSec['menu_label'].' <i class="fa fa-angle-left"></i></a>';
                                                        $secondLi = TRUE;
                                                        $mobileFormet .= '<li>';
                                                        $mobileFormet .= '<a href="'.$frontendTopbarMenuSec['menu_link'].'">'.$frontendTopbarMenuSec['menu_label'].' <i class="fa fa-angle-left"></i></a>';

                                                        if($frontendTopbarMenuSec['menu_link'] == base_url('signin/index')) {
                                                            $loginActiveStatusArray[] = TRUE;
                                                        }
                                                    }
                                                }

                                                if(isset($frontendTopbarMenuSec['child'])) {
                                                    echo '<ul class="sub-menu">';
                                                    $mobileFormet .= '<ul class="sub-menu">';
                                                    foreach ($frontendTopbarMenuSec['child'] as $frontendTopbarMenuThr) {
                                                        if($frontendTopbarMenuThr['menu_typeID'] == 1) {
                                                            echo '<li><a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenuThr['menu_pagesID']]->url).'">'.$frontendTopbarMenuThr['menu_label'].'</a></li>';
                                                            $mobileFormet .= '<li><a href="'.base_url('frontend/page/'.$fpages[$frontendTopbarMenuThr['menu_pagesID']]->url).'">'.$frontendTopbarMenuThr['menu_label'].'</a></li>';
                                                        } elseif($frontendTopbarMenuThr['menu_typeID'] == 2) {
                                                            echo '<li><a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenuThr['menu_pagesID']]->url).'">'.$frontendTopbarMenuThr['menu_label'].'</a></li>';
                                                            $mobileFormet .= '<li><a href="'.base_url('frontend/post/'.$fposts[$frontendTopbarMenuThr['menu_pagesID']]->url).'">'.$frontendTopbarMenuThr['menu_label'].'</a></li>';
                                                        } elseif($frontendTopbarMenuThr['menu_typeID'] == 3) {
                                                            echo '<li><a href="'.$frontendTopbarMenuThr['menu_link'].'">'.$frontendTopbarMenuThr['menu_label'].'</a></li>';
                                                            $mobileFormet .= '<li><a href="'.$frontendTopbarMenuThr['menu_link'].'">'.$frontendTopbarMenuThr['menu_label'].'</a></li>';

                                                            if($frontendTopbarMenuThr['menu_link'] == base_url('signin/index')) {
                                                                $loginActiveStatusArray[] = TRUE;
                                                            }
                                                        }
                                                    }
                                                    echo '</ul>';
                                                    $mobileFormet .= '</ul>';
                                                }
                                            }

                                            if($secondLi) {
                                                echo '</li>';
                                                $mobileFormet .= '</li>';
                                            }
                                        echo '</ul>';
                                        $mobileFormet .= '</ul>';
                                    }
                                    $i++;
                                }


                                if(frontendData::get_frontend('login_menu_status')) {
                                    if(!in_array(TRUE, $loginActiveStatusArray)) {
                                        echo '<li><a href="'.base_url('signin/index').'">Login</a></li>';
                                        $mobileFormet .= '<li><a href="'.base_url('signin/index').'">Login</a></li>';
                                    }
                                }
                                
                                if($firstLi) {
                                    echo '</li>';
                                    $mobileFormet .= '</li>'; 
                                }
                            } else {
                                echo '<li><a href="'.base_url('signin/index').'">Login</a></li>';
                                $mobileFormet .= '<li><a href="'.base_url('signin/index').'">Login</a></li>';
                            }
                        } else {
                            echo '<li><a href="'.base_url('signin/index').'">Login</a></li>';
                            $mobileFormet .= '<li><a href="'.base_url('signin/index').'">Login</a></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
        <ul class="mobile-menu">
           <?=$mobileFormet?>
        </ul>
    </nav>