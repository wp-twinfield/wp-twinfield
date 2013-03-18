<?php

$customer = new Pronamic\Twinfield\Customer\Customer();
$customer->setID(1002);

$order = new Pronamic\Twinfield\Invoice\Order();
$order
	->setQuantity(5)
	->setArticle(15);

$invoice = new Pronamic\Twinfield\Invoice\Invoice();
$invoice
	->setInvoiceType('FACTUUR')
	->addOrder( $order )
	->setCustomer( $customer );






?>

<form method="POST" class="input-form">
	<input type="hidden" name="twinfield_form" value="true" />
	<p>
		<label>Invoice Type</label>
		<input type="text" name="invoiceType" value="<?php echo $invoice->getInvoiceType(); ?>"/>
	</p>
	<p>
		<label>Customer</label>
		<input type="text" name="customerID" value="<?php echo $invoice->getCustomer()->getID(); ?>" />
	</p>
	<p>
		<label>Quantity</label>
		<input type="text" name="quantity" value="<?php echo $order->getQuantity(); ?>"/>
	</p>
	<p>
		<label>Article</label>
		<input type="text" name="article" value="<?php echo $order->getArticle(); ?>"/>
	</p>
	<input type="submit" value="Send"/>
</form>