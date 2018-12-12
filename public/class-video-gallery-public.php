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
    
     public function video_shortcode($atts){
        
        $a = shortcode_atts(
            array(
                'cat'   => null,
                'columns'=>null,
            )
        , $atts
        );
   	 
        // var_dump($a['columns']);die;
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
         $count=0;

		if( $query->have_posts() ){
			
			$html = '';
            $html.= '<div class="row">'; 
			while ( $query->have_posts() ){

              $query->the_post();
            
                $videoID = get_post_meta(get_the_ID(), 'channel_youtube', true);
                $API_key    = 'AIzaSyAvN-IZc_qQRoTdLa4of-4gMSZp7sP_ZYw';
                $maxResults = 20;
                $videoWidth = get_post_meta(get_the_ID(), 'video_width', true);
                
                 
               if(get_post_meta(get_the_ID(), 'channel_check', true)=='yes')
                {
                     $videoList = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$videoID.'&maxResults='.$maxResults.'&key='.$API_key.''));

                    if(get_post_meta(get_the_ID(), 'video_popup', true)=='yes'){
                       
                                 foreach($videoList->items as $item){
                                    $count++;
                                    //Embed video
                                    $html.=  '<div class="column-'.$a['columns'].'">';
                                    $html.= '<a href="www.youtube.com/watch?v='.$item->id->videoId.'" class="popup-youtube"> 
                                    <img  src="http://img.youtube.com/vi/'.$item->id->videoId.'/0.jpg"              width="'.$videoWidth.'" /><p>'.$item->snippet->title.'</p> </div>';

                                     if($count%$a['columns']==0){
                                     $html.='<div class="clearfix"></div>';
                                     }

                        } 

                    }else{
                                 foreach($videoList->items as $item){
                                    $count++;
                                    //Embed video
                                    $html.=  '<div class="column-'.$a['columns'].'">';
                                    $html.=  '<iframe width="'.$videoWidth.'" height="200" src="https://www.youtube.com/embed/'.$item->id->videoId.'" frameborder="0" allowfullscreen></iframe><p>'. $item->snippet->title .'<p> </div>';

                                     if($count%$a['columns']==0){
                                     $html.='<div class="clearfix"></div>';
                                     }

                        } 

                    }
                   
               } else {
                   
                if(get_post_meta(get_the_ID(), 'video_popup', true)=='yes'){
                        $count++;
                       
                        
                        $html.=  '<div class="column-'.$a['columns'].'">';
                        $html.= '<a href="www.youtube.com/watch?v='.$videoID.'" class="popup-youtube"> 
                        <img  src="http://img.youtube.com/vi/'.$videoID.'/0.jpg"  width="'.$videoWidth.'" />';
                        $html.= '</a>';
                        $html.=  get_the_title(get_the_id());
                        $html.='</div>';
                        
                    }else{
                        $count++;
                        $html.=  '<div class="column-'.$a['columns'].'">';

                        //$html.= wp_oembed_get( $videoID,array( 'width'=> $videoWidth, )  ); 
                        $html.= '<iframe width="'.$videoWidth.'" height="200" src="https://www.youtube.com/embed/'.$videoID.'"></iframe>';
                        $html.=  get_the_title(get_the_id());
                        $html.='</div>';
                }
                   
                   
               }   
                if($count%$a['columns']==0){
                        $html.='<div class="clearfix"></div>';
                }
                  
            }
			$html.=  '</div>';
            return $html;

         
        }
         
     }
    public function register_shortcode(){
        
         add_shortcode( 'video', array( $this, 'video_shortcode') );
    } 

}
