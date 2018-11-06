<?php

namespace Controller;

class UploadController
{
    private $routingHandler;

    public function __construct($routingHandler = null)
    {
        $this->routingHandler = $routingHandler;
    }

    public function uploadFile()
    {
        $post = $this->routingHandler->getPost();
        if (!isset($_FILES)) {
            return json_encode(['error' => true, 'message' => "No files uploaded!"]);
        }
        $files = $_FILES['fileuploader'];
        $filename = $this->routingHandler->getPostParameter('filename');
        $overwrite = $this->routingHandler->getPostParameter('overwrite', false);
        if (!isset($this->routingHandler->uploadDirectory)) {
            //this is not right, but I can't figure out the correct way to solve it right now
            throw new UploadException("Parameter uploadDirectory not set!");
        }
        $targetDir = $this->routingHandler->uploadDirectory;
        $destination = $targetDir . '/' . $filename;
        if (file_exists($destination) && !$overwrite) {
            //verify overwrite
            echo json_encode(["success" => false, "cause" => "overwrite-confirmation"]);
        } else {
            if (move_uploaded_file($files['tmp_name'], $destination)) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "cause" => "Failed writing file $filename to target directory $targetDir"]);
            }
        }
    }

    private function cleanupFilename($filename)
    {
        return preg_replace('/[+:; åäö\/\\?|<>]/', '_', $filename);
    }

    public function  uploadImage()
    {
        $post = $this->routingHandler->getPost();
        if (!isset($_FILES)) {
            return json_encode(['error' => true, 'message' => "No files uploaded!"]);
        }
        $files = $_FILES['fileuploader'];
        $filename = $files['name'];
        $targetDir = $this->routingHandler->uploadDirectory;
        $destination = $targetDir . '/' . $filename;

        if (move_uploaded_file($files['tmp_name'], $destination)) {
            //create thumb
            //$this->generateThumb($destination, 110, "width", false);
            json_encode(["success" => true, "message" => "Bilden uppladdad för granskning"]);
        } else {
            json_encode(["success" => false, "message" => "Kunde inte ladda up bilden"]);
        }
    }

}

class UploadException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 10001);
    }

    public function jsonError()
    {
        echo json_encode($this);
    }
}
