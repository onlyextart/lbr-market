<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'lbr-market',
    'sourceLanguage' => 'ru',
    'timeZone' => 'Europe/Minsk',
    'language' => 'ru',
    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.*',
        'application.helpers.*',
        'ext.YiiMailer.YiiMailer',
    ),

    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'user',
        'admin',
    ),
    'preload' => array('log'),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'loginUrl'=>array('site/login'),
            'allowAutoLogin' => true,
        ),
        /*'mail' => array(
            'class' => 'ext.Mailing.Mailer',
            'emailDefaults' => array(
            'sender' => 'YURY <extart@mail.ru>',
            'layout' => 'ext.Mailing.views.layout', //this is used by default. You can set any other layout for email letters
            ),
            'transport' => array(
            'class' => 'SmtpTransport' //DebugTransport, PhpTransport, SmtpTransport
            )
        ),*/
        'ih' => array(
            'class' => 'CImageHandler',
        ),
        'session' => array(
            //'class' => 'HttpSession',
            'autoStart'=>true,
            'autoCreateSessionTable'=>false, // запрещаем автосоздание таблицы в базе
            'class'=>'system.web.CDbHttpSession', // подключаем класс
            'connectionID'=>'db',  // идентификатор соединения с базой
            'sessionTableName' => 'guest_cart', // название таблицы
            'timeout'=>'10800', // 4 часа - время хранения данных в базе в секундах

        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            //'useStrictParsing' => true,
            'rules' => array(
                '<url:(garantiya|delivery|payment)>' => 'site/description',
                'product-maker/<id:\d+>' => '/description/maker',
                '<controller:(user)>/<_a:\w+>' => 'user/default/<_a>',
                '<controller:(site)>/<action:\w+>' => '<controller>/<action>',
                '<module:(admin)>' => '<module>',
                '<module:(admin)>/<controller:\w+>' => '<module>/<controller>',
                '<module:(admin)>/<controller>/<action>' => '<module>/<controller>/<action>',
                '<module:(admin)>/<controller>/<action>/id/<id:\d+>' => '<module>/<controller>/<action>',
                '<controller:(product)>' => '<controller>',
                '<controller:(product)>/<action>' => '<controller>/<action>',
                
                'modelline/index/id/<id:\d+>' => 'modelline/index',
                'modellines/index/id/<id:\d+>' => 'modellines/index',
                'model/show/id/<id:\d+>' => 'model/show',
                'search/show/input/<input:[\w_\/-\d\s]+>'=>'search/show',
                'seasonalsale/index/id/<id:\d+>' => 'seasonalsale/index',
                'equipmentmaker/index/id/<id:\d+>' => 'equipmentmaker/index',
                'productmaker/index/id/<id:\d+>' => 'productmaker/index',
                'cart/guestremove/<path:[\w_\/-\d]+>' => 'cart/guestremove',
                'cart/remove/<path:[\w_\/-\d]+>' => 'cart/remove',
                'wishlist/remove/<path:[\w_\/-\d]+>' => 'wishlist/remove',
                //'<controller:\w+>/<action:\w+>/page/<page:[\w_\/-]+>/*' => '<controller>/<action>',
                //'<_a:(feedback|help)>'=>'site/<_a>',
                /*'<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                */
                // custom  rule for url like '/type/maker/model-line/model/'
                array(
                    'class'=>'application.components.ShopUrlRule',
                    'connectionID'=> 'db',
                ),
            ),
        ),

        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../../../data/shop.db',
            'initSQLs' => array(
                'PRAGMA foreign_keys = ON',
            ),
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'db_auth' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../../../data/auth.db',
            'initSQLs' => array(
                'PRAGMA foreign_keys = ON',
            ),
        ),
        'db_lbr' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../../../data/lbr.db',
            'initSQLs' => array(
                'PRAGMA foreign_keys = ON',
            ),
        ),
        // uncomment the following to use a MySQL database
        /*
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=chat',
            'emulatePrepare' => true,
            'username' => 'mysql',
            'password' => 'mysql',
            'charset' => 'utf8',
        ),*/

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                /*array(
                    'class' => 'CWebLogRoute', 'levels' => 'info, error, warning',
                ),*/
                array(
                    'class' => 'CFileLogRoute', 'levels' => 'info, error, warning',
                ),
            )
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db_auth',
        ),
        'search'=>array(
            'class'=>'SearchComponent',
        )
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName'] 
    'params' => array(
        // this is used in contact page
        //'host'=>'lbr.test',
        'host'=>'lbr-market.ru',
        'admin_email'=>'shop@lbr.ru',
        'maxInCart' => 5, // count of product types
        'region'=>'',
        'currentType' => '',
        'currentMaker' => '',
        'currentSale' => '',
        'searchFlag' => '',
        
        'sortOrder' => 'asc',
        'sortCol' => 'col',
        
        'showDrafts' => 0,
        'showPrices' => 0,
        'showPricesForAdmin' => 1, // will run if showPrices == 0,
        'randomImages' => 1,
        'footerLabel' => date("Y").' &copy; ООО "ЛБР-АгроMаркет"',
        'breadcrumbs' => array(),
        'meta_title' => 'Запчасти ЛБР-Агромаркет',
        'meta_description' => 'Магазин запчастей, запчасти ЛБР',
        'menu_admin' => array(
            'Каталог'=>array(
                'Валюта'=>'/admin/currency/',
                'Группы товаров'=>'/admin/group/',
                'Запчасти'=>'/admin/product/',
                'Категории'=>'/admin/category/',
                'Модельные ряды'=>'/admin/modelline/',
                'Производители запчастей'=>'/admin/productmaker/',
                'Производители техники'=>'/admin/equipmentmaker/',
                'Филиалы и зоны'=>'/admin/filial/',
            ),
            'Скидки'=>'/admin/discount/',
            'Заказы'=>array(
                'Все заказы'=>'/admin/order/',
                //'Доставка'=>'#',
                'Статусы заказов'=>'/admin/orderstatus/',
            ),
            'Сайт'=>array(
                //'Актуальные предложения'=>'/admin/actualoffer/',
                //'Журнал редактирования'=>'/admin/changes/',
                'Спецпредложения'=>'/admin/bestoffer/',
                'Страницы'=>'/admin/page/',
                'Структура'=>'/admin/structure/',
                //'Хиты продаж'=>'/admin/bestseller/',
            ),
            //'Уведомления'=>'#',
            //'Статистика'=>'#',
            'Пользователи'=>array(
                'Все пользователи'=>'/admin/user/',
                'Статусы пользователей'=>'/admin/userstatus/',
            ),
            'FAQ'=>'/admin/faq/',
        ),
    ),
);
