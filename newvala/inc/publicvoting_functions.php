<?php
function new_vala_social_icon($website='',$facebook='',$twitter='',$gmail='',$skype='',$youtube='',$linkedin=''){
   $output = '';
    $team_link='';
    if(  $website != ''  )
        $team_link .= '<li><a class="t_website hastip" title="website" target="_blank" href="'.$website.'"></a></li>';
    if(  $facebook != ''  )
        $team_link .= '<li><a class="t_facebook hastip" title="facebook" target="_blank" href="'.$facebook.'"></a></li>';
    if(  $twitter != ''  )
        $team_link .= '<li><a class="t_twitter hastip" title="twitter" target="_blank" href="'.$twitter.'"></a></li>';            
    if(  $gmail != ''  )
        $team_link .= '<li><a class="t_gmail hastip" title="gmail" target="_blank" href="'.$gmail.'"></a></li>';
    if(  $skype != ''  )
        $team_link .= '<li><a class="t_skype hastip" title="skype" target="_blank" href="'.$skype.'"></a></li>';    
    if(  $youtube != ''  )
        $team_link .= '<li><a class="t_youtube hastip" title="youtube" target="_blank" href="'.$youtube.'"></a></li>';
    if(  $linkedin != ''  )
        $team_link .= '<li><a class="t_linkedin hastip" title="linkedin" target="_blank" href="'.$linkedin.'"></a></li>'; 
        
    $output .= '<ul class="nominee_socials">'. $team_link . '</ul>';       
    return $output;             
}

function new_vala_sfpv_template($cats, $cat) {
    global $wpdb; 
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
  $winners = $cats['apps'];
  //$app_cat_id = $cats['info']['id'];
  $html = '';
  if ( $winners ) {

    $rcname = strtolower( str_replace(array(" ","/"),"-",$cat['name']) ); 
    $rcname = str_replace( array("&",","), array( "and","" ), $rcname ); 
    if ( $winners && count($winners) > 0 ) {
      //ob_start();
        $_wninfo = array();
        $_wninfo['header_info'] = "";
        $_wninfo['header_info_desc'] = "";
        $_wninfo['body_info'] = "";
        $region_name = 'Dorset';
        //$region_name = get_bloginfo( 'name' );
        $rid = $cat['region_id'];
      foreach( $winners as $app_cat_id => $winner ) {
        if( $_post_id = new_vala_isAppidExist( $winner['id'] ) ){  
            $winner['app_cat_id'] = $app_cat_id;
            
            $nominee = new_vala_getappuserdetails( $winner['id'] );  
            //echo '<pre>'.print_r($nominee,true).'</pre>';
            //$_post_permalink = get_permalink( $_post_id );
            $_post_permalink = '';
            
            //Youtube link
            $youtube_link = isset( $winner['youtube'] ) ? $winner['youtube'] : false;
            
            //list(,$rid) = explode("-",$winner['Code']);
            $cname = strtolower( str_replace(array(" ","/"),"-",$cat['name']) );   
            $winner['Code'] = $cname."-".$winner['id']."-".$cat['category_id']."-".$rid;
            $pvote_value = $winner['id']."-".$cat['category_id']."-".$rid."-".$winner['app_cat_id'];
            $winner['Name'] = $nominee['firstname'] .' '. $nominee['lastname'];
            
            $image_source = isset( $nominee['image'] ) ? 'http://valasystem.com/images/user/'. $nominee['id'] . '/' . $nominee['image'] : '';
            //$image_source =  $stylesheet_directory_uri. '/include/scripts/timthumb.php?src='.$image_source.'&w=200&h=200&q=100&a=t&zc=3';
            
            $winner['Image'] = $image_source;
            $winner['Org'] = $nominee['org_name'];
            
            //$rrname = strtoupper( str_replace("-"," ",vala_region_name_byid($rid)) );
            $rrname = strtoupper( str_replace("-"," ",$region_name) );
            $sc_html = new_vala_social_icon( $nominee['website'], $nominee['facebook'], $nominee['twitter'] );
            
            $bio = array();
            $bio[0] = $nominee['biography'];
            $bio[1] = $nominee['personal_bio']; 
      
          $html .= '<div class="rnblock rid'.$rid.'" style="display:inline-block; padding-right: 30px; padding-bottom: 5px;">
            <div class="image"><div data-target="#'.$rcname.'" data-toggle="modal" data-nomineecode="'.$winner['Code'].'" class="avatar" style="background-image: url(\''.$winner['Image'].'\'); cursor: pointer;"></div>
            <input type="checkbox" id="'.$pvote_value.'" value="'.$pvote_value.'" name="pvotes[]" style="display:none"><a href="#'.$winner['Code'].'" class="wpcf7-list-item-label" data-target="#'.$rcname.'" data-toggle="modal" data-nomineecode="'.$winner['Code'].'">'.$winner['Name'].'</a></div>
          </div>';

          $_wninfo['header_info'] .= '<div class="vala-modal-title cn-'.$winner['Code'].'" id="'.$winner['Code'].'">'.$winner['Name'].'</div>';
          $_wninfo['header_info_desc'] .= '<div class="vala-modal-title-desc cn-'.$winner['Code'].'" id="'.$winner['Code'].'"><h5>'.stripslashes_deep( $winner['Org'] ).' - '.$rrname.'</h5></div>';
          
          // Youtube
          if( $youtube_link && !preg_match( '/embed/', $youtube_link ) ){
              $_vid = '';
                 list(,$ytargs) = explode( '?', $youtube_link);
                 $vid = explode( '&', $ytargs);
                 foreach( $vid as $_v ){
                     if( preg_match( '/v=/', $_v ) ){ 
                         $_vid = str_replace('v=','',$_v);
                         $_vid = trim( $_vid );
                     }
                  }
                 
            //$_youtube_html = '<iframe id="'.$_vid.'" class="app_youtube" width="100%" height="315" src="http://www.youtube.com/embed/'.$_vid.'?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>';
            $_youtube_html = '<iframe id="'.$_vid.'" class="app_youtube" width="100%" height="315" src="" frameborder="0" allowfullscreen></iframe>';
          }elseif($youtube_link){
            $_vid = str_replace('http://www.youtube.com/embed/',$youtube_link);
            //$_youtube_html = '<iframe id="'.$_vid.'" class="app_youtube" width="100%" height="315" src="'.$youtube_link.'?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>';  
            $_youtube_html = '<iframe id="'.$_vid.'" class="app_youtube" width="100%" height="315" src="" frameborder="0" allowfullscreen></iframe>';  
          }
          
          //$_youtube_html = '<a class="item_gallery" href="'.zp_video_preg_match( $youtube_link ).'"></a>';
          $read_more_html = ( ( isset( $youtube_link ) && !empty( $youtube_link ) ) ? '<div style="width:95%"><p>'.$_youtube_html.'</p></div>' : '' );
          
          // Vote Button
          $vote_btn_sc = '[nv_voting_btn appid="'.$winner['id'].'" catid="'.$cat['category_id'].'" rid="'.$rid.'" appcatid="'.$winner['app_cat_id'].'"]Vote[/nv_voting_btn]';
          $vote_btn = do_shortcode($vote_btn_sc);
          
          $_wninfo['body_info'] .= '<div class="vala-modal-title-body cn-'.$winner['Code'].'" id="'.$winner['Code'].'"><div class="image"><div class="avatar" style="background-image: url(\''.$winner['Image'].'\');"></div>'.$sc_html.'</div><div class="va-bio"><p style="width: 95%;">'.$bio[0].'</p> '.$read_more_html.'</div>'.$vote_btn.'</div>';
        
        }
    } // endof foreach
        
        $html .= '<div id="'.$rcname.'" class="winner modal fade red" style="display: none; width: 100%;" aria-hidden="false">';
               $html .= '<div class="modal-dialog">';
               $html .= '<div class="modal-content">';
               $html .= '<div class="modal-header"><button style="text-shadow: none !important" data-dismiss="modal" class="close"><span>x</span></button><br><h4 class="modal-title" style="font-size: 24px;">'.$_wninfo['header_info'].'</h4><div class="vala-modal-title-desc-wrap">'.$_wninfo['header_info_desc'].'</div></div>';
               $html .= '<div class="modal-body" style="padding-bottom: 1em;">'.$_wninfo['body_info'].'</div>';
                        
               $html .= '<div class="modal-footer">
                            <ul id="cn-'.$rcname.'" class="pager"><li><a class="pagerPrev" href="#prev">Previous</a></li><li><a class="pagerNext" href="#next">Next</a></li></ul>
                        </div>
                    </div>
                </div>
            </div>';
    }
    //$html = ob_get_contents();
    
  }
  
  return $html;
}

/**
* WPCF7 Submission
*/
function new_vala_get_submit_content_to_db( $items ){
    global $wpdb;

    if( isset( $_POST['new_vala_id'] ) ){
        $vn_info = $_POST;
        
        //echo '<pre>'.print_r($vn_info,true).'</pre>';
        
        $_token = new_vala_requesttoken();
      
        $curdate = date("Y-m-d h:i:s");
        $_day = explode(" ", $curdate);
        $day = $_day[0];
        
        $emailSent = $items['mailSent'] ? 'y' : 'n';
        
        //rid
        list($rname,$_rid) = explode( '-', $vn_info['new_vala_id'] ); 
        
        $votes = array();
        foreach( $vn_info['pvotes'] as $vote ){
            list( $app_id, $cat_id, $rid, $app_cat_id ) = explode('-',$vote);
            $votes[] = array('app_id'=>$app_id,'cat_id'=>$cat_id, 'app_cat_id'=>$app_cat_id);    
        }
        
        $params_ = array( '_type' => 'publicvoting', 
                    'rid' => $_rid, 
                    'name' => $vn_info['your-name'], 
                    'email' => $vn_info['your-email'],
                    'votes' => json_encode( $votes ),  
                    '_token' => $_token, 
                    'ip' => new_vala_get_the_user_ip(),
                    'region' => $rname );
                    
        
                    
        $res = new_vala_insert_new_votes( $params_ );
        
        //echo '<pre>'.print_r($res,true).'</pre>';
        
        /*if( isset( $res['id'] ) ){
            $email_body = new_vala_get_email_confirmation_template($params_);   
            
            $to = $vn_info['your-email'];
            $vaemail = 'info@venusawards.co.uk';
            $subject = 'Venus Awards - Public Voting Confirmation';
            // Send email with nomination votes info.
            //$headers[] = 'From: '.$vn_info['your-name'].' <'.$vn_info['your-email'].'>';
            $headers[] = 'From: Venus Awards <'.$vaemail.'>';

            // Finally send the email.
            add_filter( 'wp_mail_content_type', 'new_vala_set_html_content_type' );
            $copy_email_sent = wp_mail( $to, $subject, $email_body, $headers );
            //$items['isCopyEmailSent'] = $copy_email_sent;
            
            // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
            remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
        }*/
        //echo '<pre>'.print_r($res,true).'</pre>';
        
    }
    
    return $items;
}
add_filter( 'wpcf7_ajax_json_echo', 'new_vala_get_submit_content_to_db', 10 );

function new_vala_set_html_content_type() {
    return 'text/html';
}

function new_vala_get_email_confirmation_template($nm){
    $emal_body = "";
    
    $curdate = date("Y-m-d h:i:s");
    $_day = explode(" ", $curdate);
    $day = $_day[0];
       
        $nminfo = $nm['nomination'];
        $nmvotesinfo = $nm['votes'];
        
                  $emal_body .= '<table cellpadding="5" class="vala-vote-table">';
                  //$emal_body .= '<tr><th width="2%">ID</th>';
                  $emal_body .= '<th width="10%">Name</th>';
                  $emal_body .= '<th width="10%">Region</th>';
                  $emal_body .= '<th withd="10%">Date</th>';
                  $emal_body .= '</tr>';
                  
                  $emal_body .= '<tr>';
                  $emal_body .= '<td>' . $nm['name'] . '</td>';
                  $emal_body .= '<td>' . $nm['region'] . '</td>';
                  $emal_body .= '<td>' . $day . '</td>';
                  $emal_body .= '</tr>';
                  $emal_body .= '</table>';
                  
                  // Votes
                  $votes = $nm['pvotes'];
                  $votes_html = "No Votes Result.";
                            if( count( $votes ) > 0 ){
                                $votes_html = "Counts: ".count($votes);
                                /*$votes_html .= '<table cellpadding="5" class="vala-vote-table-inner">'; 
                                $votes_html .= '<tr><th width="45%">Category</th>'; 
                                $votes_html .= '<th width="45%">Application</th></tr>'; 
                                
                                foreach( $votes as $vote ){
                                    $votes_html .= '<tr><td>'.$vote.'</td>';
                                    $votes_html .= '<td>'.$vote->Name.'</td></tr>';
                                }
                                $votes_html .= '</table>';*/
                            }
                  
                  $email_body .= '<h3>Votes</h3>';
                  $email_body .= $votes_html; 
    
    return $emal_body;
}
?>
