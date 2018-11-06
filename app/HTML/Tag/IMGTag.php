<?php

namespace HTML\Tag;

class IMGTag
{
    private $imgPath;
    private $class;
    private $styles;
    private $additional;

    public function __construct($imgpath, $class = '', array $styles = [], $additional = '')
    {
        $this->imgPath = $imgpath;
        $this->class = $class;
        $this->styles = implode(';', $styles);
        $this->additional = $additional;
    }

    public function __toString()
    {
        return sprintf("<img class='%s' src='%s' style='%s' %s />", $this->class, $this->imgPath, $this->styles, $this->additional);
    }
}
