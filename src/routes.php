<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function (Request $request, Response $response, array $args) {
	
	$mincepierating = new Mincepie();
	$args['pies'] = $mincepierating->get_pie_ratings();

	$instagram = new Instagram();
	$args['profile_pic'] = $instagram->get_profile_pic();
	$args['instagram'] = $instagram->get_media_feed();

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/json', function (Request $request, Response $response, array $args) {
	$mincepierating = new Mincepie();
	$pies = $mincepierating->get_pie_ratings();

    // Render index view
    return $response->withJson($pies);
});

$app->get('/chatbot', function (Request $request, Response $response, array $args) {});
