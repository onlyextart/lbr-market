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
    <div id="maker_name"><span> </span></div>
    <div id="message">Изменения сохранены</div>
    <?php echo CHtml::beginForm(); ?>
        <div class="form">
            
        </div>   
        <div class="buttons">
            <input type="button" id="save" value="Сохранить">
            <input type="button" id="close" value="Закрыть">
        </div>
    <?php echo CHtml::endForm(); ?>
    
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
            if (maker_repeat===false){
                var table_row_html='<tr><td class=\"maker\">'+$(this).attr('name')+'</td>'
                +'<td width="16" class="button_del"><img src="/images/delete.png" title="Удалить из выбранных" alt="Удалить"></td>'
                +'<td width="16" class="button_edit"><img src="/images/update.png" title="Редактировать модельные ряды" alt="Редактировать"></td>'
                +'<td><input type="hidden" name="makers[]" value="'+$(this).attr('id')+'"></td></tr>';
                $("#category-makers-table>tbody:last").append(table_row_html);
            }
        });
        $(document).on('click','.button_edit img',function(){
            $("#maker_name>span").html("Модельные ряды производителя "+$(this).parent().parent().find("td.maker").text());
            $.ajax({
                type:'GET',
                url:'/admin/category/getModelLines',
                data : {
                    makerId: $(this).parent().parent().find("input[type=hidden]").val(),
                    categoryId:<?php echo $model->id;?>
                },
                success:function(result){
                        $(".category-maker-modellines .form").empty();
                        result_array = JSON.parse(result);
                        $(".category-maker-modellines").css('display','block');
                        if (result_array.length==0){
                             $(".category-maker-modellines .form").append("<span>Нет результатов</span>");
                        }
                        else{
                            var catalog_top;
                            $.each(result_array,function(index, value){
                                catalog_top='';
                                if(value['catalog_top']==='1'){
                                    catalog_top='checked';
                                }
                                $(".category-maker-modellines .form").append("<div class='row'><input type='checkbox' name='modellines[]' "+catalog_top+" id='i"+index+"' value='"+value['id']+"'>"+value['name']+"</div>");
                            
                            });
                       }
                        
                }
            });
        });
        $(document).on('click','.category-maker-modellines #close',function(){
            $(".category-maker-modellines").hide();
        });
        $(document).on('click','.category-maker-modellines #save',function(){
            var modelLines_check=new Array();
            var modelLines_uncheck=new Array();
            $(this).parent().parent().find("input[type='checkbox']").each(function(index,element){
                if ($(element).attr('checked')==='checked'){
                    modelLines_check.push($(element).val());
                }
                else{
                    modelLines_uncheck.push($(element).val());
                }
            });
            $.ajax({
                type:'POST',
                url:'/admin/category/saveModelLines',
                async: false,
                data : {
                    modelLinesId_show: modelLines_check,
                    modelLinesId_hide: modelLines_uncheck,
                },
                success:function(result){
                    $('#message').fadeIn(1000).delay(500).fadeOut(1000);
                }
            });
        });
    });
</script> 

