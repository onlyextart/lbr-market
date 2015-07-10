<div class="breadcrumbs">
    <?php
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => Yii::app()->params['breadcrumbs'],
            'activeLinkTemplate' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="{url}">{label}</a></span>',
            'inactiveLinkTemplate' => '{label}',
            'homeLink' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="/">Главная</a></span>',
            'tagName' => 'span',
            'htmlOptions' => array(
                'xmlns:v' => 'http://rdf.data-vocabulary.org/#',
            ),
        ));
    ?>
</div>
<div class="bestoffer-wrapper">
    <div class="elements">
        <h1>Распродажа</h1><img class="spec-label" src="/images/sale-label.png">
        <?php if(count($data->getData())): 
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $data,
                'cssFile'      => false,
                'itemView'     => '_view',
                'ajaxUpdate'   => false,
                'emptyText'    => 'На данный момент нет распродаж.',
                'itemsTagName' => 'div',
                'template'     => '{sorter}{items}{pager}',
                'htmlOptions'  => array('class'=>'best-spareparts-wrapper'),
                'sortableAttributes' => array('name'=>'Названию'),//, 'count'=>'Наличию'),
                'sorterHeader' => 'Сортировать по:',
                'pager'        => array(
                    'header'   => false,
                    'class' => 'LinkPager', 
                    'firstPageLabel' => 'В начало',
                    'prevPageLabel'  => 'Назад',
                    'nextPageLabel'  => 'Вперёд',
                    'lastPageLabel'  => 'В конец',
                    'cssFile'        => false
                )
            )); 
        ?>
        <?php else: ?>
              <div class="empty">На данный момент нет распродаж.</div>
        <?php endif; ?>
    </div>
</div>
<script>
    $(function() {
        $( ".small-cart-button" ).on('click', function() {
            var parent = $(this).parent();
            var cart = parent.find('.cart-quantity');
            var count = parseInt(cart.val());
            
            if(count > 0) {
                $.ajax({
                    type: 'POST',
                    url: '/cart/add',
                    dataType: 'json',
                    data: {
                        id: parent.attr('elem'),
                        count: count,
                    },
                    success: function(response) {
                        cart.val('1');
                        if(response.count) {
                            var label = ' товаров';
                            if(response.count == 1) {
                                label = ' товар';
                            } else if(response.count == 2 || response.count == 3 || response.count == 4){
                                label = ' товарa';
                            }
                            $('#cart-count').text(response.count+label);
                        }
                        alertify.success(response.message);
                    },
                });
            } else {
                alertify.success('<div class="mes-notify"><span></span><div>Введено неправильное количество</div></div>');
            }
        });
    });
</script>
