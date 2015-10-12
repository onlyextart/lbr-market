<?php 
    $flag = false;
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));
?>
<!--<div class="search-form">
    <form id="form_full_search" method="post">
        <div class="search-metod">
            <h1>ПОИСК ПО РАЗДЕЛАМ</h1>
        </div>
        <div class="query-field">
                <input id="full-search" type="text" name="q" value="<?php echo htmlspecialchars($input, ENT_QUOTES, "UTF-8") ?>" placeholder="Найти" autocomplete="off"/>
                <ul class="full-quick-result"></ul>
                <input class="btn full-search-button" type="button" value="Найти">
        </div>
    </form>
</div>-->
<div class="search-text">Вы искали: "<span><?php echo $input;?></span>"</div>
<?php if(!empty($input)): ?>
<div class="search-result">
    <?php if(count($product->getData())): 
        $flag = true; 
    ?>
    <h2>НАЙДЕНО В <span>ЗАПЧАСТЯХ</span></h2>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$product,
        'itemView'=>'_product', // представление для одной записи
        'ajaxUpdate'=>false, // отключаем ajax поведение
        'emptyText'=>'По Вашему запросу ничего не найдено.',
        'template'=>'{items} {pager}',
        //'summaryText'=>'Показано {start} — {end} из {count}',
        'sorterHeader'=>'',
        'itemsTagName'=>'div',
        'sortableAttributes'=>array('name'),
        'pager' => array(
            'class' => 'LinkPager',
            'maxButtonCount' => '5',
            'header'   => false,
            'firstPageLabel' => '<<',
            'prevPageLabel'  => '<',
            'nextPageLabel'  => '>',
            'lastPageLabel'  => '>>',
            'cssFile'        => false
        )
    )); ?>
    <?php endif; ?>
    <?php if(count($category->getData())): 
        $flag = true; 
    ?>
    <h2>НАЙДЕНО В <span>ТИПАХ ТЕХНИКИ</span>:</h2>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$category,
        'itemView'=>'_category', // представление для одной записи
        'ajaxUpdate'=>false, // отключаем ajax поведение
        'emptyText'=>'По Вашему запросу ничего не найдено.',
        'template'=>'{items} {pager}',
        //'summaryText'=>'Показано {start} — {end} из {count}',
        'sorterHeader'=>'',
        'itemsTagName'=>'div',
        'sortableAttributes'=>array('name'),
        'pager' => array(
            'class' => 'LinkPager',
            'maxButtonCount' => '5',
            'header'   => false,
            'firstPageLabel' => '<<',
            'prevPageLabel'  => '<',
            'nextPageLabel'  => '>',
            'lastPageLabel'  => '>>',
            'cssFile'        => false
        )
    ));
    ?>
    <?php endif; ?>
    <?php if(count($model->getData())): 
        $flag = true;
    ?>
    <h2>НАЙДЕНО В <span>МОДЕЛЬНЫХ РЯДАХ</span>:</h2>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$model,
        'itemView'=>'_model', // представление для одной записи
        'ajaxUpdate'=>false, // отключаем ajax поведение
        'emptyText'=>'По Вашему запросу ничего не найдено.',
        'template'=>'{items} {pager}',
        //'summaryText'=>'Показано {start} — {end} из {count}',
        'sorterHeader'=>'',
        'itemsTagName'=>'div',
        'sortableAttributes'=>array('name'),
        'pager' => array(
            'class' => 'LinkPager',
            'maxButtonCount' => '5',
            'header'   => false,
            'firstPageLabel' => '<<',
            'prevPageLabel'  => '<',
            'nextPageLabel'  => '>',
            'lastPageLabel'  => '>>',
            'cssFile'        => false
        )
    ));
    ?>
    <?php endif; ?>
    <?php if(count($bestoffer->getData())): 
        $flag = true; 
    ?>
    <h2>НАЙДЕНО В <span>СПЕЦПРЕДЛОЖЕНИЯХ</span></h2>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$bestoffer,
        'itemView'=>'_bestoffer', // представление для одной записи
        'ajaxUpdate'=>false, // отключаем ajax поведение
        'emptyText'=>'По Вашему запросу ничего не найдено.',
        'template'=>'{items} {pager}',
        //'summaryText'=>'Показано {start} — {end} из {count}',
        'sorterHeader'=>'',
        'itemsTagName'=>'div',
        'sortableAttributes'=>array('name'),
        'pager' => array(
            'class' => 'LinkPager',
            'maxButtonCount' => '5',
            'header'   => false,
            'firstPageLabel' => '<<',
            'prevPageLabel'  => '<',
            'nextPageLabel'  => '>',
            'lastPageLabel'  => '>>',
            'cssFile'        => false
        )
    )); ?>
    <?php endif; ?>
    <?php if(count($brand->getData())): 
        $flag = true; 
    ?>
    <h2>НАЙДЕНО В <span>ПРОИЗВОДИТЕЛЯХ</span></h2>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$brand,
        'itemView'=>'_brand', // представление для одной записи
        'ajaxUpdate'=>false, // отключаем ajax поведение
        'emptyText'=>'По Вашему запросу ничего не найдено.',
        'template'=>'{items} {pager}',
        //'summaryText'=>'Показано {start} — {end} из {count}',
        'sorterHeader'=>'',
        'itemsTagName'=>'div',
        'sortableAttributes'=>array('name'),
        'pager' => array(
            'class' => 'LinkPager',
            'maxButtonCount' => '5',
            'header'   => false,
            'firstPageLabel' => '<<',
            'prevPageLabel'  => '<',
            'nextPageLabel'  => '>',
            'lastPageLabel'  => '>>',
            'cssFile'        => false
        )
    )); ?>
    <?php endif; ?>
    <?php if(!$flag) echo '<div class="no-result">По Вашему запросу ничего не найдено.</div>'; ?>
</div>
<?php endif; ?>
<script>
//(function($){
//    $(window).load(function() {
//        $('#full-search').focus(function() {
//            $('#full-search').blur(function(){
//                $('.full-quick-result').fadeOut(200);
//            });
//            var ajax = new AjaxQuickSearch('full');
//        });
//        
//        var search_enter=new QuickSearchEnter('full');
//        
//        $('.full-search-button').click(function() {
//            var input = $.trim($('#full-search').val());
//            if(input.length > 0)
//               document.location.href = "/search/show/input/" + input;
//        });
//        
        
        /*$('.pager').pagination({
            items: 100,
            itemsOnPage: 10,
            cssStyle: 'compact-theme'
        });*/
    });
})(jQuery);
</script>

