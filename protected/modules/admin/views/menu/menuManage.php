<?php
/*
 * $menuModel модель меню
 */
if($menuModel->isNewRecord){
    if( isset($_GET['rootId']) ){
        $rootModel = Category::model()->findByPk($_GET['Id']);
        if( $rootModel !== null){
            $pageHeader = 'Добавить новый пункт меню в "'.$rootModel->name.'"';
        }
        else{
            $pageHeader = 'Добавить новый пункт меню';
        }
    }
}
else{
    $pageHeader = 'Редактирование пункта меню "'.$menuModel->name.'"';
}
?>
<h2>
    <?php echo $pageHeader ?>
</h2>
<style>
    .admin_main_features{float:left; width:60%;}
    .admin_additional_features{float:left; width:40%;}
    .button-column img{width:16px; height:16px;}
</style>
<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
            'id'=>'menuItem_form',
            'enableClientValidation'=>true,
            'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'afterValidate'=>'js:function(form, data, hasError){
                                if(!hasError){
                                    $.ajax({
                                            "type":"POST",
                                            "url":$("#menuItem_form").attr("action"),
                                            "data":form.serialize(),
                                            "success":function(data){$("#test").html(data); setTimeout(function(){
                                                menuTreeView.updateTree('.$menuModel->isNewRecord.');
                                                alertify.success("Сохранено");
                                            }, 500);},
                                    });
                                }
                                else{
                                    alertify.error("Поля формы заполнены не верно");
                                }
                            }'
            ),
        )
    );?>
    <div class="admin_main_features">
        <div class="row">
            <?php echo $form->error($menuModel, 'name'); ?>
            <?php echo $form->labelEx($menuModel, 'name'); ?>
            <?php echo $form->textField($menuModel, 'name', array('style'=>'width:95%',)); ?>
        </div>
        <div class="row">
            <?php echo $form->error($menuModel, 'header'); ?>
            <?php echo $form->labelEx($menuModel, 'header'); ?>
            <?php echo $form->textField($menuModel, 'header', array('style'=>'width:95%',)); ?>
        </div>
        <div class="row">
            <?php echo $form->error($menuModel, 'alias'); ?>
            <?php echo $form->labelEx($menuModel, 'alias'); ?>
            <?php echo $form->textField($menuModel, 'alias', array('style'=>'width:95%',)); ?>
        </div>
        <div class="row">
            <?php echo $form->error($menuModel, 'icon'); ?>
            <?php echo $form->labelEx($menuModel, 'icon'); ?>
            <?php echo $form->textField($menuModel, 'icon', array('style'=>'width:95%',)); ?>
        </div>
        <div class="row">
            <?php echo $form->error($menuModel, 'published'); ?>
            <?php echo $form->labelEx($menuModel, 'published'); ?>
            <?php echo $form->checkBox($menuModel, 'published'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->error($menuModel, 'group_id'); ?>
            <?php echo $form->labelEx($menuModel, 'group_id'); ?>
            <?php echo $form->dropDownList($menuModel, 'group_id', MenuGroups::getGroupsArray()); ?>
        </div>
        <div class="row">
        <?php
            echo CHtml::submitButton($menuModel->isNewRecord? 'Создать':'Сохранить', 
                /*$form->action,
                array( 
                    'complete'=>'setTimeout(function(){menuTreeView.updateTree('.$menuModel->isNewRecord.');
                            alertify.success("Сохранено");
                        }, 500)', 
                    'liveEvents'=>false,
                ),*/
                array(
                    'id'=>'saveMenuItem'.rand(),
                    'class'=>'btn btn-green'
                )
            );
            echo CHtml::link('Закрыть', '', array('class'=>'btn del', 'onclick'=>'$("#menu_features").html(" ")'))
        ?>
        </div>
    </div>
    <div class="admin_additional_features">
        <label>Дополнительные параметры</label>
        <h3>Мета теги и SEO текст</h3>
        <div class="form admin_additional_features_content">
            <div class="row">
                <?php echo $form->error($menuModel, 'meta_description'); ?>
                <?php echo $form->labelEx($menuModel, 'meta_description'); ?>
                <?php echo $form->textField($menuModel, 'meta_description', array('style'=>'width:95%',)); ?>
            </div>
            <div class="row">
                <?php echo $form->error($menuModel, 'meta_title'); ?>
                <?php echo $form->labelEx($menuModel, 'meta_title'); ?>
                <?php echo $form->textField($menuModel, 'meta_title', array('style'=>'width:95%',)); ?>
            </div>
            <div class="row">
                <?php echo $form->error($menuModel, 'meta_keywords'); ?>
                <?php echo $form->labelEx($menuModel, 'meta_keywords'); ?>
                <?php echo $form->textField($menuModel, 'meta_keywords', array('style'=>'width:95%',)); ?>
            </div>
            <div class="row">
                <?php echo $form->error($menuModel, 'seo_text'); ?>
                <?php echo $form->labelEx($menuModel, 'seo_text'); ?>
                <?php echo $form->textarea($menuModel, 'seo_text', array('style'=>'width:95%', 'rows'=>6,)); ?>
            </div>
        </div>
        <h3>Содержимое пункта меню</h3>
        <div class="form admin_additional_features_content">
            <?php
                $menuModel->getItemContent();
            ?>
        </div>
    </div>
</div>
<?php  $this->endWidget();?>
<script>
    $(".admin_additional_features").accordion({ header: "h3" , collapsible: true, active:false, heightStyle: "content"});
</script>
