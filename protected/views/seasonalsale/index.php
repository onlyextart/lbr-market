<div class="breadcrumbs">
    <?php
        /*$breadcrumbs['Тест'] = '/';
        $breadcrumbs[] = 'Производитель';
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;  */
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



















































