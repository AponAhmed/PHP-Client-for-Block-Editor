<?php

namespace Aponahmed\HtmlBuilder;

class Content extends Element
{
    protected $content;

    public function __construct($elementData)
    {
        parent::__construct('div');
        $this->addClass('content-area');
        $this->styles = isset($elementData['more']['styles']) ? $elementData['more']['styles'] : [];
        $this->content = isset($elementData['content']) ? $elementData['content'] : "";
        $this->setStyleAttribute();
        if (isset($elementData['more']['customClass'])) {
            $this->addClass($elementData['more']['customClass']);
        }
    }

    public function render($indent = 0)
    {
        $indentation = str_repeat("\t", $indent);
        $this->addClass('indent-' . $indent); //Development version

        $html = "\n$indentation<div";
        foreach ($this->attributes as $name => $value) {
            $html .= " $name=\"" . htmlspecialchars($value, ENT_QUOTES) . "\"";
        }
        $html .= ">";

        $html .= $this->content;

        $html .= "\n$indentation</div>";
        return $html;
    }
}
