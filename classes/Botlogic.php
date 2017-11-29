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
		return $data->result->logic;
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

}