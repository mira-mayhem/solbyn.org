<?php

namespace Controller;

class EditorController
{
    public function enableEditorMode()
    {
        global $ioc;
        $sess = $ioc->getSessionService();
        $sess->set('editmode', true);
        header("Location: /");
    }

    public function disableEditorMode()
    {
        var_dump("disable thing");
        global $ioc;
        $sess = $ioc->getSessionService();
        $sess->set('editmode', false);
        $sess->save();
        header("Location: /");
    }
}
