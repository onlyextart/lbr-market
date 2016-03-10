<?php
class WishlistController extends Controller
{
    public function actionShow()
    {
        Yii::app()->params['meta_title'] = 'Блокнот';
        $items = $temp = $info = array();
        
        $wishlist = Wishlist::model()->findAll('user_id=:user', array(':user'=>Yii::app()->user->_id));
        foreach($wishlist as $wish) {
            $temp[] = $wish->product_id;
            $info[$wish->product_id]['original'] = $wish->original_product_id;
            $info[$wish->product_id]['count'] = $wish->count;
        }
        
        $criteria = new CDbCriteria();
        $criteria->addInCondition("t.id", $temp);
        $criteria->order = '(count > 0) desc, name';
        
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
        $this->render('index', array('data'=>$dataProvider, 'count' => $count, 'info' => $info));
    }
}

