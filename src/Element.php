<?php

namespace Aponahmed\HtmlBuilder;

abstract class Element
{
    protected $type;
    protected $classes = [];
    protected $attributes = [];
    protected $styles = [];

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function addStyle($propertyName, $propertyValue)
    {
        $this->styles[$propertyName] = $propertyValue;
        $this->styles = array_filter(array_unique($this->styles));
        $this->setStyleAttribute();
    }

    public function addClass($name)
    {
        $this->classes[] = $name;
        $this->classes = array_filter(array_unique($this->classes));
        $this->setClassAttribute();
    }

    public function setStyleAttribute()
    {
        unset($this->attributes['style']);
        if (count($this->styles) > 0) {
            $this->setAttribute("style", implode(";", $this->styles));
            $styleStr = "";
            foreach ($this->styles as $prop => $v) {
                $styleStr .= $prop . ":" . $v . ";";
            }
            $this->setAttribute("style", $styleStr);
        }
    }

    public function setClassAttribute()
    {
        unset($this->attributes['class']);
        if (count($this->classes) > 0) {
            $this->setAttribute("class", implode(" ", $this->classes));
        }
    }

    abstract public function render($indent = 0);
}
