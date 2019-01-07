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
        wp_enqueue_style( 'magnific-popup', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( 'jquery-magnific-popup', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/video-gallery-public.js', array( 'jquery' ), $this->version, false );

	}
    
     public function wpg_video_shortcode($atts){
        
        $a = shortcode_atts(array('cat' => '','columns'=> '1'),$atts);

        $iftax ='';
       	if ($a['cat']!='') {
           
          $iftax = array(
                        
                        array(
                            'taxonomy' => 'gallery-video-albums',   
                            'field'    => 'slug',
                            'terms'    => $a['cat'],
                        )
                    );
       }
       	$args = array( 
       				'post_type' => 'videos-gallery',
            	);
           

        
		$query = new WP_Query( $args );
         $count=0;

		if( $query->have_posts() ){
		
			$html = '';
            $html.= '<div class="wpg_row">'; 
			while ( $query->have_posts() ){
				$query->the_post();
              //var_dump($args);
            
                $videoID = get_post_meta(get_the_ID(), 'channel_youtube', true);
                $API_key    = 'AIzaSyAvN-IZc_qQRoTdLa4of-4gMSZp7sP_ZYw';
                $maxResults = 20;
                $videoWidth = get_post_meta(get_the_ID(), 'video_width', true);

               if(get_post_meta(get_the_ID(), 'channel_check', true)=='yes'){
                     $videoList = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$videoID.'&maxResults='.$maxResults.'&key='.$API_key.''));

                    if(get_post_meta(get_the_ID(), 'video_popup', true)=='yes'){
                       
                                 foreach($videoList->items as $item){
                                    $count++;
                                    //Embed video
                                    $html.=  '<div class="wpg_column-'.$a['columns'].'">';
                                    $html.= '<a href="www.youtube.com/watch?v='.$item->id->videoId.'" class="popup-youtube"> 
                                    <img  src="http://img.youtube.com/vi/'.$item->id->videoId.'/0.jpg"              width="'.$videoWidth.'" /><p>'.$item->snippet->title.'</p> </div>';

                                     if($count%$a['columns']==0){
                                     $html.='<div class="wpg_clearfix"></div>';
                                     }

                        } 

                    }else{
                                 foreach($videoList->items as $item){
                                    $count++;
                                    //Embed video
                                    $html.=  '<div class="wpg_column-'.$a['columns'].'">';
                                    $html.=  '<iframe width="'.$videoWidth.'"  src="https://www.youtube.com/embed/'.$item->id->videoId.'" frameborder="0" allowfullscreen></iframe><p>'. $item->snippet->title .'<p> </div>';

                                     if($count%$a['columns']==0){
                                     $html.='<div class="wpg_clearfix"></div>';
                                     }

                        } 

                    }
                   
               } else {
                if(get_post_meta(get_the_ID(), 'video_popup', true)=='yes'){
                        $count++;
                        $html.=  '<div class="wpg_column-'.$a['columns'].'">';
                        $html.= '<a href="http://www.youtube.com/watch?v='.$videoID.'" class="popup-youtube"> 
                        <img  src="https://img.youtube.com/vi/'.$videoID.'/0.jpg"  width="'.$videoWidth.'" />';
                        $html.= '</a>';
                        $html.=  "<h4>".get_the_title(get_the_id())."</h4>";
                        $html.='</div>';
                        
                    }else{
                        $count++;
                        $html.=  '<div class="wpg_column-'.$a['columns'].'">';

                        //$html.= wp_oembed_get( $videoID,array( 'width'=> $videoWidth, )  ); 
                        $html.= '<iframe width="'.$videoWidth.'" src="https://www.youtube.com/embed/'.$videoID.'"></iframe>';
                        $html.=  "<h4>".get_the_title(get_the_id())."</h4>";
                        $html.='</div>';
                }
                   
                   
               }   
                if($count%$a['columns']==0){
                        $html.='<div class="wpg_clearfix"></div>';
                }
                  
            }
			$html.=  '</div>';
            return $html;

         
        }
         
     }
    public function wpg_register_shortcode(){
        
        add_shortcode( 'wpg-video', array( $this, 'wpg_video_shortcode') );
    }

    function load_photo_template($template) {
    	global $post;
		if ( is_post_type_archive('videos-gallery') ){
		    include (plugin_dir_path( __FILE__ ) . "archive-video.php");
            exit;
		}
	}
	
/*	function chanakya_trustee_shortcode_callback( $atts ){

		$atts = extract(shortcode_atts( array(
			'trustee_name' => '',
			'trustee_image' => '',
			'trustee_ldesignation' => '',
			'trustee_cdesignation' => '',
			'trustee_description' => ''
			), $atts,'trustee_data'));

	    $imageSrc = wp_get_attachment_image_src($trustee_image, 'trustee_thumbnails');
	    $output = '';
	    $output .= '<div class="trustee vc_row wpb_row vc_row-fluid">';
	    if ($trustee_description !="") {
			$output .='<div class="has_descp vc_col-sm-6 vc_col-xs-12">';
	    }
	    $output .='<div class="trustee_pic vc_col-xs-5"><div class="trustee_image"><img src="' . $imageSrc[0] . '" /></div></div> <div class="details_info vc_col-xs-7"><div class="trustee_details">';
	    $output .='<div class="trustee_name"><h4>'.$trustee_name.'</h4></div>';
	    $output .='<div class="trustee_ldesignation"><span>'.$trustee_ldesignation.'</span></div>';
	    $output .='<div class="trustee_cdesignation"><p>'.$trustee_cdesignation.'</p></div>';
	    $output .='</div></div>';
	    if ($trustee_description !="") {
			$output .='</div> <div class="trustee_desc vc_col-sm-6 vc_col-xs-12"> <div class="trustee_description"><p>'.$trustee_description.'</p></div></div>';
	    }
	    $output .='</div>';
	    $output .='';

	  return $output;
	}
	add_shortcode( 'chanakya_trustee', 'chanakya_trustee_shortcode_callback' );

	add_action( 'init', 'chanakya_vc_addon' );

	function chanakya_vc_addon(){

		vc_map( 
			array(
			
				"name" => __("Chanakya Trustee", 'vc_extend'),
				"description" => __("Chanakya Trustee shortcode is used to display carousel of brand logo's.", 'vc_extend'),
				"base" => "chanakya_trustee",
				"class" => "",
				"controls" => "full",
				//"icon" =>  get_stylesheet_directory() . 'img/asterisk_yellow.png', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
				"category" => __('Chanakya Blocks', 'js_composer'),
				//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
				//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
				"params" => array(
			
				  array(
					  "type" => "textfield",
					  "holder" => "div",
					  "class" => "",
					  "heading" => __("Trustee Name",'Chanakya_Mandal'),
					  "param_name" => "trustee_name",                             
					  "description" => "Enter Trustee Name",         
					  ),
				  array(
					  "type" => "attach_image",
					  "holder" => "div",
					  "class" => "",
					  "heading" => __("Trustee Image",'Chanakya_Mandal'),
					  "param_name" => "trustee_image",                             
					  "description" => "Select Trustee Image",         
					  ),
			
				   array(
					  "type" => "textfield",
					  "holder" => "div",
					  "class" => "",
					  "heading" => __("Last Designation",'Chanakya_Mandal'),
					  "param_name" => "trustee_ldesignation",                             
					  "description" => "Enter Trustee's Last Designation",         
					  ),
			
					array(
					  "type" => "textfield",
					  "holder" => "div",
					  "class" => "",
					  "heading" => __("Current Designation",'Chanakya_Mandal'),
					  "param_name" => "trustee_cdesignation",                             
					  "description" => "Enter Trustee's Current Designation",         
					  ),
			
					array(
					  "type" => "textarea",
					  "holder" => "div",
					  "class" => "",
					  "heading" => __("Description",'Chanakya_Mandal'),
					  "param_name" => "trustee_description",                             
					  "description" => "Enter Description",         
					  ), 
				)
			) 
		);    
	    
	}*/ 

}
