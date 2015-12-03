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
        echo CHtml::openTag('div', array(
           'class' => 'div_desc' 
        ));
            echo $data['description'];
        echo CHtml::closeTag('div');
    echo CHtml::closeTag('div');
    ?>
    <?php if(!(empty($products_by_makers))){ ?>
    <div class="bestoffer-spareparts-wrapper">
    <h2>Запасные части по спецпредложению</h2>
    <div class="grid-overlay" style="display: none"><div><span>Выполняется загрузка...</span><span class="loader"></span></div></div>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'seasonalsale-grid-products',
        'filter' => $products,
        'dataProvider' => $dataProvider,
        'loadingCssClass' => '',
        'beforeAjaxUpdate' => 'function(id, data) { '
            . 'var container = $(".bestoffer-spareparts-wrapper");'
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
            'prevPageLabel' => '<',
            'nextPageLabel' => '>'
        ),
        'columns' => array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, array("$data->path"))',
                'htmlOptions' => array('width' => '130px')
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
                'htmlOptions' => array('width' => '150px', 'align' => 'center'),
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
                        $available = '<div class="stock">' . Product::NO_IN_STOCK . '</div>';
                        if ($data->count > 0) {
                            $available = '<div class="stock in-stock">' . Product::IN_STOCK_SHORT . '</div>';
                        }
                        
                        if (!Yii::app()->user->isGuest || ($data->liquidity == 'D' && $data->count > 0)) {
                            $result = '<div class="cell">' .
                                    '<span>' . $price . '</span>'.$available.
                                    '</div>';
                        } else {
                            $result = '<div class="cell">' .
                                    '<a href="/site/login/" class="price_link">' . Yii::app()->params['textNoPrice'] . '</a>'.$available.
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
                    'width' => '150px'
                ),
                'filter' => array(
                    '1' => Product::IN_STOCK_SHORT,
                    '2' => Product::NO_IN_STOCK
                ),
                'type' => 'raw',
                'value' => function($data) {
                    $result = '<div class="cell"><div class="cart-form" elem="' . $data->id . '">';

                    if (empty($data->date_sale_off)) {

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
                }
            ),
            array(
                'header' => 'Производитель',
                'name' => 'product_maker_id',
                'type' => 'raw',
                'value' => 'ProductMaker::model()->findByPk($data->product_maker_id)->name',
                'htmlOptions' => array('width'=>'200px', 'align' => 'center'),
                'filter' => CHtml::listData($filter_data,'id','name'),
            ),
        ),
    ));
    ?> 
    </div>
    <?php } ?>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));



















































