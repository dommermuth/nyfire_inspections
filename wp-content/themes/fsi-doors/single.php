<?php get_header();  wp_reset_postdata(); ?>
			
<div id="content">

	<div id="inner-content-news" class="row">

		<main id="main" class="" role="main">
		
		    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		    	<?php get_template_part( 'parts/loop', 'single' ); ?>
		    	
		    <?php endwhile; else : ?>
		
		   		<?php get_template_part( 'parts/content', 'missing' ); ?>

		    <?php endif; ?>

			<div class="post-buttons">
				<span class="previous-button">
					<?php previous_post_link( '%link','<i class="fa fa-arrow-left" aria-hidden="true"></i> Previous' ) ?>
				</span>
				<span class="next-button">
					<?php next_post_link( '%link','Next <i class="fa fa-arrow-right" aria-hidden="true"></i>' ) ?>
				</span>
			</div>

		</main> <!-- end #main -->

		<?php get_sidebar(); ?>


		

	</div> <!-- end #inner-content -->

	

</div> <!-- end #content -->



<?php get_footer(); ?>