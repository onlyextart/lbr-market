<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));        
    
    $host = Yii::app()->getRequest()->getHostInfo();
    $img=$host.$data['img'];
    echo CHtml::openTag('div', array(
      'class' => 'block_info_seasonalsale'
    ));
//        echo CHtml::openTag('div', array(
//            'class' => 'div_logo'
//        ));
//            echo CHtml::image($img,'',array());
//        echo CHtml::closeTag('div');
        echo CHtml::openTag('div', array(
           'class' => 'div_desc' 
        ));
            echo $data['description'];
        echo CHtml::closeTag('div');
    echo CHtml::closeTag('div');



















































