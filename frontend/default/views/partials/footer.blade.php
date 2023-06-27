
<footer>
    <div class="footer-top-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="footer-widget">
                        <!-- Start Logo -->
                        <div class="logo footer-logo text-uppercase">
                            <h1>
                                @if(count($homepage))
                                    <?php $hometype = (isset($homepage->pagesID) ? 'page' : (isset($homepage->postsID) ? 'post' : '')); ?>
                                    <a href="{{ base_url('frontend/'.$hometype.'/'.$homepage->url) }}"> {{ frontendColorStyle(namesorting($backend->sname, 16)) }} </a>
                                @else
                                    <a> {{ frontendColorStyle(namesorting($backend->sname, 16)) }} </a>
                                @endif

                            </h1>
                        </div>
                        <!-- End Logo -->
                        <p>{{ frontendData::get_frontend('description') }}</p>
                        <div class="footer-social">
                            <ul>
                                <li><a href="{{ frontendData::get_frontend('facebook') }}"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{ frontendData::get_frontend('twitter') }}"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="{{ frontendData::get_frontend('linkedin') }}"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="{{ frontendData::get_frontend('youtube') }}"><i class="fa fa-youtube"></i></a></li>
                                <li><a href="{{ frontendData::get_frontend('google') }}"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if(isset($menu['frontendSocialQueryMenus']))
                    @if(count($menu['frontendSocialQueryMenus']))
                        <div class="col-md-3 col-md-offset-1 col-sm-4">
                            <div class="footer-widget">
                                <h2>Information</h2>
                                <ul>
                        <?php $i = 1; ?>
                        <?php $countFrontendSocialQueryMenus = count($menu['frontendSocialQueryMenus']); ?>
                        @foreach ($menu['frontendSocialQueryMenus'] as $frontendSocialQueryMenu)
                            <?php 
                                $url = '#';
                                if($frontendSocialQueryMenu->menu_typeID == 1) {
                                    if(isset($fpages[$frontendSocialQueryMenu->menu_pagesID])) {
                                        $url = base_url('frontend/page/'.$fpages[$frontendSocialQueryMenu->menu_pagesID]->url);
                                    }
                                } elseif ($frontendSocialQueryMenu->menu_typeID == 2) {
                                    if(isset($fposts[$frontendSocialQueryMenu->menu_pagesID])) {
                                        $url = base_url('frontend/post/'.$fposts[$frontendSocialQueryMenu->menu_pagesID]->url);
                                    }
                                } elseif($frontendSocialQueryMenu->menu_typeID == 3) {
                                    $url = $frontendSocialQueryMenu->menu_link;
                                }
                            ?>
                            <li><a href="{{ $url }}">{{ $frontendSocialQueryMenu->menu_label }}</a></li>
                            @if($i == 5)
                                        </ul>
                                    </div>
                                </div>
                                @if($countFrontendSocialQueryMenus > 5)
                                    <div class="col-md-3 col-md-offset-1 col-sm-4">
                                        <div class="footer-widget">
                                            <h2>Usefull links</h2>
                                            <ul>
                                @endif
                            @endif
                            @if(($i == 10) || ($countFrontendSocialQueryMenus == $i))

                                        </ul>
                                    </div>
                                </div>
                                <?php break; ?>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                    @endif
                @endif
                
            </div>
        </div>
    </div>
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="copyright text-center">
                        {{ frontendData::get_backend('footer') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>