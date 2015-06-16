<?php
class CabinetController extends Controller
{
    public function actionIndex()
    {
        if(Yii::app()->user->isShop){
            Yii::app()->params['meta_title'] = 'Личный кабинет';
            $model= User::model()->findByPk(Yii::app()->user->_id);
            $model_info=new CabinetInfoForm;
            $model_info->attributes=$model->attributes;
            $model_pass=new CabinetPassForm;
            if (isset($_POST['CabinetInfoForm'])){
                $model_info->attributes=$_POST['CabinetInfoForm'];
                if ($model_info->validate()){
                    $model->attributes=$model_info->attributes;
                    if ($model->save()){
                        Yii::app()->user->setFlash('message', 'Изменения сохранены');
                    }
                    else{
                        $errors = "Ошибка при сохранении";
                        Yii::log($errors, 'error');
                        Yii::app()->user->setFlash('error', $errors);
                    }
                }
                $this->render('index', array('model_info'=>$model_info,'model_pass'=>$model_pass));
            }

            elseif (isset($_POST['CabinetPassForm'])){
                $model_pass->attributes=$_POST['CabinetPassForm'];
                if ($model_pass->validate()){
                    $model->password=crypt($model_pass->password_new, User::model()->blowfishSalt());
                    if ($model->save()){
                        Yii::app()->user->setFlash('message', 'Изменения сохранены');
                    }
                    else{
                        $errors = "Ошибка при сохранении";
                        Yii::log($errors, 'error');
                        Yii::app()->user->setFlash('error', $errors);
                    }
                }
                $this->render('index', array('model_info'=>$model_info,'model_pass'=>$model_pass));    
            }
            else{
                $this->render('index', array('model_info'=>$model_info,'model_pass'=>$model_pass));
            }
        } else {
            $this->redirect(Yii::app()->homeUrl);
        }
    }
}


