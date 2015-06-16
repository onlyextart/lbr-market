<div class="breadcrumbs">
    <?php
       /* $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => Yii::app()->params['breadcrumbs'],
            'activeLinkTemplate' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="{url}">{label}</a></span>',
            'inactiveLinkTemplate' => '{label}',
            'homeLink' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="/">Главная</a></span>',
            'tagName' => 'span',
            'htmlOptions' => array(
                'xmlns:v' => 'http://rdf.data-vocabulary.org/#',
            ),
        ));
        //echo $data->image; exit;
        $image = '/images/no-photo.png';
        if(!empty($data->image)) $image = 'http://api.lbr.ru/images/shop/spareparts/'.$data->image;
       */
    ?>
</div>
<div>
   <?php if(!empty($model->image)): ?>
        <div class="draft-wrapper">
             <h1 itemprop="name">Сборочный чертеж "<?php echo $model->name?>"</h1>
             <div class="draft-image-wrapper">
                 <a href="http://api.lbr.ru/images/shop/draft/<?php echo $model->image ?>" class="thumbnail" target="_blank">
                    <img border="0" itemprop="image" alt="Двигатель ВАЗ-2103-01-07 1.5л, 70л.с, Аи-92" src="http://api.lbr.ru/images/shop/draft/<?php echo $model->image ?>">
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
                     <td><a href="/product/index/id/<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></td>
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

<script>
/*(function($){
    
})(jQuery);*/
</script>

