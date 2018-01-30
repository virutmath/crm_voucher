<?php
require_once 'libs/config.php';
require_once 'libs/voucher.php';
for($i = 0;$i<100;$i++) {
	$vc = new Voucher();
	$vc->addVoucher();
}