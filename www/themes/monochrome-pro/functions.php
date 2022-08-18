<?php
/**
 * Monochrome Pro.
 *
 * This file adds functions to the Monochrome Pro Theme.
 *
 * @package Monochrome
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/monochrome/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Child theme (do not remove).
define( 'CHILD_THEME_HANDLE', sanitize_title_with_dashes( wp_get_theme()->get( 'Name' ) ) );
define( 'CHILD_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

// Define Session Duration (WP session duration default is 48 hrs/2 days)
define( 'MAX_SESSION_DURATION', 24*60*60); // value should be in seconds

// Setup Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'monochrome_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function monochrome_localization_setup() {

	load_child_theme_textdomain( 'monochrome-pro', get_stylesheet_directory() . '/languages' );

}

// Adds the theme helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds Image upload and Color select to WordPress Theme Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Includes the Customizer CSS for the WooCommerce plugin.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Includes notice to install Genesis Connect for WooCommerce.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * Allows plugins to Removes support if required.
 *
 * @since 1.1.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

add_action( 'wp_enqueue_scripts', 'monochrome_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function monochrome_enqueue_scripts_styles() {

	wp_enqueue_style( 'monochrome-fonts', '//fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i|Open+Sans+Condensed:300', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'monochrome-ionicons', '//unpkg.com/ionicons@4.1.2/dist/css/ionicons.min.css', array(), CHILD_THEME_VERSION );

	wp_enqueue_script( 'monochrome-global-script', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'monochrome-block-effects', get_stylesheet_directory_uri() . '/js/block-effects.js', array(), '1.0.0', true );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'monochrome-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menus' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script( 'monochrome-responsive-menu', 'genesis_responsive_menu', monochrome_responsive_menu_settings() );

}

/**
 * Defines responsive menu settings.
 *
 * @since 1.1.0
 */
function monochrome_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => __( 'Menu', 'monochrome-pro' ),
		'menuIconClass'    => 'ionicons-before ion-ios-menu',
		'subMenu'          => __( 'Submenu', 'monochrome-pro' ),
		'subMenuIconClass' => 'ionicons-before ion-ios-arrow-down',
		'menuClasses'      => array(
			'combine' => array(),
			'others'  => array(
				'.nav-primary',
			),
		),
	);

	return $settings;

}

// Adds HTML5 markup structure.
add_theme_support( 'html5', genesis_get_config( 'html5' ) );

// Adds accessibility support.
add_theme_support( 'genesis-accessibility', genesis_get_config( 'accessibility' ) );

// Adds viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Adds support for custom logo.
add_theme_support( 'custom-logo', genesis_get_config( 'custom-logo' ) );

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds image sizes.
add_image_size( 'front-blog', 960, 540, true );
add_image_size( 'sidebar-thumbnail', 80, 80, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

add_action( 'genesis_theme_settings_metaboxes', 'monochrome_remove_genesis_metaboxes' );
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 1.0.0
 *
 * @param string $_genesis_theme_settings The admin screen to remove meta boxes from.
 */
function monochrome_remove_genesis_metaboxes( $_genesis_theme_settings ) {

	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings, 'main' );
	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );

}

add_filter( 'genesis_customizer_theme_settings_config', 'monochrome_remove_customizer_settings' );
/**
 * Removes output of header and front page breadcrumb settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function monochrome_remove_customizer_settings( $config ) {

	unset( $config['genesis']['sections']['genesis_header'] );
	unset( $config['genesis']['sections']['genesis_breadcrumbs']['controls']['breadcrumb_front_page'] );
	return $config;

}

// Displays custom logo.
add_action( 'genesis_site_title', 'the_custom_logo', 0 );

add_filter( 'genesis_seo_title', 'monochrome_header_title', 10, 3 );
/**
 * Removes the link from the hidden site title if a custom logo is in use.
 *
 * Without this filter, the site title is hidden with CSS when a custom logo
 * is in use, but the link it contains is still accessible by keyboard.
 *
 * @since 1.2.0
 *
 * @param string $title  The full title.
 * @param string $inside The content inside the title element.
 * @param string $wrap   The wrapping element name, such as h1.
 * @return string The site title with anchor removed if a custom logo is active.
 */
function monochrome_header_title( $title, $inside, $wrap ) {

	if ( has_custom_logo() ) {
		$inside = get_bloginfo( 'name' );
	}

	return sprintf( '<%1$s class="site-title">%2$s</%1$s>', $wrap, $inside );

}

// Registers navigation menus.
add_theme_support( 'genesis-menus', genesis_get_config( 'menus' ) );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_after', 'genesis_do_subnav', 12 );

add_action( 'genesis_meta', 'monochrome_add_search_icon' );
/**
 * Adds the search icon to the header if the option is set in the Customizer.
 *
 * @since 1.0.0
 */
function monochrome_add_search_icon() {

	$show_icon = get_theme_mod( 'monochrome_header_search', monochrome_customizer_get_default_search_setting() );

	// Exit early if option set to false.
	if ( ! $show_icon ) {
		return;
	}

	add_action( 'genesis_header', 'monochrome_do_header_search_form', 14 );
	add_filter( 'genesis_nav_items', 'monochrome_add_search_menu_item', 10, 2 );
	add_filter( 'wp_nav_menu_items', 'monochrome_add_search_menu_item', 10, 2 );

}

/**
 * Modifies the menu item output of the header menu.
 *
 * @since 1.0.0
 *
 * @param string $items The menu HTML.
 * @param array  $args The menu options.
 * @return string Updated menu HTML.
 */
function monochrome_add_search_menu_item( $items, $args ) {

	$search_toggle = sprintf( '<li class="menu-item">%s</li>', monochrome_get_header_search_toggle() );

	if ( 'primary' === $args->theme_location ) {
		$items .= $search_toggle;
	}

	return $items;

}

add_filter( 'wp_nav_menu_args', 'monochrome_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 1.0.0
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function monochrome_secondary_menu_args( $args ) {

	if ( 'secondary' !== $args['theme_location'] ) {
		return $args;
	}
	$args['depth'] = 1;
	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'monochrome_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 1.0.0
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function monochrome_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_post_info', 'monochrome_entry_meta_header' );
/**
 * Modifies the meta information in the entry header.
 *
 * @since 1.0.0
 *
 * @param string $post_info Current post info.
 * @return string New post info.
 */
function monochrome_entry_meta_header( $post_info ) {

	$post_info = '[post_author_posts_link] &middot; [post_date format="M j, Y"] &middot; [post_comments] [post_edit]';
	return $post_info;

}

add_filter( 'genesis_post_meta', 'monochrome_entry_meta_footer' );
/**
 * Modifies the entry meta in the entry footer.
 *
 * @since 1.0.0
 *
 * @param string $post_meta Current post info.
 * @return string The new entry meta.
 */
function monochrome_entry_meta_footer( $post_meta ) {

	$post_meta = '[post_categories before=""] [post_tags before=""]';
	return $post_meta;

}

add_filter( 'genesis_comment_list_args', 'monochrome_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 1.0.0
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function monochrome_comments_gravatar( $args ) {

	$args['avatar_size'] = 48;
	return $args;

}

/**
 * Counts used widgets in given sidebar.
 *
 * @since 1.0.0
 *
 * @param string $id The sidebar ID.
 * @return int|void The number of widgets, or nothing.
 */
function monochrome_count_widgets( $id ) {

	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

/**
 * Outputs class names based on widget count.
 *
 * @since 1.0.0
 *
 * @param string $id The widget ID.
 * @return string The class.
 */
function monochrome_widget_area_class( $id ) {

	$count = monochrome_count_widgets( $id );

	$class = '';

	if ( 1 === $count ) {
		$class .= ' widget-full';
	} elseif ( 1 === $count % 3 ) {
		$class .= ' widget-thirds';
	} elseif ( 1 === $count % 4 ) {
		$class .= ' widget-fourths';
	} elseif ( 0 === $count % 2 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves';
	}

	return $class;

}

add_filter( 'get_the_content_limit', 'monochrome_content_limit_read_more_markup', 10, 3 );
/**
 * Modifies the generic more link markup for posts.
 *
 * @since 1.0.0
 *
 * @param string $output The current full HTML.
 * @param string $content The content HTML.
 * @param string $link The link HTML.
 * @return string The new more link HTML.
 */
function monochrome_content_limit_read_more_markup( $output, $content, $link ) {

	$output = sprintf( '<p>%s &#x02026;</p><p class="more-link-wrap">%s</p>', $content, str_replace( '&#x02026;', '', $link ) );

	return $output;

}

// Removes entry meta in entry footer.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

add_action( 'genesis_before_footer', 'monochrome_before_footer_cta' );
/**
 * Hooks in before footer CTA widget area.
 *
 * @since 1.0.0
 */
function monochrome_before_footer_cta() {

	genesis_widget_area(
		'before-footer-cta',
		array(
			'before' => '<div class="before-footer-cta"><div class="wrap">',
			'after'  => '</div></div>',
		)
	);

}

// Removes site footer.
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

// Adds site footer.
add_action( 'genesis_after', 'genesis_footer_markup_open', 5 );
add_action( 'genesis_after', 'genesis_do_footer' );
add_action( 'genesis_after', 'genesis_footer_markup_close', 15 );

add_filter( 'genesis_after', 'monochrome_custom_footer_logo', 7 );
/**
 * Outputs the footer logo above the footer credits.
 *
 * @since 1.2.0
 */
function monochrome_custom_footer_logo() {

	$footer_logo      = get_theme_mod( 'monochrome-footer-logo', monochrome_get_default_footer_logo() );
	$footer_logo_link = sprintf( '<p><a class="footer-logo-link" href="%1$s"><img class="footer-logo" src="%2$s" alt="%3$s" /></a></p>', trailingslashit( home_url() ), esc_url( $footer_logo ), get_bloginfo( 'name' ) );

	if ( $footer_logo ) {
		echo $footer_logo_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}

// Registers widget areas.
genesis_register_sidebar(
	array(
		'id'          => 'before-footer-cta',
		'name'        => __( 'Before Footer CTA', 'monochrome-pro' ),
		'description' => __( 'This is the before footer CTA section.', 'monochrome-pro' ),
	)
);

add_action( 'wp_enqueue_scripts', 'wsm_custom_stylesheet', 20 );
function wsm_custom_stylesheet() {
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/custom.css', '', 'v1.0.0' );
}


################################################################################
add_action('after_setup_theme', 'remove_admin_bar');
 
function remove_admin_bar() {
    if (!current_user_can('administrator') && 
        !current_user_can('editor') &&
        !current_user_can('author')) {
        show_admin_bar(false);
    }
#        show_admin_bar(false);
}

################################################################################
/**
 * Remove "You are here" text from Genesis breadcrumb feature
 */
add_filter('genesis_breadcrumb_args', 'remove_breadcrumbs_yourarehere_text');
function remove_breadcrumbs_yourarehere_text( $args ) {
    $args['labels']['prefix'] = '';
    return $args;
}

/**
 * Remove Post Title from Breadcrumb.
 * 
 * Takes a substring of crumb, starting at 0, with a length of up to the last occurrence of the 
 * (start of the) separator string.
 */
add_filter( 'genesis_single_crumb', 'remove_post_title_from_bread_crumb', 10, 2 );
add_filter( 'genesis_page_crumb', 'remove_post_title_from_bread_crumb', 10, 2 );
function remove_post_title_from_bread_crumb( $crumb, $args ) {
  return substr( $crumb, 0, strrpos( $crumb, $args['sep'] ) );
}

################################################################################
/**
 * Update WordPress session duration
 */
/*add_filter('auth_cookie_expiration', 'my_expiration_filter', 99, 3);
function my_expiration_filter($seconds, $user_id, $remember){

    //http://en.wikipedia.org/wiki/Year_2038_problem
    if ( PHP_INT_MAX - time() < constant("MAX_SESSION_DURATION") ) {
        //Fix to a little bit earlier!
        return(PHP_INT_MAX - time() - 5);
    }
	
    return constant("MAX_SESSION_DURATION");
}*/

################################################################################
/**
 * Create a session so that when authn/authz occurs, it can be used to store CAS information
 */

/*if (!session_id()) {
	session_start();
}*/
