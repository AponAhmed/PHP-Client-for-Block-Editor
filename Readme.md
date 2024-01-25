# Block Editor PHP Client Documentation

The Block Editor PHP Client is a set of PHP classes that facilitate the creation and rendering of HTML elements for a block editor(From JSON data). Json Data generate by a JavaScript App linked Below. It provides an object-oriented approach to building dynamic and customizable layouts.

## Install 
```base
   composer require aponahmed/blockeditor-php-client
```

## Uses

```php
use Aponahmed\HtmlBuilder\ElementFactory;

require_once 'vendor/autoload.php';
//Json String From Generate by Builder(Link Below)
$jsonString='[{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"BlockEditor - A Simple and Easy Handle Html Builder","tag":"h2","more":{"customClass":""}},{"type":"P","align":"left","content":"The Block Editor is a versatile web development tool designed for creating dynamic and customizable layouts. Built using  HTML, CSS, and JavaScript, this editor empowers web developers to effortlessly construct content-rich pages with a variety of components.","more":{"customClass":""}}],"width":100,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{}}},{"type":"Area","direction":"row","childs":[{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"Features","tag":"h3","more":{"customClass":""}},{"type":"P","align":"left","content":"Extensibility: The editor is designed with future extensibility in mind, allowing for the seamless addition of new features or specialized methods.<br><br>Documentation: Ongoing efforts to improve documentation, including comments and JSDoc, to enhance code readability and maintainability.<br><br>Modularity: Components can be easily added or removed, promoting a modular and scalable approach to building layouts.<br><br>Customization: The editor allows for the customization of individual components through properties, such as custom classes, padding, background, border, and border radius.<br><br>Context Menus: Context menus provide quick access to actions like creating new areas, inserting components, deleting areas, changing direction, resizing, and accessing properties.<br><br>Resizable Layouts: Users can dynamically resize areas, enhancing the flexibility of the editor in adapting to various content needs.<br><br>Component Browser: A convenient component browser facilitates the insertion of new components, streamlining the development process.","more":{"customClass":""}}],"width":50,"more":{"customClass":"feature-area","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#e8e8e8","padding":"25px","margin":"0px"}}},{"type":"Area","direction":"column","childs":[{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"Components","tag":"h3","more":{"customClass":""}},{"type":"List","align":"left","listType":"ul","items":["Heading H1-H6","Paragraph","List (ul/ol)","Column with some default orientation","Image WordPress","Editor WpEditor<br>"],"more":{"customClass":""}}],"width":100,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#a1b56c","padding":"30px"}}},{"type":"Area","direction":"column","childs":[{"type":"H","align":"left","content":"Key Feature","tag":"h3","more":{"customClass":""}},{"type":"List","align":"left","listType":"ol","items":["Easy Create Any layout","Modular Component System","Dynamic HTML Element Creation","Component Icons","User-Friendly Event Handling","HTML Parsing"],"more":{"customClass":""}}],"width":100,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#86c1b9","padding":"30px","margin":"10px 0 0"}}}],"width":50,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{"background":"#e8e8e8"}}}],"width":100,"more":{"customClass":"","padding":0,"bg":false,"border":false,"borderRadius":0,"styles":{}}}]';

echo ElementFactory::json2html($jsonString)//Here Html Will be generated

```


## JSON Builder
   Here is the JSON builder for a block editor : [BlockEditor](https://github.com/AponAhmed/BlockEditor)<br>
   A quick preview of JSON Builder [Codepen](https://codepen.io/apon22/full/abXPPyB)

## Table of Contents

- [Element Class](#element-class)
- [Area Class](#area-class)
- [TextElement Class](#textelement-class)
- [ListElement Class](#listelement-class)
- [createElement Function](#createelement-function)
- [JSON Structure](#json-structure)
- [Usage](#usage)

## Element Class

The `Element` class is an abstract base class that serves as the foundation for specific HTML elements. It includes methods for setting attributes, styles, and classes, as well as rendering the element.

### Methods

#### `setAttribute($name, $value)`

Sets the attribute of the element.

#### `addStyle($propertyName, $propertyValue)`

Adds a style to the element.

#### `addClass($name)`

Adds a class to the element.

#### `setStyleAttribute()`

Sets the style attribute based on the added styles.

#### `setClassAttribute()`

Sets the class attribute based on the added classes.

#### `render($indent = 0)`

Abstract method to be implemented by subclasses for rendering the HTML representation of the element.

## Area Class

The `Area` class extends the `Element` class and represents a block or container area. It can contain child elements and has additional properties like direction and width.

### Properties

- `$childs`: An array of child elements.
- `$dir`: The direction of the area ('row' or 'column').
- `$width`: The width of the area.

### Methods

#### `addChild(Element $child)`

Adds a child element to the area.

#### `render($indent = 0)`

Renders the HTML representation of the area.

#### `renderMarkdown($indent = 0)`

Renders the Markdown representation of the area.

## TextElement Class

The `TextElement` class extends the `Element` class and represents a text-based HTML element.

### Properties

- `$content`: The content of the text element.

### Methods

#### `render($indent = 0)`

Renders the HTML representation of the text element.

#### `renderMarkdown($indent = 0)`

Renders the Markdown representation of the text element.

## ListElement Class

The `ListElement` class extends the `Element` class and represents a list HTML element.

### Properties

- `$listType`: The type of the list ('ul' or 'ol').
- `$items`: An array of child elements representing list items.

### Methods

#### `addChild(Element $item)`

Adds a child element (list item) to the list.

#### `render($indent = 0)`

Renders the HTML representation of the list.


## createElement Function

The `createElement` function is a helper function to create instances of the various element classes based on provided JSON data.

## JSON Structure

The structure of the JSON data used to define the layout and content of the block editor.

## Usage

Example usage of the Block Editor PHP Client to generate HTML output.

