<div class="row">
    <?php
        echo $form_view->labelEx($model, 'logo');
        echo $form_view->fileField($model, 'logo');
        echo CHtml::label('*не более 300Кб', 'logo', array('class'=>'note'));
        echo $form_view->error($model, 'logo'); 
    ?>
</div>

<?php
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