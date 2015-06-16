<?php
     $image = '/images/no-photo.png';
     if(!empty($data->image)) $image = 'http://api.lbr.ru/images/shop/spareparts/'.$data->image;
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
       xxxx руб.                
    </div>
    <div class="width-5 remove-wrap">
        <a class="remove" href="/wishlist/remove<?php echo $data->path ?>"></a>
    </div>
    <div style="clear: both;"></div>
</div>

