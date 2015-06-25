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
        <title><?php echo Yii::app()->params['meta_title']; ?></title>
        <link rel="shortcut icon" type="image/jpg" href="<?php echo Yii::app()->request->baseUrl.'/images/favicon.jpg';?>"/>
        <link rel="stylesheet" type="text/css" href="/css/front/frontend.css?<?php echo time(); ?>" />
        <link rel="stylesheet" type="text/css" href="/css/front/accordion.css" />
        <link rel="stylesheet" type="text/css" href="/css/front/jquery.mCustomScrollbar.css" />
        <link rel="stylesheet" type="text/css" href="/css/front/alertify/core.css" />
        <link rel="stylesheet" type="text/css" href="/css/front/alertify/default.css" />
        <?php
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerCssFile('/css/ui/jquery-ui-1.10.3.css');
            Yii::app()->clientScript->registerCssFile('/css/front/alertify/core.css');
            Yii::app()->clientScript->registerCssFile('/css/front/tip-darkgray/tip-darkgray.css');
            Yii::app()->clientScript->registerCssFile('/css/front/alertify/default.css');
            Yii::app()->clientScript->registerScriptFile('/js/front/frontend.js');
            Yii::app()->clientScript->registerScriptFile('/js/front/cart.js');
            Yii::app()->clientScript->registerScriptFile('/js/jquery.jcarousel.min.js');
            Yii::app()->clientScript->registerScriptFile('/js/jquery.carouFredSel.min.js');
            Yii::app()->clientScript->registerScriptFile('/js/jquery.dcjqaccordion.2.7.js');
            Yii::app()->clientScript->registerScriptFile('/js/jquery.hoverIntent.minified.js');
            Yii::app()->clientScript->registerScriptFile('/js/jquery.mCustomScrollbar.concat.min.js');
            Yii::app()->clientScript->registerScriptFile('/js/jquery.cookie.min.js');
            Yii::app()->clientScript->registerScriptFile('/js/alertify.min.js');
            Yii::app()->clientScript->registerScriptFile('/js/front/search.js');
            Yii::app()->clientScript->registerScriptFile('/js/jquery.dotdotdot.js');
            Yii::app()->clientScript->registerScriptFile('/js/easyTooltip.js');
            //Yii::app()->clientScript->registerScriptFile('/js/front/jquery.BlackAndWhite.min.js');
            //Yii::app()->clientScript->registerScriptFile('/js/jquery.inputmask-3.x/js/jquery.inputmask.js');
            //Yii::app()->clientScript->registerScriptFile('/js/jquery.inputmask-3.x/js/inputmask.js');
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
                    <?php $href = 'selskohozyaystvennaya-tehnika'; ?>
                    <li <?php if (is_numeric(strpos(mb_strtolower(Yii::app()->request->requestUri), $href)) ||
                            (Yii::app()->request->cookies['rootmenualias']->value == 'selskohozyaystvennaya-tehnika' && Yii::app()->params['currentMenuItem']->level == 5))
                        echo 'class="active"'
                        ?> >
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
                </ul>
            </div>
            <div class="header-label">
                <ul class="label-list">
                    <li>
                        <a href="http://www.lbr.ru/company/" title="О компании">О компании</a>
                    </li>
                    <!--li>
                        <a href="http://career.lbr.ru/" title="Карьера">Карьера</a>
                    </li-->
                    <li>
                        <a href="/search/show/" title="Поиск">Поиск</a>
                    </li>
                    <!--li>
                        <a href="/" title="Карьера">Вход</a>
                    </li-->
                </ul>
            </div>
            <!--div class="region-label">Ваш регион: <span id="region">Не выбран</span></div-->
            <div class="map">
                <a href="http://www.lbr.ru/company/contacts/">
                    <span>Контакты</span>
                    <img src="/images/map.jpg" title="Контакты ЛБР-Агромаркет" alt="ЛБР-Агромаркет контакты"/>
                </a>
            </div>
            <!--div id="main-menu"-->
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
            <!--/div-->
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
                                        

        <!--div>
            <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                'id' => 'setRegion',
                'options' => array(
                    'title' => 'Выбор региона',
                    'autoOpen' => false,
                    'modal' => true,
                    'resizable'=> false,
                ),
            ));
            ?>
            <div class="row">
                <?php
                   //echo CHtml::dropDownList('select-region', '2', $filials);
                ?>
            </div>
            <div class="reg-button">
            <?php echo CHtml::button('Подтвердить',array('id' => 'confirm-region', 'class' => 'btn')); ?>
            </div>
            <?php
                $this->endWidget('zii.widgets.jui.CJuiDialog');
            ?>
        </div-->
    </body>
</html>
<script>
    $(document).ready(function($){
        /*
        var setFilialName = getCookie('filial');
        if(!setFilialName){
            $("#setRegion").dialog("open");
        }
        */
    });
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