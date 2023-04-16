<?php

global $blog_id;
$current_blog_id = $blog_id;
switch_to_blog(1);

if(IS_IN_MAINTENANCE_MODE){

    $serious_email_debug_mode = get_field('serious_email_debug_mode','option'); //0 = On and  1 = Off
    $salesforce_mode = get_field('salesforce_mode','option'); //0 = Debug and  1 = Live
    echo "<div class='maintence-mode'>";
    echo "Site is in maintenance mode<br>";

    if(LEAD_RESOURCE == 0){
        if($serious_email_debug_mode == 0 && LEAD_RESOURCE == 0){
            echo "Serious Email is in DEBUG mode<br>";
        }else{
            echo "Serious Email is in LIVE mode<br>";
        }
    }

    if(LEAD_RESOURCE == 1){

        if($salesforce_mode == 2){
            echo "SALESFORCE is in PRODUCTION mode<br>";
        }else if($salesforce_mode == 1){
            echo "SALESFORCE is in STAGING mode<br>";
        }else{
            echo "SALESFORCE is in DEV mode<br>";
        }

        //$api = new SalesForceAPI(NULL,  $salesforce_mode);
        //$api_data = $api->getDebugInfo();
        //var_dump($api_data);
    }

    echo "</div>";

}

switch_to_blog($current_blog_id);

?>