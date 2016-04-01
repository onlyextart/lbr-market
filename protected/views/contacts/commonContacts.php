<?php
Yii::app()->clientScript->registerCssFile('/css/front/form.css');
?>

<?php 
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
<?php if(Yii::app()->user->hasFlash('error')):?>
    <div class="info error">
        <a href="<?php echo Yii::app()->getBaseUrl(true).'/contacts/#contact_form'?>">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </a>
    </div>
<?php endif; ?>
<div id="contactsCommon-wrapper">  
    <!--<h1 class="contacts-header">Контакты ЛБР-АгроМаркет</h1>-->
    <ul class="contacts_list clearfix">
    <a name="list_filials"></a>
    <?php

    $districts=ContactsController::getDistricts();

    foreach ($districts as $districtId => $districtName) {
        $div_clearfix='';
        $regions_in_distinct=ContactsController::getFilialsInDistrict($districtId);
        if(count($regions_in_distinct) == 1) 
        {
            echo '<div class="region-group region-one"><div class="region-group-name">'.$districtName.'</div>';
        }
        else 
        {
            echo '<div class="region-group"><div class="region-group-name">'.$districtName.'</div>';
            $div_clearfix='<div class="clearfix"></div>';
        }
        if(isset($regions_in_distinct)){
            foreach($regions_in_distinct as $region) { ?>
            <div class="contact-item">
                <div class="city-name"><?php echo CHtml::link($region['name'], '/contacts/'.$region['alias'].'/')?></div>
                <div class="contact-image"><img src="<?php echo 'http://www.lbr.ru/images/ContactsImages/'.$region['alias'].'/main.jpg'?>" /></div>
                <div class="contact-info" itemscope="address" itemtype="http://schema.org/LocalBusiness">
                    <meta itemprop="url" content="lbr-market.ru" />
                    <meta itemprop="name" content="ЛБР-АгроМаркет" />
                    <time itemprop="openingHours" datetime="Mo-Su 08:00-17:00" /></time>
                    <div itemprop="description">
                        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <meta itemprop="addressCountry" content="Россия" />
                                <div><b>Адрес: </b><?php echo $region['address']?></div>
                        </div>
                    </div>
                    <div><b>Телефон: </b><?php echo $region['telephone']?></div>
                    <div itemprop="email"><b>Email: </b><?php echo $region['email']?></div>
                 </div>
            </div>
            <?php 
            }
        }
        echo '</div>'.$div_clearfix;
    }

    ?>
    </ul>
     <!-- Map of Russia -->
    <div class="contact-additional-info">
        <a class="view_all_contacts" href="<?php echo Yii::app()->getBaseUrl(true).'/contacts/#list_filials'?>">Посмотреть все контакты</a>
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="800" height="400">
            <param name="movie" value="/images/map1000px.swf">
            <param name="bgcolor" value="#ffffff">
            <!--[if !IE]>-->
            <object type="application/x-shockwave-flash" data="/images/map1000px.swf" width="800" height="400">
                    <!--<![endif]-->
                    <!--[if !IE]>-->
                    <param name="wmode" value="opaque">
            </object>
            <!--<![endif]-->
        </object>
    </div>
    <div class="contact_form contact_form_common form">
        <a name="contact_form"></a>
        <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'contact-form',
                //'name' => 'contact_form',
                //'enableAjaxValidation'=>true,
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    //'afterValidateAttribute'=>'js:'.$updateCaptcha,
                    //'afterValidate'=>'js:'.$updateCaptcha,
                ),
        //        'htmlOptions'=>array(
        //            'name' => 'contact_form',
        //        ),
            ));

        ?>
        <h2>Форма обратной связи</h2>

	<?php echo $form->errorSummary($formModel); ?>

	<div class="row">
            <?php echo $form->labelEx($formModel, 'name'); ?>
            <?php echo $form->textField($formModel, 'name', array('class'=>'contact_form_field')); ?>
            <?php echo $form->error($formModel, 'name'); ?>
	</div>
        
	<div class="row">
            <?php echo $form->labelEx($formModel, 'company'); ?>
            <?php echo $form->textField($formModel, 'company', array('class'=>'contact_form_field')); ?>
            <?php echo $form->error($formModel, 'company'); ?>
	</div>
        
	<div class="row">
            <?php echo $form->labelEx($formModel, 'phone'); ?>
            <?php echo $form->textField($formModel, 'phone', array('class'=>'contact_form_field')); ?>
            <div class="note">пример: +7(4722)402104</div>
            <?php echo $form->error($formModel, 'phone'); ?>
	</div>
        
        <div class="row">
            <?php echo $form->labelEx($formModel, 'email'); ?>
            <?php echo $form->textField($formModel, 'email', array('class'=>'contact_form_field')); ?>
            <?php echo $form->error($formModel, 'email'); ?>
	</div>
        
        <div class="row">
            <?php echo $form->labelEx($formModel, 'region'); ?>
            <?php echo $form->dropDownList($formModel, 'region', $regions, array('empty'=>'', 'class'=>'contact_form_field')); ?>
            <?php echo $form->error($formModel, 'region'); ?>
	</div>
        
	<div class="row">
            <?php echo $form->labelEx($formModel, 'mailTo'); ?>
            <?php echo $form->dropDownList($formModel, 'mailTo', ContactForm::$mailToArray, array('empty'=>'', 'class'=>'contact_form_field')); ?>
            <?php echo $form->error($formModel, 'mailTo'); ?>
	</div>

	<div class="row">
            <?php echo $form->labelEx($formModel, 'body'); ?>
            <?php echo $form->textArea($formModel, 'body', array('rows'=>6, 'cols'=>50, 'style' => 'resize:none')); ?>
            <?php echo $form->error($formModel, 'body'); ?>
	</div>
        
        <?php if(CCaptcha::checkRequirements()): ?>
            <div class="row">
                <?php echo $form->labelEx($formModel, 'verifyCode'); ?>
                <?php echo $form->textField($formModel, 'verifyCode', array('class'=>'verifyCode_form_field')); ?>
                <?php echo $form->error($formModel, 'verifyCode'); ?>
                <div id='pict_captcha'>
                    <?php 
                        $this->widget('CCaptcha', 
                            array(
                                'captchaAction' => 'site/captcha',
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
<?php 
    Yii::app()->clientScript->registerScript('refresh-captcha', '$(document).ready(function(){$("#yw0").click();});');

