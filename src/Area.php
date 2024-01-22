<?php

namespace Aponahmed\HtmlBuilder;

class Area extends Element
{
    protected $childs = [];
    protected $dir = null;
    protected $props;
    protected $width = 100;

    public function __construct($elementData)
    {
        parent::__construct('Area');
        //$this->props = (object) $elementData;

        $this->addClass('area');
        $this->dir = isset($elementData['direction']) ? $elementData['direction'] : 'row';
        $this->width = (int) isset($elementData['width']) ? $elementData['width'] : 100;
        $this->styles = isset($elementData['more']['styles']) ? $elementData['more']['styles'] : [];
        $this->setStyleAttribute();
        if ($this->dir) {
            $this->addClass("dir-" . $this->dir);
        }
        if ($this->width) {
            $this->addClass('area-w' . $this->width);
        }

        if (isset($elementData['more']['customClass'])) {
            $customClasses = explode(' ', $elementData['more']['customClass']);
            foreach ($customClasses as $class) {
                $this->addClass($class);
            }
        }
    }

    public function addChild(Element $child)
    {
        $this->childs[] = $child;
    }

    public function render($indent = 0)
    {
        $indentation = str_repeat("\t", $indent);

        //Screen size logic for rendering
        if (count($this->childs) > 3) { //Grid size logic for rendering
            switch (count($this->childs)) {
                case 4:
                case 5:
                    $this->addClass('tab-grid-2');
                    break;
                case 6:
                    $this->addClass('tab-grid-3');
                    $this->addClass('mob-grid-2');
                    break;
            }
        } else { //reguler column size logic for rendering
            if ($indent > 0 && $this->dir == 'row' && $this->width >= 50) {
                $this->addClass('tab-column');
                if (count($this->childs) <= 2) {
                    $this->addClass('mob-row');
                }
            }
        }
        //End screen size logic for rendering

        $this->addClass('indent-' . $indent); //Development version
        $html = "\n$indentation<div";
        foreach ($this->attributes as $name => $value) {
            $html .= " $name=\"" . htmlspecialchars($value, ENT_QUOTES) . "\"";
        }

        $html .= ">";

        if (in_array('align-center', $this->classes)) {
            $html .= "<div class=\"center-align\">";
        }

        foreach ($this->childs as $child) {

            $html .= $child->render($indent + 1);
        }
        if (in_array('align-center', $this->classes)) {
            $html .= "</div>";
        }

        $html .= "\n$indentation</div>";

        return $html;
    }
}
