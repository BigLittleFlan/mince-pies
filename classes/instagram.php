<?php

/**
* 
*/
class Instagram
{	
	var $feed_data;

	function __construct() {
		$data = file_get_contents("https://www.instagram.com/mincepierating/?__a=1");
		$this->feed_data = json_decode($data);	
	}

	function get_profile_pic()
	{
		return $this->feed_data->user->profile_pic_url_hd;
	}

	function get_media_feed()
	{
		$feed = array();

		$images = $this->feed_data->user->media->nodes;
		foreach ($images as $image) {
			$feed[] = array(
				'image' => $image->display_src,
				'caption' => $image->caption
			);
		}
		
		return $feed;
	}

}