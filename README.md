This is a plugin for RoundCube that enables real-time web chat based on [JSXC](https://www.jsxc.org).

JSXC is a JavaScript XMPP client, therefore it requires XMPP server. Before you begin, please check for JSXC [prerequisites](https://www.jsxc.org/installation.html).

## Installation

To install the plugin, follow the [standard procedure for RoundCube plugin installation](https://plugins.roundcube.net).

Here is the snippet for `composer.json`:
```
"require" : {
    ...,
    "jsxc/jsxc": ">=1.0"
}
```

## Configuration

To configure the plugin, create `config.in.php` in the `plugins/jsxc` directory. You can use `config.inc.php.dist` as a reference.

### JSXC Configuration

Here you can configure basic XMPP and WebRTC connectivity options, as well as many other parameters. The structure mirrors that of JSXC configuration; please refer to [JSXC documentation](https://rawgit.com/jsxc/jsxc/master/doc/jsxc.options.html) for the full list of options.

Example:
```
$config['jsxc'] = [
    'xmpp' => [
        'url' => 'https://mydomain.foo/http-bind/',
        'domain' => 'mydomain.foo',
        'resource' => 'JSXC',
        'overwrite' => true,
        'onlogin' => false
    ],
    'RTCPeerConfig' => [
        'iceServers' => [
            [
                'urls' => 'stun:stun.l.google.com:19302'
            ]
        ]
    ]
];
```

### TURN REST API Configuration

If you use a private/corporate TURN server, you probably won't want to expose TURN credentials. This is a well-known problem that has been addressed to some extent by [this draft](https://tools.ietf.org/html/draft-uberti-rtcweb-turn-rest-00). 
The plugin supports generating the so-called ephemeral TURN credentials. For that, you'll need to configure shared cryptographic secret(s):

```
$config['turn'] = [
    'ttl' => 86400,
    'servers' => [
        [
            urls => 'turn:turn.myserver.foo',
            secret => 'secret'
        ]
    ]
];
```

If TURN REST API is configured, the whole `RTCPeerConfig` section from the JSXC config will be overridden. 
