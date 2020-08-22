<?php


global $totalcount;

function get_social_counts($id) {
	global $context;
	global $totaltwitter;
	global $totalfacebook;
	global $totallinkedin;
	$raw = file_get_contents("http://dw.theconversation.edu.au/social_by_content.json?content_id=".$id,FALSE, $context);
  //_echo_r($raw);
	$data = json_decode($raw)->results[0];
	$storydata['twitter'] = $data->tweet_count;
	//$storydata['linkedin'] = $data->linked_in_count;
	$storydata['facebook'] = $data->facebook_count;


	print_r("Total Twitter: ".$totaltwitter += $data->tweet_count);
	print_r(" - Total Facebook: ".$totalfacebook += $data->facebook_count);
	print_r(" - Total LinkedIn: ".$totallinkedin += $data->linked_in_count);
	//print_r(" --- ");
	$totalfacebook += $data->facebook_count;
	//	_echo_r($storydata);
	return $storydata;
}

function get_region_content($region_id) {
	global $context2;


	$raw2 = file_get_contents("http://dw.theconversation.edu.au/views_by_content.json?region_id=".$region_id,FALSE, $context2);
  // echo $raw2;
	$data2 = json_decode($raw2)->results;
	foreach ($data2 as $key => $value) {
		//$regiondata['content_label'] = $value->content_label;
		// $regiondata['content_label'] = "<a href='https://theconversation.com/article-".$value->content_id."'>".$value->content_label.'</a>';
		$regiondata['content_id'] = $value->content_id;
		//$regiondata['count'] = $value->count;
		$totalcount += $value->count;
		$regiondata['social'] = get_social_counts($value->content_id);
		var_dump($regiondata);
	}
	echo "Total Views: ".$totalcount;
}


get_region_content('38');

// var_dump(get_region_content('38'));

// $raw = file_get_contents("http://dw.theconversation.edu.au/social_by_content.json?content_id=".$id,FALSE, $context);

// $csv = array_map('str_getcsv', file('content_id_export.csv'));
// $csv_content = $csv[0];
//
// foreach ($csv_content as $value) {
//
// 		var_dump(get_social_counts($value));
//     //echo "Value: $value<br />\n";
// }


//var_dump(get_social_counts($id));
// $array = json_decode($json);
//
// $urlPoster=array();
// foreach ($array as $value) {
//     $urlPoster[]=$value->urlPoster;
// }
// ini_set("allow_url_fopen", 1);
// print_r($urlPoster);
?>
