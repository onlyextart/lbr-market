<?php
class MapController extends Controller
{    
    public function addNodes(&$menuTreeArray, $items)
    {
        foreach($menuTreeArray as &$menuItem) {
            if(isset($menuItem['children'])) {
                if(array_key_exists($menuItem['id'], $items)) {
                    foreach($items[$menuItem['id']] as $productId) {
                        if($productId) {
                            $product = MapItems::getProductInformation($productId);
                            eval('$product[text] = MapItems::getMenuManageRow($product);');
                            $menuItem['children'][] = $product;
                        }
                    }
                }
                
                if(is_array($menuItem['children'])) {
                    $this->addNodes($menuItem['children'], $items);
                }
           }
        }
    }
    
    public function findLeaf(&$menuTreeArray, &$leafArray)
    {
        foreach($menuTreeArray as &$menuItem) {
            if(isset($menuItem[children])) {
                if(is_array($menuItem[children])) {
                    if(empty($menuItem[children]) && $menuItem['type'] == 0) {
                        $leafArray[] = $menuItem['id'];
                    }
                    $this->findLeaf($menuItem[children], $leafArray);
                }
           }
        }
    }
    
    public function actionIndex()
    {
        $siteMapFile = Yii::app()->getBaseUrl(true) . '/images/file.html';
        $siteMapHtml = file_get_contents($siteMapFile);
        $sitemapDate = filemtime('images/file.html');
        $this->render('index', array(
            'siteMapHtml' => $siteMapHtml,
            'sitemapDate' => $sitemapDate,
        ));
    }
    
    public function actionUpdateSitemapHtml()
    {
        $leafArray = array();
        $mapModel = MapItems::model()->findAll();
        $menuTreeArray = MapItems::getMenuTree();

        $this->findLeaf($menuTreeArray, $leafArray);
       
        $items = MapItems::getBanners(array_unique($leafArray));
        $this->addNodes($menuTreeArray, $items);
        
        $tree = $this->buildHtmlTree($menuTreeArray, 0);
        file_put_contents('images/file.html', $tree);
        $sitemapDate = filemtime('images/file.html');

        $this->redirect(array('map/index'));
    }
    
    public function buildHtmlTree($cats, $level) {
        if(is_array($cats)) {
            $listStyleType = 'disc';
            if($level > 0){
                $listStyleType = $level == 1 ? 'circle' : 'square';
            }
            
            $tree = '<ul style = "list-style-type: ' . $listStyleType . ';" class="level_' . $level . '">';
            foreach($cats as $cat) {
                $path = $cat['path'];
                
                if($path == '/index') { 
                    $path = '';
                } else {
                    $find = strpos($path, 'www');
                    if($find) {
                        $path = 'http://' . substr($path, $find); 
                    }
                }
                
                $display = $cat['published'] ? '' : 'display: none';
                $li = '<li style="' . $display . '"><a style="text-decoration: none" href="' . $path . '/">' . $cat['name'] . '</a>';
                
                //href="/http://www.lbr.nichost.ru/spareparts/?c=83/">Запчасти</a>'
                $find = strpos($li, '/http://');
                $tree .= $li;
                $tree .= $this->buildHtmlTree($cat['children'], $cat['level']);
                $tree .= '</li>';
            }
            $tree .= '</ul>';
        } else {
            return null;
        }
        
        return $tree;
    }
}
