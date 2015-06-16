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
<div class="model-wrapper">
    <h1><?php echo $title?></h1>
    <?php if(!empty($hitProducts)): ?>
    <!--div class="spec-offer"><a href="#">Спецпредложение для "<?php echo $title?>"</a></div-->
    <h2>Хиты продаж для "<?php echo $title?>"</h2>
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
    <?php if(!empty($result)): ?>
    <h2>Запасные части для <?php echo $title?></h2>
    <span class="spareparts-order">
        Сортировать по:
        <!--a href="/model/sort/id/<?php echo $model->id?>/name/name/" class="<?php if(Yii::app()->params['sortCol'] == 'name') echo Yii::app()->params['sortOrder'] ?>">Названию</a>
        <a href="/model/sort/id/<?php echo $model->id?>/name/col/" class="<?php if(Yii::app()->params['sortCol'] == 'col') echo Yii::app()->params['sortOrder'] ?>">Наличию</a>
        <a href="/model/sort/id/<?php echo $model->id?>/name/category/" class="<?php if(Yii::app()->params['sortCol'] == 'category') echo Yii::app()->params['sortOrder'] ?>">Категории</a-->
        
        <a href="/model/show/id/<?php echo $model->id?>/sort/name/order/<?php echo (Yii::app()->params['sortCol'] == 'name' && Yii::app()->params['sortOrder'] == 'asc')?'desc':'asc' ?>/" class="<?php if(Yii::app()->params['sortCol'] == 'name') echo Yii::app()->params['sortOrder'] ?>">Названию</a>
        <a href="/model/show/id/<?php echo $model->id?>/sort/col/order/<?php echo (Yii::app()->params['sortCol'] == 'col' && Yii::app()->params['sortOrder'] == 'asc')?'desc':'asc' ?>/" class="<?php if(Yii::app()->params['sortCol'] == 'col') echo Yii::app()->params['sortOrder'] ?>">Наличию</a>
        <a href="/model/show/id/<?php echo $model->id?>/sort/category/order/<?php echo (Yii::app()->params['sortCol'] == 'category' && Yii::app()->params['sortOrder'] == 'asc')?'desc':'asc' ?>/" class="<?php if(Yii::app()->params['sortCol'] == 'category') echo Yii::app()->params['sortOrder'] ?>">Категории</a>
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
        $('.cell.price-link').easyTooltip({content:'Авторизуйтесь, чтобы узнать цену'});
        
        $( ".small-cart-button" ).on('click', function() {
            var parent = $(this).parent();
            var cart = parent.find('.cart-quantity');
            var count = parseInt(cart.val());
            
            if(count > 0) {
                $.ajax({
                    type: 'POST',
                    url: '/cart/add',
                    dataType: 'json',
                    data: {
                        id: parent.attr('elem'),
                        count: count,
                    },
                    success: function(response) {
                        cart.val('1');
                        if(response.count) {
                            var label = ' товаров';
                            if(response.count == 1) {
                                label = ' товар';
                            } else if(response.count == 2 || response.count == 3 || response.count == 4){
                                label = ' товарa';
                            }
                            $('#cart-count').text(response.count+label);
                        }
                        alertify.success(response.message);
                    },
                });
            } else {
                alertify.success('<div class="mes-notify"><span></span><div>Введено неправильное количество</div></div>');
            }
        });
        
        /*$( ".spareparts-order a" ).on('click', function() {
            if($(this).hasClass('asc') || $(this).hasClass('desc')) {
                if($(this).hasClass('asc')) {
                    $(this).addClass('desc');
                }
                else $(this).addClass('asc');
            } else {
                $(this).addClass('asc');
            }
        });*/
    });
</script>
