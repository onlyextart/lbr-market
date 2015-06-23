<div class="row">      
    <?php  
        echo $form_view->labelEx($model, 'name');
        echo $form_view->textField($model, 'name');
    ?>
</div>

<div class="row">      
    <?php  
        echo $form_view->error($model, 'published'); 
        echo $form_view->labelEx($model, 'published');
        echo $form_view->dropDownList($model, 'published',array('0'=>'Нет','1'=>'Да'));
        echo CHtml::link('Предварительный просмотр', '/equipmentmaker/index/id/'.$model->id, array('class' => 'link_view','target'=>'_blank')); 
    ?>
</div>

<div class="row">
    <?php 
        echo $form_view->labelEx($model, 'description');
        $this->widget('application.components.SRichTextarea',array(
            'model'=>$model,
            'attribute'=>'description'));
    ?>
</div>

<div class="row">      
    <?php  
        echo $form_view->labelEx($model, 'meta_title');
        //echo $form_view->textField($model, 'meta_title');
        $this->widget('application.components.SRichTextarea',array(
            'model'=>$model,
            'attribute'=>'meta_title'));
    ?>
</div>

<div class="row">      
    <?php  
        echo $form_view->labelEx($model, 'meta_description');
        //echo $form_view->textField($model, 'meta_description');
        
        $this->widget('application.components.SRichTextarea',array(
            'model'=>$model,
            'attribute'=>'meta_description'));
        
    ?>
</div>

<div class="row">
    <?php 
        echo $form_view->labelEx($model, 'top_text');
        $this->widget('application.components.SRichTextarea',array(
            'model'=>$model,
            'attribute'=>'top_text'));
    ?>
</div>

<div class="row">
    <?php 
        echo $form_view->labelEx($model, 'bottom_text');
        $this->widget('application.components.SRichTextarea',array(
            'model'=>$model,
            'attribute'=>'bottom_text'));
    ?>
</div>

<?php
/*if(!empty($model->logo)) { 
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
));*/

