<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
$um_forms=$this->get_um_forms();
?>
<div class="ki-live-video-conferences">
	<h3 ><?php esc_html_e( 'Your new meeting request', 'ki-live-video-conferences' );?></h3>
	<div class="ki-row">
		<a href="/account/meetings/" class="ki-but">&#9668; <?php esc_html_e( 'Back to the profile', 'ki-live-video-conferences' );?></a>
	</div>
		
	<div class="ki-row">

		<div class="form-make_appointment" >
			<?php echo do_shortcode($um_forms['shortcod_create_meeting_reques']);?>
	
		</div>
	</div>
</div>



