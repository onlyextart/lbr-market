<div class="breadcrumbs">
    <?php
        Yii::app()->params['meta_title']=$data->name;
    
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => Yii::app()->params['breadcrumbs'],
            'activeLinkTemplate' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="{url}">{label}</a></span>',
            'inactiveLinkTemplate' => '{label}',
            'homeLink' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="/">Главная</a></span>',
            'tagName' => 'span',
            'htmlOptions' => array(
                'xmlns:v' => 'http://rdf.data-vocabulary.org/#',
            ),
        ));
    ?>
</div>
<?php
    $host = Yii::app()->getRequest()->getHostInfo();
    $logo=$host.$data['logo'];
    echo CHtml::openTag('div', array(
      'class' => 'block_info'
    ));
        echo CHtml::openTag('div', array(
            'class' => 'div_logo'
        ));
            echo CHtml::image($logo,$data['name'],array());
        echo CHtml::closeTag('div');
        echo CHtml::openTag('div', array(
           'class' => 'div_desc' 
        ));
            echo $data['description'];
        echo CHtml::closeTag('div');
    echo CHtml::closeTag('div');