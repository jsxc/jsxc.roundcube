<?php
class jsxc extends rcube_plugin
{
    private $rcmail;
    private $distconfig;
    private $userconfig;

    function init() {

        $rcmail = rcmail::get_instance();
        $this->rcmail = $rcmail;

        if ($this->load_config()) {
            $this->userconfig = json_encode($rcmail->config->get('jsxc'));
        } else {
            $this->rcmail->write_log("jsxc", "Warning: config.inc.php not found, not loading JSXC");
            return;
        }

        $this->load_config('config.inc.php.dist');
        $this->distconfig = json_encode($rcmail->config->get('jsxc'));

        $this->add_hook('render_page', array($this, 'on_render_page'));
        $this->register_action('plugin.jsxc.turn', array($this, 'turn_request_handler'));

    }

    function load_jsxc() {

        // Load CSS stylesheets
        $this->include_stylesheet('css/jsxc.css');

        // Load custom CSS stylesheet if exists
        if (stat($this->home.'/css/jsxc.roundcube.css')) {
            $this->include_stylesheet('css/jsxc.roundcube.css');
        }

        // Load JS modules
        $this->include_script('lib/jquery.slimscroll.js');
        $this->include_script('lib/jquery.fullscreen.js');
        $this->include_script('lib/jsxc.dep.min.js');
        $this->include_script('jsxc.min.js');

        $script = "
            $(function() {
                var distconfig = ".$this->distconfig.";
                var userconfig = ".$this->userconfig.";
                var config = $.extend(true, distconfig, userconfig);";

        $turnconfig = $this->rcmail->config->get('turn');

        if ($turnconfig) {
            $script .= "
                config.RTCPeerConfig = {
                    ttl: " . $turnconfig['ttl'] . ",
                    url: rcmail.url('plugin.jsxc.turn')
                };
            ";
        }

        $script .= "
                jsxc.init(config);
            });
        ";

        $this->api->output->add_script($script, 'foot');

    }

    function on_render_page($args) {

        switch($args['template']) {

            case 'login':
                $this->load_jsxc();
                break;

            default:
                if (!$this->api->output->env['framed']) {
                    $this->load_jsxc();
                    $this->api->output->add_script("
                        $('.button-logout').each(function(i, b) {
                            var onclick = b.onclick;
                            b.onclick = function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                if (jsxc.xmpp.conn != null) {
                                    $(document).on('disconnected.jsxc', onclick);
                                    jsxc.xmpp.logout(true);
                                } else {
                                    onclick();
                                }
                            }
                        });
                    ", 'foot');
                }

        }

    }

    function turn_request_handler() {

        $config = $this->rcmail->config->get('turn');

        if (!$config) {
            $this->rcmail->write_log("jsxc", "Warning: TURN credentials requested with TURN REST API not configured");
            http_response_code(404);
            exit;
        }

        $ttl = $config['ttl'];
        $iceServers = array();

        foreach ($config['servers'] as $server) {

            $secret = $server['secret'];
            $timestamp = time() + $ttl;
            $username = $timestamp . ":" . $this->rcmail->user->get_username();
            $password = base64_encode(hash_hmac("sha1", $username, $secret, true));

            $iceServers[] = array(
                "urls" => $server['urls'],
                "username" => $username,
                "credential" => $password
            );

        }

        $turn = array(
            "iceServers" => $iceServers
        );

        header("Content-Type: application/json");
        echo rcmail_output_json::json_serialize($turn);
        exit;

    }

}
