<?php

namespace Service;

class SubmenuService
{
    private $page;
    private $filename;
    private $menuSelected;

    public function __construct($page, $currentlySelected)
    {
        $this->page = $page;
        $this->menuSelected = $currentlySelected;
        $this->filename = sprintf('templates/submenu/submenu.%s.inc.php', $this->page);
    }

    public function render()
    {
        if (file_exists($this->filename)) {
            ob_start();
            include $this->filename;
            return ob_get_clean();
        } else {
            error_log("File missing: " . $this->filename);
            return "";
        }
    }

    public function isSelected($current, $returnIfTrue = "class='selected'", $returnIfFalse = '')
    {
        return $this->menuSelected == $current ? $returnIfTrue : $returnIfFalse;
    }
}
