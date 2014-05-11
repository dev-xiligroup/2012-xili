<?php
// twentytwelve - modifications for twentytwelve-xili
// dev.xiligroup.com - msc - 2012-09-02
// dev.xiligroup.com - msc - 2013-01-10 - options to clone sidebar container
// dev.xiligroup.com - msc - 2013-01-31 - improves search form and filter
// dev.xiligroup.com - msc - 2013-02-06 - improves sidebar cloning
// dev.xiligroup.com - msc - 2013-02-23 - don't clone if option (temporaly) disabled
// dev.xiligroup.com - msc - 2013-03-03 - fixes
// 2013-02-23 - 1.2 - based on new class since xili-language 2.8.8+
// 2013-07-17 - 1.2.1 - based on updated class since xili-language 2.8.10+
// 2013-11-12 - 1.3.1 - as parent version - updated with class of 2.9.10+ - fixes header loop
// 2014-02-09 - 1.3.2 - as parent version - updated with new class of 2.10.0+
// 2014-04-10 - 1.4.0 - as parent version - updated with new class of 2.12.0+

define( 'TWENTYTWELVE_XILI_VER', '1.4.0'); // as style.css

function twentytwelve_xilidev_setup () {

	$theme_domain = 'twentytwelve';

	load_theme_textdomain( $theme_domain, get_stylesheet_directory() . '/langs' ); // now use .mo of child

	$xl_required_version = false;

	$minimum_xl_version = '2.11.99';

	if ( class_exists('xili_language') ) { // if temporary disabled

		$xl_required_version = version_compare ( XILILANGUAGE_VER, $minimum_xl_version, '>' );

		global $xili_language;

		$xili_language_includes_folder = $xili_language->plugin_path .'xili-includes';

		$xili_functionsfolder = get_stylesheet_directory() . '/functions-xili' ;

		if ( file_exists( $xili_functionsfolder . '/multilingual-classes.php') ) {
			require_once ( $xili_functionsfolder . '/multilingual-classes.php' ); // xili-options

		} elseif ( file_exists( $xili_language_includes_folder . '/theme-multilingual-classes.php') ) {

			require_once ( $xili_language_includes_folder . '/theme-multilingual-classes.php' ); // ref xili-options based in plugin
		}

		if ( file_exists( $xili_functionsfolder . '/multilingual-functions.php') ) {
			require_once ( $xili_functionsfolder . '/multilingual-functions.php' );
		}



	//register_nav_menu ( 'toto', 'essai' );

		global $xili_language_theme_options ; // used on both side
	// Args dedicaced to this theme named Twenty Thirteen
		$xili_args = array (
			'customize_clone_widget_containers' => true, // comment or set to true to clone widget containers
			'settings_name' => 'xili_twentytwelve_theme_options', // name of array saved in options table
			'theme_name' => 'Twenty Twelve',
			'theme_domain' => $theme_domain,
			'child_version' => TWENTYTWELVE_XILI_VER
		);

		if ( is_admin() ) {

		// Admin args dedicaced to this theme

			$xili_admin_args = array_merge ( $xili_args, array (
				'customize_adds' => true, // add settings in customize page
				'customize_addmenu' => false, // done by 2013
				'capability' => 'edit_theme_options',

				'authoring_options_admin' => false,
			) );
			if ( class_exists ( 'xili_language_theme_options_admin' ) ) {
				$xili_language_theme_options = new xili_language_theme_options_admin ( $xili_admin_args );
				$class_ok = true ;
			} else {
				$class_ok = false ;
			}


		} else { // visitors side - frontend

			if ( class_exists ( 'xili_language_theme_options' ) ) {
				$xili_language_theme_options = new xili_language_theme_options ( $xili_args );
				$class_ok = true ;
			} else {
				$class_ok = false ;
			}
		}

		// new ways to add parameters in authoring propagation
		add_theme_support('xiliml-authoring-rules', array (
			'post_content' => array('default' => '1',
				'data' => 'post',
				'hidden' => '',
				'name' => 'Post Content',
				/* translators: added in child functions by xili */
				'description' => __('Will copy content in the future translated post', 'twentytwelve')
		),
			'post_parent' => array('default' => '1',
				'data' => 'post',
				'name' => 'Post Parent',
				'hidden' => '1',
				/* translators: added in child functions by xili */
				'description' => __('Will copy translated parent id (if original has parent and translated parent)!', 'twentytwelve')
		))
		); //

		$xili_theme_options = get_theme_xili_options() ;
		// to collect checked value in xili-options of theme
		if ( file_exists( $xili_functionsfolder . '/multilingual-permalinks.php') && $xili_language->is_permalink && isset ( $xili_theme_options['perma_ok'] ) && $xili_theme_options['perma_ok'] ) {
			require_once ( $xili_functionsfolder . '/multilingual-permalinks.php' ); // require subscribing premium services
		}

	}

	// errors and installation informations

	if ( ! class_exists( 'xili_language' ) ) {

		$msg = '
		<div class="error">'.
			/* translators: added in child functions by xili */
			'<p>' . sprintf ( __('The %s child theme requires xili-language plugin installed and activated', 'twentytwelve' ), get_option( 'current_theme' ) ).'</p>
		</div>';

	} elseif ( $class_ok === false ) {

		$msg = '
		<div class="error">'.
			/* translators: added in child functions by xili */
			'<p>' . sprintf ( __('The %s child theme requires <em>xili_language_theme_options</em> class to set multilingual features.', 'twentytwelve' ), get_option( 'current_theme' ) ).'</p>
		</div>';

	} elseif ( $xl_required_version ) {

		$msg = '
		<div class="updated">'.
			/* translators: added in child functions by xili */
			'<p>' . sprintf ( __('The %s child theme was successfully activated with xili-language.', 'twentytwelve' ), get_option( 'current_theme' ) ).'</p>
		</div>';

	} else {

		$msg = '
		<div class="error">'.
			/* translators: added in child functions by xili */
			'<p>' . sprintf ( __('The %1$s child theme requires xili-language version %2$s+', 'twentytwelve' ), get_option( 'current_theme' ), $minimum_xl_version ).'</p>
		</div>';
	}
	// after activation and in themes list
	if ( isset( $_GET['activated'] ) || ( ! isset( $_GET['activated'] ) && ( ! $xl_required_version || ! $class_ok ) ) )
		add_action( 'admin_notices', $c = create_function( '', 'echo "' . addcslashes( $msg, '"' ) . '";' ) );

	// end errors...

}

add_action( 'after_setup_theme', 'twentytwelve_xilidev_setup', 11 );
if ( class_exists('xili_language') )	// if temporary disabled
	add_action ( 'wp_head', 'special_head', 11);


/**
 * define when search form is completed by radio buttons to sub-select language when searching
 *
 */
function special_head() {

	// to change search form of widget
	// if ( is_front_page() || is_category() || is_search() )
	if ( is_search() ) {
		add_filter('get_search_form', 'my_langs_in_search_form_2012', 10, 1); // in multilingual-functions.php
	}
	$xili_theme_options = get_theme_xili_options() ; // see below

	if ( !isset( $xili_theme_options['no_flags'] ) || $xili_theme_options['no_flags'] != '1' ) {
		twentytwelve_flags_style();
	}
}

// need to be here not in hook
add_action( 'customize_preview_init', 'xili_customize_js_footer', 9 ); // before parent 2013 to be in footer
function xili_customize_js_footer () {

	wp_enqueue_script( 'customize-xili-js-footer', get_stylesheet_directory_uri(). '/functions-xili' . '/js/xili_theme_customizer.js' , array( 'customize-preview' ), TWENTYTWELVE_XILI_VER, true );

}

function twentytwelve_xilidev_setup_custom_header () {

	// %2$s = in child
	register_default_headers( array(
		'xili2012' => array(
			'url'			=> '%2$s/images/headers/xili-2012.jpg',
			'thumbnail_url' => '%2$s/images/headers/xili-2012-thumbnail.jpg',
			/* translators: added in child functions by xili */
			'description'	=> _x( '2012 by xili', 'header image description', 'twentytwelve' )
		),
		'xili2012-2' => array(
			'url'			=> '%2$s/images/headers/xili-2012-2.jpg',
			'thumbnail_url' => '%2$s/images/headers/xili-2012-2-thumbnail.jpg',
			/* translators: added in child functions by xili */
			'description'	=> _x( '2012-2 by xili', 'header image description', 'twentytwelve' )
		))
	);

	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'	=> '303030', // diff of parent
		'default-image'			=> 'random-default-image', //'%2$s/images/headers/xili-2012.jpg',

		// Set height and width, with a maximum value for the width.
		'height'				=> 120,
		'width'					=> 960,


		// Callbacks for styling the header and the admin preview.
		'wp-head-callback'			=> 'twentytwelve_header_style',
		'admin-head-callback'		=> 'twentytwelve_admin_header_style',
		'admin-preview-callback'	=> 'twentytwelve_admin_header_image',
	);




	add_theme_support( 'custom-header', $args ); // need 8 in add_action to overhide parent



}

add_action( 'after_setup_theme', 'twentytwelve_xilidev_setup_custom_header', 9 );


function twentytwelve_xilidev_setup_custom_bk_reset () {

	//define( 'BACKGROUND_COLOR', 'f5f5f5' ); // to reset default parent value

	add_theme_support( 'custom-background', array(
		'default-color' => 'f5f5f5',
		'default-image' => get_stylesheet_directory_uri() . '/images/background-2012.jpg'
	) );

}

function twentytwelve_reset_default_theme_value ( $theme ) {


	set_theme_mod( 'header-text-color', '303030' ); // to force first insertion // same in css

	set_theme_mod( 'header_image', 'random-default-image' );

	set_theme_mod( 'background_repeat', 'repeat-x');
	set_theme_mod( 'background_position_x', 'center');
	set_theme_mod( 'background_attachment', 'fixed');
	set_theme_mod( 'background_color', 'f5f5f5');
	set_theme_mod( 'background_image', get_stylesheet_directory_uri() . '/images/background-2012.jpg');
	set_theme_mod( 'background_image_thumb', get_stylesheet_directory_uri() . '/images/background-2012-thumbnail.jpg');

}

add_action( 'after_switch_theme', 'twentytwelve_reset_default_theme_value' );

add_action( 'after_setup_theme', 'twentytwelve_xilidev_setup_custom_bk_reset', 9 ); // to reset parent value

/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_entry_meta() {
	// translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
	if ( is_singular() ) {
	echo '&nbsp;-&nbsp;'; global $post;
	if ( xiliml_new_list() ) xiliml_the_other_posts($post->ID,"Read this post in");
	}
}

/**
 * dynamic style for flag depending current list
 *
 * @since 1.0.2 - add #access
 *
 */
function twentytwelve_flags_style () {
	if ( class_exists('xili_language') ) {
		global $xili_language ;
		$language_xili_settings = get_option('xili_language_settings');
		if ( !is_array( $language_xili_settings['langs_ids_array'] ) ) {
			$xili_language->get_lang_slug_ids(); // update array when no lang_perma 110830 thanks to Pierre
			update_option( 'xili_language_settings', $xili_language->xili_settings );
			$language_xili_settings = get_option('xili_language_settings');
		}

		$language_slugs_list = array_keys ( $language_xili_settings['langs_ids_array'] ) ;
		$xili_theme_options = get_theme_xili_options() ; // see below


		?>
		<style type="text/css">
		<?php

		$path = get_stylesheet_directory_uri();

		$ulmenus = array();
		foreach ( $language_slugs_list as $slug ) {
			echo "ul.nav-menu li.menu-separator { margin:0; }\n";
			echo "ul.nav-menu li.lang-{$slug} { background: transparent url('{$path}/images/flags/{$slug}.png') no-repeat center 16px; margin:0;}\n";
			echo "ul.nav-menu li.lang-{$slug}:hover {background: transparent url('{$path}/images/flags/{$slug}.png') no-repeat center 17px !important;}\n";
			$ulmenus[] = "ul.nav-menu li.lang-{$slug} a";
		}
			echo implode (', ', $ulmenus ) . " {text-indent:-9999px; width:24px; }\n";
		?>
		</style>
		<?php

	}
}

/**
 * condition to filter adjacent links
 * @since 1.1.4
 *
 */

function is_xili_adjacent_filterable() {

	if ( is_search () ) { // for multilingual search
		return false;
	}
	return true;
}



/**
 *
 *
 */
function single_lang_dir($post_id) {
	$langdir = ((function_exists('get_cur_post_lang_dir')) ? get_cur_post_lang_dir($post_id) : array());
	if ( isset($langdir['direction']) ) return $langdir['direction'];
}

function twentytwelve_xili_credits () {
	/* translators: added in child functions by xili */
	printf( __("Multilingual child theme of twentytwelve by %s", 'twentytwelve' ),"<a href=\"http://dev.xiligroup.com\">dev.xiligroup</a> - " );
}

add_action ('twentytwelve_credits','twentytwelve_xili_credits');

/**
 * to avoid display of old xiliml_the_other_posts in singular
 * @since 1.1
 */
function xiliml_new_list() {
	if ( class_exists('xili_language') ) {
		global $xili_language;

		$xili_theme_options = get_theme_xili_options() ;

		if ( $xili_theme_options['linked_posts'] == 'show_linked' ) {
			if (is_page() && is_front_page() ) {
				return false;
			} else {
				return true;
			}
		}

		if ( is_active_widget ( false, false, 'xili_language_widgets' ) ) {

			$xili_widgets = get_option('widget_xili_language_widgets', array());
			foreach ( $xili_widgets as $key => $arrprop ) {
				if ( $key != '_multiwidget' ) {
					if ( $arrprop['theoption'] == 'typeonenew' ) {	// widget with option for singular
						if ( is_active_widget( false, 'xili_language_widgets-'.$key, 'xili_language_widgets' ) ) return false ;
					}
				}
			}
		}

		if ( XILILANGUAGE_VER > '2.0.0' && isset($xili_language -> xili_settings['navmenu_check_options']['primary']) && in_array ('navmenu-1', $xili_language -> xili_settings['navmenu_check_options']['primary']) ) return false ;

	}
	return true ;

}



?>