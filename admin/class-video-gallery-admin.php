<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       wpgenius.in
 * @since      1.0.0
 *
 * @package    Video_Gallery
 * @subpackage Video_Gallery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Video_Gallery
 * @subpackage Video_Gallery/admin
 * @author     Team WPGenius <mane.makarand@gmail.com>
 */
class Video_Gallery_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Video_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Video_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/video-gallery-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Video_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Video_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/video-gallery-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function vg_post_type() {

		$video_labels = array(
			 			'name' => _x( 'Videos','' ),
						'singular_name'         => _x( 'Video', '' ),	
						'menu_name'             => __( 'Video', '' ),
						'name_admin_bar'        => __( 'Video', '' ),
						'archives'              => __( 'Video Archive', '' ),
						'parent_item_colon'     => __( 'Parent Video:', '' ),		
						'all_items'             => __( 'All Videos', '' ),		
						'add_new_item'          => __( 'Add New Video', '' ),		
						'add_new'               => __( 'Add New Video', '' ),		
						'new_item'              => __( 'New Videos', '' ),		
						'edit_item'             => __( 'Edit Videos', '' ),		
						'update_item'           => __( 'Update Videos', '' ),		
						'view_item'             => __( 'View Videos', '' ),		
						'search_items'          => __( 'Search Videos', '' ),		
						'not_found'             => __( 'Not found', '' ),		
						'not_found_in_trash'    => __( 'Not found in Trash', '' ),		
						'featured_image'        => __( 'Featured Image', '' ),		
						'set_featured_image'    => __( 'Set thumbnail image', '' ),		
						'remove_featured_image' => __( 'Remove thumbnail image', '' ),		
						'use_featured_image'    => __( 'Use as thumbnail image', '' ),		
						'insert_into_item'      => __( 'Insert into Video', '' ),		
						'uploaded_to_this_item' => __( 'Uploaded to this Video', '' ),		
						'items_list'            => __( 'Video list', '' ),		
						'items_list_navigation' => __( 'Video list navigation', '' ),		
						'filter_items_list'     => __( 'Filter Video list', '' ),	
					);

		$video_args = array(	
						'label'                 => __( 'Videos', 'crezza' ),		
						'description'           => __( 'Videos', 'crezza' ),
						'labels'                => $video_labels,
						'supports'              => array( 'title', 'thumbnail'),
						'hierarchical'          => false,
						'public'                => true,
						'show_ui'               => true,
						'show_in_menu'          => true,
						'menu_position'         => 10,
						'menu_icon'             => 'dashicons-video-alt2',
						'show_in_admin_bar'     => true,
						'show_in_nav_menus'     => true,
						'can_export'            => true,
						'has_archive'           => true,
						'exclude_from_search'   => false,
						'publicly_queryable'    => true,
						/*'capability_type'       => 'page',*/
						'rewrite'				=> array(											
													'slug'                       => 'videos',											
													'with_front'                 => false,											
													'hierarchical'               => false,										
												),		
					);

		register_post_type( 'vg-videos', $video_args );

		$args = array( 

					'hierarchical' => true,
					'label' => 'Video Albums',
					'show_admin_column' => true, 
					'show_ui' => true, 
					'show_in_menu' => true,
					'public'=> true ,
					'publicly_queryable' => false , 
					'query_var' => true,
					'singular_label' => 'Video Album'

				);
  
  		register_taxonomy( 'vg-video-albums', array( 'vg-videos'), $args );

	}

	function vg_video_meta_box(){
	
		add_meta_box( 'youtube-video-link', __( 'YouTube Video'), array($this, 'vg_video_post_meta_callback'), 'vg-videos', 'advanced', 'high');

	}

	/*
		Mj video call back function
	*/
	function vg_video_post_meta_callback($post){

		$value = get_post_meta($post->ID, 'mj_video_post_meta_value', true); ?>

		<table class="form-table cmb_metabox">
			<tbody>
				<tr class="cmb-type-text cmb_id_themestudio_custom_video_link">
					<th style="width:13%">
						<label for="youtube_link">Video Link</label>
					</th>
					<td>
						<input style="width:55%; padding:10px !important;" type="text" id="youtube_link" name="youtube_link" value="<?php echo $value; ?>" placeholder="https://www.youtube.com/watch?v=M323Pos6UTQ"/><br>
						<label>Please enter only <b>YouTube</b> video link.</label>
					</td>
				</tr>
			</tbody>
		</table><?php
	}

	/*
		Mj video save meta
	*/
	public function vg_video_save_post_meta( $post_id ){

		if(isset($_POST['youtube_link']) && $_POST['youtube_link'] != ''){

			$mydata =  $_POST['youtube_link'];
			add_post_meta($post_id, 'mj_video_post_meta_value', $mydata);
			update_post_meta($post_id, 'mj_video_post_meta_value', $mydata);
		}

	}

	public function vg_add_submenu_page_to_post_type() {

	   add_submenu_page( 'edit.php?post_type=vg-videos',__('Video Options', ''),__('Video Options', ''), 'delete_posts','vg-video-settings', array($this, 'vg_options_display'));
	    
	}

	function vg_options_display(){
		echo 'test';
	}

}
