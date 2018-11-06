<?php

namespace Service;

class FileService
{
    public function getFiles($targetDirectory, $withDirectories = false)
    {
        $files = [];
        $dir = $targetDirectory;
        if ($handle = opendir($dir)) {
            while ($file = readdir($handle)) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if ($withDirectories == false && is_dir($dir.'/'.$file) == false) {
                    $files[] = $file;
                } else {
                    $files[] = [$file, is_dir($dir.'/'.$file)?'dir':'file'];
                }
            }
            closedir($handle);
        }
        return $files;
    }

    public function createFileList(array $list, $dir)
    {
        $html = "<ul id='filelisting' class='filelist'>";
        $latest = "<span class='latest'>Senaste!</span>";
        foreach ($list as $file) {
            $html .= sprintf("<li>%s %s <a target='_blank' href='%s'>%s</a></li>", $file, $latest, $dir.$file, $this->path());
            $latest = '';
        }
        $html .= "</ul>";
        return $html;
    }

    public function createImageList(array $list, $dir)
    {
        $html = "<ul id='imagefilelisting' class='filelist'>";
        foreach ($list as $file) {
            $html .= sprintf("<li>%s <a target='_blank' href='%s'>%s</a></li>", $file, $latest, $dir.$file, $this->path());
        }
        $html .= "</ul>";
        return $html;
    }

    private function path()
    {
        return "
        <svg viewbox='0 0 16 16' class='newwindow'>
           <path d='M7,4 L1,4 L1,15 L11,15 L11,10 M9,1 L15,1 L15,7 L13,5 L9,9 L7,7 L11,3 L9,1' />
        </svg>";
    }

/*           <path d='M1,1 L50,1 L50,99 L1,99 L1,1' />
*/}
