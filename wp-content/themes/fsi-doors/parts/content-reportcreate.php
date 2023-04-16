
<?php

$pid = ( isset( $_GET['pid'] ) ) ? sanitize_text_field( $_GET['pid'] ) : 'new_post';

$header_text = "Add Report";
if(is_numeric($pid)){
	$header_text = "Edit Report";
}

?>
<div id="msg-container"></div>
<div class="form-container">

	<?php
		// Check rows exists.
		if( have_rows("form_groups","options") ):

			$form_goups_ar = [];
			// Loop through rows.
			while( have_rows("form_groups","options") ) :
				the_row();

				$button_label = get_sub_field('button_label');
				$field_id = get_sub_field('field_id');

				$group = [
							"button_label" => $button_label,
							"field_id" => $field_id
						];
				$form_goups_ar[] = $group;
			endwhile;
			// No value.
		else :
			// Do something...
			echo "No Form Groups found";
		endif;

		echo '<div class="left">';
		echo '<div id="report-nav">';
		//create nav
		foreach($form_goups_ar as $index => $form){
			//make hash for url bar
			$hash = strtolower($form["button_label"]);
			$hash = str_replace(" ", "-", $hash);
			$active_class = "";
			if($index == 0){
				$active_class = "active";
			}

			if(!is_numeric($pid) && $index > 0){
				//before showing all form navigation - save the post.
				//once saved show all form navigation.
				continue;
			}

			echo '<div class="nav-button-container">
					<button class="nav-button '.$active_class.'" data-hash="'.$hash.'" data-formid="'.$form["field_id"].'">'.$form["button_label"].'</button>
					<span class="progress-bar"></span>
				  </div>';
		}

		echo '<a class="btn-view-pdf"  href="'.home_url().'/csv?pid='.$pid.'" >Download CSV</a>';
		echo '<a class="btn-view-pdf"  href="'.home_url().'/report/pdf?pid='.$pid.'" target="_blank">View PDF</a>';

		echo '</div><!-- end nav -->';

		echo '</div><!-- end left -->';
		echo '<div class="right">';
		echo '<h1>'.$header_text.'</h1>';
		foreach($form_goups_ar as $index => $form){

			$class="";
			$submit_value = "Update Report";
			$show_post_title = false;
			if(!is_numeric($pid)){
				$submit_value = "Add Report";
				$class="";
			}

			if($index > 0){
				$class="hidden";
			}else{
				$show_post_title = true;
			}
			$args = array(
				'id'   => "form_".$form['field_id'],
				'post_id'   => $pid,
				'new_post'  => array(
					'post_type'     => 'reports',
					'post_status'   => 'publish'
				),
				'post_title'   => $show_post_title,
				'post_content' => false,
				'field_groups' => [$form['field_id']],
				'instruction_placement' => "field",
				'submit_value' => esc_html__( $submit_value, 'acf' )
			);

			
			echo '<div id="'.$form['field_id'].'" data-btnlabel="'.$form["button_label"].'" class="report-form '.$class.'" >';
			echo '<h5>'.$form["button_label"].'</h5>';
			acf_form($args);
			echo '</div>';

		}

		echo '</div><!-- end right -->';

	?>

</div>

<script type="text/javascript">
		
	jQuery(function ($) {

		initHints();
		initNav();
		initFields();
		initFormDataTest();
		initListeners();
		

		<?php

		//only load the proceeding JS on page edit.  Not page create.  the post id will only
		//be available on the edit page
		if(is_numeric($pid)):
		
        ?>

		//autosave only works when the following function is present
		initAutoSave();

		//only track progress after form saved
		initProgressTracker();

		jQuery('.acf-form').submit(function (event) {
			event.preventDefault();
			//save serialized data so wwe can compare later and make sure form is saved before they leave page
			if(jQuery(this).closest(".report-form").hasClass("hidden")){
				return;	
			}
			let btn_label = jQuery(this).closest('.report-form').attr("data-btnlabel");
			
			let form_id = $(this).attr('id');
			let form_data = createFormData(form_id);
			let ls_name = form_id;
			jQuery(this).data('form_data', form_data);
			localStorage.setItem(ls_name, JSON.stringify(form_data));
			//$("#autosave-msg").remove();
			$("#lost-connection-container").remove();
			if (!isOnline) {
				//not currently online
				let save_html = jQuery("<div id='lost-connection-container'></div>");
				save_html.append("<p class='warning'>Connection lost:  Saved <strong>" + btn_label + "</strong> data to your device.  This will be saved later when you have a connection...</p>");
				jQuery("#msg-container").append(save_html);
				return;
			}
			/*
			 * this caused long delay and should be rare so removed.
			let isLoggedIn = isUserLoggedIn();
			if (!isLoggedIn) {
				let save_html = jQuery("<div id='lost-connection-container'></div>");
				save_html.append("<p class='warning'>Connection lost: You are no longer logged in. Saved <strong>" + btn_label + "</strong> data to your device.  This will be saved after you have logged in.</p>");
				jQuery("#report-nav").append(save_html);
				return;
			}
			*/
			console.log("btn_label", btn_label);
			let save_html = jQuery("<div class='autosave-msg'></div>");
			save_html.append("<p class='warning'>Saving <strong>" + btn_label + "</strong> data...</p>");
			jQuery("#msg-container").append(save_html);			
			
			let ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>?action=save_my_data";
			jQuery.post(ajaxurl, form_data).done(function (save_data) {
				//console.log("save_data", save_data);
				if (!save_data) {
					save_html.remove();
					if (!$("#lost-connection-container").length) {
						let lost_connect_html = $("<div id='lost-connection-container'></div>");
						let emergency_save_btn = $("<button id='emergency-save'>Save</button>");
						lost_connect_html.append("<p class='warning'>Connection was lost or you were logged out. Data is saved on your device and will be saved to the server when your connection is restored.</p>");
						lost_connect_html.append(emergency_save_btn);
						$("#msg-container").append(lost_connect_html);
					}

				} else {
					//remove backup
					$("#lost-connection-container").remove();
					localStorage.removeItem(ls_name);
					save_html.empty();
					save_html.append("<p class='warning'><strong>" + btn_label + "</strong> data saved.</p>");
					jQuery(save_html).stop().fadeOut('slow', function () {
						jQuery(this).remove();
					});
				}
			});

		});

		$(window).bind('beforeunload', function (e) {
			$('.acf-form').each(function (e) {
				let form_id = $(this).attr('id');
				let form_name = $(this).closest(".report-form").attr("data-btnlabel");
				let form_data = createFormData(form_id);
				if (JSON.stringify(jQuery(this).data('form_data')) != JSON.stringify(form_data)) {
					console.log(form_name, "Data was not saved");
					let lost_connect_html = $("<div id='lost-connection-container'></div>");
					lost_connect_html.append("<p class='warning'><strong>"+ form_name + "</strong> data was not saved. Click the related button above and click update or add report on your bottom right.</p>");
					$("#msg-container").append(lost_connect_html);
					return true;
				} else {
					e=null;
				}
			});
		});

		$("#doors .acf-actions a").click(function() {
		  collapsePreviousDoor();
		});

		/*
		 .self-closing-required
		.fdny-sign-required
		.required-sign
		
		Entrance
		Vestibule
		1st Floor Exits
		Basement Exit
		Roof Door
		Stairway Door
		Boiler Room
		Elec Meter Rm
		Gas Metter Room
		Compactor Room
		Chute Room
		Elev Mach Rm
		Pump Room
		Garage
		 */

		$(document).on('change', '#doors .door-type select', function () {
			let self_closing_req = $(this).closest("tr").find('.self-closing-required');
			let fdny_sign_req = $(this).closest("tr").find('.fdny-sign-required');
			let req_sign = $(this).closest("tr").find('.required-sign');
			if(
				this.value == "Entrance" || 
				this.value == "Vestibule" || 
				this.value == "Roof Door"
			   ){
				//self closing required button
				if(!self_closing_req.find("input[type='radio'][value='no']:checked").val()){
					self_closing_req.find("input[type='radio'][value='no']").trigger("click");
				}

				if(!fdny_sign_req.find("input[type='radio'][value='no']:checked").val()){
					fdny_sign_req.find("input[type='radio'][value='no']").trigger("click");
				}

			}else if(
					this.value == "Service Elevator Lobby Door" ||
					this.value == "Fire/Smoke Stop Door"
				){
				if(!self_closing_req.find("input[type='radio'][value='yes']:checked").val()){
					self_closing_req.find("input[type='radio'][value='yes']").trigger("click");
				}

				if(!fdny_sign_req.find("input[type='radio'][value='no']:checked").val()){
					fdny_sign_req.find("input[type='radio'][value='no']").trigger("click");
				}
			}else{
				if(!self_closing_req.find("input[type='radio'][value='yes']:checked").val()){
					self_closing_req.find("input[type='radio'][value='yes']").trigger("click");
				}

				if(!fdny_sign_req.find("input[type='radio'][value='yes']:checked").val()){
					fdny_sign_req.find("input[type='radio'][value='yes']").trigger("click");
				}
			}
		});

		<?php endif; ?>

	
	}); //end jquery ready

	function collapsePreviousDoor(){
		let rowCount = jQuery('.ui-sortable tr').length;
		let row = rowCount -1;
	    let collapsed =jQuery(".ui-sortable tr:nth-child("+ (rowCount-1) +")").hasClass("-collapsed");
		if(!collapsed){
			jQuery(".ui-sortable tr:nth-child("+ (rowCount-1) +") .-collapse").trigger("click");
		}
	}

	function initListeners() {
		window.isOnline = 1;
		window.addEventListener("online", function(){
			isOnline = 1;
			jQuery("#lost-connection-container").remove();
			saveLocalFormData();
		});

		window.addEventListener("offline", function(){
			  isOnline = 0;
		}); 
	}

	function initAutoSave() {
		var autosave_interval = setInterval(timedSave, 60000);
		jQuery('form').on('input change paste', ':input', function(){
			clearSetInterval();
			autosave_interval = setInterval(timedSave, 2000);			
		});

		function timedSave() {
			clearSetInterval();
			isUserLoggedIn();
			jQuery('.acf-form').submit();
		}

		function clearSetInterval() {
			console.log("cleared interval");
			clearInterval(autosave_interval);
		}
	}

	function initFormDataTest() {
		jQuery(".acf-form").each(function(index, value){
			let form_id = jQuery(this).attr('id');
			let form_data = createFormData(form_id);
			let ls_name = form_id;
			jQuery(this).data('form_data', form_data);
		});			
	}

	function createFormData(form_id) {
		let form_data = {};
		jQuery("#" + form_id + " :input").each(function () {
			//don't save unchecked radios
			if (jQuery(this).attr('type') == "radio" && !jQuery(this).is(':checked')) { return; }
			//console.log("formdata:", $(this).attr('name'), "formdata:", $(this).val());
			form_data[jQuery(this).attr('name')] = jQuery(this).val();
		});

		return form_data;
	}

	function initFields() {
		<?php
			//populate fields if any in URL string
			$title = ( isset( $_GET['address'] ) ) ? sanitize_text_field( $_GET['address'] ) : '';
			$state = ( isset( $_GET['state'] ) ) ? sanitize_text_field( $_GET['state'] ) : '';
			$city = ( isset( $_GET['city'] ) ) ? sanitize_text_field( $_GET['city'] ) : '';
			$zip = ( isset( $_GET['city'] ) ) ? sanitize_text_field( $_GET['zip'] ) : '';
        ?>
		const title = "<?php echo $title; ?>";
		const state ="<?php echo $state; ?>";
		const city = "<?php echo $city; ?>";
		const zip = "<?php echo $zip; ?>";

		if (title) {
			jQuery("#acf-_post_title").val(title);
			jQuery("#acf-field_61aa51af1175d").val(title);
		}

		if (state) {
			jQuery("#acf-field_61aa51e81175f").val(state);
		}

		if (city) {
			jQuery("#acf-field_61aa51da1175e").val(city);
		}

		if (zip) {
			jQuery("#acf-field_61aa522e11760").val(zip);
		}
	}
	
	//this function changes the form showing either by nav button click or page load
	function changeForms(hash) {
		let button = jQuery("button[data-hash='" + hash + "']");
		let form_id = button.attr("data-formid");
		jQuery(".nav-button").removeClass("active");
		button.addClass("active");
		jQuery(".report-form").addClass("hidden");
		jQuery("#"+form_id).removeClass("hidden");
		window.location.hash = hash;
	}

	function isUserLoggedIn() {			
		let ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>?action=check_user_logged_in";	
		jQuery.post(ajaxurl).done(function (response) {	
			if(response == "0"){	
				//check to see if user is online	
				if(window.isOnline){	
					alert("You are no longer logged-in. Refresh page to login again.");	
				}	
			}	
		});	
	}

	//this function is executed by an emergency save button that is added in the event
	//internet connection is lost
	function saveLocalFormData() {
		let save_html = jQuery("<div class='autosave-msg'></div>");
		save_html.append("<p class='warning'><strong>Connection Restored</strong></p>");
		jQuery("#msg-container").append(save_html);
		for ( var i = 0, len = localStorage.length; i < len; ++i ) {
			if (localStorage.key(i).includes('form_group')) {
				let ls_name = localStorage.key(i);
				let ls_data = localStorage.getItem(ls_name);
				let form_data = JSON.parse(ls_data);
				let ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>?action=save_my_data";
				console.log("ls_name", ls_name);
				console.log("ajaxurl", ajaxurl);
				jQuery.post(ajaxurl, form_data).done(function (save_data) {	
					console.log("save_data",save_data);
					if (!save_data) {
						alert("Failed to save local data");						
					} else {
						//remove backup
						jQuery("#lost-connection-container").remove();
						localStorage.removeItem(ls_name);											
						save_html.append("<p class='warning'>Local Storage " + ls_name + " data saved.</p>");						
						
					}
				}).fail(function(xhr, status, error) {
					console.log("xhr", xhr);
					console.log("status", status);
					console.log("error", error);
					jQuery(".autosave-msg").remove();
					let save_html = jQuery("<div class='autosave-msg'></div>");
					save_html.append("<p class='warning'><strong>Retry autosave in 5 seconds...</strong></p>");
					jQuery("#msg-container").append(save_html);
					jQuery(save_html).delay(4000).fadeOut('slow', function () {
						jQuery(this).remove();
					});
					setTimeout(function() { saveLocalFormData(); }, 5000);
					
				});
			}
		}
		jQuery(save_html).delay(2000).fadeOut('slow', function () {
			jQuery(this).remove();
		});
	}

	function initNav() {
		//on page load go to correct hash if exists
		if( location.hash && location.hash.length ) {
		   const hash = decodeURIComponent(location.hash.substr(1));
		   changeForms(hash);
		}

		//add event to nav buttons
		jQuery(".nav-button").on("click", function(){
			let hash = jQuery(this).attr("data-hash");
			changeForms(hash);
		});
	}

	function initHints() {

		//hide all descriptions (animate in with interactivity)
		//jQuery("p.description").css({});

		jQuery("input, textarea, .acf-button-group").focus(function () {
			//jQuery(this).closest(".acf-field").find('.description').stop().show("slow");
			let def = jQuery(this).closest(".acf-field").find('.description');
			animateIn(def);
		});

		jQuery("input, textarea, .acf-button-group").focusout(function () {
			//jQuery(this).closest(".acf-field").find('.description').stop().hide("slow");
			let def = jQuery(this).closest(".acf-field").find('.description');
			animateOut(def);
		});

		jQuery(".acf-field-button-group").on({
			mouseenter: function () {
				//jQuery(this).closest(".acf-field").find('.description').stop().show("slow");
				let def = jQuery(this).closest(".acf-field").find('.description');
				animateIn(def);
			},
			mouseleave: function () {
				//jQuery(this).closest(".acf-field").find('.description').stop().hide("slow");
				let def = jQuery(this).closest(".acf-field").find('.description');
				animateOut(def);
			}
		});

		function animateIn(def) {
			jQuery('.description').stop().animate({right: '-344px'});//animate all out
			def.stop();
			def.animate({right: '-4px'});

		}

		function animateOut(def) {
			def.stop();
			def.animate({right: '-344px'});

		}
	}

	function initProgressTracker() {

		jQuery('.acf-form').each(function () {
			let form = jQuery(this);
			let form_name = form.parent().attr("data-btnlabel");
			console.log("Form: ", form_name);
			let total_form_elements = 0;
			let total_completed_form_elements = 0;
			let form_id = "#" + form.attr("id");
			jQuery(form_id + ' .progress-check').each(				
				function (index) {
					let input = jQuery(this).find(":input");
					total_form_elements++;	
					if (input.attr('name')) {

						let status = 0;
						console.log("Name :", input.attr('name'), "Type : ", input.attr('type'));

						if(jQuery(this).find("select").length){
							if(jQuery(this).find("select").val()){
								//jQuery(this).css("background-color","pink");
								total_completed_form_elements++;
								status = 1;
							}
						}else if ((!jQuery(this).attr("data-type") != "button_group" && input.val()) ) {
							total_completed_form_elements++;
							status = 1;
						} else if (jQuery(this).attr("data-type","button_group") && input.is(":checked")) {
							total_completed_form_elements++;
							status = 1;
						}					

						input.attr("data-formparent",form_id);
						updateInputIsComplete(input, status);

						//add eveent
						input.on('keyup change paste', function(){
							let e = jQuery(this);
							let status = 0;
							let is_button_group = 0;
							
							if(e.val() || e.is(":checked")){
								status = 1;
							}


							if( e.attr("data-iscomplete") == "0" && status){ //status has changed
								let form = jQuery(jQuery(this).attr("data-formparent"));
								let tcfe = form.attr("data-totalcompletedformelements");
								tcfe = Number(tcfe) + 1;
								form.attr("data-totalcompletedformelements", tcfe);
								updateFormProgress(form);
							}else if(e.attr("data-iscomplete") == "1" && !status){
								let form = jQuery(jQuery(this).attr("data-formparent"));
								let tcfe = form.attr("data-totalcompletedformelements");
								tcfe = Number(tcfe) - 1;
								form.attr("data-totalcompletedformelements", tcfe);
								updateFormProgress(form);
							}													
							
							updateInputIsComplete(e, status);

							//remove the click event once a button has been checked
							//because you can't uncheck it
							if(e.attr("type") == "radio" && e.attr("data-iscomplete") != "done"){
								let closestParent = e.closest(".acf-input");
								closestParent.find(":input").each(				
									function (index) {
										jQuery(this).attr("data-iscomplete", "done");
									}
								);								
							}
							
						});
					}
				}
			);
			console.log("total form_elements :", form_name, total_form_elements);
			form.attr("data-totalformelements", total_form_elements);
			form.attr("data-totalcompletedformelements", total_completed_form_elements);

			updateFormProgress(form);			
			
		});

		function updateInputIsComplete(input, status){
			input.attr("data-iscomplete", status);
		}

		function updateFormProgress(form){
			let total_form_elements = form.attr("data-totalformelements");
			let total_completed_form_elements = form.attr("data-totalcompletedformelements");
			let button_id = form.attr("id").replace("form_", "");
			let r = (total_completed_form_elements/total_form_elements) * 100;
			jQuery(`[data-formid="${button_id}"]`).next(".progress-bar").css("width", r+"%");

			if(r==100){
				jQuery(`[data-formid="${button_id}"]`).addClass('section-complete');
			}else{
				jQuery(`[data-formid="${button_id}"]`).removeClass('section-complete');
			}
		}
	}


</script>