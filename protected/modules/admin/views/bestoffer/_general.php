<?php
echo CHtml::openTag('div', array('class' => 'row'));
echo $form->labelEx($model, 'name');
echo $form->textField($model, 'name');
echo $form->error($model, 'name');
echo CHtml::closeTag('div');

echo CHtml::openTag('div', array('class' => 'row'));
echo $form->labelEx($model, 'published');
echo $form->dropDownList($model, 'published', array('0' => 'Нет', '1' => 'Да'));
echo $form->error($model, 'published');
if (!empty($model->id) && $model->published) {
    echo CHtml::link('Предварительный просмотр', '/seasonalsale/index/id/' . $model->id, array('class' => 'link_view', 'target' => '_blank'));
}
echo CHtml::closeTag('div');

echo CHtml::openTag('div', array('class' => 'row'));
echo $form->labelEx($model, 'level');
echo $form->textField($model, 'level');
echo $form->error($model, 'level');
echo CHtml::closeTag('div');

// Upload button
echo CHtml::openTag('div', array('class' => 'row'));
echo $form->error($model, 'img');
echo CHtml::label('Выберите изображение', 'img');
echo $form->fileField($model, 'img');
echo CHtml::label('*размер 770x250, не больше 1Мб', 'img', array('class' => 'note'));
echo CHtml::closeTag('div');

// Image
if (!empty($model->img)) {
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
                                $model->img, CHtml::encode('Изображение не найдено'), array('style' => 'max-height: 150px')
                        ), $model->img, array('target' => '_blank', 'class' => 'pretty')
                ),
            ),
        ),
    ));
}

echo CHtml::openTag('div', array('class' => 'row'));
echo $form->labelEx($model, 'description');
$this->widget('ext.elrtef.elRTE', array(
    'model' => $model,
    'attribute' => 'description',
    //'name' => 'text',
    'htmlOptions' => array('height' => '400'),
    'options' => array(
        'doctype' => 'js:\'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\'',
        'cssClass' => 'el-rte',
        'cssfiles' => array('css/elrte-inner.css'),
        'allowSource' => true,
        'lang' => 'ru',
        'height' => 400,
        'fmAllow' => true, //if you want to use Media-manager
        'fmOpen' => 'js:function(callback) {$("<div id=\"elfinder\" />").elfinder(%elfopts%);}', //here used placeholder for settings
        'toolbar' => 'maxi',
    ),
    'elfoptions' => array(//elfinder options
        'url' => 'auto', //if set auto - script tries to connect with native connector
        'passkey' => 'mypass', //here passkey from first connector`s line
        'lang' => 'ru',
        'dialog' => array('width' => '700', 'modal' => true, 'title' => 'Media Manager'),
        'closeOnEditorCallback' => true,
        'editorCallback' => 'js:callback'
    ),
        )
);

echo CHtml::closeTag('div');

// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
    'target' => 'a.pretty',
    'config' => array(),
));


