<?php if(!empty($data)): ?>
<div class="category-maker-header">Топ-производители в категории</div>
<div class="category-maker-top">
    <table id="category-makers-table">
        <?php
            echo '<tbody>';
            if (!empty($selected_makers)){
                foreach ($selected_makers as $maker_id) {
                    echo '<tr><td class="maker">'.EquipmentMaker::getMakerName($maker_id).'</td>'; 
                    echo '<td width="16" class="button_del"><img src="/images/delete.png" title="Удалить из выбранных" alt="Удалить"></td>';
                    echo '<td width="16" class="button_edit"><img src="/images/update.png" title="Редактировать модельные ряды" alt="Редактировать"></td>';
                    echo '<td><input type="hidden" name="makers[]" value="'.$maker_id.'"></td></tr>';
                }
            }
            echo '</tbody>';
        ?> 
    </table>
</div>
<div class="category-maker-modellines">
    <div id="maker_name"><span>Модельные ряды</span></div>
    <div class="form">
        <?php echo CHtml::beginForm(); ?>
        
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
<div class="category-maker-header">Все производители запчастей</div>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'categoryMakerListGrid',
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
        ),
        'columns' => array(
            array( 
                'name'=>'Название',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'$data->maker->name',
            ),
            array(
                'type'=>'raw',
                'value'=>'CHtml::image("/images/add.png","Добавить",array("id"=>$data->maker->id,"name"=>$data->maker->name,"class"=>"button_add"));'
            ),
        ),
    ));
?>
</div>
<?php endif; ?>

<script>
    $(document).ready(function(){
        $(".category-maker-modellines").css('display','none');
        $(document).on('click','.button_del img',function(){
            $(this).parent().parent().remove();
        }); 
        $(document).on('click','.button_add',function(){
            var maker_repeat=false;
            var id=$(this).attr('id');
            $("#category-makers-table input[type=hidden]").each(function(){
                if ($(this).val()===id){
                    maker_repeat=true;
                }
                return (!maker_repeat);
            });
            if (!maker_repeat){
                var table_row_html='<tr><td class=\"maker\">'+$(this).attr('name')+'</td>'
                +'<td class="button_del"><img src="/images/delete.png" title="Удалить из выбранных" alt="Удалить"></td>'
                +'<td><input type="hidden" name="makers[]" value="'+$(this).attr('id')+'"></td></tr>';
                $("#category-makers-table>tbody:last").append(table_row_html);
            }
        });
        $(document).on('click','.button_edit img',function(){
            $.ajax({
                type:'GET',
                url:'/admin/category/getModelLines',
                data : {
                    makerId: $(this).parent().parent().find("input[type=hidden]").val(),
                    categoryId:<?php echo $model->id;?>
                },
                success:function(result){
                    $(".category-maker-modellines").css('display','block');
                    console.log(result);
                }
            });
        });
    });
</script> 

