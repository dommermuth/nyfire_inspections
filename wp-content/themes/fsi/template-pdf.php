<?php

/*
Template Name: PDF
*/
require __DIR__ . '/vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf;

if( !is_user_logged_in() || !current_user_can( 'edit_posts' )){
	$site_url = get_site_url();
    $url = $site_url . "/wp-login.php";
    wp_redirect($url);
    exit;
}

$pid = ( isset( $_GET['pid'] ) ) ? sanitize_text_field( $_GET['pid'] ) : '';

if (is_numeric($pid)) {

    $report = get_post($pid);

    //wkhtmltopdf options: https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
    /*
	 install via cli ubuntu:
     cd ~
	 wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.focal_amd64.deb
	 sudo apt install ./wkhtmltox_0.12.6-1.focal_amd64.deb

    specifying font size is crucial (in parent element)
	*/

	$pdf = new Pdf(array(
            'no-outline',         // Make Chrome not complain
            'margin-top'    => '1cm',
            'margin-right'  => '1.5cm',
            'margin-bottom' => '1cm',
            'margin-left'   => '1.5cm',
            'load-error-handling' => 'ignore',
            'orientation'   => 'portrait',
            'footer-center' => '[page] of [topage] - ' . $report->post_title,
            'footer-font-size' => 8,
            'encoding' => 'utf-8',
			'image-dpi' => 300
            //'footer-right' => 'Page [page] of [toPage]',
            //'header-spacing' => 6,
            // Default page options

        ));

	//invoices/school/classes/{year}/class/{class}
	$site_url = get_site_url();
    $url = $site_url . "/";
	$pdf->addPage($url.'report?pid='.$pid);

	if (!$pdf->send()) {
		//add file name here if want to generate pdf for download
		$error = $pdf->getError();
		echo $error;
	}

}else{

    $site_url = get_site_url();
    $url = $site_url . "/";
    wp_redirect($url);
    exit;
}

?>