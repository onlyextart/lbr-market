<?php

$image = '/images/no-photo.png';
if(!empty($data->image)) $image = 'http://api.lbr.ru/images/shop/spareparts/'.$data->image;

$count = '<span class="stock">'.Product::NO_IN_STOCK.'</span>';
if($data->count > 0) {
    $count = '<span class="stock in-stock">'.Product::IN_STOCK_SHORT.'</span>';
} 

$cart = '';
$intent = "\"ga('send', 'event', 'action','addtocard'); yaCounter30254519.reachGoal('addtocard'); return true;\" ";
if(Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop))){
    $cart = '<input type="number" value="1" min="1" pattern="[0-9]*" name="quantity" maxlength="4" size="7" autocomplete="off" product="1" class="cart-quantity">
        <input onsubmit='.$intent. ' type="button" title="Добавить в корзину" value="" class="small-cart-button">'
    ;

    $cart .= '<button class="wish-small" title="Добавить в блокнот">
                   <span class="wish-icon"></span>
                </button>'
    ;
}

$draftLabel = '';
$allDrafts = ProductInDraft::model()->findAllByAttributes(array('product_id'=>$data->id));

if(!empty($allDrafts)){
    foreach($allDrafts as $one) {
        $draft = Draft::model()->findByPk($one['draft_id']);
        $draftLabel .= '<a target="_blank" href="/draft/index/id/'.$draft->id.'">Чертеж "'.$draft->name.'"</a>';
    }
}
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));
?>
<div class="spareparts-wrapper">
    <div class="row">
        <div class="cell width-20">
            <a target="_blank" href="<?php echo $data->path ?>"><?php echo $data->name ?></a>
        </div>
        <div class="cell cell-img">
            <a class="thumbnail" target="_blank" href="<?php echo $image ?>">
                <img alt="<?php echo $data->name ?>" src="<?php echo Product::model()->getImage($data->image, 's'); ?>">
            </a>
        </div>
        <div class="cell draft width-35"><?php echo (Yii::app()->params['showDrafts']) ? $draftLabel : '' ?></div>
        <div class="cell width-15">
            <span><?php
               if(!Yii::app()->params['showPrices']) {
                   // show for admin
                   if(!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin']) {
                      $priceLabel = Price::model()->getPrice($data->id);
                      if(!empty($priceLabel)) echo $priceLabel;
                      else echo '<span class="no-price-label">'.Yii::app()->params['textNoPrice'].'</span>';
                   } else echo Yii::app()->params['textHidePrice'];
               } else {
                  $priceLabel = Price::model()->getPrice($data->id);
                  if(!empty($priceLabel)) echo $priceLabel;
                  else echo '<span class="no-price-label">'.Yii::app()->params['textNoPrice'].'</span>';
               }
            ?></span>
        </div>
        <div class="cell width-20">
            <div class="cart-form" elem="<?php echo $data->id ?>">
                <?php echo $count ?>
                <?php echo $cart ?>
            </div>
        </div>
    </div>
</div>