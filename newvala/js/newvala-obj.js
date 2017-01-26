var vala_userid;
var newvala = {
    //url: 'http://localhost/newvala/public/',
    url: _ewm_new_vala_ajax_url,
    rid: _ewm_new_vala_rid,
    vala_url: _ewm_new_vala_url,
    api_url: _ewm_new_vala_api_url,
    user: {},
    onload: function(e){
        newvala._isUserLogged();  
    },
    _isUserLogged: function(){
        //var _url = 'http://localhost/newvala/public/';
        var _url = 'http://178.62.111.176/';
        var url = _url+'va-login/0/'+_ewm_new_vala_plugin_callback_url_base64encoded;
        
        newvala.userid = _ewm_new_vala_user_id;
        if( newvala.userid > 0 )
            newvala._getUser( newvala.userid );

        /*jQuery.get( url+"vala-api/user/islogged?va_token=1q2w3e4r5tva", function( data ) {},"json").done(function(data){
                                //console.log(data);
                                newvala.userid = data.userid;   
                                //vala_userid = userid;
                                //jQuery('#userid').val(data.userid);
                                if( newvala.userid > 0 ){
                                    newvala._getUser( data.userid );
                                }else{
                                    jQuery('.va-user-login-wrap').html( newvala._generateLoginBtn() );
                                }       
                        });*/
        
        //var c = document.getElementById('va-isuserlogged').contentWindow.document.body.innerHTML
        //console.log(c);
        /*var c = jQuery('#va-isuserlogged').contents().find('body').html();
        console.log(c);
        var data = jQuery.parseJSON( c );
        //console.log(data);
        newvala.userid = data.userid;
        if( newvala.userid > 0 ){
            newvala._getUser( data.userid );
        }else{
            jQuery('.va-user-login-wrap').html( newvala._generateLoginBtn() );
        } */    
        
        //var islogged = jQuery('#va-isuserlogged').contents().find("body").html();
        //console.log(islogged);
        /*jQuery('#va-isuserlogged').on("load", function(e) {
            console.log(jQuery(this).contents());
        });*/
        
        /*jQuery.post(
                newvala.url, 
                {
                    'action': 'new_vala_isuserlogged',
                    'data':   ''
                }, 
                function(data){
                                newvala.userid = data.userid;   
                                if( newvala.userid > 0 ){
                                    newvala._getUser( data.userid );
                                }else{
                                    jQuery('.va-user-login-wrap').html( newvala._generateLoginBtn() );
                                }       
                },"json"
            );*/
    },
    _getUser: function(id){
         jQuery.post(
                newvala.url, 
                {
                    'action': 'new_vala_getuser',
                    'userid': id,
                    'rid': newvala.rid
                }, 
                function(data){
                    newvala.user = data; 
                    jQuery('.va-user-info').html(newvala._generateUserInfo(data.info));
                    jQuery('.va-user-login-wrap').html( newvala._generateLoginBtnWthAnotherAccount() );
                    
                    jQuery('.btn-vote-this').prop('disabled',false);
                    
                    /**
                    * find all already voted and disabled vote btn.
                    */
                    jQuery.each( data.votes, function( key, value ) {
                            jQuery.each( value.votes, function( k, v ) {
                              jQuery( '#vote-'+v.app_id+'-'+v.cat_id+'-'+newvala.rid+'-'+v.app_category_id ).text('Voted').addClass('already-voted').prop('disabled',true);
                              jQuery( '#'+v.app_id+'-'+v.cat_id+'-'+newvala.rid+'-'+v.app_category_id ).prop('checked',true).prop('disabled',true);
                            });
                        });
                },"json"
            );
    },
    _getUserInfo: function(id){
         jQuery.post(
                newvala.url, 
                {
                    'action': 'new_vala_getuser',
                    'userid': id,
                    'rid': newvala.rid
                }, 
                function(data){
                    newvala.user = data; 
                    
                    /*jQuery('.va-user-info').html(newvala._generateUserInfo(data.info));
                    jQuery('.va-user-login-wrap').html( newvala._generateLoginBtnWthAnotherAccount() );
                    
                    jQuery('.btn-vote-this').prop('disabled',false);
                    
                    //find all already voted and disabled vote btn.
                    jQuery.each( data.votes, function( key, value ) {
                            jQuery.each( value.votes, function( k, v ) {
                              jQuery( '#vote-'+v.app_id+'-'+v.cat_id+'-'+newvala.rid+'-'+v.app_category_id ).text('Voted').addClass('already-voted').prop('disabled',true);
                              jQuery( '#'+v.app_id+'-'+v.cat_id+'-'+newvala.rid+'-'+v.app_category_id ).prop('checked',true).prop('disabled',true);
                            });
                        });*/
                },"json"
            );
    },
    _generateLoginBtn: function(){
        var btn = '<div style="width: 100%; text-align: center;"><button id="va-login-btn" type="button" class="btn btn-primary">Login with Vala</button></div>';
        var script = '<script>jQuery.noConflict();jQuery(document).ready(function(e) {';
        script += 'jQuery("#va-login-btn").click(function(e){e.preventDefault();var useridres = newvala._openModal("")});';
        script += '});</script>';
        
        return btn+script;
    },
    _generateLoginBtnWthAnotherAccount: function(){
        var btn = '<div style="width: auto; text-align: left;"><button id="va-login-waa-btn" type="button" class="btn btn-primary btn-sm">Login with another Vala account</button></div>';
        var script = '<script>jQuery.noConflict();jQuery(document).ready(function(e) {';
        script += 'jQuery("#va-login-waa-btn").click(function(e){e.preventDefault();var useridres = newvala._openModal("another_account")});';
        script += '});</script>';
        
        return btn+script;
    },
    _generateUserInfo: function(user){
        var info = '<div class="va-user-info">You are logged-in as: <strong>'+user.firstname+ ' ' +user.lastname+'</strong></div>';
        //var addtlinfo = '<div class="va-user-addtl-info">'+user.biography+'</div>';
        var addtlinfo = '';
        return info+addtlinfo;
    },
    _openModal: function(opt){
        var log_diff = 0;
            if( opt == 'another_account' )
                log_diff = 1;
                
         var plugin_callback_url = _ewm_new_vala_plugin_url+'inc/callback.php';
         var plugin_callback_url_base64encoded = _ewm_new_vala_plugin_callback_url_base64encoded;
         //var _url = 'http://178.62.111.176/';
         var _url = _ewm_new_vala_url;
         
         var url = _url+'va-login/'+log_diff+'/'+plugin_callback_url_base64encoded;
         
         var screensize = window.screen;
          var dleft = ( screensize.width / 2 ) - 300;
          var data = new Array;
          data[0] = plugin_callback_url;
         
         var userid = window.showModalDialog( url, data, "dialogwidth: 500; dialogheight: 500; dialogtop: 150; dialogleft: "+dleft);
         
         if( userid > 0){
            window.location.reload();
         }
    },
    _iframeLoaded: function(){
        
        //var c = jQuery('#va-isuserlogged').contents();
        //console.log(c);
    }
}

jQuery.noConflict();
jQuery(document).ready(function(e) {     
    newvala.onload(e);
});