<?php
/**
 * @package Hello_Dolly
 * @version 1.7.2
 */
/*
Plugin Name: Properties
Plugin URI: http://bellaworksweb.com
Description: Creates the Property page.
Author: Austin Crane
Version: 1.0
Author URI: https://bellaworksweb.com
*/

/**
 * Enqueue scripts and styles.
 */
function bellaworks_scripts() {
	wp_enqueue_style( 
		'bellaworks-style',
		 plugin_dir_url( __FILE__ ) . "style-properties.css",
		 array(),
		 '1.0'
	);
	
	wp_enqueue_script( 
			'colorbox', 
			plugin_dir_url( __FILE__ ) . 'assets/js/vendors/colorbox.js', 
			array(), '1.0', 
			true 
	);
	wp_enqueue_script( 
			'custom', 
			plugin_dir_url( __FILE__ ) . 'assets/js/custom.js', 
			array(), '1.0', 
			true 
	);

}
add_action( 'wp_enqueue_scripts', 'bellaworks_scripts' );


/**
 *   Add Shortcode
 */
add_shortcode( 'properties', 'properties_func' );
function properties_func( $atts ) {

  $output = '';
  ob_start();
 

  	$terms = get_terms('location');
  	// echo '<pre>';
  	// print_r($terms);
  	$i = 0;
  	foreach( $terms as $term ) {
  		
  		$i++; 
  		$wp_query = new WP_Query();
		$wp_query->query(array(
			'post_type'=>'property',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'location', 
					'field' => 'slug',
					'terms' => array( $term->slug ) 
				)
			)
	));
	if ($wp_query->have_posts()) : ?>

		
		<h4 class="uagb-heading-text capitalize"><?php echo $term->slug; ?></h2>
		
		<section class="prop-row">

		<?php while ($wp_query->have_posts()) : $wp_query->the_post(); 

			$link = get_field('link');
			$image = get_field('image');

		?>

		
			

			<div class="property">
				<a class="<?php echo 'group-'.$i; ?>" href="<?php echo $image['url']; ?>" title="<?php the_title(); ?>">
					<div class="overlay">
						<?php the_title(); ?>
					</div>
					
					<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" >
				</a>
				<?php if( $link ) { ?>
					<div class="link"><a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a></div>
				<?php } ?>
			</div>

			
		

	<?php endwhile; ?>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {
		$("<?php echo '.group-'.$i; ?>").colorbox({
			rel:"<?php echo 'group-'.$i; ?>",
			width:"80%", height:"80%"
		});
		});

	</script>
	</section>
<?php endif; ?>
  	<?php }


  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}