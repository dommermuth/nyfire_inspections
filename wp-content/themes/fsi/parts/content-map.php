<?php 

    global $post;
	$postid = $post->ID;

?>

<div id="map-section-1" >

	<?php

	$post_object = get_post( $postid );
	echo $post_object->post_content;

	?>

	<?php

		if( have_rows('flexible_content') ):

			while ( have_rows('flexible_content') ) :
				the_row();

				if( get_row_layout() == 'HTML' ):

					$html = get_sub_field('html');
					echo $html;

				elseif( get_row_layout() == 'gallery_carousel' ):

					include_once("gallery-carousel.php");

				elseif( get_row_layout() == 'testimonial_carousel' ):

					include_once("testimonial-carousel.php");

				elseif( get_row_layout() == 'locations_map' ):

					include_once("map.php");

				elseif( get_row_layout() == 'hospitals_by_region'):

						include_once("hospitals_by_region.php");

				endif;

			endwhile;

		else :
			// Do something.
		endif;

	?>

</div>