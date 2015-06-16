<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" type="image/jpg" href="<?php echo Yii::app()->request->baseUrl.'/images/favicon.jpg';?>"/>
        <title>Административная панель</title>
    </head>
    <body>
        <header>
            <div class="menu">
                <a target="_blank" href="/" class="logo-link">Магазин</a>
            <?php
                $menu = Yii::app()->params['menu_admin'];
                echo returnMenu($menu);
                if(!Yii::app()->user->isGuest):
            ?>
                <a href="/user/logout/" class="admin-logout">Выход <span>(<?php echo AuthUser::model()->findByPk(Yii::app()->user->_id)->login; ?>)</span></a>
            <?php endif; ?>
            </div>
            <div class="yui-gc" id="navigation">
		<div class="yui-u first" style="width:1px;">
                    <div class="navigation-content-left">
                        <div id="breadcrumbs" class="breadCrumb module">
                            <div>
                                <?php
                                    $this->widget('application.modules.admin.widgets.AdminBreadcrumbs', array(
                                        'homeLink'=>$this->createUrl('admin'),
                                        'links'=>$this->breadcrumbs,
                                    ));
                                ?>
                            </div>
                        </div>
                    </div>
		</div>
		<!--div class="yui-u" style="width:50%;">
                    <div class="navigation-content-right marright" align="right" style="float:right;">
                        <div style="float:right;">
                        <?php
                           // if (!empty($this->topButtons)) echo $this->topButtons;
                        ?>
                        </div>
                    </div>
		</div-->
            </div>            
        </header>
        <div class="wrapper">
            <?php echo $content; ?>
        </div>
        <footer>
            <p><?php echo Yii::app()->params['footerLabel']; ?></p>
        </footer>
    </body>
</html>


<?php
function returnMenu($arr) {
    $menu = '<ul class="nav">';
    foreach ($arr as $key=>$link){
        if (is_array($link)){
            $menu .= '<li class="item parent"><span>'.$key.'</span>'.returnMenu($link).'</li>';
        }else{
            $menu .= '<li class="item"><a href="'.$link.'">'.$key.'</a></li>';
        }
    }
    $menu .= '</ul>';
    return $menu;
  }
?>

<script>
     $(function() {
        $('.menu .nav li.item').click(function(){
            //console.log(11);
            sessionStorage.clear();
        });
     });
</script>