# Block Editor PHP Client Documentation

The Block Editor PHP Client is a set of PHP classes that facilitate the creation and rendering of HTML elements for a block editor(From JSON data). Json Data generate by a JavaScript App linked Below. It provides an object-oriented approach to building dynamic and customizable layouts.

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

