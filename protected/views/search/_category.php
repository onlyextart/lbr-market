<?php
    preg_match('/\d{2,}\./i', $data->name, $result);
    $title = trim(substr($data->name, strlen($result[0])));
    $path = '/catalog'.$data->path.'/';
?>
<div>
    <div class="label"><a href="<?php echo Yii::app()->getBaseUrl(true).$path; ?>"><?php echo $title; ?></a></div>
</div>

