<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property document_m $document_m
 * @property email_m $email_m
 * @property error_m $error_m
 */
class Frontend_Controller extends MY_Controller {
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

    private $_frontendTheme = '';
    private $_frontendThemePath = '';
    private $_frontendThemeBasePath = '';


    protected $bladeView;

    function __construct () {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'file'));
        $this->load->library('blade');
        $this->load->library("session");
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('date');
        $this->load->helper('form');
        $this->load->helper("frontenddata");
        $this->load->model('frontend_setting_m');
        $this->load->model('setting_m');
        $this->load->model('classes_m');
        $this->load->model('pages_m');
        $this->load->model('posts_m');
        $this->load->model('fmenu_relation_m');
        $this->load->model('fmenu_m');
        $this->load->model('event_m');
        $this->load->model('teacher_m');
        $this->load->model('notice_m');
        $this->load->model('sociallink_m');

        $this->data['backend_setting'] = $this->setting_m->get_setting();
        $this->data['frontend_setting'] = $this->frontend_setting_m->get_frontend_setting();
        $this->data['frontend_topbar'] = $this->fmenu_m->get_single_fmenu(array('topbar' => 1));
        $this->data['frontend_social'] = $this->fmenu_m->get_single_fmenu(array('social' => 1));
        
        $schoolyearID = $this->data['backend_setting']->school_year;
        if($this->session->userdata('defaultschoolyearID')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
        }

        /* Start All Data Call */
        $this->data['events'] = $this->event_m->get_order_by_event(array('schoolyearID' => $schoolyearID));
        $this->data['teachers'] = $this->teacher_m->get_teacher();
        $this->data['notices'] = $this->notice_m->get_order_by_notice(array('schoolyearID' => $schoolyearID));
        $this->data['sociallink'] = pluck_multi_array_key($this->sociallink_m->get_sociallink(), 'obj', 'usertypeID', 'userID');
        $this->data['classes'] = $this->classes_m->general_get_classes();
        $this->data['countrys'] = $this->getCountrys();
        /* Close All Data Call */

        $this->data['homepage'] = $this->pages_m->get_one($this->data['frontend_topbar']);

        $this->data['forntend_pages'] = pluck($this->pages_m->get_pages(), 'obj', 'pagesID');
        $this->data['forntend_posts'] = pluck($this->posts_m->get_posts(), 'obj', 'postsID');
        $this->data['menu'] = $this->callMenu();

        $this->_frontendTheme = strtolower($this->data["backend_setting"]->frontend_theme);;
        $this->_frontendThemePath = 'frontend/'. $this->_frontendTheme.'/';
        $this->_frontendThemeBasePath = FCPATH.'frontend/'. $this->_frontendTheme.'/';

        $this->blade->load_view_root($this->_frontendThemeBasePath);
        $this->bladeView = $this->blade;
        $this->bladeView->set('backend', $this->data['backend_setting']);
        $this->bladeView->set('frontend', $this->data['frontend_setting']);
        $this->bladeView->set('frontendThemePath', $this->_frontendThemePath);

        if ( count($this->data['homepage']) ) {
            $this->data['homepageTitle'] = $this->data['homepage']->menu_label;
            $this->bladeView->set('homepageTitle', $this->data['homepage']->menu_label);
            if ( $this->data['homepage']->menu_typeID == 1 ) {
                $page                       = $this->pages_m->get_single_pages([ 'pagesID' => $this->data['homepage']->menu_pagesID ]);
                $this->data['homepage']     = $page;
                $this->data['homepageType'] = 'page';
                $this->bladeView->set('homepage', $page);
                $this->bladeView->set('homepageType', 'page');
            } elseif ( $this->data['homepage']->menu_typeID == 2 ) {
                $post                       = $this->posts_m->get_single_posts([ 'postsID' => $this->data['homepage']->menu_pagesID ]);
                $this->data['homepage']     = $post;
                $this->data['homepageType'] = 'post';
                $this->bladeView->set('homepage', $post);
                $this->bladeView->set('homepageType', 'post');
            } else {
                $nonehomepage           = (object) [ 'url' => '' ];
                $this->data['homepage'] = $nonehomepage;
                $this->bladeView->set('homepage', $nonehomepage);
                $this->bladeView->set('homepageType', 'none');
            }
        } else {
            $nonehomepage                = (object) [ 'url' => '' ];
            $this->data['homepage']      = $nonehomepage;
            $this->data['homepageTitle'] = '';
            $this->bladeView->set('homepage', $nonehomepage);
            $this->bladeView->set('homepageType', 'none');
            $this->bladeView->set('homepageTitle', $this->data['homepageTitle']);
        }

        $this->bladeView->set('events', $this->data['events']);
        $this->bladeView->set('teachers', $this->data['teachers']);
        $this->bladeView->set('notices', $this->data['notices']);
        $this->bladeView->set('sociallink', $this->data['sociallink']);
        $this->bladeView->set('classes', $this->data['classes']);
        $this->bladeView->set('countrys', $this->data['countrys']);
        $this->bladeView->set('fpages', $this->data['forntend_pages']);
        $this->bladeView->set('fposts', $this->data['forntend_posts']);
        $this->bladeView->set('menu', $this->data['menu']);
        $this->bladeView->set('schoolyearID', $schoolyearID);
    }

    private function callMenu() {
        $frontendTopbarMenu = [];
        $frontendSocialMenu = [];
        $frontendTopbarQueryMenus = [];
        $frontendSocialQueryMenus = [];
        if(count($this->data['frontend_topbar'])) {
            $frontendTopbarQueryMenus = $this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $this->data['frontend_topbar']->fmenuID));
            $frontendTopbarMenu = $this->orderMenu($frontendTopbarQueryMenus);
        }

        if(count($this->data['frontend_topbar'])) {
            if($this->data['frontend_topbar']->social == 1) {
                $frontendSocialMenu = $frontendTopbarMenu;
                $frontendSocialQueryMenus = $frontendTopbarQueryMenus;
            } else {
                if(count($this->data['frontend_social'])) {
                    $frontendSocialQueryMenus = $this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $this->data['frontend_social']->fmenuID));
                    $frontendSocialMenu = $this->orderMenu($frontendSocialQueryMenus);
                }
            }
        } else {
            if(count($this->data['frontend_social'])) {
                $frontendSocialQueryMenus = $this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $this->data['frontend_social']->fmenuID));
                $frontendSocialMenu = $this->orderMenu($frontendSocialQueryMenus);
            }
        }

        $returnArray = array('frontendTopbarMenus' => $frontendTopbarMenu, 'frontendSocialMenus' => $frontendSocialMenu, 'frontendTopbarQueryMenus' => $frontendTopbarQueryMenus, 'frontendSocialQueryMenus' => $frontendSocialQueryMenus);

        return $returnArray;
    }

    private function orderMenu($elements) {
        $mergeElements = [];
        if(count($elements)) {
            $elements = json_decode(json_encode($elements), true);

            if(count($elements)) {
                foreach ($elements as $elementKey => $element) {
                    if($element['menu_parentID'] == 0) {
                        $mergeElements[] = $element;
                        unset($elements[$elementKey]);
                    }
                }

                foreach ($elements as $elementKey => $element) {
                    if(count($mergeElements)) {
                        foreach ($mergeElements as $mergeElementKey =>  $mergeElement) {
                            if($element['menu_rand_parentID'] == $mergeElement['menu_rand']) {
                                $mergeElements[$mergeElementKey]['child'][] = $element;
                                unset($elements[$elementKey]);
                            }       
                        }
                    }
                }
                
                foreach ($elements as $elementKey => $element) {
                    if(count($mergeElements)) {
                        foreach ($mergeElements as $mergeElementKey =>  $mergeElement) {
                            if(isset($mergeElement['child'])) {
                                if(count($mergeElement['child'])) {
                                    foreach ($mergeElement['child'] as $secondLayerKey => $secondLayer) {
                                        if($secondLayer['menu_rand'] == $element['menu_rand_parentID']) {
                                            $mergeElements[$mergeElementKey]['child'][$secondLayerKey]['child'][] = $element;
                                        }
                                    }
                                }
                            }       
                        }
                    }
                }
            }
        }

        return $mergeElements;
    }

    private function getCountrys() {
        $country = array(
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "BQ" => "British Antarctic Territory",
            "IO" => "British Indian Ocean Territory",
            "VG" => "British Virgin Islands",
            "BN" => "Brunei",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CT" => "Canton and Enderbury Islands",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos [Keeling] Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo - Brazzaville",
            "CD" => "Congo - Kinshasa",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "CI" => "Côte d’Ivoire",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "NQ" => "Dronning Maud Land",
            "DD" => "East Germany",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "FQ" => "French Southern and Antarctic Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "HN" => "Honduras",
            "HK" => "Hong Kong SAR China",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JT" => "Johnston Island",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Laos",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macau SAR China",
            "MK" => "Macedonia",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "FX" => "Metropolitan France",
            "MX" => "Mexico",
            "FM" => "Micronesia",
            "MI" => "Midway Islands",
            "MD" => "Moldova",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar [Burma]",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NT" => "Neutral Zone",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "KP" => "North Korea",
            "VD" => "North Vietnam",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PC" => "Pacific Islands Trust Territory",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territories",
            "PA" => "Panama",
            "PZ" => "Panama Canal Zone",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "YD" => "People's Democratic Republic of Yemen",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn Islands",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RO" => "Romania",
            "RU" => "Russia",
            "RW" => "Rwanda",
            "RE" => "Réunion",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "KR" => "South Korea",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syria",
            "ST" => "São Tomé and Príncipe",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UM" => "U.S. Minor Outlying Islands",
            "PU" => "U.S. Miscellaneous Pacific Islands",
            "VI" => "U.S. Virgin Islands",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "SU" => "Union of Soviet Socialist Republics",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "ZZ" => "Unknown or Invalid Region",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VA" => "Vatican City",
            "VE" => "Venezuela",
            "VN" => "Vietnam",
            "WK" => "Wake Island",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
            "AX" => "Åland Islands",
            );
        return $country;
    }
}

