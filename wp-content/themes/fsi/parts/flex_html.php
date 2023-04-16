<?php

	global $post;
	$postid = $post->ID;

	$html = get_sub_field('html_content');
	$margin_left = get_sub_field('margin_left');
	$margin_top = get_sub_field('margin_top');
	$margin_right = get_sub_field('margin_right');
	$margin_bottom = get_sub_field('margin_bottom');
	$search_and_replace_ar = get_sub_field('fields');

	foreach($search_and_replace_ar as $field){

		$replacement = get_field($field, $pid);

		if(empty($replacement)){
			continue;
		}

		if($field == "nyfc_examiner"){
			//test if is user object
			if($replacement["user_firstname"] && $replacement["user_lastname"]){
				$replacement = $replacement["user_firstname"] . " " . $replacement["user_lastname"];
			}else{
				$replacement = "Add user first name and last name to user account.";
			}
		}else if($field == "nyfc_examiner_signature"){
			$replacement = '<img src="'.$replacement.'" width="300" />';
		}

		$html = str_replace('{'.$field.'}', $replacement, $html);
	}
	$css_margin_width =  $margin_right + $margin_left;
	echo '<div class="text-container" style="padding:'.$margin_top.'px '.$margin_right.'px '. $margin_bottom.'px '.$margin_left.'px; width:calc( 100% - '.$css_margin_width.'px );">'.$html.'</div>';


?>
