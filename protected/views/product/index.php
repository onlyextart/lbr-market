<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));       
    
    $makerLabel = $maker->name;
    //$path = Yii::getPathOfAlias('webroot').$maker->logo;
    if ($maker->published && !empty($maker->logo)) { // && file_exists(Yii::getPathOfAlias('webroot').$maker->logo)) {
        $makerLabel = '<a href="/product-maker'.$maker->path.'/">' .$makerLabel . '</a>';
    }
                    
?>
<div>
    <?php if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)): // logged user ?>
    <div class="product-wrapper" itemscope itemtype="http://schema.org/Product">    
        <div class="prod-name" itemprop="name"><?php echo $data->name; ?></div>
        <div id="prod-info">
            <div class="product-image-wrapper">
                <a href="<?php echo $image; ?>" class="thumbnail" target="_blank">
                   <img border="0" itemprop="image" alt="<?php echo $data->name; ?>" src="<?php echo $image; ?>">
                </a>
            </div>
            <div class="product-params">
                <?php if(!empty($update)): ?>
                <div>
                      <span></span>
                      <span class="date-label">Обновлено: <?php echo $update ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($maker)): ?>
                <div itemprop="brand" itemscope itemtype="http://schema.org/Organization">
                    <span>Производитель:</span>
                    <span title="Производитель" itemprop="name"><?php echo $makerLabel ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($maker->country)): ?>
                <div itemprop="manufacturer" itemscope itemtype="http://schema.org/Organization">
                    <span>Страна производителя:</span>
                    <span title="Страна производитель" itemprop="name"><?php echo $maker->country ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->weight)): ?>
                <div>
                    <span>Ориентировочный вес, кг:</span>
                    <span title="Вес" itemprop="weight"><?php echo $data->weight ?></span>
                </div>
                <?php endif; ?>
                <?php if(empty($data->date_sale_off)): ?>
                    <?php if(Yii::app()->params['showPrices']): ?>
                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <span>Цена:</span>
                        <span>
                            <?php
                                if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
                                    echo '<span itemprop="price">'.$price.'</span><div class="price-info">(на условии самовывоза со склада: <a href="/user/cabinet/index/">'.$filial.'</a>)</div>';
                                } else if(!Yii::app()->user->isGuest) {
                                    echo '<span>'.$price.'</span><div class="price-info">(на условии самовывоза со склада: '.$filial.')</div>';
                                } else if($data->liquidity == 'D' && $data->count > 0){
                                    echo '<span>'.$price.'</span><div class="price-info">(на условии самовывоза со склада: '.$filial.')</div>';
                                } else {
                                    echo '<span class="price_link"><a href="/site/login/">'.Yii::app()->params['textNoPrice'].'</a></span>';
                                }
                             ?>
                        </span>
                    </div>
                    <?php elseif(!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin']): ?>
                    <div>
                        <span>Цена:</span>
                        <span>
                            <?php
                               echo '<span>'.$price.'</span><div class="price-info">(цена указана на условии самовывоза со склада: '.$filial.')</div>';
                            ?>
                        </span>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div>
                    <span>Наличие:</span>
                    <span>
                        <?php if(empty($data->date_sale_off)): ?>
                            <?php if((int)$data->count > 0): ?>
                            <span class="in-stock" itemprop="availability" href="http://schema.org/InStock"><?php echo Product::IN_STOCK ?></span>
                            <?php else: ?>
                            <span itemprop="availability" href="http://schema.org/InStock"><?php echo Product::NO_IN_STOCK ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span  itemprop="availability" href="http://schema.org/InStock"><?php echo Yii::app()->params['textSaleOff'] ?></span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if(empty($data->date_sale_off) && (Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)))): ?>
                <div>
                    <span>Корзина</span>
                    <span class="price">
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
                     </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: // guest or admin?>
    <div class="product-wrapper">
        <div class="prod-name"><?php echo $data->name; ?></div>
        <div id="prod-info">
            <div class="product-image-wrapper">
                <a href="<?php echo $image; ?>" class="thumbnail" target="_blank">
                   <img border="0" itemprop="image" alt="<?php echo $data->name; ?>" src="<?php echo $image; ?>">
                </a>
            </div>
            <div class="product-params">
                <?php if(!empty($update)): ?>
                <div>
                      <span></span>
                      <span class="date-label">Обновлено: <?php echo $update ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($maker)): ?>
                <div>
                    <span>Производитель:</span>
                    <span title="Производитель"><?php echo $makerLabel ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($maker->country)): ?>
                <div>
                    <span>Страна производителя:</span>
                    <span title="Страна производитель"><?php echo $maker->country ?></span>
                </div>
                <?php endif; ?>
                <?php if(!Yii::app()->user->isGuest && !empty($data->weight)): ?>
                <div>
                    <span>Ориентировочный вес, кг:</span>
                    <span title="Вес"><?php echo $data->weight ?></span>
                </div>
                <?php endif; ?>
                <?php if(empty($data->date_sale_off)): ?>
                    <?php if(Yii::app()->params['showPrices']): ?>
                    <div>
                        <span>Цена:</span>
                        <span>
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
                        </span>
                    </div>
                    <?php elseif(!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin']): ?>
                    <div>
                        <span>Цена:</span>
                        <span>
                            <?php
                               echo '<span>'.$price.'</span><div class="price-info">(цена указана на условии самовывоза со склада: '.$filial.')</div>';
                            ?>
                        </span>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div>
                    <span>Наличие:</span>
                    <span>
                        <?php if(empty($data->date_sale_off)): ?>
                            <?php if((int)$data->count > 0): ?>
                            <span class="in-stock"><?php echo Product::IN_STOCK ?></span>
                            <?php else: ?>
                            <span><?php echo Product::NO_IN_STOCK ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span><?php echo Yii::app()->params['textSaleOff'] ?></span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if(empty($data->date_sale_off) && (Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)))): ?>
                <div>
                    <span>Корзина</span>
                    <span class="price">
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
                     </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
    <?php if(!empty($data->additional_info)): ?>
    <div class="product-add-info">
        <?php echo $data->additional_info; ?>
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
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

