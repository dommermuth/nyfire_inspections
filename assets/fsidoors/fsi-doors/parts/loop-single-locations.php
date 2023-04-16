<?php

$primary_image = get_field("primary_image");

?>

<div id="locations-section-1">

	<h1 >
		<?php the_title(); ?>
	</h1>

	<img src="<?php echo $primary_image["url"]; ?>" alt="<?php echo $primary_image["alt"]; ?>" />

</div>


<?php

$description_introduction = get_field("description_introduction");
$description = get_field("description");

$street_address_1 = get_field("street_address_1");
$street_address_2 = get_field("street_address_2");
$telephone = get_field("telephone");
$city = get_field("city");
$state = strtoupper(get_field("state"));
$zip = get_field("zip");
$website_url = get_field("website_url");
$primary_website_url = get_field("primary_website_url");
$website_link_label = get_field("website_link_label");

$emergency_care_url = get_field("emergency_care_url");
$labor_and_delivery_url = get_field("labor_and_delivery_url");

$labor_and_delivery_icon = get_field("labor_and_delivery_icon", "option");
$premier_hosptial_icon = get_field("premier_hosptial_icon", "option");

$address = $street_address_1;
if(!empty($street_address_2)){
	$address .= "<br>".$street_address_2;
}

$address .= "<br>".$city . ", " . $state . " " . $zip;


?>

<div id="locations-section-2">

	<div class="left">
		<h2><?php echo $description_introduction; ?></h2>

		<?php echo $description; ?>
	</div>

	<div class="right">

		<div class="green-box">

			<p class="address">

				<?php echo $address; ?>

			</p>

			<p class="phone">
				<?php echo $telephone; ?>
			</p>

			<hr />

			<a class="" href="<?php echo $primary_website_url; ?>" target="_blank">Visit Website</a>

			<hr />

			<a class="" href="<?php echo $emergency_care_url; ?>">Emergency Care</a>

			<?php if (!empty($labor_and_delivery_url)) : ?>
			<a class="btn lt-green" href="<?php echo $labor_and_delivery_url; ?>">
				<span class="ico">
					<img src="<?php echo$labor_and_delivery_icon["url"]; ?>" />
				</span>Labor and delivery
			</a>
			<?php endif; ?>

			<a id="btn-premier-hospitals" class="btn dk-green" href="#" target="_blank"><span class="ico"><img src="<?php echo $premier_hosptial_icon["url"]; ?>" /></span>Our premier hospitals<i class="fa fa-caret-right" aria-hidden="true"></i></a>

			<?php

			//$custom_terms = get_terms('location_categories');

			//foreach($custom_terms as $custom_term) {

				$args = array('post_type' => 'locations',
					'tax_query' => array(
						array(
							'taxonomy' => 'location_categories',
							'field' => 'id',
							'terms' => 9,
						),
					),
				 );

				$loop = new WP_Query($args);
				if($loop->have_posts()) {
					//echo '<h2>'.$custom_term->name.'</h2>';
					echo "<div id='hospitals'>";
					while($loop->have_posts()) :
						$loop->the_post();
						echo '<a href="'.get_permalink().'">'.get_the_title().'</a>';
					endwhile;
					echo "</div>";
				}
				wp_reset_query();
			//}

			?>

			<script>

				jQuery(document).ready(function ($) {
					let expanded = 0;
					$("#btn-premier-hospitals").click(function (e) {
						e.preventDefault();
						$("#hospitals").slideToggle( "fast", function() {
							// Animation complete.
						});
						if (!expanded) {
							expanded = 1;
							$(this).find(".fa-caret-right").addClass("rotate");
						} else {
							expanded = 0;
							$(this).find(".fa-caret-right").removeClass("rotate");
						}
					});
				});


			</script>

		</div>

	</div>

</div>

<?php

$awards_and_accolades_title = get_field("awards_and_accolades_title");
$awards_and_accolades_icon = get_field("awards_and_accolades_icon");

?>

<div id="locations-section-3">

	<h3><span><img src="<?php echo $awards_and_accolades_icon["url"]; ?>" /></span><?php echo $awards_and_accolades_title; ?></h3>

	<div class="container">
		<?php

			if( have_rows('awards_and_accolades') ):

				while( have_rows('awards_and_accolades') ) :
					the_row();

					$name = get_sub_field('name');
					echo '<p>'.$name.'</p>';

				endwhile;

			endif;

			if( have_rows('footnotes_and_asterisks') ):

				while( have_rows('footnotes_and_asterisks') ) :
					the_row();

					$is_footnote = get_sub_field('is_footnote');
					$footnote_or_asterisk = get_sub_field('footnote_or_asterisk');
					$footnote_number = get_sub_field('footnote_number');
					$asterisk = get_sub_field('asterisk');

					if(!empty($is_footnote)){
						echo '<p class="footnote-or-asterisk"><sup>'.$footnote_number.'</sup>'.$footnote_or_asterisk.'</p>';
					}else{
						echo '<p class="footnote-or-asterisk">'.$asterisk.$footnote_or_asterisk.'</p>';
					}

				endwhile;

			endif;

		?>
	</div>
</div>

<?php

$centers_for_excellence_title = get_field("centers_for_excellence_title");
$centers_for_excellence_icon = get_field("awards_and_accolades_icon");

?>

<div id="locations-section-4">

	<h3>
		<span>
			<img src="<?php echo $centers_for_excellence_icon["url"]; ?>" />
		</span><?php echo $centers_for_excellence_title; ?>
	</h3>

	<div class="container">
		<?php

		if( have_rows('centers_of_excellence') ):

			while( have_rows('centers_of_excellence') ) :
				the_row();

				$name = get_sub_field('name');
				echo '<p>'.$name.'</p>';

			endwhile;

		endif;

		?>
	</div>
</div>