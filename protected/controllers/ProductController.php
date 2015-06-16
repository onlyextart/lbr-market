<?php
class ProductController extends Controller
{
    public function actionIndex($id)
    {
        // product page
        
        /*
        $model = new ModelLine;
        $data = $model->getPageByPath($page);
        */
        /////////////////////////////////////////////////////////////
        
        $data = Product::model()->findByPk($id);
        if(!$data)
            throw new CHttpException(404, 'Товар не найден');
        
        $maker = ProductMaker::model()->findByPk($data->product_maker_id);
              
        Yii::app()->params['meta_title'] = $data->name;
        
        // bradcrumbs
        if(!empty(Yii::app()->request->urlReferrer) && !empty(Yii::app()->session['model'])) {
            //preg_match_all('~(.*)/model/show/id/(\d*)~i', Yii::app()->request->urlReferrer, $result);
            //$modellineId = (int)$result[2][0];
            
            $modelline = ModelLine::model()->findByPk(Yii::app()->session['model']);
        
            $category = Category::model()->findByPk($modelline->category_id);
            
            $categoryParent = $category->parent()->find();
            preg_match('/\d{2,}\./i', $categoryParent->name, $result);
            Yii::app()->params['currentType'] = $categoryParent->id;
            $label = trim(substr($categoryParent->name, strlen($result[0])));
            //$breadcrumbs[$label] = '/subcategory/index/id/'.$categoryParent->id;
            $breadcrumbs[$label] = '/catalog'.$categoryParent->path.'/';
            $breadcrumbs[$category->name] = '/catalog'.$category->path;
            $parent = $modelline->parent()->find();
            $breadcrumbs[$parent->name] = '/modelline/index/id/'.$parent->id;
        
            $breadcrumbs[" $modelline->name"] = Yii::app()->request->urlReferrer;
                    
            //var_dump($modelline);
            //exit;
            
            //$breadcrumbs['l'] = Yii::app()->request->urlReferrer;
            
            /*$category = Category::model()->findByPk(Yii::app()->session['category']);
            preg_match('/\d{2,}\./i', $category->name, $result);
            $label = trim(substr($category->name, strlen($result[0])));
            $breadcrumbs[$label] = '/subcategory/index/id/'.$category->id;
            
            $crt = new CDbCriteria();
            $crt->compare('product_id', $id, true);
            $crt->compare('model_line_id', Yii::app()->session['model'], true);
            */
            //$crt->join = 'LEFT JOIN model_line ON model_line.id=model_line_id';
            //$crt->addCondition("model_line.category_id=$category->id");
            //$crt->condition = 'model_line.category_id = '.$category->id;
            /*$crt->with = array(
                'product_in_model_line' => array('joinType'=>'JOIN'),
            );
            $crt->addCondition('t.id = product_in_model_line.model_line_id');
            */
            /*
            $criteria->with = array(
                    'owners'=>array(
                            'order'=>'owners.name ASC, owners.surname ASC',
                            'on'=>'owners.name = "John"',
                            'joinType'=>'INNER JOIN',
                    ),
                    'breed',
            );*/

            //$result = ProductInModelLine::model()->findAll($crt);
            //echo '<pre>';
            //echo Yii::app()->session['model'].'<br>';
            //var_dump($result);
            //exit;
            
        } else if(!empty(Yii::app()->params['searchFlag'])) {
            $url = '/search/show/';
            if(strpos(Yii::app()->request->urlReferrer, $url))
               $breadcrumbs['Поиск'] = Yii::app()->request->urlReferrer;
            else $breadcrumbs['Поиск'] = $url; //Yii::app()->request->urlReferrer;
        }
        
        $relatedProducts = $this->showRelatedProducts($id);
        $mainProductPrice = $this->getPrice($id);
        $analogProducts = $this->showAnalog($id, $filial);

        $breadcrumbs[] = $data->name;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;

        $this->render('index', array('data' => $data, 'price'=>$mainProductPrice[0], 'update'=>$mainProductPrice[1], 'maker' => $maker, 'relatedProducts' => $relatedProducts, 'analogProducts'=>$analogProducts[0], 'drafts'=>$analogProducts[1]));
    }
    
    public function getPrice($productId)
    {
        if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
            $user = User::model()->findByPk(Yii::app()->user->_id);
            if(!empty($user->filial)) {
                $update = $priceLabel = '';
                
                $price = PriceInFilial::model()->findByAttributes(array('product_id'=>$productId, 'filial_id'=>$user->filial));
                if(!empty($price)) {
                    $currency = Currency::model()->findByPk($price->currency_code)->symbol;
                    $priceLabel = $price->price.' '.$currency;
                    if(!empty($price->update_time)) $update = date('d.m.Y H:i', strtotime($price->update_time));
                }
                
                return array($priceLabel, $update);
            } else return null;
        }
    }
    
    public function showAnalog($id)
    {
        $filial = null;
        $drafts = array();
        if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
            $user = User::model()->findByPk(Yii::app()->user->_id);
            if(!empty($user->filial)) {
                $filial = $user->filial;
            }
        } // else ---- if user not logged
        $temp = Yii::app()->db->createCommand()
            ->selectDistinct('analog_product_id')
            ->from('analog')
            ->where('product_id=:id', array(':id'=>$id))
            ->queryColumn()
        ;
        
        $criteria = new CDbCriteria;
        $criteria->addInCondition('t.id', $temp);
        //if(!empty($filial)) $criteria->addCondition('priceInFilial.filial_id = '.$filial);
        $analogProducts = Product::model()->with('priceInFilial')->findAll($criteria);
        
        // drafts for analog
        if(Yii::app()->params['showDrafts']){
            foreach($temp as $analogId) {            
                $allDrafts = ProductInDraft::model()->findAllByAttributes(array('product_id'=>$analogId));
                if(!empty($allDrafts)) {
                    foreach($allDrafts as $one) {
                        $draft = Draft::model()->findByPk($one['draft_id']);
                        $drafts[$analogId][] = '<a target="_blank" href="/draft/index/id/'.$draft->id.'">Чертеж "'.$draft->name.'"</a>';
                    }
                }
            }
        }
        
        //$array = array('date' => $curDate, 'minUpdate' => $updateTimeInMilliseconds, 'end'=>$endDate);
        //echo json_encode($array);
        return array($analogProducts, $drafts);
    }
    
    public function showRelatedProducts($id)
    {
        $temp = Yii::app()->db->createCommand()
            ->selectDistinct('related_product_id')
            ->from('related_product')
            ->where('product_id=:id', array(':id'=>$id))
            ->queryColumn()
        ;
        
        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', $temp);
        $criteria->addCondition('image IS NOT NULL');
        $relatedProducts = Product::model()->findAll($criteria);
        
        return $relatedProducts;
    }

    public function actionMark() {
        /*-------------------*/
        /*$folder = $_SERVER['DOCUMENT_ROOT'] . '/../api/images/shop/spareparts/*.{jpg,jpeg,JPG,JPEG,png,gif}';
        $files = glob($folder, GLOB_BRACE);
        $waterMarkPath = $_SERVER['DOCUMENT_ROOT'] . '/images/watermark.png';
        foreach($files as $file) {
           $imgPath = $file;
           Yii::app()->ih->load($imgPath);
           Yii::app()->ih->watermark_center_full($waterMarkPath);
           Yii::app()->ih->save($imgPath);
        }*/
        /*-------------------*/
    }
}
