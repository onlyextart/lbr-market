<?php  
$mess = Yii::app()->user->getFlash('message');
$error = Yii::app()->user->getFlash('error');
?>
<script>
     alertify.set({ delay: 6000 });
        <?php if ($mess) :?>
            alertify.success('<?php echo $mess; ?>');
        <?php endif; ?>
        <?php if ($error) :?>
            alertify.error('<?php echo $error; ?>');
        <?php endif; ?>
</script>

<?php    
    if(!empty($bestoffer)) {
        echo $bestoffer;
    }
?>
<div id="shop-description">
        <div><span>Lbr-market.ru </span>  – это электронный магазин запчастей для сельхозтехники, предоставляющий широкий ассортимент продукции (более 60 000 позиций от отечественных и импортных производителей). Вы всегда подберете здесь товар, который будет отличаться высоким качеством и приемлемой стоимостью.</div>
        <div>Продуманная структура сайта, позволяет осуществлять быстрый поиск нужной позиции. Все товары в <span>&laquo;ЛБР-АгроМаркет&raquo; </span> классифицированы по виду техники и производителю. К каждой сельхоз запчасти прилагается техническое описание, информация о производителе и изображение на линейке, что позволяет сопоставить реальные размеры детали.</div>
        <div>Магазин сельхоз запчастей стремится соответствовать требованиям каждого заказчика. Достаточно сделать заказ, а об остальном позаботимся мы. </div>
    </div>
<?php 
    if(!empty($hitProducts)) {
        echo $hitProducts;
    }
?>
<div class="clearfix"></div>
<?php
    if(!empty($makers)) {
        echo $makers;
    }
?>
<div class="clearfix"></div>