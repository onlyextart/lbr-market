<div class="breadcrumbs">
    <?php
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
        //echo $data->image; exit;
        $image = '/images/no-photo.png';
        if(!empty($data->image)) $image = 'http://api.lbr.ru/images/shop/spareparts/'.$data->image;
    ?>
</div>
<div itemtype="http://schema.org/Product" itemscope="">
   <!--div>
    <?php
        /*echo CHtml::link(
            'Назад',
            empty(Yii::app()->request->urlReferrer)?'http://lbr-market.ru':Yii::app()->request->urlReferrer
        );*/
    ?>
   </div-->
   <div class="product-wrapper">
        <h1 itemprop="name"><?php echo $data->name; ?></h1>
        <div class="product-image-wrapper">
            <!--img border="0" itemprop="image" alt="Двигатель ВАЗ-2103-01-07 1.5л, 70л.с, Аи-92" src="http://api.lbr.ru/images/shop/SMK-00082297_IMG_0022.jpg"-->

            <a href="<?php echo $image; ?>" class="thumbnail" target="_blank">
               <img border="0" itemprop="image" alt="Двигатель ВАЗ-2103-01-07 1.5л, 70л.с, Аи-92" src="<?php echo $image; ?>">
            </a>
        </div>
        <div id="prod-info">
           <table>
              <tbody>
                  <?php if(!empty($update)): ?>
                  <tr>
                      <td class="date-label"></td>
                      <td class="date-label">Обновлено: <?php echo $update ?></td>
                  </tr>
                  <?php endif; ?>
                  <?php if(!empty($maker)): ?>
                  <tr>
                     <td>Производитель:</td>
                     <td>
                        <span title="производитель"><?php echo $maker->name ?></span>
                     </td>
                  </tr>
                  <?php endif; ?>
                  <?php if(!empty($maker->country)): ?>
                  <tr>
                     <td>Страна производителя:</td>
                     <td>
                        <span title="страна производитель"><?php echo $maker->country ?></span>
                     </td>
                  </tr>
                  <?php endif; ?>
                  <?php if(!empty($data->weight)): ?>
                  <tr>
                     <td>Вес, кг:</td>
                     <td>
                        <span title="вес"><?php echo $data->weight ?></span>
                     </td>
                  </tr>
                  <?php endif; ?>
                  <?php if(Yii::app()->params['showPrices']): ?>
                  <tr itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
                     <td>Цена:</td>
                     <td class="price">
                         <div itemprop="price">
                             <?php
                                if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
                                    echo '<span>'.$price.'</span><div class="price-info">(на условии самовывоза со склада: <a href="/user/cabinet/index/">'.$filial.'</a>)</div>';
                                } else if(!Yii::app()->user->isGuest) {
                                    echo '<span>'.$price.'</span><div class="price-info">(на условии самовывоза со склада: '.$filial.')</div>';
                                } else {
                                    echo '<span class="price_link"><a href="/site/login/">Узнать цену</a></span>';
                                }
                             ?>
                        </div>
                        <link href="http://schema.org/InStock" itemprop="availability">
                     </td>
                  </tr>
                  <?php elseif(!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin']): ?>
                  <tr itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
                     <td>Цена:</td>
                     <td class="price">
                         <div itemprop="price">
                             <?php
                                echo '<span>'.$price.'</span><div class="price-info">(цена указана на условии самовывоза со склада: '.$filial.')</div>';
                             ?>
                        </div>
                        <link href="http://schema.org/InStock" itemprop="availability">
                     </td>
                  </tr>
                  <?php endif; ?>
                  <?php //if ((!Yii::app()->user->isGuest && !empty($price)) || Yii::app()->user->isGuest): ?>
                  <tr>
                     <td>Наличие:</td>
                     <td>
                        <?php if((int)$data->count > 0): ?>
                        <span class="in-stock"><?php echo Product::IN_STOCK ?></span>
                        <?php else: ?>
                        <span><?php echo Product::NO_IN_STOCK ?></span>
                        <?php endif; ?>
                     </td>
                  </tr>
                  <?php //endif; ?>
                  <?php if(Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop))): ?>
                  <tr>
                     <td>Корзина</td>
                     <td class="price">
                        <div class="cart-form" elem="<?php echo $data->id ?>">
                             <?php //if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop) && $price): ?>
                             <input type="number" value="1" min="1" pattern="[0-9]*" name="quantity" maxlength="7" size="7" autocomplete="off" product="1" class="cart-quantity">
                             <input type="button" title="Добавить в корзину" value="" class="small-cart-button">
                             <?php //endif; ?>
                             <button class="wish" title="Добавить в блокнот">
                                 <span class="wish-icon"></span>
                                 В блокнот
                             </button>
                         </div>
                     </td>
                  </tr>
                  <?php endif; ?>
               </tbody>
           </table>
        </div>
        <?php if(!empty($data->additional_info)): ?>
        <div itemprop="description">
            <?php echo $data->additional_info; ?>
        </div>
        <?php endif; ?>
        <div class="clearfix"></div>
    </div>
    <div class="product-info">
        <?php if(!empty($analogProducts)): ?>
        <div class="left-menu-wrapper grey" style="display: none">
            <ul class="accordion" id="accordion-sparepart">
                <li>
                    <a href="#">Аналоги</a>
                    <ul>
                    <?php echo $analogProducts; ?>
                    </ul>
                </li>
             </ul>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($relatedProducts)): ?>
        <h2>Сопутствующие товары</h2>
        <div class="best-sales">
            <?php foreach($relatedProducts as $related): ?>
            <div class="one_banner">
               <h3><a target="_blank" href="<?php echo $related->path; ?>"><?php echo $related->name; ?></a></h3>
               <div class="img-wrapper">
                   <a target="_blank" href="<?php echo $related->path; ?>">
                      <img src="http://api.lbr.ru/images/shop/spareparts/<?php echo $related->image; ?>" alt="">
                   </a>
               </div>
            </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($drafts)): ?>
        <h2>Сборочные чертежи</h2>
        <div class="best-sales">
            <?php foreach($drafts as $draft): ?>
            <div class="one_banner">
               <h3><a target="_blank" href="/draft/index/id/<?php echo $draft[id]; ?>/"><?php echo $draft[name]; ?></a></h3>
               <div class="img-wrapper">
                   <a target="_blank" href="/draft/index/id/<?php echo $draft[id]; ?>/">
                      <img src="http://api.lbr.ru/images/shop/draft/<?php echo $draft[image]; ?>" alt="Нет изображения">
                   </a>
               </div>
            </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
   </div>
</div>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));
?>

<script>
(function($){
    $(".left-menu-wrapper").css('display','block');
    $('.price_link').easyTooltip({content:'Авторизуйтесь, чтобы узнать цену'}); 
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
                    //alertify.set({ delay: 2000000 }); 
                    $('.cart-quantity').val('1');
                    if(response.count){
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
    
    //Add product to wish list
    $( ".wish" ).on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/wishlist/add/',
            dataType: 'json',
            data: {
                id: <?php echo $data->id; ?>,
            },
            success: function(response) {
                if(response.redirect) 
                    window.location = response.redirect;
                else {
                    alertify.success(response.message);
                }
            },
        });
    });
})(jQuery);
</script>

