<?php
/*
Author: Eddie Machado
Modifications: Lucien Dubois
URL: http://themble.com/wordpressgo/
*/

// TGM Plugin Activation
require_once('inc/class-tgm-plugin-activation.php');
require_once('library/plugins.php');

// Chargement de wordpressgo
require_once( 'library/wordpressgo.php' );
require_once( 'library/admin.php' );

function wordpressgo_ahoy() {

  // Support des langues pour le thème
  load_theme_textdomain( 'wordpressgotheme', get_template_directory() . '/library/translation' );

  // Création d'un custom Post Type (enlever le commentaire pour utiliser)
  // require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'wordpressgo_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'wordpressgo_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'wordpressgo_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'wordpressgo_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'wordpressgo_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'wordpressgo_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  wordpressgo_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'wordpressgo_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'wordpressgo_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'wordpressgo_excerpt_more' );

} /* end wordpressgo ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'wordpressgo_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Taille des miniatures
add_image_size( 'wordpressgo-thumb-600', 600, 150, true );
add_image_size( 'wordpressgo-thumb-300', 300, 100, true );

add_filter( 'image_size_names_choose', 'wordpressgo_custom_image_sizes' );

function wordpressgo_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'wordpressgo-thumb-600' => __('600px by 150px'),
        'wordpressgo-thumb-300' => __('300px by 100px'),
    ) );
}



// Sidebars
function wordpressgo_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'wordpressgotheme' ),
		'description' => __( 'The first (primary) sidebar.', 'wordpressgotheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

}


// Template pour les commentaires
function wordpressgo_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'wordpressgotheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'wordpressgotheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'wordpressgotheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'wordpressgotheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


// Google Fonts
function wordpressgo_fonts() {
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts', 'wordpressgo_fonts');

// Supprimer les commentaires HTML laissé par YOAST
// https://gist.github.com/paulcollett/4c81c4f6eb85334ba076
if (defined('WPSEO_VERSION')){
  add_action('get_header',function (){ ob_start(function ($o){
  return preg_replace('/\n?<.*?yoast.*?>/mi','',$o); }); });
  add_action('wp_head',function (){ ob_end_flush(); }, 999);
}



?>