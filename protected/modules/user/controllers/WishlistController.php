<?php
class WishlistController extends Controller
{
    public function actionShow()
    {
        Yii::app()->params['meta_title'] = 'Блокнот';
        $items = $temp = array();
        
        $wishlist = Wishlist::model()->findAll('user_id=:user', array(':user'=>Yii::app()->user->_id));
        foreach($wishlist as $wish) {
            $temp[] = $wish->product_id;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition("id", $temp);
        
        $dataProvider = new CActiveDataProvider('Product',
            array(
                'criteria' => $criteria,
                'pagination'=>array(
                   'pageSize' => 6,
                   'pageVar' => 'page',
                )
            )
        );
        
        $count = Product::model()->count($criteria);
        $this->render('index', array('data'=>$dataProvider, 'count'=>$count));
    }
}

