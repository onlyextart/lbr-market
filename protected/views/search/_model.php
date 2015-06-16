<div>
    <div class="label">
        <!--a href="<?php echo Yii::app()->getBaseUrl(true).'/modelline/index/id/'.$data->id?>"-->
        <a href="<?php echo Modelline::getUrl($data->id); ?>">
            <?php echo $data->name; ?>
        </a>
    </div>
</div>

