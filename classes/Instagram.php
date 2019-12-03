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

                $images = $this->feed_data->graphql->user->edge_owner_to_timeline_media->edges;

                foreach ($images as $image) {

                        $feed[] = array(
                                'image' => $image->node->display_url,
                                'caption' => $image->node->edge_media_to_caption->edges[0]->node->text
                        );
                }

                return $feed;
        }

}