<?php
    $name = 'Запчасти, на которые нет цен';
    $this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin/'),
        'Каталог'=>Yii::app()->createUrl(''),
        $name
    );
?>
<h1><?php echo $name; ?></h1>
<div class="total">
    <div class="left">
        <div class="form wide padding-all user-wrapper">
        <?php 
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'noprice-form',
                //'action'=>$action,
                /*'enableClientValidation' => true,        
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange' => true,
                    'afterValidate'=>'js:function( form, data, hasError ) 
                    {     
                        if( hasError ){
                            return false;
                        }
                        else{
                            return true;
                        }
                    }'
                ),*/
               
            ));
    
            $tabs=array(
                'Вообще нет цен'=>$this->renderPartial('_all', array('form'=>$form, 'data'=>$data), true),
                //'Нет цен на отдельные филиалы' => $this->renderPartial('_few', array('form'=>$form, 'data'=>$notAll), true),
            );
            
            /*$errorSummary = $form->errorSummary($model_form)."\n";
            foreach ($tabs as &$tab)
                $tab = $errorSummary.$tab;*/

            $this->beginWidget('ext.yiiext.sidebartabs.SAdminSidebarTabs', array('tabs'=>$tabs));
            $this->endWidget();
            $this->endWidget(); 
        ?> 
        </div>
    </div>
    <div class="right">
        <!--h1>Дополнительно</h1-->
        <?php /*if(count($orders) > 0): ?>
        <a href="#">Заказов: <?php echo count($orders) ?></a>
        <?php else: ?>
        <div class="user-order">Заказов: <?php echo $orderCount ?></div>
        <?php endif; */?>
        
        <?php echo $this->sidebarContent; ?>
    </div>
</div>