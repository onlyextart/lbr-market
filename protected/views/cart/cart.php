<div class="cart-wrapper">
    <div class="breadcrumbs">
        <?php
            $breadcrumbs[] = 'Корзина';
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;  
            $this->widget('zii.widgets.CBreadcrumbs', array(
                'links' => Yii::app()->params['breadcrumbs'],
                'activeLinkTemplate' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="{url}">{label}</a></span>',
                'inactiveLinkTemplate' => '{label}',
                'homeLink' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="/">Главная</a></span>',
                'tagName' => 'span',
                'htmlOptions' => array(
                    'xmlns:v' => 'http://rdf.data-vocabulary.org/#',
                ),
            ));
            $action = '/cart/index/';
        ?>
    </div>
    <?php if($mess = Yii::app()->user->getFlash('error')): ?>
    <div class="flash_error">
        <?php echo $mess; ?>
    </div>
    <?php //echo Yii::app()->user->setFlash('message', ''); ?>
    <?php endif; ?>
    <div class="cart-header">
       <h1>Корзина</h1>
    </div>
    <?php if(empty($items)): ?>
    <div class="empty-cart">В корзине нет товаров.</div>
    <?php else: ?>
    <?php echo CHtml::form() ?>
    <div class="order_products">
        <table width="100%">
           <thead>
              <tr>
                 <td></td>
                 <td></td>
                 <td>Количество</td>
                 <td>Сумма</td>
                 <td>Удалить</td>
              </tr>
           </thead>
           <tbody>
                <?php if(!Yii::app()->user->isGuest): ?>
                    <?php 
                       foreach($items as $item): 
                       $price = $this->getProductPrice($item->product->id, $item->count);
                    ?>
                    <tr>
                        <td width="110px" align="center">
                            <?php
                            $image = '/images/no-photo.png';
                            if(!empty($item->product->image)) $image = 'http://api.lbr.ru/images/shop/spareparts/'.$item->product->image;
                            ?>
                            <a href="<?php echo $image ?>" class="thumbnail" target="_blank">
                                <img src="<?php echo $image ?>" alt="<?php echo $item->product->name ?>"/>
                            </a>
                        </td>
                        <td width="220px">
                            <?php
                                echo CHtml::link($item->product->name, $item->product->path, array('target'=>'_blank'));
                                echo CHtml::openTag('span', array('class'=>'price'));
                                echo (Yii::app()->params['showPrices'])?$price['one']:'';
                                echo CHtml::closeTag('span');
                            ?>
                        </td>
                        <td width="120px">
                            <div class="minus">&minus;</div>
                            <?php //echo CHtml::textField("", $item->count, array('class'=>'count', 'maxlength'=>7, 'length'=>7)) ?>
                            <?php //echo CHtml::numberField('products['.$item->product->id.']', $item->count, array('class'=>'count', 'min'=>1, 'maxlength'=>7, 'length'=>7)) ?>
                            <?php echo CHtml::textField('products['.$item->product->id.']', $item->count, array('class'=>'count', 'min'=>1, 'maxlength'=>7, 'length'=>7)) ?>
                            <div class="plus">&plus;</div>
                        </td>
                        <td>
                            <?php
                                echo CHtml::openTag('span', array('class'=>'price'));
                                echo (Yii::app()->params['showPrices'])?$price['total']:'Информация о ценах в данный момент не доступна';
                                echo CHtml::closeTag('span');
                            ?>
                        </td>
                        <td width="20px">
                            <!--a class="remove" href="/cart/remove/<?php echo $item->order->id ?>/"></a-->
                            <a class="remove" href="/cart/remove<?php echo $item->product->path ?>"></a>
                        </td>
                    </tr>
                    <?php endforeach ?>
               <?php else: ?>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td width="110px" align="center">
                            <?php
                            echo CHtml::link(CHtml::image($item['img'], '', array('class'=>'thumbnail')), $item['img'], array('class'=>'thumbnail'));
                            ?>
                        </td>
                        <td width="210px" >
                            <?php
                            echo CHtml::link($item[name], $item[path]);
                            //echo CHtml::openTag('span', array('class'=>'price'));
                            //echo '<a href="/site/login/">Узнать цену</a>';
                            //echo CHtml::closeTag('span');
                            ?>
                        </td>
                        <td>
                            <div class="minus">&minus;</div>
                            <?php echo CHtml::textField("products[$item[id]]", $item[count], array('class'=>'count', 'maxlength'=>7, 'length'=>7)) ?>
                            <div class="plus">&plus;</div>
                        </td>
                        <td>
                            <?php
                            echo CHtml::openTag('span', array('class'=>'price'));
                            echo '<a class="price-link" href="/site/login/">Узнать цену</a>';
                            echo CHtml::closeTag('span');
                            ?>
                        </td>
                        <td width="20px">
                            <a class="remove" href="/cart/guestremove<?php echo $item[path] ?>"></a>
                        </td>
                    </tr>
                    <?php endforeach ?>
               <?php endif; ?>
           </tbody>
        </table>
        <?php if(!Yii::app()->user->isGuest && Yii::app()->params['showPrices']): ?>
        <div class="price recount-price">
            <button class="recount" type="submit" name="recount" value="1">Пересчитать</button>
            <span class="total">Всего:</span>
            <span id="total">
                <?php echo $total; ?>
            </span>
        </div>
        <?php endif; ?>
    </div>
    <?php if(!Yii::app()->user->isGuest): ?>
    <?php if(Yii::app()->params['showPrices']):?>
    <div class="order-data">
        <div class="delivery-type">
            <h2>Способ доставки</h2>
            <ul>
                <?php foreach($deliveryMethods as $delivery): ?>
                <?php //if($delivery->id == 1): ?>
                <li>
                        <label class="radio">
                                <?php
                                echo CHtml::activeRadioButton($this->form, 'delivery_id', array(
                                        'checked'        => ($this->form->delivery_id == $delivery->id),
                                        'uncheckValue'   => null,
                                        'value'          => $delivery->id,
                                        //'data-price'     => Yii::app()->currency->convert($delivery->price),
                                        //'data-free-from' => Yii::app()->currency->convert($delivery->free_from),
                                        //'onClick'        => 'recountOrderTotalPrice(this);',
                                ));
                                ?>
                                <span><?php echo CHtml::encode($delivery->name) ?></span>
                        </label>
                        <!--p><? echo $delivery->name?></p-->
                </li>
                <?php //endif; ?>
                <?php endforeach; ?>
                 <!--li>
                   <label class="radio">
                      <input type="radio" id="OrderCreateForm_delivery_id" name="OrderCreateForm[delivery_id]" value="1">						
                      <span>Самовывоз</span>
                   </label>
                </li>
                <li>
                   <label class="radio">
                      <input type="radio" id="OrderCreateForm_delivery_id" name="OrderCreateForm[delivery_id]" value="2">						
                      <span>Курьером</span>
                   </label>
                </li-->
            </ul>
        </div>
        <div class="guest-data">
            <h2>Адрес получателя</h2>
            <div class="form wide">
                <?php echo CHtml::errorSummary($this->form); ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::activeLabel($this->form, 'user_name', array('required'=>true)); ?>
                        <?php echo CHtml::activeTextField($this->form,'user_name'); ?>
                    </div>

                    <div class="row">
                        <?php echo CHtml::activeLabel($this->form, 'user_email', array('required'=>true)); ?>
                        <?php echo CHtml::activeTextField($this->form,'user_email'); ?>
                    </div>

                    <div class="row">
                        <?php echo CHtml::activeLabel($this->form, 'user_phone', array('required'=>true)); ?>
                        <?php echo CHtml::activeTextField($this->form,'user_phone'); ?>
                    </div>

                    <div class="row">
                        <?php echo CHtml::activeLabel($this->form, 'user_address', array('required'=>true)); ?>
                        <?php echo CHtml::activeTextArea($this->form,'user_address'); ?>
                    </div>

                    <div class="row">
                        <?php echo CHtml::activeLabel($this->form,'user_comment'); ?>
                        <?php echo CHtml::activeTextArea($this->form,'user_comment'); ?>
                    </div>  
                </div>

            </div>
        </div>
    </div>
    <div class="confirm_order">
        <h1>Всего к оплате:</h1>
        <span id="total-price" class="total">
            <?php echo $total; ?>
        </span>
        <button class="btn" type="submit" name="create" value="1">Оформить</button>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <div class="confirm_order">
        <input class="btn guestcart" type="button" value="Авторизоваться">
    </div>
    <?php endif; ?>
    <?php echo CHtml::endForm() ?>
    <?php endif; ?>
</div>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));
?>
<script>
    $(function() {
       $('.price-link').easyTooltip({content:'Авторизуйтесь, чтобы узнать цену'});

       $('.order_products .plus').click(function(event) {
          var elem = $(this).parent().find('.count');
          var count = parseInt(elem.val()) + 1;
          elem.val(count);
       });
       
       $('.order_products .minus').click(function(event) {
          var elem = $(this).parent().find('.count');
          var count = parseInt(elem.val()) - 1;
          if(count < 1) count = 1;
          elem.val(count);
       });
    });
</script>


