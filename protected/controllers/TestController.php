<?php

class TestController extends Controller 
{    
    public function actionTest() 
    {        
        $this->render('index');
    }
    
    public function actionRename() 
    {        
        $user = User::model()->findByPk(28); // WebWiki
        $user->login = 'webviki';
        $user->name = 'ВебВИКИ';
        $user->email = 'marketing@webviki.by';
        $user->phone = '+375296772782';
        $user->password = crypt('forwebviki', User::model()->blowfishSalt());
        $user->save();
    }
}
