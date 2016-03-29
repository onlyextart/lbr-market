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
         <!--<link rel="stylesheet" href="https://www.sipnet.ru/bundles/artsoftemain/css/webrtc_client.css" />-->
        <script>
            var lbrAnaliticsMark = "<?php echo Yii::app()->params['analiticsMark']; ?>";
        </script>
        <?php
            Yii::app()->clientScript->registerCssFile('/distribution/css/styles.min.css?70');
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile('/distribution/js/scripts.min.js?12');

            if(empty(Yii::app()->request->cookies['lbrfilial'])) {
                $id = Filial::model()->find('lower(name) like lower("%Москва%")')->id;
                $cookie = new CHttpCookie('lbrfilial', $id);
                $cookie->expire = time() + 60*60*24*30*12; // year
                Yii::app()->request->cookies['lbrfilial'] = $cookie;
            }
            
            $allFilials = Filial::model()->findAll(array('condition'=>'level != 1'));
            $filial = Yii::app()->request->cookies['lbrfilial']->value;
        ?>
    </head>
    <body>
        <header>
            <div class="logo">
                <a href="http://lbr-market.ru/">
                    <img src="/images/logo.png" title="ЛБР-Агромаркет" alt="Логотип ЛБР-АгроМаркет"/>
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
                    <li>
                        <a href="http://www.lbr.ru/finance/products/">
                            <img src="/images/mainMenuIcon/toppict_finance.png" alt="Финансирование">
                            <span>Финансовые<br>программы</span>
                        </a>
                    </li>
                    <div class="phone_main">
                        <div class="phone_text_help">ПОМОЩЬ В ПОДБОРЕ ЗАПЧАСТЕЙ</div>
                            <div class="phone_number">8-800-5553219</div>
                            <div class="phone_text">звонок бесплатный</div>
                        <div class="clearfix"><div>
                        <!--div class="fw-container__step__form__design-btn__body call">
                            <label for="design-btn-2" data-token="YY5JRWW8Z6Q13JR6J16DYRVYR1WDVG8V" data-dtmf="off" data-lang="ru" data-defautlText="null" data-endText="Завершить" class="fw-container__step__form__design-btn__label js-start_call fw-container__step__form__design-btn__label--2" style='background-color: #FFFFFF; color: #F39314'>
                                <span class="fw-container__step__form__design-btn__label__icon"></span>
                                <span class="js-text_call" id='call_button'>ЗАКАЗАТЬ ОБРАТНЫЙ ЗВОНОК</span>
                                <span class="fw-container__step__form__design-btn__label__icon2"></span>
                            </label>
                        </div-->
                        <!--div class="call">
                            <label>
                                <span id='call_button'>ЗАКАЗАТЬ ОБРАТНЫЙ ЗВОНОК</span>
                            </label>
                        </div-->
                    </div>
                </ul>
            </div>
    <!--         <div class="header-label">
                <ul class="label-list">
                    
                   <li>
                        <a href="http://www.lbr.ru/company/" title="О компании">О компании</a>
                    </li>
                    <li>
                        <a href="/search/show/" title="Поиск">Поиск</a>
                    </li>
                </ul>
            </div>-->
            
            <div class="map">
<!--                 <a href="http://www.lbr.ru/company/" title="О компании"><span>О КОМПАНИИ</span></a>
                 <a onclick="ga('send', 'event', 'action','contacts'); yaCounter30254519.reachGoal('contacts'); return true;" href="http://www.lbr.ru/company/contacts/">
                   <img src="/images/map.jpg" title="Контакты ЛБР-Агромаркет" alt="ЛБР-Агромаркет контакты"/>
                </a>-->
                <a onclick="ga('send', 'event', 'action','contacts'); yaCounter30254519.reachGoal('contacts'); return true;" href="http://www.lbr.ru/company/contacts/">
                    <span>Наши филиалы</span>
                    <img src="/images/map.jpg" title="Контакты ЛБР-Агромаркет" alt="ЛБР-Агромаркет контакты"/>
                </a>
            </div>
            <div class="main-menu">
                <ul id="nav">
                    <li><a href="/"><span>Главная</span></a></li>
                    <li><a href="/seasonalsale/"><span>Спецпредложения</span></a></li>
                    <li><a href="/sale/"><span>Распродажа</span></a></li>
                    <li><a href="/partners/"><span>Наши партнеры</span></a></li>
                    <li>
                        <a href="#"><span>Информация</span></a>
                        <ul class="submenu">
                            <!--<li><a href="/site/aboutus/">Мы online</a></li>-->
                            <li><a href="/payment/">Условия и оплата</a></li>
                            <li><a href="/delivery/">Доставка</a></li>
                            <li><a href="/garantiya/">Гарантия</a></li>
                            <li>
                                <a href="http://www.lbr.ru/company/" title="О компании"><span>О компании</span></a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="http://www.lbr.ru/company/contacts/"><span>Контакты</span></a></li>
                </ul>
            </div>
            <div class="menu-line">
                <ul class="menu-line-items">
                   <li id="menu-item-region">
                       <?php if (Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop))): ?>
                           <?php
                           if (!empty($filial)):
//                               $filial = Filial::model()->findByPk($filial)->name;
                               ?>
                               <div id="region-label" class="elem">Ваш филиал: 
                                   <!--<span id="region"><?php //echo $filial ?></span>-->
                                   <?php echo CHtml::dropDownList('select_region',$filial,CHtml::listData($allFilials,'id','name'),array('id'=>'select_region')); ?>
                               </div>
                           <?php else: ?>
                               <div id="region-label" class="elem"></div>  
                               <!--<div id="region-label" class="elem">Ваш филиал: <span id="region">Не выбран</span></div>-->
                           <?php endif; ?>
                           <?php else: ?>
                               <?php if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)): ?>
                               <div id="region-label" class="elem reg-user"><span>Ваш филиал:</span><a href="/user/cabinet/index/" id="select_region_link">
                                   <?php echo User::model()->getFilialName(Yii::app()->user->_id);?></a><span id="arrow"></span>
                               </div> 
                               <?php else: ?>
                                    <div id="region-label" class="elem"></div> 
                               <?php  endif; ?>
                       <?php  endif; ?>  
                    </li>
                    
                    <li id="menu-item-search-input">
                        <div class="search-input elem">
                            <div class="form-search-wrapper">
                            <form id="form_search" method="post">
                                <span>ПОИСК</span>
                                <input id="search" type="text" name="q" autocomplete="off"/>
                                <ul class="quick-result"></ul> 
                                <input class="search-button" type="button" value=""/>
                            </form>
                            </div>
                        </div>
                    </li>
                     <?php  
                        # Подключаем файл
                        if (!Yii::app()->user->isGuest && Yii::app()->user->isShop)
                            echo '<li class="login-elem"><div class="elem"><a href="/user/cabinet/index/">Кабинет</a></div></li>';
                        else if(Yii::app()->user->isGuest)
                            echo '<li class="login-elem"><div class="elem"><a href="/user/cabinet/index/">Вход / Регистрация</a></div></li>';
                        else 
                            echo '<li class="login-elem"><span class="empty-menu"></span></li>';

                    ?>
                </ul>
            </div>
<!--            <div class="main-menu">
                <ul id="nav" class="dropdown">
                    <li><a href="/"><span>Главная</span></a></li>
                    <li><a href="/sale/"><span>Распродажа</span></a></li>
                    <li><a href="/seasonalsale/"><span>Спецпредложения</span></a></li>
                    <li><a href="/payment/"><span>Условия и оплата</span></a></li>
                    <li><a href="/garantiya/"><span>Гарантия</span></a></li>
                    <li><a href="/delivery/"><span>Доставка</span></a></li>
                    <?php   

                        # Подключаем файл
//                        if (!Yii::app()->user->isGuest && Yii::app()->user->isShop)
//                            echo '<li class="last"><a href="/user/cabinet/index/"><span>Кабинет</span></a></li>';
//                        else if(Yii::app()->user->isGuest)
//                            echo '<li class="last"><a href="/user/cabinet/index/"><span>Вход / Регистрация</span></a></li>';
//                        else 
//                            echo '<li class="last empty-li"><span class="empty-menu"></span></li>';

                    ?>
                </ul>
            </div>-->
        </header>
        <div class="page-overlay" style="display: none"><div><span>Изменение филиала...</span><span class="loader"></span></div></div>
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
        
        <!--div id="window_call">
            <div id="modal_form_call">
                <div id="modal_title" style="margin:20px 0px 20px 0px;">
                    <span style="font-family: 'Trebuchet MS';font-size:20px;font-weight:bold; color:#F39314;text-transform: uppercase;">
                        Бесплатный Web-звонок
                    </span>
                </div>
                <div id='form_call'>
                    <form method="POST" action="http://customer.voipexchange.ru/cgi-bin/Exchange.dll/FreeWebCall">
                        <input type=hidden name="ID" value="92465201823405422891005848819646">
                        <input type=hidden name="Delay" value="0">
                        <div class="row"><label for="phone">Ваш телефон, включая код страны и города<br> <span class='example'>например, 79102345678</span></label></div>
                        <div class="row"><input type="text" name="phone" maxlength="32" size="16"></div>
                        <div class="row button"><input class="buttonform" type="submit" value="ПОЗВОНИТЬ"></div>
                    </form>
                </div>
            </div>
        </div-->
        <!-- OnlineSeller.ru {literal} -->
             <!--script type="text/javascript">var _oaq = _oaq || [];_oaq.push(['_OPAccount', '716']);(function() {var oa = document.createElement('script'); oa.type = 'text/javascript';oa.charset='UTF-8'; oa.async = true; oa.src = 'http://onlinesaler.ru/assets/templates/os2013/common/js.php?akkid=716'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(oa, s);  })();</script-->
        <!--OnlineSeller.ru {/literal} -->
        
        <!-- BEGIN JIVOSITE CODE {literal} -->
            <script type='text/javascript'>
            (function(){ var widget_id = 'ghN05azA7B';
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
        <!-- {/literal} END JIVOSITE CODE -->

                                        

<!--        <div>
            <?php //$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
//                'id' => 'setRegion',
//                'options' => array(
//                    'title' => 'Выбор филиала',
//                    'autoOpen' => false,
//                    'modal' => true,
//                    'resizable'=> false,
//                ),
//            ));
            ?>
            <div class="row">
                <?php
                //echo CHtml::dropDownList('select-region', '', array());
                ?>
            </div>
            <div class="reg-button">
            <?php //echo CHtml::button('Подтвердить',array('id' => 'confirm-region', 'class' => 'btn')); ?>
            </div>
            <?php
                //$this->endWidget('zii.widgets.jui.CJuiDialog');
            ?>
        </div>-->
        <!------ Teg for remarketing ------>
        <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 896868792;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
        </script>
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
        </script>
        <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/896868792/?value=0&amp;guid=ON&amp;script=0"/>
        </div>
        </noscript>
        <!------ end Teg for remarketing ------>
    </body>
</html>
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
<!-- Обратный звонок-->
<!--<script type="text/javascript">-->
<!--//                (function(){
//                    var s = document.createElement("script");
//                    s.type = "text/javascript";
//                    s.async = true;
//                    s.src = "https://www.sipnet.ru/bundles/artsoftemain/js/frontend/modules/webrtc_client.js";
//                    var ss = document.getElementsByTagName("script")[0]; ss.parentNode.insertBefore(s, ss);
//                })();-->

<!--</script>-->
