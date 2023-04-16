<?php get_header(); ?>
			
	<div id="content">
	
		<div id="inner-content-news" class="row expanded">
	
		    <main id="main" class="" role="main">
            
            	<?php if (have_posts()) { while (have_posts()) : the_post(); ?>
			 
					<!-- To see additional archive styles, visit the /parts directory -->
					<?php get_template_part( 'parts/loop', 'archive' ); ?>
				    
				<?php endwhile; ?>	

				<?php posts_nav_link('&nbsp;|&nbsp;','Previous','Next'); ?>
								
				<?php } else { ?>
											
					<?php get_template_part( 'parts/content', 'missing' ); ?>
						
				<?php } ?>
																								
		    </main> <!-- end #main -->

		</div> <!-- end #inner-content-news -->

	</div> <!-- end #content -->

<?php get_footer(); ?>