<?php

namespace Aponahmed\HtmlBuilder;

class ImageStatic extends Image
{

    protected $id; //Attachment ID for WP

    public function __construct($data)
    {
        parent::__construct($data);
        $this->id = isset($data['id']) ? $data['id'] : false;
        if ($this->id && function_exists('getMedia')) {
            $media = getMedia($this->id);
            $this->setAttribute('src', $media->getSrc());
        }
    }

    public function render($indent = 0)
    {
        $indentStr = str_repeat(' ', $indent);
        $html = $indentStr . '<' . $this->type;

        foreach ($this->attributes as $name => $value) {
            $html .= ' ' . $name . '="' . htmlspecialchars($value) . '"';
        }

        $html .= '>';

        return $html;
    }
}
