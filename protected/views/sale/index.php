<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    )); 
?>

<div class="bestoffer-wrapper">
    <h1>Распродажа</h1><img width="30" height="30" class="spec-label" src="/images/sale-label.png"> 
    <div class="bestoffer-filters">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'sale-product-form',
        'action'=>'',
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
        'enableClientValidation' => true,        
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange' => true,
            'afterValidate'=>'js:function( form, data, hasError ) 
                {     
                  if( hasError ){
                    return false;
                  }
                  else{
                    return true;
                  }
                }'
         ),
     ));

    echo "<div class='block'>";
        echo $form->labelEx($additional_filter, 'category', array('class'=>'label'));
        echo $form->dropDownList($additional_filter, 'category', $products->filter_category, array('empty'=>'','class'=>'filter-select'));
    echo "</div>";
    echo "<div class='block'>";
        echo $form->labelEx($additional_filter, 'maker', array('class'=>'label'));
        echo $form->dropDownList($additional_filter, 'maker', $products->filter_maker, array('empty'=>'','class'=>'filter-select'));
    echo "</div>";
    echo "<div class='button-filter'>";
        echo CHtml::button('Выбрать', array('id' => 'filter', 'class'=>'buttonform'));
    echo "</div>";
    echo "<div class='clearfix'></div>";
    
    
    $this->endWidget();
    ?>
    </div>
    <div class="grid-overlay" style="display: none"><div><span>Выполняется загрузка...</span><span class="loader"></span></div></div>
    <div class="spareparts-wrapper">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'sale-grid-products',
        'filter' => $products,
        'dataProvider' => $dataProvider,
        'ajaxUrl' => Yii::app()->createUrl($this->route, array( 'maker' => $additional_filter->maker, 'category'=>$additional_filter->category ) ),
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
                'name' => '',
                //'filter' => $groupFilter,
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
                'header' => 'Производитель',
                'name' => 'product_maker_id',
                'filter' => $makerFilter,
                'type' => 'raw',
                'value' => '(!empty($data->product_maker_id)) ? ProductMaker::model()->findByPk($data->product_maker_id)->name : ""',
                'htmlOptions' => array('width'=>'200px', 'align' => 'center'),
            ),
            array(
                'header' => '',
                'filter' => false,
                'htmlOptions' => array('width' => '150px', 'align' => 'center'),
                'type' => 'raw',
                'value' => function($data) {
                    $price = Price::model()->getPrice($data->id);
                    if (empty($price)){
                        $price = '<span class="no-price-label">' . Yii::app()->params['textNoPrice'] . '</span>';}
                    $available = '<div class="stock in-stock">' . Product::IN_STOCK_SHORT . '</div>';
                    $result = '<div class="cell">' .
                                    '<span>' . $price . '</span>'.$available.
                                    '</div>';
                    return $result;
                }
            ),
            array(
                'header' => '',
                'name' => 'count',
                'htmlOptions' => array(
                    'width' => '150px'
                ),
                'filter' => false,
                'type' => 'raw',
                'value' => function($data) {
                    $result = '<div class="cell"><div class="cart-form" elem="' . $data->id . '">';

                    if (empty($data->date_sale_off)) {
                        $intent = "\"yaCounter30254519.reachGoal('addtocard'); ga('send','event','action','addtocard'); return true;\" ";
                        if (Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop))) {
                           $result .= '<input type="number" value="1" min="1" pattern="[0-9]*" name="quantity" maxlength="4" size="7" autocomplete="off" product="1" class="cart-quantity">
                                                <input onclick=' . $intent . ' type="submit"  title="Добавить в корзину" value="" class="small-cart-button">'
                            ;

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
        ),
    ));
    ?>
    </div>
<!--    <div class="elements">
        <h1>Распродажа</h1><img width="30" height="30" class="spec-label" src="/images/sale-label.png">
        <?php //if(count($data->getData())): 
//            $this->widget('zii.widgets.CListView', array(
//                'dataProvider' => $data,
//                'cssFile'      => false,
//                'itemView'     => '_view',
//                'ajaxUpdate'   => false,
//                'emptyText'    => 'На данный момент нет распродаж.',
//                'itemsTagName' => 'div',
//                'template'     => '{sorter}{items}{pager}',
//                'htmlOptions'  => array('class'=>'best-spareparts-wrapper'),
//                'sortableAttributes' => array('name'=>'Названию'),//, 'count'=>'Наличию'),
//                'sorterHeader' => 'Сортировать по:',
//                'pager'        => array(
//                    'header'   => false,
//                    'class' => 'LinkPager', 
//                    'firstPageLabel' => 'В начало',
//                    'prevPageLabel'  => 'Назад',
//                    'nextPageLabel'  => 'Вперёд',
//                    'lastPageLabel'  => 'В конец',
//                    'cssFile'        => false
//                )
//            )); 
        ?>
        <?php //else: ?>
              <div class="empty">На данный момент нет распродаж.</div>
        <?php //endif; ?>
    </div>-->
</div>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));
?>
<script>
    $("#filter").click(function() {
        $('#sale-product-form').submit();
    });
</script>
