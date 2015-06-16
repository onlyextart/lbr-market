<?php
$cartCount = 0;
if(!Yii::app()->user->isGuest) {
    if(Yii::app()->user->isShop) {
        $user = User::model()->findByPk(Yii::app()->user->_id);
        $user->date_last_login = date('Y-m-d H:i:s');
        $user->save();
        
        $allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
        foreach($allOrdersInCart as $orderInCart){
            $cartCount += OrderProduct::model()->find('order_id=:order', array(':order'=>$orderInCart->id))->count;
        }
        
        $cartLabel = ' товаров';
        if($cartCount == 1) {
            $cartLabel = ' товар';
        } else if($cartCount == 2 || $cartCount == 3 || $cartCount == 4){
            $cartLabel = ' товарa';
        }
        
        $cartCount .= $cartLabel;
    } else {
        $user = AuthUser::model()->findByPk(Yii::app()->user->_id);
    }
    $name = (!empty($user->name))? $user->name : $user->login;
?>
    <div class='user-info'>
        <?php //echo '<span class="user-name"> Добро пожаловать, '.$user->name.'!</span>'; ?>
        <?php //echo '<span class="user-name"> Добро пожаловать!</span>'; ?>
        <?php echo 'Добро пожаловать, '.$name.'!'; ?>
    </div>
    <?php if(Yii::app()->user->isShop) { ?>
            <ul class="user-menu">
                <li><a href="/user/cabinet/index/">Кабинет</a>
                    <ul id="submenu" class="user-submenu">
                        <li><a href="/user/orders/show/">Мои заказы</a></li>
                        <li><a href="/user/wishlist/show/">Блокнот</a></li>
                    </ul>
                </li>
                <?php 
                $user_info=User::model()->findByPk(Yii::app()->user->_id);
                if((empty($user_info->parent))&&$user_info->organization_type==User::LEGAL_PERSON){ ?>
                    <li><a href="/user/contact/show/">Контактные лица</a></li>
                <?php } ?>
                <li><a href="/user/logout/" class="exit">Выход</a></li>
            </ul>
            <center>
                <?php echo CHtml::button('Отправить заявку', array('submit' =>array('/site/quickform/'), 'class'=>'buttonform')); ?>    
            </center>
            <a href="/cart/" class="cart"><img src="/images/cart.png" alt="Корзина"/></a>
            <div class="cart-label">В корзине <a id="cart-count" href="/cart/"><?php echo $cartCount ?></a></div>
    <?php }  else { ?>
                <ul class="user-menu">
                    <?php if(Yii::app()->user->checkAccess('shopRead')){ ?>
                        <li><a href="/admin/" class="admin">Административная панель</a></li>
                    <?php } ?>
                        <li><a href="/user/logout/" class="exit">Выход</a></li>
                </ul>
    <?php }
} else {
    if(!empty(Yii::app()->params['cart'])) {
        foreach(Yii::app()->params['cart'] as $item) {
            $cartCount += $item;
        }
    }
    
    $cartLabel = ' товаров';
    if($cartCount == 1) {
        $cartLabel = ' товар';
    } else if($cartCount == 2 || $cartCount == 3 || $cartCount == 4){
        $cartLabel = ' товарa';
    }

    $cartCount .= $cartLabel;
        
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
    )); ?>
    
    <div class="text_quickform">
        
        <p>Вы можете оформить заявку, и  в кратчайшие сроки наши специалисты произведут подбор запасных частей согласно заявке и указанным моделям техники.</p>
        
    </div>
    
    <div class="clearfix"></div>
    <center>
    <?php echo CHtml::button('Отправить заявку', array('submit' =>array('/site/quickform/'), 'class'=>'buttonform')); ?>    
    </center>
        <div class="clearfix"></div>
    <a href="/cart/" class="cart"><img src="/images/cart.png" alt="Корзина"/></a>
    <div class="cart-label">В корзине <a id="cart-count" href="/cart/"><?php echo $cartCount ?></a></div>
    
    
<?php $this->endWidget();    
}
?>
    
<?php if(!empty($sale)): ?>
<div>
    <div id="sale-block-wrapper">
        <h3><a href="/sale/">Распродажа</a></h3>
        <div id="sale-block">
            <ul>
                <?php
                    foreach($sale as $offer){
                        echo '<li>'.
                            '<div class="one_banner">
                                <h3>
                                   <a href="'.$offer[path].'" target="_blank">'.$offer[name].'</a>
                                </h3>
                                <div class="img-wrapper">
                                    <a href="'.$offer[path].'" target="_blank">
                                        <img class="main-img" alt="" src="http://api.lbr.ru/images/shop/spareparts/'.$offer[image].'">
                                        <img class="sale-label" alt="Скидка" src="/images/sale-label.png">
                                    </a>
                                </div>'.
                            '</div>'
                        . '</li>';
                    }
                ?>
            </ul>
            <div class="clearfix"></div>
            <a id="prev-logo-sale" class="prev" href="#">&lt;</a>
            <a id="next-logo-sale" class="next" href="#">&gt;</a>
            <!--div id="pager-logo-sale" class="pager"></div-->
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    $(document).ready(function($){
        var updateCart = function() {
            $.ajax({
                type: 'POST',
                url: '/cart/count',
                /*dataType: 'json',
                data:{
                    id: selector.val(),
                },*/
                success: function(data) {
                    console.log(data);
            }});  
        };

        var interval = 1000 * 10;//60 * 1; // every minute
        <?php //if():?>
                //setInterval(updateCart, interval);
        <?php //endif; ?>
        
    });
</script>
