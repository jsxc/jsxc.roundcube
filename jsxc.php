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
                $this->include_stylesheet('css/jquery-ui.min.css');
                $this->include_stylesheet('css/jsxc.css');

                //Cargo los archivos JS
		if ($this->jsxc_load_jquery){
                	$this->include_script('lib/jquery.min.js');
		}
                $this->include_script('lib/jquery.ui.min.js');
                $this->include_script('lib/jquery.slimscroll.js');
                $this->include_script('lib/jquery.fullscreen.js');
                $this->include_script('lib/jsxc.dep.js');
                $this->include_script('jsxc.min.js');
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
