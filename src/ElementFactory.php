<?php

namespace Aponahmed\HtmlBuilder;

class ElementFactory
{
    public static function createElement($elementData)
    {
        $type = $elementData['type'];

        switch ($type) {
            case 'Area':
            case 'Column':
                $area = new Area($elementData);
                if (isset($elementData['childs']) && is_array($elementData['childs'])) {
                    foreach ($elementData['childs'] as $childData) {
                        $child = self::createElement($childData);
                        if ($child) {
                            $area->addChild($child);
                        }
                    }
                }
                return $area;
            case 'Editor':
                $content = new Content($elementData);
                return $content;
            case 'Image':
                if (file_exists('wp-config.php')) {
                    return new ImageWP($elementData);
                } elseif (defined('CMS_STATIC')) {
                    return new ImageStatic($elementData);
                } else {
                    return new Image($elementData);
                }
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
                    $child = self::createElement(['type' => 'li', 'content' => $item]); // Assuming 'li' type for list items
                    $list->addChild($child);
                }
                return $list;
            default:
                // Handle other element types as needed
                return null;
        }
    }

    public static function json2html($string = "[]")
    {
        $jsonArray = json_decode($string, true);
        $htmlOutput = '';

        foreach ($jsonArray as $elementData) {
            $element = ElementFactory::createElement($elementData);
            if ($element) {
                $htmlOutput .= $element->render();
            }
        }
        return $htmlOutput;
    }
}
