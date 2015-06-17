<?php
class ContactController extends Controller
{
    public function actionShow()
    {
            Yii::app()->params['meta_title'] = 'Контактные лица';
            
            $model_form=new AddContactForm;
            $criteria = new CDbCriteria();
            $criteria->compare('parent',Yii::app()->user->_id);
        
            $items=User::model()->findAll($criteria);
        
            if(isset($_POST['AddContactForm'])){
            $model_form->attributes=$_POST['AddContactForm'];
            if ($model_form->validate()){
              $model=new User;
              $model->attributes=$model_form->attributes;
              $pass=uniqid(rand(),true);
              $model->password = crypt($pass, User::model()->blowfishSalt());
              $model->date_created = date('Y-m-d H:i:s');
              $model->organization_type=User::LEGAL_PERSON;
              $model->parent=Yii::app()->user->_id;
              $model->status=User::USER_ACTIVE;
              
              if($model->save()){
                  $subject = "Интернет-магазин ЛБР-АгроМаркет: регистрация";
                  $message = "Здравствуйте! Спасибо за регистрацию в Интернет-магазине компании ЛБР-АгроМаркет ".Yii::app()->params['host'].".<br><br>"
                  ."Ваш логин: ".$model->login
                  ."<br>Ваш временный пароль:".$pass
                  ."<br>При первом входе рекомендуем сменить пароль.<br><br>С уважением, Администрация сайта ".Yii::app()->params['host'];
                  $add_param="Content-type:text/html; Charset=windows-1251;";
                  mail("'".$model->email."'", $subject, $message, $add_param);
                  Yii::app()->user->setFlash('message','Контактное лицо добавлено');
              }
              else{
                  $errors = "Ошибка при сохранении";
                  Yii::log($errors, 'error');
                  Yii::app()->user->setFlash('error', $errors);
              }
              $this->redirect('/user/contact/show/');
              
          }
          else{
             $this->render('index',array('items'=>$items, 'model_form'=>$model_form));
          }
        }
        else{
            $this->render('index', array('items'=>$items, 'model_form'=>$model_form));
        }
        
    }
    
    public function actionRemove($id) {
        if (!empty($id)){
            $contact = User::model()->findByPk($id);
            if(!empty($contact)) {
                $contact->delete();
                Yii::app()->user->setFlash('message', 'Контактное лицо удалено');
                $this->redirect(array('/user/contact/show/'));
            }
        }
    }
    
  
}

