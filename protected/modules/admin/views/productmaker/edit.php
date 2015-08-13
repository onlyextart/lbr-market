<?php
$name = 'Создание производителя запчастей';
$submit_text = 'Создать';
$action = '/admin/productmaker/create/';
if (!empty($model->id)) {
    $submit_text = 'Сохранить';
    $name = 'Редактирование производителя запчастей';
    $action = '/admin/productmaker/edit/id/' . $model->id;
}
$this->breadcrumbs = array(
    'Home' => $this->createUrl('/admin/'),
    'Сайт' => Yii::app()->createUrl(''),
    'Производители' => Yii::app()->createUrl('/admin/productmaker/'),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <?php /* if(!empty($model->id)): ?>
      <div class="admin-btn-one">
      <span class="admin-btn-del"></span>
      <?php echo CHtml::link('Удалить', '/admin/productmaker/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
      </div>
      <?php endif; */ ?>
    <div class="admin-btn-one">
        <span class="admin-btn-save"></span>
        <?php echo CHtml::button($submit_text, array('id' => 'save-btn', 'class' => 'btn-admin')); ?>
    </div>
    <div class="admin-btn-one">
        <span class="admin-btn-close"></span>
        <?php echo CHtml::button('Закрыть', array('id' => 'close-btn', 'class' => 'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="total">
    <div class="left">
        <div class="form wide wide-wrapper padding-all">
            <?php
            $form_view = $this->beginWidget('CActiveForm', array(
                'id' => 'ProductMaker',
                'action' => $action,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'validateOnChange' => true,
                    'afterValidate' => 'js:function( form, data, hasError ) 
                        {     
                            if( hasError ){
                                return false;
                            }
                            else{
                                return true;
                            }
                        }'
                ),
            ));

            echo $form_view->errorSummary($model);
            ?>
            <div class="row">      
                <?php
                echo $form_view->labelEx($model, 'name');
                echo $form_view->textField($model, 'name');
                ?>
            </div>

            <div class="row">      
                <?php
                echo $form_view->error($model, 'country');
                echo $form_view->labelEx($model, 'country');
                echo $form_view->textField($model, 'country');
                ?>
            </div>

            <div class="row">      
                <?php
                echo $form_view->error($model, 'published');
                echo $form_view->labelEx($model, 'published');
                echo $form_view->dropDownList($model, 'published', array('0' => 'Нет', '1' => 'Да'));
                //echo CHtml::link('Предварительный просмотр', '/description/maker/id/'.$model->id, array('class' => 'link_view','target'=>'_blank')); 
                ?>
            </div>

            <div class="row">
                <?php
                echo $form_view->labelEx($model, 'logo');
                echo $form_view->fileField($model, 'logo');
                echo $form_view->error($model, 'logo');
                ?>
            </div>
            <!--?php echo $form->asTabs(); ?-->

            <?php
//            // Upload button
//            echo CHtml::openTag('div', array('class'=>'row'));
//            echo CHtml::label('Выберите изображение', 'files');
//            $this->widget('system.web.widgets.CMultiFileUpload', array(
//                'model'=>$model,
//                'name'=>'Images',
//                'attribute'=>'files',
//                'accept'=>'jpg|jpeg|JPG|JPEG|gif|png',
//                'denied'=>'Разрешено загружать файлы с расширением jpg, jpeg, gif или png.',
//                'max'=>'1',
//            ));
//            echo CHtml::closeTag('div');
            if (!empty($model->logo)) {
                $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'htmlOptions' => array(
                        'class' => 'detail-view imagesList',
                    ),
                    'attributes' => array(
                        array(
                            'label' => 'Изображение',
                            'type' => 'raw',
                            'value' => CHtml::link(
                                    CHtml::image(
                                            $model->logo, CHtml::encode('test'), array('max-height' => '150px',)
                                    ), $model->logo, array('target' => '_blank', 'class' => 'pretty')
                            ),
                        ),
                    ),
                ));
            }
            ?>
            <div class="row">
                <?php
                echo $form_view->labelEx($model, 'description');
                $this->widget('application.components.SRichTextarea', array(
                    'model' => $model,
                    'attribute' => 'description'));
                ?>
            </div>       
            <?php
            // Fancybox ext
            $this->widget('application.extensions.fancybox.EFancyBox', array(
                'target' => 'a.pretty',
                'config' => array(),
            ));

            $this->endWidget();
            // Image
            ?>
        </div>
    </div>

    <!--    <div class="right">
    <?php echo $this->sidebarContent; ?>
        </div>-->
</div>
<script>
    $(function () {
        alertify.set({delay: 6000});
<?php if ($alertMsg) : ?>
            alertify.success('<?php echo $alertMsg; ?>');
<?php elseif ($errorMsg): ?>
            alertify.error('<?php echo $errorMsg; ?>');
<?php endif; ?>

        $("#save-btn").click(function () {
            $('form').submit();
        });

        $('#close-btn').click(function () {
            document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/productmaker/";
        });
    });
</script>

