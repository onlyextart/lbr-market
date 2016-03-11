<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));
    
    $image = Product::model()->getDraftImage($model->image);
?>
<div>
   <?php if(!empty($model->image)): ?>
        <div class="draft-wrapper">
             <h1 itemprop="name">Сборочный чертеж "<?php echo $model->name?>"</h1>
             <div class="draft-image-wrapper">
                 <a href="<?php echo $image ?>" class="thumbnail" target="_blank">
                    <img border="0" itemprop="image" alt="<?php echo $model->name?>" src="<?php echo $image ?>">
                 </a>
             </div>
             <div class="clear"></div>
        </div>
        <?php if(!empty($products)): ?>
        <div>
           <table class="draft-table" width="100%" cellspacing="2" cellpadding="3" border="0">
              <tbody>
                 <tr class="header">
                     <th width="1%"></th>
                     <th width="5%">
                        <b>№</b>
                     </th>
                     <th width="50%">
                        <b>Наименование детали</b>
                     </th>
                     <th width="15%">
                        <b>Кол-во в узле</b>
                     </th>
                     <th width="20%">
                        <b>Примечание</b>
                     </th>
                 </tr>
                 <?php foreach($products as $product): ?>
                 <tr> 						
                     <td></td>
                     <td><?php echo $product['level']; ?></td>
                     <td><a href="<?php echo $product['path']; ?>"><?php echo $product['name']; ?></a></td>
                     <td><?php echo ($product['count'] > 0) ? $product['count']: '' ?></td>
                     <td><?php echo $product['note']; ?></td>
                 </tr>
                 <?php endforeach; ?>
              </tbody>
           </table>
        </div>
        <?php endif; ?>
   <?php else: ?>
       <div>Нет изображения</div>
   <?php endif; ?>
</div>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));
?>

