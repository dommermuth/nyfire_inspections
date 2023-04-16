<?php

	$margin_left = get_sub_field('margin_left');
	$margin_top = get_sub_field('margin_top');
	$margin_right = get_sub_field('margin_right');
	$margin_bottom = get_sub_field('margin_bottom');
	$image_field_repeater = get_sub_field('fields');

	if( have_rows($image_field_repeater, $pid) ){

		$conditions_true_ar = [];
		echo '<div class="image-container">';
		while( have_rows($image_field_repeater, $pid) ) :

			the_row();
			$image = get_sub_field('image');
			$image_description = get_sub_field('image_description');

			echo '<div class="image"><img src="'.$image["url"].'" />';
			echo '<p class="image-description">'. $image_description . '</p></div>';


		endwhile;
		echo '<div style="clear:both"></div>';
		echo '</div>';
		//

	}


?>
