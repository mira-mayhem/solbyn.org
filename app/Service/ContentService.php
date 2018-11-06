<?php

namespace Service;

use Plugin\GalleryPlugin;
use Controller\AdminController\SecurityController;

class ContentService
{
    private $submenu;
    private $routingHandler;

    private $galleryImages;

    public function __construct($routingHandler)
    {
        $this->routingHandler = $routingHandler;
    }

    public function getSubmenu()
    {
        return $this->submenu;
    }

    private function setSubmenu($submenu, $currentItem)
    {
        $submenu = new SubmenuService($submenu, $currentItem);
        $this->submenu = $submenu;
        return $this;
    }

    public function getPageContent($page, $generationType = null)
    {
        if ($generationType == "generic") {
            return $this->replacePlaceholders($this->getGenericPageContent($page));
        } else {
            $method = sprintf("get%sPage", implode('', array_map("ucfirst", preg_split('/\//', $page))));
            if (method_exists($this, $method)) {
                return $this->replacePlaceholders($this->$method());
            } else {
                return $this->getFourOfour($method, $page);
            }
        }
    }

    private function getGenericPageContent($page)
    {
        $filename = sprintf('templates/%s.inc.html', $page);
        if (isset($this->routingHandler->submenu)) {
            $this->setSubmenu($this->routingHandler->submenu, $page);
        }
        return $this->render($filename);
    }

    private function render($filename, $backupfile = null)
    {
        if (file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            if (isset($backupfile)) {
                return $this->render($backupfile);
            }
            return $this->getFourOfour(null, $filename);
       }
    }

    private function renderphp($filename)
    {
        if (file_exists($filename)) {
            ob_start();
            require $filename;
            return ob_get_clean();
        } else {
            return $this->getFourOfour(null, $filename);
       }
    }

    private function replacePlaceholdersPHP($pageContent, $replaceTextBlob)
    {
        //___PHP:key___
        global $ioc;
        $ss = $ioc->getSessionService();
        if (!($ss->get('editmode', false) && $ss->get('auth', false))) {
            if (preg_match_all("/___PHP:(.+)___/", $pageContent, $captureGroups)) {
                foreach ($captureGroups[1] as $key => $value) {
                    $replaceText = $captureGroups[0][$key];
                    $replacementContent = $replaceTextBlob[$value];
                    $pageContent = str_replace($replaceText, $replacementContent, $pageContent);
                }
            }
        }
        return $pageContent;
    }

    private function replacePlaceholders($pageContent)
    {
        //if user logged in - display alternative form (if available)
        //if in editmode - don't replacce placeholders
        global $ioc;
        $sess = $ioc->getSessionService();
        if (!($sess->get('editmode', false) && $sess->get('auth', false))) {
            $fileSuffix = '';
            $useBackupfile = false;
            if ($sess->get('auth') === true) {
                $fileSuffix = '.auth';
                $useBackupfile = true;
            }
            if (preg_match_all("/___(.+)___/", $pageContent, $captureGroups)) {
                foreach ($captureGroups[1] as $key => $value) {
                    $replacementFile = sprintf('templates/submodules/%s%s', strtolower($value), $fileSuffix);
                    if ($useBackupfile) {
                        $replacementBackupFile = sprintf('templates/submodules/%s', strtolower($value));
                        $replacementContent = $this->render($replacementFile, $replacementBackupFile);
                    } else {
                        $replacementContent = $this->render($replacementFile);
                    }
                    $replaceText = $captureGroups[0][$key];
                    $pageContent = str_replace($replaceText, $replacementContent, $pageContent);
                }
            }
        }
        return $pageContent;
    }

    private function getNotAuthorizedPage()
    {
        $filename = 'templates/noauth.inc.html';
        return $this->render($filename);
    }

    private function getGalleryPage()
    {
        $gpl = new GalleryPlugin("images/gallery/", '*.JPG', 'images/gallery/width-110/');
        $this->galleryImages['thumbs'] = implode('', $gpl->build());
        $filename = 'templates/gallery.inc.php';
        return $this->renderphp($filename);
    }

    private function getMemberPage()
    {
        if (isset($this->routingHandler->resource)) {
            $pageContent = $this->getPageContent('member/' . $this->routingHandler->resource);
        } else {
            $auth = new AuthenticationService();
            $filename = 'templates/member.inc.html';
            $this->setSubmenu('member', 'member');
            $pageContent = $this->render($filename);
            $pageContent = $this->replacePlaceholdersPHP($pageContent, ['HOUSEONCALL' => $auth->getHouseOnCall()]);
        }
        return $pageContent;
    }

    private function getMemberSolbybladetPage()
    {
        $fs = new FileService();
        $dir = "uploads/solbybladet/";
        $files = $fs->getFiles($dir);
        $htmlList = $fs->createFileList($files, $dir);
        $this->setSubmenu('member', 'solbybladet');
        $filename = 'templates/solbybladet.inc.html';
        $page = $this->render($filename);
        $page .= $htmlList;
        if ($this->routingHandler->checkAuthorization('Editor')) {
            $uploadForm = $this->render('templates/submodules/upload.form');
            $page .= $uploadForm;
        }
        return $page;
    }

    private function getMemberImageUploaderPage()
    {
        $uploadForm = $this->render('templates/submodules/uploadimage.form');
        return $uploadForm;
    }

    private function getFourOfour($method, $page)
    {
        if (isset($method)) {
            error_log("Missing method ContentService::$method");
        }
        if (isset($page)) {
            error_log("Page '$page' is missing");
        }
        $fourofour = 'templates/404.inc.html';
        return file_get_contents($fourofour);
    }
}
