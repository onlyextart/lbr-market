<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => Yii::app()->params['breadcrumbs'],
    'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
    'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
    'inactiveLinkTemplate' => '{label}',
));   

echo '<div class="article">';
echo '<h1>' . $data['header'] . '</h1>';
echo $data['full_description'];


if($url == 'delivery') {
//    echo '<div>Актуальные цены доставки уточняйте у перевозчика</div>';
//    Yii::app()->clientScript->registerScriptFile('http://www.baikalsr.ru/api-calc/?ver=2.2&setAccount=BS-0000189');
//    echo '<div id="baycalService"></div>';
}

echo CHtml::openTag('dl', array(
    'class' => 'article-info'
 ));
echo CHtml::openTag('dd', array(
    'class' => 'create'
 ));
echo '<span>Обновлено: '.date('d.m.Y H:i', strtotime($data['date_edit'])).'</span>';
echo CHtml::closeTag('dd');
echo CHtml::closeTag('dl');
echo '</div>';
?>
<?php if($url == 'delivery'): ?>
<script>
    $(function() {
       $("#baycalService").bsCalculator({
            city_out:true
        });
    });
</script>
<?php endif; ?>


