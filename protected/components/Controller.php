<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        /*public function beforeAction()
        {
            $script = Yii::app()->clientScript;
            //$script->registerCoreScript('jquery');
            $script->registerScriptFile('/js/jquery.1.11.3.min.js');
            
            $script->registerCssFile('/css/ui/jquery-ui-1.10.3.css');
            $script->registerCssFile('/css/front/alertify/core.css');
            $script->registerCssFile('/css/front/tip-darkgray/tip-darkgray.css');
            $script->registerCssFile('/css/front/alertify/default.css');
            
            $script->registerScriptFile('/js/front/frontend.js');
            $script->registerScriptFile('/js/front/cart.js');
            $script->registerScriptFile('/js/jquery.jcarousel.min.js');
            $script->registerScriptFile('/js/jquery.carouFredSel.min.js');
            $script->registerScriptFile('/js/jquery.dcjqaccordion.2.7.min.js');
            $script->registerScriptFile('/js/jquery.hoverIntent.minified.js');
            $script->registerScriptFile('/js/jquery.mCustomScrollbar.concat.min.js');
            $script->registerScriptFile('/js/jquery.cookie.min.js');
            $script->registerScriptFile('/js/alertify.min.js');
            $script->registerScriptFile('/js/front/search.js');
            $script->registerScriptFile('/js/jquery.dotdotdot.min.js');
            $script->registerScriptFile('/js/easyTooltip.js');

            return true;
        }*/
}