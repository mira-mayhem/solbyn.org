<?php

namespace Handler;

class ImageHandler
{
    public function moveToDirectory($fromDir, $toDir, $filename, $fn = 'rename')
    {
        try {

            $fromPath = realpath($fromDir) . '/' . $filename;
            $toPath = realpath($toDir) . '/' . $filename;
            if (!file_exists($fromPath)) {
                throw new Exception("File `$fromPath` don't exists");
            }
            if (file_exists($toPath)) {
                throw new Exception("File `$toPath` already exists");
            }
            if (!$fn($fromPath, $toPath)) {
                throw new Exception("Cannot execute `$fn` on `$filename` in `$fromDir` to target `$toDir`");
            }
            return $toPath;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function calculateImageSize($imageFullpath, $targetSize, $targetAxis)
    {
        list($orignalWidth, $originalHeight) = getimagesize($imageFullpath);
        $mime = getimagesize($imageFullpath)['mime'];

        if (in_array(strtolower($targetAxis), ['height', 'h', 'y', 'höjd'])) {
            $targetHeight = $targetSize;
            $targetWidth = round($targetHeight * $orignalWidth / $originalHeight, 1);
            echo "height: " . $targetHeight . " w: " . $targetWidth;
        } else {
            $targetWidth = $targetSize;
            $targetHeight = round($targetWidth * $originalHeight / $orignalWidth, 1);
        }
        return [$mime, $targetHeight, $targetWidth, $originalHeight, $orignalWidth];
    }

    public function generateThumb($imageFullpath, $targetSize, $targetAxis, $crop = false)
    {
        list($mime, $newHeight, $newWidth, $originalHeight, $originalWidth) = $this->calculateImageSize($imageFullpath, $targetSize, $targetAxis);
        $resource = $this->returnImageResource($mime, $imageFullpath);
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($thumb, $resource, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        $pathData = pathinfo($imageFullpath);
        $newPath = sprintf("%s/%s-%s", $pathData['dirname'], $targetAxis, $targetSize);
        if (!file_exists($newPath)) {
            if (!mkdir($newPath)) {
                throw new \Exception("Failed creating new directory: $newPath");
            }
        }
        $targetFilepath = sprintf("%s/%s", $newPath, $pathData['basename']);
        imagejpeg($thumb, $targetFilepath);
    }

    private function returnImageResource($mime, $filepath)
    {
        list($resourceType, $mimeType) = explode('/', $mime);
        $fn = "imagecreatefrom" . $mimeType;
        set_error_handler(function($errNbr, $errMessage, $errFile, $errLine, array $errContext){
            if (preg_match('/not a valid/', $errMessage)) {
                throw new WrongTypeException("");
            } else {
                var_dump($errNbr, $errMessage, $errFile, $errLine);
                error_log("oh ho - it is a waning!");
            }
        });
        try {
            return $fn($filepath);
        } catch (WrongTypeException $e) {
            //continue
        } catch (\Exception $e) {
            throw $e;
        }
        restore_error_handler();
    }
}

class Exception extends \Exception
{}

class WrongTypeException extends \Exception
{}
