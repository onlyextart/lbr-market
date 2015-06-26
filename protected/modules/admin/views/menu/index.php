<?php
/*
В действие Index контроллера administrator/menu определена переменная $menuModel
содержащая модель MENU со всеми пунктами меню.
 */
//var_dump($menuTreeArray);
?>
<style>
    #menu_features{
        box-shadow: 0px 0px 7px;
        border-radius: 5px;
        float: left; 
        width: 60%; 
        padding: 2%; 
        margin-left:2%; 
    }
    .menuTreeViewWrapper{
        box-shadow: 0px 0px 7px;
        border-radius: 5px;
        float: left; 
        width: 27%; 
        padding: 2%; 
    }
</style>
    
<h2 class="col ">Менеджер меню</h2>
<div class="menuTreeViewWrapper">
    <?php
    $this->widget('CTreeView', 
        array(
            'data' => $menuTreeArray, 
            'animated'=>100, 
            'htmlOptions'=>array('class'=>'menuTreeView'),
        )
    );
    ?>
    <a href="/administrator/menu/createMenu">
        <button class="btn">Добавить новое меню</button> 
    </a>
</div>
<div id="menu_features">
        
</div>
<script>
 //nemuItemForm
function MenuTreeViewConstructor(jQuery){
    var thisObject = this;
    this.jQuery = jQuery;
    
    this.showForm = function( linkElement ){
        $('#menu_features').append('<div class="ui-widget-overlay ui-front"></div>');
        $.ajax({
            url: linkElement.href
        }).done(function ( data ) {
            $('#menu_features').html( data );
        });
    }
    this.updateTree = function( clearMenuFeaturesWrapper ){
        $.ajax({
            type: "POST",
            url: '/administrator/menu/menuTree',
            data: thisObject.getOpenItemsId()
        }).done(function ( data ) {
            $('.menuTreeViewWrapper').html( data );
            thisObject.jQuery(function($) {
                thisObject.treeObject = thisObject.jQuery(".menuTreeView").treeview({'animated':100});
            });
            if( clearMenuFeaturesWrapper ){
                $('#menu_features').html('');
            }
        });
    }
    this.deleteItem = function( linkElement ){
        $.ajax({
            url: linkElement.href
        }).done(function ( data ) {
            setTimeout(thisObject.updateTree( true ), 600);
        });
    }
    this.sortItem = function( linkElement ){
        $.ajax({
            url: linkElement.href
        }).done(function ( data ) {
            setTimeout(thisObject.updateTree( true ), 600);
        });
    }
    this.changePublishing = function( linkElement ){
        $.ajax({
            url: linkElement.href
        }).done(function ( data ) {
            setTimeout(thisObject.updateTree( true ), 600);
        });
    }
    this.getOpenItemsId = function(){
        var openItemsIdObject = {};
        var i = 0;
        $('.collapsable').each(function(){
            openItemsIdObject['OpenItems['+i+']'] = $(this).attr('id');
            i++;
        });
        return openItemsIdObject;
    }
 }
 menuTreeView = new MenuTreeViewConstructor(jQuery);
</script>