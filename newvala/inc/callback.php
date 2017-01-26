<?php
/**
* Get Result
*/
if( isset($_GET['auth']) ): $vala_id = $_GET['id'];?>

<?php
    if( !session_id())
        session_start();
        
        $_SESSION['vala_id'] = (int) $_GET['id'];
?>

<script type="text/javascript">
    window.returnValue = <?php echo $vala_id?>; 
    window.close();
</script>

<?php
    print_r( json_encode( array('userid'=>$_GET['id']) ) );
?>

<?php endif;?>
