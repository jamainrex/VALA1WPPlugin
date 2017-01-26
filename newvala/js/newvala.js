jQuery.noConflict();

jQuery(document).ready(function($) {
    
    jQuery("#awardsregion").change(function(e){
        var rid = ".rid" + jQuery(this).val();
        
        //jQuery(".rnblock").find("input:disabled").removeAttr("disabled");
        //jQuery(rid).find('input').attr("checked",false).attr("disabled","disabled");
        
        //jQuery(".rnblock").show();
        //jQuery(rid).hide();
        //console.log(rid);    
    }); 
    
    /*jQuery('ul.pager a').click(function(e){
        e.preventDefault();
        var el = jQuery('#va-tablist');
        var amethod = jQuery(this).hasClass('pagerPrev') ? 'prev' : 'next'; 
        var activetab = el.find('li.active');
        
        //console.log( activetab );
        
        if( amethod == 'prev' && !activetab.is(':first-child') ){
            console.log( activetab.prev('li').find('a') );
            activetab.prev('li').find('a').tab("show"); 
        }else if( amethod == 'next' && !activetab.is(':last-child') ){
            console.log( activetab.next('li').find('a') );
            activetab.next('li').find('a').tab("show");
        }
        //alert(amethod);
    });*/
   
    /*var votingheight = jQuery('#vala-voting-wrap').position();
    alert(votingheight);
    // side bar
    setTimeout(function () {
      jQuery('.bs-docs-sidenav').affix({
        offset: {
          top: votingheight.top,
          bottom: 270
        }
      })
    }, 100)*/
    
    /*jQuery('a[data-toggle="modal"]').click(function(e){
        e.preventDefault();
        var el = jQuery(this);    
        console.log(el.attr('lang'));
    });*/ 
    jQuery('.modal').on('show.bs.modal', function (event) {
          var nlink = jQuery(event.relatedTarget); // Button that triggered the modal
          var ncode = nlink.data('nomineecode'); // Extract info from data-* attributes
          //console.log(ncode);
          
          var modal = jQuery(this);
          
          
          modal.find('.vaActive').removeClass('vaActive');
          modal.find('h4.modal-title div#'+ncode).addClass('vaActive');
          modal.find('div.vala-modal-title-desc-wrap div#'+ncode).addClass('vaActive');
          modal.find('div.modal-body div#'+ncode).addClass('vaActive');
          
          var apps_yt = modal.find('div.modal-body div.vaActive iframe.app_youtube');
          var url = 'http://www.youtube.com/embed/'+apps_yt.attr('id')+'?rel=0&amp;showinfo=0';
          apps_yt.attr('src', url);
    });
    
    jQuery('.modal').on('hide.bs.modal', function (event) {
          var modal = jQuery(this);
          var apps_yt = modal.find('iframe.app_youtube');
          apps_yt.attr('src', '');
    });
    
    jQuery('ul.pager a').click(function(e){
        e.preventDefault();
        var amethod = jQuery(this).hasClass('pagerPrev') ? 'prev' : 'next'; 
        var elp = jQuery(this).parent().parent().attr('id');
        var elpid = elp.replace('cn-','');
        var elpmodal = jQuery('#'+elpid);
            
        var actitle = elpmodal.find('h4.modal-title div.vaActive');
        var actdesc = elpmodal.find('div.vala-modal-title-desc-wrap div.vaActive');
        var acbody = elpmodal.find('div.modal-body div.vaActive');
        
        var apps_yt = acbody.find('iframe.app_youtube');
        apps_yt.attr('src', '');
        //console.log(actitle);
               
        if( amethod == 'prev' && !actitle.is(':first-child') ){
            //console.log('prev');   
            actitle.removeClass('vaActive'); 
            actdesc.removeClass('vaActive'); 
            acbody.removeClass('vaActive');
                         
            actitle.prev('div').addClass('vaActive'); 
            actdesc.prev('div').addClass('vaActive'); 
            acbody.prev('div').addClass('vaActive'); 
            // youtube
            var iframeyt = acbody.prev('div').find('iframe.app_youtube');
            var url = 'http://www.youtube.com/embed/'+iframeyt.attr('id')+'?rel=0&amp;showinfo=0';
            iframeyt.attr('src',url);
            
        }else if( amethod == 'next' && !actitle.is(':last-child') ){
            //console.log('next');
            actitle.removeClass('vaActive'); 
            actdesc.removeClass('vaActive'); 
            acbody.removeClass('vaActive');
            
            actitle.next('div').addClass('vaActive'); 
            actdesc.next('div').addClass('vaActive'); 
            acbody.next('div').addClass('vaActive');
            
            // youtube
            var iframeyt = acbody.next('div').find('iframe.app_youtube');
            var url = 'http://www.youtube.com/embed/'+iframeyt.attr('id')+'?rel=0&amp;showinfo=0';
            iframeyt.attr('src',url);
        }
        //alert(amethod);
    });
    
    // readmore
    //jQuery('div.va-bio p').readmore({speed: 500});
    
    /*$("div.va-bio p").on("hide", function() {
        $(this).prev("div").find("i").attr("class","icon-plus-sign");
        $(this).css("display","");
        $(this).css("height","5px");
    });
    $("div.va-bio p").on("show", function() {
        $(this).prev("div").find("i").attr("class","icon-minus-sign");
        $(this).css("display","inline");
        $(this).css("height","5px");
    });*/
    
    jQuery('.nbread-more').click(function(e){
        e.preventDefault();
        var el = jQuery(this);
        var id = el.attr('href').replace("#","span");
        var span = jQuery('#'+id);
        
        span.toggle();
        if( span.css('display') == 'none' ){
            el.text('View More');
        }else{
            el.text('Less');
        }
    });
    
    jQuery('.btn-vote-this').click(function(e){
        e.preventDefault();
        var el = jQuery(this);
        
        if( el.hasClass('error-vote-login') ){
            var useridres = openModal();     
        }else{
        var pvote = el.attr('id').replace("vote-","");  
        jQuery.post(
                _ewm_new_vala_ajax_url, 
                {
                    'action': 'new_vala_publicvote',
                    'pvote': pvote,
                    'userid': newvala.user.info.id
                }, 
                function(data){
                    //console.log(data);
                    if( !data.error ){
                        el.text('Voted');
                        el.prop('disable',true);
                        el.addClass('voted');
                    }else{
                        el.text(data.error);
                        el.prop('disable',true);
                        el.addClass('error-vote-login');
                    }
                    //jQuery('#va-info').text()   
                },"json"
            );  
        jQuery('#'+pvote).prop('checked',true).prop('disabled',true);
        }
    });
});