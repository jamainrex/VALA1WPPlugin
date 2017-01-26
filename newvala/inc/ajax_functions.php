<?php
add_action( 'wp_ajax_new_vala_isuserlogged', 'new_vala_ajx_isuserlogged' );
add_action( 'wp_ajax_nopriv_new_vala_isuserlogged', 'new_vala_ajx_isuserlogged' );

function new_vala_ajx_isuserlogged() {
    if( !session_id())
        session_start();

    $user_id = isset( $_SESSION['vala_id'] ) ? (int) $_SESSION['vala_id'] : 0;
    print_r( json_encode( array( 'userid'=>$user_id ) ) );
    die(); 
    /*$user = new_vala_isUserLogged_new('user/islogged');
    print_r( json_encode( $user ) );
    die();*/
    //var_dump($user);
}

add_action( 'wp_ajax_new_vala_getuser', 'new_vala_ajx_getuser' );
add_action( 'wp_ajax_nopriv_new_vala_getuser', 'new_vala_ajx_getuser' );

function new_vala_ajx_getuser() {
    $id = $_REQUEST['userid'];
    $rid = $_REQUEST['rid'];
    $user = new_vala_getuserdetails($id);
    $votes = new_vala_getuserpublicvotes($rid.'-'.$id);
    print_r( json_encode( array( 'info'=>$user, 'votes'=>$votes) ) );
    die();
}

add_action( 'wp_ajax_new_vala_publicvote', 'new_vala_ajx_publicvote' );
add_action( 'wp_ajax_nopriv_new_vala_publicvote', 'new_vala_ajx_publicvote' );

function new_vala_ajx_publicvote() {
    $userid = $_REQUEST['userid'];
    /*$appid = $_REQUEST['data']['appid'];
    $catid = $_REQUEST['data']['catid'];
    $appcatid = $_REQUEST['data']['appcatid'];*/
    $pvote_val = $_REQUEST['pvote'];
    $vote = new_vala_putpublicvote($pvote_val.'-'.$userid);
    print_r( json_encode( $vote ) );
    die();
}
?>
