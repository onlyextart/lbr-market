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
<div class="subcategory-wrapper">
    <?php if(!empty($topText)): ?>
    <div class="text"><?php echo $topText?></div>
    <?php endif; ?>
    <h1><?php echo $title ?></h1>
    <div class="elements">
        <?php if(!empty($response)): ?>
        <?php echo $response; ?>
        <?php else: ?>
        <span class="empty">Подкатегории не найдены.</span>
        <?php endif; ?>
    </div>
    <?php if(!empty($hitProducts)): ?>
    <span class="hit-label">Хиты продаж</span>
    <div id="special-offer">
        <?php 
        foreach ($hitProducts as $product):
            $image = Product::model()->getImage($product->image, 'm');
        ?>
        <div class="one-banner-special">
           <h3><a target="_blank" href="<?php echo $product->path; ?>"><?php echo $product->name; ?></a></h3>
           <div class="spec-img-wrapper">
               <a target="_blank" href="<?php echo $product->path; ?>">
                   <img src="<?php echo $image ?>" alt="<?php echo $product->name; ?>">
               </a>
           </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if(!empty($bottomText)): ?>
    <div class="text">
        <div><?php echo $bottomText?></div>
        <span class="bottom-more">Подробнее...</span>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>


