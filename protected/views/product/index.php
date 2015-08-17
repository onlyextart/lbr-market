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
    ?>
</div>
<div itemtype="http://schema.org/Product" itemscope="">
   <div class="product-wrapper">
        <h1 itemprop="name"><?php echo $data->name; ?></h1>
        <div class="product-image-wrapper">
            <a href="<?php echo $image; ?>" class="thumbnail" target="_blank">
               <img border="0" itemprop="image" alt="<?php echo $data->name; ?>" src="<?php echo $image; ?>">
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
                                } else if($data->liquidity == 'D' && $data->count > 0){
                                    echo '<span>'.$price.'</span><div class="price-info">(на условии самовывоза со склада: '.$filial.')</div>';
                                } else {
                                    echo '<span class="price_link"><a href="/site/login/">'.Yii::app()->params['textNoPrice'].'</a></span>';
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
                             <input onclick="yaCounter30254519.reachGoal('addtocard'); ga('send','event','action','addtocard'); return true;" type="submit" title="Добавить в корзину" value="" class="small-cart-button">
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
                      <img src="<?php echo Product::model()->getImage($related->image, 'm'); ?>" alt="">
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
                      <img src="<?php echo Product::model()->getDraftImage($draft[image], 'm'); ?>" alt="Сборочный чертеж">
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

