<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<h3 class="menu-toggle"><?php _e( 'Menu', 'twentytwelve' ); ?></h3>
			<a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentytwelve' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
		</nav><!-- #site-navigation -->

		<?php 
		$header_image_url = get_header_image();
		$xili_theme_options = get_theme_xili_options() ; 
		if ( $header_image_url ) : 
			
			if ( class_exists ( 'xili_language' ) && isset ( $xili_theme_options['xl_header'] ) &&  $xili_theme_options['xl_header'] ) { 
				global $xili_language, $xili_language_theme_options ; 
				$xili_theme_options = get_theme_xili_options() ;
				// check if image exists in current language
				// 2013-10-10 - Tiago suggestion
				$curlangslug = ( '' == the_curlang() ) ? strtolower( $xili_language->default_lang ) :  the_curlang() ;
				
				$headers = get_uploaded_header_images(); // search in uploaded header list
					
				$this_default_headers = $xili_language_theme_options->get_processed_default_headers () ;
				if ( ! empty( $this_default_headers ) ) {
					$headers = array_merge( $this_default_headers, $headers );
				}	
				
				foreach ( $headers as $header_key => $header ) {
						
					if ( isset ( $xili_theme_options['xl_header_list'][$curlangslug] ) && $header_key == $xili_theme_options['xl_header_list'][$curlangslug] ) {
						$header_image_url =  $header['url'];
						break ;
					}
				}
			}
		
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo $header_image_url; ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
		
		
		<?php endif; ?>
	</header><!-- #masthead -->

	<div id="main" class="wrapper">