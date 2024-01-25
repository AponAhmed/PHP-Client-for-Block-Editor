<?php

namespace Aponahmed\HtmlBuilder;

class ImageWP extends Image
{

    protected $id; //Attachment ID for WP

    public function __construct($data)
    {
        parent::__construct($data);
        $this->id = isset($data['id']) ? $data['id'] : false;
        //WP
        if ($this->id && function_exists('wp_get_attachment_image_srcset')) {
            $this->setAttribute('src-set', wp_get_attachment_image_srcset($this->id));
        }
    }
}
