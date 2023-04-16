
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

		//initHints();
		initNav();
		initFields();
		initFormDataTest();
		initListeners();

		//add repeater row if none exists
		//autoAddRepeaterRow();

		<?php

		//only load the proceeding JS on page edit.  Not page create.  the post id will only
		//be available on the edit page
		if(is_numeric($pid)):
		
        ?>

		
		//autosave only works when the following function is present
		initAutoSave();

		//add click event to ACF repeaters so progress tracker includes new fields
		initACFRepeaterCustomizations();

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

		<?php endif; ?>

	
	}); //end jquery ready

	function autoAddRepeaterRow(){
		jQuery(".acf-repeater").each(function(index, value) {
			if(jQuery(this).hasClass("-empty") ){
				jQuery(this).find(".acf-repeater-add-row").trigger("click");
			}
		});
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

		//for tracking all hidden input changes. 
		//A hidden input will not trigger a change automatically.  This makes it trigger a change
		MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
		var trackChange = function(element) {
		  var observer = new MutationObserver(function(mutations, observer) {
			if(mutations[0].attributeName == "value") {
				jQuery(element).trigger("change");
			}
		  });
		  observer.observe(element, {
			attributes: true
		  });
		}
		//add trackChange to all hidden inputs
		jQuery("input[type='hidden']").each(function(index, value){
			trackChange( jQuery(this)[0] );
		});	

		//save once per min
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

	function initACFRepeaterCustomizations(){

		var acfFieldAdded = function( field ){

			if(field.$el.hasClass("progress-check")){
				console.log("ADD - has progress-check class");
				let form = field.$el.closest('.acf-form');
				let form_id = "#" + field.$el.closest('.acf-form').attr("id");				
				let total_form_elements = form.attr("data-totalformelements");
				let total_completed_form_elements = form.attr("data-totalcompletedformelements");				
				let data = processProgressInput(field.$el,form_id );

				if(data.is_trackable_form_element){
					total_form_elements++;
				}

				if(data.is_completed_form_element){
					total_completed_form_elements++;
				}

				form.attr("data-totalformelements", total_form_elements);
				form.attr("data-totalcompletedformelements", total_completed_form_elements);
				updateFormProgress(form);	
			}
		};

		var acfFieldRemoved = function( field ){

			if(field.$el.hasClass("progress-check")){
				console.log("REMOVE - has progress-check class");
				let form = field.$el.closest('.acf-form');
				let form_id = "#" + field.$el.closest('.acf-form').attr("id");				
				let total_form_elements = form.attr("data-totalformelements");
				let total_completed_form_elements = form.attr("data-totalcompletedformelements");				
				let data = processProgressInput(field.$el,form_id );

				if(data.is_trackable_form_element){
					total_form_elements--;
				}

				if(data.is_completed_form_element){
					total_completed_form_elements--;
				}

				form.attr("data-totalformelements", total_form_elements);
				form.attr("data-totalcompletedformelements", total_completed_form_elements);
				updateFormProgress(form)
			}
		};

		acf.addAction('new_field', acfFieldAdded);
		acf.addAction('remove_field', acfFieldRemoved); 
	}

	function processProgressInput(field, form_id) {
		
		let data = {};
		data.is_trackable_form_element = 0;
		data.is_completed_form_element = 0;
		
		let input;
		if(field.attr("data-type") == "button_group"){
			input = field.find(":input[type=radio]");
		}else if(field.attr("data-type") =="select" || field.attr("data-type") =="user" || field.attr("data-type") == "date_picker"){
			input = field.find("select");
		}else{
			input = field.find(":input[name]:not(:disabled):first");
		}
		
		if(!input.length){
			input = field.find(".hasDatepicker")
		}
		
		if(!input.length){
			//still not found
			return data;
			//console.log("NOT FOUND", field.attr("data-name"));
		}else{
			//console.log("    FOUND", field.attr("data-name"));
		}

		if(input.hasClass("hasDatepicker")){
			//alert("dp");
			}
		//total_form_elements++;
		data.is_trackable_form_element = 1;
		let status = 0;
		//console.log("Name :", input.attr('name'), "Type : ", input.attr('type'));
		
		if(field.find("select").length){
			if(field.find("select").val() && field.find("select").val().length){								
				//total_completed_form_elements++;
				data.is_completed_form_element = 1;
				status = 1;
			}
		}else if (field.attr("data-type") != "button_group" && input.val() ) {
			//total_completed_form_elements++;
			data.is_completed_form_element = 1;
			status = 1;
		} else if (field.attr("data-type") == "button_group" && field.find(".selected").length) {
			//total_completed_form_elements++;
			data.is_completed_form_element = 1;
			status = 1;
		} else {
			console.log("is not completed: ", field.attr("data-name"));
		}			
		
		input.attr("data-iscomplete",0);
		
		if (data.is_completed_form_element) {
			input.attr("data-iscomplete",1);
		}
			
		//so we can access later
		input.attr("data-formparent",form_id);
		
		//add event
		if( field.attr("data-type") == "button_group" || 
			field.attr("data-type") == "date_picker" || 
			field.attr("data-type") == "image"){
			input.on('change', trackProgress )
		}else{
			input.on('input select2:select select2:unselect select2:clear', trackProgress ); //end input event
		}
		return data;
	}

	function resetEventWait(input){
		console.log("resetEventWait");
		input.attr("data-wait", 0);
	}
	
	function trackProgress(event){
		
		console.log("trackProgress called");

		if(jQuery(this).attr("data-wait") == 1){
			console.log("WAITING");
			return;
		}
		jQuery(this).attr("data-wait", 1);
		let e = jQuery(this);
		let status = 0;
		let is_button_group = 0;
		
		//seems to be needed for select2 multiple	
		
		if(e.is("select") && e.val().length){
			status = 1;		
		}else if(!e.is("select") && e.val()){
			status = 1;	
		}else if(e.closest(".acf-button-group").find(".selected").length){
			status = 1;	
		}		
							
		let form = jQuery(jQuery(this).attr("data-formparent"));
		let tcfe = form.attr("data-totalcompletedformelements");
		let data_container =  e.closest(".progress-check").find('.data-container');
		let is_complete = jQuery(this).attr("data-iscomplete");
		console.log("is_complete", is_complete);
		
		if( is_complete == "1" ){ //status has changed
			if(status){
				//don't do anything. 
				console.log("s",1);
			}else{
				console.log("s",2);
				
				jQuery(this).attr("data-iscomplete", 0);									
				tcfe = Number(tcfe) - 1;
				form.attr("data-totalcompletedformelements", tcfe);
				updateFormProgress(form);
			}	
		}else {

			if(status){
				console.log("s",3);
			
				jQuery(this).attr("data-iscomplete", 1);
				tcfe = Number(tcfe) + 1;
				form.attr("data-totalcompletedformelements", tcfe);
				updateFormProgress(form);
				
			}else{
				console.log("s",4);
		
				jQuery(this).attr("data-iscomplete", 0);									
				tcfe = Number(tcfe) - 1;
				form.attr("data-totalcompletedformelements", tcfe);
				updateFormProgress(form);
			}
		}
		setTimeout(resetEventWait, 1000, jQuery(this));
		return;  //works
		return false; //does't work
	}

	function initProgressTracker() {
		
		jQuery('.acf-form').each(function () {
			
			let form = jQuery(this);
			let form_name = form.parent().attr("data-btnlabel");
			let total_form_elements = 0;
			let total_completed_form_elements = 0;
			let form_id = "#" + form.attr("id");
			
			jQuery(form_id + ' .progress-check').each(function () {
				
				let data = processProgressInput(jQuery(this),form_id );

				if(data.is_trackable_form_element){
					total_form_elements++;
				}

				if(data.is_completed_form_element){
					total_completed_form_elements++;
				}
			});
			
			//console.log("total form_elements :", form_name, total_form_elements);
			form.attr("data-totalformelements", total_form_elements);
			form.attr("data-totalcompletedformelements", total_completed_form_elements);

			updateFormProgress(form);			
			
		});
		
	}

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

</script>