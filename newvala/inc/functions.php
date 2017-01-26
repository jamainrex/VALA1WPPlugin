<?php
/**
* Update post
*/
function new_vala_update_post($user,$wc='semi',$year=''){
    
    $user['fullname'] = $user['firstname'] . ' ' . $user['lastname'];
    
    // Set app_status
    $user['app_status'] = 1;
    $cpt_term = 'Semi Finalist';     
     
    if( $wc == 'finalist' ) 
        $cpt_term = 'Finalist';
    elseif( $wc == 'winners' )
        $cpt_term = 'Winners';
    
    // Check if term already added
    $status = 'publish';
    $cpt = 'profile';
    //$taxonomy = 'profile_category';
    $taxonomy = 'profile_event_category';
    $term = term_exists( $cpt_term, $taxonomy);
    if ($term === 0 && $term === null) {
        $term = wp_insert_term(
          $cpt_term, // the term 
          $taxonomy
        );
        
        $term_id = $term['term_id'];
    }else $term_id = $term['term_id'];
    
    // Check if term per year already added
    /*$cpt_term_yearly = $cpt_term . " " . $year;
    $taxonomy = 'profile_event_category';
    $term = term_exists( $cpt_term, $taxonomy);
    if ($term === 0 && $term === null) {
        $term = wp_insert_term(
          $cpt_term, // the term 
          $taxonomy
        );
        
        $term_id = $term['term_id'];
    }else $term_id = $term['term_id'];*/
     
    // Generate Post data
    $post = array(
      //'ID'             => [ <post id> ] // Are you updating an existing post?
      'post_content'   => $user['biography'], // The full text of the post.
      'post_name'      => $user['fullname'], // The name (slug) for your post
      'post_title'     => $user['fullname'], // The title of your post.
      'post_status'    => $status, // Default 'draft'.
      'post_type'      => $cpt, // Default 'post'.
      'post_excerpt'   => $user['personal_bio'], // For all your post excerpt needs.
      'comment_status' => 'closed', // Default is the option 'default_comment_status', or 'closed'.
      //'post_category'  => array($term_id), // Default empty.
      //'tax_input'      => array( 'profile_category' =>  ) [ array( <taxonomy> => <array | string>, <taxonomy_other> => <array | string> ) ] // For custom taxonomies. Default empty.
      //'page_template'  => [ <string> ] // Requires name of template file, eg template.php. Default empty.
    );  
    
    // Check if app_id is already exist.
    if( $_post_id = new_vala_isAppidExist( $user['app_id'] ) ){
        $post['ID'] = $_post_id;
        $post_id = wp_update_post( $post, $wp_error );
    }else
        $post_id = wp_insert_post( $post, $wp_error );
    
    
    if( $post_id ){
        $user['post_id'] = $post_id;
        // set terms
        wp_set_post_terms( $post_id, $term_id, $taxonomy );

        // Update meta
        if ( ! add_post_meta( $post_id, '_vala_details', $user, true ) ) { 
           update_post_meta ( $post_id, '_vala_details', $user );
        }
        
        // Add VALA ID to metadata
        if ( ! add_post_meta( $post_id, '_vala_app_id', $user['app_id'], true ) ) { 
           update_post_meta ( $post_id, '_vala_app_id', $user['app_id'] );
        }
        
        return $post_id;
    }else die($wp_error);
}


function new_vala_get_the_user_ip() {
if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
return $ip;
}

function new_vala_insert_new_votes($params_){
    
    //$api_url = 'http://178.62.111.176/vala-api/';  //Demo Site
    //$api_url = 'http://valasystem.com/vala-api/'; 
    //$api_url = 'http://localhost/newvala/public/vala-api/'; 
    $api_url = EWM_NEW_VALA_API_URL;
    $url = $api_url . 'publicvoting/insert';
    
    //echo '<pre>'.print_r($params_,true).'</pre>';
    
    /* initialize curl handle */
    $ch = curl_init();
    /* set url to send post request */
    curl_setopt($ch, CURLOPT_URL, $url);
    /* allow redirects */
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            /* return a response into a variable */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            /* times out after 30s */
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            /* set POST method */
            curl_setopt($ch, CURLOPT_POST, 1);
            /* add POST fields parameters */
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params_);
            /* execute the cURL */
            $result = curl_exec($ch);
            curl_close($ch);
            
    return $result;
}
?>
