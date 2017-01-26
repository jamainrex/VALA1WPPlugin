<?php
function new_vala_fetch_api($fn='',$data='',$dev=false){
    
    //$api_url = 'http://178.62.111.176/vala-api/';  //Demo Site
    //$api_url = 'http://valasystem.com/vala-api/'; 
    //$api_url = 'http://localhost/newvala/public/vala-api/'; 
    
    $api_url = EWM_NEW_VALA_API_URL;
    
    /**
    * For Debugging Purposes
    * Only Admin can set Debugging Request
    */
    if( current_user_can('administrator') && isset( $_GET['nv_api_server'] )  ) {
        
        if( $_GET['nv_api_server'] == 'dev' )
            $api_url = 'http://dev.valasystem.com/vala-api/';
        elseif( $_GET['nv_api_server'] == 'staging' )
            $api_url = 'https://stage.valasystem.com/vala-api/';
        
    }
    
    set_time_limit(30);
    $JSON = file_get_contents( $api_url . $fn . '/' . $data );
    $res = json_decode($JSON,true);
    
    if($JSON === null){
          return array( 'error_msg'=>"CONNECTION ERROR");
      }
      if($JSON === false){
          return array( 'error_msg'=>"CONNECTION ERROR");
      }
      
    
    if( $dev ) echo '<pre>'.print_r($res,true).'</pre>';
      
    return $res;    
}

function new_vala_fetch_api_($fn='',$data='',$dev=false){
    
    //$api_url = 'http://178.62.111.176/vala-api/';  //Demo Site
    //$api_url = 'http://valasystem.com/vala-api/'; 
    //$api_url = 'http://localhost/newvala/public/vala-api/'; 
                 
    //$api_url = EWM_NEW_VALA_API_URL;
    $api_url = 'http://dev.valasystem.com/vala-api/';
    
    set_time_limit(30);
    $JSON = file_get_contents( $api_url . $fn . '/' . $data );
    $res = json_decode($JSON,true);
    
    if($JSON === null){
          return array( 'error_msg'=>"CONNECTION ERROR");
      }
      if($JSON === false){
          return array( 'error_msg'=>"CONNECTION ERROR");
      }
      
    
    if( $dev ) echo '<pre>'.print_r($res,true).'</pre>';
      
    return $res;    
}

// default cached for one day
function new_vala_fetch_api_cached( $fn='', $data='', $filter='', $expiry = 3600, $cacheit = false ){
    
    if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
    
    $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        if( $fn == 'applications' && $filter == 'winners' ){ // Set dev.valasystem.com for winners for now.
            // It wasn't there, so regenerate the data and save the transient
            //$new_vala_api_call_results = new_vala_fetch_api_( $fn, $data, false );
            $new_vala_api_call_results = new_vala_fetch_api( $fn, $data, false );
            $new_vala_api_call_results = array( 'winners' => $new_vala_api_call_results['winners'], 'categories' => $new_vala_api_call_results['categories'] ); 
        }else{ 
            // It wasn't there, so regenerate the data and save the transient
            $new_vala_api_call_results = new_vala_fetch_api( $fn, $data, false );
            
            // For Applications API Call 
            if( $fn == 'applications' && $filter == 'semi' ){
                $new_vala_api_call_results = array( 'semifinalists' => $new_vala_api_call_results['semifinalists'], 'categories' => $new_vala_api_call_results['categories'] );
            }
            elseif( $fn == 'applications' && $filter == 'finalists' ){
                $new_vala_api_call_results = array( 'finalists' => $new_vala_api_call_results['finalists'], 'categories' => $new_vala_api_call_results['categories'] ); 
            }
        }
        
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
}

/**
* Fetch regions
*/
function new_vala_getallregions($dev=false){
    $regions = new_vala_fetch_api('regions','getall',$dev);
    return $regions;
}

function new_vala_getappregions($dev=false){
    $regions = new_vala_fetch_api('regions','appRegions',$dev);
    return $regions;
}

function new_vala_getappregions_($dev=false){
    return new_vala_fetch_api_cached( 'regions','appRegions','appregions' );
}

/**
* Fetch All Applications
*/
function new_vala_getallapplicationsbyregion($region,$dev=false){
    return new_vala_fetch_api('applications',$region,$dev);
}

/**
* Winners Circle API
*/
// Semi-finalists
function new_vala_getsemibyregion_($region,$dev=false,$cacheit=false,$expiry=3600){
    return new_vala_fetch_api_cached('applications',$region,'semi',$expiry,$cacheit);
}
// Finalists
function new_vala_getfinalistbyregion_($region,$dev=false,$cacheit=false,$expiry=3600){
    return new_vala_fetch_api_cached('applications',$region,'finalists',$expiry,$cacheit);
}
// Winners
function new_vala_getwinnersbyregion_($region,$dev=false,$cacheit=false,$expiry=3600){
    return new_vala_fetch_api_cached('applications',$region,'winners',$expiry,$cacheit);
}


/**
* Fetch Semi-finalists
*/
function new_vala_getsemibyregion($region,$dev=false,$cached=false){
    // If cached is set
    if( $cached === true ){
        $expiry = is_numeric( $cached ) ? (int) $cached : 3600;
        $semi = new_vala_fetch_api_cached('applications',$region,'semi',$expiry);
        
        return $semi;
    }
    
    if($dev)
        $semi = new_vala_fetch_api_('applications',$region,$dev);
    else
        $semi = new_vala_fetch_api('applications',$region,$dev);
    return array( 'semifinalists' => $semi['semifinalists'], 'categories' => $semi['categories'] );
}

/**
* Fetch Finalists
*/
function new_vala_getfinalistbyregion($region,$dev=false,$cached=false){
    // If cached is set
    if( $cached === true ){
        $expiry = is_numeric( $cached ) ? (int) $cached : 3600;
        $semi = new_vala_fetch_api_cached('applications',$region,'finalists',$expiry);
        
        return $semi;
    }
    
    if($dev)
        $semi = new_vala_fetch_api_('applications',$region,$dev);
    else
        $semi = new_vala_fetch_api('applications',$region,$dev);
    return array( 'finalists' => $semi['finalists'], 'categories' => $semi['categories'] );
}

/**
* Fetch Winners
*/
function new_vala_getwinnersbyregion($region,$dev=false,$cached=false){
    // If cached is set
    if( $cached === true ){
        $expiry = is_numeric( $cached ) ? (int) $cached : 3600;
        $semi = new_vala_fetch_api_cached('applications',$region,'winners',$expiry);
        return $semi;
    }
    
    if($dev)
        $semi = new_vala_fetch_api_('applications',$region,$dev);
    else
        $semi = new_vala_fetch_api_('applications',$region,$dev);
    return array( 'winners' => $semi['winners'], 'categories' => $semi['categories'] );
}

/**
* Fetch User Details
*/
function new_vala_getappuserdetails($id,$dev=false){
    $user = new_vala_fetch_api('appuser',$id,$dev);
    
    // Override Bio Informations
    $user['biography'] = $user['app_org_bio'];
    $user['personal_bio'] = $user['app_bio'];
    
    return $user;
}

/**
* Public Votes
*/
function new_vala_putpublicvote($pvote,$dev=false){
    $vote = new_vala_fetch_api('publicvote',$pvote,$dev);
    return $vote;
}
function new_vala_getvotedetailsbyid($pvid,$dev=false){
    return new_vala_fetch_api('getvoterdetailsbyid',$pvid,$dev);
}
function new_vala_getvotesbyregion($rid,$dev=false){
    return new_vala_fetch_api('getvotes',$rid,$dev);
}

/**
* Fetch User Details
*/
function new_vala_getuserdetails($id,$dev=false){
    $user = new_vala_fetch_api('user',$id,$dev);
    return $user;
}

function new_vala_getuserpublicvotes($regid,$dev=false){
    $votes = new_vala_fetch_api('getvotesbyregid',$regid,$dev);
    return $votes;
}

function new_vala_isuserlogged($dev=false){
    return new_vala_fetch_api('user','islogged',$dev);
}

function new_vala_getcategorysponsorlistbyregion_($region,$cacheit=false,$expiry=3600){
   //$categories = new_vala_fetch_api_cached('getcategoriesbyregion',$region,$dev);
   
   if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
   
   $fn = 'getcategoriesbyregion';
   $data = $region;
   $filter = 'categorysponsorslist';
   //$expiry = 3600;
   
   $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        // It wasn't there, so regenerate the data and save the transient
        $sponsors = new_vala_getcontractsbyregion($region);
        $catData = new_vala_getcategoriesbyregion($region);
        
        $_vs = $sponsors['all'];
        $cats = $catData['all'];
        // Set empty result
        $new_vala_api_call_results = array();
        
        foreach( $_vs as $s ){
             
             if( $s['type'] != 'Category Sponsor' ) continue;
             
             $category = null;
             foreach( $cats as $cat ){
                 if( $cat['contract_id'] === $s['id'] ){
                        $category = $cat; break;
                 }
                      
             }

            $sp = trim( $s['org_name'] );
            if( empty( $sp ) ) continue;
            
            $new_vala_api_call_results[] = array( 'sponsor'=>$s, 'category'=>$category );
        }
        
        //$new_vala_api_call_results = new_vala_fetch_api( $fn, $data, false );
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
}

function new_vala_getcategorieswithsponsorslistbyregion_($region,$cacheit=false,$expiry=3600){
   //$categories = new_vala_fetch_api_cached('getcategoriesbyregion',$region,$dev);
   
   if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
   
   $fn = 'getcategorieswithsponsorslistbyregion';
   $data = $region;
   $filter = 'list';
   //$expiry = 3600;
   
   $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        // It wasn't there, so regenerate the data and save the transient
        $sponsors = new_vala_getcontractsbyregion($region);
        $catData = new_vala_getcategoriesbyregion($region);
        
        $_vs = $sponsors['all'];
        $cats = $catData['all'];
          
          // Set empty result
          $new_vala_api_call_results = array();
          
          foreach( $cats as $cat ){
              $sponsor = array(); 
              if( is_numeric( $cat['contract_id'] ) ){
                     foreach( $_vs as $s ){
                         if( $s['type'] != 'Category Sponsor' ) continue;
                         
                         if( $cat['contract_id'] === $s['id'] ){
                                $sponsor = $s; break;
                         }
                              
                     }
              }
               $new_vala_api_call_results[] = array( 'sponsor'=>$sponsor, 'category'=>$cat );
          }
        
        //$new_vala_api_call_results = new_vala_fetch_api( $fn, $data, false );
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
}

function new_vala_getcategorysponsorlogobyregion_($region,$cacheit=false,$expiry=3600){
   //$categories = new_vala_fetch_api_cached('getcategoriesbyregion',$region,$dev);
   
   if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
   
   $fn = 'getcategoriesbyregion';
   $data = $region;
   $filter = 'categorysponsorslogo';
   //$expiry = 3600;
   
   $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        // It wasn't there, so regenerate the data and save the transient
        $sponsors = new_vala_getcontractsbyregion($region);
        $catData = new_vala_getcategoriesbyregion($region);
        
        $_vs = $sponsors['all'];
        $cats = $catData['all'];
        // Set empty result
        $new_vala_api_call_results = array();
        
        foreach( $_vs as $s ){
        
             //set the correct org_id
             $s['organization_id'] = $s['organization']['id'];
             
             $category = null;
             foreach( $cats as $cat ){
                 if( $cat['contract_id'] === $s['id'] ){
                        $category = $cat; break;
                 }
                      
             }

            $sp = trim( $s['org_name'] );
            if( empty( $sp ) ) continue;
            
            $new_vala_api_call_results[] = array( 'sponsor'=>$s, 'category'=>$category );
        }
        
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
}   

// New Sponsors Shortcode for Filter by Year.
function new_vala_getcategorysponsorlogobyregionandyear($region,$year=2015,$cacheit=false,$expiry=3600){
    global $__newvalaobj;     
    
    $yearly_region_id = $__newvalaobj->getYearlyRegionIdByYear( $year );    
    
   if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
   
   $fn = 'getcategoriesbyregion';
   $data = $region;
   $filter = 'categorysponsorslogobyregionandyear_'. $year;
   
   
   $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        // It wasn't there, so regenerate the data and save the transient
        $sponsors = new_vala_getcontractsbyregion($region);
        $catData = new_vala_getcategoriesbyregion( $yearly_region_id );
        
        $_vs = $sponsors['all'];
        $cats = $catData['all'];
        // Set empty result
        $new_vala_api_call_results = array();
        
        foreach( $_vs as $s ){
             // Filter by Year
             if( is_numeric( $year ) && (int) $year > 0 && $s['year'] != $year ) continue;
            
             //set the correct org_id
             $s['organization_id'] = $s['organization']['id'];
             
             $category = null;
             foreach( $cats as $cat ){
                 if( $cat['contract_id'] === $s['id'] ){
                        $category = $cat; break;
                 }
                      
             }

            $sp = trim( $s['org_name'] );
            if( empty( $sp ) ) continue;
            
            $new_vala_api_call_results[] = array( 'sponsor'=>$s, 'category'=>$category );
        }
        
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
} 
function new_vala_getcategorysponsorsliderbyregionandyear($region,$year=2015,$cacheit=false,$expiry=3600){
   
   if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
   
   $fn = 'getcategoriesbyregion';
   $data = $region;
   $filter = 'categorysponsorssliderbyregionandyear_'. $year;
   
   
   $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        // It wasn't there, so regenerate the data and save the transient
        $sponsor_banners = new_vala_getcontractsbyregion($region);
        
        // Set empty result
        $new_vala_api_call_results = array();
        
        foreach( $sponsor_banners['all'] as $s ){
            // Filter by Year
            if( is_numeric( $year ) && (int) $year > 0 && $s['year'] != $year ) continue;
        
            if( !isset( $s['org_logo'] ) || empty( $s['org_logo'] ) ) continue;
        
            //set the correct org_id
            $s['organization_id'] = $s['organization']['id'];
            
            $_allowed = array('jpg','jpeg','png','gif');
            
            $_logo = explode( '.', $s['org_logo'] ); 
            $_format = strtolower( $_logo[sizeof($_logo)-1] );
            
            // check if image
            if( !in_array( $_format, $_allowed) ) continue;
         
            // Add to Carousel
            $new_vala_api_call_results[] = $s;   
        }
        
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
} 

function new_vala_getcategorieswithsponsorslistbyregionandyear($region, $year=2015,$cacheit=false,$expiry=3600){
   global $__newvalaobj; 
   $yearly_region_id = $__newvalaobj->getYearlyRegionIdByYear( $year ); 
      
   if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
   
   $fn = 'getcategorieswithsponsorslistbyregion_'.$year;
   $data = $region;
   $filter = 'list';
   //$expiry = 3600;
   
   $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        // It wasn't there, so regenerate the data and save the transient
        $sponsors = new_vala_getcontractsbyregion( $region );
        $catData = new_vala_getcategoriesbyregion( $yearly_region_id );
        
        $_vs = $sponsors['all'];
        $cats = $catData['all'];
          
          // Set empty result
          $new_vala_api_call_results = array();
          
          foreach( $cats as $cat ){
              $sponsor = array(); 
              if( is_numeric( $cat['contract_id'] ) ){
                     foreach( $_vs as $s ){
                         // Filter by Year
                         if( is_numeric( $year ) && (int) $year > 0 && $s['year'] != $year ) continue;
                         
                         if( $s['type'] != 'Category Sponsor' && $s['cd_category_id'] == 0 ) continue;
                         
                         if( $cat['contract_id'] === $s['id'] ){
                                $sponsor = $s; break;
                         }
                              
                     }
              }
               $new_vala_api_call_results[] = array( 'sponsor'=>$sponsor, 'category'=>$cat );
          }
        
        //$new_vala_api_call_results = new_vala_fetch_api( $fn, $data, false );
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
}

function new_vala_getcategorysponsorlogobyregion_dev($region,$year=2015,$cacheit=false,$expiry=3600){
    global $__newvalaobj;     
    
    $yearly_region_id = $__newvalaobj->getYearlyRegionIdByYear( $year );    

   if( isset( $_GET['nv_cacheit'] ) && $_GET['nv_cacheit'] == 'yes' ) $cacheit = true;
   
   $fn = 'getcategoriesbyregion';
   $data = $region;
   $filter = 'categorysponsorslogobyregionandyear_dev_'. $year;
   
   
   $transient_name = "nv_api_".$fn."_".$data."_".$filter; 
    
    // Get any existing copy of our transient data
    if ( false === ( $new_vala_api_call_results = get_transient( $transient_name ) ) || $cacheit === true ) {
        // It wasn't there, so regenerate the data and save the transient
        $sponsors = new_vala_getcontractsbyregion($region);
        $catData = new_vala_getcategoriesbyregion( $yearly_region_id );
        
        $_vs = $sponsors['all'];
        $cats = $catData['all'];
        // Set empty result
        $new_vala_api_call_results = array();
        
        foreach( $_vs as $s ){
             // Filter by Year
             if( is_numeric( $year ) && (int) $year > 0 && $s['year'] != $year ) continue;
            
             //set the correct org_id
             $s['organization_id'] = $s['organization']['id'];
             
             $category = null;
             foreach( $cats as $cat ){
                 if( $cat['contract_id'] === $s['id'] ){
                        $category = $cat; break;
                 }
                      
             }

            $sp = trim( $s['org_name'] );
            if( empty( $sp ) ) continue;
            
            $new_vala_api_call_results[] = array( 'sponsor'=>$s, 'category'=>$category );
        }
        
        set_transient( $transient_name, $new_vala_api_call_results, $expiry );
    }
    
    return $new_vala_api_call_results;
}   


/**
* Fetch Contracts by Region
*/
function new_vala_getcontractsbyregion($region,$dev=false){
    if( $dev )
        return new_vala_fetch_api_('getcontractsbyregion',$region);
    else
        return new_vala_fetch_api('getcontractsbyregion',$region,$dev);
}

/**
* Fetch Categories by Region
*/
function new_vala_getcategoriesbyregion($region,$dev=false){
    if( $dev )
        return new_vala_fetch_api_('getcategoriesbyregion',$region); 
    else
        return new_vala_fetch_api('getcategoriesbyregion',$region,$dev);
}

// Sponsor Slider
function new_vala_getsponsorsliderbyregion( $region, $dev=false, $cachit=false, $expiry=3600 ){
    return new_vala_fetch_api_cached('getcontractsbyregion',$region,'sponsorslider',$expiry,$cacheit);
}

function new_vala_requesttoken(){
    //$token = file_get_contents( "http://localhost/newvala/public/vala-api/requesttoken/1q2w3e4r5tva" );
    $token = new_vala_fetch_api('requesttoken','1q2w3e4r5tva');
    $_token = str_replace('"','',$token);
    return $_token; 
}

/**
* Check if app_id already exist on posts
*/
function new_vala_isAppidExist($app_id){
    $args = array(
     'post_type'  => 'profile',
    'meta_query' => array(
        array(
            'key'     => '_vala_app_id',
            'value'   => $app_id,
            'compare' => '=',
        ),
    ),
    'fields' => 'ids'
);
    $query = new WP_Query( $args );
    if( count( $query->posts ) > 0 ) return $query->posts[0];
    else return false;
}


function new_vala_isUserLogged_new($fn,$params_=array()){
    //$api_url = 'http://178.62.111.176/vala-api/';  //Demo Site
    //$api_url = 'http://valasystem.com/vala-api/'; 
    //$api_url = 'http://localhost/newvala/public/vala-api/'; 
    $api_url = EWM_NEW_VALA_API_URL;
    $url = $api_url . $fn;
    
    //echo '<pre>'.print_r($params_,true).'</pre>';
    
    /* initialize curl handle */
    $ch = curl_init();
    /* set url to send post request */
    curl_setopt($ch, CURLOPT_URL, $url);
    /* allow redirects */
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            /* return a response into a variable */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            /* times out after 30s */
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            /* set POST method */
            //curl_setopt($ch, CURLOPT_POST, 1);
            /* add POST fields parameters */
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $params_);
            /* execute the cURL */
            $result = curl_exec($ch);
            curl_close($ch);
            
    return $result;
}
?>
