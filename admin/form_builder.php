<?php

$invoice = new Pronamic\Twinfield\DOM\Invoice();

print_r('<code>');
$xml = $invoice->prepare();

print_r($xml);

print_r('</code>');

?>