<?php

// This script defines all the necessary constants and functions to run Playforia/Aapeli Java games in multiple languages.
// Each game has one index.php file that requires this script and that fills out three applet tag parameters depending on the language
// that was passed. For example: http://www.playforia.net/play/serpent/index.php?language=finnish

// Maps a language to an associative array that in turn maps three applet tag parameters to their respective language dependent value.
const PLAYFORIA_LANGUAGE_PARAMETERS = array(
	'english' 		=> array('locale' => 'en', 'lang' => 'en_US', 'initmessage' => 'Loading game...'),
	'finnish' 		=> array('locale' => 'fi', 'lang' => 'fi_FI', 'initmessage' => 'Ladataan peliä...'),
	'dutch' 		=> array('locale' => 'nl', 'lang' => 'nl_NL', 'initmessage' => 'Spel inladen...'),
	'estonian' 		=> array('locale' => 'ee', 'lang' => 'et_EE', 'initmessage' => 'Laen mängu....'),
	'french' 		=> array('locale' => 'fr', 'lang' => 'fr_FR', 'initmessage' => 'Chargement du jeu...'),
	'german' 		=> array('locale' => 'de', 'lang' => 'de_DE', 'initmessage' => 'Spiel lädt gerade...'),
	'hungarian' 	=> array('locale' => 'hu', 'lang' => 'hu_HU', 'initmessage' => 'Játék betöltése...'),
	'italian' 		=> array('locale' => 'it', 'lang' => 'it_IT', 'initmessage' => 'Caricamento gioco...'),
	'latvian' 		=> array('locale' => 'lv', 'lang' => 'lv_LV', 'initmessage' => 'Spēle ielādējas...'),
	'lithuanian' 	=> array('locale' => 'lt', 'lang' => 'lt_LT', 'initmessage' => 'Siunčiamas žaidimas...'),
	'norwegian' 	=> array('locale' => 'no', 'lang' => 'no_NO', 'initmessage' => 'Laster spill...'),
	'polish' 		=> array('locale' => 'pl', 'lang' => 'pl_PL', 'initmessage' => 'Ładowanie gry..'),
	'russian' 		=> array('locale' => 'ru', 'lang' => 'ru_RU', 'initmessage' => 'Загрузка игры...'),
	'spanish' 		=> array('locale' => 'es', 'lang' => 'es_ES', 'initmessage' => 'Cargando el juego...'),
	'swedish' 		=> array('locale' => 'se', 'lang' => 'sv_SE', 'initmessage' => 'Laddar spel...'),

	// Unlisted languages - these didn't have dedicated Playforia subdomains which means they weren't embedded
	// on a page, which means there's no initmessage parameter.
	// Turkish is the only one to have the relevant XML files fully translated. The rest are in English.
	'bulgarian' 	=> array('locale' => 'bg', 'lang' => 'bg_BG', 'initmessage' => 'Loading game...'),
	'portuguese' 	=> array('locale' => 'pt', 'lang' => 'pt_PT', 'initmessage' => 'Loading game...'),
	'romanian' 		=> array('locale' => 'ro', 'lang' => 'ro_RO', 'initmessage' => 'Loading game...'),
	'turkish' 		=> array('locale' => 'tr', 'lang' => 'tr_TR', 'initmessage' => 'Loading game...'),

	// For unknown languages. This makes it easier to catch mistakes since you'd see the game request a file called LANGUAGE_NOT_SUPPORTED.xml.
	'not-supported'	=> array('locale' => 'LANGUAGE_NOT_SUPPORTED', 'lang' => 'LANGUAGE_NOT_SUPPORTED', 'initmessage' => 'LANGUAGE_NOT_SUPPORTED')
);

// Returns the associative array for a given language. If the language is NULL, it returns the one for English. If the language isn't
// valid, it returns a special 'not-supported' associative array.
function get_playforia_language_parameters($language) {
	$language = $language ?? 'english';
	return PLAYFORIA_LANGUAGE_PARAMETERS[$language] ?? PLAYFORIA_LANGUAGE_PARAMETERS['not-supported'];
}

?>