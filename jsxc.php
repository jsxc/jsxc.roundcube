<?php
class jsxc extends rcube_plugin
{
    private $rcmail;
    private $xmpp_domain="";
    private $xmpp_resource="";
    private $xmpp_overwrite=True;
    private $jsxc_root="/";
    private $jsxc_load_jquery=False;

    function init() {

        $rcmail = rcmail::get_instance();
        $this->rcmail = $rcmail;

        $this->load_config();
        $this->xmpp_bosh_url = $rcmail->config->get('xmpp_bosh_url');
        $this->xmpp_domain = $rcmail->config->get('xmpp_domain');
        $this->xmpp_resource = $rcmail->config->get('xmpp_resource');
        $this->xmpp_overwrite = $rcmail->config->get('xmpp_overwrite');
        $this->jsxc_root = $rcmail->config->get('jsxc_root');
        $this->jsxc_load_jquery = $rcmail->config->get('jsxc_load_jquery');

        $this->add_hook('render_page', array($this, 'on_render_page'));

    }

    function load_jsxc() {

        // Load CSS stylesheets
        $this->include_stylesheet('css/jquery-ui.min.css');
        $this->include_stylesheet('css/jsxc.css');

        // Load custom CSS stylesheet if exists
        if (stat('plugins/jsxc/css/jsxc.roundcube.css')) {
            $this->include_stylesheet('css/jsxc.roundcube.css');
        }

        // Load JS modules
        if ($this->jsxc_load_jquery) {
            $this->include_script('lib/jquery.min.js');
        }
        $this->include_script('lib/jquery.ui.min.js');
        $this->include_script('lib/jquery.slimscroll.js');
        $this->include_script('lib/jquery.fullscreen.js');
        $this->include_script('lib/jsxc.dep.min.js');
        $this->include_script('jsxc.min.js');

        $this->api->output->add_script("

            $(function() {
                jsxc.init({
                    loginForm: {
                        form: '#login-form > div > form',
                        jid: '#rcmloginuser',
                        pass: '#rcmloginpwd'
                    },
                    rosterAppend: 'body',
                    root: '".$this->jsxc_root."plugins/jsxc',
                    displayRosterMinimized: function() {
                        return true;
                    },
                    xmpp: {
                        url: '".$this->xmpp_bosh_url."',
                        domain: '".$this->xmpp_domain."',
                        resource: '".$this->xmpp_resource."',
                        overwrite: ".$this->xmpp_overwrite.",
                        onlogin: false
                    }
                });
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
