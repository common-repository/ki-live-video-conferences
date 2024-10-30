<?php
/**
 * This template shows a meeting.
 *
 * You can use this tamplete in your theme or child theme
 * yourtheme/conference-video-room/shortcode/meeting.php.
 *
 * Please use $atts - here all params of the shortcode
 */
namespace KiLiveVideoConferences;
$token = KiVideoChatRainbow::get_token();
?>
<div class="ki-video-chat-shortcode vc-rainbow">
	<?php
	$post_id    = get_the_ID();
	$start_time = get_post_meta( $post_id, 'start_time', true );
	$timezone   = get_post_meta( $post_id, 'timezone', true );

	$start = true;
	if ( $start_time != '' ) {
		if ( time() < strtotime( $start_time . ' ' . $timezone ) ) {
			$start = false;
		}
	}
	if ( $start ) {
		if ( ! empty($token) ):
			?>
            <iframe allowfullscreen src="<?php echo home_url( '/?conference-video-room-rainbow=' . esc_attr( $atts['meeting_id'] ) ); ?>" width="100%" height="600"></iframe>
            <button type="button" class="rainbow_logout"><?php esc_html_e( 'Logout', 'ki-live-video-conferences' ) ?></button>
		<?php
		else:

			?>
            <div class="RainbowAuth">
                <p><?php esc_html_e( 'Log in please' ) ?></p>
                <div class="">
                    <a href="<?= KiVideoChatRainbow::auth_url() ?>"><?php esc_html_e( 'Join', 'ki-live-video-conferences' ) ?></a>
                </div>

            </div>
		<?php
		endif;
	} else {
		?>
        <div class="RainbowAuth">
            <p><?php esc_html_e( 'The conference has not started yet.', 'ki-live-video-conferences' ) ?></p>


        </div>
		<?php
	}
	?>
</div>

