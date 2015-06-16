<?php
    $Msg=Yii::app()->user->getFlash('message');
    $MsgErr=Yii::app()->user->getFlash('error')
?>
<script>
    $(function() {
        alertify.set({ delay: 6000 });
        <?php if ($Msg) :?>
            alertify.success('<?php echo $Msg; ?>');
        <?php elseif ($MsgErr) :?>
            alertify.error('<?php echo $MsgErr; ?>');
        <?php endif; ?>
       
    });
</script>
<?php $user=User::model()->findByPk(Yii::app()->user->_id);
    if($user->organization_type==User::LEGAL_PERSON){ ?>
<div class="contact-wrapper">
    <div class="contact-header">
        <h1>
            <?php echo Yii::app()->params['meta_title']; ?>
        </h1>
    </div>
    <div class="contact-data">
        <?php if(!empty($items)): ?>
                <div class="contact-grid">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>ФИО</td>
                                <td>Логин</td>
                                <td>Email</td>
                                <td>Телефон</td>
                                <td>Удалить</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($items as $item): ?>
                                <tr>
                                    <td>
                                        <?php
                                            echo CHtml::openTag('span');
                                            echo $item->name;
                                            echo CHtml::closeTag('span');
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo CHtml::openTag('span');
                                            echo $item->login;
                                            echo CHtml::closeTag('span');
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo CHtml::openTag('span');
                                            echo $item->email;
                                            echo CHtml::closeTag('span');
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo CHtml::openTag('span');
                                            echo $item->phone;
                                            echo CHtml::closeTag('span');
                                        ?>
                                    </td>
                            
                                    <td width="20px">
                                        <a class="remove" href="/user/contact/remove/id/<?php echo $item->id ?>"></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                          
                         </tbody>
                       </table>
            </div>
        <?php endif; ?>
    <div class='user'>
        <h3>Добавить контактное лицо</h3>
        <div class='form wide'>
            <?php
                $action = '/user/contact/show/';

                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'addUser-form',
                    'action'=>$action,
                    'htmlOptions'=>array('autocomplete'=>'off'),
            )); ?>
            
            <?php echo CHtml::errorSummary($model_form); ?>
            <div class="row">
                <?php echo $form->labelEx($model_form,'login'); ?>
                <?php echo $form->textField($model_form,'login'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model_form,'name'); ?>
                <?php echo $form->textField($model_form,'name'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model_form,'email'); ?>
                <?php echo $form->textField($model_form,'email'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model_form,'phone'); ?>
                <?php echo $form->textField($model_form,'phone'); ?>
            </div>
           
            <?php echo CHtml::submitButton('Сохранить', array('class'=>'btn')); ?>
            <div class="clearfix"></div>
            
   
        <?php $this->endWidget(); ?>
        </div>
    </div>
    </div>
</div>
<?php }?>