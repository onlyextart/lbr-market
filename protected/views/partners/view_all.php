<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => Yii::app()->params['breadcrumbs'],
    'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
    'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
    'inactiveLinkTemplate' => '{label}',
));
?>
<div class="makers_wrapper">
    <?php
        foreach ($data_productmaker as $maker){
            $link='/product-maker'.$maker->path.'/';
            $this->renderPartial('_maker', array('link'=>$link, 'maker'=>$maker));
        }
        foreach ($data_equipmentmaker as $maker){
            $link='/equipment-maker'.$maker->path.'/';
            $this->renderPartial('_maker', array('link'=>$link, 'maker'=>$maker));
        }
    ?>
</div>