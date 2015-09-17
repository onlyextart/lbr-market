<?php
/*$this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => Yii::app()->params['breadcrumbs'],
    'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . Yii::app()->getBaseUrl(true) . '/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
    'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . Yii::app()->getBaseUrl(true) . '{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
    'inactiveLinkTemplate' => '{label}',
));*/
?>
<div class="model-wrapper">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProvider,
        'filter'  => $model,
        'columns' => array(
            'name'
        ),
    ));
    ?> 
</div>
