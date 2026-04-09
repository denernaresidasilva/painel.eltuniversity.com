<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
// func :: webinarIgnition_dbug
// --------------------------------------------------------------------------------------
function webinarIgnition_dbug() {
    $cvs = phpversion();
    // current version string
    $rvs = '5.4.9';
    // required version string
    if ( version_compare( $cvs, $rvs, '<' ) ) {
        echo '<br>
               <div class="error" style="display:inline-block; padding:12px; margin-left:2px; margin-top:20px"><b>' . esc_html__( 'WARNING !!', 'webinar-ignition' ) . '</b><br>' . esc_html__( 'This plugin requires at least PHP version', 'webinar-ignition' ) . ' ' . esc_html( $rvs ) . ' ' . esc_html__( "but this server's installed version is older:", 'webinar-ignition' ) . ' ' . esc_html( $cvs ) . '<br><br>' . esc_html__( 'It is <strong>strongly</strong> recommended that you contact your hosting provider to upgrade your PHP installation to the required version or better.<br> If you ignore this, your software will throw errors or cause unwanted problems.', 'webinar-ignition' ) . '</div>';
    }
}

function wi_date_difference(  $date_1, $date_2, $difference_in = 'days'  ) {
    switch ( $difference_in ) {
        case 'seconds':
            $difference_format = '%R%s';
            break;
        case 'minutes':
            $difference_format = '%R%i';
            break;
        case 'hours':
            $difference_format = '%R%h';
            break;
        case 'days':
            $difference_format = '%R%d';
            break;
        default:
            $difference_format = '%R%a';
            break;
    }
    $datetime1 = date_create( $date_1 );
    $datetime2 = date_create( $date_2 );
    $interval = date_diff( $datetime1, $datetime2 );
    $seconds = intval( $interval->format( '%R%s' ) );
    $minutes = intval( $interval->format( '%R%i' ) );
    $hours = intval( $interval->format( '%R%h' ) );
    $days = intval( $interval->days );
    if ( 'days' == $difference_in ) {
        return $days;
    } elseif ( 'hours' == $difference_in ) {
        return $days * 24 + $hours;
    } elseif ( 'minutes' == $difference_in ) {
        return $days * 24 * 60 + $hours * 60 + $minutes;
    } elseif ( 'seconds' == $difference_in ) {
        return $days * 24 * 60 + $hours * 60 + $minutes * 60 + $seconds;
    }
    return $interval->format( $difference_format );
}

// --------------------------------------------------------------------------------------
function webinarignition_dashboard() {
    $id = ( isset( $_GET['id'] ) ? absint( $_GET['id'] ) : null );
    $create = ( isset( $_GET['create'] ) ? sanitize_text_field( wp_unslash( $_GET['create'] ) ) : null );
    // Sanitize as plain text
    $webinars = ( isset( $_GET['webinars'] ) ? sanitize_textarea_field( wp_unslash( $_GET['webinars'] ) ) : null );
    // For larger text
    // fix :: notice on outdated PHP version
    // --------------------------------------------------------------------------------------
    webinarIgnition_dbug();
    // --------------------------------------------------------------------------------------
    // Universal Variables
    $sitePath = WEBINARIGNITION_URL;
    // UI FRAMEWORK
    include 'ui-core.php';
    include 'ui-com2.php';
    // The whole code of js-core.php is transferred to webinarignition-admin-dashboard.php File
    //include 'js-core.php';
    update_option( 'webinarignition_activated', 0 );
    // Acitvation Look Up ::
    global $wpdb;
    // Create a new stdClass object
    $site_url = get_site_url();
    $statusCheck = new stdClass();
    $statusCheck->switch = 'free';
    $statusCheck->slug = 'free';
    $statusCheck->licensor = '';
    $statusCheck->is_free = 1;
    $statusCheck->is_dev = '';
    $statusCheck->is_registered = '';
    $statusCheck->title = 'Free';
    $statusCheck->member_area = '';
    $statusCheck->is_pending_activation = 1;
    $statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
    $statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
    $statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
    $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
    $statusCheck->name = '';
    $webinarignition_dashboard_link = admin_url( '?page=webinarignition-dashboard' );
    ?>
	<div id="mWrapper">
		<div id="mHeader" style="background-color: #353337;">
			<div id="mLogo">
				<div class="webinar_logo_license_cont">
					<div class="mLogoIMG">
					<?php 
    ?>
								<img class="welogo" style="padding-top: 10px;" src="<?php 
    echo esc_url( WEBINARIGNITION_URL );
    ?>images/webinarignition-white-grey.png" width="284" alt="" border="0">		
							<?php 
    ?>
					</div>

					<?php 
    ?>
					
				</div>
				<?php 
    if ( $statusCheck && (empty( $statusCheck ) || $statusCheck->switch == '' || !empty( $statusCheck->is_fs ) || empty( $statusCheck->keyused )) ) {
    } else {
        $is_freemius_not_registered = !empty( $statusCheck->reconnect_url ) && !$statusCheck->is_registered;
        if ( $is_freemius_not_registered ) {
            ?>
				<style>
					.WIheaderRight {
						width: 80%;
						float: right;
						position: relative;
						display: table;
						padding: 0 14px;
						line-height: 47px;
					}
					.mSupport {
						margin-top: 0px;
						margin-right: 0px;
					}
					.mSupport:last-child {
						margin-right: 0px;
					}
				</style>
				<?php 
        }
        ?>
					<div class="WIheaderRight">
						<button
								data-toggle="collapse"
								data-target="#unlockFormsContainer"
								aria-expanded="false"
								aria-controls="unlockFormsContainer"
								class="btn btn-primary mSupport"
								title="<?php 
        esc_html_e( 'License bought before 01/2021', 'webinar-ignition' );
        ?>"
						>
							<i class="icon-key" style="margin-right: 5px;"></i>
							<?php 
        esc_html_e( 'Manage license', 'webinar-ignition' );
        ?>
						</button>
						<?php 
    }
    //end if
    ?>
				<div class="wi-head-btns-wrap">

				<?php 
    ?>

				<a href="<?php 
    echo esc_url( get_admin_url() . 'admin.php?page=webinarignition_support' );
    ?>" class="btn btn-primary mSupport"><i class="icon-question-sign" style="margin-right: 5px;"></i> <?php 
    esc_html_e( 'Solution Center', 'webinar-ignition' );
    ?></a>
				</div>
			</div>
		</div>

		<div id="container">
			<?php 
    // Edit App
    if ( isset( $id ) ) {
        include 'editapp.php';
    } elseif ( isset( $create ) ) {
        include 'create.php';
    } elseif ( isset( $webinars ) ) {
        include 'webinars-list.php';
    } else {
        include 'dash.php';
    }
    ?>

		</div>
	</div>
	<?php 
    // END
}
