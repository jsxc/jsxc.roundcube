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

    }

    function load_jsxc() {

        // Load CSS stylesheets
        $this->include_stylesheet('css/jquery-ui.min.css');
        $this->include_stylesheet('css/jsxc.css');

        // Load custom CSS stylesheet if exists
        if (stat($this->home.'/css/jsxc.roundcube.css')) {
            $this->include_stylesheet('css/jsxc.roundcube.css');
        }

        // Load JS modules
        $this->include_script('lib/jquery.ui.min.js');
        $this->include_script('lib/jquery.slimscroll.js');
        $this->include_script('lib/jquery.fullscreen.js');
        $this->include_script('lib/jsxc.dep.min.js');
        $this->include_script('jsxc.min.js');

        $this->api->output->add_script("
            $(function() {
                var distconfig = ".$this->distconfig.";
                var userconfig = ".$this->userconfig.";
                var config = $.extend(true, distconfig, userconfig);
                jsxc.init(config);
            });
        ", 'foot');

    }

    function on_render_page($args) {

        switch($args['template']) {

            case 'login':
                $this->load_jsxc();
                break;

            case 'mail':
            case 'compose':
            case 'addressbook':
            case 'settings':
            case 'folders':
            case 'identities':
            case 'responses':
            case 'about':
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
                break;

        }

    }

}
