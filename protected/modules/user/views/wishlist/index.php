<div class="wishlist-wrapper">
    <div class="wishlist-header">
        <h1>
            <?php echo Yii::app()->params['meta_title']; ?>
        </h1>
    </div>
    <?php
        if($count) {
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $data,
                'viewData' => array('info' => $info), 
                'cssFile'      => false,
                'itemView'     => '_item',
                'ajaxUpdate'   => false,
                'emptyText'    => 'Блокнот пуст.',
                'itemsTagName' => 'div',
                'template'     => '{sorter}{items}{pager}',
                'sorterHeader' => '',
                'pager'        => array(
                    'header'   => false,
                    'firstPageLabel' => '<< Первая',
                    'prevPageLabel'  => '< Предыдущая',
                    'nextPageLabel'  => 'Следующая >',
                    'lastPageLabel'  => 'Последняя >>',
                    'cssFile'        => false
                )
            ));
        } else echo '<span class="empty">Блокнот пуст.</span>';
    ?>
</div>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.thumbnail',
	'config'=>array(),
));