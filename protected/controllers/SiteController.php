<?php
class SiteController extends Controller
{
    public function actionIndex($s = null)
    {   
        //Yii::app()->session['category'] = Yii::app()->session['maker'] = Yii::app()->session['model'] = Yii::app()->session['search'] = null;
        //if(empty(Yii::app()->session['order'])) Yii::app()->session['order'] = 'asc';
        //if(empty(Yii::app()->session['sort'])) Yii::app()->session['sort'] = 'col';
        
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
        $max = Product::model()->cache(1000, $dependency)->count(array(
            'condition' => 'liquidity = "A" and image IS NOT NULL', // price more 500 
        ));
        
        if($max > 8) {
            $temp = array();
            for($i=0; $i<8; ) {
                $offset = mt_rand(0, $max);
                $hitProductId = Product::model()->cache(1000, $dependency)->find(array(
                    'condition' => 'liquidity = "A" and image IS NOT NULL', // price more 500 
                    'offset' => $offset,
                    'limit' => 1,
                ))->id;
                
                if(!in_array($hitProductId, $temp)) {
                   $temp[] = $hitProductId;
                   $i++;
                }
            }
            
            $hitProducts = Product::model()->cache(1000, $dependency)->findAllByAttributes(array('id'=>$temp));
        } else {
            $hitProducts = Product::model()->cache(1000, $dependency)->findAll(array(
                'condition' => 'liquidity = "A" and image IS NOT NULL', // price more 500
                'limit' => 8,
            ));
        }
        
        $bestOffer = BestOffer::model()->findAll(array('condition'=>'published=1', 'order'=>'IFNULL(level, 1000000000)'));
        $filials = array ('1' => 'Москва', '2' => 'Новосибирск');
        
        $this->render('index', array('hitProducts' => $hitProducts, 'bestoffer' => $bestOffer, 'filials' => $filials));
    }
    
    public function actionDescription($url)
    {
        //Yii::app()->session['category'] = Yii::app()->session['maker'] = null;

        $model = Page::model()->findByAttributes(array('url'=>$url));
        $this->render('staticPage', array('data'=>$model), false, true);
    }
    
    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'MyCCaptchaAction',
            ),
        );
    }
    
    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array('captcha'),
                'users'=>array('*'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }

    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }
    
    public function actionRegistration()
    {
        if(!Yii::app()->user->isGuest){
            $this->redirect('/');
        }
        $model_form_start=new RegistrationTypeForm;
        $model_form=array(
            'IND'=>new RegFormInd,
            'LEGAL_PERSON'=>new RegFormLegalPerson,
        );
        //$model_form_ind=new RegFormInd;
        //$model_form_LP=new RegFormLegalPerson;
        $model=new User;
        $model->status = User::USER_NOT_ACTIVATED;
//        if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
//        {
//            $model->login=$_POST['User']['login'];
//            echo CActiveForm::validate($model);
//            Yii::app()->end();
//        }
        if(isset($_POST['RegistrationTypeForm']))
        {
            $model_form_start->attributes=$_POST['RegistrationTypeForm'];
            if($model_form_start->validate()){
                if ($_POST['RegistrationTypeForm']['organization_type']==User::INDIVIDUAL){
                    $this->render('registration_ind',array('model_form'=>$model_form['IND']));
                }
                elseif ($_POST['RegistrationTypeForm']['organization_type']==User::LEGAL_PERSON){
                    $this->render('registration_legal_person',array('model_form'=>$model_form['LEGAL_PERSON']));
                }
            } else {
                $this->render('registration_start',array('model_form_start'=>$model_form_start));
            }
        }
        
        elseif(isset($_POST['RegFormInd'])||isset($_POST['RegFormLegalPerson']))
        {
            if (isset($_POST['RegFormInd'])){
                $model_form['IND']->attributes=$_POST['RegFormInd'];
                
                $login=$_POST['RegFormInd']['login'];
                $view='registration_ind';
                $key='IND';
                if($model_form['IND']->validate()){
                    $valid=true;
                    $model->attributes=$model_form['IND']->attributes;
                    $model->password = crypt($_POST['RegFormInd']['password'], User::model()->blowfishSalt());
                    $model->date_created = date('Y-m-d H:i:s');
                    $model->organization_type=User::INDIVIDUAL;
                    $model->address='';
                    if (!empty($_POST['RegFormInd']['country'])){
                        $model->address.=$_POST['RegFormInd']['country'];
                    }
                    if (!empty($_POST['RegFormInd']['region'])){
                        if($model->address!==''){
                            $model->address.=', ';
                        }
                        $model->address.=$_POST['RegFormInd']['region'];
                    }
                    if (!empty($_POST['RegFormInd']['locality_name'])){
                        if($model->address!==''){
                            $model->address.=', ';
                        }
                        $model->address.=$_POST['RegFormInd']['locality_type'].' '.$_POST['RegFormInd']['locality_name'];
             
                    }
                   
                }
                
                else{
                    $this->render('registration_ind',array('model_form'=>$model_form['IND']));
                    exit();
                }
            }
            else {
                $model_form['LEGAL_PERSON']->attributes=$_POST['RegFormLegalPerson'];
                $login=$_POST['RegFormLegalPerson']['login'];
                $view='registration_legal_person';
                $key='LEGAL_PERSON';
                if($model_form['LEGAL_PERSON']->validate()){
                    $valid=true;
                    $model->attributes=$model_form['LEGAL_PERSON']->attributes;
                    $model->password = crypt($_POST['RegFormLegalPerson']['password'], User::model()->blowfishSalt());
                    $model->date_created = date('Y-m-d H:i:s'); 
                    $model->organization_type=User::LEGAL_PERSON;
                }
                else{
                    $this->render('registration_legal_person',array('model_form'=>$model_form['LEGAL_PERSON']));
                    exit();
                }
            }
            
            if($model->save()&&$valid) {
                $criteria=new CDbCriteria(); 
                $criteria->select = 'id,login,email';
                $criteria->condition='login=:login';
                $criteria->params = array(':login'=>$login);
                $model_user= User::model()->findAll($criteria);
                
                $activation = md5($model_user[0]->id);
                $email = new TEmail;
                $email->from_email = Yii::app()->params['admin_email'];
                $email->from_name = 'Интернет-магазин ЛБР АгроМаркет';
                $email->to_email = $model_user[0]->email;
                $email->to_name = $model_user[0]->name;
                $email->subject = 'Подтверждение регистрации';
                $email->type = 'text/html';
                $email->body = '<p>Здравствуйте! Спасибо за регистрацию в Интернет-магазине компании ЛБР-АгроМаркет '.Yii::app()->params['host'].'.</p><br>'
                .'<p>Ваш логин: '.$model_user[0]->login.'</p><p>Чтобы завершить регистрацию, нужно активировать созданную учетную запись. Для этого перейдите по <a href="http://'.Yii::app()->params['host'].'/site/activation?login='.$model_user[0]->login.'&act='.$activation.'">ссылке.</a></p><br><br><p>С уважением, Администрация сайта '.Yii::app()->params['host'].'</p>';
                
                $email->sendMail();
                Yii::app()->user->setFlash('message','На Ваш E-mail выслана cсылка для активации созданной учетной записи');
                $this->redirect('/');
             } else {
                $errors = "Ошибка при сохранении";
                Yii::log($errors, 'error');
                Yii::app()->user->setFlash('error', $errors);
                $this->render($view,array('model_form'=>$model_form[$key]));
             }
            
           
        }
        
//            else{
//                $this->render('registration',array('model_form'=>$model_form,'model'=>$model));
//            }
        else{
            $this->render('registration_start',array('model_form_start'=>$model_form_start,'model'=>$model)); 
        }
    }
    
    public function actionActivation(){
           
           if (isset($_GET['act']) && isset($_GET['login'])) {
                $criteria=new CDbCriteria();
                $criteria->select = 'id,login,email,status';
                $criteria->condition='login=:login';
                $criteria->params = array(':login'=>$_GET['login']);
                $model_user= User::model()->findAll($criteria);
            
                if(md5($model_user[0]->id)==$_GET['act']){
                    $model_user[0]->status = User::USER_NOT_CONFIRMED;
                    if ($model_user[0]->save()){
                        $email = new TEmail;
                        $email->from_email = Yii::app()->params['admin_email'];
                        $email->from_name = 'Интернет-магазин ЛБР АгроМаркет';
                        $email->to_email = 'isakov@lbr.ru,boyko@lbr.ru,teterukova@lbr.ru';
                        $email->to_name = 'Исаков Федор Федорович';
                        $email->subject = 'Подтверждение регистрации';
                        $email->type = 'text/html';
                        $email->body = '<p>Здравствуйте! Зарегистрировался новый пользователь: login '.$model_user[0]->login.', email '.$model_user[0]->email.'</p>';
                        $email->sendMail();
                        Yii::app()->user->setFlash('message','Спасибо за регистрацию! Ваша учетная запись будет доступна после ее подтверждения модератором');
                        
                    }    
                    else{
                        Yii::app()->user->setFlash('error','Ошибка активации! <br>Обратитесь к администратору.');  
                    }
                }
                else { 
                    Yii::app()->user->setFlash('error','Ошибка активации! <br>Обратитесь к администратору.');
               
                }
                $this->redirect('/');
           } else { 
               Yii::app()->user->setFlash('error','Ошибка активации! <br>Обратитесь к администратору.');
               $this->redirect('/');
           }
           
    }
    
    public function actionRestore()
    {
       // $form = new RestoreForm;
        if (Yii::app()->user->id) {
            $this->redirect('/');
        } else {
            // если форма с email отправлена
            $form_email=new RestoreEmailForm;
            $form_password=new RestoreForm;
            if(isset($_POST['RestoreEmailForm'])){
                $form_email->attributes=$_POST['RestoreEmailForm'];
                if ($form_email->validate()){
                    $criteria=new CDbCriteria();
                    $criteria->select = 'id,login,email';
                    $criteria->condition='email=:email';
                    $criteria->params = array(':email'=>$form_email->email);
                    $model_user= User::model()->findAll($criteria);
                    $restore_key = md5($model_user[0]->id);
                    $restore_email="http://".Yii::app()->params['host']."/site/restore?login=".$model_user[0]->login."&key=".$restore_key;
                    
                    $email = new TEmail;
                    $email->from_email = Yii::app()->params['admin_email'];
                    $email->from_name = 'Интернет-магазин ЛБР АгроМаркет';
                    $email->to_email = $model_user[0]->email;
                    $email->to_name = $model_user[0]->name;
                    $email->subject = 'Восстановление доступа';
                    $email->type = 'text/html';
                    $email->body = '<p>Для восстановления доступа к сайту перейдите по <a href="'.$restore_email.'">ссылке</a>.</p><br><br><p>С уважением, Администрация сайта '.Yii::app()->params['host'].'</p>';
                   
                    $email->sendMail();
                    Yii::app()->user->setFlash('message','На Ваш E-mail выслана cсылка для восстановления учетной записи');
                    $this->redirect('/');
                }
                else{
                   $this->render('restoreEmail',array('form_email'=>$form_email));
                }
            }
            
            elseif(isset($_GET['key']) && isset($_GET['login'])){
                $criteria=new CDbCriteria();
                $criteria->select = 'id,login,password,status';
                $criteria->condition='login=:login';
                $criteria->params = array(':login'=>$_GET['login']);
                $model_user= User::model()->findAll($criteria);
                if(md5($model_user[0]->id)==$_GET['key']){
                    if(($model_user[0]->status==User::USER_ACTIVE)||($model_user[0]->status==User::USER_WARNING)){
                        $form_password->id=$model_user[0]->id;
                        $this->render('restore',array('form_password'=>$form_password));
                    }
                   else{
                        Yii::app()->user->setFlash('error','Доступ к Вашей учетной записи заблокирован! Обратитесь к администратору');
                        $this->redirect('/');
                    }
                   
                }
                else { 
                    Yii::app()->user->setFlash('error','Ошибка восстановления доступа! <br>Обратитесь к администратору');
                    $this->redirect('/'); 
                }
                
            }
                
            elseif(isset($_POST['RestoreForm'])){
                $criteria=new CDbCriteria();
                $criteria->select = 'id,login,email,password';
                $criteria->condition='id=:id';
                $criteria->params = array(':id'=>$_POST['RestoreForm']['id']);
                $model_user= User::model()->findAll($criteria);
                if ($model_user){
                    $model_user[0]->password = crypt($_POST['RestoreForm']['password_new'], User::model()->blowfishSalt());
                    if($model_user[0]->save()){
                        Yii::app()->user->setFlash('message','Пароль успешно изменен');
                        $this->redirect('/');
                    }
                    else{
                        Yii::app()->user->setFlash('error','Ошибка при сохранении пароля! <br>Обратитесь к администратору');
                        $this->redirect('/'); 
                    }
                }
                else{
                    Yii::app()->user->setFlash('error','Ошибка при сохранении пароля! <br>Обратитесь к администратору');
                    $this->redirect('/'); 
                }
                
            }
            
            else{
               $this->render('restoreEmail',array('form_email'=>$form_email)); 
            }
        }

    }
    
    public function actionError()
    {
        $this->render('pageNotFound'); 
    }
    
        // Форма отправки заявки
        public function actionQuickform()
        {
            if(!Yii::app()->user->isGuest) {
                if(!Yii::app()->user->isShop) {                   
                     $this->redirect('/');
                    }
                }


        $model=new QuickForm('insert'); 
        if(isset($_POST['ajax']) && $_POST['ajax']==='quick-form') //тут ajax-валидация
        {
        $model->setScenario('ajax'); // метод, устанавливающий сценарий 'ajax'
        echo CActiveForm::validate($model);
        Yii::app()->end();
        }
        if(isset($_POST['QuickForm']))
        {
        $model->attributes=$_POST['QuickForm'];
        if($model->validate())
        {                    
        
        //используем представление 'quickform' из директории views/mail
        $mail = new YiiMailer ('quickform', 
                array(
                    
                    'name' => $model->name, 
                    'email' => $model->email,
                    'phone' => $model->phone, 
                    'region' => $model->region,                   
                    'organization' => $model->organization,
                    'body' => $model->body));
        //устанавливаем свойства
        $mail->setFrom($model->email, $model->name);
        $mail->setSubject("Письмо с сайта ".Yii::app()->params['host'].". Создана заявка от ".$model->name);
        $mail->setTo('vasiliyan@lbr.ru');

        //Сохраняем загруженные файлы на сервер нашей функцией uploadMultifile
         if($filez=$this->uploadMultifile($model,'attachments','/images/quickform/'))
           {
        $model->attachments=implode(",", $filez);
           }

        //Прикрепляем к сообщению загруженные файлы с помощью setAttachment() 
        $attachments = explode(',', $model->attachments);
                if (count($attachments)) {
                    foreach ($attachments as $file) {
                        $mail->setAttachment('images/quickform/'.$file);
                    }
                }

        //отправляем сообщение
        if ($mail->send()) {
        Yii::app()->user->setFlash('message','Заявка принята, с Вами свяжется менеджер в ближайшее время!');
                    $this->redirect('/');
        } else {
        Yii::app()->user->setFlash('error','Какая-то ошибка: '.$mail->getError());
        }

        $this->refresh();
        }
        }
        $this->render('quickform',array('model'=>$model));
        } 


        public function uploadMultifile ($model,$attr,$path)
        {
        
        if($sfile=CUploadedFile::getInstances($model, $attr)){
         foreach ($sfile as $i=>$file){  
            $formatName=time().$i.'.'.$file->getExtensionName();
            $file->saveAs(Yii::app()->basePath .DIRECTORY_SEPARATOR.'..'. $path.$formatName);
            $ffile[$i]=$formatName;
          }
          return ($ffile);
         }
        }
    
    /*public function actionSetRegion()
    {
        Yii::app()->session['region'] = (int)$_POST['id'];
    }*/
    
//    public function actionTestFilial()
//    {
//        $command = Yii::app()->db->createCommand();
//        $x=$command->update('user', array('filial'=>48), 'filial IS NULL');
//        if ($x)
//        {
//            echo 'true';
//        }
//        
//    }
    
    public function actionTest()
    {
        //$root1 = new ProductGroup;
        //$root1 = new Category;
        //$root1->name = 'Все категории';
        //$root1->published=true;
        //$root1->saveNode();        
        /*$root1 = ProductGroup::model()->findByPk(118);
        
        $root3 = new ProductGroup;
        //$root3 = new Category;
        $root3->name = 'Метизы';
        //$root3->published=true;
        $root3->appendTo($root1);
        
        $subCategory1 = new ProductGroup;
        //$subCategory1 = new Category;
        $subCategory1->name='Болты';
        //$subCategory1->published=true;
        
        $subCategory2 = new ProductGroup;
        //$subCategory2 = new Category;
        //$subCategory2->published=false;
        $subCategory2->name='Гайки';
        
        $subCategory2->appendTo($root3);
        $subCategory1->insertAfter($subCategory2);
        
        $root2 = new ProductGroup;
        //$root2 = new Category;
        $root2->name = '2_Метизы';
        //$root2->published=false;
        $root2->appendTo($root1);
        */
        /*
        $root1 = new ModelLine;
        $root1->name = 'Все модельные ряды';
        $root1->saveNode();   
        
        $root3 = new ModelLine;
        $root3->name = 'Case';
        $root3->path = '/case';
        $root3->appendTo($root1);
        
        $subCategory1 = new ModelLine;
        $subCategory1->name='Case Magnum';
        $subCategory1->path = '/case/case-magnum';
        
        $subCategory2 = new ModelLine;
        $subCategory2->name='Case Puma';
        $subCategory2->path = '/case/case-puma';
        
        $subCategory3 = new ModelLine;
        $subCategory3->name='Case MXM';
        $subCategory3->path = '/case/case-mxm';
        
        $subCategory4 = new ModelLine;
        $subCategory4->name='Case MTM';
        $subCategory4->path = '/case/case-mtm';
        
        $subCategory5 = new ModelLine;
        $subCategory5->name='Case STX';
        $subCategory5->path = '/case/case-stx';
        
        $subCategory6 = new ModelLine;
        $subCategory6->name='Case JX';
        $subCategory6->path = '/case/case-jx';
        
        $subCategory7 = new ModelLine;
        $subCategory7->name='Case SS';
        $subCategory7->path = '/case/case-ss';
        
        $subCategory2->appendTo($root3);
        $subCategory1->insertAfter($subCategory2);
        $subCategory3->insertAfter($subCategory2);
        $subCategory4->insertAfter($subCategory2);
        $subCategory5->insertAfter($subCategory2);
        $subCategory6->insertAfter($subCategory2);
        $subCategory7->insertAfter($subCategory2);
        */
        
        /*$root1 = new EquipmentMaker;
        $root1->name = 'Case';
        $root1->save();
        
        $root2 = new EquipmentMaker;
        $root2->name = 'Expom';
        $root2->save();*/
        
        Filial::model()->deleteAll();
    }
    public function actionTranslitePath()
    {
        set_time_limit(0);
        /*$allMakers = EquipmentMaker::model()->findAll();
        foreach($allMakers as $maker){
            $maker->path = '/'.Translite::rusencode($maker->name, '-');
            $maker->save();
        }*/
        
        /*$allProducts = Product::model()->findAll();
        foreach($allProducts as $product){
            $product->path = '/'.Translite::rusencode($product->name, '-');
            $product->save();
        }*/
        
        /*
        $allProductMakers = ProductMaker::model()->findAll();
        foreach($allProductMakers as $maker){
            $maker->path = '/'.Translite::rusencode($maker->name, '-');
            $maker->save();
        }*/
        
        //=============================================
        
        /*$allCategory = Category::model()->findAll();
        foreach($allCategory as $category) {
            //$category->path = '/'.Translite::rusencode($maker->name, '-');
            //$category->saveNode();
            
            preg_match('/\d{2,}\./i', $category->name, $result);
            $category->name = trim(substr($category->name, strlen($result[0])));
            $category->saveNode();
        }
        */
        
        /*$allCategory = Category::model()->findAll();
        foreach($allCategory as $category) {
            $path = '';
            if($category->level == 3){
                $parent = $category->parent()->find();
                $path = '/'.Translite::rusencode($parent->name, '-');
            }
            $path .= '/'.Translite::rusencode($category->name, '-');
            
            $category->path = $path;
            $category->saveNode();            
        }*/
        
        /*$allProducts = Product::model()->findAll(array('condition'=>"id > 60000 and id <= 65000"));
        //echo '<pre>';
        //var_dump($allProducts); exit;
        foreach($allProducts as $product) {
            $product->path = '/sparepart/'.$product->id.'-'.Translite::rusencode($product->name, '-').'/';
            $product->save();
        }*/
    }
}
