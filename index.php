<?php
require_once 'libs/config.php';
require_once 'libs/voucher.php';
$message = '';
$success = '';
$fullname = @$_POST['fullname'];
$phone = @$_POST['phone'];
$booking_time = @$_POST['booking_time'];
$voucher = @$_POST['voucher'];
$number = @$_POST['number'] ?: 1;
if (isset($_POST['action']) && $_POST['action'] == 'execute') {
	$vc = new Voucher();
	try {
		if ($vc->validateVoucher($voucher)) {
			$vc->useVoucher($voucher);
			//save to file booking
			$line = $fullname . ',' . $booking_time . ',' . $number . ',' . $phone . ',' . $voucher;
			$booking_list = file_get_contents(BOOKING_FILE);
			$booking_list .= PHP_EOL . $line;
			file_put_contents(BOOKING_FILE,$booking_list);
			$success = 'Bạn đã đổi mã thành công';
		}
	} catch (Error $exception) {
		$message = $exception->getMessage();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Panda Chan Voucher</title>
	<link rel="stylesheet" href="skeleton.css" type="text/css">
</head>
<body>
<div class="container">
	<?php
	if ($message) {
		?>
		<p style="margin-top: 10px;text-align: center;font-size: 11px;color:darkred"><?= $message ?></p>
	<?php }
	?>
	<?php
	if ($success) {
		?>
		<p style="margin-top: 10px;text-align: center;font-size: 11px;color:darkblue"><?= $success ?></p>
	<?php }
	?>
	<form class="" action="" method="post">
		<h3>Panda Chan - Đổi mã</h3>
		<input type="hidden" name="action" value="execute">
		<div class="row">
			<div class="columns">
				<label>Họ tên bạn</label>
				<input type="text" name="fullname" value="<?= $fullname ?>" required class="u-full-width">
			</div>
		</div>
		<div class="row">
			<div class="columns">
				<label>Số điện thoại</label>
				<input type="tel" name="phone" value="<?= $phone ?>" required class="u-full-width">
			</div>
		</div>
		<div class="row">
			<div class="columns">
				<label>Giờ đặt bàn</label>
				<input type="datetime-local" name="booking_time" required value="<?= $booking_time ?>"
					   class="u-full-width">
			</div>
		</div>
		<div class="row">
			<div class="columns">
				<label>Số khách</label>
				<input type="number" name="number" required value="<?= $number ?>" class="u-full-width">
			</div>
		</div>
		<div class="row">
			<div class="columns">
				<label>Mã Voucher</label>
				<input type="text" name="voucher" maxlength="6" class="u-full-width" required>
			</div>
		</div>
		<div class="row">
			<div class="columns">
				<button class="button button-primary u-full-width" type="submit">Sử dụng mã</button>
			</div>
		</div>
	</form>
	<h6>Bạn cần trợ giúp? Gọi <a href="tel:0943168777">0943.168.777</a></h6>
	<h6>
		<b>Panda Chan - Quán ăn Nhật Bản truyền thống duy nhất tại Nam Định</b>
		<p style="font-size:11px">Đ/c: 133 Trần Quang Khải - TP Nam Định</p>
	</h6>

</div>
</body>
</html>
