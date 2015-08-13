<?php
Yii::app()->getClientScript()->registerCss('infoStyles', "
	table.imagesList {
		float: left;
		width: 45%;
		min-width:250px;
		margin-right: 15px;
		margin-bottom: 15px;
	}
	div.MultiFile-list {
		margin-left:190px
	}
");

// Upload button
echo CHtml::openTag('div', array('class'=>'row'));
echo CHtml::label('Выберите изображение', 'files');
$this->widget('system.web.widgets.CMultiFileUpload', array(
    'model'=>$model,
    'name'=>'Images',
    'attribute'=>'files',
    'accept'=>'jpg|jpeg|JPG|JPEG|gif|png',
    'denied'=>'Разрешено загружать файлы с расширением jpg, jpeg, gif или png.',
    'max'=>'1',
));
echo CHtml::label('*не более 300Кб', 'logo', array('class'=>'note'));
echo CHtml::closeTag('div');

// Image
if(!empty($model->logo)) {   
    $this->widget('zii.widgets.CDetailView', array(
        'data'=>$model,
        'htmlOptions'=>array(
            'class'=>'detail-view imagesList',
        ),
        'attributes'=>array(
            array(
                'label'=>'Изображение',
                'type'=>'raw',
                'value'=>CHtml::link(
                    CHtml::image(
                        $model->logo,
                        CHtml::encode('test'),
                        array('max-height'=>'150px',)
                    ),
                    $model->logo,
                    array('target'=>'_blank', 'class'=>'pretty')
                ),
            ),
        ),
    ));
}

// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.pretty',
	'config'=>array(),
));