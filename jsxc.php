<?php
class jsxc extends rcube_plugin
{
	private $user="";
	private $passwd="";
	private $xmpp_url="";
	private $xmpp_domain="";
	private $xmpp_resource="";
	private $xmpp_overwrite=True;
	private $jsxc_root="/";
	private $jsxc_load_jquery=False;
		

	function init(){
		$rcmail = rcmail::get_instance();
	        $this->load_config();
	        $this->xmpp_bosh_url = $rcmail->config->get('xmpp_bosh_url');
	        $this->xmpp_domain = $rcmail->config->get('xmpp_domain');
	        $this->xmpp_resource = $rcmail->config->get('xmpp_resource');
		$this->xmpp_overwrite = $rcmail->config->get('xmpp_overwrite');
		$this->jsxc_root = $rcmail->config->get('jsxc_root');
		$this->jsxc_load_jquery = $rcmail->config->get('jsxc_load_jquery');
		
                $this->add_hook('storage_connect', array($this, 'carga_chat'));
	}

	function render_page ($args){

                $this->api->output->add_script("

			$(function() {
   				jsxc.init({
					logoutElement: $('#rcmbtn101'),
					checkFlash: false,
				      	rosterAppend: 'body',
				      	root: '".$this->jsxc_root."plugins/jsxc',
				      	turnCredentialsPath: 'plugins/jsxc/ajax/getturncredentials.json',
				      	displayRosterMinimized: function() {
				        	return true;
				    	},
					otr: {
				      		debug: true,
				      		SEND_WHITESPACE_TAG: true,
				      		WHITESPACE_START_AKE: true
					},
    					loadSettings: function(username, password) {
         					return {
            						xmpp: {
               							url: '".$this->xmpp_bosh_url."',
               							domain: '".$this->xmpp_domain."',
						               	resource: '".$this->xmpp_resource."',
               							overwrite: ".$this->xmpp_overwrite.",
               							onlogin: false,
            						}
         					};
      					}
   				});
			});
                ",'foot');

                //Cargo las hojas CSS
                $this->include_stylesheet('css/jquery/jquery-ui.min.css');
                $this->include_stylesheet('css/jquery/jquery.mCustomScrollbar.css');
                $this->include_stylesheet('css/jquery/jquery.colorbox.css');
                $this->include_stylesheet('css/jsxc.css');
                $this->include_stylesheet('css/jsxc.webrtc.css');

                //Cargo los archivos JS
		if ($this->jsxc_load_jquery){
                	$this->include_script('js/jquery/jquery.min.js');
		}
                $this->include_script('js/jquery/jquery.ui.min.js');
                $this->include_script('js/jquery/jquery.colorbox-min.js');
                $this->include_script('js/jquery/jquery.slimscroll.js');
                $this->include_script('js/jquery/jquery.fullscreen.js');
                $this->include_script('js/strophe.jingle/strophe.js');
                $this->include_script('js/strophe.jingle/strophe.muc.js');
                $this->include_script('js/strophe.jingle/strophe.disco.js');
                $this->include_script('js/strophe.jingle/strophe.caps.js');
                $this->include_script('js/strophe.jingle/strophe.vcard.js');
                $this->include_script('js/strophe.jingle/strophe.jingle.js');
                $this->include_script('js/strophe.jingle/strophe.jingle.session.js');
                $this->include_script('js/strophe.jingle/strophe.jingle.sdp.js');
                $this->include_script('js/strophe.jingle/strophe.jingle.adapter.js');
                $this->include_script('js/otr/build/dep/salsa20.js');
                $this->include_script('js/otr/build/dep/bigint.js');
                $this->include_script('js/otr/build/dep/crypto.js');
                $this->include_script('js/otr/build/dep/eventemitter.js');
                $this->include_script('js/otr/build/otr.js');
                $this->include_script('js/jsxc/jsxc.lib.js');
                $this->include_script('js/jsxc/jsxc.lib.webrtc.js');
	}

	function carga_chat($args) {
		$this->user=$args['user'];
		$this->passwd=$args['password'];
		if ((!empty($this->user))&&(!empty($this->passwd))){
			$this->add_hook('render_page', array($this, 'render_page'));
		}
	}
}
?>
