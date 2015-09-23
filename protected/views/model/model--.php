<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));        
?>
<div class="model-wrapper">
    <h1><?php echo $title?></h1>
    <?php if(!empty($hitProducts)): ?>
    <span class="hit-label-main">Рекомендуем для "<?php echo $title?>"</span>
    <div id="special-offer">
        <?php foreach ($hitProducts as $product): ?>
        <div class="one-banner-special">
           <h3><a target="_blank" href="<?php echo $product->path; ?>"><?php echo $product->name; ?></a></h3>
           <div class="spec-img-wrapper">
               <a target="_blank" href="<?php echo $product->path; ?>">
                   <img src="<?php echo Product::model()->getImage($product->image, 'm'); ?>" alt="<?php echo $product->name; ?>">
               </a>
           </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($result)): ?>
    <h2>Запасные части для <?php echo $title?></h2>
    <span class="spareparts-order">
        Сортировать по:
        <!--a href="/model/show/id/<?php echo $model->id?>/sort/name/order/<?php echo (Yii::app()->params['sortCol'] == 'name' && Yii::app()->params['sortOrder'] == 'asc')?'desc':'asc' ?>/" class="<?php if(Yii::app()->params['sortCol'] == 'name') echo Yii::app()->params['sortOrder'] ?>">Названию</a-->
        <a href="<?php echo $url ?>sort/name/order/<?php echo (Yii::app()->params['sortCol'] == 'name' && Yii::app()->params['sortOrder'] == 'asc')?'desc':'asc' ?>/" class="<?php if(Yii::app()->params['sortCol'] == 'name') echo Yii::app()->params['sortOrder'] ?>">Названию</a>
        <a href="<?php echo $url ?>sort/col/order/<?php echo (Yii::app()->params['sortCol'] == 'col' && Yii::app()->params['sortOrder'] == 'asc')?'desc':'asc' ?>/" class="<?php if(Yii::app()->params['sortCol'] == 'col') echo Yii::app()->params['sortOrder'] ?>">Наличию</a>
        <a href="<?php echo $url ?>sort/category/order/<?php echo (Yii::app()->params['sortCol'] == 'category' && Yii::app()->params['sortOrder'] == 'asc')?'desc':'asc' ?>/" class="<?php if(Yii::app()->params['sortCol'] == 'category') echo Yii::app()->params['sortOrder'] ?>">Категории</a>
    </span>
    <div style='clear: both'></div>
    <?php echo $result; ?>
    <?php endif; ?>    
</div>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));
?>
<script>
    $(function() {
        $(".left-menu-wrapper").css('display','block');
    });
</script>
