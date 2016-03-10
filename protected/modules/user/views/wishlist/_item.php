<?php
    //echo '<pre>';
    //var_dump($data); exit;
    
    $image = Product::model()->getImage($data->product->image);
    $productName = $data->product->name;
    //$productInfo = $info[$data->product->id];
    
    if(!empty($data->original_product_id)) {
        $productName = 'Аналог товара "'.Product::model()->findByPk($data->original_product_id)->name.'"';
    }
    
    $price = Yii::app()->params['textHidePrice'];
    if(Yii::app()->params['showPrices']) {
       $price = Price::model()->getPrice($data->product->id);
       if(empty($price)) $price = '<span class="no-price-label">'.Yii::app()->params['textNoPrice'].'</span>';
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
        <div><?php echo ProductMaker::model()->findByPk($data->product->product_maker_id)->country ?></div>
        <div><?php echo $price ?></div>
    </div>
    <div class="width-20">
       <?php echo ($data->product->count > 0) ? '<span class="in-stock">'.Product::IN_STOCK.'</span>' : Product::NO_IN_STOCK ; ?>             
    </div>
    <div class="width-20">      
       <?php
          $count = 1;
          if(!empty($data->count)) $count = $data->count;
          echo $count.' шт.';
       ?>                
    </div>
    <div class="width-5" elem="<?php echo $data->product->id ?>" count="<?php echo $count ?>" original="<?php echo (!empty($data->original_product_id)) ? $data->original_product_id : '' ?>">
        <input onclick="yaCounter30254519.reachGoal('addtocard'); ga('send','event','action','addtocard'); return true;" type="submit" title="Добавить в корзину" value="" class="small-cart-button-wishlist">
    </div>
    <div class="width-5 remove-wrap">
        <a title="Удалить из блокнота" class="remove" href="/wishlist/remove<?php echo $data->product->path ?>"></a>
    </div>
    <div style="clear: both;"></div>
</div>

