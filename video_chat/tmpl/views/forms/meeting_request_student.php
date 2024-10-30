<?php if ( ! defined( 'ABSPATH' ) ) exit;


$form_id = $args['form_id'];

?>

<div class="um um-<?php echo esc_attr( $form_id ); ?>">

	<div class="um-form" data-mode="<?php echo esc_attr( $mode ) ?>">
		<form method="post" action="">
			<?php
			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_before_form
			 * @description Some actions before register form
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Register form shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_before_form', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_before_form', 'my_before_form', 10, 1 );
			 * function my_before_form( $args ) {
			 *     // your code here
			 * }
			 * ?>
			 */
			do_action( "um_before_form", $args );
			
			?>
			
			<div id="um_field_3775_user_login" class="um-field um-field-text   um-field-text um-field-type_text" >
				<div class="um-field-label">
				<label for="doctors_list"><?php esc_html_e( 'Teacher', 'ki-live-video-conferences' );?></label>
				<div class="um-clear"></div></div>
				<div class="um-field-area">
					<select name ="teacher">
						<?php
						$doctors=get_users(Array('role'=>'teacher'));
						foreach($doctors as $doctor){
							?>
						<option value="<?php echo $doctor->ID;?>" ><?php echo $doctor->display_name;?></option>
							<?php
						}
						?>
					</select>

				</div>
			</div>
			<?php

			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_before_{$mode}_fields
			 * @description Some actions before register form fields
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Register form shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_before_{$mode}_fields', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_before_{$mode}_fields', 'my_before_fields', 10, 1 );
			 * function my_before_form( $args ) {
			 *     // your code here
			 * }
			 * ?>
			 */
			do_action( "um_before_{$mode}_fields", $args );
			

			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_before_{$mode}_fields
			 * @description Some actions before register form fields
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Register form shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_before_{$mode}_fields', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_before_{$mode}_fields', 'my_before_fields', 10, 1 );
			 * function my_before_form( $args ) {
			 *     // your code here
			 * }
			 * ?>
			 */
			do_action( "um_main_{$mode}_fields", $args );

			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_after_form_fields
			 * @description Some actions after register form fields
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Register form shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_after_form_fields', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_after_form_fields', 'my_after_form_fields', 10, 1 );
			 * function my_after_form_fields( $args ) {
			 *     // your code here
			 * }
			 * ?>
			 */
			do_action( 'um_after_form_fields', $args );

			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_after_{$mode}_fields
			 * @description Some actions after register form fields
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Register form shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_after_{$mode}_fields', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_after_{$mode}_fields', 'my_after_form_fields', 10, 1 );
			 * function my_after_form_fields( $args ) {
			 *     // your code here
			 * }
			 * ?>
			 */
			do_action( "um_after_{$mode}_fields", $args );

			/**
			 * UM hook
			 *
			 * @type action
			 * @title um_after_form
			 * @description Some actions after register form fields
			 * @input_vars
			 * [{"var":"$args","type":"array","desc":"Register form shortcode arguments"}]
			 * @change_log
			 * ["Since: 2.0"]
			 * @usage add_action( 'um_after_form', 'function_name', 10, 1 );
			 * @example
			 * <?php
			 * add_action( 'um_after_form', 'my_after_form', 10, 1 );
			 * function my_after_form( $args ) {
			 *     // your code here
			 * }
			 * ?>
			 */
			 
			 
			do_action( "um_after_form", $args ); ?>
			
			<p class="add-submit">
					<input type="submit" name="zoom_reg" class="button button-primary" value="<?php esc_attr_e( 'Submit', 'ki-live-video-conferences' ); ?>">
				</p>

		</form>

	</div>

</div>
<style>
.um-account{
	display: none;
}
h1.entry-title{
	display: none;
}
h2.entry-title{
	display: none;
}
h3.entry-title{
	display: none;
}
</style>