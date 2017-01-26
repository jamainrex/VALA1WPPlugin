<?php
  add_shortcode('nv_userstatus', 'newvala_userstatus');
  function newvala_userstatus(){
          global $__newvalaobj;
          
          wp_enqueue_script( 'newvala-core-js' );
          wp_enqueue_script( 'newvala-obj-js' ); 
          
        $info = "";
        $btn = "";
        
        
        if( $__newvalaobj->getUserID() > 0 ){  
            $user = $__newvalaobj->getUserInfo();
            $info = '<div class="va-user-info">You are logged-in as: <strong>'.$user['firstname']. ' ' .$user['lastname'].'</strong></div>';
            $btn = '<div style="width: auto; text-align: left;"><button id="va-login-waa-btn" type="button" class="btn btn-primary btn-sm">Login with another Vala account</button></div>';
            $script = '<script>jQuery.noConflict();jQuery(document).ready(function(e) {';
            $script .= 'jQuery("#va-login-waa-btn").click(function(e){e.preventDefault();var useridres = newvala._openModal("another_account")});';
            $script .= '});</script>';
            
            $btn .= $script;
        }else{
            $btn = '<div style="width: 100%; text-align: center;"><button id="va-login-btn" type="button" class="btn btn-primary">Login with Vala</button></div>';
            $script = '<script>jQuery.noConflict();jQuery(document).ready(function(e) {';
            $script .= 'jQuery("#va-login-btn").click(function(e){e.preventDefault();var useridres = newvala._openModal("")});';
            $script .= '});</script>';
            
            $btn .= $script;
        }
          
          $iframe = "";
          //$keyencoded = 'va'.base64_encode( EWM_NEW_VALA_PLUGIN_URL.'inc/callback.php' . '|venusawardssecretkey' );
          //$iframe = '<iframe id="va-isuserlogged" src="'.EWM_NEW_VALA_API_URL.'isuserlogged/'.$keyencoded.'" style="display: block"></iframe>';
          
          $html = '<div class="va-user-wrap">'.$iframe.'<div class="va-user-info">'.$info.'</div><div class="va-user-login-wrap">'.$btn.'</div></div>';
          
          return $html;
  }
  
  add_shortcode('nv_voting_btn', 'newvala_votingbtn');
  function newvala_votingbtn($atts, $content){
      
      extract( shortcode_atts( array(
        'userid' => '',
        'appid' => '',
        'catid' => '',
        'appcatid' => '',
        'rid' => ''
    ), $atts ) );
    $pvote_value = $appid."-".$catid."-".$rid."-".$appcatid;
    $pv_btn = '<div style="width: 100%; text-align: center;"><button id="vote-'.$pvote_value.'" type="button" class="btn-vote-this btn btn-primary" style="float: none; margin: 0px;">'.$content.'</button></div>';
    
    return $pv_btn;
  }
  
  add_shortcode( 'nv_publicvoting_semi_list', '_newvala_publicvoting_semi_sc' );
  function _newvala_publicvoting_semi_sc(){
      global $__newvalaobj;
      //
        //$nv_region_id = $__newvalaobj->getRegionID();
        $nv_region_id = 1;
        
        // $awards = array_keys( $winners );
        //$stylesheet_directory_uri = get_stylesheet_directory_uri();
        $stylesheet_directory_uri = 'http://wp.venusawards.co.uk/dorset/wp-content/themes/VenusAward';
        wp_enqueue_style( 'winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/winners-circle.css", array(), '1.5', 'all' );
        wp_enqueue_style( 'bootstrap', $stylesheet_directory_uri . '/css/modals/bootstrap.css', array(), '1.2', 'all' );
        wp_enqueue_style( 'vala-nomination-css', $stylesheet_directory_uri . '/vala/vala-nomination-styles.css', array(), '1.0', 'all' );
        wp_enqueue_script( 'bootstrap', $stylesheet_directory_uri . '/vala/bootstrap.js', array( 'jquery' ), '1.0', false );  
        
        wp_enqueue_style( 'newvala-css' );
        wp_enqueue_script( 'newvala-js' );  
        
        
        $region_id = $nv_region_id;
        
        // Get all Categories for Public VOting
        $catData = new_vala_getcategoriesbyregion($region_id);
        $appPublicVotingCategories = $catData['public_votes'];
        
        //echo '<pre>'.print_r($appPublicVotingCategories,true).'</pre>';
        
        // Get Semi-finalists
        $data = new_vala_getsemibyregion($region_id);
        
        $nv_semi = $data['semifinalists'];
        $nv_cats = $data['categories'];
        ob_start();
        $output = "";    
        $output .= '<hr />';
        // get all semifinalists that are public voting
        foreach( $appPublicVotingCategories as $cat_id => $cat ){
            // Get Semifinalists from Category
            $nv_cats_semis = $nv_cats[$cat['category_id']];
            if( $nv_cats_semis ){
                $output .= '<div id="winners-circle" class="clearfix">'; 
                $output .= '<h3 style="font-size:20px;">'.$cat['name'].'</h3>';
                $output .= new_vala_sfpv_template($nv_cats_semis,$cat);
                $output .= '</div>';
                $output .= '<hr />';     
            } 
        }
        
        ob_end_clean();
        //removing extra <br>
        /*$Old     = array( '<br />', '<br>', '<p></p>' );
        $New     = array( '','','' );
        $output = str_replace( $Old, $New, $output );*/
        
        return $output;
  }
?>
