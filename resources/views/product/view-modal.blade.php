<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      <h4 class="modal-title" id="modalTitle">{{$product->name}}</h4>
	    </div>
	    <div class="modal-body">
      		<div class="row">
      			<div class="col-sm-9">
	      			<div class="col-sm-4 invoice-col">
	      				<b>@lang('product.sku'):</b>
						{{$product->sku }}<br>
						<b>@lang('product.brand'): </b>
						{{$product->brand->name ?? '--' }}<br>
						<b>@lang('vehiculos.model'): </b>
						{{$product->model ?? '--' }}<br>	
						<b>@lang('vehiculos.vin'): </b>
						{{$product->bin ?? '--' }}<br>	
						<b>@lang('vehiculos.color'): </b>
						{{$product->color ?? '--' }}<br>	
						<b>@lang('vehiculos.dua'): </b>
						{{$product->dua ?? '--' }}<br>
						<b>@lang('vehiculos.placa'): </b>
						{{$product->placa ?? '--' }}<br>											
						@php 
    						$custom_labels = json_decode(session('business.custom_labels'), true);
						@endphp
						
						<br>
						<strong>@lang('lang_v1.available_in_locations'):</strong>
						@if(count($product->product_locations) > 0)
							{{implode(', ', $product->product_locations->pluck('name')->toArray())}}
						@else
							@lang('lang_v1.none')
						@endif
	      			</div>

	      			<div class="col-sm-4 invoice-col">
						<b>@lang('product.category'): </b>
						{{$product->category->name ?? '--' }}<br>											
						<br>
					
	      			</div>
					
	      			<div class="col-sm-4 invoice-col">
	      				
	      			</div>
	      			<div class="clearfix"></div>
	      			<br>
      				<div class="col-sm-12">
      					{!! $product->product_description !!}
      				</div>
	      		</div>
      			<div class="col-sm-3 col-md-3 invoice-col">
      				<div class="thumbnail">
      					<img src="{{$product->image_url}}" alt="Product image">
      				</div>
      			</div>
      		</div>
      		@if($rack_details->count())
      		@if(session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
      			<div class="row">
      				<div class="col-md-12">
      					<h4>@lang('lang_v1.rack_details'):</h4>
      				</div>
      				<div class="col-md-12">
      					<div class="table-responsive">
      					<table class="table table-condensed bg-gray">
      						<tr class="bg-green">
      							<th>@lang('business.location')</th>
      							@if(session('business.enable_racks'))
      								<th>@lang('lang_v1.rack')</th>
      							@endif
      							@if(session('business.enable_row'))
      								<th>@lang('lang_v1.row')</th>
      							@endif
      							@if(session('business.enable_position'))
      								<th>@lang('lang_v1.position')</th>
      							@endif
      							</tr>
      						@foreach($rack_details as $rd)
      							<tr>
	      							<td>{{$rd->name}}</td>
	      							@if(session('business.enable_racks'))
	      								<td>{{$rd->rack}}</td>
	      							@endif
	      							@if(session('business.enable_row'))
	      								<td>{{$rd->row}}</td>
	      							@endif
	      							@if(session('business.enable_position'))
	      								<td>{{$rd->position}}</td>
	      							@endif
      							</tr>
      						@endforeach
      					</table>
      					</div>
      				</div>
      			</div>
      		@endif
      		@endif
      		@if($product->type == 'single')
      			@include('product.partials.single_product_details')
      		@elseif($product->type == 'variable')
      			@include('product.partials.variable_product_details')
      		@elseif($product->type == 'combo')
      			@include('product.partials.combo_product_details')
      		@endif
      		@if($product->enable_stock == 1)
	      		<div class="row">
	      			<div class="col-md-12">
	      				<strong>@lang('lang_v1.product_stock_details')</strong>
	      			</div>
	      			<div class="col-md-12" id="view_product_stock_details" data-product_id="{{$product->id}}">
	      			</div>
	      		</div>
      		@endif
      	</div>
      	<div class="modal-footer">
      		<button type="button" class="btn btn-primary no-print" 
	        aria-label="Print" 
	          onclick="$(this).closest('div.modal').printThis();">
	        <i class="fa fa-print"></i> @lang( 'messages.print' )
	      </button>
	      	<button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang( 'messages.close' )</button>
	    </div>
	</div>
</div>
