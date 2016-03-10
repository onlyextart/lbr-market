<?php
class WishlistController extends Controller
{
    public function actionShow()
    {
        Yii::app()->params['meta_title'] = 'Блокнот';
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id = :user';
        $criteria->params = array(':user'=>Yii::app()->user->_id);
        $criteria->with = array('product');
        
        $dataProvider = new CActiveDataProvider('Wishlist',
            array(
                'criteria' => $criteria,
                'pagination' => array(
                   'pageSize' => 6,
                   'pageVar' => 'page',
                )
            )
        );
        
        $count = Wishlist::model()->count($criteria);
        $this->render('index', array('data'=>$dataProvider, 'count'=>$count));
    }
}

