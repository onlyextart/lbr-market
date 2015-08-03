<?php

class AnaliticsController extends Controller 
{
    public function actionSave() 
    {
        $url = Yii::app()->getBaseUrl(true).Yii::app()->request->getPost('url');
        $cookies = Yii::app()->request->cookies;
        if((Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && Yii::app()->user->isShop)) && !strpos($url, '/user/login') && (isset($cookies['ct']) || isset($cookies['sb']))) {
            $model = new Analitics;
            $model->push_1C = true;
            $model->time = Yii::app()->request->getPost('time');
            $model->url_mark = Yii::app()->request->getPost('url_mark');
            $model->date_created = date('Y-m-d H:i:s');
            
            // set url
            /*$end = strpos($url, '/?');
            if ($end) {
                $model->link_id = $this->getLinkId($url);
                $model->url = substr($url, 0, $end);
            } else $model->url = substr($url, 0, strlen($url) - 1);*/
            // end set url
            
            $model->url = substr($url, 0, strlen($url) - 1);
            if(isset($cookies['lk'])) $model->link_id = $cookies['lk']->value; //$this->getLinkId($url);
            
            if (!empty($cookies['ct'])) {
                $model->customer_id = SecurityController::decrypt($cookies['ct']->value);
            }

            if (!empty($cookies['sb'])) {
                $model->subscription_id = $cookies['sb']->value;
            }
            
            $model->save();
        }
    }
    
    public function actionDelAnalitics() 
    {
        // admin
        if (!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop))
           Analitics::model()->deleteAll();
    }

    /*public function getLinkId($str) 
    {
        $output = '';
        $temp = substr($str, $end + 2);
        $temp = explode("&", $temp);
        $result = array();
        foreach ($temp as $key => $val) {
            list($key, $value) = explode('=', $val);
            $result[$key] = $value;
        }
        if(!empty($result['lk'])) $output = $result['lk'];

        return $output;
    }*/
}