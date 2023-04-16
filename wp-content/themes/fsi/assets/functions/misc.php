<?php




//Remove white space at the end of wysiwyg strings
add_filter('acf/update_value/type=wysiwyg', function($value){
	//preg_replace below only works if the wpautop function has been run
	$value = wpautop($value);
	$value = force_balance_tags($value);
	$value = preg_replace('/<p>(?:\s|&nbsp;)*?<\/p>/i', '', $value);
	$value = trim($value);
	return $value;
});

//dealing with strings
function hyphenize($string) {
    $dict = array(
        "I'm"      => "I am",
        "thier"    => "their",
        // Add your own replacements here
    );
    return strtolower(
        preg_replace(
          array( '#[\\s-]+#', '#[^A-Za-z0-9. -]+#' ),
          array( '-', '' ),
          // the full cleanString() can be downloaded from http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
          cleanString(
              str_replace( // preg_replace can be used to support more complicated replacements
                  array_keys($dict),
                  array_values($dict),
                  urldecode($string)
              )
          )
        )
    );
}

function cleanString($text) {
    $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}