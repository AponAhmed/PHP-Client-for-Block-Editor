<?php

namespace Aponahmed\HtmlBuilder;

class ListElement extends Element
{
    protected $listType;
    protected $items = [];

    public function __construct($listType, array $items)
    {
        parent::__construct('List');
        $this->listType = $listType;
        $this->items = $items;
    }

    public function addChild(Element $item)
    {
        $this->items[] = $item;
    }

    public function render($indent = 0)
    {
        $indentation = str_repeat("\t", $indent);
        $html = "\n$indentation<$this->listType";

        foreach ($this->attributes as $name => $value) {
            $html .= " $name=\"" . htmlspecialchars($value, ENT_QUOTES) . "\"";
        }

        $html .= ">";

        foreach ($this->items as $item) {
            $html .= $item->render($indent + 1);
        }

        $html .= "\n$indentation</$this->listType>";

        return $html;
    }
}
