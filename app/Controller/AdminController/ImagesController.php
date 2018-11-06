<?php

namespace Controller\AdminController;

use Service\FileService;
use Handler\ImageHandler;

class ImagesController
{
    private $routingHandler;

    public function __construct($routingHandler)
    {
        $this->routingHandler = $routingHandler;
    }

    public function listUploadsDirectory()
    {
        if (isset($_GET['directory'])) {
            $dir = $_GET['directory'];
        } else {
            $dir = "uploads";
        }
        $li = [];
        $fs = new FileService();
        $files = $fs->getFiles($dir, true);
        $directoryStructure = [];
        foreach ($files as $entry) {
            list($entryName, $entryType) = $entry;
            $directoryStructure[$entryType][] = $entryName;
        }
        ksort($directoryStructure);
        $parentDir = realpath($dir . '/..');
        $li[] = sprintf("<li class='dir uplevel' href='%s'>%s</li>", $parentDir, $this->createArrowUp());
        $countDirs = isset($directoryStructure['dir']) ? count($directoryStructure['dir']) : 0;
        $countFiles = isset($directoryStructure['file']) ? count($directoryStructure['file']) : 0;
        foreach ($directoryStructure as $entryType => $entries) {
            foreach ($entries as $entryName) {
                $dirLocation = $entryType == 'dir' ? $dir . '/' : '';
                $li[] = sprintf("<li class='%s' href='%s'><img src='images/%s.png' />%s</li>", $entryType, $dirLocation.$entryName, $entryType, $entryName);
            }
        }
        if ($countFiles + $countDirs == 0) {
            $li[] = "<li class='empty'>&lt;directory empty&gt;</li>";

        }
        $dirlisting = sprintf("<ul class='dirlist'>%s</ul>", implode("", $li));
        require 'templates/admin/pages/reviewImages.php';
    }

    private function createArrowUp()
    {
        $svg = '
        <svg viewbox="0 0 100 100" style="height: 15px; width: 15px;">
          <path d="M30,0 L60,30 L40,30 L40,80, L90,80 L90,100 L20,100 L20,30 L0,30 L30,0" ></path>
        </svg>
        ';
        return $svg;
    }

    public function move()
    {
        $post = $this->routingHandler->getPost();
        $toPath = $post['targetdirectory'];
        $thumbSizes = explode(",", $post['thumbsizes']);
        $fromPath = $post['sourcedirectory'];
        $createThumb = $post['createthumb'] == "false" ? false : true;
        $doMove = $post['domove'] == "false" ? false : true;
        $images = $post['image'];
        $rootFolder = $_SERVER['DOCUMENT_ROOT'];
        $imgH = new ImageHandler();
        foreach ($images as $image) {
            $fromPath = str_replace($rootFolder, '', $fromPath);
            $toPath = str_replace($rootFolder, '', $toPath);
            if ($doMove) {
                $imgFilePath = $imgH->moveToDirectory($fromPath, $toPath, $image, 'move');
            } else {
                $imgFilePath = $fromPath . '/' . $image;
            }
            if ($createThumb) {
                foreach ($thumbSizes as $sizeData) {
                    list($axis, $size) = explode(':', $sizeData);
                    $imgH->generateThumb($imgFilePath, $size, $axis);
                }
            }
        }
    }

    public function delete()
    {
        $post = $this->routingHandler->getPost();
        $path = $post['sourcedirectory'];
        $file = $post['filename'];
        $rootFolder = $_SERVER['DOCUMENT_ROOT'];
        $path = str_replace($rootFolder, '', $path);
        return unlink($path.'/'.$file);
    }
}
