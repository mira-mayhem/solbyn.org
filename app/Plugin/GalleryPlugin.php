<?php

namespace Plugin;

use HTML\Tag\IMGTag;
use HTML\Tag\DIVTag;
use HTML\Tag\ATag;

class GalleryPlugin
{
    private $images;

    public function __construct($directory, $pattern, $thumbdirectory)
    {
        if (file_exists($directory) && is_dir($directory)) {
            if ($handle = opendir($directory)) {
                while ($file = readdir($handle)) {
                    if (is_dir($directory . $file)) {
                        continue;
                    }
                    set_error_handler(function($errNbr, $errMessage, $errFile, $errLine, array $errContext){
                        error_log($errNbr . ": " . $errMessage);
                    });
                    $metaData = exif_read_data($directory.$file);
                    $description = isset($metaData['ImageDescription']) ? $metaData['ImageDescription'] : '';
                    restore_error_handler();
                    if (file_exists($thumbdirectory . $file)) {
                        $thumb = $thumbdirectory . $file;
                    } else {
                        $thumb = $directory . $file;
                    }
                    $href = $directory.$file;
                    $this->images[] = new ImageModel($thumb, $href, $description);
                }
                closedir($handle);
            }
        }
    }

    public function build()
    {
        $images = [];
        foreach ($this->images as $img) {
            $imgTag = new IMGTag($img->getThumb(), "obox-image");
            $additional = ["rel='obox'"];
            $aTag = new ATag($img->getHref(), '', $img->getDescription(), '', $additional, $imgTag);
            $divCol = new DIVTag($aTag, "obox-div");
            $images[] = $divCol;
        };
        return $images;
    }
}

class ImageModel
{
    private $thumb;
    private $href;
    private $description;

    public function __construct($thumb, $href, $description = '')
    {
        $this->thumb = $thumb;
        $this->href = $href;
        $this->description = $description;
    }

    public function getThumb()
    {
        return $this->thumb;
    }

    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
        return $this;
    }

    public function getHref()
    {
        return $this->href;
    }

    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

}
