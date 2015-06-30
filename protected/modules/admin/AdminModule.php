<?php

class AdminModule extends CWebModule
{
    public function init()
    {
            // this method is called when the module is being created
            // you may place code here to customize the module or the application

            // import the module-level models and components
            $this->setImport(array(
                    'admin.models.*',
                    'admin.components.*',
            ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action)) {
           /* $controller->layout = 'main';
            Yii::app()->clientScript->registerCssFile('/css/back/backend.css?'.time());
            Yii::app()->clientScript->registerCssFile('/css/ui/jquery-ui-1.10.3.css');
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile('/js/ui/jquery-ui-1.10.3.js');
            Yii::app()->clientScript->registerScriptFile('/js/back/backend.js');
*/
            if(Yii::app()->user->isGuest) {
               $this->addStyle($controller);
               Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
               Yii::app()->request->redirect('/user/login/');
            } elseif(Yii::app()->user->checkAccess('shopRead') || Yii::app()->user->checkAccess('shopAdmin')) {
               $this->addStyle($controller);
            } else {
                throw new CHttpException(403, Yii::t('yii','У Вас недостаточно прав доступа в административную панель.'));
            }

            return true;
        } else 
            return false;
    }
    
    public function addStyle($controller)
    {
        $controller->layout = 'main';
            Yii::app()->clientScript->registerCssFile('/css/back/backend.css?'.time());
            Yii::app()->clientScript->registerCssFile('/css/ui/jquery-ui-1.10.3.css');
            Yii::app()->clientScript->registerCssFile('/css/back/alertify/core.css');
            Yii::app()->clientScript->registerCssFile('/css/back/alertify/default.css');
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile('/js/ui/jquery-ui-1.10.3.js');
            Yii::app()->clientScript->registerScriptFile('/js/ui/timepicker.js'); 
            Yii::app()->clientScript->registerScriptFile('/js/back/backend.js');
            Yii::app()->clientScript->registerScriptFile('/js/alertify.min.js');
            Yii::app()->clientScript->registerScriptFile('/js/back/editUser.js');
            Yii::app()->clientScript->registerScriptFile('/js/back/editDiscount.js');
    }
}
