<?php $__env->startSection('title', __('vehiculos.add_new_product')); ?>

<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo app('translator')->get('vehiculos.add_new_product'); ?></h1>
        <!-- <ol class="breadcrumb">
                                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                <li class="active">Here</li>
                              </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <?php
            $form_class = empty($duplicate_product) ? 'create' : '';
        ?>
        <?php echo Form::open([
            'url' => action('ProductController@store'),
            'method' => 'post',
            'id' => 'product_add_form',
            'class' => 'product_form ' . $form_class,
            'files' => true,
        ]); ?>

        <?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('name', __('vehiculos.product_name') . ':*'); ?>

                        <?php echo Form::text('name', !empty($duplicate_product->name) ? $duplicate_product->name : null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('vehiculos.product_name'),
                        ]); ?>

                    </div>
                </div>
                <div class="col-sm-4 <?php if(!session('business.enable_brand')): ?> hide <?php endif; ?>">
                    <div class="form-group">
                        <?php echo Form::label('brand_id', __('product.brand') . ':'); ?>

                        <div class="input-group">
                            <?php echo Form::select(
                                'brand_id',
                                $brands,
                                !empty($duplicate_product->brand_id) ? $duplicate_product->brand_id : null,
                                ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                            ); ?>

                            <span class="input-group-btn">
                                <button type="button" <?php if(!auth()->user()->can('brand.create')): ?> disabled <?php endif; ?>
                                    class="btn btn-default bg-white btn-flat btn-modal"
                                    data-href="<?php echo e(action('BrandController@create', ['quick_add' => true]), false); ?>"
                                    title="<?php echo app('translator')->get('brand.add_brand'); ?>" data-container=".view_modal"><i
                                        class="fa fa-plus-circle text-primary fa-lg"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('color', __('Color') . ':*'); ?>

                        <?php echo Form::text('color', !empty($duplicate_product->color) ? $duplicate_product->color : null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('Color'),
                        ]); ?>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('model', __('Modelo') . ':*'); ?>

                        <?php echo Form::text('model', !empty($duplicate_product->model) ? $duplicate_product->model : null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('Modelo'),
                        ]); ?>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('bin', __('Bin') . ':*'); ?>

                        <?php echo Form::text('bin', !empty($duplicate_product->bin) ? $duplicate_product->bin : null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('Bin'),
                        ]); ?>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('placa', __('Placa') . ':*'); ?>

                        <?php echo Form::text('placa', !empty($duplicate_product->placa) ? $duplicate_product->placa : null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('Placa'),
                        ]); ?>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('dua', __('Dua') . ':*'); ?>

                        <?php echo Form::text('dua', !empty($duplicate_product->dua) ? $duplicate_product->dua : null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('Dua'),
                        ]); ?>

                    </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo Form::label('comprado_a', __('Comprado a') . ':'); ?>

                      <?php echo Form::text('comprado_a', !empty($duplicate_product->comprado_a) ? $duplicate_product->comprado_a : null, [
                          'class' => 'form-control',
                          'placeholder' => __('Comprado a'),
                      ]); ?>

                  </div>
              </div>



                

                <!-- <div class="clearfix"></div> -->




                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('sku', __('vehiculos.sku') . ' o código:'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.sku') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
                        <?php echo Form::text('sku', null, ['class' => 'form-control', 'placeholder' => __('vehiculos.sku')]); ?>

                    </div>
                </div>

                <div class="col-sm-4 <?php if(!session('business.enable_category')): ?> hide <?php endif; ?>">
                    <div class="form-group">
                        <?php echo Form::label('category_id', __('product.category') . ':'); ?>

                        <?php echo Form::select(
                            'category_id',
                            $categories,
                            !empty($duplicate_product->category_id) ? $duplicate_product->category_id : null,
                            ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                        ); ?>

                    </div>
                </div>

                

                <?php
                    $default_location = null;
                    if (count($business_locations) == 1) {
                        $default_location = array_key_first($business_locations->toArray());
                    }
                ?>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('product_locations', __('business.business_locations') . ':'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('lang_v1.product_location_help') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
                        <?php echo Form::select('product_locations[]', $business_locations, $default_location, [
                            'class' => 'form-control select2',
                            'multiple',
                            'id' => 'product_locations',
                        ]); ?>

                    </div>
                </div>


                <div class="clearfix"></div>

                
                
                <?php if(!empty($common_settings['enable_product_warranty'])): ?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo Form::label('warranty_id', __('lang_v1.warranty') . ':'); ?>

                            <?php echo Form::select('warranty_id', $warranties, null, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                            ]); ?>

                        </div>
                    </div>
                <?php endif; ?>
                <!-- include module fields -->
                <?php if(!empty($pos_module_data)): ?>
                    <?php $__currentLoopData = $pos_module_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($value['view_path'])): ?>
                            <?php if ($__env->exists($value['view_path'], ['view_data' => $value['view_data']])) echo $__env->make($value['view_path'], ['view_data' => $value['view_data']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <?php echo Form::label('product_description', __('Descripción') . ':'); ?>

                        <?php echo Form::textarea(
                            'product_description',
                            !empty($duplicate_product->product_description) ? $duplicate_product->product_description : null,
                            ['class' => 'form-control'],
                        ); ?>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo Form::label('image', __('Imagen del vehículo') . ':'); ?>

                        <?php echo Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*']); ?>

                        <small>
                            <p class="help-block"><?php echo app('translator')->get('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000]); ?> <br> <?php echo app('translator')->get('lang_v1.aspect_ratio_should_be_1_1'); ?></p>
                        </small>
                    </div>
                </div>
            </div>
        <?php echo $__env->renderComponent(); ?>

        

        


        
        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="text-center">
                    <div class="btn-group">
                        <?php if($selling_price_group_count): ?>
                            <button type="submit" value="submit_n_add_selling_prices"
                                class="btn btn-warning submit_product_form"><?php echo app('translator')->get('lang_v1.save_n_add_selling_price_group_prices'); ?></button>
                        <?php endif; ?>

                        

                        <button type="submit" value="save_n_add_another"
                            class="btn bg-maroon submit_product_form"><?php echo app('translator')->get('lang_v1.save_n_add_another'); ?></button>

                        <button type="submit" value="submit"
                            class="btn btn-primary submit_product_form"><?php echo app('translator')->get('messages.save'); ?></button>
                    </div>

                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>


    </section>
    <!-- /.content -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <?php $asset_v = env('APP_VERSION'); ?>
    <script src="<?php echo e(asset('js/product.js?v=' . $asset_v), false); ?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            onScan.attachTo(document, {
                suffixKeyCodes: [13], // enter-key expected at the end of a scan
                reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
                onScan: function(sCode, iQty) {
                    $('input#sku').val(sCode);
                },
                onScanError: function(oDebug) {
                    console.log(oDebug);
                },
                minLength: 2,
                ignoreIfFocusOn: ['input', '.form-control']
                // onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
                //     console.log('Pressed: ' + iKeyCode);
                // }
            });
        });


        $('#tipo').change(() => {
            let tipo = $('#tipo').val();
            if (tipo == 'veiculo') {
                $('.veiculo').css('display', 'block')
            } else {
                limpaDadosVeiculo()
            }
        })

        function limpaDadosVeiculo() {
            $('.veiculo').css('display', 'none')
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/product/create.blade.php ENDPATH**/ ?>