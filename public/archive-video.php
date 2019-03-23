<?php
/**
 * Video Archives
 */

get_header(); 

?>

<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
			<?php //Breadcurmb
				cmp_show_breadcrumb(); ?>
			<div class="section">
				<div class="section_wrapper clearfix"> 
					<div class="cm_videos">
						<div class="container"> <?php
							$count = 0;
							while ( have_posts() ) : the_post();
								global $post;
								$video_link = get_post_meta(get_the_ID(),'mj_video_post_meta_value', true);
								$youtube_id = explode('?v=', $video_link);
								if(sizeof($youtube_id) != 1){
									$youtube_id = $youtube_id['1'];
									$count++;
									
									
									
									
									 ?>
									<div class="column wpg_column-3">
										<?php
                                            if($youtube_id){
                                            echo '<div class="cm_youtube_video">';
                                            echo '<a class="popup-youtube" href="http://www.youtube.com/watch?v='.$youtube_id.'" > 
                                                    <img  src="https://img.youtube.com/vi/'.$youtube_id.'/0.jpg"  width="560" height="315"/></a>';
                                            echo '</div>'; ?>
											<h4 class="title"><?php the_title(); ?></h4> <?php
                                                
                                                $categories = get_the_terms( $post->ID, 'gallery-video-albums' );
											if($categories) {
												foreach( $categories as $category ) {
													echo '<h5 class="category-title">'.$category->name.'</h5>';
												}
											}
                                    }
										/*if($youtube_id){
											echo '<div class="cm_youtube_video">';
												echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$youtube_id.'?rel=0&amp;showinfo=0" 
														frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
											echo '</div>'; ?>
											<h4 class="title"><?php the_title(); ?></h4> <?php
											
											$categories = get_the_terms( $post->ID, 'gallery-video-albums' );
											if($categories) {
												foreach( $categories as $category ) {
													echo '<h5 class="category-title">'.$category->name.'</h5>';
												}
											}
											
										}*/ ?>
									</div>
									<?php 
									if($count%3 == 0) 
										echo '<div class="clearfix"></div>';
								}
							endwhile;

							if(function_exists( 'mfn_pagination' )):
								echo mfn_pagination();
								else: ?>
									<div class="nav-next"><?php next_posts_link(__('&larr; Older Entries', 'betheme')) ?></div>
									<div class="nav-previous"><?php previous_posts_link(__('Newer Entries &rarr;', 'betheme')) ?></div> <?php
							endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- .four-columns - sidebar -->
		<div class="sidebar four columns">
			<div class="widget-area clearfix lines-boxed" style="min-height: 1348px;">
				<?php 
					dynamic_sidebar("sidebar-video-gallery-left-sidebar");
				 ?>
			</div>
		</div>
		
	</div>
</div>

<?php get_footer();