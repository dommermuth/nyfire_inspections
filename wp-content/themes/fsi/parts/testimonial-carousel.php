<?php

	global $post;
	$postid = $post->ID;
	//$min_height = get_sub_field("gallery_carousel_min_height");
	//$number_of_images_to_show = get_sub_field("number_of_images_to_show");

?>

<div id="testimonial-carousel">

	<?php

		if(have_rows("testimonials")) :


			while( have_rows('testimonials') ):
				the_row();

				$testimonial = get_sub_field('testimonial');
				$attributed_to = get_sub_field('attributed_to');
	?>
				<blockquote><span>
				<p class="testimonial"><?php echo $testimonial; ?></p>
				<p class="attribution"><?php echo $attributed_to; ?></p>
				</span>
				</blockquote>

		<?php
			endwhile;


		endif;
        ?>


</div>
<script>

jQuery(function() {
	jQuery('#testimonial-carousel').slick({
		arrows: true,
		dots: true,
		slidesToShow: 1,
		autoplay: true,
		prevArrow:"<img src='<?php echo get_template_directory_uri(); ?>/assets/images/carousel_left.png' class='slick-prev pull-left'>",
		nextArrow: "<img src='<?php echo get_template_directory_uri(); ?>/assets/images/carousel_right.png' class='slick-next pull-right'>",
		customPaging: function(slider, i) {
            return '<div class="custom-slick-dots" id=' + i + "></div>";
        }

	});
});

</script>