<?php
/**
 * The Finder
 *  
 * @author cheshenkov
 */
class SearchController extends Controller
{
    public function actionIndex()
    {
        //Yii::app()->session['category'] = Yii::app()->session['maker'] = Yii::app()->session['model'] = null;
        //if(empty(Yii::app()->session['order'])) Yii::app()->session['order'] = 'asc';
        //if(empty(Yii::app()->session['sort'])) Yii::app()->session['sort'] = 'col';
        
        $query = trim($_GET['q']);
        $result = array();
        if($this->prepareSqlite()) {
            if (!empty($query)) {
                $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
                $result = Yii::app()->db->cache(1000, $dependency)->createCommand()
                    ->select('name, path')
                    ->from('product')
                    ->where('published = 1 and lower(name) like lower("%'.$query.'%")')
                    ->limit(7)
                    ->queryAll()
                ;
            }
        }
        
        $this->renderPartial('quickAjaxResult', array('data' =>$result));
    }
    
    public function actionShow($input = null)
    {
            //Yii::app()->session['category'] = Yii::app()->session['maker'] = Yii::app()->session['model'] = null;
            //if(empty(Yii::app()->session['order'])) Yii::app()->session['order'] = 'asc';
            //if(empty(Yii::app()->session['sort'])) Yii::app()->session['sort'] = 'col';
            //Yii::app()->session['search'] = true;
            Yii::app()->params['searchFlag'] = true;
            
            $criteria = new CDbCriteria();
            $criteria->order = 'name';
            
            if(!empty($input)){
                if($this->prepareSqlite()) {
                    if(!empty($input)) {
                        $criteria->condition = ' lower(name) like lower(:input)';
                        $criteria->params = array(':input' => '%' . $input . '%');
                    }
                }
                
                $dependecy = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
                $categoryProvider = new CActiveDataProvider(Category::model()->cache(1000, $dependecy, 2), array ( 
                //$categoryProvider = new CActiveDataProvider(Category::model(), array ( 
                    'criteria'=>$criteria,
                    'pagination' => array ( 
                        'pageSize' => 8, 
                    ) 
                ));

                $dependecy = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
                $modelProvider = new CActiveDataProvider(ModelLine::model()->cache(1000, $dependecy, 2), array ( 
                //$modelProvider = new CActiveDataProvider(ModelLine::model(), array ( 
                    'criteria'=>$criteria,
                    'pagination' => array ( 
                        'pageSize' => 8, 
                    ) 
                ));
                
                $criteria_bestoffer = new CDbCriteria();
                $criteria_bestoffer->condition = ' published=1 and lower(name) like lower(:input)';
                $criteria_bestoffer->params = array(':input' => '%' . $input . '%');
                $bestofferProvider = new CActiveDataProvider(BestOffer::model(), array ( 
                    'criteria'=>$criteria_bestoffer,
                    'pagination' => array ( 
                        'pageSize' => 8, 
                    ) 
                ));
                $productSize = 8;
                if(count($modelProvider->getData()) == 0 && count($categoryProvider->getData()) == 0){
                    $productSize = 15;
                }
                
                $crit = new CDbCriteria();
                $crit->order = 'count desc, name';
                $crit->condition = 'published = 1 and lower(name) like lower(:input)';
                $crit->params = array(':input' => '%' . $input . '%');
                
                //$dependecy = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
                $productProvider = new CActiveDataProvider(Product, array ( 
                //$productProvider = new CActiveDataProvider(Product::model(), array ( 
                    'criteria'=>$crit,
                    'pagination' => array ( 
                        'pageSize' => $productSize, 
                    ) 
                ));
            } else {
                $productProvider = $categoryProvider = $modelProvider = array();
            }
            
            // bradcrumbs
            Yii::app()->params['meta_title'] = 'Поиск';
            $breadcrumbs[] = 'Поиск';
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
            $this->render('index', array('input'=>$input, 'product'=>$productProvider, 'category'=>$categoryProvider, 'model'=>$modelProvider, 'bestoffer'=>$bestofferProvider));
    }
    
    public function prepareSqlite()
    {
        function lower($str){
            $return = str_replace(array(")", "(", "'", '"' ), "", $str);
            return mb_strtolower(strip_tags($return), "UTF-8");
        }
        
        Yii::app()->db->getPdoInstance()->sqliteCreateFunction('lower', 'lower', 1);
        return true;
    }
}
