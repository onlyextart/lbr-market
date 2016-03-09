<?php
    //echo '<pre>';
    //echo $data->id.'<br>';
    //var_dump($info[$data->id]); exit;
    //var_dump($info); exit;

    $image = Product::model()->getImage($data->image);
    $productName = $data->name;
    $productInfo = $info[$data->id];
    if(!empty($productInfo['original'])) {
        $productName = 'Аналог товара "'.Product::model()->findByPk($productInfo['original'])->name.'"';
    }
?>
<div class="wish-item">
    <div style="width: 115px; float: left;">
        <a class="thumbnail" href="<?php echo $image ?>">
          <img class="thumbnail" alt="" src="<?php echo $image ?>">
        </a>
    </div>
    <div class="width-30">
        <div><?php echo $productName ?></div>
        <div><?php echo ProductMaker::model()->findByPk($data->product_maker_id)->country ?></div>
    </div>
    <div class="width-20">
       <?php echo ($data->count > 0) ? Product::IN_STOCK : Product::NO_IN_STOCK ; ?>             
    </div>
    <div class="width-20">      
       <?php
          $price = Yii::app()->params['textHidePrice'];
          if(Yii::app()->params['showPrices']) {
             $price = Price::model()->getPrice($data->id);
             if(empty($price)) $price = '<span class="no-price-label">'.Yii::app()->params['textNoPrice'].'</span>';
          }
          echo $price;
       ?>                
    </div>
    <div class="width-5" elem="<?php echo $data->id ?>">
        <input onclick="yaCounter30254519.reachGoal('addtocard'); ga('send','event','action','addtocard'); return true;" type="submit" title="Добавить в корзину" value="" class="small-cart-button-wishlist">
    </div>
    <div class="width-5 remove-wrap">
        <a title="Удалить из блонкота" class="remove" href="/wishlist/remove<?php echo $data->path ?>"></a>
    </div>
    <div style="clear: both;"></div>
</div>

