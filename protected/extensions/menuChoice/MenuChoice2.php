<?php
class MenuChoice extends CWidget
{
    public function init()
    {
        $types = array();
        
        $model = new Category;
        $mainRoot = $model->roots()->find();
        if(!empty($mainRoot)) {
            $roots = $mainRoot->children()->findAll('published=:published', array(':published' => true));
            foreach($roots as $root) {
                $title = $root->name;
                $types[$root->id]['name'] = trim($title);
                $types[$root->id]['id'] = $root->id;
                $types[$root->id]['path'] = $root->path;
            }
        }        
        ksort($types);
        
        $this->render('index',array('types'=>$types));
    }
}
