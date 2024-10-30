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

use Abraham\TwitterOAuth\TwitterOAuth;

//FIX Start Zoom Meetingv7.1
$User_ID = get_current_user_id();

$Zoom_Api = ki_publish_api_zoom();
$meeting_id = get_post_meta($atts['meeting_id'], Settings::get_slug('zoom') . '_meeting_id', true);
$ZHost = '';
if (user_can($User_ID, 'assistant')) $ZHost = $Zoom_Api->DefaultHost();
if (user_can($User_ID, 'doctor')) $ZHost = get_user_meta($User_ID, 'zoom_host_id', true);
if (user_can($User_ID, 'teacher')) $ZHost = get_user_meta($User_ID, 'zoom_host_id', true);
if ($ZHost != '') {
    $Live_Meetings = $Zoom_Api->ListMeetings($ZHost, 'live');
    if (empty($Live_Meetings->message)) {
        foreach ($Live_Meetings->meetings as $item_meeting) {
            if ($item_meeting->id != $meeting_id) $Zoom_Api->EndMeeting($item_meeting->id);

        }
    }
}

$tw_posts = array();

$reg_user_id = false;
$reg_message = '';
if (!empty($_POST['form_reg_user'])) {
    if (!empty($_POST['new_user_name']) and !empty($_POST['new_user_pass']) and !empty($_POST['new_user_email'])) {
        $user_name = esc_attr(KiFunctions::v_post('new_user_name'));
        $user_pass = esc_attr(KiFunctions::v_post('new_user_pass'));
        $user_email = sanitize_email(KiFunctions::v_post('new_user_email'));

        $reg_user_id = wp_create_user($user_name, $user_pass, $user_email);
        $reg_message = esc_html__('Are you registered?', 'ki-live-video-conferences');
    } else {
        $reg_message = esc_html__('You have not filled in all the fields.', 'ki-live-video-conferences');
    }
}
?>
<div class="ki-video-chat-shortcode">

    <?php
    if (empty($atts['meeting_id'])) :

        return esc_html__('You haven\'t add the meeting id', 'ki-live-video-conferences');

    else:

        $public_meeting = get_post_meta($atts['meeting_id'], 'public_meeting', true);
        $meeting_password = get_post_meta($atts['meeting_id'], 'password', true);
        if (is_user_logged_in() or $public_meeting == '1'):
            if (is_user_logged_in() or (!empty($_POST['zoom_user_name']) and $_POST['zoom_user_pass'] == $meeting_password)) {
                $user_name = '';
                if (!empty($_POST['zoom_user_name'])) {
                    $user_name = '&user_name=' . KiFunctions::v_post('zoom_user_name');
                }

                //Get Twitter Posts
                $twitter_hashtag = get_post_meta($atts['meeting_id'], Settings::get_slug() . '_twitter_hashtag', true);
                $ki_twitter_key = get_option("ki_twitter_consumer_key");
                $ki_twitter_secret = get_option("ki_twitter_consumer_secret");
                $ki_twitter_access_token = get_option("ki_twitter_access_token");
                $ki_twitter_access_token_secret = get_option("ki_twitter_access_token_secret");
                if (get_option('pl_ki_twitter_analytics_active', false) === 'active' and $twitter_hashtag != '' and !empty($ki_twitter_key) and !empty($ki_twitter_secret) and !empty($ki_twitter_access_token) and !empty($ki_twitter_access_token_secret)) {


                    $connection_tw = new TwitterOAuth($ki_twitter_key, $ki_twitter_secret, $ki_twitter_access_token, $ki_twitter_access_token_secret);

                    $my_tweets = $connection_tw->get('search/tweets', array(
                        'q' => $twitter_hashtag,
                        'count' => 100
                    ));

                    foreach ($my_tweets->statuses as $my_tweet) {

                        $tw_posts[] = $my_tweet->text;
                    }


                }


                ?>

                <div class="ConteinerVideoChat">
                    <div class="ifrmaeVC">

                        <iframe allowfullscreen
                                src="<?php echo home_url('/?conference-video-room=' . esc_attr($atts['meeting_id']) . '&$user_name=' . esc_attr($user_name)); ?>"
                                width="100%" height="600"></iframe>

                    </div>
                    <?php

                    if (!empty($twitter_hashtag)) {


                        ?>
                        <div class="twitter-posts">
                            <?php
                            echo '<div class="title-hashtag">' . esc_html__('Meeting Hashtag', 'ki-live-video-conferences') . ': <span>' . $twitter_hashtag . '</span></div>';
                            if (count($tw_posts) > 0) {
                                foreach ($tw_posts as $tw_post) {
                                    ?>
                                    <div class="tw-post"><?php echo $tw_post; ?></div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }

                    ?>
                </div>
                <?php


            } else {
                ?>
                <form method="post">
                    <p class="login-username">
                        <label for="user_name"><?php esc_html_e('Your name', 'ki-live-video-conferences') ?></label>
                        <input type="text" name="zoom_user_name" id="user_name" class="input" value="" size="20">
                    </p>
                    <p class="login-password">
                        <label for="user_pass"><?php esc_html_e('Meeting password', 'ki-live-video-conferences') ?></label>
                        <input type="password" name="zoom_user_pass" id="user_pass" class="input" value="" size="20">
                    </p>
                    <p class="login-submit">
                        <input type="submit" name="zoom_submit" class="button button-primary"
                               value="<?php esc_attr_e('Login', 'ki-live-video-conferences') ?>">
                    </p>
                </form>
                <?php
            }

        else:
            $login_args = array(
                'echo' => true,
                'redirect' => site_url($_SERVER['REQUEST_URI']),
                'form_id' => 'loginform',
                'label_username' => esc_html__('Login', 'ki-live-video-conferences'),
                'label_password' => esc_html__('Password', 'ki-live-video-conferences'),
                'label_remember' => esc_html__('Remember me', 'ki-live-video-conferences'),
                'label_log_in' => esc_html__('Login', 'ki-live-video-conferences'),
                'id_username' => 'user_login',
                'id_password' => 'user_pass',
                'id_remember' => 'rememberme',
                'id_submit' => 'wp-submit',
                'remember' => true,
                'value_username' => null,
                'value_remember' => false
            );
            ?>
            <div class="container-authorization">
                <?php


                if (!empty($reg_message)) {
                    echo '<div class="mes">' . esc_html__($reg_message, 'ki-live-video-conferences') . '</div>';
                }

                ?>
                <div class="ca_buttons">
                    <div class="ca_but but-registration"
                         data="registration"><?php esc_html_e('Registration', 'ki-live-video-conferences') ?></div>
                    <div class="ca_but but-login"
                         data="login"><?php esc_html_e('Login', 'ki-live-video-conferences') ?></div>
                </div>

                <div class="form-login" style="display: none;"><?php wp_login_form($login_args); ?></div>

                <div class="form-registration" style="display: none;">
                    <form method="post">
                        <p class="reg-username">
                            <label for="user_name"><?php esc_html_e('Name', 'ki-live-video-conferences') ?></label>
                            <input type="text" name="new_user_name" id="user_name" class="input" value="" size="20">
                        </p>
                        <p class="reg-password">
                            <label for="user_pass"><?php esc_html_e('Password', 'ki-live-video-conferences') ?></label>
                            <input type="password" name="new_user_pass" id="user_pass" class="input" value="" size="20">
                        </p>
                        <p class="login-password">
                            <label reg="user_pass"><?php esc_html_e('Email', 'ki-live-video-conferences') ?></label>
                            <input type="text" name="new_user_email" id="user_pass" class="input" value="" size="20">
                        </p>
                        <p class="reg-submit">
                            <input type="submit" name="zoom_reg" class="button button-primary"
                                   value="<?php esc_attr_e('Registration', 'ki-live-video-conferences'); ?>">
                        </p>
                        <input type="hidden" name="form_reg_user" value="reg">
                    </form>
                </div>
            </div>
        <?php


        endif;
    endif; ?>
</div>
