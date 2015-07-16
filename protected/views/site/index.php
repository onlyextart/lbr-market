<?php
    $Msg=Yii::app()->user->getFlash('message');
    $MsgErr=Yii::app()->user->getFlash('error')
?>

<?php if(!empty($bestoffer)): ?>
<div id="carousel-wrapper">
    <div id="carousel">
        <ul>
            <?php foreach($bestoffer as $offer):
                $link="/seasonalsale/index/id/".$offer->id;
                //if(file_exists(Yii::getPathOfAlias('webroot').$offer->img)){
            ?>
            <li><a href="<?php echo $link ?>"><img src="<?php echo $offer->img ?>" alt="<?php echo $offer->name ?>"></a></li>
            <?php //}  ?>
            <?php endforeach;  ?>
        </ul>
        <div class="clearfix"></div>
        <div id="pager" class="pager"></div>
    </div>
</div>
<?php endif; ?>
<div id="shop-description">
    <div>За 15 лет работы <span>&laquo;ЛБР-АгроМаркет&raquo; </span> зарекомендовал себя надежным, заслуживающим доверие клиентов партнером в сфере поставки сельхозтехники и запчастей к ней. Компания продолжает развиваться, совершенствовать свои бизнес-процессы, завоевывая доверия новых клиентов.</div>
    <div><span>Lbr-market.ru </span>  – это электронный магазин запчастей для сельхозтехники, предоставляющий широкий ассортимент продукции (более 60 000 позиций от отечественных и импортных производителей). Вы всегда подберете здесь товар, который будет отличаться высоким качеством и приемлемой стоимостью.</div>
    <div>Продуманная структура сайта, позволяет осуществлять быстрый поиск нужной позиции. Все товары в <span>&laquo;ЛБР-АгроМаркет&raquo; </span> классифицированы по виду техники и производителю. К каждой сельхоз запчасти прилагается техническое описание, информация о производителе и изображение на линейке, что позволяет сопоставить реальные размеры детали.</div>
    <div>Магазин сельхоз запчастей стремится соответствовать требованиям каждого заказчика. Достаточно сделать заказ, а об остальном позаботимся мы. </div>
</div>
<?php if(!empty($hitProducts)): ?>
<span class="hit-label-main">Хиты продаж</span>
<div class="best-sales">
    <?php foreach($hitProducts as $product): ?>
    <div class="one_banner">
       <h3><a target="_blank" href="<?php echo $product->path; ?>"><?php echo $product->name; ?></a></h3>
       <div class="img-wrapper">
           <a target="_blank" href="<?php echo $product->path; ?>">
              <img src="http://api.lbr.ru/images/shop/spareparts/<?php echo $product->image; ?>" alt="">
           </a>
       </div>
    </div>
    <?php endforeach;?>
</div>
<?php endif; ?>
<div class="clearfix"></div>
<div id="carousel-logo-wrapper">
    <div id="carousel-logo" class="jcarousel">
        <ul>
            <?php
                $path = Yii::getPathOfAlias('webroot'); 
                $makers=EquipmentMaker::model()->getAllMakers();
                foreach ($makers as $maker){
                        $link="/equipmentmaker/index/id/".$maker->id;
                        if(file_exists($path.$maker->logo)){
                            echo '<li><a href="'.$link.'"  target="_blank"><div class="img-container bwWrapper"><img src="'.$maker->logo.'" alt="" /></div></a></li>'; 
                        }
                 }
                   
                $makers_product=ProductMaker::model()->getAllMakers();
                foreach ($makers_product as $maker){
                    $link="/productmaker/index/id/".$maker->id;
                    if(file_exists($path.$maker->logo)){
                        echo '<li><a href="'.$link.'"  target="_blank"><div class="img-container bwWrapper"><img src="'.$maker->logo.'" alt="" /></div></a></li>'; 
                
                    }    
                }

            ?>
        </ul>
        <div class="clearfix"></div>
        <a id="prev-logo" class="prev" href="#">&lt;</a>
        <a id="next-logo" class="next" href="#">&gt;</a>
    </div>
</div>
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
        <?php if ($Msg) :?>
            alertify.success('<?php echo $Msg; ?>');
        <?php elseif ($MsgErr) :?>
            alertify.error('<?php echo $MsgErr; ?>');
        <?php endif; ?>
    });
</script>