<?php
/**
 * Loyihalar — Oʻzbek tili (lotin), az.php dan tarjima. Same structure and order as az.php.
 */

return array(
	'menu'           => 'Loyihalar',
	'categories'     => array(
		'residential'    => 'Turar joy',
		'commercial'     => 'Tijorat',
		'infrastructure' => 'Jamoat va infratuzilma',
	),
	'tags'           => array(
		'bim'          => 'BIM',
		'master-plan'  => 'Bosh reja',
		'architecture' => 'Arxitektura',
		'structural'   => 'Konstruktiv muhandislik',
		'mep'          => 'MEP',
		'civil'        => 'Fuqarolik infratuzilmasi',
		'agdam'        => 'Agʻdam',
	),
	'labels'         => array(
		'location'      => 'Joylashuv',
		'period'        => 'Davr',
		'services'      => 'Xizmatlar',
		'overview'      => 'Loyiha haqida umumiy maʼlumot',
		'features'      => 'Hudud xususiyatlari va muhandislik yondashuvi',
		'objects'       => 'Loyiha obyektlari va texnik koʻrsatkichlar',
		'col_object'    => 'Obyekt',
		'col_area'      => 'Hudud maydoni',
		'col_elevation' => 'Mutlaq balandlik (±0.00)',
	),
	'services_intro' => 'Ichki ekspertizamizga tayangan holda koʻrsatgan asosiy loyihalash xizmatlarimiz:',
	'services_list'  => array( 'Bosh reja', 'Arxitektura', 'Konstruksiya', 'Muhandislik tizimlari' ),
	'overview'       => 'Ushbu kompleks loyiha buyurtmachimiz — «Qorabogʻ iqtisodiy rayonida tiklash, qurilish va boshqaruv xizmati» ommaviy-huquqiy shaxsi bilan yaqin hamkorlikda, tegishli shartnoma va loyiha topshirigʻi asosida ishlab chiqilgan. Loyihalash jarayonida Agʻdam shahrining tasdiqlangan yangi bosh rejasiga muvofiq chegara nuqtalari va jamoamiz tomonidan tayyorlangan hududning topografik xaritasi asos qilib olingan.',
	'features'       => array(
		array( 'Relyef va geologiya:', 'Hudud qisman vayronaga uchragan boʻlsa-da, unda oz miqdorda maishiy chiqindilar aralashgan unumdor tuproq qatlami mavjud. Tekis relyefga ega hudud muhandislik-geologik tadqiqotlar natijalariga koʻra 8 balli seysmik zonaga kiritilgan.' ),
		array( 'Infratuzilma va xavfsizlik:', 'Barcha binolar atrofida yongʻin oʻchirish va tez tibbiy yordam mashinalari manevr qilishi uchun maxsus yoʻllar, shuningdek, qulay va xavfsiz piyodalar harakatini taʼminlaydigan bino atrofi yoʻlaklari nazarda tutilgan.' ),
		array( 'Obodonlashtirish:', 'Loyihalanayotgan hududlar yangi bosh rejaga muvofiq yashil zonalar, tadbirlar uchun maydonchalar va zamonaviy yoʻl-yoʻlak infratuzilmasi bilan toʻliq obodonlashtiriladi.' ),
	),
	'projects'       => array(
		array(
			'title'         => 'Göytəpə: kompleks ishchi loyihani tayyorlash',
			'slug'          => 'goytepe-kompleks-ishchi-loyiha',
			'excerpt'       => 'Agʻdam tumani Göytəpə qishlogʻida bolalar bogʻchasi, tibbiyot punkti, maʼmuriy bino va maktabdan iborat majmuaning bosh reja, arxitektura, konstruksiya va muhandislik tizimlari loyihasi.',
			'location'      => 'Agʻdam tumani, Göytəpə qishlogʻi',
			'objects_intro' => 'Loyiha doirasida har bir binaning 1-qavati pol sathi shartli ravishda 0.000 deb qabul qilingan. Quyida majmuaga kiruvchi asosiy binolarning bosh reja koʻrsatkichlari keltirilgan:',
			'objects'       => array(
				array( '2 qavatli, 80 oʻrinli bolalar bogʻchasi (1-bosqich)', '1.28 ga', '+287.90' ),
				array( '1 qavatli tibbiyot punkti', '3.94 ga', '+286.65' ),
				array( '2 qavatli maʼmuriy bino', '1.15 ga', '+286.40' ),
				array( '2 qavatli, 432 oʻrinli maktab binosi', '2.59 ga', '+287.20' ),
			),
		),
		array(
			'title'         => 'Güllücə: kompleks ishchi loyihani tayyorlash',
			'slug'          => 'gulluce-kompleks-ishchi-loyiha',
			'excerpt'       => 'Agʻdam tumani Güllücə qishlogʻida maktablar, bolalar bogʻchalari, maʼmuriy bino, tibbiyot punkti va qabriston majmuasining bosh reja, arxitektura, konstruksiya va muhandislik tizimlari loyihasi.',
			'location'      => 'Agʻdam tumani, Güllücə qishlogʻi',
			'objects_intro' => 'Loyiha doirasida har bir binaning 1-qavati pol sathi shartli ravishda 0.000 deb qabul qilingan. Quyida majmuaga kiruvchi turli maqsadli binolar va zonalarning bosh reja koʻrsatkichlari keltirilgan:',
			'objects'       => array(
				array( '2 qavatli, 80 oʻrinli bolalar bogʻchasi (2-bosqich)', '2.23 ga', '+248.35' ),
				array( '2 qavatli maʼmuriy bino va 1 qavatli tibbiyot punkti', '5.63 ga', 'Maʼmuriy bino: +229.55 | Tibbiyot punkti: +228.05' ),
				array( '2 qavatli, 480 oʻrinli maktab binosi va 2 qavatli, 120 oʻrinli bolalar bogʻchasi (1-bosqich)', '5.36 ga', 'Maktab binosi: +235.60 | Bogʻcha binosi: +234.55' ),
				array( '2 qavatli, 480 oʻrinli maktab binosi va 2 qavatli, 80 oʻrinli bolalar bogʻchasi (2-bosqich)', '3.75 ga', 'Maktab binosi: +218.83 | Bolalar bogʻchasi: +219.52' ),
				array( 'Qabriston majmuasi (konservatsiya qilingan)', '2.86 ga', 'Gʻassolxona: +242.58 | Qoʻriqlash budkasi: +241.44' ),
			),
		),
		array(
			'title'         => 'Salahlı: kompleks ishchi loyihani tayyorlash',
			'slug'          => 'salahli-kompleks-ishchi-loyiha',
			'excerpt'       => 'Agʻdam tumani Salahlı qishlogʻida maʼmuriy bino, tibbiyot punkti, maktab va bolalar bogʻchasining bosh reja, arxitektura, konstruksiya va muhandislik tizimlari loyihasi.',
			'location'      => 'Agʻdam tumani, Salahlı qishlogʻi',
			'objects_intro' => 'Loyiha doirasida har bir binaning 1-qavati pol sathi shartli ravishda 0.000 deb qabul qilingan. Quyida majmuaga kiruvchi turli maqsadli binolarning bosh reja koʻrsatkichlari keltirilgan:',
			'objects'       => array(
				array( '2 qavatli maʼmuriy bino va 1 qavatli tibbiyot punkti', '4.26 ga', 'Maʼmuriy bino: +344.70 | Tibbiyot punkti: +343.65' ),
				array( '2 qavatli, 480 oʻrinli maktab binosi va 2 qavatli, 80 oʻrinli bolalar bogʻchasi (2-bosqich)', '3.75 ga', 'Maktab binosi: +218.83 | Bolalar bogʻchasi: +219.52' ),
			),
		),
	),
	'strings'        => array(
		'Projects'              => 'Loyihalar',
		'All'                   => 'Barchasi',
		'Home'                  => 'Bosh sahifa',
		'All projects'          => 'Barcha loyihalar',
		'No projects here yet.' => 'Bu yerda hozircha loyihalar yoʻq.',
		'Share'                 => 'Ulashish',
		'Previous project'      => 'Oldingi loyiha',
		'Next project'          => 'Keyingi loyiha',
	),
);
