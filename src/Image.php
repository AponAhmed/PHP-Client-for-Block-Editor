<?php

namespace Aponahmed\HtmlBuilder;

class Image extends Element
{
    protected $align;
    protected $src;
    protected $width;
    protected $height;
    protected $customClass;

    public function __construct($data)
    {
        parent::__construct('img');
        
        $this->align = isset($data['align']) ? $data['align'] : '';
        $this->src = isset($data['src']) ? $data['src'] : '';
        $this->width = isset($data['width']) ? $data['width'] : '';
        $this->height = isset($data['height']) ? $data['height'] : '';
        $this->customClass = isset($data['more']['customClass']) ? $data['more']['customClass'] : '';


        $this->setAttribute('src', $this->src);
        $this->setAttribute('width', $this->width);
        $this->setAttribute('height', $this->height);

        $this->addClass('align-' . $this->align);
        $this->addClass($this->customClass);
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
