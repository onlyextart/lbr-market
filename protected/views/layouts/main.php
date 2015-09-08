<!DOCTYPE html>
<html>
    <head>
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta content="all" name="robots">
        <meta content="dynamic" name="document-state">
        <meta content="2 days" name="revisit-after">
        <meta content="Global" name="distribution">
        <meta http-equiv="pragma" content="no-cache">
        <meta name="description" content="<?php echo Yii::app()->params['meta_description']; ?>">
        <meta name=viewport content="width=device-width, initial-scale=1">
        <title><?php echo Yii::app()->params['meta_title']; ?></title>
        <link rel="shortcut icon" type="image/jpg" href="<?php echo Yii::app()->request->baseUrl.'/images/favicon.jpg';?>"/>
        <script>
            var lbrAnaliticsMark = "<?php echo Yii::app()->params['analiticsMark']; ?>";
        </script>
        <?php
            Yii::app()->clientScript->registerCssFile('/distribution/css/styles.min.css?7');
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile('/distribution/js/scripts.min.js');

            if(empty(Yii::app()->request->cookies['lbrfilial'])) {
                $id = Filial::model()->find('lower(name) like lower("%Москва%")')->id;
                $cookie = new CHttpCookie('lbrfilial', $id);
                $cookie->expire = time() + 60*60*24*30*12; // year
                Yii::app()->request->cookies['lbrfilial'] = $cookie;
            }
            
            $filial = Yii::app()->request->cookies['lbrfilial']->value;
        ?>
    </head>
    <body>
        <header>
            <div class="logo">
                <a href="http://www.lbr.ru/">
                    <img src="/images/logo.png" title="ЛБР-Агромаркет" alt="Логотип ЛБР-Агромаркет"/>
                </a>
            </div>
            <div class="header-main">
                <ul class="main-top">
                    <li>
                        <a href="http://www.lbr.ru/selskohozyaystvennaya-tehnika/type/">
                            <img src="/images/mainMenuIcon/toppict1.png" alt="Сельскохозяйственная техника">
                            <span>Сельхоз техника</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/">
                            <img src="/images/mainMenuIcon/toppict2.png" alt="Запчасти">
                            <span>Запчасти</span>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.lbr.ru/service/">
                            <img src="/images/mainMenuIcon/toppict3.png" alt="Сервисное обслуживание">
                            <span>Сервис</span>
                        </a>
                    </li>
                    <div class="phone_number">
                        <span class="phone_number">8-800-5553219</span>
                        <div class="phone_text">Помощь в подборе запчастей</div>                        
                    </div>
                </ul>
            </div>
            <div class="header-label">
                <ul class="label-list">
                    <li>
                        <a href="http://www.lbr.ru/company/" title="О компании">О компании</a>
                    </li>
                    <li>
                        <a href="/search/show/" title="Поиск">Поиск</a>
                    </li>
                </ul>
            </div>
            <?php if(Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop))): ?>
               <?php
                  if(!empty($filial)): 
                      $filial = Filial::model()->findByPk($filial)->name; 
               ?>
                  <div class="region-label">Ваш филиал: <span id="region"><?php echo $filial?></span></div>
               <?php else: ?>
                  <div class="region-label">Ваш филиал: <span id="region">Не выбран</span></div>
               <?php endif; ?>
            <?php endif; ?>
            <div class="map">
                <a onclick="ga('send', 'event', 'action','contacts'); yaCounter30254519.reachGoal('contacts'); return true;" href="http://www.lbr.ru/company/contacts/">
                    <span>Контакты</span>
                    <img src="/images/map.jpg" title="Контакты ЛБР-Агромаркет" alt="ЛБР-Агромаркет контакты"/>
                </a>
            </div>
            <div class="main-menu">
                <ul id="nav" class="dropdown">
                    <li><a href="/"><span>Главная</span></a></li>
                    <li><a href="/sale/"><span>Распродажа</span></a></li>
                    <li><a href="/seasonalsale/"><span>Спецпредложения</span></a></li>
                    <li><a href="/payment/"><span>Условия и оплата</span></a></li>
                    <li><a href="/garantiya/"><span>Гарантия</span></a></li>
                    <li><a href="/delivery/"><span>Доставка</span></a></li>
                    <?php   

                        # Подключаем файл
                        if (!Yii::app()->user->isGuest && Yii::app()->user->isShop)
                            echo '<li class="last"><a href="/user/cabinet/index/"><span>Кабинет</span></a></li>';
                        else if(Yii::app()->user->isGuest)
                            echo '<li class="last"><a href="/user/cabinet/index/"><span>Вход / Регистрация</span></a></li>';
                        else 
                            echo '<li class="last empty-li"><span class="empty-menu"></span></li>';

                    ?>
                </ul>
            </div>
        </header>
        <div class="wrapper">
            <div class="left-sidebar">
                <?php $this->widget('ext.menuChoice.MenuChoice'); ?>
            </div>
            <div class="content">
                <?php if (!Yii::app()->user->isGuest): ?>
                <noscript><div class="hide"></noscript>
                <?php endif; ?>
                <?php echo $content; ?>
                <?php if (!Yii::app()->user->isGuest): ?>
                <noscript></div></noscript>
                <?php endif; ?>
            </div>
            <div class="right-sidebar">
                <?php $this->widget('ext.userAccount.UserAccount'); ?>
            </div>
            
        </div>
        <div class="clearfix"></div>
        <footer>
            <div><?php echo Yii::app()->params['footerLabel']; ?></div>
        </footer>
        
        
        <!-- OnlineSeller.ru {literal} -->
             <script type="text/javascript">var _oaq = _oaq || [];_oaq.push(['_OPAccount', '716']);(function() {var oa = document.createElement('script'); oa.type = 'text/javascript';oa.charset='UTF-8'; oa.async = true; oa.src = 'http://onlinesaler.ru/assets/templates/os2013/common/js.php?akkid=716'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(oa, s);  })();</script>
        <!--OnlineSeller.ru {/literal} -->
                                        

        <div>
            <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                'id' => 'setRegion',
                'options' => array(
                    'title' => 'Выбор филиала',
                    'autoOpen' => false,
                    'modal' => true,
                    'resizable'=> false,
                ),
            ));
            ?>
            <div class="row">
                <?php
                echo CHtml::dropDownList('select-region', '', array());
                ?>
            </div>
            <div class="reg-button">
            <?php echo CHtml::button('Подтвердить',array('id' => 'confirm-region', 'class' => 'btn')); ?>
            </div>
            <?php
                $this->endWidget('zii.widgets.jui.CJuiDialog');
            ?>
        </div>
    </body>
</html>
<script>
    /*function loadJs(url) {
        var script  = document.createElement( 'script' );
        script.src  = url;
        script.type = 'text/javascript';
        document.getElementsByTagName( 'head' )[0].appendChild( script );
    }*/
    //loadJs("/js/alertify.min.js");
    //loadJs("/js/jquery.mCustomScrollbar.concat.min.js");
    //loadJs("/js/jquery.carouFredSel.min.js");
    //loadJs("/js/jquery.jcarousel.min.js");
    //loadJs("/js/jquery.dotdotdot.min.js");
    //loadJs("/js/jquery.dcjqaccordion.2.7.min.js");
    //loadJs("/js/easyTooltip.js");
    //loadJs("/js/jquery.cookie.min.js");
    //loadJs("/js/jquery.hoverIntent.minified.js");
    
    
    //loadJs("/js/front/search.js");
    //loadJs("/js/front/cart.js");
    //loadJs("/js/front/frontend.js");
    
//  $(function() {
        <?php 
        /* 
        * Share cookie to another user
        */
//        $cookies = Yii::app()->request->cookies;
//        if((Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && Yii::app()->user->isShop)) && (isset($cookies['ct']->value) || isset($cookies['sb']->value))): 
        ?>
//        $('a').each(function(index) {
//            var element = $(this);
//            var href = element.attr('href')+'?sb=<?php //echo $cookies['sb']->value ?>&ct=<?php //echo $cookies['ct']->value ?>';
//            element.attr('href', href);
//        });
        <?php //endif; ?>
//  });
</script>
<!----- Universal Analitics ----->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-63008189-1', 'auto');
  ga('send', 'pageview');

</script>
<!------ /Universal Analitics ----------->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter30254519 = new Ya.Metrika({id:30254519,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<!-- /Yandex.Metrika counter -->
