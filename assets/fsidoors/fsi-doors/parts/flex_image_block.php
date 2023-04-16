<?php

	global $post;
	$postid = $post->ID;
	$image= get_sub_field("image");
	$any_or_all = get_sub_field("any_or_all_conditions_must_be_true");
	$is_this_a_restaurant_or_hotel = get_sub_field("is_this_a_restaurant_or_hotel");

	if(!empty($is_this_a_restaurant_or_hotel)){

		$rest_rows = get_field("eating_establishments", $pid);
		$row_index = 0;
		$show_image = 0;
		foreach($rest_rows as $rest_row){

			if( have_rows("conditions") ){

				$conditions_ar = [];
				while( have_rows("conditions") ) :

					the_row();
					$c_field = get_sub_field('fields_restaurant_hotel');
					$c_field_value = $rest_rows[$row_index][$c_field];
					$c_value = get_sub_field('value');

					if($c_field_value != $c_value){
						$conditions_ar[] = 0;
						continue;
					}
					$conditions_ar[] = 1;

				endwhile;

				
				$len = count($conditions_ar);
				$number_of_times_true = count(array_keys($conditions_ar, 1));

				//var_dump($conditions_ar);
				//echo "<br>";
				//echo "len :". $len."<br>";
				//echo "num times true: " .$number_of_times_true. "<br>";
				//echo "any or all: " . $any_or_all. "<br>";

				if($any_or_all == "all" && ($number_of_times_true == $len)){
					$show_imag++;
				}elseif($any_or_all == "any" && $number_of_times_true){
					$show_image++;
				}


			}else{//no conditions were required

				echo '<img src="'. $image["url"] .'" width="100%" border="0" />';

			}//end conditions repeater

			
		}

		if($show_image){
			echo '<img src="'. $image["url"] .'" width="100%" border="0" />';
		}

	}else{
		if( have_rows("conditions") ){

			$conditions_ar = [];
			while( have_rows("conditions") ) :

				the_row();
				$c_field = get_sub_field('fields');
				$c_field_value = get_field($c_field, $pid);
				$c_value = get_sub_field('value');

				if($c_field_value != $c_value){
					$conditions_ar[] = 0;
					continue;
				}
				$conditions_ar[] = 1;

			endwhile;


			$show_image = 0;
			$len = count($conditions_ar);
			$number_of_times_true = count(array_keys($conditions_ar, 1));


			//var_dump($conditions_ar);
			//echo "<br>";
			//echo "len :". $len."<br>";
			//echo "num times true: " .$number_of_times_true. "<br>";
			//echo "any or all: " . $any_or_all. "<br>";

			if($any_or_all == "all" && ($number_of_times_true == $len)){
				$show_image = 1;
			}elseif($any_or_all == "any" && $number_of_times_true){
				$show_image = 1;
			}

			//echo "show image : " . $show_image."<br>";
			//echo "imag url : ". $image["url"]."<br>";

			if($show_image){
				echo '<img src="'. $image["url"] .'" width="100%" border="0" />';
			}

		}else{//no conditions were required

			echo '<img src="'. $image["url"] .'" width="100%" border="0" />';

		}//end conditions repeater

	}//not restaurant


?>
