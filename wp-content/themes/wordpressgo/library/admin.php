<?php
/*
Fonctions du côté admin
*/


// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget

}


// RSS Dashboard Widget
function wordpressgo_rss_dashboard_widget() {
	if ( function_exists( 'fetch_feed' ) ) {
		// include_once( ABSPATH . WPINC . '/feed.php' );               // include the required file
		$feed = fetch_feed( 'http://feeds.feedburner.com/LePetitrudit' );        // specify the source feed
		if (is_wp_error($feed)) {
			$limit = 0;
			$items = 0;
		} else {
			$limit = $feed->get_item_quantity(7);                        // specify number of items
			$items = $feed->get_items(0, $limit);                        // create an array of items
		}
	}
	if ($limit == 0) echo '<div>The RSS Feed is either empty or unavailable.</div>';   // fallback message
	else foreach ($items as $item) { ?>

	<h4 style="margin-bottom: 0;">
		<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo mysql2date( __( 'j F Y @ g:i a', 'wordpressgotheme' ), $item->get_date( 'Y-m-d H:i:s' ) ); ?>" target="_blank">
			<?php echo $item->get_title(); ?>
		</a>
	</h4>
	<p style="margin-top: 0.5em;">
		<?php echo substr($item->get_description(), 0, 200); ?>
	</p>
	<?php }
}

// calling all custom dashboard widgets
function wordpressgo_custom_dashboard_widgets() {
	wp_add_dashboard_widget( 'wordpressgo_rss_dashboard_widget', __( 'Recently on Themble', 'wordpressgotheme' ), 'wordpressgo_rss_dashboard_widget' );
}


// removing the dashboard widgets
add_action( 'wp_dashboard_setup', 'disable_default_dashboard_widgets' );
// adding any custom widgets
add_action( 'wp_dashboard_setup', 'wordpressgo_custom_dashboard_widgets' );


/************* CUSTOM LOGIN PAGE *****************/

// calling your own login css so you can style it

//Updated to proper 'enqueue' method
//http://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
function wordpressgo_login_css() {
	wp_enqueue_style( 'wordpressgo_login_css', get_template_directory_uri() . '/library/css/login.css', false );
}

// changing the logo link from wordpress.org to your site
function wordpressgo_login_url() {  return home_url(); }

// changing the alt text on the logo to show your site name
function wordpressgo_login_title() { return get_option( 'blogname' ); }

// calling it only on the login page
add_action( 'login_enqueue_scripts', 'wordpressgo_login_css', 10 );
add_filter( 'login_headerurl', 'wordpressgo_login_url' );
add_filter( 'login_headertitle', 'wordpressgo_login_title' );


// Custom Backend Footer
function wordpressgo_custom_admin_footer() {
	_e( '<span id="footer-thankyou">Développé par <a href="" target="_blank">Votre site</a></span>. ', 'wordpressgotheme' );
}

// adding it to the admin area
add_filter( 'admin_footer_text', 'wordpressgo_custom_admin_footer' );


function disable_wp_emojicons() {

  // Toutes les actions reliées aux Emoji (inutiles)
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

}
add_action( 'init', 'disable_wp_emojicons' );

?>
