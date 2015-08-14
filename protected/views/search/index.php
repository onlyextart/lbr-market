<?php 
    $flag = false;
?>
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
<div class="search-form">
    <form action="#">
        <div class="search-metod">
            <h1>ПОИСК ПО РАЗДЕЛАМ</h1>
        </div>
        <div class="query-field">
            <input id="full-search" type="text" name="q" value="<?php echo $input ?>" placeholder="Найти" autocomplete="off"/>
            <ul class="full-quick-result"></ul>
            <input class="btn full-search-button" type="button" value="Найти">
        </div>
    </form>
</div>
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
    <?php if(!$flag) echo '<div class="no-result">По Вашему запросу ничего не найдено.</div>'; ?>
</div>
<?php endif; ?>
<script>
(function($){
    $(window).load(function() {
        $('#full-search').focus(function() {
            $('#full-search').blur(function(){
                $('.full-quick-result').fadeOut(200);
            });
            var ajax = new AjaxQuickSearch('full');
        });
        
        $('.full-search-button').click(function() {
            var input = $.trim($('#full-search').val());
            if(input.length > 0)
               document.location.href = "/search/show/input/" + input;
        });
        
        $("#full-search").keypress(function(e){
            if(e.keyCode==13){
                var input = $.trim($('#full-search').val());
                if(input.length > 0)
                    document.location.href = "/search/show/input/" + input;
            }
	});
        
        /*$('.pager').pagination({
            items: 100,
            itemsOnPage: 10,
            cssStyle: 'compact-theme'
        });*/
    });
})(jQuery);
</script>

