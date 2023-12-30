<?php

namespace Aponahmed\HtmlBuilder;

class TextElement extends Element
{
    protected $content;

    public function __construct($type, $content)
    {
        parent::__construct($type);
        $this->content = $content;
    }

    public function render($indent = 0)
    {
        $indentation = str_repeat("\t", $indent);
        $tag = strtolower($this->type);
        $html = "\n$indentation<$tag";

        foreach ($this->attributes as $name => $value) {
            $html .= " $name=\"" . htmlspecialchars($value, ENT_QUOTES) . "\"";
        }

        $html .= ">" . $this->content . "</$tag>";
        return $html;
    }
}
