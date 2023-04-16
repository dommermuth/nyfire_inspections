<?php

	global $post;
	$postid = $post->ID;

    // Do stuff

	if ( is_home()  )  {
		$postid = 21;
	}

	if('sales-team' == get_post_type()) {
		$postid = 3605;

	}else if(is_single() || is_search()) {
		$postid = 3428;
	}

    $hero_height = get_field('hero_height', $postid);

    //repeater for slider
    if( have_rows('slides', $postid) ):

        // loop through the rows of data
        $slide_ar = [];
        if(!empty(get_field('slide_show_delay', $postid))){
            $slide_show_delay = get_field('slide_show_delay', $postid);
        }else{
            $slide_show_delay = 4;
        }
        while ( have_rows('slides', $postid) ) :

            the_row();

            if(get_sub_field('show_slide')){

                $tmp_ar = [];
                $tmp_ar['image']        = get_sub_field('image');
                $tmp_ar['show_callout'] = get_sub_field('show_callout');
                $tmp_ar['callout']      = get_sub_field('callout');

                $vert_pos = get_sub_field('vertical_position');
                $hori_pos = get_sub_field('horizontal_position');

                if(empty($vert_pos)){
                    $vert_pos = "50%";
                }

                if(empty($hori_pos)){
                    $hori_pos = "50%";
                }

                $tmp_ar['vert_pos'] = $vert_pos;
                $tmp_ar['hori_pos'] = $hori_pos;

                $slide_ar[] = $tmp_ar;
            }


        endwhile;

?>

    <!-- HERO SLIDER -->


    <div class="slideshow-container-se" style="height:<?php echo $hero_height; ?>px;" onmouseenter="pause_slideshow()" onmouseleave="restart_slideshow()">


        <!-- Full-width images with number and caption text -->
        <?php
              $slide_count = 1;
            foreach($slide_ar as $slide):
        ?>

        <div class="mySlides fade" data-slideid="<?php echo $slide_count; ?>" style="background-image: url(<?php echo $slide['image']; ?>); background-repeat: no-repeat; background-position:<?php echo $slide['hori_pos']; ?> <?php echo $slide['vert_pos']; ?>; height:<?php echo $hero_height ; ?>px;">
            <div class="row" style="height:<?php echo $hero_height ; ?>px;">
               <div class="inner" >
                    <?php
                        if($slide['show_callout']):
                            echo $slide['callout'];
                        endif;
                    ?>
               </div>
            </div>
        </div>

        <?php
            $slide_count++;
            endforeach;
        ?>

        <?php if (count($slide_ar) > 1): ?>
        <!-- The dots/circles -->
        <div class="dots" style="text-align:center">

            <?php
                  $slide_count = 1;
                  foreach($slide_ar as $slide):
            ?>
            <span class="dot" onclick="currentSlide(<?php echo $slide_count; ?>)"></span>
            <?php
                      $slide_count++;
                  endforeach;
            ?>
        </div>
        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>

        <?php endif; ?>
    </div>

    <script>

        var total_slides = <?php echo ($slide_count-1); ?>;
        var slide_show_delay = <?php echo $slide_show_delay; ?>;
        var slideIndex = 1;
        var slider_timer;
        var slideshow_running = 1;
        showSlides(slideIndex);

        // Next/previous controls
        function plusSlides(n) {        
            showSlides(slideIndex += n);
        }

        // Thumbnail image controls
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function autoplay() {
            var n = slideIndex;
            if(n+1 > total_slides){
                n = 0;
            }
            n = n+1;
            showSlides(slideIndex = n);
            slider_timer = setTimeout( autoplay, slide_show_delay*1000 );
        }
        slider_timer = setTimeout( autoplay, slide_show_delay*1000 );

        function pause_slideshow(){ 
            //console.log('pause');
            slideshow_running = 0;
            clearInterval(slider_timer); 
        }

        function restart_slideshow(){
            if(slideshow_running === 0){
                slideshow_running = 1;
                slider_timer = setTimeout( autoplay, slide_show_delay*1000 );
            }
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            

            if (typeof slides[slideIndex - 1] != "undefined") {
                slides[slideIndex - 1].style.display = "block";
            }

            if (typeof dots[slideIndex - 1] != "undefined") {
                dots[slideIndex - 1].className += " active";
            }
        }
    </script>

<?php endif; ?>