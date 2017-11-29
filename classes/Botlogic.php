<?php

/**
* 
*/
class Botlogic
{	

	var $response_template = array(
		"speech" => "",
		"displayText" => "",
		"data" => array(),
		"contextOut" => "",
		"source" => "",
	);

	function __construct() {

	}

	function find_intent($data)
	{
		return $data['result']['action'];
	}

	function format_reponse($speech = '', $display_text = '', $data = array(), $context_out = array(), $source = '') {
		$this->response_template = array(
			"speech" => $speech,
			"displayText" => $display_text,
			"data" => $data,
			"contextOut" => $context_out,
			"source" => $source
		);

		return $this->response_template;
	}

	function get_winning_pie($pie_data)
	{
		$winner = reset($pie_data);

		return $this->format_reponse(
			'The best mince pie, according to experts, is ' . $winner['name'], 
			'The best mince pie, according to experts, is ' . $winner['name'], 
			$winner
		);
	}

	function get_leaderboard($pie_data)
	{
		$leaderboard = 'Current leaderboard: ';

		$leaderboard_pies = array();

		foreach ($pie_data as $data) {
			$leaderboard_pies[] = $data['name'] . ' with ' . $data['averages']['non_rounded_rating'];
		}

		$leaderboard .= implode(', ', $leaderboard_pies);

		return $this->format_reponse(
			$leaderboard, 
			$leaderboard, 
			$pie_data
		);
	}

}