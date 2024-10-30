<?php
/**
 *
 */
namespace KiLiveVideoConferences;
$post_id   = KiFunctions::v_get( 'conference-video-room' );
$user_name = '';
if ( ! empty( KiFunctions::v_get( 'user_name' ) ) ) {
	$user_name = KiFunctions::v_get( 'user_name' );
}


$ajaxurl = admin_url( 'admin-ajax.php' );

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script>
			var ki_live_video_conferences_ajax = '<?php echo esc_attr( $ajaxurl ); ?>';
    </script>
	<?php wp_head(); ?>

    <script>
			var $ = jQuery;
    </script>
    <style>
        .tab-title {
            color: #fff;
        }
    </style>
</head>
<body>


<?php wp_footer(); ?>

<div class="wp_zoom" style="display: none;"><?php echo esc_attr( $post_id ); ?></div>
<div class="wp_zoom_user_name" style="display: none;"><?php echo esc_attr( $user_name ); ?></div>
</body>
</html>

<?php exit; ?>
