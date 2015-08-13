<?php
     $image = Product::model()->getImage($data->image);
?>
<div class="wish-item">
    <div style="width: 115px; float: left;">
        <a class="thumbnail" href="<?php echo $image ?>">
          <img class="thumbnail" alt="" src="<?php echo $image ?>">
       </a>
    </div>
    <div class="width-30">
        <a href="<?php echo $data->path ?>" target="_blank"><?php echo $data->name ?></a>              
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
    <div class="width-5 remove-wrap">
        <a class="remove" href="/wishlist/remove<?php echo $data->path ?>"></a>
    </div>
    <div style="clear: both;"></div>
</div>

