<?php
  
/***
* Shortcode Templates
*/
function new_vala_all_nominees_template( $columns, $categories, $type, $show_all, $filter, $app_status = 'apps', $with_bio = false, $with_name_org = true ) {
  $dom_dimensions = zp_portfolio_items_values( $columns );
  
  //echo '<pre>'.print_r($categories,true).'</pre>';
  
  /** inherit from 4-column dimensions **/
  $dom_dimensions['image_width'] = 245;
  $dom_dimensions['image_height'] = 160;
   
  $html = '';
  if ( $categories ) {
    ob_start();

    //$awards = array_keys( $data );
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    wp_enqueue_style( 'nominees-winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/nominees-winners-circle.css", array(), '1.0', 'all' );
    //wp_enqueue_style( 'newvala-winner-circle-css' );
    
    wp_enqueue_script( 'hide_show', get_stylesheet_directory_uri() . '/js/hideshow.js', array(), '1.0.0', true );
    
    if(!$with_bio){
         $va_nobio_css = ".award-title{font-size: 26px;padding: 3em 0;} .nominee{ margin-bottom: 3em; } @media only screen and (min-width: 1000px) {.nominee > div{ min-height: 0; } }";            
         wp_add_inline_style( 'nominees-winners-circle', $va_nobio_css );    
    }
    
    $region_name = get_bloginfo( 'name' );
    $region_primary_color = get_region_skin_primary_color( $region_name );
    
    $awards = $categories;
    
    // get Current URL
    list( $_uri, $_args ) = explode( "?", $_SERVER['REQUEST_URI'] );
    $_current_page_url = "https://" . $_SERVER['HTTP_HOST'] . $_uri; //str_replace( "winner-circle", "winners-circle", $_uri );

    // Set Profile URL
    $winnersCircleProfileURL = "https://" . $_SERVER['HTTP_HOST'] . str_replace( "winners-circle", "winner-circle", $_uri );
    ?>

    <?php
    foreach( $awards as $cat_id => $award ) {
      if ( count($award[$app_status]) > 0 ) {
        ?>
        
        
        
        <div class="award-title">
                <?php echo '<a name='.$award['name'].'>'.$award['name'].'</a>'; ?>
        </div>

             
        <?php
        $index_counter = 0;
        $even_odd_class = 'even';
        $columns_class = '';
        foreach( $award[$app_status] as $app ) {
          
          if( $_post_id = new_vala_isAppidExist( $app['id'] ) ){  
          
              $nominee = new_vala_getappuserdetails( $app['id'] ); 
              $slug = get_post( $_post_id )->post_name; 
              
              //$_post_permalink = get_permalink( $_post_id );
              $_post_permalink = $winnersCircleProfileURL . '?profile=' . $slug . '&cat=' . $cat_id;

              $bio = get_post_field('post_content', $_post_id);
               
              $rank = isset( $nominee['rank'] ) ? $nominee['rank'] : '';
              $div_class = $rank . ' nominee ' . $even_odd_class . ' ' . $columns_class;
              if ( $index_counter > 2 ) {
                $div_class .= " top-border";
              }
              $nomine_image = str_replace("'","%27",$nominee['image']);
              //$image_source = isset( $nominee['image'] ) ? 'https://valasystem.com/images/user/'. $nominee['id'] . '/' . $nomine_image : '';
              $image_source = isset( $nominee['image'] ) ? 
                EWM_NEW_VALA_URL.'images/user/'. $nominee['id'] . '/' . $nomine_image : 
                EWM_NEW_VALA_PLUGIN_URL . 'img/default_profile_img.png';
              //$image_source = isset( $nominee['image'] ) ? 'http://valasystem.com/images/user/'. $nominee['id'] . '/' . $nominee['image'] : '';
              ?>
              
              <div class="<?php echo $div_class; ?>">
                <div>
                  <div class="image">
                    <a href="<?php echo $_post_permalink ?>"><div class="avatar" style="background-image: url('<?php echo $image_source; ?>');"></div></a>
                    <?php if ( $rank == 'winner' || $rank == 'finalist' ) { ?>
                    <div class="label"><?php echo $rank; ?></div>
                    <?php } ?>
                  </div>
                  
                  <div class="text">
                  <?php if ( $with_name_org ) { ?>
                        <h4><a href="<?php echo $_post_permalink ?>"><?php echo $nominee['firstname'] . " " . $nominee['lastname']; ?></a></h4>
                        <h5><?php echo $nominee['org_name']; ?></h5>   
                        <h5><a href="<?php echo $_post_permalink ?>">Read more...</a></h5> 
                    <?php } ?>
                    
                    <?php if ( $with_bio && isset($nominee['biography']) ) { ?>
                     <p><?php echo $bio; ?></p>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <?php
              
              ++$index_counter;
              if ( $even_odd_class == 'even' ) {
               $even_odd_class = 'odd';
              }
              else {
                $even_odd_class = 'even';
              }
              
              if ( ($index_counter % 3) == 0 ) {
                $columns_class = 'cols-3';
              }
              else {
                $columns_class = ''; 
              }
          }
        
        }
        
      }
      
    }

    $html = ob_get_contents();
    ob_end_clean();
  }
  
  return $html;
}

function new_vala_all_nominees_template_( $columns, $categories, $type, $show_all, $filter, $app_status = 'apps',$with_bio = false, $with_name_org = true ) {
  $dom_dimensions = zp_portfolio_items_values( $columns );
  
  //echo '<pre>'.print_r($categories,true).'</pre>';
  
  /** inherit from 4-column dimensions **/
  $dom_dimensions['image_width'] = 245;
  $dom_dimensions['image_height'] = 160;
   
  $html = '';
  if ( $categories ) {
    ob_start();
    
    //$awards = array_keys( $data );
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    //wp_enqueue_style( 'nominees-winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/nominees-winners-circle.css", array(), '1.0', 'all' );
    wp_enqueue_style( 'newvala-winner-circle-css' );
    
    wp_enqueue_script( 'hide_show', get_stylesheet_directory_uri() . '/js/hideshow.js', array(), '1.0.0', true );
    
    
    $region_name = get_bloginfo( 'name' );
    $region_primary_color = get_region_skin_primary_color( $region_name );
    
    $awards = $categories;
    
    // get Current URL
    list( $_uri, $_args ) = explode( "?", $_SERVER['REQUEST_URI'] );
    $_current_page_url = "https://" . $_SERVER['HTTP_HOST'] . $_uri; //str_replace( "winner-circle", "winners-circle", $_uri );

    // Set Profile URL
    $winnersCircleProfileURL = "https://" . $_SERVER['HTTP_HOST'] . str_replace( "winners-circle", "winner-circle", $_uri );
    ?>

    <?php
    foreach( $awards as $cat_id => $award ) {
      if ( count($award[$app_status]) > 0 ) {
        ?>
        
        
        
        <div class="award-title">
                <?php echo '<a name='.$award['name'].'>'.$award['name'].'</a>'; ?>
        </div>

             
        <?php
        $index_counter = 0;
        $even_odd_class = 'even';
        $columns_class = '';
        foreach( $award[$app_status] as $app ) {
          
          if( $_post_id = new_vala_isAppidExist( $app['id'] ) ){  
          
              $nominee = new_vala_getappuserdetails( $app['id'] );  
              
              //$_post_permalink = get_permalink( $_post_id );
              
              $slug = get_post( $_post_id )->post_name; 
              $_post_permalink = $winnersCircleProfileURL . '?profile=' . $slug . '&cat=' . $cat_id;
              
              $bio = get_post_field('post_content', $_post_id);
               
              $rank = isset( $nominee['rank'] ) ? $nominee['rank'] : '';
              $div_class = $rank . ' nominee ' . $even_odd_class . ' ' . $columns_class;
              if ( $index_counter > 2 ) {
                $div_class .= " top-border";
              }
              $nomine_image = str_replace("'","%27",$nominee['image']);
              //$image_source = isset( $nominee['image'] ) ? 'https://valasystem.com/images/user/'. $nominee['id'] . '/' . $nomine_image : '';
              $image_source = isset( $nominee['image'] ) ? 
                EWM_NEW_VALA_URL.'images/user/'. $nominee['id'] . '/' . $nomine_image : 
                EWM_NEW_VALA_PLUGIN_URL . 'img/default_profile_img.png';
              //$image_source = isset( $nominee['image'] ) ? 'http://valasystem.com/images/user/'. $nominee['id'] . '/' . $nominee['image'] : '';
              ?>
              
              <div class="<?php echo $div_class; ?>">
                <div>
                  <div class="image">
                    <a href="<?php echo $_post_permalink ?>"><div class="avatar" style="background-image: url('<?php echo $image_source; ?>');"></div></a>
                    <?php if ( $rank == 'winner' || $rank == 'finalist' ) { ?>
                    <div class="label"><?php echo $rank; ?></div>
                    <?php } ?>
                  </div>
                  
                  <div class="text">
                  <?php if ( $with_name_org ) { ?>
                        <h4><a href="<?php echo $_post_permalink ?>"><?php echo $nominee['firstname'] . " " . $nominee['lastname']; ?></a></h4>
                        <h5><?php echo $nominee['org_name']; ?></h5>   
                        <h5><a href="<?php echo $_post_permalink ?>">Read more...</a></h5> 
                    <?php } ?>
                    
                    <?php if ( $with_bio && isset($nominee['biography']) ) { ?>
                     <p><?php echo $bio; ?></p>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <?php
              
              ++$index_counter;
              if ( $even_odd_class == 'even' ) {
               $even_odd_class = 'odd';
              }
              else {
                $even_odd_class = 'even';
              }
              
              if ( ($index_counter % 3) == 0 ) {
                $columns_class = 'cols-3';
              }
              else {
                $columns_class = ''; 
              }
          }
        
        }
        
      }
      
    }

    $html = ob_get_contents();
    ob_end_clean();
  }
  
  return $html;
}

function new_vala_semi_public_voting_template( $columns, $categories, $type, $show_all, $filter ) {
  $dom_dimensions = zp_portfolio_items_values( $columns );
  
  //echo '<pre>'.print_r($categories,true).'</pre>';
  
  /** inherit from 4-column dimensions **/
  $dom_dimensions['image_width'] = 245;
  $dom_dimensions['image_height'] = 160;
   
  $html = '';
  if ( $categories['pub_cats'] ) {
    ob_start();
    
    $_cats = array();
    $_cats = $categories['cats'];
    
    //$awards = array_keys( $data );
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    wp_enqueue_style( 'nominees-winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/nominees-winners-circle.css", array(), '1.0', 'all' );
    
    wp_enqueue_script( 'hide_show', get_stylesheet_directory_uri() . '/js/hideshow.js', array(), '1.0.0', true );
    
    
    $region_name = get_bloginfo( 'name' );
    $region_primary_color = get_region_skin_primary_color( $region_name );
    
    $awards = $categories['pub_cats'];
    ?>

    <?php
    foreach( $awards as $cat_id => $award ) {
        if( !isset( $_cats[$award['id']] ) ) continue;
        
      $apps = $_cats[$award['id']]['apps'];
      if ( count($apps) > 0 ) {
        ?>
        
        
        
        <div class="award-title">
                <?php echo '<a name='.$award['name'].'>'.$award['name'].'</a>'; ?>
        </div>

             
        <?php
        //echo '<pre>'.print_r($apps,true).'</pre>';
        $index_counter = 0;
        $even_odd_class = 'even';
        $columns_class = '';
        foreach( $apps as $app ) {
          
          if( $_post_id = new_vala_isAppidExist( $app['id'] ) ){  
              
              $nominee = new_vala_getappuserdetails( $app['id'] );  
              //echo '<pre>'.print_r($nominee,true).'</pre>';
              
              $_post_permalink = get_permalink( $_post_id );
               
              $rank = isset( $nominee['rank'] ) ? $nominee['rank'] : '';
              $div_class = $rank . ' nominee ' . $even_odd_class . ' ' . $columns_class;
              if ( $index_counter > 2 ) {
                $div_class .= " top-border";
              }
              
              $image_source = isset( $nominee['image'] ) ? 
                EWM_NEW_VALA_URL.'images/user/'. $nominee['id'] . '/' . $nominee['image'] : 
                EWM_NEW_VALA_PLUGIN_URL . 'img/default_profile_img.png';
              ?>
              
              <div class="<?php echo $div_class; ?>">
                <div>
                  <div class="image">
                    <div class="avatar" style="background-image: url('<?php echo $image_source; ?>');"></div>
                    <?php if ( $rank == 'winner' || $rank == 'finalist' ) { ?>
                    <div class="label"><?php echo $rank; ?></div>
                    <?php } ?>
                  </div>
                  
                  <div class="text">
                    <h4><a href="<?php echo $_post_permalink ?>"><?php echo $nominee['firstname'] . " " . $nominee['lastname']; ?></a></h4>
                    <h5><?php echo $nominee['org_name']; ?></h5>
                    
                    <?php if ( isset($nominee['biography']) ) { ?>
                    <p><?php echo $nominee['biography']; ?></p>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <?php
              
              ++$index_counter;
              if ( $even_odd_class == 'even' ) {
               $even_odd_class = 'odd';
              }
              else {
                $even_odd_class = 'even';
              }
              
              if ( ($index_counter % 3) == 0 ) {
                $columns_class = 'cols-3';
              }
              else {
                $columns_class = ''; 
              }
          }
        
        }
        
      }
      
    }

    $html = ob_get_contents();
    ob_end_clean();
  }
  
  return $html;
}

function new_vala_sponsors_shortcode( $__vs, $columns, $type ){
    global $post, $paged, $wp_query;    
    /** get appropriate columns, image height and image width*/
    $_values = zp_portfolio_items_values( $columns );
    /** determines if it will be a portfolio layout or gallery layout*/
    $class = ( $type == 'portfolio' ) ? 'sponsor-element' : 'gallery';
    //$ext_class = ( $type == 'portfolio' ) ? 'ext-sponsor-height' : '';
    $_vs = $__vs['sponsors'];
    $cats = $__vs['categories'];
    
    $html='';
    $html .='<div id="container-" class="filter_container" style="height: auto; width: 100%;"> ';
    ?>    

    

    <?php
         foreach( $_vs as $s ){
             $category = null;
             foreach( $cats as $cat ){
                 if( $cat['contract_id'] === $s['id'] ){
                        $category = $cat; break;
                 }
                      
             }   
             
            $logo = ""; 
            $logo = $s['org_logo'];     
            $isLogoValid = true;
            if( !isset( $s['org_logo'] ) || empty( $s['org_logo'] ) ) $isLogoValid = false;
    
            $_allowed = array('jpg','jpeg','png','gif');
            
            $_logo = explode( '.', $s['org_logo'] ); 
            $_format = strtolower( $_logo[sizeof($_logo)-1] );
            if( !in_array( $_format, $_allowed) ) $isLogoValid = false;
            /*
            echo '-=-=-=-=-=-=- $s -=-=-=-=-=-=-';
            echo '<pre>' , print_r($s) , '</pre>';
            */
            $sp = trim( $s['org_name'] );
            if( empty( $sp ) ) continue;
            
            $t=$s->sponsor;
            $permalink='#';
            $link = isset($s['org_website']) ? $s['org_website'] : '';
            $twitter = isset($s['org_twitter']) ? $s['org_twitter'] : '';
            $linkedin = isset($s['org_linkedin']) ? $s['org_linkedin'] : '';
            $fb = isset($s['org_facebook']) ? $s['org_facebook'] : '';
            // $blurb = $s->blurb;    
            $blurb = $s['org_desc'];
            //$blurb = '';    

            //$cat = isset($s['category'])?$s['category']:'';
            $cat_name = isset($category)?$category['name']:'';
            // if not Category Sponsor
            if( $s['type'] != 'Category Sponsor' && empty( $cat_name ) ) $cat_name = $s['type'];
            
            //$thumbnail = $s->logo; 
            $logo = EWM_NEW_VALA_URL.'images/sponsor/'. $s['organization_id'] . '/' . $s['org_logo']; 
            $thumbnail = '';
            if( $isLogoValid )
                $thumbnail = get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$logo.'&amp;w='.$_values['width'].'&amp;h='.$_values['height'].'&amp;q=90&amp;zc=2';
            
            $sc_output = do_shortcode('[accordionitem title="'.$cat_name.'"]'.$blurb.'[/accordionitem]');
            
            $openLink='<div class="portfolio_image">';

            $closeLink='</div>';
            
            
            /** Social Links*/
            // $social='<div class="team_socials">' . "$link". "$twitter" . "$linkedin" . "$fb" . '</div>';
            $social = '<ul class="team_socials">';
            
            if ( $link ) {
              $social .= '<li><a class="t_website hastip" href="' . $link . '" target="_blank"></a>';
            }
            
            if ( $fb ) {
              $social .= '<li><a class="t_facebook hastip" href="' . $fb . '" target="_blank"></a>';
            }
            
            if ( $twitter ) {
              $social .= '<li><a class="t_twitter hastip" href="' . $twitter . '" target="_blank"></a>';
            }
            
            if ( $linkedin ) {
              $social .= '<li><a class="t_linkedin hastip" href="' . $linkedin . '" target="_blank"></a>';
            }

            $social .= '</ul>';
            /** End Social Links **/

            //$span_desc='<div class="item-desc"><a class="item_link" href="'.$permalink.'"></a></div><div class="item_label sponsor-content"><h4>'.$t.' Sponsoring </h4><p class="sponsor-content-text">'.$sc_output.'</p></div>';    
            //$span_desc='<div class="item-desc" style="display:none;"><a class="item_link" href="'.$permalink.'"></a></div><div class="item_label sponsor-content"><h4>'.$t.' Sponsoring </h4><div class="social">'.$social.'</div><p class="sponsor-content-text"><h4>'.$s->category.'</h4>'.$blurb.'</p></div>';    
            $span_desc='<div class="item-desc" style="display:none;"><a class="item_link" href="'.$permalink.'"></a></div><div class="item_label sponsor-content"><h4>'.$t.'  </h4><div class="social">'.$social.'</div><p class="sponsor-content-text"><h4>'.$cat_name.'</h4>'.$blurb.'</p></div>';    
          
          /** generate the final item HTML */
            
           $html.= '<div class="sponsor-element '.$class.''.$_values['class'].' '.$ext_class.'" >'.$openLink.'<img src="'.$thumbnail.'" alt="'.$t.'" />'.$closeLink.''.$span_desc.'</div>';
            
           
        }       
            return $html.'</div>  ';
            //.'<script type="text/javascript">jQuery.noConflict();jQuery(".sponsor-content-text").shorten({"showChars" : 50,"moreText"  : "Read More","lessText"  : "Less",});</script>';    
        ?>
       
<?php } 

function new_vala_sponsors_shortcode_( $catSponsors, $columns, $type ){
    global $post, $paged, $wp_query;    
    /** get appropriate columns, image height and image width*/
    $_values = zp_portfolio_items_values( $columns );
    /** determines if it will be a portfolio layout or gallery layout*/
    $class = ( $type == 'portfolio' ) ? 'sponsor-element' : 'gallery';

    $html='';
    $html .='<div id="container-" class="filter_container" style="height: auto; width: 100%;"> ';
    foreach( $catSponsors as $catSponsor ){
        // Set to empty Array
        $s = array();
        $category = array();
                
        $s = $catSponsor['sponsor']; 
        $category = $catSponsor['category'];

            $logo = ""; 
            $logo = $s['org_logo'];     
            $isLogoValid = true;
            if( !isset( $s['org_logo'] ) || empty( $s['org_logo'] ) ) $isLogoValid = false;
    
            $_allowed = array('jpg','jpeg','png','gif');
            
            $_logo = explode( '.', $s['org_logo'] ); 
            $_format = strtolower( $_logo[sizeof($_logo)-1] );
            if( !in_array( $_format, $_allowed) ) $isLogoValid = false;

            $sp = trim( $s['org_name'] );
            if( empty( $sp ) || !$isLogoValid ) continue;
            
            $t=$s->sponsor;
            $permalink='#';
            $link = isset($s['org_website']) ? $s['org_website'] : '';
            $twitter = isset($s['org_twitter']) ? $s['org_twitter'] : '';
            $linkedin = isset($s['org_linkedin']) ? $s['org_linkedin'] : '';
            $fb = isset($s['org_facebook']) ? $s['org_facebook'] : '';
            // $blurb = $s->blurb;    
            $blurb = $s['org_desc'];

            //$cat = isset($s['category'])?$s['category']:'';
            $cat_name = isset($category)?$category['name']:'';
            
            // if not Category Sponsor
            if( $s['type'] != 'Category Sponsor' /*&& empty( $cat_name )*/ ) $cat_name = $s['type'];
            
            //$thumbnail = $s->logo; 
            $logo = EWM_NEW_VALA_URL.'images/sponsor/'. $s['organization_id'] . '/' . $s['org_logo']; 
            $thumbnail = '';
            if( $isLogoValid )
                $thumbnail = get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$logo.'&amp;w='.$_values['width'].'&amp;h='.$_values['height'].'&amp;q=90&amp;zc=2';
            
            $sc_output = do_shortcode('[accordionitem title="'.$cat_name.'"]'.$blurb.'[/accordionitem]');
            
            $openLink='<div class="portfolio_image">';

            $closeLink='</div>';

            /** Social Links*/
            // $social='<div class="team_socials">' . "$link". "$twitter" . "$linkedin" . "$fb" . '</div>';
            $social = '<ul class="team_socials">';
            
            if ( $link ) {
              $social .= '<li><a class="t_website hastip" href="' . $link . '" target="_blank"></a>';
            }
            
            if ( $fb ) {
              $social .= '<li><a class="t_facebook hastip" href="' . $fb . '" target="_blank"></a>';
            }
            
            if ( $twitter ) {
              $social .= '<li><a class="t_twitter hastip" href="' . $twitter . '" target="_blank"></a>';
            }
            
            if ( $linkedin ) {
              $social .= '<li><a class="t_linkedin hastip" href="' . $linkedin . '" target="_blank"></a>';
            }

            $social .= '</ul>';
            /** End Social Links **/

            $span_desc='<div class="item-desc" style="display:none;"><a class="item_link" href="'.$permalink.'"></a></div><div class="item_label sponsor-content"><h4>'.$t.'  </h4><div class="social">'.$social.'</div><p class="sponsor-content-text"><h4>'.$cat_name.'</h4>'.$blurb.'</p></div>';    
          
            /** generate the final item HTML */ 
           $html.= '<div class="sponsor-element '.$class.''.$_values['class'].' '.$ext_class.'" >'.$openLink.'<img src="'.$thumbnail.'" alt="'.$t.'" />'.$closeLink.''.$span_desc.'</div>';    
    } 
         
    return $html.'</div>  ';
} 
function new_vala_sponsors_shortcode_dev( $catSponsors, $columns, $type ){
    global $post, $paged, $wp_query;    
    /** get appropriate columns, image height and image width*/
    $_values = zp_portfolio_items_values( $columns );
    /** determines if it will be a portfolio layout or gallery layout*/
    $class = ( $type == 'portfolio' ) ? 'sponsor-element' : 'gallery';

    $html='';
    $html .='<div id="container-" class="filter_container" style="height: auto; width: 100%;"> ';
    foreach( $catSponsors as $catSponsor ){
        // Set to empty Array
        $s = array();
        $category = array();
                
        $s = $catSponsor['sponsor']; 
        $category = $catSponsor['category'];

            $logo = ""; 
            $logo = $s['org_logo'];     
            $isLogoValid = true;
            if( !isset( $s['org_logo'] ) || empty( $s['org_logo'] ) ) $isLogoValid = false;
    
            $_allowed = array('jpg','jpeg','png','gif');
            
            $_logo = explode( '.', $s['org_logo'] ); 
            $_format = strtolower( $_logo[sizeof($_logo)-1] );
            if( !in_array( $_format, $_allowed) ) $isLogoValid = false;

            $sp = trim( $s['org_name'] );
            if( empty( $sp ) || !$isLogoValid ) continue;
            
            $t=$s->sponsor;
            $permalink='#';
            $link = isset($s['org_website']) ? $s['org_website'] : '';
            $twitter = isset($s['org_twitter']) ? $s['org_twitter'] : '';
            $linkedin = isset($s['org_linkedin']) ? $s['org_linkedin'] : '';
            $fb = isset($s['org_facebook']) ? $s['org_facebook'] : '';
            // $blurb = $s->blurb;    
            $blurb = $s['org_desc'];

            //$cat = isset($s['category'])?$s['category']:'';
            $cat_name = isset($category)?$category['name']:'';
            
            // if not Category Sponsor
            if( $s['type'] != 'Category Sponsor' && empty( $cat_name ) ) $cat_name = $s['type'];
            
            //$thumbnail = $s->logo; 
            $logo = 'https://dev.valasystem.com/images/sponsor/'. $s['organization_id'] . '/' . $s['org_logo']; 
            $thumbnail = '';
            if( $isLogoValid )
                $thumbnail = get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$logo.'&amp;w='.$_values['width'].'&amp;h='.$_values['height'].'&amp;q=90&amp;zc=2';
            
            $sc_output = do_shortcode('[accordionitem title="'.$cat_name.'"]'.$blurb.'[/accordionitem]');
            
            $openLink='<div class="portfolio_image">';

            $closeLink='</div>';

            /** Social Links*/
            // $social='<div class="team_socials">' . "$link". "$twitter" . "$linkedin" . "$fb" . '</div>';
            $social = '<ul class="team_socials">';
            
            if ( $link ) {
              $social .= '<li><a class="t_website hastip" href="' . $link . '" target="_blank"></a>';
            }
            
            if ( $fb ) {
              $social .= '<li><a class="t_facebook hastip" href="' . $fb . '" target="_blank"></a>';
            }
            
            if ( $twitter ) {
              $social .= '<li><a class="t_twitter hastip" href="' . $twitter . '" target="_blank"></a>';
            }
            
            if ( $linkedin ) {
              $social .= '<li><a class="t_linkedin hastip" href="' . $linkedin . '" target="_blank"></a>';
            }

            $social .= '</ul>';
            /** End Social Links **/

            $span_desc='<div class="item-desc" style="display:none;"><a class="item_link" href="'.$permalink.'"></a></div><div class="item_label sponsor-content"><h4>'.$t.'  </h4><div class="social">'.$social.'</div><p class="sponsor-content-text"><h4>'.$cat_name.'</h4>'.$blurb.'</p></div>';    
          
            /** generate the final item HTML */ 
           $html.= '<div class="sponsor-element '.$class.''.$_values['class'].' '.$ext_class.'" >'.$openLink.'<img src="'.$thumbnail.'" alt="'.$t.'" />'.$closeLink.''.$span_desc.'</div>';    
    } 
         
    return $html.'</div>  ';
} 

/**
* Winner Circle template new Styles...
* 
* @param mixed $columns
* @param mixed $categories
* @param mixed $type
* @param mixed $show_all
* @param mixed $filter
* @param mixed $app_status
* @return string
*/
function new_vala_winner_circle_template( $columns, $categories, $type, $show_all, $filter, $app_status = 'apps' ) {
  $dom_dimensions = zp_portfolio_items_values( $columns );
  
  /** inherit from 4-column dimensions **/
  $dom_dimensions['image_width'] = 245;
  $dom_dimensions['image_height'] = 160;
   
  $html = '';
  if ( $categories ) {
    ob_start();
    
    //$awards = array_keys( $data );
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    //wp_enqueue_style( 'nominees-winners-circle', $stylesheet_directory_uri . "/css/shortcode-templates/nominees-winners-circle.css", array(), '1.0', 'all' );
    wp_enqueue_style( 'newvala-winner-circle-css' );
    
    wp_enqueue_script( 'hide_show', get_stylesheet_directory_uri() . '/js/hideshow.js', array(), '1.0.0', true );
    
    
    $region_name = get_bloginfo( 'name' );
    $region_primary_color = get_region_skin_primary_color( $region_name );
    
    $awards = $categories;
    ?>

    <?php
    foreach( $awards as $cat_id => $award ) {
      if ( count($award[$app_status]) > 0 ) {
        ?>
        

        <div class="award-title">
                <?php echo '<a name='.$award['name'].'>'.$award['name'].'</a>'; ?>
        </div>

             
        <?php
        $index_counter = 0;
        $even_odd_class = 'even';
        $columns_class = '';
        foreach( $award[$app_status] as $app ) {
          
          if( $_post_id = new_vala_isAppidExist( $app['id'] ) ){  
          
              $nominee = new_vala_getappuserdetails( $app['id'] );  
              
              $_post_permalink = get_permalink( $_post_id );
              
              $bio = get_post_field('post_content', $_post_id);
               
              $rank = isset( $nominee['rank'] ) ? $nominee['rank'] : '';
              $div_class = $rank . ' nominee ' . $even_odd_class . ' ' . $columns_class;
              if ( $index_counter > 2 ) {
                $div_class .= " top-border";
              }
              
              $image_source = isset( $nominee['image'] ) ? 'http://valasystem.com/images/user/'. $nominee['id'] . '/' . $nominee['image'] : '';
              $thumbnail = get_stylesheet_directory_uri().'/include/scripts/timthumb.php?src='.$image_source.'&amp;w=250&amp;q=90&amp;zc=2';
              ?>
              
              <div class="<?php echo $div_class; ?>">
                <div>
                  <div class="image">
                        <img src="<?php echo $thumbnail; ?>" alt="winner-thumb-<?php echo $app['id'];?>" class="img-circle" style="width: 140px; height: 140px;">
                        <?php if ( $rank == 'winner' || $rank == 'finalist' ) { ?><div class="label"><?php echo $rank; ?></div><?php } ?>
                  </div>
                  
                  <div class="text">
                    <h4><a href="<?php echo $_post_permalink ?>"><?php echo $nominee['firstname'] . " " . $nominee['lastname']; ?></a></h4>
                    <h5><?php echo $nominee['org_name']; ?></h5>
                    
                    <?php if ( isset($nominee['biography']) ) { ?>
                    <p><?php echo $bio; ?></p>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <?php
              
              ++$index_counter;
              if ( $even_odd_class == 'even' ) {
               $even_odd_class = 'odd';
              }
              else {
                $even_odd_class = 'even';
              }
              
              if ( ($index_counter % 3) == 0 ) {
                $columns_class = 'cols-3';
              }
              else {
                $columns_class = ''; 
              }
          }
        
        }
        
      }
      
    }

    $html = ob_get_contents();
    ob_end_clean();
  }
  
  return $html;
}

function new_vala_category_sponsors_shortcode( $__vs, $title ){
    global $post, $paged, $wp_query;    

    $_vs = $__vs['sponsors'];
    $cats = $__vs['categories'];
    
    $html='';
    
    //$html .= '[hr][page_title]'; 
    
    $html .= '<hr><h1 id="page-title-v2">';
    $html .= $title .'</h1>';
    
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
            
            $link = !empty($s['org_website']) ? $s['org_website'] : ( !empty($s['org_facebook']) ? $s['org_facebook'] : '');
            $cat_name = isset($category)?$category['name']:'';
            $cat_tagline = isset($category)?$category['default_tagline']:'';                                   
           
            $html .= '<p><strong><a href="'.$link.'" target="_blank">'. $s['org_name'] .'</a> '. $cat_name .' - </strong>' . $cat_tagline . '</p>';  
            
           
        }       
            
return do_shortcode($html);
} 

function new_vala_category_sponsors_shortcode_( $catSponsors, $title ){
    global $post, $paged, $wp_query;    
    
    $html='';
    
    $html .= '<hr><h1 id="page-title-v2">';
    $html .= $title .'</h1>';
    
    foreach( $catSponsors as $catSponsor ){
        // Set to empty Array
        $s = array();
        $category = array();
        
        $s = $catSponsor['sponsor']; 
        $category = $catSponsor['category'];
        
        // Set inner html
        $inner_html = "";
        
        if( isset( $s['id'] ) ){
            $link = !empty($s['org_website']) ? $s['org_website'] : ( !empty($s['org_facebook']) ? $s['org_facebook'] : '');      
            $inner_html .= '<strong><a href="'.$link.'" target="_blank">'. $s['org_name'] .'</a></strong> ';  
        }

        $cat_name = isset($category)?$category['name']:'';
        $cat_tagline = isset($category)?$category['default_tagline']:'';                                   
        $inner_html .= '<strong>' . $cat_name . ' - </strong>' . $cat_tagline;
        
        $html .= '<p>' . $inner_html . '</p>';    
    }
return $html;
} 


function new_vala_all_nominees_template_updatedStyles( $columns, $categories, $type, $show_all, $filter, $app_status = 'apps', $with_bio = false, $with_name_org = true ) {
  $dom_dimensions = zp_portfolio_items_values( $columns );
  
  //echo '<pre>'.print_r($categories,true).'</pre>';
  
  /** inherit from 4-column dimensions **/
  $dom_dimensions['image_width'] = 245;
  $dom_dimensions['image_height'] = 160;
   
  $html = '';
  if ( $categories ) {
    ob_start();

    //$awards = array_keys( $data );
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    wp_enqueue_style( 'newvala-winner-circle-finalists-css' );
    
    wp_enqueue_script( 'hide_show', get_stylesheet_directory_uri() . '/js/hideshow.js', array(), '1.0.0', true );
    
    if(!$with_bio){
         $va_nobio_css = ".award-title{font-size: 26px;padding: 3em 0;} .nominee{ margin-bottom: 3em; } @media only screen and (min-width: 1000px) {.nominee > div{ min-height: 0; } }";            
         wp_add_inline_style( 'nominees-winners-circle', $va_nobio_css );    
    }
    
    $region_name = get_bloginfo( 'name' );
    $region_primary_color = get_region_skin_primary_color( $region_name );
    
    $awards = $categories;
    
    // get Current URL
    list( $_uri, $_args ) = explode( "?", $_SERVER['REQUEST_URI'] );
    $_current_page_url = "https://" . $_SERVER['HTTP_HOST'] . $_uri; //str_replace( "winner-circle", "winners-circle", $_uri );

    // Set Profile URL
    $winnersCircleProfileURL = "https://" . $_SERVER['HTTP_HOST'] . str_replace( "winners-circle", "winner-circle", $_uri );
    ?>

    <?php
    foreach( $awards as $cat_id => $award ) {
      if ( count($award[$app_status]) > 0 ) {
        ?>
        
        
        
        <div class="award-title">
                <?php echo '<a name='.$award['name'].'>'.$award['name'].'</a>'; ?>
        </div>

             
        <?php
        $index_counter = 0;
        $even_odd_class = 'even';
        $columns_class = '';
        foreach( $award[$app_status] as $app ) {
          
          if( $_post_id = new_vala_isAppidExist( $app['id'] ) ){  
          
              $nominee = new_vala_getappuserdetails( $app['id'] ); 
              $slug = get_post( $_post_id )->post_name; 
              
              //$_post_permalink = get_permalink( $_post_id );
              $_post_permalink = $winnersCircleProfileURL . '?profile=' . $slug . '&cat=' . $cat_id;

              $bio = get_post_field('post_content', $_post_id);
               
              $rank = isset( $nominee['rank'] ) ? $nominee['rank'] : '';
              $div_class = $rank . ' nominee ' . $even_odd_class . ' ' . $columns_class;
              if ( $index_counter > 2 ) {
                $div_class .= " top-border";
              }
              $nomine_image = str_replace("'","%27",$nominee['image']);
              //$image_source = isset( $nominee['image'] ) ? 'https://valasystem.com/images/user/'. $nominee['id'] . '/' . $nomine_image : '';
              $image_source = isset( $nominee['image'] ) ? EWM_NEW_VALA_URL.'images/user/'. $nominee['id'] . '/' . $nomine_image : '';
              //$image_source = isset( $nominee['image'] ) ? 'http://valasystem.com/images/user/'. $nominee['id'] . '/' . $nominee['image'] : '';
              ?>
              
              <div class="<?php echo $div_class; ?>">
                <div>
                  <div class="image">
                    <a href="<?php echo $_post_permalink ?>"><div class="avatar" style="background-image: url('<?php echo $image_source; ?>');"></div></a>
                    <?php if ( $rank == 'winner' || $rank == 'finalist' ) { ?>
                    <div class="label"><?php echo $rank; ?></div>
                    <?php } ?>
                  </div>
                  
                  <div class="text">
                  <?php if ( $with_name_org ) { ?>
                        <h4><a href="<?php echo $_post_permalink ?>"><?php echo $nominee['firstname'] . " " . $nominee['lastname']; ?></a></h4>
                        <h5><?php echo $nominee['org_name']; ?></h5>   
                        <h5><a href="<?php echo $_post_permalink ?>">Read more...</a></h5> 
                    <?php } ?>
                    
                    <?php if ( $with_bio && isset($nominee['biography']) ) { ?>
                     <p><?php echo $bio; ?></p>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <?php
              
              ++$index_counter;
              if ( $even_odd_class == 'even' ) {
               $even_odd_class = 'odd';
              }
              else {
                $even_odd_class = 'even';
              }
              
              if ( ($index_counter % 3) == 0 ) {
                $columns_class = 'cols-3';
              }
              else {
                $columns_class = ''; 
              }
          }
        
        }
        
      }
      
    }

    $html = ob_get_contents();
    ob_end_clean();
  }
  
  return $html;
}
?>
