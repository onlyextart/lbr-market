<?php

/**
 * This is the model class for table "menu_items".
 *
 * The followings are the available columns in table 'menu_items':
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $icon
 * @property integer $lft
 * @property integer $rt
 * @property string $meta_description
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $seo_text
 * @property integer $published
 * @property integer $group_id
 * @property integer $type
 * @property integer $level
 * @property integer $root
 * @property integer $header
 * @property string $path
 *
 * The followings are the available model relations:
 * @property MenuGroups $group
 * @property MenuItemsContent[] $menuItemsContents
 */
class MapItems extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuItems the static model class
	 */
    
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}
        
        /*
         * Метод для создания дерева меню.
         */
        static function getMenuTree()
        {
            $elementKatalog = Yii::app()->db->createCommand('SELECT lft, rgt FROM category WHERE name LIKE "Каталог%"')->queryRow();

            //$categories = Yii::app()->db->createCommand('SELECT id, path, level, name, published FROM menu_items WHERE lft NOT BETWEEN 642 AND 1249 ORDER BY lft')->queryAll();
            $categories = Yii::app()->db->createCommand('SELECT id, path, level, name, published FROM category WHERE lft NOT BETWEEN ' . $elementKatalog['lft'] . ' AND ' . $elementKatalog['rgt'] . ' ORDER BY lft')->queryAll();
            return(self::toHierarchy($categories, 'getMenuManageRow'));
        }
        
        public static function getBanners($leafArray) 
        {   
            $pageId = array();
            $items = array();
            
            foreach($leafArray as $leaf){
                $pageId[$leaf] = Yii::app()->db->createCommand()
                    ->select('c.page_id')
                    ->from('menu_items i, menu_items_content c')
                    ->where('i.id = c.item_id and i.id = ' . $leaf)
                    ->queryAll()
                ;
            }
            
            foreach($pageId as $key => $page) {
                foreach($page as $value) {
                    $items[$key][] = Yii::app()->db->createCommand()
                        ->select('menu_item_id')
                        ->from('banner_links')
                        ->where('banner_id = ' . $value['page_id'])
                        ->queryScalar()
                    ;
                }
            }
           
            return $items;
        }
        
        public static function getProductInformation($id) 
        {
            $product = Yii::app()->db->createCommand()
                ->select('id, name, path, level, published')
                ->from('category')
                ->where('id=:id', array(':id' => $id))
                ->queryRow()
            ;
            
            return $product;
        }
        
        private static function toHierarchy($collection, $rowBuildFunction, $checkBoxNameAttr = null, $checkStatus = null, $activeItemType=null)
        {
            // Trees mapped
            $trees = array();
            $l = 0;

            if (count($collection) > 0) {
                // Node Stack. Used to help building the hierarchy
                $stack = array();
                foreach ($collection as $node) {
                    $item = $node;
                  
                    $item['expanded'] = ( isset($_REQUEST['OpenItems'] ) && in_array( $node[id], $_REQUEST['OpenItems'] ) ) ? true : false;
                    eval('$item[text] = self::' . $rowBuildFunction . '($item, $checkBoxNameAttr, $checkStatus, $activeItemType);');
                    $item['children'] = array();
                    
                    // Number of stack items
                    $l = count($stack);

                    // Check if we're dealing with different levels
                    while($l > 0 && $stack[$l - 1]['level'] >= $item['level']) {
                            array_pop($stack);
                            $l--;
                    }

                    // Stack is empty (we are inspecting the root)
                    if ($l == 0) {
                            // Assigning the root node
                            $i = count($trees);
                            $trees[$i] = $item;
                            $stack[] = & $trees[$i];
                    } else {
                            // Add node to parent
                            $i = count($stack[$l - 1]['children']);
                            $stack[$l - 1]['children'][$i] = $item;
                            
                   
                            $stack[] = &$stack[$l - 1]['children'][$i];
                    }
                }
            }
            return $trees;
        }
        
        public static function getMenuManageRow($item){
            $item[level] == 1 ? $linkUrl = '/admin/menu/updateMenu' : $linkUrl = '/admin/menu/updateMenuItem';
            $rowHtml = $item['path'];
            
            return $rowHtml;
        }
}