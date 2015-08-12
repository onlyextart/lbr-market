
        <div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'name');
                echo $form_view->textField($model, 'name', array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'external_id');
                echo $form_view->textField($model, 'external_id', array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->error($model, 'published'); 
                echo $form_view->labelEx($model, 'published');
                echo $form_view->dropDownList($model, 'published',array('0'=>'Нет','1'=>'Да'));
                if (!empty($model->id)) {
                    echo CHtml::link('Предварительный просмотр', $model->path, array('class' => 'link_view','target'=>'_blank')); 
                }
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'group');
                echo $form_view->textField($model, 'group', array('disabled'=>'true'));
                echo $form_view->hiddenField($model, 'product_group_id');
            ?>
        </div>

        <div class="row">      
            <?php   
                echo $form_view->labelEx($model, 'product_maker_id');
                echo $form_view->dropDownList($model, 'product_maker_id',$model->getProductMaker(), array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php   
                echo $form_view->labelEx($model, 'catalog_number');
                echo $form_view->textField($model, 'catalog_number', array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'count');
                echo $form_view->textField($model, 'count', array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'liquidity');
                echo $form_view->textField($model, 'liquidity', array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'min_quantity');
                echo $form_view->textField($model, 'min_quantity', array('disabled'=>'true'));
            ?>
        </div>

<!--        <div class="row">      
            <?php  
//                echo $form_view->error($model, 'price_value'); 
//                echo $form_view->labelEx($model, 'price_value');
//                echo $form_view->textField($model, 'price_value',array('class'=>'number'));
//                echo $form_view->dropDownList($model, 'currency_iso',$model->getCurrency());
            ?>
        </div>-->

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
//echo CHtml::openTag('div', array('class'=>'row'));
//echo CHtml::label('Выберите изображение', 'image');
//echo $form_view->fileField($model, 'image');
//echo CHtml::closeTag('div');

// Image
if(!empty($model->image)) {
    $href = 'http://api.lbr.ru/images/shop/spareparts/'.$model->image;
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
                        $href,
                        CHtml::encode('test'),
                        array('max-height'=>'150px',)
                    ),
                    $href,
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
        
    






