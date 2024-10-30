<?php
namespace KiLiveVideoConferences;

if (!defined('ABSPATH')) {
    exit();
}

?>
<div class="ki_lvc_get_start">

    <div class="all_service">
        <div class="intro_srv_desc">
            <div class="intro_image">
                <img src="<?php echo esc_url(KI_VC_ADMIN_URL . 'assets/images/zoom.png'); ?>">
            </div>
            <div class="intro_text">
                <p>Users Join Zoom meetings directly from Browser . KI live offers extensive functionality to manage
                    zoom meetings, webinars, recordings, users, reports from your WordPress Dashboard</p>
            </div>
        </div>
        <div class="intro_srv_desc">
            <div class="intro_image">
                <img src="<?php echo esc_url(KI_VC_ADMIN_URL . 'assets/images/rainbow.png'); ?>">
            </div>
            <div class="intro_text">

                <p>Rainbow encrypted communication solution is a user-friendly online tool designed to support
                    collaboration, time management, security, and privacy needs.</p>
                <p>Rainbow offers End-to-end encryption, full GDRP conformity and ISO27001 certification.</p>
            </div>
        </div>
    </div>
    <div class="intro_center">
        <img src="<?php echo esc_url(KI_VC_ADMIN_URL . 'assets/images/intro.png'); ?>" class="intro_img">
    </div>
    <div class="into_buttons">
        <a href="<?php echo home_url('wp-admin/admin.php?page=ki_settings_page&active=learning'); ?>"
           class="but"><?php _e('Learning', 'ki-live-video-conferences'); ?></a>
        <a href="<?php echo home_url('wp-admin/admin.php?page=ki_settings_page&active=healthcare'); ?>"
           class="but"><?php _e('Healthcare', 'ki-live-video-conferences'); ?></a>
        <a href="<?php echo home_url('wp-admin/admin.php?page=ki_settings_page&active=video_platform'); ?>"
           class="but"><?php _e('Video Platform', 'ki-live-video-conferences'); ?></a>

    </div>
</div>
