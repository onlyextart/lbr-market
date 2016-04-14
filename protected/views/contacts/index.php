<?php
/* @var $this ContactsController */
Yii::app()->clientScript->registerCssFile('/css/front/form.css');
Yii::app()->controller->createAction('captcha')->getVerifyCode(true);
$this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));   
?>
<?php if(Yii::app()->user->hasFlash('success')):?>
    <div class="info">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<div id="contact-wrapper">  
    <h1 class="contact-header"><?php echo $contactModel["name"] ?></h1>
    <div class="contact_info">
    <div itemscope itemtype="http://schema.org/LocalBusiness">
    <meta itemprop="url" content="www.lbr.ru" />
    <meta itemprop="name" content="ЛБР-АгроМаркет" />
    <span itemprop="description">
        <table>
            <tr>
                <td>
                    <img class="contact_icon" src="/images/contacts/addressIcon.png">
                </td>
                <td>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                    <meta itemprop="addressCountry" content="Россия" />
                    <?php echo 'Адрес: '.$contactModel["address"] ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <img class="contact_icon" src="/images/contacts/phoneIcon.png">
                </td>
                <td>                
                    <?php echo $contactModel["telephone"] ?>                
                </td>
            </tr>
            <tr>
                <td>
                    <img class="contact_icon" src="/images/contacts/workTimeIcon.png">
                </td>
                <td>
                    Время работы: <time itemprop="openingHours" datetime="Mo-Su <?php echo $contactModel['work_time'] ?>" >
                     <?php echo $contactModel["work_time"] ?>
                    </time>
                </td>
            </tr>
            <tr>
                <td>
                    <img class="contact_icon" src="/images/contacts/mailIcon.png">
                </td>
                <td>
                    <span itemprop="email"><?php echo $contactModel["email"] ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <img class="contact_icon pict" src="/images/contacts/infoIcon.png">
                </td>
                <td>
                    <?php echo $contactModel["info"] ?>
                </td>
            </tr>
        </table>
       </span>
    </div>

    </div>
    
   
    <div class="contact_map">
        <?php echo $contactModel["map_code"] ?>
    </div>
    <div class="contact_form form">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'contact-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
            )); ?>
            <h2>Форма обратной связи</h2>
            <p class="note">Поля отмеченные <span class="required">*</span> обязательны для заполнения.</p>

            <?php echo $form->errorSummary($formModel); ?>

            <div class="row">
                    <?php echo $form->labelEx($formModel,'name'); ?>
                    <?php echo $form->textField($formModel,'name', array('class'=>'contact_form_field')); ?>
                    <?php echo $form->error($formModel,'name'); ?>
            </div>

            <div class="row">
                    <?php echo $form->labelEx($formModel,'company'); ?>
                    <?php echo $form->textField($formModel,'company', array('class'=>'contact_form_field')); ?>
                    <?php echo $form->error($formModel,'company'); ?>
            </div>

            <div class="row">
                    <?php echo $form->labelEx($formModel,'phone'); ?>
                    <?php echo $form->textField($formModel,'phone', array('class'=>'contact_form_field')); ?>
                    <div class="note">пример: +7(4722)402104</div>
                    <?php echo $form->error($formModel,'phone'); ?>
            </div>

            <div class="row">
                    <?php echo $form->labelEx($formModel,'email'); ?>
                    <?php echo $form->textField($formModel,'email', array('class'=>'contact_form_field')); ?>
                    <?php echo $form->error($formModel,'email'); ?>
            </div>

            <div class="row">
                    <?php echo $form->labelEx($formModel,'body'); ?>
                    <?php echo $form->textArea($formModel,'body',array('rows'=>6, 'cols'=>50)); ?>
                    <?php echo $form->error($formModel,'body'); ?>
            </div>

            <?php if(CCaptcha::checkRequirements()): ?>
                <div class="row">
                    <?php echo $form->labelEx($formModel,'verifyCode'); ?>
                    <?php echo $form->textField($formModel,'verifyCode', array('class'=>'verifyCode_form_field','value'=>'')); ?>
                    <?php echo $form->error($formModel,'verifyCode'); ?>
                    <div id='pict_captcha'>
                        <?php 
                            $this->widget('CCaptcha', 
                                array(
                                    'captchaAction' => 'contacts/captcha',
                                    'clickableImage' => true, 
                                    'showRefreshButton' => false,
                                    'imageOptions'=>array('style'=>'border:none;cursor:pointer;',
                                        'alt'=>'Изображение с кодом валидации',
                                        'title'=>'Обновить код'
                                    )
                                )
                            );
                        ?>
                    </div>
                    
                </div>
            <?php endif; ?>

            <div class="row buttons">
                    <?php echo CHtml::submitButton('Отправить', array('class'=>'btn')); ?>
            </div>

    <?php $this->endWidget(); ?>

</div>
</div>