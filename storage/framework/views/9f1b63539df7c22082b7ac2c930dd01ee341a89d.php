<?php $__env->startSection('title', __('product.import_products')); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo app('translator')->get('product.import_products'); ?>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <?php if(session('notification') || !empty($notification)): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php if(!empty($notification['msg'])): ?>
                <?php echo e($notification['msg'], false); ?>

                <?php elseif(session('notification.msg')): ?>
                <?php echo e(session('notification.msg'), false); ?>

                <?php endif; ?>
            </div>
        </div>  
    </div>     
    <?php endif; ?>
    
    <div class="row">
        <div class="col-sm-12">
            <?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>
            <?php echo Form::open(['url' => action('ImportProductsController@store'), 'method' => 'post', 'enctype' => 'multipart/form-data' ]); ?>

            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <?php echo Form::label('name', __( 'product.file_to_import' ) . ':'); ?>

                            <?php echo Form::file('products_csv', ['accept'=> '.xls, .xlsx, .csv', 'required' => 'required']); ?>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <br>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('messages.submit'); ?></button>
                    </div>
                </div>
            </div>

            <?php echo Form::close(); ?>

            <br><br>
            <div class="row">
                <div class="col-sm-4">
                    <a href="<?php echo e(asset('files/import_products_csv_template.xls'), false); ?>" class="btn btn-success" download><i class="fa fa-download"></i> <?php echo app('translator')->get('lang_v1.download_template_file'); ?></a>
                </div>
            </div>
            <?php echo $__env->renderComponent(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.instructions')]); ?>
            <strong><?php echo app('translator')->get('lang_v1.instruction_line1'); ?></strong><br>
            <?php echo app('translator')->get('lang_v1.instruction_line2'); ?>
            <br><br>
            <table class="table table-striped">
                <tr>
                    <th><?php echo app('translator')->get('lang_v1.col_no'); ?></th>
                    <th><?php echo app('translator')->get('lang_v1.col_name'); ?></th>
                    <th><?php echo app('translator')->get('lang_v1.instruction'); ?></th>
                </tr>
                <tr>
                    <td>1</td>
                    <td><?php echo app('translator')->get('product.product_name'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.required'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.name_ins'); ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><?php echo app('translator')->get('product.brand'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.brand_ins'); ?> <br><small class="text-muted">(<?php echo app('translator')->get('lang_v1.brand_ins2'); ?>)</small></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><?php echo app('translator')->get('product.unit'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.required'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.unit_ins'); ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><?php echo app('translator')->get('product.category'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.category_ins'); ?> <br><small class="text-muted">(<?php echo app('translator')->get('lang_v1.category_ins2'); ?>)</small></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td><?php echo app('translator')->get('product.sub_category'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.sub_category_ins'); ?> <br><small class="text-muted">(<?php echo __('lang_v1.sub_category_ins2'); ?>)</small></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td><?php echo app('translator')->get('product.sku'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.sku_ins'); ?></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td><?php echo app('translator')->get('product.barcode_type'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>, <?php echo app('translator')->get('lang_v1.default'); ?>: C128)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.barcode_type_ins'); ?> <br>
                        <strong><?php echo app('translator')->get('lang_v1.barcode_type_ins2'); ?>: C128, C39, EAN-13, EAN-8, UPC-A, UPC-E, ITF-14</strong>
                    </td>
                </tr>
                <tr>
                    <td>8</td>
                    <td><?php echo app('translator')->get('product.manage_stock'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.required'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.manage_stock_ins'); ?><br>
                        <strong>1 = <?php echo app('translator')->get('messages.yes'); ?><br>
                        0 = <?php echo app('translator')->get('messages.no'); ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>9</td>
                    <td><?php echo app('translator')->get('product.alert_quantity'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('product.alert_quantity'); ?></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td><?php echo app('translator')->get('product.expires_in'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.expires_in_ins'); ?></td>
                </tr>
                <tr>
                    <td>11</td>
                    <td><?php echo app('translator')->get('lang_v1.expire_period_unit'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.expire_period_unit_ins'); ?><br>
                        <strong><?php echo app('translator')->get('lang_v1.available_options'); ?>: days, months</strong>
                    </td>
                </tr>
                <tr>
                    <td>12</td>
                    <td><?php echo app('translator')->get('product.applicable_tax'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.applicable_tax_ins'); ?> <?php echo __('lang_v1.applicable_tax_help'); ?></td>
                </tr>
                <tr>
                    <td>13</td>
                    <td><?php echo app('translator')->get('product.selling_price_tax_type'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.required'); ?>)</small></td>
                    <td><?php echo app('translator')->get('product.selling_price_tax_type'); ?> <br>
                        <strong><?php echo app('translator')->get('lang_v1.available_options'); ?>: inclusive, exclusive</strong>
                    </td>
                </tr>
                <tr>
                    <td>14</td>
                    <td><?php echo app('translator')->get('product.product_type'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.required'); ?>)</small></td>
                    <td><?php echo app('translator')->get('product.product_type'); ?> <br>
                        <strong><?php echo app('translator')->get('lang_v1.available_options'); ?>: single, variable</strong></td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td><?php echo app('translator')->get('product.variation_name'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.variation_name_ins'); ?>)</small></td>
                        <td><?php echo app('translator')->get('lang_v1.variation_name_ins2'); ?></td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td><?php echo app('translator')->get('product.variation_values'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.variation_values_ins'); ?>)</small></td>
                        <td><?php echo __('lang_v1.variation_values_ins2'); ?></td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <td> <?php echo app('translator')->get('lang_v1.purchase_price_inc_tax'); ?><br><small class="text-muted">(<?php echo app('translator')->get('lang_v1.purchase_price_inc_tax_ins1'); ?>)</small></td>
                        <td><?php echo __('lang_v1.purchase_price_inc_tax_ins2'); ?></td>
                    </tr>
                    <tr>
                        <td>18</td>
                        <td><?php echo app('translator')->get('lang_v1.purchase_price_exc_tax'); ?>  <br><small class="text-muted">(<?php echo app('translator')->get('lang_v1.purchase_price_exc_tax_ins1'); ?>)</small></td>
                        <td><?php echo __('lang_v1.purchase_price_exc_tax_ins2'); ?></td>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td><?php echo app('translator')->get('lang_v1.profit_margin'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td><?php echo app('translator')->get('lang_v1.profit_margin_ins'); ?><br>
                            <small class="text-muted"><?php echo __('lang_v1.profit_margin_ins1'); ?></small></td>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td><?php echo app('translator')->get('lang_v1.selling_price'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                            <td><?php echo app('translator')->get('lang_v1.selling_price_ins'); ?><br>
                               <small class="text-muted"><?php echo __('lang_v1.selling_price_ins1'); ?></small></td>
                           </tr>
                           <tr>
                            <td>21</td>
                            <td><?php echo app('translator')->get('lang_v1.opening_stock'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                            <td><?php echo app('translator')->get('lang_v1.opening_stock_ins'); ?> <?php echo __('lang_v1.opening_stock_help_text'); ?><br>
                            </td>
                        </tr>
                        <tr>
                            <td>22</td>
                            <td><?php echo app('translator')->get('lang_v1.opening_stock_location'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>) <br><?php echo app('translator')->get('lang_v1.location_ins'); ?></small></td>
                            <td><?php echo app('translator')->get('lang_v1.location_ins1'); ?><br>
                            </td>
                        </tr>
                        <tr>
                            <td>23</td>
                            <td><?php echo app('translator')->get('lang_v1.expiry_date'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                            <td><?php echo __('lang_v1.expiry_date_ins'); ?><br>
                            </td>
                        </tr>
                        <tr>
                            <td>24</td>
                            <td><?php echo app('translator')->get('lang_v1.enable_imei_or_sr_no'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>, <?php echo app('translator')->get('lang_v1.default'); ?>: 0)</small></td>
                            <td><strong>1 = <?php echo app('translator')->get('messages.yes'); ?><br>
                            0 = <?php echo app('translator')->get('messages.no'); ?></strong><br>
                        </td>
                    </tr>
                    <tr>
                        <td>25</td>
                        <td><?php echo app('translator')->get('lang_v1.weight'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td><?php echo app('translator')->get('lang_v1.optional'); ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td>26</td>
                        <td><?php echo app('translator')->get('lang_v1.rack'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td><?php echo __('lang_v1.rack_help_text'); ?></td>
                    </tr>
                    <tr>
                        <td>27</td>
                        <td><?php echo app('translator')->get('lang_v1.row'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td><?php echo __('lang_v1.row_help_text'); ?></td>
                    </tr>
                    <tr>
                        <td>28</td>
                        <td><?php echo app('translator')->get('lang_v1.position'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td><?php echo __('lang_v1.position_help_text'); ?></td>
                    </tr>
                    <tr>
                        <td>29</td>
                        <td><?php echo app('translator')->get('lang_v1.image'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td><?php echo __('lang_v1.image_help_text', ['path' => 'public/uploads/'.config('constants.product_img_path')]); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>30</td>
                        <td><?php echo app('translator')->get('lang_v1.product_description'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>31</td>
                        <td><?php echo app('translator')->get('lang_v1.product_custom_field1'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>32</td>
                        <td><?php echo app('translator')->get('lang_v1.product_custom_field2'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    </tr>
                    <tr>
                        <td>33</td>
                        <td><?php echo app('translator')->get('lang_v1.product_custom_field3'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>34</td>
                        <td><?php echo app('translator')->get('lang_v1.product_custom_field4'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    </tr>
                    <tr>
                        <td>35</td>
                        <td><?php echo app('translator')->get('lang_v1.not_for_selling'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                        <td><strong>1 = <?php echo app('translator')->get('messages.yes'); ?><br>
                        0 = <?php echo app('translator')->get('messages.no'); ?></strong><br>
                    </td>
                </tr>
                <tr>
                    <td>36</td>
                    <td><?php echo app('translator')->get('lang_v1.product_locations'); ?> <small class="text-muted">(<?php echo app('translator')->get('lang_v1.optional'); ?>)</small></td>
                    <td><?php echo app('translator')->get('lang_v1.product_locations_ins'); ?>
                    </td>
                </tr>

                <tr>
                    <td>37</td>
                    <td>%ICMS <small class="text-muted">(Obrigatório)</small></td>
                    <td>Percentual de ICMS para o produto
                    </td>
                </tr>
                <tr>
                    <td>38</td>
                    <td>%PIS <small class="text-muted">(Obrigatório)</small></td>
                    <td>Percentual de PIS para o produto
                    </td>
                </tr>
                <tr>
                    <td>39</td>
                    <td>%COFINS <small class="text-muted">(Obrigatório)</small></td>
                    <td>Percentual de COFINS para o produto
                    </td>
                </tr>
                <tr>
                    <td>40</td>
                    <td>%IPI <small class="text-muted">(Obrigatório)</small></td>
                    <td>Percentual de IPI para o produto
                    </td>
                </tr>

                <tr>
                    <td>41</td>
                    <td>CST/CSOSN <small class="text-muted"></small></td>
                    <td>CST/CSOSN para o produto padrão 101
                    </td>
                </tr>

                <tr>
                    <td>42</td>
                    <td>CST/PIS <small class="text-muted"></small></td>
                    <td>CST/PIS para o produto padrão 49
                    </td>
                </tr>

                <tr>
                    <td>43</td>
                    <td>CST/COFINS <small class="text-muted"></small></td>
                    <td>CST/COFINS para o produto padrão 49
                    </td>
                </tr>

                <tr>
                    <td>44</td>
                    <td>CST/IPI <small class="text-muted"></small></td>
                    <td>CST/IPI para o produto padrão 99
                    </td>
                </tr>

                <tr>
                    <td>45</td>
                    <td>NCM <small class="text-muted">(Obrigatório)</small></td>
                    <td>NCM para o produto
                    </td>
                </tr>

                <tr>
                    <td>46</td>
                    <td>CEST <small class="text-muted"></small></td>
                    <td>CEST para o produto
                    </td>
                </tr>

                <tr>
                    <td>47</td>
                    <td>CFOP Saida Estadual <small class="text-muted">(Obrigatório)</small></td>
                    <td>CFOP Saida Estadual para o produto
                    </td>
                </tr>

                <tr>
                    <td>48</td>
                    <td>CFOP Saida Inter Estadual <small class="text-muted">(Obrigatório)</small></td>
                    <td>CFOP Saida Inter Estadual para o produto
                    </td>
                </tr>

            </table>
            <?php echo $__env->renderComponent(); ?>
        </div>
    </div>
</section>
<!-- /.content -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/import_products/index.blade.php ENDPATH**/ ?>