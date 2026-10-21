<?php
/**
 * Azərbaycan dili — sample content. Used once by tools/seed.php.
 */

return array(
	'slugs'    => array( 'home' => 'ana-sehife', 'contact' => 'elaqe' ),
	'titles'   => array( 'home' => 'Ana səhifə', 'contact' => 'Əlaqə' ),
	'menu'     => 'Əsas menyu',

	'hero'     => array(
		array( 'kicker' => 'Memarlıq və interyer dizaynı studiyası', 'title' => "İşığın yaratdığı\nməkanlar", 'button' => 'Layihələrimiz', 'link' => '#projects' ),
		array( 'kicker' => 'Yaşayış · Kommersiya · İctimai', 'title' => "Məqsədyönlü\nlayihələndirmə", 'button' => 'Xidmətlərimiz', 'link' => '#services' ),
		array( 'kicker' => 'Layihəniz haqqında danışaq', 'title' => "İlk eskizdən\nson detala qədər", 'button' => 'Bizimlə əlaqə', 'link' => 'contact' ),
	),

	'intro'    => array(
		'label'     => 'Studiya haqqında',
		'statement' => 'Biz işığın, materialın və məqsədin birləşdiyi <em>zamansız memarlıq</em> və interyerlər yaradırıq.',
		'text'      => 'Via Adlucem memarlıq, interyer dizaynı və planlaşdırma sahələrində fəaliyyət göstərən müstəqil studiyadır. Hər layihə dinləməklə başlayır — insanları, məkanı və işığı — və yaşamaq üçün rahat məkanlarla başa çatır.',
		'button'    => 'Bizimlə əlaqə',
	),

	'services' => array(
		'label' => 'Nə edirik',
		'title' => 'Dörd istiqamət, bir yanaşma',
		'items' => array(
			array( 'icon' => 'fas fa-building', 'title' => 'Memarlıq', 'text' => 'Konsepsiyadan tikinti sənədlərinə qədər layihələndirilən yaşayış və kommersiya binaları.' ),
			array( 'icon' => 'fas fa-couch', 'title' => 'İnteryer dizaynı', 'text' => 'Aydın məkan ideyası, təbii materiallar və diqqətlə işlənmiş detallarla interyerlər.' ),
			array( 'icon' => 'fas fa-city', 'title' => 'Şəhərsalma', 'text' => 'Binaları şəhər həyatı ilə birləşdirən baş planlar və ictimai məkanlar.' ),
			array( 'icon' => 'fas fa-drafting-compass', 'title' => 'Konsaltinq', 'text' => 'Hər mərhələdə texniki-iqtisadi əsaslandırma, layihə ekspertizası və texniki dəstək.' ),
		),
	),

	'projects' => array(
		'label' => 'Seçilmiş işlər',
		'title' => 'Son layihələr',
		'items' => array(
			array( 'title' => 'Caspian Residence', 'category' => 'Yaşayış · Bakı' ),
			array( 'title' => 'Light Gallery', 'category' => 'Mədəniyyət · Gəncə' ),
			array( 'title' => 'Atrium Office', 'category' => 'Kommersiya · Bakı' ),
			array( 'title' => 'Stone House', 'category' => 'Yaşayış · Qəbələ' ),
			array( 'title' => 'Harbour Pavilion', 'category' => 'İctimai · Sumqayıt' ),
			array( 'title' => 'Loft Interior', 'category' => 'İnteryer · Daşkənd' ),
		),
	),

	'offer'    => array(
		'label' => 'Xidmətlər',
		'title' => 'İlk ideyadan hazır məkana qədər tam xidmət.',
		'text'  => 'Hər layihəni bütün mərhələlərdən keçiririk ki, dizayn ideyası son detala qədər qorunsun.',
		'items' => array(
			array( 'title' => 'Konsepsiya və texniki-iqtisadi əsaslandırma', 'text' => 'Ərazini, tapşırığı və büdcəni öyrənir, bütün layihənin istiqamətini müəyyən edən aydın konsepsiyaya çeviririk.' ),
			array( 'title' => 'Memarlıq layihələndirməsi', 'text' => 'Eskiz layihəsindən işçi çertyojlarına qədər funksional, səmərəli və təbii işıqla dolu binalar hazırlayırıq.' ),
			array( 'title' => 'İnteryer dizaynı', 'text' => 'Planlaşdırma, materiallar, işıqlandırma və fərdi mebel — sakit, praktik və şəxsi interyerlər.' ),
			array( 'title' => 'Tikinti sənədləri', 'text' => 'Podratçıların layihəni olduğu kimi tikməsinə imkan verən dəqiq texniki çertyojlar və spesifikasiyalar.' ),
			array( 'title' => 'Müəllif nəzarəti', 'text' => 'Keyfiyyəti, xərcləri və cədvəli nəzarətdə saxlamaq üçün tikinti meydançasına müntəzəm səfərlər və podratçılarla koordinasiya.' ),
		),
	),

	'studio'   => array(
		'label'     => 'Studiyamız',
		'statement' => '2015-ci ildən məqsədli məkanlar yaradırıq',
		'text'      => 'Hər layihəyə şəxsi diqqət ayıracaq qədər kiçik, mürəkkəb binaları vaxtında təhvil verəcək qədər təcrübəliyik.',
		'numbers'   => array(
			array( 'number' => 120, 'suffix' => '+', 'title' => 'Həyata keçirilmiş layihə' ),
			array( 'number' => 10, 'suffix' => '+', 'title' => 'İllik təcrübə' ),
			array( 'number' => 25, 'suffix' => '', 'title' => 'Komanda üzvü' ),
			array( 'number' => 8, 'suffix' => '', 'title' => 'Şəhər' ),
		),
	),

	'contact'  => array(
		'label'         => 'Əlaqə',
		'title'         => 'Növbəti layihəniz haqqında danışaq',
		'text'          => 'Planlarınız barədə bizə yazın — adətən bir iş günü ərzində cavab veririk.',
		'details_label' => 'Əlaqə məlumatları',
		'studio'        => 'Studiya',
		'phone'         => 'Telefon',
		'email'         => 'E-poçt',
		'hours'         => 'İş saatları',
		'hours_text'    => 'Bazar ertəsi – Cümə, 9:00 – 18:00',
		'form_label'    => 'Bizə yazın',
		'form_title'    => 'Mesaj göndərin',
		'form_text'     => 'Formu doldurun, komandamız qısa zamanda sizinlə əlaqə saxlayacaq.',
		'map'           => 'Baku, Azerbaijan',
	),

	'form'     => array(
		'title'    => 'Əlaqə forması (AZ)',
		'name'     => 'Adınız',
		'email'    => 'E-poçt',
		'phone'    => 'Telefon',
		'subject'  => 'Mövzu',
		'message'  => 'Mesaj',
		'submit'   => 'Mesajı göndər',
		'messages' => array(
			'mail_sent_ok'     => 'Təşəkkür edirik! Mesajınız göndərildi.',
			'mail_sent_ng'     => 'Mesaj göndərilərkən xəta baş verdi. Zəhmət olmasa, bir az sonra yenidən cəhd edin.',
			'validation_error' => 'Bir və ya bir neçə sahədə xəta var. Zəhmət olmasa, yoxlayıb yenidən cəhd edin.',
			'invalid_required' => 'Zəhmət olmasa, bu sahəni doldurun.',
			'invalid_email'    => 'Zəhmət olmasa, düzgün e-poçt ünvanı daxil edin.',
		),
	),

	// Theme strings: English source key => translation.
	'strings'  => array(
		'Menu'                                    => 'Menyu',
		'Close'                                   => 'Bağla',
		'Let’s design your next space together.' => 'Növbəti məkanınızı birlikdə dizayn edək.',
		'Get in touch'                            => 'Bizimlə əlaqə',
		'An architecture and interior design studio creating timeless, light-filled spaces.' => 'İşıqla dolu, zamansız məkanlar yaradan memarlıq və interyer dizaynı studiyası.',
		'Visit us'                                => 'Ünvanımız',
		'Talk to us'                              => 'Əlaqə',
		'Navigate'                                => 'Naviqasiya',
		'28 May Street 15, Baku, Azerbaijan'      => '28 May küçəsi 15, Bakı, Azərbaycan',
		'© %s Via Adlucem. All rights reserved.' => '© %s Via Adlucem. Bütün hüquqlar qorunur.',
		'Back to top'                             => 'Yuxarı',
	),
	'tagline'  => 'Memarlıq və interyer dizaynı studiyası',
);
