<?php

namespace HTML\Tag;

class DIVTag
{
    private $content;
    private $class;
    private $styles;

    public function __construct($content, $class = '', $styles = null)
    {
        $this->content = $content;
        $this->class = $class;
        $this->styles = $styles;
    }

    public function __toString()
    {
        return sprintf("<div class='%s' style='%s'>%s</div>", $this->class, $this->styles, $this->content);
    }
}
