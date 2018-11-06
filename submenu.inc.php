    <?php

property::SetSelectedSubMenu('flats');

class property
{
    private static $selectedSubMenu;

    public static function SetSelectedSubMenu($menu)
    {
        self::$selectedSubMenu = $menu;
    }

    public static function selected($current)
    {
        return self::$selectedSubMenu == $current ? "class='selected'" : '';
    }
}

