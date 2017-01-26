 // fix for deprecated method in Chrome 37
  if (!window.showModalDialog) {
     window.showModalDialog = function (arg1, arg2, arg3) {

        var w;
        var h;
        var resizable = "no";
        var scroll = "no";
        var status = "no";

        // get the modal specs
        var mdattrs = arg3.split(";");
        for (i = 0; i < mdattrs.length; i++) {
           var mdattr = mdattrs[i].split(":");

           var n = mdattr[0];
           var v = mdattr[1];
           if (n) { n = n.trim().toLowerCase(); }
           if (v) { v = v.trim().toLowerCase(); }

           if (n == "dialogheight") {
              h = v.replace("px", "");
           } else if (n == "dialogwidth") {
              w = v.replace("px", "");
           } else if (n == "resizable") {
              resizable = v;
           } else if (n == "scroll") {
              scroll = v;
           } else if (n == "status") {
              status = v;
           }
        }

        var left = window.screenX + (window.outerWidth / 2) - (w / 2);
        var top = window.screenY + (window.outerHeight / 2) - (h / 2);
        var targetWin = window.open(arg1, arg1, 'toolbar=no, location=no, directories=no, status=' + status + ', menubar=no, scrollbars=' + scroll + ', resizable=' + resizable + ', copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        targetWin.focus();
        
     };
     
  }

function openModal(opt)
{
  var log_diff = 0;
  if( opt == 'another_account' )
        log_diff = 1;
    
  //var _url = 'http://localhost/newvala/public/';
  //var redirecturl = 'http://localhost/wordpress/wp-content/plugins/newvala/inc/callback.php';
  
  var plugin_callback_url = _ewm_new_vala_plugin_url+'inc/callback.php';
  var plugin_callback_url_base64encoded = _ewm_new_vala_plugin_callback_url_base64encoded;
  
  //var _url = 'http://178.62.111.176/';
  var _url = _ewm_new_vala_url;
  //var redirecturl = 'http://wp.venusawards.co.uk/dorset/wp-content/plugins/newvala/inc/callback.php';
  
  
  var screensize = window.screen;
  var dleft = ( screensize.width / 2 ) - 300;
  var data = new Array;
  data[0] = plugin_callback_url;
  
  //var url = _url+'va-login?diff_log='+log_diff+'&r='+plugin_callback_url;
  var url = _url+'va-login/'+log_diff+'/'+plugin_callback_url_base64encoded;

  var userid = window.showModalDialog( url, data, "dialogwidth: 500; dialogheight: 500; dialogtop: 150; dialogleft: "+dleft);
  //document.getElementById('foo').textContent = r;
  //alert(userid);
  //console.log(userid);
  if( userid > 0){
    window.location.reload();
  }
  
  
}

/*jQuery.noConflict();
jQuery(document).ready(function(e) {     
    newvala.onload(e);
    
    

    jQuery('#valogin').click(function(e){
        e.preventDefault();
        var useridres = openModal();
    });
    
   jQuery('#valogout').click(function(e){
    jQuery.get( newvala.url+"logout", function( data ) {},"json").fail(function(data){
                            window.location.reload();
                        });    
   });
});*/