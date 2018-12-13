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
 * @author     Team WPGenius <deepak@wpgenius.in>
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
    
    public function video_post_type() {

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

		register_post_type( 'videos-gallery', $video_args );

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
  
  		register_taxonomy( 'gallery-video-albums', array( 'videos-gallery'), $args );

	}

    public function video_meta_box(){
        
		add_meta_box( 'youtube-video-link', __(  'Video'), array($this, 'video_post_meta_callback'), 'videos-gallery', 'advanced', 'high');
    }

    function video_post_meta_callback($post){

		$value = get_post_meta($post->ID, 'youtube_link', true); ?>

		<table class="form-table cmb_metabox">
			<tbody>
				<tr class="cmb-type-text cmb_id_themestudio_custom_video_link">
					
                    <th style="width:13%">
						<label for="youtube_link">Enter Video Link</label>
					</th>
					<td>
						<input style="width:60%; padding:10px !important;" type="text" id="youtube_link" name="youtube_link" value="<?php echo $value; ?>" /><br>
						<label>Please enter video link.</label>
					</td>
				</tr>
                
                <tr>
                    <th>
                        <label for="video_popup">Check if URL is YouTube Channel :</label> 
                    </th>
                    <td><?php $checkboxMeta = get_post_meta( $post->ID );?>
                        <input type="checkbox" name="channel_check" id="channel_check" value="yes" <?php if ( isset ( $checkboxMeta['channel_check'] ) ) checked( $checkboxMeta['channel_check'][0], 'yes' ); ?> />
                    
                    </td>
                </tr>
                
                <tr>
                    <th>
                        <label for="video_width">Width:</label> 
                    </th>
                    <td>
                     <input type="number" name="video_width" id="video_width" value="<?php echo (get_post_meta( $post->ID,'video_width', true ))? get_post_meta( $post->ID,'video_width', true ):''; ?>"><br/><br/>
                    
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="video_popup">Check for video popup:</label> 
                    </th>
                    <td><?php $checkboxMeta = get_post_meta( $post->ID );?>
                        <input type="checkbox" name="video_popup" id="video_popup" value="yes" <?php if ( isset ( $checkboxMeta['video_popup'] ) ) checked( $checkboxMeta['video_popup'][0], 'yes' ); ?> />
                    
                    </td>
                </tr>
                
                <td>
                
            </tbody>
		</table><?php
	}

    public function video_save_post_meta( $post_id ){

		if(isset($_POST['youtube_link']) && $_POST['youtube_link'] != ''){

			$mydata =  $_POST['youtube_link'];
			update_post_meta($post_id, 'youtube_link', $mydata);
		}
        
        if($_POST["video_width"]){
            $width = $_POST["video_width"];
            update_post_meta( $post_id,'video_width' , $width);
        }
        
         
        
        if( isset( $_POST[ 'video_popup' ] ) ) {
        	update_post_meta( $post_id, 'video_popup', 'yes' );
        } else {
        	update_post_meta( $post_id, 'video_popup', 'no' );
        }  
        
        
        if( isset( $_POST[ 'channel_check' ] ) ) {
        	update_post_meta( $post_id, 'channel_check', 'yes' );
        } else {
       	 	update_post_meta( $post_id, 'channel_check', 'no' );
        } 
        
        $videoID = get_post_meta(get_the_ID(), 'youtube_link', true); 
        $API_key    = 'AIzaSyAvN-IZc_qQRoTdLa4of-4gMSZp7sP_ZYw';
        $channel="";
        if(preg_match("/\buser\b/i", $videoID) || preg_match("/\bchannel\b/i", $videoID)){
            $channel="true";
        }
        if(preg_match("/\bwatch\b/i", $videoID) || preg_match("/\byoutu.be\b/i", $videoID)){
            $channel="false";
        }
        
          if (preg_match("/\buser\b/i", $videoID))
                                {
                                    $cparts = explode("user/", $videoID);
                                    $c_video  = $cparts[1];
                                    $channel_ids = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/channels?key='.$API_key.'&forUsername='.$c_video.'&part=id'));

                                        foreach($channel_ids->items as $ch_id){

                                            if($ch_id->id){

                                             $channelID = $ch_id->id;

                                            } 
                                        }  

                                  } 
        
            elseif (preg_match("/\bchannel\b/i", $videoID))
                                {   
                                $cparts = explode("channel/", $videoID); 
                                $channelID  = $cparts[1];
                                }
            
       
         if (preg_match("/\bwatch\b/i", $videoID)){
         	
                                $parts = explode("?v=", $videoID);
                                $channelID=$parts[1];

                                if(isset($channelID) && !empty($channelID) && is_array($channelID) && count($channelID)>1){

                                $params = explode("&", $channelID);

                                if(isset($params) && !empty($params) && is_array($params)){

                                foreach($params as $param){
                                    $kv = explode("=", $param);
                                    if(isset($kv) && !empty($kv) && is_array($kv) && count($kv)>1){
                                        if($kv[0]=='v'){
                                        $channelID = $kv[1];
                                        $flag = true;
                                        break;
                                        }
                                        }

                                    }

                                }
                            }
                        }
            
        elseif (preg_match("/\byoutu.be\b/i", $videoID))
                                {
                                        if(!$flag){
                                        $needle = "youtu.be/";
                                        $pos = null;
                                        $pos = strpos($videoID, $needle);
                                        if ($pos !== false) {
                                        $start = $pos + strlen($needle);
                                        $channelID = substr($videoID, $start, 11);
                                        }           
                                    }   
                                }
    
    $ytube = $channelID;      
    update_post_meta( $post_id,'channel_youtube' , $ytube);

  }
    
}
