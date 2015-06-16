<?php
if(!empty($data)){
    foreach ($data as $li)
    {
        $text = $li['name'];
        echo '<li><a href="'.Yii::app()->getBaseUrl(true).$li['path'].'">'.$text.'</a></li>';
    }
} else echo '<li>Ничего не найдено ...</li>';
