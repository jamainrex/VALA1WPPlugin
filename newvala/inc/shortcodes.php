<?php
add_shortcode( 'new_vala_semi', 'new_vala_semi_sc' );
function new_vala_semi_sc( $atts, $content ) {
    global $__newvalaobj;
    
    extract( shortcode_atts( array(
        'type' => '',
        'columns' => '',
        'show_all' => '',
        'region' => '',
        'year' => 2015,
        'filter' => '',
        'withbio' => '',
        'withnameorg' => '',
        'cacheit' => '',
        'dev' => ''
    ), $atts ) ); 
    
    //if( is_numeric( $year ) || $year == 'yes' )
        $nv_region_id = $__newvalaobj->getYearlyRegionIdByYear( $year );
    /*else
        $nv_region_id = $__newvalaobj->getRegionID(); */
    
    $withbio = ( $withbio == 'yes' ) ? true : false;
    $withnameorg = ( $withnameorg == 'yes' ) ? true : false;  
    $cacheit = ( $cacheit == 'yes' ) ? true : ( is_numeric( $cacheit ) ? (int) $cacheit : false );  
    
    // if dev print_r results    
    $dev = empty( $a['dev'] ) ? false : true;
    // get the id of region by year
    //$region_id = isset( $vala_region_list[(int)$year][$region] ) ? (int) $vala_region_list[(int)$year][$region] : 0;
    $region_id = $nv_region_id;
    // Array where data to be stored.
    $data = array(); 
    
    // Get Semi-finalists
    //$data = new_vala_getsemibyregion($region_id,false,$cacheit);
    $data = new_vala_getsemibyregion_($region_id,false,$cacheit);
    
    
    
    $nv_semi = $data['semifinalists'];
    $nv_cats = $data['categories'];
    
    //echo '<pre>'.print_r($nv_cats,true).'</pre>';
    
   // Filter by Category 
   $filter = ( $filter == 'true' ? true : false );   

    // print_for_debugging($data);
    // exit;
    $output='';
    ob_clean();
    // Build html
   // $output .= va_region_semi_and_finalist_template(4,$data,$categories,'profile',true, $filter);
   $output .= new_vala_all_nominees_template(4,$nv_cats,'profile',true, $filter, 'apps', $withbio, $withnameorg);
    
    return $output;
}

add_shortcode( 'new_vala_finalist', 'new_vala_finalist_sc' );
function new_vala_finalist_sc( $atts, $content ) {
    global $__newvalaobj;
    
    extract( shortcode_atts( array(
        'type' => '',
        'columns' => '',
        'show_all' => '',
        'region' => '',
        'year' => 2015,
        'filter' => '',
        'withbio' => '', 
        'withnameorg' => '',
        'cacheit' => '',
        'dev' => ''
    ), $atts ) );
    
    $nv_region_id = $__newvalaobj->getYearlyRegionIdByYear( $year ); 
    
    /*if( is_numeric( $year ) || $year == 'yes' )
        $nv_region_id = $__newvalaobj->isYearlyIDExists( $year );
    else
        $nv_region_id = $__newvalaobj->getRegionID();*/
    
    $withbio = ( $withbio == 'yes' ) ? true : false;  
    $withnameorg = ( $withnameorg == 'yes' ) ? true : false;  
    $cacheit = ( $cacheit == 'yes' ) ? true : ( is_numeric( $cacheit ) ? (int) $cacheit : false );
    
    // if dev print_r results    
    $dev = empty( $a['dev'] ) ? false : true;
    // get the id of region by year
    //$region_id = isset( $vala_region_list[(int)$year][$region] ) ? (int) $vala_region_list[(int)$year][$region] : 0;
    $region_id = $nv_region_id;
    // Array where data to be stored.
    $data = array(); 
    
    // Get Semi-finalists
    //$data = new_vala_getfinalistbyregion($region_id,false,$cacheit);
    $data = new_vala_getfinalistbyregion_($region_id,false,$cacheit);

    $nv_semi = $data['finalists'];
    $nv_cats = $data['categories'];
    
    //echo '<pre>'.print_r($nv_cats,true).'</pre>';
    
   // Filter by Category 
   $filter = ( $filter == 'true' ? true : false );   

    // print_for_debugging($data);
    // exit;
    $output='';
    ob_clean();
    // Build html
   // $output .= va_region_semi_and_finalist_template(4,$data,$categories,'profile',true, $filter);
   $output .= new_vala_all_nominees_template(4,$nv_cats,'profile',true, $filter, 'finalApps', $withbio, $withnameorg);
    
    return $output;
}

add_shortcode( 'new_vala_winners', 'new_vala_winners_sc' );
function new_vala_winners_sc( $atts, $content ) {
    global $__newvalaobj;
    
    extract( shortcode_atts( array(
        'type' => '',
        'columns' => '',
        'show_all' => '',
        'region' => '',
        'year' => 2015,
        'filter' => '',
        'withnameorg' => '',  
        'withbio' => '',  
        'cacheit' => '',
        'dev' => ''
    ), $atts ) );
   
    $nv_region_id = $__newvalaobj->getYearlyRegionIdByYear( $year ); 
    
    /*if( is_numeric( $year ) || $year == 'yes' )
        $nv_region_id = $__newvalaobj->isYearlyIDExists( $year );
    else
        $nv_region_id = $__newvalaobj->getRegionID(); */
    
    $withbio = ( $withbio == 'yes' ) ? true : false;  
    $withnameorg = ( $withnameorg == 'yes' ) ? true : false;  
    $cacheit = ( $cacheit == 'yes' ) ? true : ( is_numeric( $cacheit ) ? (int) $cacheit : false );
    
    // if dev print_r results    
    $dev = empty( $a['dev'] ) ? false : true;
    // get the id of region by year
    //$region_id = isset( $vala_region_list[(int)$year][$region] ) ? (int) $vala_region_list[(int)$year][$region] : 0;
    $region_id = $nv_region_id;
    // Array where data to be stored.
    $data = array(); 
    
    // Get Winners
    //$data = new_vala_getfinalistbyregion($region_id);
    //$data = new_vala_getwinnersbyregion($region_id,false,$cacheit);
    $data = new_vala_getwinnersbyregion_($region_id,false,$cacheit);
    
    $nv_semi = $data['winners'];
    //$nv_semi = $data['finalists'];
    $nv_cats = $data['categories'];
    
   // Filter by Category 
   $filter = ( $filter == 'true' ? true : false );   

    // print_for_debugging($data);
    // exit;
    $output='';
    ob_clean();
    // Build html
   // $output .= va_region_semi_and_finalist_template(4,$data,$categories,'profile',true, $filter);
   $output .= new_vala_all_nominees_template_(4,$nv_cats,'profile',true, $filter, 'winnerApps', $withbio, $withnameorg);
   //$output .= new_vala_all_nominees_template_(4,$nv_cats,'profile',true, $filter, 'finalApps');
    
    return $output;
}

add_shortcode('new_vala_public_voting_semi', 'new_vala_public_voting_semi_sc');
function new_vala_public_voting_semi_sc($atts, $content){
    global $__newvalaobj;
    
    $nv_region_id = $__newvalaobj->getRegionID();
    
     extract( shortcode_atts( array(
        'type' => '',
        'columns' => '',
        'show_all' => '',
        'region' => '',
        'year' => '',
        'filter' => '',
        'dev' => ''
    ), $atts ) );
    
    // Array where data to be stored.
    $data = array(); 
    
    $region_id = $nv_region_id;
    //$region_id = 1; // Dorset
    
    // Get all Categories for Public VOting
    $catData = new_vala_getcategoriesbyregion($region_id);
    $appPublicVotingCategories = $catData['public_votes'];
    
    //echo '<pre>'.print_r($appPublicVotingCategories,true).'</pre>';
    
    // Get Semi-finalists
    $data = new_vala_getsemibyregion($region_id);
    
    $nv_semi = $data['semifinalists'];
    $nv_cats = $data['categories'];
    
    // Filter by Category 
   $filter = ( $filter == 'true' ? true : false );  
    
    $output='';
    ob_clean();
    $output .= new_vala_semi_public_voting_template(4,array('cats'=>$nv_cats,'pub_cats'=>$appPublicVotingCategories),'profile',true,$filter);
    
    return $output;
    //echo '<pre>'.print_r($nv_cats,true).'</pre>';
    
    /*foreach( $appPublicVotingCategories as $publicCategory ){
        if( !isset( $nv_cats[$publicCategory['id']] ) ) continue;
        
        echo '<pre>'.print_r($publicCategory,true).'</pre>';
        //echo $publicCategory['id']; 
        $apps = $nv_cats[$publicCategory['id']]['apps'];
        echo '<pre>'.print_r($apps,true).'</pre>';
    }*/
}

/**
* WPCF7 ADD Shortcode
*/
//if( function_exists( 'wpcf7_add_shortcode' ) ){

wpcf7_add_shortcode('newvala_wpcf7_hiddenfld', 'new_vala_sfpv_hiddenfld', true);
function new_vala_sfpv_hiddenfld(){
    global $__newvalaobj;
    $nv_region_id = $__newvalaobj->getRegionID();
    $region_name = get_bloginfo( 'name' );
    $rname = strtolower( str_replace(array(" ","/"),"-",$region_name) );   
    $output = "";
    $output .= '<input type="hidden" name="new_vala_id" value="'.$rname.'-'.$nv_region_id.'">';
    return $output;    
}
    
wpcf7_add_shortcode('newvala_wpcf7_semi_checkboxes', 'new_vala_sfpv_checkboxes', true);
function new_vala_sfpv_checkboxes(){    
    global $__newvalaobj;
    $nv_region_id = $__newvalaobj->getRegionID();
    
    // $awards = array_keys( $winners );
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    wp_enqueue_style( 'winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/winners-circle.css", array(), '1.5', 'all' );
    wp_enqueue_style( 'bootstrap', $stylesheet_directory_uri . '/css/modals/bootstrap.css', array(), '1.2', 'all' );
    wp_enqueue_style( 'vala-nomination-css', $stylesheet_directory_uri . '/vala/vala-nomination-styles.css', array(), '1.0', 'all' );
    
    wp_enqueue_style( 'newvala-css', $stylesheet_directory_uri . "/vala/newvala.css", array(), '1.0', 'all' );
    
    //wp_enqueue_script( 'bootstrap', $stylesheet_directory_uri . '/js/modals/bootstrap.min.js', array( 'jquery' ), '1.0', false );  
    wp_enqueue_script( 'bootstrap', $stylesheet_directory_uri . '/vala/bootstrap.js', array( 'jquery' ), '1.0', false );  
    //wp_enqueue_script( 'jquery-readmore', $stylesheet_directory_uri . '/vala/jquery.readmore.js', array( 'jquery' ), '1.0', false );
    wp_enqueue_script( 'newvala-js', $stylesheet_directory_uri . '/vala/newvala.js', array( 'jquery' ), '1.0', false );  
    //wp_enqueue_script( 'newvala-js', EWM_NEW_VALA_PLUGIN_DIR_URI . 'newvala.js', array( 'jquery' ), '1.0', false );  
    
    
    $region_id = $nv_region_id;
    //$region_id = 1; // Dorset
    
    // Get all Categories for Public VOting
    $catData = new_vala_getcategoriesbyregion($region_id);
    $appPublicVotingCategories = $catData['public_votes'];
    
    //echo '<pre>'.print_r($appPublicVotingCategories,true).'</pre>';
    
    // Get Semi-finalists
    $data = new_vala_getsemibyregion($region_id);
    
    $nv_semi = $data['semifinalists'];
    $nv_cats = $data['categories'];
    
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
    
    //removing extra <br>
    /*$Old     = array( '<br />', '<br>', '<p></p>' );
    $New     = array( '','','' );
    $output = str_replace( $Old, $New, $output );*/
    
    return $output;
}
add_shortcode('newvala_publicvoting_semi', 'newvala_publicvoting_semi_sc');
function newvala_publicvoting_semi_sc(){    
    global $__newvalaobj;
    $nv_region_id = $__newvalaobj->getRegionID();
    
    // $awards = array_keys( $winners );
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    wp_enqueue_style( 'winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/winners-circle.css", array(), '1.5', 'all' );
    wp_enqueue_style( 'bootstrap', $stylesheet_directory_uri . '/css/modals/bootstrap.css', array(), '1.2', 'all' );
    wp_enqueue_style( 'vala-nomination-css', $stylesheet_directory_uri . '/vala/vala-nomination-styles.css', array(), '1.0', 'all' );
    
    wp_enqueue_style( 'newvala-css', $stylesheet_directory_uri . "/vala/newvala.css", array(), '1.0', 'all' );
    
    //wp_enqueue_script( 'bootstrap', $stylesheet_directory_uri . '/js/modals/bootstrap.min.js', array( 'jquery' ), '1.0', false );  
    wp_enqueue_script( 'bootstrap', $stylesheet_directory_uri . '/vala/bootstrap.js', array( 'jquery' ), '1.0', false );  
    //wp_enqueue_script( 'jquery-readmore', $stylesheet_directory_uri . '/vala/jquery.readmore.js', array( 'jquery' ), '1.0', false );
    wp_enqueue_script( 'newvala-js', $stylesheet_directory_uri . '/vala/newvala.js', array( 'jquery' ), '1.0', false );  
    
    wp_enqueue_script( 'newvala-obj-js' );  
    //wp_enqueue_script( 'newvala-js', EWM_NEW_VALA_PLUGIN_DIR_URI . 'newvala.js', array( 'jquery' ), '1.0', false );  
    
    
    $region_id = $nv_region_id;
    //$region_id = 1; // Dorset
    
    // Get all Categories for Public VOting
    $catData = new_vala_getcategoriesbyregion($region_id);
    $appPublicVotingCategories = $catData['public_votes'];
    
    //echo '<pre>'.print_r($appPublicVotingCategories,true).'</pre>';
    
    // Get Semi-finalists
    $data = new_vala_getsemibyregion($region_id);
    
    $nv_semi = $data['semifinalists'];
    $nv_cats = $data['categories'];
    
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
    
    //removing extra <br>
    /*$Old     = array( '<br />', '<br>', '<p></p>' );
    $New     = array( '','','' );
    $output = str_replace( $Old, $New, $output );*/
    
    return $output;
}
//}

add_shortcode( 'new_vala_sponsors_slider', 'new_vala_sponsors_slider_sc' );
function new_vala_sponsors_slider_sc(  $atts, $content = null  ){
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
extract(  shortcode_atts(  array( 
    'name' => '',
    'region_id' => null,
    'year' => null
 ), $atts ) );
              
$output ='';
$output .= '<div id="scroller"><ul id="sponsorCarousel" class="'.$name.'">';

// old api
//$sponsor_banners = vala_filterCatsSponsorsbyregionyear($region_id,14400,$year); 

if( isset( $region_id ) ) $nv_region_id = $region_id;

// new api
//$sponsor_banners = new_vala_getcontractsbyregion($nv_region_id); 
$sponsor_banners = new_vala_getsponsorsliderbyregion($nv_region_id); 
//echo '<pre>'.print_r($sponsor_banners,true).'</pre>';   
shuffle($sponsor_banners['all']);
foreach( $sponsor_banners['all'] as $s ){
    if( !isset( $s['org_logo'] ) || empty( $s['org_logo'] ) ) continue;
    
    //set the correct org_id
    $s['organization_id'] = $s['organization']['id'];
    
    $_allowed = array('jpg','jpeg','png','gif');
    
    $_logo = explode( '.', $s['org_logo'] ); 
    $_format = strtolower( $_logo[sizeof($_logo)-1] );
    
    // check if image
    if( !in_array( $_format, $_allowed) ) continue;
    
    $captions = $s['org_name'];
    //$banner = str_replace(" ","",$s->org_banner);
    $banner = EWM_NEW_VALA_URL.'images/sponsor/'. $s['organization_id'] . '/' . $s['org_logo'];
    
    //$img_src = empty( $banner ) ? '' : get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$s->banner;
    $img_src = get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$banner;
    $output .= '<li><img alt="'.$captions.'" id="tickImage" src="'.$img_src.'&amp;h=55&amp;q=100"></li>';   
}
            
$output .= '</ul></div>';       
return $output;       
}

/**
* New Sponsor Carousel by Year
*/
add_shortcode( 'new_vala_sponsors_slider_by_year', 'new_vala_sponsors_slider_by_year_sc' );
function new_vala_sponsors_slider_by_year_sc(  $atts, $content = null  ){
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
extract(  shortcode_atts(  array( 
    'name' => '',
    'region_id' => null,
    'year' => 2015
 ), $atts ) );
              
$output ='';
$output .= '<div id="scroller"><ul id="sponsorCarousel" class="'.$name.'">';

if( isset( $region_id ) ) $nv_region_id = $region_id;

$sponsor_banners = new_vala_getcategorysponsorsliderbyregionandyear($nv_region_id, (int) $year); 

shuffle( $sponsor_banners );
foreach( $sponsor_banners as $s ){
    $captions = $s['org_name'];
    $banner = EWM_NEW_VALA_URL.'images/sponsor/'. $s['organization_id'] . '/' . $s['org_logo'];
    
    $img_src = get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$banner;
    $output .= '<li><img alt="'.$captions.'" id="tickImage" src="'.$img_src.'&amp;h=55&amp;q=100"></li>';   
}
            
$output .= '</ul></div>';      
return $output;       
}

/**Sponsors **/
//add_shortcode(  'new_vala_sponsors_logo', 'new_vala_sponsors_logo_sc'  );
function new_vala_sponsors_logo_sc(  $atts, $content = null  ) {
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
    extract(  shortcode_atts(  array( 
            'type' => 'portfolio',
            'columns' => '4',
            'year' => null
     ), $atts ) );

    $output='';

    //$sponsor_logos = vala_filterCatsSponsorsbyregionyear($region,14400,$year);    
    // new api
    $sponsors = new_vala_getcontractsbyregion($nv_region_id);
    $catData = new_vala_getcategoriesbyregion($nv_region_id);
    shuffle($sponsors['all']);
        $output = new_vala_sponsors_shortcode( array( 'sponsors'=>$sponsors['all'], 'categories' =>$catData['all'] ), $columns, $type );

    return $output;

}

add_shortcode(  'new_vala_sponsors_logo', 'new_vala_sponsors_logo_sc_'  );
// Changed to "new_vala_sponsors_logo_by_year_sc_" for Dorset
function new_vala_sponsors_logo_sc_(  $atts, $content = null  ) {
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
extract(  shortcode_atts(  array( 
            'type' => 'portfolio',
            'columns' => '4',
            'year' => 2015
     ), $atts ) );

    $output='';

    /*if( $nv_region_id == 1 ) // Set for Dorset for now.
        $catSponsors = new_vala_getcategorysponsorlogobyregionandyear($nv_region_id, (int) $year );
    else
        $catSponsors = new_vala_getcategorysponsorlogobyregion_($nv_region_id);*/
        
    $catSponsors = new_vala_getcategorysponsorlogobyregionandyear($nv_region_id, (int) $year );   
        
    shuffle( $catSponsors );
    $output = new_vala_sponsors_shortcode_( $catSponsors, $columns, $type );

    return $output;
}

add_shortcode(  'new_vala_sponsors_logo_dev', 'new_vala_sponsors_logo_sc_dev'  );
function new_vala_sponsors_logo_sc_dev(  $atts, $content = null  ) {
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
extract(  shortcode_atts(  array( 
            'type' => 'portfolio',
            'columns' => '4',
            'year' => 0
     ), $atts ) );

    $output='';
    
    $catSponsors = new_vala_getcategorysponsorlogobyregion_dev($nv_region_id, (int) $year );
    //echo '<pre>'.print_r($__newvalaobj->getRegionYearlyIDs(),true).'</pre>';
    shuffle( $catSponsors );
    $output = new_vala_sponsors_shortcode_( $catSponsors, $columns, $type );

    return $output;
}

/**
* Filter by Year.
*/
add_shortcode(  'new_vala_sponsors_logo_by_year', 'new_vala_sponsors_logo_by_year_sc_'  );
//add_shortcode(  'new_vala_sponsors_logo', 'new_vala_sponsors_logo_by_year_sc_'  );
function new_vala_sponsors_logo_by_year_sc_(  $atts, $content = null  ) {
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
extract(  shortcode_atts(  array( 
            'type' => 'portfolio',
            'columns' => '4',
            'year' => 2015
     ), $atts ) );

    $output='';
    
    $catSponsors = new_vala_getcategorysponsorlogobyregionandyear($nv_region_id, (int) $year );
    shuffle( $catSponsors );
    $output = new_vala_sponsors_shortcode_( $catSponsors, $columns, $type );

    return $output;
}

//add_shortcode( 'new_vala_category_sponsor_list', 'new_vala_category_sponsors_list_sc' );
function new_vala_category_sponsors_list_sc($atts, $content = null  ) {
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
    extract(  shortcode_atts(  array( 
            'title' => ''
     ), $atts ) );

    $output='';

    $sponsors = new_vala_getcontractsbyregion($nv_region_id);
    $catData = new_vala_getcategoriesbyregion($nv_region_id);

    $output = new_vala_category_sponsors_shortcode( array( 'sponsors'=>$sponsors['all'], 'categories' =>$catData['all'] ), $title );

    return $output;
}
//add_shortcode( 'new_vala_category_sponsor_list', 'new_vala_category_sponsors_list_sc_' );  
function new_vala_category_sponsors_list_sc_($atts, $content = null  ) {
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
    extract(  shortcode_atts(  array( 
            'title' => ''
     ), $atts ) );

    $output='';
    
    //$catSponsors = new_vala_getcategorysponsorlistbyregion_($nv_region_id,$title);
    $catSponsors = new_vala_getcategorieswithsponsorslistbyregion_($nv_region_id,$title);
    $output = new_vala_category_sponsors_shortcode_($catSponsors,$title);

    return $output;
}
add_shortcode( 'new_vala_category_sponsor_list', 'new_vala_category_sponsors_list_by_year_sc' );    
//add_shortcode( 'new_vala_category_sponsor_list_by_year', 'new_vala_category_sponsors_list_by_year_sc' );  
function new_vala_category_sponsors_list_by_year_sc($atts, $content = null  ) {
global $__newvalaobj;
$nv_region_id = $__newvalaobj->getRegionID();
    extract(  shortcode_atts(  array( 
            'title' => '',
            'year' => 2015
     ), $atts ) );

    $output='';

    $catSponsors = new_vala_getcategorieswithsponsorslistbyregionandyear($nv_region_id, (int) $year);
    $output = new_vala_category_sponsors_shortcode_($catSponsors,$title);

    return $output;
}

if( !function_exists('new_vala_national_winners_slider') ){    
function new_vala_national_winners_slider(  $atts, $content = null  ){
global $post;
extract(  shortcode_atts(  array( 
    'title' => '', 
    'category'=> 'winners',
    'winners_slug'=> 'winners',
    'is_modal' => 'false',
    'width' => '240',
    'height' => '240',  
 ), $atts ) );
 
$winnerscircle_url = site_url('/winner-circle/'.$winners_slug.'/');
 
$args = array('post_type'=> 'profile', 'showposts' => '-1', 'profile_event_category' => 'winners' );
query_posts( $args );
 
$_title = "<h1>".$title."</h1>";
$output ='';
$output .= $_title;
$output .='<div style="height: auto;">';
$output .='[newvala_client is_modal="'.$is_modal.'"]';
$i =0 ;
    if(  have_posts( ) ) {                                            
         while (  have_posts(  )  ) { the_post();
            
            $img_id = get_the_ID();
            // Get profile data
            $profile_data = get_post_meta( get_the_ID(), '_vala_details', true );
            $images = 'http://valasystem.com/images/user/'.$profile_data['id'].'/'.$profile_data['image'];;
            $name = get_the_title('',FALSE);
            $fullcontent = get_the_content();
            $addbio=get_the_excerpt(  );
            //$addbio = get_post_meta($post->ID, 'winner_addbio', true);
            //$region = get_post_meta($post->ID, 'winner_region', true);
            $region = get_bloginfo( 'name' );           
            //$category = get_post_meta($post->ID, 'winner_category', true);
            $category = $profile_data['category'][0]['name']; 
            //$company = get_post_meta($post->ID, 'winner_company', true);
            $company = $profile_data['org_name'];
            //$website = get_post_meta($post->ID, 'winner_website', true);
            $website = $profile_data['org_website'];
            //$facebook = get_post_meta($post->ID, 'winner_facebook', true);
            $facebook = $profile_data['facebook'];
            //$twitter = get_post_meta($post->ID, 'winner_twitter', true);
            $twitter = $profile_data['twitter'];
            $linkedin = $profile_data['linkedin'];
            
            $profile_permalinks = $winnerscircle_url . '?profile=' . get_the_slug( get_the_ID() ) . '&cat=' . $profile_data['category'][0]['category_id'];
            
            // winner id
            $wn_id = 'wn-'.$i;
            
            // social media icons
            $sc_html = va_social_icon( $website, $facebook, $twitter, '', '', '', $linkedin );
            // read more addtional bio
            $read_more_html = ( ( isset( $addbio ) && !empty( $addbio ) ) ? '<div style="width:95%"><a class="nbread-more" href="#'.$wn_id.'">View More</a></div> <div id="span'.$wn_id.'" class="nbt" ><p>'.$addbio.'</p></div>' : '' );
            
            $header_info = '<div class="va-modal-title cn-'.$wn_id.'" id="'.$wn_id.'"><h2>'.$name.'</h2></div>';     
            $header_info_desc = '<div class="va-modal-title-desc cn-'.$wn_id.'" id="'.$wn_id.'"><h5>'.stripslashes_deep( $company ).' - '.$region.'</h5></div>'; 
          
            $body_info = $header_info . $header_info_desc . '<div class="va-modal-title-body cn-'.$wn_id.'" id="'.$wn_id.'"><div class="image"><div class="avatar" style="background-image: url(\''.$images.'\');"></div>'.$sc_html.'</div><div class="va-bio"><p style="width: 95%;">'.$fullcontent.'</p> '.$read_more_html.'</div></div>';
            
            $output .= '<div class="profile-item" style="float:left; display:block; width:'.( (int) $width+30 ).'px;"  data-index="' . $i  . '">
                <a id="'.$img_id.'" href="'.$profile_permalinks.'" style="border:none;" class="item_gallery" data-index="' . $i  . '">
                <div style="min-height:'.$height.'px;"><img src="'.get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$images.'&amp;w='.$width.'&amp;h='.$height.'&amp;q=100&a=t&zc=2" /></div>
                <span style="display:block; width:'.$width.'px">'.$name.( empty($category) ? '' : ' - '.$category ).'</span>
                </a>
                <div id="inline-'.$img_id.'" class="hide desc">'.$body_info.'</div>
            </div>';
            
            $i++;
         }
    }
            
$output .= '[/newvala_client]';
$output .= '</div>';
$output .= '[hr]';
$output .= '&nbsp;';
//$output .= '<div class="clearfix"></div>';
            
wp_reset_query(  );            
return do_shortcode($output);
                  
}
add_shortcode(  'newvala_winners_slider', 'new_vala_national_winners_slider'  );
}

function new_vala_client_sc(  $atts, $content = null  ) {
    extract(  shortcode_atts(  array(
      'is_modal' => ''
     ), $atts ) );
     
     $_isModal = ($is_modal == 'false') ? false : true;

     

    wp_enqueue_script(  'jquery_carouFredSel'   );
    wp_enqueue_script(  'jquery_mousewheel'  );
    wp_enqueue_script(  'jquery_touchswipe' );
    wp_enqueue_script(  'jquery_transit' );
    wp_enqueue_script(  'jquery_throttle'  );        

?>

    <script type="text/javascript">jQuery.noConflict();jQuery(document).ready(function($){     
        var _isModal = '<?php echo $_isModal ?>';
        
        
        jQuery(".client_container").carouFredSel({
            auto: true,
            circular: true,
            infinite: true,
            swipe: {
                onMouse: true,
                onTouch: true
            },
            scroll : {        
                items: 1,               
                pauseOnHover    : true,
                duration        : 1000,  
                timeoutDuration : 1000
            }                   
        });
        
    // if Modal enabled
    if( _isModal == 'true' ){
        
        jQuery('#profile-carousel-modal').modal({
          show: false
        });
        
   
        
        jQuery('.client_container .item_gallery').on('click', function(e){
          var $modal = jQuery('#profile-carousel-modal');
          var desc = jQuery(this).parent().find('.desc').html();
          var index = jQuery(this).data('index');
          
          
          $modal.find('.modal-body').html( desc );
          $modal.data('current-index', index);
          $modal.find('.index').html(index + 1);
          $modal.find('.total').html( jQuery('.client_container .profile-item').length );
          $modal.modal('show');
          
         
          
          
          e.preventDefault();
        });
        
        
        jQuery('#profile-carousel-modal .close').on('click', function(e){
          jQuery('#profile-carousel-modal').modal('hide');
          
          e.preventDefault();
        });
        
        
        jQuery('#profile-carousel-modal .prev').on('click', function(e){
          var index = parseInt( jQuery('#profile-carousel-modal').data('current-index'));
          var newIndex = index-1;
          
          if( newIndex < 0 ){
            newIndex = jQuery('.client_container .profile-item').length - 1;
          }
          
          var prev = jQuery('.client_container .profile-item[data-index="' + newIndex + '"]');
          
          prev.find('.item_gallery').trigger('click');
          
          e.preventDefault();
        });
        

         jQuery('#profile-carousel-modal .next').on('click', function(e){
          var index = parseInt( jQuery('#profile-carousel-modal').data('current-index'));
          var newIndex = index+1;
          
          if( newIndex > jQuery('.client_container .profile-item').length - 1 ){
            newIndex = 0;
          }
          
          var next = jQuery('.client_container .profile-item[data-index="' + newIndex + '"]');
          
          next.find('.item_gallery').trigger('click');
          
          e.preventDefault();
        });
        
   }// end of Modal JS
        
    });

    </script>
    
    <?php 
    $region_name = get_bloginfo( 'name' );
    $region_primary_color = get_region_skin_primary_color( $region_primary_color );
    ?>
    
    <div id="profile-carousel-modal" class="nafinalist modal fade red" style="display: none; width: 100%;" aria-hidden="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span>x</span></button>
            <h4 class="modal-title">Regional Winners - <?php echo $region_name; ?></h4>
          </div>
          <div class="modal-body">
          </div>  
          <div class="prev-next">
            <a href="#" class="prev">&lt;</a>
            <span class="index"></span> / <span class="total"></span>
            <a href="#" class="next">&gt;</a>
          </div>
        </div>
      </div>  
    </div>

<?php 
        $stylesheet_directory_uri = get_stylesheet_directory_uri();
        //wp_enqueue_script( 'bootstrap', $stylesheet_directory_uri . '/js/modals/bootstrap.min.js', array( 'jquery' ), '1.0', false );
        wp_enqueue_style( 'national-winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/national-nominees.css", array(), '1.0', 'all' );
        wp_enqueue_style( 'bootstrap', $stylesheet_directory_uri . '/css/modals/bootstrap.css', array(), '1.2', 'all' );
        wp_enqueue_style( 'vala-nomination-css', $stylesheet_directory_uri . '/vala/vala-nomination-styles.css', array(), '1.0', 'all' );
        
        wp_enqueue_script( 'bootstrap', $stylesheet_directory_uri . '/vala/bootstrap.js', array( 'jquery' ), '1.0', false );
        wp_enqueue_script( 'national-nomination', $stylesheet_directory_uri . '/js/national.nomination.js', array( 'jquery' ), '1.0', false );
    
 
    
    $output='';     



        



    $output .= '<div class="client_carousel">';



    $output .= '<div class="client_container">'.do_shortcode( $content ).'</div>';            



                



    $output .= '</div>';



    



    //removing extra <br>



    $Old     = array( '<br />', '<br>' );



    $New     = array( '','' );



    $output = str_replace( $Old, $New, $output );    



    



    return $output;



    



}



add_shortcode(  'newvala_client', 'new_vala_client_sc'  );





/**
* Test Shortcode
*/
add_shortcode( 'new_vala_test', 'new_vala_test_sc' );
function new_vala_test_sc(){
   
     global $__newvalaobj, $post, $paged, $wp_query, $wp_rewrite, $vala_region_list, $region_list, $wpdb, $table_prefix;     
     
     //$site_list = $wpdb->get_results( $wpdb->prepare('SELECT * FROM va_blogs ORDER BY blog_id', '') );
    $rid = $__newvalaobj->getRegionID();
     echo '<pre>'.print_r( $rid, true ).'</pre>';
     echo '<pre>'.print_r( $vala_region_list, true ).'</pre>';
     echo '<pre>'.print_r( $region_list, true ).'</pre>';
     /**
     * Update Profile Post Type
     */
     /*$rid = $__newvalaobj->getRegionID();
     $_nv = new_vala_getallapplicationsbyregion( $rid );
        
        //if( $_POST['update_semi_data'] ){
            $new_vala_settings_saved = 1;
                $_posts = array();
                $_region_id = $rid;

                $nv_semi = $_nv['semifinalists'];
                $results = array();
                foreach( $nv_semi as $semi ){
                    $user = new_vala_getappuserdetails($semi['info']['id']);
                    
                    // Add categories
                    $user['category'] = $semi['apps']; 
                    $user['info'] = $semi['info']; 
                    
                    $results[] = $user;
                    
                    // Push to Post type
                    //$pid = new_vala_update_post($user);
                    //$_posts[]=$pid;
                } 
                
                //$results = $_posts;   
            //}
     
      echo '<pre>'.print_r($nv_semi,true).'</pre>';  
      echo '<pre>'.print_r($results,true).'</pre>';*/  
     /*$region = $__newvalaobj->getRegionID();
     
     $contracts = new_vala_fetch_api_('getcontractsbyregion',$region); 
     echo '<pre>'.print_r($contracts,true).'</pre>';*/
    
    $appRegions = new_vala_getappregions_();
    echo '<pre>'.print_r( $appRegions, true ).'</pre>';
    
     //$data = new_vala_getcategorysponsorlistbyregion_($region);
     //echo '<pre>'.print_r($data,true).'</pre>';  
     
    /*$sponsors = new_vala_getcontractsbyregion($region);
    $catData = new_vala_getcategoriesbyregion($region);
    
    echo '<pre>'.print_r($sponsors,true).'</pre>';  
    echo '<pre>'.print_r($catData,true).'</pre>';  */
     
    /* $postid = 3702;
     $profile_data = get_post_meta( $postid, '_vala_details', true );
     echo '<pre>'.print_r($profile_data,true).'</pre>';  
     return;
     
     // get Current URL
    list( $_uri, $_args ) = explode( "?", $_SERVER['REQUEST_URI'] );
    echo $_uri;
    echo '<br />';
    echo $_args;
    echo '<br />';
    
    $winners_slug = '';
    echo site_url('/winner-circle/winners-2/');
    $_current_page_url = "https://" . $_SERVER['HTTP_HOST'] . $_uri; //str_replace( "winner-circle", "winners-circle", $_uri );
     
     // Set Profile URL
     echo $winnersCircleProfileURL = "https://" . $_SERVER['HTTP_HOST'] . str_replace( "winners-circle", "winner-circle", $_uri );     */
     
     /*$start = microtime(true);
     $semi = new_vala_getsemibyregion($region);  
     echo '<pre>'.print_r(array('API results:',$semi),true).'</pre>';  
     $end = microtime(true); 
     printf("API Call was generated in %f seconds", $end - $start);
     
     echo '<hr />';  
     
     $start = microtime(true);
     $semi = new_vala_fetch_api_cached('applications',$region,'semi');  
     echo '<pre>'.print_r(array('Transient results:',$semi),true).'</pre>';  
     $end = microtime(true);  
     printf("Transient Call was generated in %f seconds", $end - $start);*/ 
     
     
}

/**
* New Styles for Winner Circles
*/
add_shortcode( 'new_vala_finalist_new_template', 'new_vala_finalist_new_template_sc' );
function new_vala_finalist_new_template_sc( $atts, $content ) {
    global $__newvalaobj;
    
    extract( shortcode_atts( array(
        'type' => '',
        'columns' => '',
        'show_all' => '',
        'region' => '',
        'year' => 2015,
        'filter' => '',
        'withbio' => '', 
        'withnameorg' => '',
        'cacheit' => '',
        'dev' => ''
    ), $atts ) );
    
    $nv_region_id = $__newvalaobj->getYearlyRegionIdByYear( $year ); 
    
    /*if( is_numeric( $year ) || $year == 'yes' )
        $nv_region_id = $__newvalaobj->isYearlyIDExists( $year );
    else
        $nv_region_id = $__newvalaobj->getRegionID(); */
    
    $withbio = ( $withbio == 'yes' ) ? true : false;  
    $withnameorg = ( $withnameorg == 'yes' ) ? true : false;  
    $cacheit = ( $cacheit == 'yes' ) ? true : ( is_numeric( $cacheit ) ? (int) $cacheit : false );
    
    // if dev print_r results    
    $dev = empty( $a['dev'] ) ? false : true;
    // get the id of region by year
    //$region_id = isset( $vala_region_list[(int)$year][$region] ) ? (int) $vala_region_list[(int)$year][$region] : 0;
    $region_id = $nv_region_id;
    // Array where data to be stored.
    $data = array(); 
    
    // Get Semi-finalists
    //$data = new_vala_getfinalistbyregion($region_id,false,$cacheit);
    $data = new_vala_getfinalistbyregion_($region_id,false,$cacheit);

    $nv_semi = $data['finalists'];
    $nv_cats = $data['categories'];
    
    //echo '<pre>'.print_r($nv_cats,true).'</pre>';
    
   // Filter by Category 
   $filter = ( $filter == 'true' ? true : false );   

    // print_for_debugging($data);
    // exit;
    $output='';
    ob_clean();
    // Build html
   // $output .= va_region_semi_and_finalist_template(4,$data,$categories,'profile',true, $filter);
   $output .= new_vala_all_nominees_template_updatedStyles(4,$nv_cats,'profile',true, $filter, 'finalApps', $withbio, $withnameorg);
    
    return $output;
}

?>
