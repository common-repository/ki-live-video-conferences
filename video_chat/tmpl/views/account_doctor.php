<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
<div class="ki-live-video-conferences">

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
	
	if(count($meetings)>0){
		?>
		<div class="ki-row">
			<center><a href="/account/?ki_vc_room=add_room&doctor=<?php echo get_current_user_id();?>" class="ki-but"><?php esc_html_e( 'Create a meeting request', 'ki-live-video-conferences' );?></a></center>
		</div>
		<?php
		foreach($meetings as $meeting){
			$post_id=$meeting->ID;
			
			$RoomDoctors=get_post_meta( $meeting->ID,'doctors', true );
			
			$arrRoomDoctors=unserialize($RoomDoctors);
			if(is_array($arrRoomDoctors)==false)continue;

			if(array_search(get_current_user_id(),$arrRoomDoctors)===false)continue;
			?>
			
			<div class="meeting-row">
				<div class="meeting-name"><?php echo $meeting->post_title?></div>
				<div class="room-buttons">
					<a href="/account/?ki_vc_room=<?php echo $post_id ;?>" >Edit</a>
					<a href="<?php the_permalink( $post_id ); ?>" target="_blank" >Show</a>
				</div>
			</div>
			<?php
		}
	}else{
		?>
		<div class="ki-row">
		<center><?php esc_html_e( "You don't have meetings yet", 'ki-live-video-conferences' );?></center>
		</div
		<div class="ki-row">
			<center><a href="/account/?ki_vc_room=add_room&doctor=<?php echo get_current_user_id();?>" class="ki-but"><?php esc_html_e( 'Create a meeting request', 'ki-live-video-conferences' );?></a></center>
		</div>
		<?php
	}
	
	?>
	</div>
</div>



