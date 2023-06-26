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
	$is_repeater = get_sub_field('is_repeater',$linked_page_id);
	$repeater_name = get_sub_field('repeater_name',$linked_page_id);


	if($search_and_replace_ar){
		foreach($search_and_replace_ar as $field){
			$replacement = get_field($field, $pid);
			$html = str_replace('{'.$field.'}', $replacement, $html);
		}
	}

	$failed_conditions = 0;
	$css_margin_width =  $margin_right + $margin_left;

	$html_tmp = "";
	//if(!empty($alternate_output)){
	$html_tmp .= '<div class="text-container" style="margin:'.$margin_top.'px '.$margin_right.'px '. $margin_bottom.'px '.$margin_left.'px;">';
	$html_tmp .='<h5>'.$section_title.'</h5>';
	//}

	if(!empty($image_field) && have_rows($image_field, $pid) ){
	//if(!$is_repeater && !$is_restaurant_or_hotel && !$is_retail && !empty($image_field) && have_rows($image_field, $pid) ){

			$html_tmp .= '<div class="image-container">';
			while( have_rows($image_field, $pid) ) :

				the_row();
				$image = get_sub_field('image');
				$image_description = get_sub_field('image_description');

				$html_tmp .='<div class="image"><img src="'.$image["url"].'" />';
				$html_tmp .= '<p class="image-description">'. $image_description . '</p></div>';

			endwhile;
			$html_tmp .= '<div style="clear:both"></div>';
			$html_tmp .= '</div>';
	}

	if($is_repeater && !empty($repeater_name) ) {

		
		$rest_rows = get_field($repeater_name, $pid);
		$row_index = 0;

		
		if(empty($rest_rows)){
			return;
		}
		
		foreach($rest_rows as $rest_row){
			
			if( have_rows("section", $linked_page_id) ):

				//$count_while = 0;
				$html_tmp .= "<ol style='border-bottom: 1px solid #666'>";
				while( have_rows("section", $linked_page_id) ) :
					the_row();
					//$count_while++;
					$text_result = get_sub_field('text_result');
					$search_and_replace_sub_ar = get_sub_field('fields_'.$repeater_name);

					$image_repeater = get_sub_field('fields_'.$repeater_name.'_images');					

					if(!empty($image_repeater)) {
						$html_tmp .=  "<li><strong>Images</strong>";
						$html_tmp .=  '<div class="image-container" style="border-bottom:none">';
						foreach( $rest_rows[$row_index][$image_repeater] as $image_ar ) {
							$image = $image_ar['image'];
							$image_description = $image_ar['image_description'];
							$html_tmp .=  '<div class="image" style="width:400px"><img src="'.$image["url"].'" />';
							$html_tmp .=  '<p class="image-description">'. $image_description . '</p></div>';
						}
						$html_tmp .=  '<div style="clear:both"></div>';
						$html_tmp .=  '</div></li>';
					}else{

						$form_results = 0;

						if(empty($search_and_replace_sub_ar)){
							$form_results = $text_result;
						}else{
							foreach($search_and_replace_sub_ar as $subfield){
								if($subfield == "image"){
									$s_replacement =$rest_rows[$row_index][$subfield]["url"];
								}else{
									$s_replacement =$rest_rows[$row_index][$subfield];
									if(is_array($s_replacement)){ //for multi-select
										$s_replacement = implode(', ', $s_replacement);
									}
								}
								$form_results = $form_results + strlen($s_replacement);
								$text_result = str_replace('{'.$subfield.'}', $s_replacement, $text_result);
							}
						}

						if( have_rows("conditions", $linked_page_id) ){

							$condition_is_true = 1;

							while( have_rows("conditions", $linked_page_id) ) {
								the_row();
								if($c_value == "__hav__" && !$form_results ){
									$condition_is_true = 0;
									continue;
								}
								//echo $text_result . " " . $count_while;
								//echo "<br><br><br>";
								//continue;
							
								$c_field = get_sub_field('fields_'.$repeater_name);
								//echo "row_index ". $row_index. "c_field ". $c_field."<br>";
								$c_field_value = $rest_rows[$row_index][$c_field]; //get_field($c_field, $pid);
								$c_value = get_sub_field('value');
								//echo $c_field_value . " " . $c_value . "<br>";
							
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
								$html_tmp .=  '<li>'.$text_result.'</li>';
							}

						}//end conditions repeater	

					}//not image

			endwhile; //end sections
			
			$html_tmp .=  "</ol>";

			if(!$failed_conditions && !empty($alternate_output)){
				$html_tmp .=  '<p>'.$alternate_output.'</p>';
			}

			endif;//end sections
			$row_index++;
		}
	}else if($is_restaurant_or_hotel ) {

		$rest_rows = get_field("eating_establishments", $pid);		

		$row_index = 0;
		
		foreach($rest_rows as $rest_row){
			//var_dump($rest_row);			
			if( have_rows("section", $linked_page_id) ):
				//exit;
				$html_tmp .= "<ol style='border-bottom: 1px solid #666'>";
				while( have_rows("section",$linked_page_id) ) :
					the_row();

					$text_result = get_sub_field('text_result');			
					$search_and_replace_sub_ar = get_sub_field('fields_restaurant_hotel');
					$image_repeater = get_sub_field('fields_restaurant_hotel_images');

					if(!empty($image_repeater)) {
						$html_tmp .= "<li><strong>Images</strong>";
						$html_tmp .= '<div class="image-container" style="border-bottom:none">';
						foreach( $rest_rows[$row_index][$image_repeater] as $image_ar ) {
							$image = $image_ar['image'];
							$image_description = $image_ar['image_description'];
							$html_tmp .= '<div class="image" style="width:400px"><img src="'.$image["url"].'" />';
							$html_tmp .= '<p class="image-description">'. $image_description . '</p></div>';
						}
						$html_tmp .= '<div style="clear:both"></div>';
						$html_tmp .= '</div></li>';
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
								$html_tmp .= '<li>'.$text_result.'</li>';
							}

						}//end conditions repeater	

					}//not image

			endwhile; //end sections
			
			$html_tmp .= "</ol>";

			if(!$failed_conditions && !empty($alternate_output)){
				$html_tmp .= '<p>'.$alternate_output.'</p>';
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
				$html_tmp .= "<ol style='border-bottom: 1px solid #666'>";
				while( have_rows("section",$linked_page_id) ) :
					the_row();

					$text_result = get_sub_field('text_result');			
					$search_and_replace_sub_ar = get_sub_field('fields_retail');
					$image_repeater = get_sub_field('fields_retail_images');


					if(!empty($image_repeater)) {
						$html_tmp .= "<li><strong>Images</strong>";
						$html_tmp .= '<div class="image-container" style="border-bottom:none">';
						foreach( $rest_rows[$row_index][$image_repeater] as $image_ar ) {
							$image = $image_ar['image'];
							$image_description = $image_ar['image_description'];
							$html_tmp .= '<div class="image" style="width:400px"><img src="'.$image["url"].'" />';
							$html_tmp .= '<p class="image-description">'. $image_description . '</p></div>';
						}
						$html_tmp .= '<div style="clear:both"></div>';
						$html_tmp .= '</div></li>';
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
								$html_tmp .= '<li>'.$text_result.'</li>';
							}

						}//end conditions repeater	

					}//not image

			endwhile; //end sections
			
			$html_tmp .= "</ol>";

			if(!$failed_conditions && !empty($alternate_output)){
				$html_tmp .= '<p>'.$alternate_output.'</p>';
			}

			endif;//end sections
			$row_index++;
		}
	}else{
		
		if( have_rows("section", $linked_page_id) ):

			$html_tmp .= "<ol>";
			while( have_rows("section",$linked_page_id) ) :
				the_row();

				$text_result = get_sub_field('text_result');			
				$search_and_replace_sub_ar = get_sub_field('fields');
				$form_results = 0;
				foreach($search_and_replace_sub_ar as $subfield){
					$s_replacement = get_field($subfield, $pid);
					if(is_array($s_replacement)){ //for multi-select or user object

						if($s_replacement["user_firstname"] && $s_replacement["user_lastname"]){ //this is a User so just show first and last name
							$s_replacement = $s_replacement["user_firstname"] . " " . $s_replacement["user_lastname"];
						}else{
							$s_replacement = implode(', ', $s_replacement);
						}
					}
					$form_results = $form_results + strlen($s_replacement);				

					$text_result = str_replace('{'.$subfield.'}', $s_replacement, $text_result);
										
				}
				//echo "text result: ".$text_result;
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

						/*
						if($text_result == "<strong>Management & Maintenance:</strong> test"){
						echo "text result: ".$text_result . "<br>";
						echo '<p>field: ' . $c_field. '<br>field value: '. $c_field_value . '<br>value: ' . $c_value. '</p>';
						echo "form res : ".$form_results . "<br>";
						}
						*/
						

						//if($c_value == "__hav__" && $c_field_value){
							//if has any value and the inputed value is anything
							//$condition_is_true = 1;
							//continue;						
						//}
					
						if($c_field_value != $c_value && $c_value != "__hav__"){

							$condition_is_true = 0;
							continue;						
						}

						if($c_value == "__hav__" && !$c_field_value ){
							//if it's has any value and there is the input is empty don't show anything
							$condition_is_true = 0;
							continue;
						}
					
					endwhile;

					if($condition_is_true){
						$failed_conditions++;
						$html_tmp .= '<li>'.$text_result.'</li>';
					}

				endif;//end conditions repeater	

			endwhile; //end sections
			
			$html_tmp .= "</ol>";
			if(!$failed_conditions && !empty($alternate_output)){
				$html_tmp .= '<p>'.$alternate_output.'</p>';
			}

		endif;//end sections
	}

	if(!$is_restaurant_or_hotel && !$is_retail && !empty($image_field_2) && have_rows($image_field_2, $pid) ){

			$html_tmp .= '<div class="image-container">';
			while( have_rows($image_field_2, $pid) ) :

				the_row();
				$image = get_sub_field('image');
				$image_description = get_sub_field('image_description');

				$html_tmp .= '<div class="image"><img src="'.$image["url"].'" />';
				$html_tmp .= '<p class="image-description">'. $image_description . '</p></div>';

			endwhile;
			$html_tmp .= '<div style="clear:both"></div>';
			$html_tmp .= '</div>';
	}

	$html_tmp .= '</div>';
		

	if (strpos($html_tmp,"No violations were found") !== false) {
		//echo "hide";
	}else{
		echo $html_tmp;
	}

?>
