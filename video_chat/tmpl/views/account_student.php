<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$um_forms=$this->get_um_forms();
?>
<div class="ki-live-video-conferences">
	
	
	<!--<div class="form-make_appointment" style="display: none;">
			<?php echo do_shortcode($um_forms['shortcod_appointment']);?>

	</div>-->
	
				
		<div class="doctor-list-meetings">
	<?php 
	$params=Array(
		'numberposts' => 100,
		'category'    => 0,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'include'     => array(),
		'exclude'     => array(),
		'meta_key'    => '',
		'meta_value'  =>'',
		'post_type'   => 'zoom_video',
		'suppress_filters' => true
	);
	$meetings = get_posts($params);
	
	if(count($meetings)==0){
		?>
		<div class="ki-row">
		<center><?php esc_html_e( "You don't have meetings yet", 'ki-live-video-conferences' );?></center>
		</div
		<div class="ki-row">
			<center><a href="/account/?ki_vc_view=meeting_request" class="ki-but"><?php esc_html_e( 'Create a meeting request', 'ki-live-video-conferences' );?></a></center>
		</div>
		<?php
	}else{
		?>
		<div class="ki-row">
			<center><a href="/account/?ki_vc_view=meeting_request" class="ki-but"><?php esc_html_e( 'Create a meeting request', 'ki-live-video-conferences' );?></a></center>
		</div>
		<?php
		foreach($meetings as $meeting){
			$post_id=$meeting->ID;
			
			$RoomDoctors=get_post_meta( $meeting->ID,'students', true );
			
			$arrRoomDoctors=unserialize($RoomDoctors);
			if(is_array($arrRoomDoctors)==false)continue;

			if(array_search(get_current_user_id(),$arrRoomDoctors)===false)continue;
			?>
			<div class="meeting-row">
				<div class="meeting-name"><?php echo $meeting->post_title?></div>
				<div class="room-buttons">
					<a href="<?php the_permalink( $post_id ); ?>" target="_blank" >Show</a>
				</div>
			</div>
			<?php
		}
		?>
		
		<?php
		
	}
	
	?>
	</div>
</div>

<style>
.um-account{
	/*display: none;*/
}
</style>

