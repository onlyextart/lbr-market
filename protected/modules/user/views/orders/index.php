<div class="orders-wrapper">
    <div class="orders-header">
        <h1>
            <?php echo Yii::app()->params['meta_title']; ?>
        </h1>
    </div>
    <div class="orders-grid">
    <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'ordersListGrid',
            'emptyText' => 'Список заказов пуст.',
            //'filter'=>$model,
            'dataProvider'=>$data,
            'template'=>'{items}{pager}',
            'columns' => array( 
                //'status_id',
                array(
                    'name'=>'',
                    //'value'=>'$data->user->login',
                    'type'=>'raw',
                    'value'=>'CHtml::link("Заказ от ".date("Y.m.d H:i", strtotime($data->date_created)), array("/cart/view", "secret_key"=>$data->secret_key))',
                ),
                array(
                    'name'=>'user_email',
                    'value'=>'$data->user_email',
                ),
                /*array(
                    'name'=>'status_id',
                    'value'=>'$data->order_status->name',
                ),*/
                array(
                    'name'=>'user_phone',
                    'value'=>'$data->user_phone',
                ),
                array(
                    'name'=>'delivery_id',
                    'value'=>'$data->delivery->name',
                ),
                
                
                //'status_id'
                /*array (
                    'name'=>'name',
                    'type'=>'raw',
                    'value'=>'CHtml::link(CHtml::encode($data->name), array("edit","id"=>$data->id))',
                    ),

                array(
                    'name'=>'productGroup_name',
                    'value'=>'$data->productGroup->name',
                 ),

                array(
                    'name'=>'price_value',
                    //'value'=>'(int)$data->price->value." ".$data->currency->iso',
                    'value'=>'(int)$data->price->value',
                ),

                array(
                    'name'=>'catalog_number',
                    'value'=>'$data->catalog_number',
                ),

                array(
                    'name'=>'productMaker_name',
                    'value'=>'$data->productMaker->name',
                ),

                array(
                    'name'=>'count',
                    'value'=>'$data->count',
                ),

                array(
                    'name'=>'liquidity',
                    'value'=>'$data->liquidity',
                ),

                array(
                    'name'=>'min_quantity',
                    'value'=>'$data->min_quantity',
                ),


                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}{delete}',
                    'buttons'=>array (
                        'update' => array (
                            'url'=>'Yii::app()->createUrl("admin/product/edit", array("id"=>$data->id))',
                        ),
                        'delete' => array (
                            'url'=>'Yii::app()->createUrl("admin/product/delete", array("id"=>$data->id))',
                            'click'=>'function(){

                            }', 
                        ),
                    ),
                ),*/
            ),
        ));
    ?>
    </div>
</div>