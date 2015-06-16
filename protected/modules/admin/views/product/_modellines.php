<?php if(!empty($modellines)):?>
<div class="label-static">Отображается в следующих модельных рядах:</div>
<?php foreach($modellines as $modelline): ?>
<div class="row">
    <!--a href="/model/show/id/<?php echo $modelline['id']?>"><?php echo $modelline['name']?></a-->
    <a href="<?php echo $modelline['path']?>"><?php echo $modelline['name']?></a>
</div>
<?php endforeach; ?>
<?php endif; ?>

