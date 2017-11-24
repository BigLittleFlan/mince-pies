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
		list($timestamp, $pie_name, $ppp, $taste, $looks, $tasting_notes) = $data;
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
		// echo $pie_name . ' gets a score of: ' . $pie_value . "\n";

		$pies[] = array(
			'name' =>$pie_name,
			'rating' => $pie_value
		);
	}	

	return $pies;
}

// Routes
$app->get('/', function (Request $request, Response $response, array $args) {
	$args['pies'] = get_pie_ratings();

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/json', function (Request $request, Response $response, array $args) {
	$pies = get_pie_ratings();

    // Render index view
    return $response->withJson($pies);
});
