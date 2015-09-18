<?php
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => Yii::app()->params['breadcrumbs'],
    'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . Yii::app()->getBaseUrl(true) . '/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
    'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . Yii::app()->getBaseUrl(true) . '{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
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
    <h2>Запасные части для <?php echo $title?></h2>
    <div class="grid-overlay" style="display: none"><div><span>Выполняется загрузка...</span><span class="loader"></span></div></div>
    <div class="spareparts-wrapper">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'model-grid-products',
        'filter' => $products,
        'dataProvider' => $dataProvider,
        'loadingCssClass' => '',
        'beforeAjaxUpdate' => 'function(id, data) { '
            . 'var container = $(".spareparts-wrapper");'
            . 'var height = container.height()+22;'
            . 'var width = container.width();'
            . 'var offset = container.offset();'
            . 'var element = $(".grid-overlay");'
            . 'element.height(height);'
            . ' var gridOverlayMargin = height/2 - 50; if(gridOverlayMargin < 20) gridOverlayMargin = 20;'
            . '$(".grid-overlay > div").css({margin: gridOverlayMargin});'
            . 'element.width(width);'
            . 'element.css({top: (offset.top - 10), left: offset.left});'
            . 'element.show();'
        . '}',
        'afterAjaxUpdate'=>'function(id, data){ '
            . '$("a.thumbnail").fancybox();'
            . '$(".price-link").easyTooltip({content:"Авторизуйтесь, чтобы узнать цену"});'
            . '$(".grid-overlay").hide();'
        . '}',         
        'template' => '{summary}{items}{pager}',
        'summaryText' => 'Элементы {start} — {end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
            'header' => false,
        ),
        'columns' => array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, array("$data->path"))',
                'htmlOptions' => array('width' => '20%')
            ),
            array(
                'header' => '',
                'name' => 'image',
                'filter' => false,
                'type' => 'raw',
                'value' =>
                'CHtml::link('
                . 'CHtml::image(Product::model()->getImage($data->image, "s"), "Логотип", array("width"=>"100px")), '
                . 'Product::model()->getImage($data->image), '
                . 'array("target"=>"_blank", "class"=>"thumbnail")'
                . ')',
                'htmlOptions' => array('width' => '100px')
            ),
            array(
                'header' => '',
                'filter' => false,
                'htmlOptions' => array('width' => '15%', 'align' => 'center'),
                'type' => 'raw',
                'value' => function($data) {
                    if (!Yii::app()->user->isGuest || ($data->liquidity == 'D' && $data->count > 0)) {
                        if (Yii::app()->params['showPrices'] || (empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin'])) {
                            $price = Price::model()->getPrice($data->id);
                            if (empty($price))
                                $price = '<span class="no-price-label">' . Yii::app()->params['textNoPrice'] . '</span>';
                        } else
                            $price = Yii::app()->params['textHidePrice'];
                    }

                    if (empty($data->date_sale_off)) {
                        if (!Yii::app()->user->isGuest || ($data->liquidity == 'D' && $data->count > 0)) {
                            $result = '<div class="cell">' .
                                    '<span>' . $price . '</span>' .
                                    '</div>';
                        } else {
                            $result = '<div class="cell price-link">' .
                                    '<a href="/site/login/">' . Yii::app()->params['textNoPrice'] . '</a>' .
                                    '</div>';
                        }
                    } else {
                        $countAnalogs = Analog::model()->count("product_id=:id", array("id" => $data->id));
                        if ($countAnalogs) {
                            $result = '<div class="cell">' .
                                    '<a class="prodInfo" target="_blank" href="' . $data->path . '">аналоги</a>' .
                                    '</div>';
                        }
                    }

                    return $result;
                }
            ),
            array(
                'header' => 'В наличии',
                'name' => 'count',
                'htmlOptions' => array(
                    'width' => '20%'
                ),
                //'value'  => '($data->count > 0 ? Product::IN_STOCK_SHORT : Product::NO_IN_STOCK)',
                'type' => 'raw',
                'value' => function($data) {
                    $result = '<div class="cell"><div class="cart-form" elem="' . $data->id . '">';

                    if (empty($data->date_sale_off)) {
                        if ($data->count > 0) {
                            $result .= '<span class="stock in-stock">' . Product::IN_STOCK_SHORT . '</span>';
                        } else {
                            $result .= '<span class="stock">' . Product::NO_IN_STOCK . '</span>';
                        }

                        $intent = "\"yaCounter30254519.reachGoal('addtocard'); ga('send','event','action','addtocard'); return true;\" ";
                        if (Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop))) {
                            //if($price) {
                            $result .= '<input type="number" value="1" min="1" pattern="[0-9]*" name="quantity" maxlength="4" size="7" autocomplete="off" product="1" class="cart-quantity">
                                                <input onclick=' . $intent . ' type="submit"  title="Добавить в корзину" value="" class="small-cart-button">'
                            ;
                            //}

                            $result .= '<button class="wish-small" title="Добавить в блокнот">
                                                       <span class="wish-icon"></span>
                                                    </button>'
                            ;
                        }
                    } else {
                        $result .= '<span>' . Yii::app()->params['textSaleOff'] . '</span>';
                    }

                    $result .= '</div></div>';

                    return $result;
                },
                'filter' => array(
                    '1' => Product::IN_STOCK_SHORT,
                    '2' => Product::NO_IN_STOCK
                )
            ),
            array(
                'header' => 'Группа',
                'name' => 'product_group_id',
                'type' => 'raw',
                'value' => 'ProductGroup::model()->findByPk($data->product_group_id)->name',
                //'htmlOptions' => array('width'=>'15%', 'padding-right'=>'5px'),
                'htmlOptions' => array('align' => 'center'),
                'filter' => $filter,
            ),
        ),
    ));
    ?> 
    </div>
</div>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));
?>
