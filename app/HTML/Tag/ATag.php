<?php

namespace HTML\Tag;

class ATag
{
    private $href;
    private $target;
    private $title;
    private $styles;
    private $additional;
    private $linktext;

    public function __construct($href, $target, $title, $styles, $additional, $linktext)
    {
        $this->href = $href;
        $this->target = $target;
        $this->title = $title;
        $this->styles = $styles;
        $this->additional = implode(' ', $additional);
        $this->linktext = $linktext;
    }

    public function __toString()
    {
        return sprintf(
            "<a href='%s' target='%s' title='%s' styles='%s' %s>%s</a>",
            $this->href,
            $this->target,
            $this->title,
            $this->styles,
            $this->additional,
            $this->linktext
        );
    }
}
