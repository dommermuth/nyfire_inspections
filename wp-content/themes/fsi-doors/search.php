<?php get_header(); ?>
			
	<div id="content">

		<div id="inner-content-news" class="row">
	
			<main id="main" class="first" role="main">
				<header class="search-header">
					<h1 class="archive-title"><?php _e( 'Search Results for:', 'jointswp' ); ?> <?php echo esc_attr(get_search_query()); ?></h1>
				</header>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			 
					<!-- To see additional archive styles, visit the /parts directory -->
					<?php get_template_part( 'parts/loop', 'archive' ); ?>					
				    
				<?php endwhile; ?>	

				<?php posts_nav_link('&nbsp;|&nbsp;','Previous','Next'); ?>
					
				<?php else : ?>
				
					<?php get_template_part( 'parts/content', 'missing' ); ?>
						
			    <?php endif; ?>
	
		    </main> <!-- end #main -->

			<?php if (have_posts()) : ?>
		
		    <?php get_sidebar(); ?>

			<?php endif; ?>
		
		</div> <!-- end #inner-content-news -->

	</div> <!-- end #content -->

<?php get_footer(); ?>
