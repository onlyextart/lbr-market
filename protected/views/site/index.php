<?php
    $message = Yii::app()->user->getFlash('message');
    $errorMsg = Yii::app()->user->getFlash('error');
            
    if(!empty($bestoffer)) {
        echo $bestoffer;
    }
?>
<div id="shop-description">
    <div>За 15 лет работы <span>&laquo;ЛБР-АгроМаркет&raquo; </span> зарекомендовал себя надежным, заслуживающим доверие клиентов партнером в сфере поставки сельхозтехники и запчастей к ней. Компания продолжает развиваться, совершенствовать свои бизнес-процессы, завоевывая доверия новых клиентов.</div>
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
<script>
    $(function() {
        $('#carousel ul').carouFredSel({
            pagination: "#pager",
            items: 1,
            scroll: 2000,
        });
        
        $('#carousel-logo ul').carouFredSel({
            next: '#next-logo',
            prev: '#prev-logo',
            auto: {
                items           : 5,
                fx              :"scroll",
                easing          : "linear",
                duration        : 1000,
                pauseOnHover    : true,
            },
           pagination: "#pager-logo",
           items: 5,
        });
        
        // black and white for makers' photos
        /*if (navigator.userAgent.search(/Firefox/) > -1){
           $('.bwWrapper img').addClass('img_filter');
        }
        else{
           $('.bwWrapper').BlackAndWhite({
            hoverEffect: true,
            speed: {
                fadeIn: 50,
                fadeOut: 50,
            },
        });
       }*/
       
       //alertify.set({ delay: 6000 });
        <?php if ($message) :?>
            alertify.success('<?php echo $message; ?>');
        <?php elseif ($errorMsg) :?>
            alertify.error('<?php echo $errorMsg; ?>');
        <?php endif; ?>
    });
</script>