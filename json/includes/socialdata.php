<html>
<head><title>Story Data for TC US</title><meta content="robots" value="noindex" />
<style>
body {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    }
td,th {border: 1px solid #dddddd;}
td {padding: 3px 5px;}
td.red {color:red;}
td.data {text-align:center;}
table {
    border-collapse: collapse;
    }
tr:nth-of-type(odd) {
	background-color: #f9f9f9;
}
.small {font-size: 12px;}
a {text-decoration:none;}
label {width: 120px;text-align:right;margin-right:10px;display:inline-block;}
form div {padding-top:8px;}
.text-input {width:500px;height:20px;font-size:14px;}
.date-input {width:200px;height:20px;font-size:14px;}
.submit-button {margin-top:12px;margin-left:40px;height:22px;width:80px;}

</style>
<script src="includes/sorttable.js"></script>
</head>
<body>

<?php

function get_social_counts($id) {
	global $context;
	$raw = file_get_contents("http://dw.theconversation.edu.au/social_by_content.json?content_id=".$id,FALSE, $context);
//	_echo_r($raw);
	$data = json_decode($raw)->results[0];
	$storydata['twitter'] = $data->tweet_count;
	$storydata['linkedin'] = $data->linked_in_count;
	$storydata['facebook'] = $data->facebook_count;
//	_echo_r($storydata);
	return $storydata;
}



if (isset($_GET['institution'])) {
	$conn = connect_abramsweb_tc();
	$school = $institution_id = $_GET['institution'];
	$school = substr($school,41,strrpos($institution_id,"-")-41);
	$school = str_replace('institutions/','',$school);
	if (strlen($institution_id) > 4) {
		$institution_id = substr($institution_id,(strrpos($institution_id,"-")+ 1));
		}

	$url = "http://dw.theconversation.edu.au/views_by_content.json?content_institution_id=".$institution_id;
	$raw = file_get_contents($url,FALSE, $context);
	$stories = json_decode($raw);
	//_echo_r($stories);

	echo "<h3>Story Data</h3>";
	echo "<table class='sortable'><thead><tr><th class='headline'>Article</th><th>Section</th><th>Topics</th><th>Authors</th><th>Pubdate</th><th>TC PVs</th><th class='total'>Repub PVs</th><th>Facebook</th><th>Twitter</th><th>LinkedIn</th></tr></thead>";
	$data = "Article,URL,Section,Topics,Authors,Date,TC PVs,Repub PVs,Facebook,Twitter,LinkedIn shares\n";

	foreach ($stories->results as $thisstory) {
		$story = get_story_info($thisstory->content_id);
		//_echo_r($story);
		$socialdata = get_social_counts($thisstory->content_id);
		echo "<tr><td class='headline'><a href='".$story['url']."' target='_blank'>".htmlspecialchars_decode($story['headline'])."</a> <span class='small'>(<a href='https://tc-deep-diver.herokuapp.com/views_by_referrer?content_id=".$thisstory->content_id."' target='_blank'>URLs</a>)</span></td><td>".$story['section']."</td><td>".$story['topics']."</td><td>".$story['byline']."</td><td>".$story['pubdate']."</td><td>".number_format($repubdata['tc'])."</td><td>".number_format($repubdata['total'])."</td><td>".number_format($socialdata['facebook'])."</td><td>".number_format($socialdata['twitter'])."</td><td>".number_format($socialdata['linkedin'])."</td></tr>";

		$data .= '"'.htmlspecialchars_decode($story['headline']).'",'. $story['url'].',"'.$story['section'].'","'.$story['topics'].'","'.$story['byline'].'","'.$story['pubdate'].'","'.number_format($repubdata['tc']).'","'.number_format($repubdata['total']).'","'.number_format($socialdata['facebook']).'","'.number_format($socialdata['twitter']).'","'.number_format($socialdata['linkedin']).'"'."\n";

		}
	close_abramsweb_tc($conn);
	echo "</table>";
	echo "<p>Click any header to sort by that field</p>";
	echo "<p>Login info for URL details is datawarehouse/c0nversation</p>";

	$data = str_replace("<br />",", ",$data);
	$data = str_replace("<sup>*</sup>","*",$data);
	$filename = 'data/'.$school.'-story-data-'.date('Y-m-d-Hi').".csv";
	save_file($filename,$data);
	echo "<h3><a href=".$filename.">Download .csv</a></h3>";
	}

//http://dw.theconversation.edu.au/views_by_content.json?content_institution_id=2138
?>
<h3>Institution Social Data Dump</h3>
<form action="socialdata.php" method="GET">
<div>Paste in the entire URL from the institution page<br>
<label>Institution:</label> <input name="institution" value="" type="text" class="text-input"/></div>
<input class="submit-button" type="submit" /><br>
</form>
</body>
</html>
