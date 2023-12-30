<?php
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
        $this->styles = $elementData['more']['styles'];
        $this->setStyleAttribute();
        if ($this->dir) {
            $this->addClass("dir-" . $this->dir);
        }
        if ($this->width) {
            $this->addClass('w-' . $this->width);
        }
        if (isset($elementData['more']['customClass'])) {
            $this->addClass($elementData['more']['customClass']);
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

        foreach ($this->childs as $child) {
            $html .= $child->render($indent + 1);
        }

        $html .= "\n$indentation</div>";

        return $html;
    }
}

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

function createElement($elementData)
{
    $type = $elementData['type'];

    switch ($type) {
        case 'Area':
        case 'Column':
            $area = new Area($elementData);
            if (isset($elementData['childs']) && is_array($elementData['childs'])) {
                foreach ($elementData['childs'] as $childData) {
                    $child = createElement($childData);
                    if ($child) {
                        $area->addChild($child);
                    }
                }
            }

            return $area;

        case 'H':
        case 'P':
        case 'li':
            $content = isset($elementData['content']) ? $elementData['content'] : '';
            $tag = isset($elementData['tag']) ? $elementData['tag'] : strtolower($type);
            $element = new TextElement($tag, $content);
            if (isset($elementData['align'])) {
                $element->addClass("align-" . $elementData['align']);
            }
            if (isset($elementData['more']['customClass'])) {
                $element->addClass($elementData['more']['customClass']);
            }
            return $element;

        case 'List':
            $listType = isset($elementData['listType']) ? $elementData['listType'] : 'ul';
            $items = isset($elementData['items']) && is_array($elementData['items']) ? $elementData['items'] : [];
            $list = new ListElement($listType, []);
            foreach ($items as $item) {
                $child = createElement(['type' => 'li', 'content' => $item]); // Assuming 'li' type for list items
                $list->addChild($child);
            }
            return $list;
        default:
            // Handle other element types as needed
            return null;
    }
}

// Your JSON structure
$jsonStructure = '[{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"BlockEditor - A Simple and Easy Handle Html Builder","tag":"h2","more":{"customClass":""}},{"type":"P","align":"left","content":"The Block Editor is a versatile web development tool designed for creating dynamic and customizable layouts. Built using  HTML, CSS, and JavaScript, this editor empowers web developers to effortlessly construct content-rich pages with a variety of components.","more":{"customClass":""}}],"width":100,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{}}},{"type":"Area","direction":"row","childs":[{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"Features","tag":"h3","more":{"customClass":""}},{"type":"P","align":"left","content":"Extensibility: The editor is designed with future extensibility in mind, allowing for the seamless addition of new features or specialized methods.<br><br>Documentation: Ongoing efforts to improve documentation, including comments and JSDoc, to enhance code readability and maintainability.<br><br>Modularity: Components can be easily added or removed, promoting a modular and scalable approach to building layouts.<br><br>Customization: The editor allows for the customization of individual components through properties, such as custom classes, padding, background, border, and border radius.<br><br>Context Menus: Context menus provide quick access to actions like creating new areas, inserting components, deleting areas, changing direction, resizing, and accessing properties.<br><br>Resizable Layouts: Users can dynamically resize areas, enhancing the flexibility of the editor in adapting to various content needs.<br><br>Component Browser: A convenient component browser facilitates the insertion of new components, streamlining the development process.","more":{"customClass":""}}],"width":50,"more":{"customClass":"feature-area","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#7cafc2","padding":"25px","margin":"0px"}}},{"type":"Area","direction":"row","childs":[{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"Components","tag":"h3","more":{"customClass":""}},{"type":"List","align":"left","listType":"ul","items":["Heading H1-H6","Paragraph","List (ul/ol)","Column with some default orientation","Image WordPress","Editor WpEditor<br>"],"more":{"customClass":""}}],"width":50,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#dc9656","padding":"15px"}}},{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"Key Feature","tag":"h3","more":{"customClass":""}},{"type":"List","align":"left","listType":"ol","items":["Easy Create Any layout","Modular Component System","Dynamic HTML Element Creation","Component Icons","User-Friendly Event Handling","HTML Parsing"],"more":{"customClass":""}}],"width":50,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#86c1b9","padding":"15px"}}}],"width":50,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#f8f8f8"}}}],"width":100,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{}}},{"type":"Area","width":100,"direction":"row","childs":[{"width":50,"direction":"column","type":"Area","childs":[{"type":"H","align":"left","content":"Sunset Serenity","tag":"h4","more":{"customClass":""}},{"type":"P","align":"left","content":"The sun sets, casting a warm glow on tranquil ocean waves.","more":{"customClass":""}}],"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"padding":"10px","background":"#ab4642"}}},{"width":50,"direction":"column","type":"Area","childs":[{"type":"H","align":"left","content":"City Lights Symphony","tag":"h4","more":{"customClass":""}},{"type":"P","align":"left","content":"Neon lights illuminate a bustling city as people rush through crowded streets.","more":{"customClass":""}}],"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"padding":"10px","background":"#7cafc2"}}},{"width":50,"direction":"column","type":"Area","childs":[{"type":"H","align":"left","content":"Write Here","tag":"h4","more":{"customClass":""}},{"type":"P","align":"left","content":"Lost in a dense forest, ancient trees whisper secrets amidst a mysterious fog.","more":{"customClass":""}}],"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#f7ca88","padding":"10px"}}},{"width":50,"direction":"column","type":"Area","childs":[{"type":"H","align":"left","content":"Write Here","tag":"h4","more":{"customClass":""}},{"type":"P","align":"left","content":"On a snowy mountaintop, crisp air echoes with laughter during a winter adventure.","more":{"customClass":""}}],"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#a1b56c","padding":"10px"}}},{"width":50,"direction":"column","type":"Area","childs":[{"type":"H","align":"left","content":"Write Here","tag":"h4","more":{"customClass":""}},{"type":"P","align":"left","content":"In a vibrant market, exotic aromas of spices fill the air, inviting exploration.","more":{"customClass":""}}],"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#86c1b9","padding":"10px"}}},{"width":50,"direction":"column","type":"Area","childs":[{"type":"H","align":"left","content":"Write Here","tag":"h4","more":{"customClass":""}},{"type":"P","align":"left","content":"Melodic notes fill the concert hall, creating magic in a symphony of silence.","more":{"customClass":""}}],"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"padding":"10px","background":"#ba8baf"}}}],"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{}}}]';
// Convert JSON string to array
$jsonArray = json_decode($jsonStructure, true);

// Build HTML from JSON using objects
$htmlOutput = '';

foreach ($jsonArray as $elementData) {
    $element = createElement($elementData);
    if ($element) {
        $htmlOutput .= $element->render();
    }
}

// Output the generated HTML
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles.css">
</head>

<body>
    <?php echo $htmlOutput; ?>
</body>

</html>