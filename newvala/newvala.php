<?php
/*
Plugin Name: New VALA Plugin
Plugin URI: http://www.eyewebmaster.com/development/new-vala.html
Description: This plugin will use the API to fetch data from the new VALA System.
Author: Jerex Salingujay
Version: 1.0
Author URI: http://www.eyewebmaster.com/
*/

/*  Copyright 2015 Eyewebmaster

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('EWM_NEW_VALA_PLUGIN_BASENAME', basename( dirname( __FILE__ ) ), true);
define('EWM_NEW_VALA_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . EWM_NEW_VALA_PLUGIN_BASENAME . '/'); 
/**
 * @var string plugin url
 */
 define( 'EWM_NEW_VALA_FILE', __FILE__ );
define( 'EWM_NEW_VALA_PLUGIN_URL', plugin_dir_url( EWM_NEW_VALA_FILE ) );

/**
* VALA System API
*/
//$vala_url = 'http://178.62.111.176/';  //Demo Site
$vala_url = 'http://valasystem.com/'; 
//$vala_url = 'http://production.valasystem.com/'; 
//$vala_url = 'https://dev.valasystem.com/'; 
//$vala_url = 'http://localhost/newvala/public/'; 
define( 'EWM_NEW_VALA_URL', $vala_url );
define( 'EWM_NEW_VALA_API_URL', EWM_NEW_VALA_URL.'vala-api/' );


class Ewm_new_vala {
    /**
     * Current version.
     * @TODO Update version number for new releases
     * @var    string
     */
    const CURRENT_VERSION = '1.0';

    /**
     * Translation domain
     * @var string
     */
    const TEXT_DOMAIN = 'ewm';

    /**
     * Options instance.
     * @var object
     */
    private $_data = null;
    
    private $_vala_id = 0;
    
    public function __construct (){
        global $wpdb, $wp_version;
        
        // Activation deactivation hooks
        register_activation_hook(__FILE__, array($this, 'install'));
        register_deactivation_hook(__FILE__, array($this, 'uninstall'));
        
        // Actions
        add_action('init', array($this, 'init'), 99);
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
        
        // Admin scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
        
        
        // Public scripts
        add_action( 'wp_enqueue_scripts', array($this,'newvala_enqueue_scripts') );
    }
    
     /**
     * Initialize the plugin
     *
     * @see        http://codex.wordpress.org/Plugin_API/Action_Reference
     * @see        http://adambrown.info/p/wp_hooks/hook/init
     */
     public function init() {
        global $wpdb, $wp_rewrite, $current_user, $blog_id, $wp_version;
        $version = preg_replace('/-.*$/', '', $wp_version);
        
        // Set VALA ID if exist
        $this->setUserID();
        
        // Fetch New VALA region ID by Name
        $blog_title = get_bloginfo('name');
        //$appRegions = new_vala_getappregions();
        $appRegions = new_vala_getappregions_();
        
        foreach( $appRegions as $appregion ){
            if( preg_match( '/'.strtolower( $blog_title ).'/i', $appregion['custom_name'] ) ){
                $this->_data['new_vala_region_id'] = $appregion['id'];
                $this->_data['new_vala_yearly_region_ids'][ $appregion['yearly_region_id'] ] = $appregion['year'];
                $this->_data['new_vala_region_ids_by_year'][ $appregion['year'] ] = $appregion['yearly_region_id'];
                
                if( $appregion['year'] == date( "Y" ) )
                    $this->_data['new_vala_yearly_region_id'] = $appregion['yearly_region_id']; 
            }
        }
        
        // Fetch User information, if set
        if( $this->_vala_id > 0 ){
            $this->_data['user'] = $this->_getUserInfo( $this->_vala_id );
            $this->_data['user']['votes'] = $this->_getUserVotes( $this->getRegionID(), $this->_vala_id );
        }
        
        // Submit process
        /*if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'ewm_new_vala-update-options')) {
            
            $page = is_network_admin() ? 'settings.php' : 'options-general.php';
            $redirect_url = $page.'?page=ewm_new_vala&new_vala_settings_saved=1';
            
            
            
           wp_redirect($redirect_url);
           exit();         
        }*/
    }
    /**
    * Setters
    * 
    */
    private function setUserID(){
        if( !session_id())
        session_start();
        
        //unset( $_SESSION['vala_id'] );
        
        $this->_vala_id = isset( $_SESSION['vala_id'] ) ? (int) $_SESSION['vala_id'] : 0;
    }
   /**
   * Getters
   *  
   */
    public function getRegionID(){
        return $this->_data['new_vala_region_id'];
        //return 1;
    }
    
    public function getRegionYearlyID(){
        return $this->_data['new_vala_yearly_region_id'];
        //return 1;
    }
    
    public function getRegionYearlyIDs(){
        return $this->_data['new_vala_yearly_region_ids'];
        //return 1;
    }
    
    public function getYearlyRegionIdByYear( $year )
    {
        if( isset( $this->_data['new_vala_region_ids_by_year'][ $year ] ) && $yearlyRegionId = $this->_data['new_vala_region_ids_by_year'][ $year ] )
            return $yearlyRegionId;
        else
            return $this->getRegionID();
    }
    
    public function isYearlyIDExists( $yearlyId )
    {
        if( isset( $this->_data['new_vala_yearly_region_ids'][ $yearlyId ] ) && $yearlyRegionId = $this->_data['new_vala_yearly_region_ids'][ $yearlyId ] )
            return $yearlyId;
        else
            return $this->getRegionYearlyID();
    }
    
    public function getUserInfo(){
        return $this->_data['user'];
    }
    
    public function getUserID(){
        return $this->_vala_id;
    }
    
    private function _getUserInfo($id){
        return new_vala_getuserdetails($id);
    }
    
    private function _getUserVotes($rid,$id){
        return new_vala_getuserpublicvotes($rid.'-'.$id);
    }
    
    public function admin_init() {
        
        // do nothing yet  
    }
    
    public function admin_enqueue_scripts() {
        global $wp_query;
        
         printf(
            '<script type="text/javascript"> var _ewm_new_vala_ajax_url="%s"; var _ewm_new_vala_rid=%d; var _ewm_new_vala_plugin_url="%s"; var _ewm_new_vala_plugin_callback_url_base64encoded="%s"; var _ewm_new_vala_user_id=%d; var _ewm_new_vala_api_url="%s"; var _ewm_new_vala_url="%s";</script>',
             admin_url('admin-ajax.php'),$this->getRegionID(), EWM_NEW_VALA_PLUGIN_URL , base64_encode( EWM_NEW_VALA_PLUGIN_URL.'inc/callback.php' ), $this->_vala_id, EWM_NEW_VALA_API_URL, EWM_NEW_VALA_URL
        );
        
        printf(
            '<script type="text/javascript">var _new_vala_view_votes_url="%s";</script>',
             admin_url('admin.php?page=new_vala_publicvoting')
        );
        
        do_action('ewm_new_vala-load_scripts-public');        
    }
    
    public function newvala_enqueue_scripts(){
        global $wp_query;
        
        printf(
            '<script type="text/javascript"> var _ewm_new_vala_ajax_url="%s"; var _ewm_new_vala_rid=%d; var _ewm_new_vala_plugin_url="%s"; var _ewm_new_vala_plugin_callback_url_base64encoded="%s"; var _ewm_new_vala_user_id=%d; var _ewm_new_vala_api_url="%s"; var _ewm_new_vala_url="%s";</script>',
             admin_url('admin-ajax.php'),$this->getRegionID(), EWM_NEW_VALA_PLUGIN_URL , base64_encode( EWM_NEW_VALA_PLUGIN_URL.'inc/callback.php' ), $this->_vala_id, EWM_NEW_VALA_API_URL, EWM_NEW_VALA_URL
        );
        do_action('ewm_new_vala-load_scripts-public');                
        
        wp_register_style( 'newvala-css', EWM_NEW_VALA_PLUGIN_URL . 'css/newvala.css', array(), self::CURRENT_VERSION );
        wp_register_style( 'newvala-winner-circle-css', EWM_NEW_VALA_PLUGIN_URL . 'css/winner-circle-new-template.css', array(), self::CURRENT_VERSION );
        wp_register_style( 'newvala-winner-circle-finalists-css', EWM_NEW_VALA_PLUGIN_URL . 'css/winner-circle-finalists.css', array(), self::CURRENT_VERSION );
        
        wp_register_script( 'newvala-js', EWM_NEW_VALA_PLUGIN_URL.'js/newvala.js', array( 'jquery' ), self::CURRENT_VERSION);    
        wp_register_script( 'newvala-obj-js', EWM_NEW_VALA_PLUGIN_URL.'js/newvala-obj.js', array( 'jquery' ), self::CURRENT_VERSION);    
        wp_register_script( 'newvala-core-js', EWM_NEW_VALA_PLUGIN_URL.'js/newvala-core.js', array( 'jquery' ), self::CURRENT_VERSION);    
    }
    
    /**
     * Add the admin menus
     *
     * @see        http://codex.wordpress.org/Adding_Administration_Menus
     */
    public function admin_menu() {
        global $submenu, $menu;
        
        //$page = is_network_admin() ? 'settings.php' : 'options-general.php';
        $perms = is_network_admin() ? 'manage_network_options' : 'manage_options';
        
        //$perms = 'manage_options';
        add_menu_page( 'New VALA', 'VALA', $perms, 'new_valasystem', array($this, 'new_vala_admin_page'), '', 83.3 ); 
        add_submenu_page( 'new_valasystem', __('Winners Circle', self::TEXT_DOMAIN), __('Winners Circle', self::TEXT_DOMAIN), $perms, 'new_vala_winners_circle',  array($this,'new_vala_winners_circle_page') ); 
        //add_submenu_page( 'new_valasystem', __('Semi Finalist', self::TEXT_DOMAIN), __('Semi Finalist', self::TEXT_DOMAIN), $perms, 'new_vala_semi',  array($this,'new_vala_semi_page') ); 
        add_submenu_page( 'new_valasystem', __('Public Voting', self::TEXT_DOMAIN), __('Public Voting', self::TEXT_DOMAIN), $perms, 'new_vala_publicvoting', array($this,'new_vala_publicvoting_page')); 
        //add_submenu_page($page, __('New VALA', self::TEXT_DOMAIN), __('New VALA', self::TEXT_DOMAIN), $perms, 'ewm_new_vala', array($this, 'settings_render'));
    
    }
    
    public function install () {
        global $wpdb;
        
        // do nothing
    }
    
    public function uninstall() {
        global $wpdb;
        
        // do nothing
    }
    
    public function settings_render() {
    global $region_list, $vala_region_list;
    
    $page = is_network_admin() ? 'settings.php' : 'options-general.php';
    
    //echo '<pre>'.print_r($region_list,true).'</pre>';
    //echo '<pre>'.print_r($vala_region_list,true).'</pre>';
    
        if(!current_user_can('manage_options')) {
              echo "<p>" . __('Nice Try...', self::TEXT_DOMAIN) . "</p>";  //If accessed properly, this message doesn't appear.
              return;
          }
        if (isset($_GET['new_vala_settings_saved']) && $_GET['new_vala_settings_saved'] == 1) {
            echo '<div class="updated fade"><p>'.__('Settings saved.', self::TEXT_DOMAIN).'</p></div>';
        }
        
         
         
    }
    
    private function vala_clear_all_transient(){
        global $region_list, $vala_region_list, $wpdb, $table_prefix, $blog_id;
        $deleted_cached = array();
        foreach( $region_list as $index => $region ){
            if( $region['blog_id'] == 1 ) continue;
            
            switch_to_blog( $region['blog_id'] );
            $option_table = $table_prefix . "options";  
            $query = "SELECT $option_table.option_name as `name` from $option_table where $option_table.option_name like '_transient_nv_api%'";
            $blog_transients = $wpdb->get_results( $query );
                foreach( $blog_transients as $transient ){
                    $_transient = str_replace( "_transient_", "", $transient->name );
                    delete_transient( $_transient );
                    
                    $deleted_cached[$region['name']][] = $_transient; 
                }
            restore_current_blog();
        }
        
        return $deleted_cached;
    }
    
    public function new_vala_admin_page(){
        global $region_list, $vala_region_list, $wpdb, $table_prefix, $blog_id;
        
        // clear all cache
        if( $_POST['force_update']  ){
            $new_vala_settings_saved = 1;
            $deleted_cached = $this->vala_clear_all_transient();   
        }
        // include page
        require_once EWM_NEW_VALA_PLUGIN_DIR . 'page/admin.html';
    }
    
public function new_vala_semi_page(){
        global $region_list, $vala_region_list;
        
         if(!current_user_can('manage_options')) {
                  echo "<p>" . __('Nice Try...', self::TEXT_DOMAIN) . "</p>";  //If accessed properly, this message doesn't appear.
                  return;
              }
        
        if( $_POST['update_semi_data'] ){
            $new_vala_settings_saved = 1;
                $_posts = array();
                $_region_id = $_POST['region_id'];
                $_nv_semi = new_vala_getsemibyregion($_region_id);
                // get only the semifinalists
                $nv_semi = $_nv_semi['semifinalists'];
                
                foreach( $nv_semi as $semi ){
                    $user = new_vala_getappuserdetails($semi['info']['id']);
                    
                    // Add categories
                    $user['category'] = $semi['apps']; 
                    
                    // Push to Post type
                    $pid = new_vala_update_post($user);
                    $_posts[]=$pid;
                } 
                
                $this->_data['posts'] = $_posts;   
            }
        
           
            if (isset($new_vala_settings_saved) && $new_vala_settings_saved == 1) {
                echo '<div class="updated fade"><p>'.__('Settings saved.', self::TEXT_DOMAIN).'</p></div>';
            }
            
        // include page
        require_once EWM_NEW_VALA_PLUGIN_DIR . 'page/semifinalist.html';
    }

public function new_vala_winners_circle_page(){
        global $region_list, $vala_region_list;
        
         if(!current_user_can('manage_options')) {
                  echo "<p>" . __('Nice Try...', self::TEXT_DOMAIN) . "</p>";  //If accessed properly, this message doesn't appear.
                  return;
              }
        
        $yearly_region_ids = $this->getRegionYearlyIDs();
        
        foreach( $yearly_region_ids as $yearly_id => $year ):
        
            $_nv = new_vala_getallapplicationsbyregion( $yearly_id );
            
            if( $_POST['update_semi_data'] ){
                $new_vala_settings_saved = 1;
                    $_posts = array();
                    $_region_id = $_POST['region_id'];

                    $nv_semi = $_nv['semifinalists'];
                    
                    foreach( $nv_semi as $semi ){
                        $user = new_vala_getappuserdetails($semi['info']['id']);
                        
                        // Add categories
                        $user['category'] = $semi['apps']; 
                        $user['info'] = $semi['info']; 
                        $user['event_year'] = $year;
                        
                        // Push to Post type
                        $pid = new_vala_update_post($user, 'semi', $year);
                        $_posts[]=$pid;
                    } 
                    
                    $this->_data['posts'] = $_posts;   
                }
                
                if( $_POST['update_finalist_data'] ){
                    $new_vala_settings_saved = 1;
                    $_posts = array();
                    $_region_id = $_POST['region_id'];

                    $nv_finalists = $_nv['finalists'];
                    
                    foreach( $nv_finalists as $finalist ){
                        $user = new_vala_getappuserdetails($finalist['info']['id']);
                        
                        // Add categories
                        $user['category'] = $finalist['apps']; 
                        $user['info'] = $finalist['info'];
                        $user['event_year'] = $year;
                        
                        // Push to Post type
                        $pid = new_vala_update_post($user, 'finalist', $year);
                        $_posts[]=$pid;
                    } 
                    
                    $this->_data['posts'] = $_posts;   
                }
                
                if( $_POST['update_winners_data'] ){
                    $new_vala_settings_saved = 1;
                    $_posts = array();
                    $_region_id = $_POST['region_id'];
                    
                    //$nv_winners = $_nv['winners'];
                    // Call from dev.valasystem.com
                    $data = new_vala_getwinnersbyregion( $this->_data['new_vala_region_id'] );
                    $nv_winners = $data['winners'];
                    
                    foreach( $nv_winners as $winner ){
                        $user = new_vala_getappuserdetails($winner['info']['id']);
                        
                        // Add categories
                        $user['category'] = $winner['apps']; 
                        $user['info'] = $winner['info'];
                        $user['event_year'] = $year;
                        
                        // Push to Post type
                        $pid = new_vala_update_post($user, 'winners', $year);
                        $_posts[]=$pid;
                    } 
                    
                    $this->_data['posts'] = $_posts;   
                }
                
            endforeach; // End of foreach Update Profiles.
        
           
            if (isset($new_vala_settings_saved) && $new_vala_settings_saved == 1) {
                echo '<div class="updated fade"><p>'.__('Settings saved.', self::TEXT_DOMAIN).'</p></div>';
            }
        
            
        // include page
        require_once EWM_NEW_VALA_PLUGIN_DIR . 'page/winnerscircle.html';
    }
    
    public function new_vala_publicvoting_page(){
        global $region_list, $vala_region_list;
        
            if(!current_user_can('manage_options')) {
                  echo "<p>" . __('Nice Try...', self::TEXT_DOMAIN) . "</p>";  //If accessed properly, this message doesn't appear.
                  return;
              }
            
            if( $_POST['update_public_voting_data'] ){
                $new_vala_settings_saved = 1;    
            }
              
           if (isset($new_vala_settings_saved) && $new_vala_settings_saved == 1) {
                echo '<div class="updated fade"><p>'.__('Settings saved.', self::TEXT_DOMAIN).'</p></div>';
            }
        
        $votes = new_vala_getvotesbyregion($this->getRegionID());   
        
        if( isset( $_GET['pvid'] ) && $_votes = $votes[$_GET['pvid']]['votes'] ){
            // include page
            require_once EWM_NEW_VALA_PLUGIN_DIR . 'page/publicvotesbyid.html';    
        }else{
            // include page
            require_once EWM_NEW_VALA_PLUGIN_DIR . 'page/publicvoting.html';    
        }
            
        
    }
    
}

/*global $ewm_vala_importer; */

// Get Eventbrite Class
require_once EWM_NEW_VALA_PLUGIN_DIR . 'inc/api_functions.php';
require_once EWM_NEW_VALA_PLUGIN_DIR . 'inc/ajax_functions.php';
require_once EWM_NEW_VALA_PLUGIN_DIR . 'inc/functions.php';
require_once EWM_NEW_VALA_PLUGIN_DIR . 'inc/template_functions.php';
require_once EWM_NEW_VALA_PLUGIN_DIR . 'inc/publicvoting_functions.php';
require_once EWM_NEW_VALA_PLUGIN_DIR . 'inc/shortcodes.php';
require_once EWM_NEW_VALA_PLUGIN_DIR . 'inc/newvala_shortcodes.php';

$__newvalaobj = new Ewm_new_vala();

?>
