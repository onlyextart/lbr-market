<div>
    <div class="label"><a target="_blank" href="<?php echo Yii::app()->getBaseUrl(true).$data->path ?>"><?php echo $data->name; ?></a></div>
    <?php if($data->count > 0): ?>
    <span style="color:#F39416; font-size: bold;"><?php echo Product::IN_STOCK ?></span>
    <?php else: ?>
    <span><?php echo Product::NO_IN_STOCK ?></span>
    <?php endif; ?>
</div>

