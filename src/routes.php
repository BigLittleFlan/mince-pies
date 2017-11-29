<?php

use Slim\Http\Request;
use Slim\Http\Response;

//Work out the average of the judges scores.
function calculate_average($index, $scores)
{
	$count = $total = 0;

	foreach ($scores as $value) {
		$count++;

		$total += $value[$index];
	}

	return $total/$count;
}

function get_pie_ratings() {
	/**
	 * An algorithm to work out the final rating of a mince pie based on looks
	 * taste and price per pie.
	 **/
	define("TASTE_RATIO", 0.8);
	define("LOOKS_RATIO", 0.2);
	define("PI_SQUARED", pow(pi(),2));

	define("CSV_URL", "https://docs.google.com/spreadsheets/d/e/2PACX-1vQz6-FbIoiOGf6l0bnfeay0kCHunRhOp-KzXRlbqS0hKLNu1hqjysvCIiKJKetAKl6CGF1fa92jQuvO/pub?gid=2097867721&single=true&output=csv");

	$data = file_get_contents(CSV_URL);
	$rows = explode("\n",$data);
	array_shift($rows);

	$user_data = array();
	foreach($rows as $row) {
	    $user_data[] = str_getcsv($row);
	}

	$pie_gradings = array();

	foreach ($user_data as $data) {
		list($timestamp, $pie_name, $ppp_old, $taste, $looks, $tasting_notes, $ppp) = $data;
		$pie_gradings[$pie_name]['ppp'] = $ppp;
		$pie_gradings[$pie_name]['scores'][] = array(
			't' => $taste,
			'l' => $looks
		);
	}

	$pies = array();
	// evaluate the pies!
	foreach ($pie_gradings as $pie_name => $values) {

		// Find the average of the scores
		$average_taste = calculate_average('t', $values['scores']);
		$average_loook = calculate_average('l', $values['scores']);

		$pie_value = round( ( (TASTE_RATIO*$average_taste) + (LOOKS_RATIO*$average_loook) ) - ($values['ppp']/PI_SQUARED));
		$pie_raw_value = ( (TASTE_RATIO*$average_taste) + (LOOKS_RATIO*$average_loook) ) - ($values['ppp']/PI_SQUARED);

		$pies[] = array(
			'name' =>$pie_name,
			'rating' => $pie_value,
			'non_rounded_rating' => number_format($pie_raw_value, 2, '.', '')
		);
		$ratings[] = $pie_raw_value;
	}	

	array_multisort($ratings, SORT_DESC, $pies);

	return $pies;
}

// Routes
$app->get('/', function (Request $request, Response $response, array $args) {
	$args['pies'] = get_pie_ratings();

	$args['instagram'] = array();

	$data = file_get_contents("https://www.instagram.com/mincepierating/?__a=1");
	$data = json_decode($data);

	$args['profile_pic'] = $data->user->profile_pic_url_hd;

	$images = $data->user->media->nodes;
	foreach ($images as $image) {
		$args['instagram'][] = array(
			'image' => $image->display_src,
			'caption' => $image->caption
		);
	}

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/json', function (Request $request, Response $response, array $args) {
	$pies = get_pie_ratings();

    // Render index view
    return $response->withJson($pies);
});


$app->get('/chatbot', function (Request $request, Response $response, array $args) {
	
	// // $request_body = json_decode($request->body);
	// // $action = $request_body->action;

	// $return_data = json_decode ('{
	// 		"speech": "Barack Hussein Obama II was the 44th and current President of the United States.",
	// 		"displayText": "Barack Hussein Obama II was the 44th and current President of the United States, and the first African American to hold the office. Born in Honolulu, Hawaii, Obama is a graduate of Columbia University   and Harvard Law School, where ",
	// 		"data": {},
	// 		"contextOut": [],
	// 		"source": "DuckDuckGo"
	// 		}', TRUE);

	// $pies = get_pie_ratings();
	// $return_data['data'] = $pies;

 //    // Render index view
 //    return $response->withJson(array('body' => $return_data));
});

