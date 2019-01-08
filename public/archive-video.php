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
		
			<div class="section">
				<div class="section_wrapper clearfix"> 
					<div class="cm_videos">
						<div class="container"> <?php
							$count = 0;
							while ( have_posts() ) : the_post();
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
												echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$youtube_id.'?rel=0&amp;showinfo=0" 
														frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
											echo '</div>'; ?>
											<h4><?php the_title(); ?></h4> <?php
										} ?>
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