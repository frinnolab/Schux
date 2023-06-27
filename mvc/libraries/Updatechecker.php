<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Updatechecker
    {
        public function verifyValidUser( $array = [], $arrayMerge = true )
        {
          //  dd('hapa verify valid user');
          //  $this->_dataMaker($array, $arrayMerge);
            //$apiCurl = $this->_apiCurl($array);
            $apiCurl = (object)["status" => "true"];
            return $apiCurl;
        }

        private function _dataMaker( &$array, $arrayMerge )
        {
            $data = [
                'purchase_code' => '',
                'username'      => '',
                'email'         => '',
                'ip'            => $this->getUserIP(),
                'domain'        => $_SERVER['HTTP_HOST'],
                'purpose'       => 'update',
                'product_name'  => config_item('product_name'),
                'version'       => config_item('ini_version'),
            ];

            if ( $arrayMerge ) {
                $CI =& get_instance();
                $CI->load->model('setting_m');
                $setting = $CI->setting_m->get_setting();
                if ( count($setting) ) {
                    $data['purchase_code'] = $setting->purchase_code;
                    $data['username']      = $setting->purchase_username;
                    $data['email']         = $setting->email;
                }
            }
            $array = array_merge($data, $array);
        }

        public function getUserIP()
        {
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];

            if ( filter_var($client, FILTER_VALIDATE_IP) ) {
                $ip = $client;
            } elseif ( filter_var($forward, FILTER_VALIDATE_IP) ) {
                $ip = $forward;
            } else {
                $ip = ( $remote == "::1" ? "127.0.0.1" : $remote );
            }

            return $ip;
        }

        private function _apiCurl( $data, $url = null )
        {
            if ( is_null($url) ) {
                $url = $this->_activeServer();
            }

            if ( !$url ) {
                return (object) [
                    'status'  => false,
                    'message' => 'Server Down',
                    'for'     => 'purchasecode',
                ];
            }

            $data_string = json_encode($data);
            $ch          = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string)
                ]
            );
            $result = curl_exec($ch);
            if ( count($result) ) {
                $jsonData = json_decode($result);
                return $jsonData;
            } else {
                return (object) [
                    'status'  => false,
                    'message' => 'Request can not found',
                    'for'     => 'purchasecode',
                ];
            }
        }

        private function _activeServer()
        {
            $allDomain = config_item('installDomain');
            if ( count($allDomain) ) {
                foreach ( $allDomain as $domainKey => $domain ) {
                    $url = parse_url($domain);
                    if ( $this->_checkInternetConnection($url['host']) ) {
                        return $domain . '/api/check';
                    }
                }
            }
            return false;
        }

        private function _checkInternetConnection( $sCheckHost = 'www.google.com' )
        {
            return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 30);
        }

        public function getBrowser()
        {
            $u_agent  = $_SERVER['HTTP_USER_AGENT'];
            $bname    = 'Unknown';
            $platform = 'Unknown';

            if ( preg_match('/linux/i', $u_agent) ) {
                $platform = 'linux';
            } elseif ( preg_match('/macintosh|mac os x/i', $u_agent) ) {
                $platform = 'mac';
            } elseif ( preg_match('/windows|win32/i', $u_agent) ) {
                $platform = 'windows';
            }

            if ( preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent) ) {
                $bname = 'Internet Explorer';
                $ub    = "MSIE";
            } elseif ( preg_match('/Firefox/i', $u_agent) ) {
                $bname = 'Mozilla Firefox';
                $ub    = "Firefox";
            } elseif ( preg_match('/Chrome/i', $u_agent) ) {
                $bname = 'Google Chrome';
                $ub    = "Chrome";
            } elseif ( preg_match('/Safari/i', $u_agent) ) {
                $bname = 'Apple Safari';
                $ub    = "Safari";
            } elseif ( preg_match('/Opera/i', $u_agent) ) {
                $bname = 'Opera';
                $ub    = "Opera";
            } elseif ( preg_match('/Netscape/i', $u_agent) ) {
                $bname = 'Netscape';
                $ub    = "Netscape";
            }

            $known   = [ 'Version', $ub, 'other' ];
            $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if ( !preg_match_all($pattern, $u_agent, $matches) ) {
                // we have no matching number just continue
            }

            $i = count($matches['browser']);
            if ( $i != 1 ) {
                if ( strripos($u_agent, "Version") < strripos($u_agent, $ub) ) {
                    $version = $matches['version'][0];
                } else {
                    $version = $matches['version'][1];
                }
            } else {
                $version = $matches['version'][0];
            }

            if ( $version == null || $version == "" ) {
                $version = "?";
            }

            return (object)[
                'userAgent' => $u_agent,
                'name'      => $bname,
                'version'   => $version,
                'platform'  => $platform,
                'pattern'   => $pattern
            ];
        }
    }