<?php

class ChangesController extends Controller
{
    public function actionIndex($input = null)
    {
        //if(Yii::app()->user->checkAccess('readChanges'))
        //{
            $criteria = new CDbCriteria();
            $criteria->distinct = true;
            $criteria->group='user_id';
            $criteria->select = '*, max(date) as last_edit';
           
            $sort = new CSort();
            $sort->sortVar = 'sort';
            $sort->defaultOrder = 'last_edit DESC';
            $sort->attributes = array(
                'last_edit' => array(
                    'asc' => 'last_edit ASC',
                    'desc' => 'last_edit DESC',
                    'default' => 'asc',
                )
            );
            
            $dataProvider = new CActiveDataProvider('Changes', 
                array(
                    'criteria'=>$criteria,
                    'sort'=>$sort,
                    'pagination'=>array(
                        'pageSize'=>'8'
                    )
                )
            );
            $this->render('changes', array('data'=>$dataProvider));
        /*} else {
            throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
        }*/
    }
}
