@php
	$subtype = '';
@endphp
@if(!empty($transaction_sub_type))
	@php
		$subtype = '?sub_type='.$transaction_sub_type;
	@endphp
@endif

@if(!empty($transactions))
	<table class="table table-slim no-border">
		@foreach ($transactions as $transaction)
			<tr class="cursor-pointer" 
	    		data-toggle="tooltip"
	    		data-html="true"
	    		title="Customer: {{optional($transaction->contact)->name}} 
		    		@if(!empty($transaction->contact->mobile) && $transaction->contact->is_default == 0)
		    			<br/>Mobile: {{$transaction->contact->mobile}}
		    		@endif
	    		" >
				<td>
					{{ $loop->iteration}}.
				</td>
				<td>
					{{ $transaction->invoice_no }} ({{optional($transaction->contact)->name}})
				</td>
				<td class="display_currency">
					{{ $transaction->final_total }}
				</td>
				<td>
					<a href="{{action('SellPosController@edit', [$transaction->id]).$subtype}}">
	    				<i class="fas fa-pen text-muted" aria-hidden="true" title="{{__('lang_v1.click_to_edit')}}"></i>
	    			</a>
	    			
	    			<a href="{{action('SellPosController@destroy', [$transaction->id])}}" class="delete-sale" style="padding-left: 20px; padding-right: 20px"><i class="fa fa-trash text-danger" title="{{__('lang_v1.click_to_delete')}}"></i></a>

	    			<!-- <a href="{{action('SellPosController@printInvoice', [$transaction->id])}}" class="print-invoice-link">
	    				<i class="fa fa-print text-muted" aria-hidden="true" title="{{__('lang_v1.click_to_print')}}"></i>
	    			</a> -->

	    			<a onclick="window.open('/nfce/imprimirNaoFiscal/{{$transaction->id}}')" class="print-invoice-link">
	    				<i class="fa fa-print text-muted" aria-hidden="true" title="{{__('lang_v1.click_to_print')}}"></i>
	    			</a>

	    			@if($transaction->numero_nfce > 0)
	    			<a onclick="window.open('/nfce/imprimir/{{$transaction->id}}')" class="print-invoice-link">
	    				<i class="fa fa-print text-success" aria-hidden="true" title="Imprimir fiscal"></i>
	    			</a>
	    			@endif
				</td>
			</tr>
		@endforeach
	</table>
@else
	<p>@lang('sale.no_recent_transactions')</p>
@endif