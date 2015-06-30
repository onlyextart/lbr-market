<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class UserController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        if(Yii::app()->user->checkAccess('shopReadUser'))
        {
            $model = new User('search');
            $model->unsetAttributes();

            if (!empty($_GET['User']))
                $model->attributes = $_GET['User'];

            $dataProvider = $model->search();
            $dataProvider->pagination->pageSize = 14;

            $this->render('user', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
        } else {
            $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
        }
    }
    
    public function actionCreate()
    {
        if(Yii::app()->user->checkAccess('shopCreateUser')) {
            $model = new User;
            $form_type_user=new UserCreateForm;
            
            if(!empty($_POST['UserCreateForm']))
            {
                $model->organization_type=$_POST['UserCreateForm']['organization_type'];
                if($model->organization_type==User::LEGAL_PERSON){
                    $form = new UserFormLegalPerson;
                    $model_name='UserFormLegalPerson';
                }
                else{
                    $form = new UserForm;
                    $model_name='UserForm';
                }
                $form->attributes = $model->attributes;
                $this->render('editUser', array('model'=>$model, 'model_form' => $form), false, true);
            }
            else{ 
                
             if(!empty($_POST['UserFormLegalPerson'])||!empty($_POST['UserForm'])) {
                    $model_name=(!empty($_POST['UserFormLegalPerson']))?'UserFormLegalPerson':'UserForm';
                    $form=(!empty($_POST['UserFormLegalPerson']))?new UserFormLegalPerson:new UserForm;
                    $model->attributes = $_POST[$model_name];
                    if(!empty($model->block_date)) $model->block_date = date('Y-m-d H:i:s', strtotime($model->block_date));
                    $model->status = User::USER_ACTIVE;
                    $model->date_created = date('Y-m-d H:i:s');
                    $form->attributes = $model->attributes;
                    $model->password = crypt($_POST[$model_name]['password'], User::model()->blowfishSalt());
                
                    if($form->validate()) {
                        if($model->save()) {
                            Yii::app()->user->setFlash('message', 'Пользователь создан успешно.');
                            $this->redirect(array('edit', 'id'=>$model->id));
                        } else {
                            $errors = $model->getErrors();
                            Yii::log($errors, 'error');
                            Yii::app()->user->setFlash('error', $errors);
                            $this->render('editUser', array('model'=>$model, 'model_form' => $form), false, true);
                        }
                    } else $this->render('editUser', array('model'=>$model, 'model_form' => $form, 'formErrors'=>$form->getErrors()), false, true);
                }
                else $this->render('createUser', array('model'=>$model, 'model_form' => $form_type_user), false, true);
            }                
       }
    }
    
    public function actionEdit($id)
    {
        $model = User::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Пользователь не найден.'));
        //echo $id; exit;
        $orders = array();
        if($model->organization_type==User::LEGAL_PERSON){
            $form = new UserFormLegalPerson;
            $model_name='UserFormLegalPerson';
            if(!isset($model->country_id)){
              $model->country_id=UserCountry::RUSSIA;  
            }
        }
        else{
            $form = new UserForm;
            $model_name='UserForm';
        }
        
        $form->attributes = $model->attributes;
        $form->id = $id;
        $test_email="";
        
        if(Yii::app()->user->checkAccess('shopEditUser')) {
            if (!empty($_POST[$model_name])) {
                $old_status=$model->status;
                $new_status=$_POST[$model_name]['status'];
                if($new_status!==$old_status){
                    $text_email="<p>Здравствуйте, ".$model->name."! Статус Вашей учетной записи был изменен на: <b>".User::$userStatus[$new_status]."</b>.</p> ";
                    switch($new_status){
                        case User::USER_TEMPORARY_BLOCKED:
                            $text_email.="Причина блокировки: ".$_POST[$model_name]['block_reason']."<br>";
                            $text_email.="Срок блокировки до: ".date('d.m.Y', strtotime($_POST[$model_name]['block_date']));
                            break;
                        case User::USER_BLOCKED:
                            $text_email.="Причина блокировки: ".$_POST[$model_name]['block_reason'];
                            break;
                        case User::USER_WARNING:
                            $text_email.="Причина: ".$_POST[$model_name]['block_reason'];
                            break;
                    }
                    
                    $text_email.="<br><br><p>С уважением, Администрация сайта <a href='http://".Yii::app()->params['host']."'>".Yii::app()->params['host']."</a></p>";
                   
                }
                $model->attributes = $_POST[$model_name];
                if($model->status == User::USER_ACTIVE|| $model->status == User::USER_NOT_CONFIRMED) $model->block_reason = null;
                if($model->status == User::USER_ACTIVE || $model->status == User::USER_WARNING|| $model->status == User::USER_NOT_CONFIRMED) $model->block_date = null;
                else if(!empty($model->block_date)) $model->block_date = date('Y-m-d H:i:s', strtotime($model->block_date));
                $form->attributes = $model->attributes;
                
                if($form->validate()) {
                    if($model->save()) {
                        Yii::app()->user->setFlash('message', 'Пользователь сохранен успешно.');
                        if(!empty($text_email)) {
                             $email = new TEmail;
                             $email->from_email = Yii::app()->params['admin_email'];
                             $email->from_name = 'Интернет-магазин ЛБР АгроМаркет';
                             $email->to_email = $model->email;
                             $email->to_name = $model->name;
                             $email->subject = 'Изменение статуса учетной записи';
                             $email->type = 'text/html';
                             $email->body = $text_email;
                             $email->sendMail();
                        }
                    } else {
                        $errors = $model->getErrors();
                        Yii::log($errors, 'error');
                        Yii::app()->user->setFlash('error', $errors);
                    }
                    $this->render('editUser', array('model'=>$model, 'model_form' => $form, 'orders'=>$orders), false, true);
                } else $this->render('editUser', array('model'=>$model, 'model_form' => $form, 'formErrors'=>$form->getErrors(), 'orders'=>$orders), false, true);
            } else $this->render('editUser', array('model'=>$model, 'model_form' => $form, 'orders'=>$orders), false, true);
        } else {
            $this->render('application.modules.admin.views.default.error', array('error' => 'Для редактирования недостаточно прав доступа.'));
        }
    }
    
    public function actionDelete($id)
    {
        $user = User::model()->findByPk($id);
        if (!$user)
            $this->render('application.modules.admin.views.default.error', array('error' => 'Пользователь не найден.'));

        $user->delete();
        Yii::app()->user->setFlash('message', 'Пользователь удален.');
        $this->redirect(array('index'));
    }
}

