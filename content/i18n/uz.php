<?php
/**
 * Oʻzbek tili (lotin) — sample content. Used once by tools/seed.php.
 */

return array(
	'slugs'    => array( 'home' => 'bosh-sahifa', 'contact' => 'aloqa' ),
	'titles'   => array( 'home' => 'Bosh sahifa', 'contact' => 'Aloqa' ),
	'menu'     => 'Asosiy menyu',

	'hero'     => array(
		array( 'kicker' => 'Arxitektura va interyer dizayni studiyasi', 'title' => "Yorugʻlik yaratgan\nmakonlar", 'button' => 'Loyihalarimiz', 'link' => '#projects' ),
		array( 'kicker' => 'Turar joy · Tijorat · Jamoat', 'title' => "Maqsad bilan\nloyihalaymiz", 'button' => 'Xizmatlarimiz', 'link' => '#services' ),
		array( 'kicker' => 'Loyihangiz haqida gaplashaylik', 'title' => "Birinchi eskizdan\nsoʻnggi detalgacha", 'button' => 'Biz bilan bogʻlaning', 'link' => 'contact' ),
	),

	'intro'    => array(
		'label'     => 'Studiya haqida',
		'statement' => 'Biz yorugʻlik, material va maqsad uygʻunlashgan <em>abadiy arxitektura</em> va interyerlarni yaratamiz.',
		'text'      => 'Via Adlucem — arxitektura, interyer dizayni va rejalashtirish sohalarida ishlaydigan mustaqil studiya. Har bir loyiha tinglashdan boshlanadi — odamlarni, joyni va yorugʻlikni — va yashash uchun qulay makonlar bilan yakunlanadi.',
		'button'    => 'Biz bilan bogʻlaning',
	),

	'services' => array(
		'label' => 'Nima qilamiz',
		'title' => 'Toʻrt yoʻnalish, bitta yondashuv',
		'items' => array(
			array( 'icon' => 'fas fa-building', 'title' => 'Arxitektura', 'text' => 'Konsepsiyadan qurilish hujjatlarigacha loyihalanadigan turar joy va tijorat binolari.' ),
			array( 'icon' => 'fas fa-couch', 'title' => 'Interyer dizayni', 'text' => 'Aniq makon gʻoyasi, tabiiy materiallar va puxta ishlangan detallarga ega interyerlar.' ),
			array( 'icon' => 'fas fa-city', 'title' => 'Shaharsozlik', 'text' => 'Binolarni shahar hayoti bilan bogʻlaydigan bosh rejalar va jamoat makonlari.' ),
			array( 'icon' => 'fas fa-drafting-compass', 'title' => 'Konsalting', 'text' => 'Har bir bosqichda texnik-iqtisodiy asoslash, loyiha ekspertizasi va texnik maslahatlar.' ),
		),
	),

	'projects' => array(
		'label' => 'Tanlangan ishlar',
		'title' => 'Soʻnggi loyihalar',
		'items' => array(
			array( 'title' => 'Caspian Residence', 'category' => 'Turar joy · Boku' ),
			array( 'title' => 'Light Gallery', 'category' => 'Madaniyat · Ganja' ),
			array( 'title' => 'Atrium Office', 'category' => 'Tijorat · Boku' ),
			array( 'title' => 'Stone House', 'category' => 'Turar joy · Gabala' ),
			array( 'title' => 'Harbour Pavilion', 'category' => 'Jamoat · Sumgait' ),
			array( 'title' => 'Loft Interior', 'category' => 'Interyer · Toshkent' ),
		),
	),

	'offer'    => array(
		'label' => 'Xizmatlar',
		'title' => 'Birinchi gʻoyadan tayyor makongacha toʻliq xizmat.',
		'text'  => 'Har bir loyihani barcha bosqichlardan olib oʻtamiz, toki dizayn gʻoyasi soʻnggi detalgacha saqlansin.',
		'items' => array(
			array( 'title' => 'Konsepsiya va texnik-iqtisodiy asoslash', 'text' => 'Hudud, topshiriq va byudjetni oʻrganib, butun loyiha yoʻnalishini belgilovchi aniq konsepsiyaga aylantiramiz.' ),
			array( 'title' => 'Arxitektura loyihasi', 'text' => 'Eskiz loyihadan ishchi chizmalargacha funksional, samarali va tabiiy yorugʻlikka boy binolarni ishlab chiqamiz.' ),
			array( 'title' => 'Interyer dizayni', 'text' => 'Rejalashtirish, materiallar, yoritish va maxsus mebel — sokin, amaliy va shaxsiy interyerlar.' ),
			array( 'title' => 'Qurilish hujjatlari', 'text' => 'Pudratchilarga loyihani aynan qurish imkonini beruvchi aniq texnik chizmalar va spetsifikatsiyalar.' ),
			array( 'title' => 'Mualliflik nazorati', 'text' => 'Sifat, xarajat va muddatlarni nazoratda saqlash uchun qurilish maydoniga muntazam tashriflar va pudratchilar bilan muvofiqlashtirish.' ),
		),
	),

	'studio'   => array(
		'label'     => 'Studiyamiz',
		'statement' => '2015-yildan beri maqsadli makonlar yaratamiz',
		'text'      => 'Har bir loyihaga shaxsiy eʼtibor qaratish uchun yetarlicha kichik, murakkab binolarni oʻz vaqtida topshirish uchun yetarlicha tajribalimiz.',
		'numbers'   => array(
			array( 'number' => 120, 'suffix' => '+', 'title' => 'Amalga oshirilgan loyihalar' ),
			array( 'number' => 10, 'suffix' => '+', 'title' => 'Yillik tajriba' ),
			array( 'number' => 25, 'suffix' => '', 'title' => 'Jamoa aʼzolari' ),
			array( 'number' => 8, 'suffix' => '', 'title' => 'Shaharlar' ),
		),
	),

	'contact'  => array(
		'label'         => 'Aloqa',
		'title'         => 'Keyingi loyihangiz haqida gaplashaylik',
		'text'          => 'Rejalaringiz haqida bizga yozing — odatda bir ish kuni ichida javob beramiz.',
		'details_label' => 'Aloqa maʼlumotlari',
		'studio'        => 'Studiya',
		'phone'         => 'Telefon',
		'email'         => 'Elektron pochta',
		'hours'         => 'Ish vaqti',
		'hours_text'    => 'Dushanba – Juma, 9:00 – 18:00',
		'form_label'    => 'Bizga yozing',
		'form_title'    => 'Xabar yuboring',
		'form_text'     => 'Shaklni toʻldiring, jamoamiz tez orada siz bilan bogʻlanadi.',
		'map'           => 'Baku, Azerbaijan',
	),

	'form'     => array(
		'title'    => 'Aloqa shakli (UZ)',
		'name'     => 'Ismingiz',
		'email'    => 'Elektron pochta',
		'phone'    => 'Telefon',
		'subject'  => 'Mavzu',
		'message'  => 'Xabar',
		'submit'   => 'Xabarni yuborish',
		'messages' => array(
			'mail_sent_ok'     => 'Rahmat! Xabaringiz yuborildi.',
			'mail_sent_ng'     => 'Xabar yuborishda xatolik yuz berdi. Iltimos, keyinroq qayta urinib koʻring.',
			'validation_error' => 'Bir yoki bir nechta maydonda xatolik bor. Iltimos, tekshirib, qayta urinib koʻring.',
			'invalid_required' => 'Iltimos, ushbu maydonni toʻldiring.',
			'invalid_email'    => 'Iltimos, toʻgʻri elektron pochta manzilini kiriting.',
		),
	),

	// Theme strings: English source key => translation.
	'strings'  => array(
		'Menu'                                    => 'Menyu',
		'Close'                                   => 'Yopish',
		'Let’s design your next space together.' => 'Keyingi makoningizni birgalikda loyihalaylik.',
		'Get in touch'                            => 'Biz bilan bogʻlaning',
		'An architecture and interior design studio creating timeless, light-filled spaces.' => 'Yorugʻlikka toʻla, abadiy makonlar yaratadigan arxitektura va interyer dizayni studiyasi.',
		'Visit us'                                => 'Manzilimiz',
		'Talk to us'                              => 'Aloqa',
		'Navigate'                                => 'Navigatsiya',
		'28 May Street 15, Baku, Azerbaijan'      => '28 May koʻchasi 15, Boku, Ozarbayjon',
		'© %s Via Adlucem. All rights reserved.' => '© %s Via Adlucem. Barcha huquqlar himoyalangan.',
		'Back to top'                             => 'Yuqoriga',
	),
	'tagline'  => 'Arxitektura va interyer dizayni studiyasi',
);
