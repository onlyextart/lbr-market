<div class="bestoffer_maker_header">Выбранные производители</div>
<table id="bestoffer-makers-table">
    <?php
        echo '<tbody>';
        if (!empty($selected_makers)){
            foreach ($selected_makers as $maker_id) {
                echo '<tr><td class="maker">'.ProductMaker::getMakerName($maker_id).'</td>';
                echo '<td class="button_del"><img src="/images/delete.png" title="Удалить из выбранных" alt="Удалить"></td>';
                echo '<td><input type="hidden" name="makers[]" value="'.$maker_id.'"></td></tr>';
            }
        }
        echo '</tbody>';
    ?> 
 </table>
<div class="bestoffer_maker_header">Все производители запчастей</div>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'bestofferMakerListGrid',
        'filter'=>$model_maker,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
        ),
        'columns' => array(
            array( 
                'name'=>'name',
            ),
            array(
                'type'=>'raw',
                'value'=>'CHtml::image("/images/add.png","Добавить",array("id"=>"$data->id","name"=>"$data->name","class"=>"button_add"));'
            ),
        ),
    ));
?>
</div>
<script>
    $(document).ready(function(){
        $(document).on('click','.button_del img',function(){
            $(this).parent().parent().remove();
        }); 
        $(document).on('click','.button_add',function(){
            var maker_repeat=false;
            var id=$(this).attr('id');
            $("#bestoffer-makers-table input[type=hidden]").each(function(){
                if ($(this).val()===id){
                    maker_repeat=true;
                }
                return (!maker_repeat);
            });
            if (!maker_repeat){
                var table_row_html='<tr><td class=\"maker\">'+$(this).attr('name')+'</td>'
                +'<td class="button_del"><img src="/images/delete.png" title="Удалить из выбранных" alt="Удалить"></td>'
                +'<td><input type="hidden" name="makers[]" value="'+$(this).attr('id')+'"></td></tr>';
                $("#bestoffer-makers-table>tbody:last").append(table_row_html);
            }
        });
    });
</script> 
