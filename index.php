<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'vendor/autoload.php';

use Telegram\Api;

$admin = 5769803593;
$base_url = ''; // admin panel manzili 
$api = new Api(''); // bot tokeni
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$mid = $message->message_id;
$cid = $message->chat->id;
$tx = $message->text;
$data = $update->callback_query->data;
$contact = $message->contact;
$phone = $contact->phone_number;
$fname = $message->from->first_name;
$uid = $message->from->id;
$update = json_decode(file_get_contents('php://input'));
$callback = $update->callback_query;
$ccid = $callback->message->chat->id;
$cqid = $update->callback_query->id;
$cid2 = $update->callback_query->message->chat->id;
$uid2 = $update->callback_query->from->id;
$fname2 = $update->callback_query->from->first_name;
$mid2 = $update->callback_query->message->message_id;
$qid = $update->callback_query->id;
$cbid = $update->callback_query->from->id;
$step = $api->files->get("cache/" . $cid . ".txt");
mkdir('cache');
mkdir('cache/category');
mkdir('cache/product');
mkdir('cache/auto');
// mkdir('cache/price');



$phone_btn = $api->keyboard->resize([['text' => 'Raqam yuborish', 'request_contact' => true]]);

$ph = json_encode([
	'resize_keyboard' => true,
	'keyboard' => [[
		['text' => 'Raqamni yuborish', 'request_contact' => true],
	]],
]);

$admin_btn = $api->keyboard->inline(['text' => 'admin']);



if ($tx == '◀️ Ortga qaytish') {
	if ($step == 'product' or $step == 'category') {
		$user = json_decode(file_get_contents($base_url.'/api/user?chat_id=' . $cid), true);
		$user_phone = $user['phone'];

		$auto = json_decode(file_get_contents($base_url.'/api/auto'), true);

		$auto_array = [];
		foreach ($auto as $item) {
			$auto_array[] = [$item['rusum']];
		}
		$auto_array[] = [['text' => '📱 Bog`lanish'], ['text' => '📍 Manzil']];

		$auto_btn = $api->keyboard->resize($auto_array);

		if ($user['message'] == 'not found') {
			$user = json_decode(file_get_contents($base_url.'/api/add-user?chat_id=' . $cid), true);
		}

		if ($user['phone'] == null or $user['phone'] == 'null' or $user['phone'] == '') {
			$api->files->put("cache/$cid.txt", 'phone');
			$api->sendMessage($cid, "
				Assalomu alekum xurmatli mijoz botdan foydalanish uchun telefon raqamingizni yuboring
				", ['reply_markup' => $ph]);
		} else {
			$api->files->put("cache/$cid.txt", 'category');
			// $api->files->put("cache/c_$cid.txt", 'category');
			$api->sendMessage($cid, "
				Assalomu alekum xurmatli mijoz botdan foydalanayotganingizdan xursandmiz. o'zingizga kerakli bo'limni tanlang
				", ['reply_markup' => $auto_btn]);
		}
	}
	if ($step == 'product_one') {
		$auto = $api->files->get("cache/auto/$cid.txt");
		$category = json_decode(file_get_contents($base_url.'/api/category?model=' . $auto), true);
		$category_array = [];
		foreach ($category as $item) {
			$category_array[] = [$item['name']];
		}
		// $category_array[] = ['ortga qaytish'];
		$category_array[] = [['text' => '◀️ Ortga qaytish'], ['text' => '❌ Bekor qilish']];
		$category_btn = $api->keyboard->resize($category_array);

		$api->files->put("cache/$cid.txt", 'product');

		$api->sendMessage($cid, "
			O'zingizga kerakli categoryani tanlangs
			", ['reply_markup' => $category_btn]);
	}
}

if ($tx == '❌ Bekor qilish') {
	$api->files->put("cache/$cid.txt", '');
	// file_get_contents($base_url.'/api/user-step?chat_id='.$cid.'&step=');
	$api->sendMessage($cid,"Xurmatli mijoz siz amallarni bekor qildingiz qaytadan /start buyrug'ini bosing");
}

if ($tx == '/start' and $step == '') {
	$user = json_decode(file_get_contents($base_url.'/api/user?chat_id=' . $cid), true);
	$user_phone = $user['phone'];

	$auto = json_decode(file_get_contents($base_url.'/api/auto'), true);

	$auto_array = [];
	foreach ($auto as $item) {
		$auto_array[] = [$item['rusum']];
	}

	$auto_array[] = [['text' => '📱 Bog`lanish'], ['text' => '📍 Manzil']];
	// $auto_array[] = ['bekor qilish'];

	$auto_btn = $api->keyboard->resize($auto_array);

	if ($user['message'] == 'not found') {
		$user = json_decode(file_get_contents($base_url.'/api/add-user?chat_id=' . $cid), true);
	}

	if ($user['phone'] == null or $user['phone'] == 'null' or $user['phone'] == '') {
		$api->files->put("cache/$cid.txt", 'phone');
		$api->sendMessage($cid, "
			Assalomu alekum xurmatli mijoz botdan foydalanish uchun telefon raqamingizni yuboring
			", ['reply_markup' => $ph]);
	} else {
		$api->files->put("cache/$cid.txt", 'category');
		// $api->files->put("cache/c_$cid.txt", 'category');
		$api->sendMessage($cid, "
			Assalomu alekum xurmatli mijoz botdan foydalanayotganingizdan xursandmiz. o'zingizga kerakli bo'limni tanlang
			", ['reply_markup' => $auto_btn]);
	}
}

if ($step == 'phone' and $contact) {
	json_decode(file_get_contents($base_url.'/api/user-set-phone?chat_id=' . $cid . '&phone=' . $phone, ), true);
	$auto = json_decode(file_get_contents($base_url.'/api/auto'), true);

	$auto_array = [];
	foreach ($auto as $item) {
		$auto_array[] = [$item['rusum']];
	}
	$auto_array = [['text' => '📱 Bog`lanish'], ['text' => '📍 Manzil']];

	// $auto_array[] = [['bekor qilish']];

	$auto_btn = $api->keyboard->resize($auto_array);
	$api->files->put("cache/$cid.txt", 'category');

	$api->sendMessage($cid, "
		Assalomu alekum xurmatli mijoz botdan foydalanayotganingizdan xursandmiz. o'zingizga kerakli bo'limni tanlang
		", ['reply_markup' => $auto_btn]);
}

if ($tx == '📱 Bog`lanish') {
	$api->files->put("cache/$cid.txt", '');

	$api->sendMessage($cid,"
		📱 Telefon: +998331360005
		📱 Telefon: +998905646979
		🧑‍💻 Admin: @avtozapchastladakokand
		");
}
if ($tx == '📍 Manzil') {
	$api->files->put("cache/$cid.txt", '');
	$api->on('sendLocation',[
		'chat_id'=>$cid,
		'latitude'=>40.555233,
		'longitude'=>70.958716
	]);
}

if ($step == 'category' and ($tx != '❌ Bekor qilish' and $tx != '◀️ Ortga qaytish' and $tx != '📱 Bog`lanish' and $tx != '📍 Manzil')) {
	$api->files->put("cache/auto/$cid.txt", $tx);
	$tx = str_replace(' ','_',$tx);
	$category = json_decode(file_get_contents($base_url.'/api/category?model=' . $tx), true);
	$category_array = [];
	foreach ($category as $item) {
		$category_array[] = [$item['name']];
	}
	$api->files->put("cache/auto/$cid.txt", $tx);
	// $category_array[] = ['ortga qaytish'];
	$category_array[] = [['text' => '◀️ Ortga qaytish'], ['text' => '❌ Bekor qilish']];
	$category_btn = $api->keyboard->resize($category_array);

	$api->files->put("cache/$cid.txt", 'product');

	$api->sendMessage($cid, "
		O'zingizga kerakli categoryani tanlang
		", ['reply_markup' => $category_btn]);
}
if ($step == 'product' and ($tx != '❌ Bekor qilish' and $tx != '◀️ Ortga qaytish')) {
	$auto = $api->files->get("cache/auto/$cid.txt");
	$api->files->put("cache/category/$cid.txt", $tx);
	$tx = str_replace(' ','_',$tx);
	$auto = str_replace(' ','_',$auto);
	$products = json_decode(file_get_contents($base_url.'/api/products?category=' . $tx . '&auto=' . $auto), true);
	$products_array = [];
	foreach ($products as $item) {
		$products_array[] = [$item['title']];
	}
	$products_array[] = [['text' => '◀️ Ortga qaytish'], ['text' => '❌ Bekor qilish']];
	$products_btn = $api->keyboard->resize($products_array);
	$api->files->put("cache/$cid.txt", 'product_one');
	$api->sendMessage($cid, "O'zingizga kerakli maxsulotni tanlang", ['reply_markup' => $products_btn]);
}
if ($step == 'product_one' and $tx != '❌ Bekor qilish' and $x != '◀️ Ortga qaytish') {
	// $api->sendMessage($cid,$tx);
	$user = json_decode(file_get_contents('https://azimjonn2003.uztan.ga/api/user?chst_id=' . $cid), true);
	$user_phone = $user['phone'];
	$api->files->put("cache/products/$cid.txt", $tx);
	$auto = $api->files->get("cache/auto/$cid.txt");
	$tx = str_replace(' ','_',$tx);
	$auto = str_replace(' ','_',$auto);
	$product = json_decode(file_get_contents($base_url.'/api/product_one?title='.$tx.'&auto='.$auto), true);
	$api->files->put("cache/product/$cid.txt", $product['product']['name']."\n  💶 Narxi: ".$product['product']['price']);

	if (count($product['images']) > 1) {
		foreach ($product['images'] as $item) {
			$api->sendPhoto($cid, $item, '');
		}
		$api->sendMessage($cid, $product['product']['description'] . "\n Narxi: " . $product['product']['price'], ['reply_markup' => json_encode([
			"inline_keyboard" => [
				[
					[
						"text" => "Buyurtma berish",
						"callback_data" => 'send',
					],
				],
			],
		])]);
	} else {
		$api->sendPhoto($cid, $product['images'][0], $product['product']['description'] . "\n Narxi: " . $product['product']['price'], ['reply_markup' => json_encode([
			"inline_keyboard" => [
				[
					[
						"text" => "Buyurtma berish",
						"callback_data" => 'send',
					],
				],
			],
		])]);
	}
}


if ($data == 'send') {
	$user = json_decode(file_get_contents($base_url.'/api/user?chat_id=' . $cid2), true);
	$user_phone = $user['phone'];
	$auto = $api->files->get("cache/auto/$cid2.txt");
	$category = $api->files->get("cache/category/$cid2.txt");
	$product = $api->files->get("cache/product/$cid2.txt");
	// $api->sendMessage($cid,'salom: '.$fname,['parse_mode'=>'markdown']);
	$api->sendMessage($admin, "
		🧑 Buyurtmachi: $fname2
		📱 Telefon raqami: $user_phone
		🚘 Avtomobil: $auto
		🔗 Categorya: $category,
		🛠 Maxsulot: $product
		", ['parse_mode' => 'html']);
	$api->on('answerCallbackQuery', [
		'callback_query_id' => $cqid,
		'chat_id' => $ccid,
		'text' => "Sizning Buyurtmangiz adminlarga yetkazildi",
		'show_alert' => true,
		'parse_mode' => 'html',
	]);
}

if ($tx == '/test' and $tx != 'bekor qilish') {
	$user = json_decode(file_get_contents($base_url.'/api/user?chat_id=' . $cid), true);
	$user_phone = $user['phone'];
	// $api->sendMessage($cid,'salom: '.$fname,['parse_mode'=>'markdown']);
	$api->sendMessage($admin, "
		Buyurtmachi: $fname
		Telefon raqami: $user_phone
		", ['parse_mode' => 'html']);

}