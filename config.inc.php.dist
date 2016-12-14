<?php

// JSXC configuration
// ==================
// The structure mirrors that of JSXC options (see jsxc.options.html).

// RoundCube defaults for JSXC. You generally won't need to touch these.
$config['jsxc'] = array(
    'loginForm' => array(
        'form' => '#login-form > div > form',
        'jid' => '#rcmloginuser',
        'pass' => '#rcmloginpwd'
    ),
    'rosterAppend' => 'body',
    'root' => 'plugins/jsxc'
);

// Use config.php.inc to configure deployment-specific options.
// The syntax is the same; JSXC configs from both files will be merged.
//$config['jsxc'] = array(
//    'xmpp' => array(
//        'url' => 'http://<HOST>:<PORT>/http-bind/',
//        'domain' => '<DOMAIN>',
//        'resource' => 'JSXC',
//        'overwrite' => true,
//        'onlogin' => false
//    ),
//    'RTCPeerConfig' => array(
//        'iceServers' => array(
//            array(
//                'urls' => 'stun:stun.l.google.com:19302'
//            )
//        )
//    )
//);

// TURN REST API configuration
// ===========================
// This is loosely based on this draft https://tools.ietf.org/html/draft-uberti-behave-turn-rest-00
// If configured, this will override RTCPeerConfig above.
// Shared secret is used to generate ephemeral credentials, which will be used
// afterwards to authenticate with the TURN server.
//
//$config['turn'] = array(
//    'ttl' => 86400,
//    'servers' => array(
//        array(
//            urls => 'turn:turn.myserver.foo',
//            secret => 'secret'
//        )
//    )
//);
