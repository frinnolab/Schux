<?php
    function getIpAddress()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

        if ( filter_var($client, FILTER_VALIDATE_IP) ) {
            $ip = $client;
        } elseif ( filter_var($forward, FILTER_VALIDATE_IP) ) {
            $ip = $forward;
        } else {
            $ip = ( $remote == "::1" ? "127.0.0.1" : $remote );
        }

        return $ip;
    }

?>