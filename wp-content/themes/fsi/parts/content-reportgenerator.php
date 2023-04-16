
<?php

//echo "<input id='building-search' type='text' name='Search' />";
//global $wpdb;
//echo $wpdb->prefix; exit;
?>

<div class="rp-head">
	<h1>Search for existing building</h1>
    <a href="/report-generator/create">New Report</a>
</div>
<input id='building-search' type='text' name='Search' />

<div id="response"></div>

<script>

    jQuery("#building-search").on("input", function () {

        let text_data = jQuery(this).val();
        let json_data = { "search_text": text_data };
        jQuery.ajax({
            url:"<?php echo admin_url('admin-ajax.php'); ?>?action=get_building",
            type: 'post',
            data: json_data,
            success: function (data) {
                jQuery("#response").empty();
                jQuery("#response").append(data);
                return;
            },
            error: function (data) {
                console.log("FAILURE");
            }
        });

    });

</script>