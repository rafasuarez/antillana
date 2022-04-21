<?php
function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ), filemtime(get_stylesheet_directory() . '/style.css') );
	wp_enqueue_script( 'antillana-js', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), filemtime(get_stylesheet_directory() . '/main.min.js'), true );
	
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

function delete_post_type(){
	unregister_post_type( 'avada_faq' );
	unregister_post_type( 'avada_portfolio' );
}
add_action('init','delete_post_type',100);

/*
* Creating a function to create our CPT
*/
 
function testimonial_post_type() {
 
	// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Testimonials', 'Post Type General Name', 'Connections' ),
			'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'Connections' ),
			'menu_name'           => __( 'Testimonials', 'Connections' ),
			'parent_item_colon'   => __( 'Parent Testimonials', 'Connections' ),
			'all_items'           => __( 'All Testimonials', 'Connections' ),
			'view_item'           => __( 'View Testimonials', 'Connections' ),
			'add_new_item'        => __( 'Add New Testimonial', 'Connections' ),
			'add_new'             => __( 'Add New', 'Connections' ),
			'edit_item'           => __( 'Edit Testimonial', 'Connections' ),
			'update_item'         => __( 'Update Testimonial', 'Connections' ),
			'search_items'        => __( 'Search Testimonial', 'Connections' ),
		);
		 
	// Set other options for Custom Post Type
		 
		$args = array(
			'label'               => __( 'Testmonial', 'Connections' ),
			'description'         => __( 'Testimonial', 'Connections' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail'),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest' => true,
	 
		);
		 
		// Registering your Custom Post Type
		register_post_type( 'testimonials', $args );
	 
	}
	/* Hook into the 'init' action so that the function
	* Containing our post type registration is not 
	* unnecessarily executed. 
	*/
	 
add_action( 'init', 'testimonial_post_type', 0 );
/*
**** TAXONOMY categorias-tutoriales ****
*/
function testimonial_taxonomies() {
	register_taxonomy( 'testimonials-categories',
	array (0 => 'testimonials'),
	array(
		'hierarchical'      => true,
		'label'             => __('Testimonial Location','Connections'),
		'show_ui'           => true,
		'query_var'         => true,
		'show_admin_column' => true
	) );
}
add_action( 'init', 'testimonial_taxonomies');
add_action( 'init', 'testimonial_taxonomies_tag');

function showTestimonials($atts){
	$a = shortcode_atts( array(
		'location' => ''
	 ), $atts );	
	$the_query = new WP_Query( array(
		'post_type' => 'testimonials',
		/*'tax_query' => array(
			array (
				'taxonomy' => 'testimonials-categories',
				'field' => 'slug',
				'terms' => $a['location'],
			)
		),*/
	) );
	ob_start();
	$posts = $the_query->posts;
?>
<div class="testimonials-carousel">
	<?php
foreach($posts as $post) {
	$featured_img_url = get_the_post_thumbnail_url($post->ID,'thumbnail'); 
?>

	<div class="col-lg-12 box-testimonials text-center">
		<p class="testimonial-rating"><?php for($i = 0; $i<5 ; $i++){?><i class="fas fa-star"></i><?php }?> </p>
		<div class="testimonal-content"><?php echo $post->post_content ?></div>
		<p class="author"><img src="<?php echo $featured_img_url;?>" class="testimonial-profile"
				alt="<?php echo $post->post_title ?>"><?php echo $post->post_title ?></p>

	</div>
	<?php }?>
</div>
<?php
wp_reset_postdata();			
$output = ob_get_contents(); 
ob_end_clean(); 
return $output;
}
add_shortcode('testimonials', 'showTestimonials');