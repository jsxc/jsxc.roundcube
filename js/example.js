

$(function() {
   jsxc.init({
      loginForm: {
         form: '#form',
         jid: '#username',
         pass: '#password'
      },
      logoutElement: $('#logout'),
      checkFlash: false,
      rosterAppend: 'body',
      root: '/webmail/plugins/jsxc',
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
               url: 'http://172.16.200.72:7070/http-bind/',
               domain: 'altec.rionegro.gov.ar',
               resource: 'example',
               overwrite: true,
               onlogin: true,
               manual:true,
               username:'lperalta',
               password:'viedma010'
            }
         };
      }
   }); 
});
