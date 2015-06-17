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
    <h1><?php echo $title ?></h1>
    <div class="elements">
        <?php if(!empty($response)): ?>
        <?php echo $response; ?>
        <?php else: ?>
        <span class="empty">Подкатегории не найдены.</span>
        <?php endif; ?>
    </div>
    <?php if(!empty($hitProducts)): ?>
    <h2>Хиты продаж</h2>
    <div id="special-offer">
        <?php foreach ($hitProducts as $product): ?>
        <div class="one-banner-special">
           <h3><a target="_blank" href="<?php echo $product->path; ?>"><?php echo $product->name; ?></a></h3>
           <div class="spec-img-wrapper">
               <a target="_blank" href="<?php echo $product->path; ?>">
                   <img src="http://api.lbr.ru/images/shop/spareparts/<?php echo $product->image ?>" alt="">
               </a>
           </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>


