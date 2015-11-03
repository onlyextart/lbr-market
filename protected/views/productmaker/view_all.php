<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => Yii::app()->params['breadcrumbs'],
    'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
    'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
    'inactiveLinkTemplate' => '{label}',
));
?>
<div class="productmakers_wrapper">
    <?php
        $path = Yii::getPathOfAlias('webroot');
        foreach ($data as $maker){
            if (file_exists($path . $maker->logo)){
                echo CHtml::openTag('div', array('class' => 'maker_wrapper'));
                    echo CHtml::openTag('div', array('class' => 'maker_inner_wrapper'));
                        $link='/product-maker'.$maker->path.'/';
                        echo CHtml::openTag('a', array('href' => $link));
                            echo CHtml::image($maker->logo, $maker->name, array());
                        echo CHtml::closeTag('a');
                    echo CHtml::closeTag('div');
                echo CHtml::closeTag('div');  
            }
            //echo $maker->logo.'<br>';
        }
    ?>
</div>