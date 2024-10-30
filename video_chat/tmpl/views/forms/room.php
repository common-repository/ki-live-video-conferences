<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


$default_timezone = get_option( 'timezone_string' );
if ( empty( $default_timezone ) ) {
	$default_timezone = date_default_timezone_get();
}
	
	
$Timezone = timezone_identifiers_list();
array_unshift( $Timezone, $default_timezone );
$TimezoneLits = array();
foreach ( $Timezone as $name ) {
	$TimezoneLits[ $name ] = $name;
}
$TypeAction='';
$RoomID=KiFunctions::v_get('ki_vc_room');
$AppointmentID=KiFunctions::v_get('appointment');
$doctor_id=KiFunctions::v_get('doctor');

$arrGender=Array('Male','Female','Female Male Trans','Male to Female Trans','Female to Male','Prefer not to answer');
$FiledsValue=Array(
	'name'=>'Room_'.time(),
	'desc'=>'',
	'doctors'=>'',
	'patients'=>'',
	'start_time'=>date('Y-m-d',time()).'T'.date('H:i',time()),
	'timezone'=>$default_timezone,
	'duration'=>'60',
	'password'=>rand(),
	'patient'=>0
);


$Zoom_Api           = ki_publish_api_zoom();

$UserID=get_user_meta($doctor_id, 'zoom_host_id',true);
if($UserID==''){
	$UserID=$Zoom_Api->DefaultHost();
}



if(intval($RoomID)>0){//Edit Room
	$TypeAction='update';
	$post = get_post($RoomID);
	$FiledsValue['name']=$post->post_title;
	$FiledsValue['desc']=$post->post_content;
	$FiledsValue['start_time']=get_post_meta( $RoomID,'start_time', true );
	$FiledsValue['timezone']=get_post_meta( $RoomID,'timezone', true );
	$FiledsValue['duration']=get_post_meta( $RoomID,'duration', true );
	$FiledsValue['doctors']=get_post_meta( $RoomID,'doctors', true );
	$FiledsValue['patients']=get_post_meta( $RoomID,'patients', true );
	
	$FiledsValue['doctors']=unserialize($FiledsValue['doctors']);
	$FiledsValue['patients']=unserialize($FiledsValue['patients']);
	
	$FiledsValue['doctors']=$FiledsValue['doctors'][0];
	$FiledsValue['patients']=$FiledsValue['patients'][0];
	
	$doctor_id=$FiledsValue['doctors'];
	
	$AppointmentID=get_post_meta( $RoomID,'appointment', true );

}else{//New Room
	$TypeAction='new';
}


$UserMetabox=Array(
	'first_name'=>'First Name',
	'last_name'=>'Last Name',
	'user_email'=>'E-mail Address',
	'gender'=>'Gender',
	'mobile_number'=>'Mobile Number',
	'birth_date'=>'Birth Date',
	'postal_code'=>'Postal code',
	'card_number'=>'Health card number',
	'version_code'=>'Reason for visit',
	'reason_visit'=>'Version code',
	'need_french'=>'J\'ai besoin que ma visite soit en français',
);
$UserFields=Array();

if(!empty($AppointmentID)){
	
	$FiledsValue['patients']=get_post_meta( $AppointmentID,'patient', true );
	$start_time=get_post_meta( $AppointmentID,'start_time', true );
	$start_time=strtotime($start_time);
	$FiledsValue['start_time']=date('Y-m-d',$start_time).'T'.date('H:i',$start_time);
	
	foreach($UserMetabox as $key=>$name){
		$UserFields[$key]=get_post_meta( $AppointmentID,$key, true );
	}
	
}


$doctor=get_userdata($doctor_id);
?>
<div class="ki-live-video-conferences">
	<div class="ki-row">
		<a href="/account/" class="ki-but">&#9668; <?php esc_html_e( 'Back to the profile', 'ki-live-video-conferences' );?></a>
	</div>
	<div class="ki-live-video-conferences-Room">
		<h3><?php esc_attr_e( 'Room', 'ki-live-video-conferences' ); ?></h3>
		<form method="post">
		<table border="0">
		
			<tr>
				<td><?php esc_attr_e( 'Doctor', 'ki-live-video-conferences' ); ?><td>
				<td><?php echo $doctor->display_name;?> <input type="hidden" name="doctor_id" value="<?php echo $doctor_id;?>"><td>
			</tr>
			<tr>
				<td><?php esc_attr_e( 'Patient', 'ki-live-video-conferences' ); ?><td>
				<td>
					<select name ="patients">
						<?php
						$patients=get_users(Array('role'=>'patient'));
						foreach($patients as $patient){
							$user_name=KiFunctions::get_user_name($patient);
							
							?>
						<option value="<?php echo $patient->ID;?>" <?php selected($patient->ID,$FiledsValue['patients']) ?>><?php echo $user_name;?></option>
							<?php
						}
						?>
					</select>
				<td>
			</tr>
			<tr>
				<td><?php esc_attr_e( 'Name room', 'ki-live-video-conferences' ); ?><td>
				<td><input type="text" name="post_title" value="<?php echo $FiledsValue['name'];?>"><td>
			</tr>
			
			<tr>
				<td><?php esc_attr_e( 'Start time', 'ki-live-video-conferences' ); ?><td>
				<td><input type="datetime-local" name="start_time" value="<?php echo $FiledsValue['start_time'];?>"><td>
			</tr>
			<tr>
				<td><?php esc_attr_e( 'Timezone', 'ki-live-video-conferences' ); ?><td>
				<td>
					<select name ="timezone">
						<?php
						foreach($TimezoneLits as $val){
							?>
						<option value="<?php echo $val;?>" <?php selected($val,$FiledsValue['timezone']) ?>><?php echo $val;?></option>
							<?php
						}
						?>
					</select>
				<td>
			</tr>
			<tr>
				<td><?php esc_attr_e( 'Duration', 'ki-live-video-conferences' ); ?><td>
				<td><input type="number" name="duration" value="<?php echo $FiledsValue['duration'];?>"><td>
			</tr>
			<tr>
				<td><?php esc_attr_e( 'Password', 'ki-live-video-conferences' ); ?><td>
				<td><input type="text" id="password" name="password" value="<?php echo $FiledsValue['password'];?>"><td>
			</tr>
			<tr>
				<td><?php esc_attr_e( 'Description', 'ki-live-video-conferences' ); ?><td>
				<td><input type="text" name="content" value="<?php echo $FiledsValue['desc'];?>"><td>
			</tr>
			<?php if(count($UserFields)>0){
				foreach($UserFields as $key=>$val){
					if(trim($val)!=''){
						if($key=='need_french' AND $val!='')$val=esc_attr__( 'Yes', 'ki-live-video-conferences' ); 
						if($key=='gender' AND $val!='')$val=esc_attr__( $arrGender[$val], 'ki-live-video-conferences' ); 
						?>
			<tr>
				<td><?php esc_attr_e( $UserMetabox[$key], 'ki-live-video-conferences' ); ?><td>
				
				
				<td><?php echo $val;?><td>
			</tr>
						<?php
					}
				}
			?>

				<?php /*if(trim($post->post_content)!=''):?>
				<tr>
					<td><?php esc_attr_e( 'Comment', 'ki-live-video-conferences' ); ?><td>
					<td><?php echo $post->post_content;?><td>
				</tr>
				<?php endif;*/?>
			<?php } ?>
			
			<?php if(!empty($AppointmentID)){
				$post = get_post($AppointmentID);
			?>

				<?php if(trim($post->post_content)!=''):?>
				<tr>
					<td><?php esc_attr_e( 'Comment', 'ki-live-video-conferences' ); ?><td>
					<td><?php echo $post->post_content;?><td>
				</tr>
				<?php endif;?>
			<?php } ?>
			
		</table>
		
		
		<input type="hidden" name="appointment_id" value="<?php echo $AppointmentID;?>">
		
		<input type="hidden" name="userId" value="<?php echo $UserID;?>">
		<input type="hidden" name="action_room" value="<?php echo $TypeAction; ?>">
		<p>
			<input type="submit" name="zoom_reg" class="button button-primary" value="<?php esc_attr_e( 'Save', 'ki-live-video-conferences' ); ?>">
		</p>
		
		</form>
	</div>
	

	
</div>

<style>
.um-account{
	display: none;
}
</style>

