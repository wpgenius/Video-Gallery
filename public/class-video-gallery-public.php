<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       wpgenius.in
 * @since      1.0.0
 *
 * @package    Video_Gallery
 * @subpackage Video_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Video_Gallery
 * @subpackage Video_Gallery/public
 * @author     Team WPGenius <deepak@wpgenius.in>
 */
class Video_Gallery_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/video-gallery-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/video-gallery-public.js', array( 'jquery' ), $this->version, false );

	}
    
     public function video_shortcode($atts){
        
        $a = shortcode_atts(
            array(
                'cat'   => null,
            )
        , $atts
        );
   	 
         // var_dump($atts);die;
    $args = array(
            'post_type' => 'videos-gallery',
            'tax_query' => array(
					
                    array(
                        'taxonomy' => 'gallery-video-albums',
                        'field'    => 'slug',
                        'terms'    => $a['cat'],
                    ),
                 )
					
                );
	

		$query = new WP_Query( $args );

		
		if( $query->have_posts() ){
			
			$html = '';
			
			while ( $query->have_posts() ){
				
              $query->the_post();
              $html .= '<br><h6 style="color:orange;">'.get_the_title().'</h6>';
                
                    // Get the video URL and put it in the $video variable
                    $videoID = get_post_meta(get_the_ID(), 'mj_video_post_meta_value', true);
                    
                    // Get the video width and put it in the $videoWidth variable
                    $videoWidth = get_post_meta(get_the_ID(), 'video_width', true);
                
                
                    //var_dump($videoHeight);die;
                
                // Check if there is in fact a video URL
                if($videoID){
                    // embed code via oEmbed
                    $html .=  wp_oembed_get( $videoID,array( 'width'=> $videoWidth, )  ); 
                }
                  
				
			}
			return $html.'<hr />';
			
		}else{
			return 'No Video found';
		}
		
    }
    
    public function register_shortcode(){
        
         add_shortcode( 'video', array( $this, 'video_shortcode') );
    } 

}
