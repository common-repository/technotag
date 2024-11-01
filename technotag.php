<?php
/*
Plugin Name: TechnoTag
Plugin URI: http://www.gudlyf.com/archives/2005/01/14/wordpress-plugin-technotag/
Description: Allows <a href="http://www.technorati.com/help/tags.html">Technorati tags</a> within &lt;tag&gt; &lt;/tag&gt;.  Use &lt;tag word="tag word"&gt; to use a different tag word than what's between the tags.  Will also allow adding 'ttag' custom values for the post for listing at the end of each post and/or 'ttaglist' of comma seperated words.
Version: 1.2.4
Author: Keith McDuffee
Author URI: http://www.gudlyf.com
*/ 

$ttag_image = get_settings('siteurl') . '/wp-content/plugins/technobubble.gif';
$ttag_image_small = get_settings('siteurl') . '/wp-content/plugins/technosquare.gif';
$ttag_link = "http://www.technorati.com/tag/";
$ttag_alt = "Technorati Tag";

function technotag($text) {

        global $ttag_image_small, $ttag_link;

	$long_pattern = '/<ttag word="(.*?)">(.*?)<\/ttag>/i';
	$short_pattern = '/<ttag>(.*?)<\/ttag>/i';
	preg_match ($long_pattern, $text , $my_long);
	$insert_long = urlencode($my_long[1]);
	preg_match ($short_pattern, $text , $my_short);
	$insert_short = urlencode($my_short[1]);
	$text = preg_replace($long_pattern,'<a href="' . $ttag_link . urlencode($insert_long) . '" rel="tag">$2<img border="0" alt="' . $ttag_alt . '" src="' . $ttag_image_small . '" /></a>',$text);
	$text = preg_replace($short_pattern,'<a href="' . $ttag_link . $insert_short . '" rel="tag">$1<img border="0" alt="' . $ttag_alt . '" src="' . $ttag_image_small . '" /></a>',$text);

        return $text;
}

function ttags($text) {

        global $ttag_image, $ttag_link;

	$ttaglist = get_post_custom_values('ttaglist');
        $ttagvals = get_post_custom_values('ttag');

	if(!empty($ttaglist)) {
		$ttags = preg_split("/[\s,]+/", $ttaglist[0]);
		$ttags = array_merge($ttags, $ttagvals);
	} else {
		$ttags = $ttagvals;
	}

        if(!empty($ttags)) {

                $ttags_list = '<span class="ttag">';
                $ttags_list = $ttags_list . '<img src="' . $ttag_image . '" alt="Technorati Tags" /> ';

                foreach ($ttags as $ttag) {
                        $ttag_nospace = str_replace(" ", "+", $ttag);
                        $ttags_list = $ttags_list . '<a href="' . $ttag_link . $ttag_nospace . '" rel="tag">'.$ttag.'</a>, ';
                }

		$ttags_list = rtrim($ttags_list, ", ");
        
                $ttags_list = $ttags_list . '</span>';

                $text = $text . $ttags_list;
        }

        return $text;
}

add_filter('the_content', 'technotag');
add_filter('the_content', 'ttags');
add_filter('comment_text', 'technotag');

?>
