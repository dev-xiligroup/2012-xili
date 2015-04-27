<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in either sidebar, hide them completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
if ( class_exists('xili_language') ) {
	$options = get_theme_xili_options();
	$curlang = ( xili_curlang() == 'en_us' || xili_curlang() == "" ) ? '' : '_'.xili_curlang() ;

		if ( $curlang != '' && !isset( $options['sidebar_'.'sidebar-1'] ) ) $curlang = '' ; //display default - no clone
	} else {
		$curlang = '' ;
}
?>

	<?php if ( is_active_sidebar( 'sidebar-1' . $curlang ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' . $curlang ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>