<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
$um_forms=$this->get_um_forms();



?>
<div class="ki-live-video-conferences">
	<div class="ki-row">
		<a href="/account/?ki_vc_view=new_user" class="ki-but"><?php esc_html_e( 'New Patient', 'ki-live-video-conferences' );?></a>
	</div>
	
	<div class="patients-list">
	<?php

	$patients=get_users(Array('role'=>'patient'));
	foreach($patients as $patient){
		$name=$patient->display_name;
		if($name=='')$name=$patient->user_login;
		if($name=='')$name=$patient->user_email;
		?>
		<div class="patient-row">
			<div class="patient-head">
				<div class="patient-name"><a href="/user/<?php echo $patient->user_login;?>/" target="_blank"><?php echo $name;?></a></div>
			</div>
			

		</div>
		<?php
	}
	?>
	</div>


</div>



