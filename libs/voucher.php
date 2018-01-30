<?php
class Voucher {
	function randStr()
	{
		$str = md5(uniqid(rand(), true));
		return strtoupper(substr($str, 0, 6));
	}

	function getListVoucher()
	{
		$content = file_get_contents(VOUCHER_PATH);
		return json_decode($content, 1);
	}

	function addVoucher()
	{
		$listVouchers = $this->getListVoucher();
		$newVc = $this->randStr();
		do {
			if (isset($listVouchers[$newVc])) {
				$newVc = $this->randStr();
				continue;
			} else {
				break;
			}
		} while (1);

		$listVouchers[$newVc] = [
			'used'=>0,
			'expired_at'=>time() + VOUCHER_EXPIRE
		];
		file_put_contents(VOUCHER_PATH,json_encode($listVouchers));
	}

	function validateVoucher($voucher) {
		$listVoucher = $this->getListVoucher();
		if(!isset($listVoucher[$voucher])) {
			throw new Error('Voucher not valid');
		}
		if($listVoucher[$voucher]['expired_at'] < time()) {
			throw new Error('Voucher expired');
		}
		if($listVoucher[$voucher]['used']) {
			throw new Error('Voucher has been taken');
		}
		return true;
	}

	function useVoucher($voucher) {
		$listVoucher = $this->getListVoucher();
		$listVoucher[$voucher]['used'] = 1;
		file_put_contents(VOUCHER_PATH,json_encode($listVoucher));
	}

	function exportVoucher() {
		$listVoucher = $this->getListVoucher();
		$listOk = [];
		foreach ($listVoucher as $voucher=>$data) {
			if($data['used'] == 1 || $data['expired_at'] < time()) continue;
			$listOk[] = $voucher;
		}
		if($listOk) {
			file_put_contents(VOUCHER_EXPORT,implode(PHP_EOL,$listOk));
		}
	}
}