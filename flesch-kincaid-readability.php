<?php
/**
*Do Not Remove this comment
* Flesch-Kincaid-Readability-Test implementation in PHP by Godwin Ojebiyi (http://www.godwinojebiyi.com)
*/

class Pattern{

	# These patterns would be normally counted as two syllables but SHOULD be one syllable. May be incomplete; do not modify.
	public $subtract_syllable_patterns = Array(
		"cia(l|$)",
		"tia",
		"cius",
		"cious",
		"[^aeiou]giu",
		"[aeiouy][^aeiouy]ion",
		"iou",
		"sia$",
		"eous$",
		"[oa]gue$",
		".[^aeiuoycgltdb]{2,}ed$",
		".ely$",
		"^jua",
		"uai",
		"eau",
		"[aeiouy](b|c|ch|d|dg|f|g|gh|gn|k|l|ll|lv|m|mm|n|nc|ng|nn|p|r|rc|rn|rs|rv|s|sc|sk|sl|squ|ss|st|t|th|v|y|z)e$",
		"[aeiouy](b|c|ch|dg|f|g|gh|gn|k|l|lch|ll|lv|m|mm|n|nc|ng|nch|nn|p|r|rc|rn|rs|rv|s|sc|sk|sl|squ|ss|th|v|y|z)ed$",
		"[aeiouy](b|ch|d|f|gh|gn|k|l|lch|ll|lv|m|mm|n|nch|nn|p|r|rn|rs|rv|s|sc|sk|sl|squ|ss|st|t|th|v|y)es$",
		"^busi$"
	);

	# These patterns might be counted as one syllable according to $subtract_syllable_patterns
	# and the base rules but SHOULD be two syllables. May be incomplete; do not modify.
	public $add_syllable_patterns = Array(
		"([^s]|^)ia",
		"iu",
		"io",
		"eo($|[b-df-hj-np-tv-z])",
		"ii",
		"[ou]a$",
		"[aeiouym]bl$",
		"[aeiou]{3}",
		"[aeiou]y[aeiou]",
		"^mc",
		"ism$",
		"asm$",
		"thm$",
		"([^aeiouy])\1l$",
		"[^l]lien",
		"^coa[dglx].",
		"[^gq]ua[^auieo]",
		"dnt$",
		"uity$",
		"[^aeiouy]ie(r|st|t)$",
		"eings?$",
		"[aeiouy]sh?e[rsd]$",
		"iell",
		"dea$",
		"real",
		"[^aeiou]y[ae]",
		"gean$",
		"riet",
		"dien",
		"uen"
	);

	# Single syllable prefixes and suffixes. May be incomplete; do not modify.
	public $prefix_and_suffix_patterns = Array(
		"^un",
		"^fore",
		"^ware",
		"^none?",
		"^out",
		"^post",
		"^sub",
		"^pre",
		"^pro",
		"^dis",
		"^side",
		"ly$",
		"less$",
		"some$",
		"ful$",
		"ers?$",
		"ness$",
		"cians?$",
		"ments?$",
		"ettes?$",
		"villes?$",
		"ships?$",
		"sides?$",
		"ports?$",
		"shires?$",
		"tion(ed)?$"
	);

	# Specific common exceptions that don't follow the rule set below are handled individually.
	# The correct syllable count is the value. May be incomplete; do not modify.
	public $problem_words = Array(
		'abalone' => 4,
		'abare' => 3,
		'abed' => 2,
		'abruzzese' => 4,
		'abbruzzese' => 4,
		'aborigine' => 5,
		'acreage' => 3,
		'adame' => 3,
		'adieu' => 2,
		'adobe' => 3,
		'anemone' => 4,
		'apache' => 3,
		'aphrodite' => 4,
		'apostrophe' => 4,
		'ariadne' => 4,
		'cafe' => 2,
		'calliope' => 4,
		'catastrophe' => 4,
		'chile' => 2,
		'chloe' => 2,
		'circe' => 2,
		'coyote' => 3,
		'epitome' => 4,
		'forever' => 3,
		'gethsemane' => 4,
		'guacamole' => 4,
		'hyperbole' => 4,
		'jesse' => 2,
		'jukebox' => 2,
		'karate' => 3,
		'machete' => 3,
		'maybe' => 2,
		'people' => 2,
		'recipe' => 3,
		'sesame' => 3,
		'shoreline' => 2,
		'simile' => 3,
		'syncope' => 3,
		'tamale' => 3,
		'yosemite' => 4,
		'daphne' => 2,
		'eurydice' => 4,
		'euterpe' => 3,
		'hermione' => 4,
		'penelope' => 4,
		'persephone' => 4,
		'phoebe' => 2,
		'zoe' => 2
	);
}

class Readability {
	function countSyllable($text) {
	    $pattern = new Pattern();
	    $text = trim($text);

	    // Check for problem words
	    if (isset($pattern->{'problem_words'}[$text])) {
	        return $pattern->{'problem_words'}[$text];
	    }

	    // Check prefix, suffix
	    $text = str_replace($pattern->{'prefix_and_suffix_patterns'}, '', $text, $tmpPrefixSuffixCount);

	    // Check for non word characters and remove them
	    $arrWordParts = preg_split('`[^aeiouy]+`', $text);
	    $wordPartCount = 0;
	    foreach ($arrWordParts as $textPart) {
	        if ($textPart <> '') {
	            $wordPartCount++;
	        }
	    }

	    $intSyllableCount = $wordPartCount + $tmpPrefixSuffixCount;

	    // Check syllable patterns 
	    foreach ($pattern->{'subtract_syllable_patterns'} as $strSyllable) {
	        $intSyllableCount -= preg_match('`' . $strSyllable . '`', $text);
	    }

	    foreach ($pattern->{'add_syllable_patterns'} as $strSyllable) {
	        $intSyllableCount += preg_match('`' . $strSyllable . '`', $text);
	    }

	    $intSyllableCount = ($intSyllableCount == 0) ? 1 : $intSyllableCount;
	    return $intSyllableCount;
	}

	function ease_score($text) {
	    # Calculate score
	    $totalSentences = 0;
	    $punctuationMarks = array('.', '!', ':', ';');

	    foreach ($punctuationMarks as $punctuationMark) {
	        $totalSentences += substr_count($text, $punctuationMark);
	    }

	    //To cater for Writing Samples with only one word and no punctuation mark (This is to avoid zero division error).
	    if($totalSentences == 0){
	    	$totalSentences = 1;
	    }

	    // calculate ASL
	    $totalWords = str_word_count($text);
	    $ASL = $totalWords / $totalSentences;

	    // Count syllables
	    $syllableCount = 0;
	    $arrWords = explode(' ', $text);
	    $intWordCount = count($arrWords);
	    //$intWordCount = $totalWords;

	    for ($i = 0; $i < $intWordCount; $i++) {
	        $syllableCount += $this->countSyllable($arrWords[$i]);
	    }

	    // calculate ASW
	    $ASW = $syllableCount / $totalWords;

	    // Implement Formula
	    $score = 206.835 - (1.015 * $ASL) - (84.6 * $ASW);
	    return $score;
	} 
}

?>