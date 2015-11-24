<?php

class SJsTreeGroups extends CWidget
{
	public $id;
	public $data = array();
	public $options = array();
	protected $cs;
	public function init()
	{
            $styleUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('ext.yiiext.behaviors.trees.style'),
                false,
                -1,
                YII_DEBUG
            );

            //Yii::app()->getClientScript()->registerPackage('cookie');

            $this->cs = Yii::app()->getClientScript();
            $this->cs->registerScriptFile($styleUrl.'/jquery.jstree.js');
	}

	public function run()
	{
		echo CHtml::openTag('div', array(
	            'id'=>$this->id,
		));
		echo CHtml::openTag('ul');
		$this->createHtmlTree($this->data);
		echo CHtml::closeTag('ul');
		echo CHtml::closeTag('div');

		$options = CJavaScript::encode($this->options);
                
		$this->cs->registerScript('JsTreeScript', "
			$('#{$this->id}').jstree({$options});
		");
	}

	/**
	 * Create ul html tree from data array
	 * @param string $data
	 */
	private function createHtmlTree($data)
	{
            
            foreach($data as $n=>$node)
            {
                echo CHtml::openTag('li', array(
                    'id'=>$this->id.'Node_'.$node['id']
                ));
                echo CHtml::link(CHtml::encode($node->name), '/admin/group/edit/id/'.$node->group_id, array('target'=>'_blank'));
                $children = $node->children()->findAll();
                if ($children)
                {
                    echo CHtml::openTag('ul');
                    $this->createHtmlTree($children);
                    echo CHtml::closeTag('ul');
                }
                echo CHtml::closeTag('li');
            }
	}

}
