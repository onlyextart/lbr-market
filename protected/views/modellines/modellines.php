<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));        
?>
<div class="modellines-wrapper">
    <?php if(!empty($topText)): ?>
    <div class="text"><?php echo $topText?></div>
    <?php endif; ?>
    <?php if(!empty($title)): ?>
    <h1><?php echo $title ?></h1>
    <?php endif; ?>
    <div class="elements">
        <?php if(!empty($response)): ?>
        <?php echo $response; ?>
        <?php else: ?>
        <span class="empty">Нет товаров.</span>
        <?php endif; ?>
    </div>
    <?php if(!empty($bottomText)): ?>
    <div class="text">
        <div><?php echo $bottomText?></div>
        <span class="bottom-more">Подробнее...</span>
    </div>
    <?php endif; ?>
</div>

