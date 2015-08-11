<div class="order-wrapper">
    <?php if($mess = Yii::app()->user->getFlash('message')): ?>
    <div class="flash_messages">
        <?php echo $mess; ?>
    </div>
    <?php //echo Yii::app()->user->setFlash('message', ''); ?>
    <?php endif; ?>
    <div class="order-header">
	<h1>Заказ <span>от <?php echo date("Y.m.d H:i", strtotime($order->date_created)) ?></span></h1>
    </div>
    <?php if($showLabelForNoPrice): ?>
    <div class="cart-label-no-price">
        Стоимость запчастей без цены будет указана в счет-фактуре.
    </div>
    <?php endif; ?>
    <div class="ordered-products">
        <table width="100%">
	   <thead>
	      <tr>
		 <td></td>
		 <td>Количество</td>
		 <td>Сумма</td>
	      </tr>
	   </thead>
	   <tbody>
		<?php foreach($items as $item): ?>
		<tr>
                    <td>
                        <h3><?php echo CHtml::link($item->product->name, $item->product->path, array('target'=>'_blank'));?></h3>
                        <?php
                        echo CHtml::openTag('div', array('class'=>'price'));
                        if(Yii::app()->params['showPrices']) {
                           echo ($item->price)? Price::model()->setPriceFormat($item->price*$item->currency).' руб.' : Yii::app()->params['textNoPrice'];
                        } else echo Yii::app()->params['textHidePrice']; 
                        echo CHtml::closeTag('div');
                        ?>
                    <td>
                        <?php echo $item->count ?>
                    </td>
                    <td>
                        <?php
                        echo CHtml::openTag('span', array('class'=>'price'));
                        if(Yii::app()->params['showPrices']) {
                           echo ($item->price)? $item->total_price.' руб.':Yii::app()->params['textNoPrice'];
                        } else echo Yii::app()->params['textHidePrice']; 
                        echo CHtml::closeTag('span');
                        ?>
                    </td>
		</tr>
		<?php endforeach ?>
           </tbody>
	</table>
        <div class="order-user-info">
            <div>
                <h2>Данные получателя</h2>
                <div class="form wide">
                    <div class="row"> Доставка: <?php echo ($order->delivery->name)? $order->delivery->name : ''; ?> </div>
                    <div class="row"> <?php echo (!empty($order->user_name)) ? $order->user_name : $order->user->name; ?> </div>
                    <div class="row"> <?php echo (!empty($order->user_email)) ? $order->user_email : $order->user->email; ?> </div>
                </div>
            </div>
        </div>
        <div class="recount">
            <span class="total">Итого:</span>
            <span id="total-price">
            <?php 
            if(Yii::app()->params['showPrices']) {
               echo $total; 
            } else echo '<div>'.Yii::app()->params['textHidePrice'].'</div>'; 
            ?>
            </span>
        </div>
    </div>
</div>
