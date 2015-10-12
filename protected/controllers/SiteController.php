<?php

class SiteController extends Controller {

    public function actionIndex($s = null) {
        $makers = $this->getMakers();
        $bestOffer = $this->getBestOffer();
        $hitProducts = $this->getHitProducts();
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'];
        $this->render('index', array('hitProducts' => $hitProducts, 'bestoffer' => $bestOffer, 'makers' => $makers));
    }
    
    public function getMakers() {
        $result = '';
        $equipmentMakers = EquipmentMaker::model()->getAllMakers();
        $productMakers = ProductMaker::model()->getAllMakers();

        if (!empty($equipmentMakers) || !empty($productMakers)) {
            $result = '<div id="carousel-logo-wrapper">' .
                    '<div id="carousel-logo" class="jcarousel">' .
                    '<ul>'
            ;

            $path = Yii::getPathOfAlias('webroot');
            foreach ($equipmentMakers as $maker) {
                if (file_exists($path . $maker['logo'])) {
                    $result .= '<li><a href="/equipment-maker' . $maker['path'] . '/" target="_blank"><div class="img-container bwWrapper"><img src="' . $maker['logo'] . '" alt="' . $maker['name'] . '" /></div></a></li>';
                }
            }
            foreach ($productMakers as $maker) {
                if (file_exists($path . $maker['logo'])) {
                    $result .= '<li><a href="/product-maker' . $maker['path'] . '/" target="_blank"><div class="img-container bwWrapper"><img src="' . $maker['logo'] . '" alt="' . $maker['name'] . '" /></div></a></li>';
                }
            }

            $result .= '</ul>' .
                    '<div class="clearfix"></div>' .
                    '<a id="prev-logo" class="prev" href="#">&lt;</a>' .
                    '<a id="next-logo" class="next" href="#">&gt;</a>' .
                    '</div>' .
                    '</div>'
            ;
        }

        return $result;
    }

    public function getHitProducts() {
        $result = '';
        $count = 8;
        $hitProducts = '';

        $elements = Yii::app()->db->createCommand()
                ->select('id')
                ->from('product')
                ->where('published=:flag and image IS NOT NULL and liquidity = "A"', array(':flag' => true))
                ->queryColumn()
        ;
        $max = count($elements);
        if ($max > 0) {
            if ($max >= $count) {
                $random_elem = array_rand($elements, $count);
            } else {
                $random_elem = array_rand($elements, $max);
            }
            $random_count = count($random_elem);
            $query = "SELECT * from product where id in (";
            for ($i = 0; $i < $random_count; $i++) {
                if ($i != 0) {
                    $query.=',';
                }
                $query.=$elements[$random_elem[$i]];
            }
            $query.=");";
            $result = Yii::app()->db->createCommand($query)->query();
            $hitProducts = $result->readAll();
        }

        if (!empty($hitProducts)) {
            $result = '<hr id="hit-line-first"/><hr id="hit-line-second"/><span class="hit-label-main">Рекомендуем</span>' .
                    '<div class="best-sales">'
            ;

            foreach ($hitProducts as $product) {
                $result .= '<div class="one_banner">';
                $result .= '<h3><a target="_blank" href="' . $product['path'] . '">' . $product['name'] . '</a></h3>';
                $result .= '<div class="img-wrapper">';

                $image = Product::model()->getImage($product['image'], 'm');

                $result .= '<a target="_blank" href="' . $product['path'] . '">' .
                        '<img src="' . $image . '" alt="' . $product['name'] . '">' .
                        '</a>'
                ;
                $result .= '</div></div>';
            }
            $result .= '</div>';
        }

        return $result;
    }

    public function getBestOffer() {
        $result = '';
        $bestOffers = BestOffer::model()->findAll(array('condition' => 'published=1', 'order' => 'IFNULL(level, 1000000000)'));
        if (!empty($bestOffers)) {
            $result = '<div id="carousel-wrapper">' .
                    '<div id="carousel">' .
                    '<ul>'
            ;
            foreach ($bestOffers as $offer) {
                $link = "/seasonalsale/index/id/" . $offer->id;
                if (file_exists(Yii::getPathOfAlias('webroot') . $offer->img)) {
                    $result .= '<li><a href="' . $link . '"><img src="' . $offer->img . '" alt="' . $offer->name . '"></a></li>';
                }
            }
            $result .= '</ul>' .
                    '<div class="clearfix"></div>' .
                    '<div id="pager" class="pager"></div>' .
                    '</div></div>'
            ;
        }

        return $result;
    }

    public function actionDescription($url) 
    {
        $model = Page::model()->findByAttributes(array('url' => $url));
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = $breadcrumbs[] = $model->title;
        //Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        $this->render('staticPage', array('data' => $model), false, true);
    }
    
    public function actionAboutus()
    {
        $this->render('aboutus', array());
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'MyCCaptchaAction',
                'testLimit' => 1,
            ),
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('captcha'),
                'users' => array('*'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function actionRegistration() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect('/');
        }
        $model_form_start = new RegistrationTypeForm;
        $model_form = array(
            'IND' => new RegFormInd,
            'LEGAL_PERSON' => new RegFormLegalPerson,
        );

        $model = new User;
        $model->status = User::USER_NOT_ACTIVATED;
        if (!isset($model_form['LEGAL_PERSON']->country_id)) {
            $model_form['LEGAL_PERSON']->country_id = UserCountry::RUSSIA;
        }

        if (isset($_POST['RegistrationTypeForm'])) {
            $model_form_start->attributes = $_POST['RegistrationTypeForm'];
            if ($model_form_start->validate()) {
                if ($_POST['RegistrationTypeForm']['organization_type'] == User::INDIVIDUAL) {
                    $this->render('registration_ind', array('model_form' => $model_form['IND']));
                } elseif ($_POST['RegistrationTypeForm']['organization_type'] == User::LEGAL_PERSON) {
                    $this->render('registration_legal_person', array('model_form' => $model_form['LEGAL_PERSON']));
                }
            } else {
                $this->render('registration_start', array('model_form_start' => $model_form_start));
            }
        } elseif (isset($_POST['RegFormInd']) || isset($_POST['RegFormLegalPerson'])) {
            if (isset($_POST['RegFormInd'])) {
                $model_form['IND']->attributes = $_POST['RegFormInd'];

                $login = $_POST['RegFormInd']['login'];
                $view = 'registration_ind';
                $key = 'IND';
                if ($model_form['IND']->validate()) {
                    $valid = true;
                    $model->attributes = $model_form['IND']->attributes;
                    $model->password = crypt($_POST['RegFormInd']['password'], User::model()->blowfishSalt());
                    $model->date_created = date('Y-m-d H:i:s');
                    $model->organization_type = User::INDIVIDUAL;
                    $model->address = '';
                    if (!empty($_POST['RegFormInd']['country'])) {
                        $model->address.=$_POST['RegFormInd']['country'];
                    }
                    if (!empty($_POST['RegFormInd']['region'])) {
                        if ($model->address !== '') {
                            $model->address.=', ';
                        }
                        $model->address.=$_POST['RegFormInd']['region'];
                    }
                    if (!empty($_POST['RegFormInd']['locality_name'])) {
                        if ($model->address !== '') {
                            $model->address.=', ';
                        }
                        $model->address.=$_POST['RegFormInd']['locality_type'] . ' ' . $_POST['RegFormInd']['locality_name'];
                    }
                } else {
                    $this->render('registration_ind', array('model_form' => $model_form['IND']));
                    exit();
                }
            } else {
                $model_form['LEGAL_PERSON']->attributes = $_POST['RegFormLegalPerson'];
                $login = $_POST['RegFormLegalPerson']['login'];
                $view = 'registration_legal_person';
                $key = 'LEGAL_PERSON';
                if ($model_form['LEGAL_PERSON']->validate()) {
                    $valid = true;
                    $model->attributes = $model_form['LEGAL_PERSON']->attributes;
                    $model->password = crypt($_POST['RegFormLegalPerson']['password'], User::model()->blowfishSalt());
                    $model->date_created = date('Y-m-d H:i:s');
                    $model->organization_type = User::LEGAL_PERSON;
                } else {
                    $this->render('registration_legal_person', array('model_form' => $model_form['LEGAL_PERSON']));
                    exit();
                }
            }

            if ($model->save() && $valid) {
                $criteria = new CDbCriteria();
                $criteria->select = 'id,login,email';
                $criteria->condition = 'login=:login';
                $criteria->params = array(':login' => $login);
                $model_user = User::model()->findAll($criteria);

                $activation = md5($model_user[0]->id);
                //отправка письма
                $address = Yii::app()->params['admin_email'];
                $name = 'Интернет-магазин ЛБР АгроМаркет';

                $mail = new YiiMailer('reg_user', array(
                    'login' => $model_user[0]->login,
                    'activation' => $activation));

                $mail->setFrom($address, $name);
                $mail->setSubject('Подтверждение регистрации');
                $mail->setTo($model_user[0]->email);
                if ($mail->send()) {
                    Yii::app()->user->setFlash('message', 'На Ваш E-mail выслана cсылка для активации созданной учетной записи');
                };
                $this->redirect('/');
            } else {
                $errors = "Ошибка при сохранении";
                Yii::log($errors, 'error');
                Yii::app()->user->setFlash('error', $errors);
                $this->render($view, array('model_form' => $model_form[$key]));
            }
        } else {
            $this->render('registration_start', array('model_form_start' => $model_form_start, 'model' => $model));
        }
    }

    public function actionActivation() {

        if (isset($_GET['act']) && isset($_GET['login'])) {
            $criteria = new CDbCriteria();
            $criteria->select = 'id,login,email,status';
            $criteria->condition = 'login=:login';
            $criteria->params = array(':login' => $_GET['login']);
            $model_user = User::model()->findAll($criteria);

            if (md5($model_user[0]->id) == $_GET['act']) {
                $model_user[0]->status = User::USER_NOT_CONFIRMED;
                if ($model_user[0]->save()) {
                    //отправка письма
                    $address = 'webmaster@lbr.ru';
                    $name = 'Интернет-магазин ЛБР АгроМаркет';

                    $mail = new YiiMailer('reg_admin', array(
                        'login' => $model_user[0]->login,
                        'email' => $model_user[0]->email));

                    $mail->setFrom($address, $name);
                    $mail->setSubject('Регистрация нового пользователя');
                    $mail->setTo(Yii::app()->params['admin_email']);
                    $mail->send();
                    Yii::app()->user->setFlash('message', 'Спасибо за регистрацию! Ваша учетная запись будет доступна после ее подтверждения модератором');
                } else {
                    Yii::app()->user->setFlash('error', 'Ошибка активации! <br>Обратитесь к администратору.');
                }
            } else {
                Yii::app()->user->setFlash('error', 'Ошибка активации! <br>Обратитесь к администратору.');
            }
            $this->redirect('/');
        } else {
            Yii::app()->user->setFlash('error', 'Ошибка активации! <br>Обратитесь к администратору.');
            $this->redirect('/');
        }
    }

    public function actionRestore() {
        // $form = new RestoreForm;
        if (Yii::app()->user->id) {
            $this->redirect('/');
        } else {
            // если форма с email отправлена
            $form_email = new RestoreEmailForm;
            $form_password = new RestoreForm;
            if (isset($_POST['RestoreEmailForm'])) {
                $form_email->attributes = $_POST['RestoreEmailForm'];
                if ($form_email->validate()) {
                    $criteria = new CDbCriteria();
                    $criteria->select = 'id,login,email';
                    $criteria->condition = 'email=:email';
                    $criteria->params = array(':email' => $form_email->email);
                    $model_user = User::model()->findAll($criteria);
                    $restore_key = md5($model_user[0]->id);
                    $restore_email = "http://" . Yii::app()->params['host'] . "/site/restore?login=" . $model_user[0]->login . "&key=" . $restore_key;

                    //отправка письма
                    $address = Yii::app()->params['admin_email'];
                    $name = 'Интернет-магазин ЛБР АгроМаркет';

                    $mail = new YiiMailer('restore_user', array(
                        'restore_email' => $restore_email));

                    $mail->setFrom($address, $name);
                    $mail->setSubject('Восстановление доступа');
                    $mail->setTo($model_user[0]->email);
                    $mail->send();
                    Yii::app()->user->setFlash('message', 'На Ваш E-mail выслана cсылка для восстановления учетной записи');
                    $this->redirect('/');
                } else {
                    $this->render('restoreEmail', array('form_email' => $form_email));
                }
            } elseif (isset($_GET['key']) && isset($_GET['login'])) {
                $criteria = new CDbCriteria();
                $criteria->select = 'id,login,password,status';
                $criteria->condition = 'login=:login';
                $criteria->params = array(':login' => $_GET['login']);
                $model_user = User::model()->findAll($criteria);
                if (md5($model_user[0]->id) == $_GET['key']) {
                    if (($model_user[0]->status == User::USER_ACTIVE) || ($model_user[0]->status == User::USER_WARNING)) {
                        $form_password->id = $model_user[0]->id;
                        $this->render('restore', array('form_password' => $form_password));
                    } else {
                        Yii::app()->user->setFlash('error', 'Доступ к Вашей учетной записи заблокирован! Обратитесь к администратору');
                        $this->redirect('/');
                    }
                } else {
                    Yii::app()->user->setFlash('error', 'Ошибка восстановления доступа! <br>Обратитесь к администратору');
                    $this->redirect('/');
                }
            } elseif (isset($_POST['RestoreForm'])) {
                $criteria = new CDbCriteria();
                $criteria->select = 'id,login,email,password';
                $criteria->condition = 'id=:id';
                $criteria->params = array(':id' => $_POST['RestoreForm']['id']);
                $model_user = User::model()->findAll($criteria);
                if ($model_user) {
                    $model_user[0]->password = crypt($_POST['RestoreForm']['password_new'], User::model()->blowfishSalt());
                    if ($model_user[0]->save()) {
                        Yii::app()->user->setFlash('message', 'Пароль успешно изменен');
                        $this->redirect('/');
                    } else {
                        Yii::app()->user->setFlash('error', 'Ошибка при сохранении пароля! <br>Обратитесь к администратору');
                        $this->redirect('/');
                    }
                } else {
                    Yii::app()->user->setFlash('error', 'Ошибка при сохранении пароля! <br>Обратитесь к администратору');
                    $this->redirect('/');
                }
            } else {
                $this->render('restoreEmail', array('form_email' => $form_email));
            }
        }
    }

    public function actionError() {
        $this->render('pageNotFound');
    }

    // Форма отправки заявки
    public function actionQuickform() {
        if (!Yii::app()->user->isGuest) {
            if (!Yii::app()->user->isShop) {
                $this->redirect('/');
            }
        }


        $model = new QuickForm('insert');
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'quick-form') { //тут ajax-валидация
            $model->setScenario('ajax'); // метод, устанавливающий сценарий 'ajax'
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['QuickForm'])) {
            $model->attributes = $_POST['QuickForm'];
            //Сохраняем загруженные файлы на сервер нашей функцией uploadMultifile
            if ($filez = $this->uploadMultifile($model, 'attachments', '/images/quickform/')) {
                $model->attachments = implode(",", $filez);
            }
            if ($model->validate()) {

                //используем представление 'quickform' из директории views/mail
                $address = 'webmaster@lbr.ru';
                $name = 'Интернет-магазин ЛБР АгроМаркет';
                $mail = new YiiMailer('quickform', array(
                    'name' => $model->name,
                    'email' => $model->email,
                    'phone' => $model->phone,
                    'region' => $model->region,
                    'organization' => $model->organization,
                    'body' => $model->body,
                    'delivery' => $model->delivery,
                    'region' => $model->region,
                    'adress' => $model->adress));
                //устанавливаем свойства        
                $mail->setFrom($address, $name);
                $mail->setSubject("Письмо с сайта " . Yii::app()->params['host'] . ". Создана заявка от " . $model->name);
                $mail->setTo('shop@lbr.ru');
                $mail->setAttachment($model->attachments);



                //Прикрепляем к сообщению загруженные файлы с помощью setAttachment() 
                $attachments = explode(',', $model->attachments);
                if (count($attachments)) {
                    foreach ($attachments as $file) {
                        $mail->setAttachment('images/quickform/' . $file);
                    }
                }

                //отправляем сообщение
                if ($mail->send()) {
                    Yii::app()->user->setFlash('message', 'Заявка принята, с Вами свяжется менеджер в ближайшее время!');
                    $this->redirect('/');
                } else {
                    Yii::app()->user->setFlash('error', 'Какая-то ошибка: ' . $mail->getError());
                }

                $this->refresh();
            }
        }

        $this->render('quickform', array('model' => $model));
    }

    public function uploadMultifile($model, $attr, $path) {

        if ($sfile = CUploadedFile::getInstances($model, $attr)) {
            foreach ($sfile as $i => $file) {
                $formatName = time() . $i . '.' . $file->getExtensionName();
                $file->saveAs(Yii::app()->basePath . DIRECTORY_SEPARATOR . '..' . $path . $formatName);
                $ffile[$i] = $formatName;
            }
            return ($ffile);
        }
    }

    public function actionSetRegion() {
        $cookie = new CHttpCookie('lbrfilial', (int) $_POST['id']);
        $cookie->expire = time() + 60 * 60 * 24 * 30 * 12; // year
        Yii::app()->request->cookies['lbrfilial'] = $cookie;
    }

    public function actionGetRegions() {
        $exists = false;
        $filials = array();
        $chosenFilialId = Yii::app()->request->cookies['lbrfilial']->value;
        $allFilials = Filial::model()->findAll(array('condition' => 'level != 1'));
        foreach ($allFilials as $filial) {
            $filials['filials'][$filial->id] = $filial->name;
        }

        if (!empty($chosenFilialId))
            $exists = Filial::model()->exists('id = :id', array(':id' => $chosenFilialId));
        // set active element
        if (Yii::app()->search->prepareSqlite() && !$exists) {
            $filials['active'] = Filial::model()->find('lower(name) like lower("%Москва%")')->id;
        } else
            $filials['active'] = $chosenFilialId;

        echo json_encode($filials);
    }
    
    public function actionCanselPublMakers() {
        $sql="UPDATE equipment_maker SET published=0 WHERE logo IS NULL or description IS NULL;";
        $connection=Yii::app()->db;
        $command=$connection->createCommand($sql);
        $rowCount=$command->execute();  
        echo $rowCount;
    }

}
