<div class="breadcrumbs">
    <?php
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
    foreach($data as $one_sale){
        $img[]=$host.$one_sale['img'];
        $link[]=$host.'/seasonalsale/index/id/'.$one_sale['id'];
        $alt[]=$one_sale['name'];
    }
    
    echo CHtml::openTag('div', array(
      'class' => 'block_info_seasonalsale_all'
    ));
        for($i=0;$i<count($img);$i++){
            echo CHtml::openTag('div', array(
                'class' => 'div_logo'
            ));
                echo CHtml::link(CHtml::image($img[$i],$alt[$i],array()),$link[$i]);
                //echo CHtml::link(CHtml::image($img[$i],'',array()),'#');
            echo CHtml::closeTag('div');
        }
       
    echo CHtml::closeTag('div');



















































