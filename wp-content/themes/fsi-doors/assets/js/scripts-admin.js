jQuery(document).ready(function ($) {	
	console.log("scripts-admin.js");

	//this copies the acf selected field to the clipboard
	$('body').on('click', '.acf-selection', function () {
		let txt = $(this).text();
		copyText(txt);
	});

});


function copyText(txt) {
	const el = document.createElement('textarea');
	el.value = txt;
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);
}