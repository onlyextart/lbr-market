<?php

class ContactsController extends Controller
{
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'MyCCaptchaAction',
                'backColor'=>0xFFFFFF,
                'testLimit'=>1,
            ),
        );
    }
    
    public function actionIndex($id=null) 
    {
        $formModel = new ContactForm('insert');
        $sectionName = 'Контакты';
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = $sectionName;
        
        if(empty($id)){// page with all contacts
            $breadcrumbs[] = $sectionName;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            $formModel->flagCommonContacts = true;
            $allRegions = $this->setRegionsForContactForm();
            if(isset($_POST['ContactForm'])) {
                $subject = 'from LBR.RU';
                $mailTo='';
                if(!empty($_POST['ContactForm']['mailTo'])){
                   $mailTo = ContactForm::$realMails[$_POST['ContactForm']['mailTo']];
                }
                $this->sendMail($_POST['ContactForm'], $formModel, $subject, $mailTo, $allRegions[$_POST['ContactForm']['region']]);
            }
            $this->render('commonContacts', array('formModel'=>$formModel,'regions' => $allRegions));
        }
        else{
            $contactModel = Yii::app()->db_lbr->createCommand()
                ->select('*')
                ->from('contacts')
                ->where('id= '.$id)
                ->queryRow();
            $breadcrumbs[$sectionName] = '/contacts/';
            $breadcrumbs[] = $contactModel["name"];
            Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] .= ' '.$contactModel["name"];
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            $formModel->region = $contactModel["name"];
            $formModel->flagCommonContacts = false;
            if(isset($_POST['ContactForm'])) {
                $subject = 'from LBR.RU';
                $this->sendMail($_POST['ContactForm'], $formModel, $subject, $contactModel["email"]);
                //$this->sendMail($_POST['ContactForm'], $formModel, $subject, 'teterukova@lbr.ru');
            }
            $this->render('index', array('contactModel'=>$contactModel,'formModel'=>$formModel));
        }
    }
    static public function getDistricts()
    {
        return array(
                '6'=>'Центральный федеральный округ',   
                '1'=>'Приволжский федеральный округ',
                '4'=>'Сибирский федеральный округ',
                '7'=>'Южный федеральный округ',  
                '5'=>'Уральский федеральный округ',
                '0'=>'Дальневосточный федеральный округ',  
                '2'=>'Северо-Западный федеральный округ',
                '3'=>'Северо-Кавказский федеральный округ',
            );
    }
    static public function getFilialsInDistrict($district_id)
    {
       
           $regions = Yii::app()->db_lbr->createCommand()
                    ->selectDistinct('c.name name, c.alias alias, c.address address, c.telephone telephone, c.email email')
                    ->from('regions r')
                    ->join('contacts c', 'c.id=r.contact_id')
                    ->where('r.published=1 AND c.okrug_id=:district_id AND r.district_id=:district_id', array(':district_id'=>$district_id))
                    ->order('c.name')
                    ->queryAll();
           return $regions;
       
    }
    
    public function setRegionsForContactForm()
    {
        $allRegions = array();
        
        $tempRegions = Yii::app()->db_lbr->createCommand()
            ->select('id, name')
            ->from('regions')
            ->order('name')
            ->queryAll()
        ;

        foreach($tempRegions as $oneRegion) {
            $allRegions[$oneRegion['id']] = $oneRegion['name'];
        }
        
        return $allRegions;
    }
    
    public function sendMail($post, $model, $subject, $mailTo, $regionName = null)
    {
        $model->attributes = $post;
        if ($model->validate()) {
            $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
            $headers = "From: $name <{$model->email}>\r\n" .
                "Reply-To: {$model->email}\r\n" .
                "MIME-Version: 1.0\r\n" .
                "Content-type: text/plain; charset=UTF-8"
            ;
            
            $message = "Имя: ".$model->name."\r\n".
                "Организация: ".$model->company."\r\n"
            ;
            
            if(!empty($regionName)) $message .= "Регион: ".$regionName."\r\n";
            
            $message .= "Телефон: ".$model->phone."\r\n".
                "Email: ".$model->email."\r\n\r\n".
                $model->body
            ;
            
            mail($mailTo, $subject, $message, $headers);

            Yii::app()->user->setFlash('success', 'Ваше письмо отправлено.');
            $this->refresh();
        } else Yii::app()->user->setFlash('error', 'Форма заполнена не полностью.');
    }
    
    
}

