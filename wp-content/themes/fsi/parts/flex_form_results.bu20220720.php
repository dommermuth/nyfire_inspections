<?php

	$section_title = get_sub_field('section_title',$linked_page_id);
	$margin_left = get_sub_field('margin_left',$linked_page_id);
	$margin_top = get_sub_field('margin_top',$linked_page_id);
	$margin_right = get_sub_field('margin_right',$linked_page_id);
	$margin_bottom = get_sub_field('margin_bottom',$linked_page_id);
	$search_and_replace_ar = get_sub_field('fields',$linked_page_id);
	$alternate_output = get_sub_field('alternate_output',$linked_page_id);
	$image_field = get_sub_field('image_field',$linked_page_id);
	$image_field_2 = get_sub_field('image_field_2',$linked_page_id);
	$is_restaurant_or_hotel = get_sub_field('is_this_a_hotel_or_restaurant');
	$is_retail = get_sub_field('is_this_a_retail_establishment');

	if($search_and_replace_ar){
		foreach($search_and_replace_ar as $field){
			$replacement = get_field($field, $pid);
			$html = str_replace('{'.$field.'}', $replacement, $html);
		}
	}

	$failed_conditions = 0;
	$css_margin_width =  $margin_right + $margin_left;

	//if(!empty($alternate_output)){
	echo '<div class="text-container" style="margin:'.$margin_top.'px '.$margin_right.'px '. $margin_bottom.'px '.$margin_left.'px;">';
	echo '<h5>'.$section_title.'</h5>';
	//}

	if(!$is_restaurant_or_hotel && !$is_retail && !empty($image_field) && have_rows($image_field, $pid) ){

			echo '<div class="image-container">';
			while( have_rows($image_field, $pid) ) :

				the_row();
				$image = get_sub_field('image');
				$image_description = get_sub_field('image_description');

				echo '<div class="image"><img src="'.$image["url"].'" />';
				echo '<p class="image-description">'. $image_description . '</p></div>';

			endwhile;
			echo '<div style="clear:both"></div>';
			echo '</div>';
	}

	if($is_restaurant_or_hotel ) {

		$rest_rows = get_field("eating_establishments", $pid);		

		$row_index = 0;
		
		foreach($rest_rows as $rest_row){
			//var_dump($rest_row);			
			if( have_rows("section", $linked_page_id) ):
				//exit;
				echo "<ol style='border-bottom: 1px solid #666'>";
				while( have_rows("section",$linked_page_id) ) :
					the_row();

					$text_result = get_sub_field('text_result');			
					$search_and_replace_sub_ar = get_sub_field('fields_restaurant_hotel');
					$image_repeater = get_sub_field('fields_restaurant_hotel_images');

					if(!empty($image_repeater)) {
						echo "<li><strong>Images</strong>";
						echo '<div class="image-container" style="border-bottom:none">';
						foreach( $rest_rows[$row_index][$image_repeater] as $image_ar ) {
							$image = $image_ar['image'];
							$image_description = $image_ar['image_description'];
							echo '<div class="image" style="width:400px"><img src="'.$image["url"].'" />';
							echo '<p class="image-description">'. $image_description . '</p></div>';
						}
						echo '<div style="clear:both"></div>';
						echo '</div></li>';
					}else{
						$form_results = 0;
						foreach($search_and_replace_sub_ar as $subfield){
							$s_replacement =$rest_rows[$row_index][$subfield];
							$form_results = $form_results + strlen($s_replacement);
							$text_result = str_replace('{'.$subfield.'}', $s_replacement, $text_result);
							//echo $text_result."<br>";
						}

						if( have_rows("conditions") ){

							$condition_is_true = 1;
							while( have_rows("conditions") ) {

								the_row();
								$c_field = get_sub_field('fields_restaurant_hotel');
								$c_field_value = $rest_rows[$row_index][$c_field]; //get_field($c_field, $pid);
								$c_value = get_sub_field('value');
								if($c_value == "other"){
									$c_value = get_sub_field('value__other'); //a hand-typed value
								}

								if($c_field_value != $c_value && $c_value != "__hav__"){
									$condition_is_true = 0;
									continue;						
								}

								if($c_value == "__hav__" && !$form_results ){
									$condition_is_true = 0;
									continue;
								}
					
							}

							if($condition_is_true){
								$failed_conditions++;
								echo '<li>'.$text_result.'</li>';
							}

						}//end conditions repeater	

					}//not image

			endwhile; //end sections
			
			echo "</ol>";

			if(!$failed_conditions && !empty($alternate_output)){
				echo '<p>'.$alternate_output.'</p>';
			}

			endif;//end sections
			$row_index++;
		}
	}else if($is_retail) {
		$rest_rows = get_field("retail_establishments", $pid);		

		$row_index = 0;
		
		foreach($rest_rows as $rest_row){
			//var_dump($rest_row);			
			if( have_rows("section", $linked_page_id) ):
				//exit;
				echo "<ol style='border-bottom: 1px solid #666'>";
				while( have_rows("section",$linked_page_id) ) :
					the_row();

					$text_result = get_sub_field('text_result');			
					$search_and_replace_sub_ar = get_sub_field('fields_retail');
					$image_repeater = get_sub_field('fields_retail_images');


					if(!empty($image_repeater)) {
						echo "<li><strong>Images</strong>";
						echo '<div class="image-container" style="border-bottom:none">';
						foreach( $rest_rows[$row_index][$image_repeater] as $image_ar ) {
							$image = $image_ar['image'];
							$image_description = $image_ar['image_description'];
							echo '<div class="image" style="width:400px"><img src="'.$image["url"].'" />';
							echo '<p class="image-description">'. $image_description . '</p></div>';
						}
						echo '<div style="clear:both"></div>';
						echo '</div></li>';
					}else{
						$form_results = 0;
						foreach($search_and_replace_sub_ar as $subfield){
							$s_replacement =$rest_rows[$row_index][$subfield];
							$form_results = $form_results + strlen($s_replacement);
							$text_result = str_replace('{'.$subfield.'}', $s_replacement, $text_result);
							//echo $text_result."<br>";
						}

						if( have_rows("conditions") ){

							$condition_is_true = 1;
							while( have_rows("conditions") ) {

								the_row();
								$c_field = get_sub_field('fields_retail');
								$c_field_value = $rest_rows[$row_index][$c_field]; //get_field($c_field, $pid);
								$c_value = get_sub_field('value');
								if($c_value == "other"){
									$c_value = get_sub_field('value__other'); //a hand-typed value
								}

								if($c_field_value != $c_value && $c_value != "__hav__"){
									$condition_is_true = 0;
									continue;						
								}

								if($c_value == "__hav__" && !$form_results ){
									$condition_is_true = 0;
									continue;
								}
					
							}

							if($condition_is_true){
								$failed_conditions++;
								echo '<li>'.$text_result.'</li>';
							}

						}//end conditions repeater	

					}//not image

			endwhile; //end sections
			
			echo "</ol>";

			if(!$failed_conditions && !empty($alternate_output)){
				echo '<p>'.$alternate_output.'</p>';
			}

			endif;//end sections
			$row_index++;
		}
	}else{

		if( have_rows("section", $linked_page_id) ):

			echo "<ol>";
			while( have_rows("section",$linked_page_id) ) :
				the_row();

				$text_result = get_sub_field('text_result');			
				$search_and_replace_sub_ar = get_sub_field('fields');
				$form_results = 0;
				foreach($search_and_replace_sub_ar as $subfield){
					$s_replacement = get_field($subfield, $pid);
					$form_results = $form_results + strlen($s_replacement);
					$text_result = str_replace('{'.$subfield.'}', $s_replacement, $text_result);
				}

				if( have_rows("conditions") ):

					$condition_is_true = 1;
					while( have_rows("conditions") ) :

						the_row();
						$c_field = get_sub_field('fields');
						$c_field_value = get_field($c_field, $pid);
						$c_value = get_sub_field('value');
						if($c_value == "other"){
							$c_value = get_sub_field('value__other'); //a hand-typed value
						}
						//echo '<p>field: ' . $c_field. ' field value: '. $c_field_value . ' value: ' . $c_value. '</p>';
						//echo "form res : ".$form_results;
						if($c_field_value != $c_value && $c_value != "__hav__"){
							$condition_is_true = 0;
							continue;						
						}

						if($c_value == "__hav__" && !$form_results ){
							$condition_is_true = 0;
							continue;
						}
					
					endwhile;

					if($condition_is_true){
						$failed_conditions++;
						echo '<li>'.$text_result.'</li>';
					}

				endif;//end conditions repeater	

			endwhile; //end sections
			
			echo "</ol>";
			if(!$failed_conditions && !empty($alternate_output)){
				echo '<p>'.$alternate_output.'</p>';
			}

		endif;//end sections
	}

	if(!$is_restaurant_or_hotel && !$is_retail && !empty($image_field_2) && have_rows($image_field_2, $pid) ){

			echo '<div class="image-container">';
			while( have_rows($image_field_2, $pid) ) :

				the_row();
				$image = get_sub_field('image');
				$image_description = get_sub_field('image_description');

				echo '<div class="image"><img src="'.$image["url"].'" />';
				echo '<p class="image-description">'. $image_description . '</p></div>';

			endwhile;
			echo '<div style="clear:both"></div>';
			echo '</div>';
	}

	//if(!empty($alternate_output)){
		echo '</div>';
	//}


?>
