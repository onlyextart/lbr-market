<?php
   //Yii::app()->clientScript->registerScriptFile('http://www.baikalsr.ru/api-calc/?ver=2.2&setAccount=BS-0000189',CClientScript::POS_END);
   Yii::app()->clientScript->registerScriptFile('http://www.baikalsr.ru/api-calc/?ver=2.2&setAccount=BS-0000189');
?>

<script type="text/javascript">
    $(function() {
        $("#myCalculator").bsCalculator({
            city_out:true,
            datepicker:true,
            load_parameters:true
        });
    });
</script>

<div id="myCalculator"></div>