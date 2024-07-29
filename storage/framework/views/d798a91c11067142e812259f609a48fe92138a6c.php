<!-- app css -->
<?php if(!empty($for_pdf)): ?>
	<link rel="stylesheet" href="<?php echo e(asset('css/app.css?v='.$asset_v), false); ?>">
<?php endif; ?>
<div class="col-md-12 col-sm-12 <?php if(!empty($for_pdf)): ?> width-100 align-right <?php endif; ?>">
        <p class="text-right align-right"><strong><?php echo e($contact->business->name, false); ?></strong><br><?php echo $contact->business->business_address; ?></p>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 <?php if(!empty($for_pdf)): ?> width-50 f-left <?php endif; ?>">
	<p class="blue-heading p-4 width-50">DADOS:</p>
	<p><strong><?php echo e($contact->name, false); ?></strong><br> <?php echo $contact->contact_address; ?> <?php if(!empty($contact->email)): ?> <br><?php echo app('translator')->get('business.email'); ?>: <?php echo e($contact->email, false); ?> <?php endif; ?>
	<br><?php echo app('translator')->get('contact.mobile'); ?>: <?php echo e($contact->mobile, false); ?>

	<?php if(!empty($contact->tax_number)): ?> <br><?php echo app('translator')->get('contact.tax_no'); ?>: <?php echo e($contact->tax_number, false); ?> <?php endif; ?>
</p>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 <?php if(!empty($for_pdf)): ?> width-50 f-left <?php endif; ?>">
	<h3 class="mb-0 blue-heading p-4"><?php echo app('translator')->get('lang_v1.account_summary'); ?></h3>
	<small><?php echo e($ledger_details['start_date'], false); ?> <?php echo app('translator')->get('lang_v1.to'); ?> <?php echo e($ledger_details['end_date'], false); ?></small>
	<hr>
	<table class="table table-condensed text-left align-left no-border <?php if(!empty($for_pdf)): ?> table-pdf <?php endif; ?>">
		<tr>
			<td><?php echo app('translator')->get('lang_v1.opening_balance'); ?></td>
			<td class="align-right"><?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $ledger_details['beginning_balance'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?></td>
		</tr>
	<?php if( $contact->type == 'supplier' || $contact->type == 'both'): ?>
		<tr>
			<td><?php echo app('translator')->get('report.total_purchase'); ?></td>
			<td class="align-right"><?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $ledger_details['total_purchase'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?></td>
		</tr>
	<?php endif; ?>
	<?php if( $contact->type == 'customer' || $contact->type == 'both'): ?>
		<tr>
			<td><?php echo app('translator')->get('lang_v1.total_invoice'); ?></td>
			<td class="align-right"><?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $ledger_details['total_invoice'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?></td>
		</tr>
	<?php endif; ?>
	<tr>
		<td><?php echo app('translator')->get('sale.total_paid'); ?></td>
		<td class="align-right"><?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $ledger_details['total_paid'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?></td>
	</tr>
	<tr>
		<td><strong><?php echo app('translator')->get('lang_v1.balance_due'); ?></strong></td>
		<td class="align-right"><?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $ledger_details['balance_due'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?></td>
	</tr>
	</table>
</div>
<div class="col-md-12 col-sm-12 <?php if(!empty($for_pdf)): ?> width-100 <?php endif; ?>">
	<p class="text-center" style="text-align: center;"><strong><?php echo app('translator')->get('lang_v1.ledger_table_heading', ['start_date' => $ledger_details['start_date'], 'end_date' => $ledger_details['end_date']]); ?></strong></p>
	<table class="table table-striped <?php if(!empty($for_pdf)): ?> table-pdf td-border <?php endif; ?>" id="ledger_table">
		<thead>
			<tr class="row-border blue-heading">
				<th><?php echo app('translator')->get('lang_v1.date'); ?></th>
				<th><?php echo app('translator')->get('purchase.ref_no'); ?></th>
				<th><?php echo app('translator')->get('lang_v1.type'); ?></th>
				<th><?php echo app('translator')->get('sale.location'); ?></th>
				<th><?php echo app('translator')->get('sale.payment_status'); ?></th>
				<th><?php echo app('translator')->get('sale.total'); ?></th>
				<th><?php echo app('translator')->get('account.debit'); ?></th>
				<th><?php echo app('translator')->get('account.credit'); ?></th>
				<th><?php echo app('translator')->get('lang_v1.payment_method'); ?></th>
				<th><?php echo app('translator')->get('report.others'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $ledger_details['ledger']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr <?php if(!empty($for_pdf) && $loop->iteration % 2 == 0): ?> class="odd" <?php endif; ?>>
					<td class="row-border"><?php echo e(\Carbon::createFromTimestamp(strtotime($data['date']))->format(session('business.date_format') . ' ' . 'H:i'), false); ?></td>
					<td><?php echo e($data['ref_no'], false); ?></td>
					<td><?php echo e($data['type'], false); ?></td>
					<td><?php echo e($data['location'], false); ?></td>
					<td><?php echo e($data['payment_status'], false); ?></td>
					<td class="ws-nowrap align-right"><?php if($data['total'] !== ''): ?> <?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $data['total'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?> <?php endif; ?></td>
					<td class="ws-nowrap align-right"><?php if($data['debit'] != ''): ?> <?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $data['debit'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?> <?php endif; ?></td>
					<td class="ws-nowrap align-right"><?php if($data['credit'] != ''): ?> <?php 
            $formated_number = "";
            if (session("business.currency_symbol_placement") == "before") {
                $formated_number .= session("currency")["symbol"] . " ";
            } 
            $formated_number .= number_format((float) $data['credit'], config("constants.currency_precision", 2) , session("currency")["decimal_separator"], session("currency")["thousand_separator"]);

            if (session("business.currency_symbol_placement") == "after") {
                $formated_number .= " " . session("currency")["symbol"];
            }
            echo $formated_number; ?> <?php endif; ?></td>
					<td><?php echo e($data['payment_method'], false); ?></td>
					<td><?php echo $data['others']; ?></td>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>
</div><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/contact/ledger.blade.php ENDPATH**/ ?>