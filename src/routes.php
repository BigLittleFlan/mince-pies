<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function (Request $request, Response $response, array $args) {
	
	$mince_pie_rating = new Mincepie();
	$args['pies'] = $mince_pie_rating->get_pie_ratings();

	$instagram = new Instagram();
	$args['profile_pic'] = $instagram->get_profile_pic();
	$args['instagram'] = $instagram->get_media_feed();

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/json', function (Request $request, Response $response, array $args) {
	$mince_pie_rating = new Mincepie();
	$pies = $mince_pie_rating->get_pie_ratings();

    // Render index view
    return $response->withJson($pies);
});

$app->post('/chatbot', function (Request $request, Response $response, array $args) {
	
	$mince_pie_rating = new Mincepie();
	$bot_logic = new Botlogic();

	$parsed_body = $request->getParsedBody();

	$data = array(
		'intent' => $bot_logic->find_intent($parsed_body), 
		'pies' => $mince_pie_rating->get_pie_ratings()
	);

	$bot_response = $bot_logic->format_reponse(
		'This is the speech',
		'This is the display_text',
		$data
	);

    // Render index view
    return $response->withJson($bot_response);	
});
