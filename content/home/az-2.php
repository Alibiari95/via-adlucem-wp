<?php
/**
 * Ana səhifə — Azərbaycan dili (mənbə: home_page-AZ.docx). Applied by tools/update-home-az.php.
 * Only texts change; images, links and layout stay as they are.
 */

return array(
	'slides'   => array(
		array(
			'title'    => 'Layihələndirmə və Mühəndislik Xidmətləri',
			'subtitle' => 'Baş plandan tutmuş müasir daxili mühəndislik və xarici infrastruktura qədər layihənizin hər mərhələsində dəqiqliyi təmin edirik.',
			'button'   => 'Xidmətlərimizlə Tanış Olun',
		),
		array(
			'title'    => 'Sonuncu Layihəmizlə Tanış Olun',
			'subtitle' => 'Müasir layihələndirmə və inteqrasiya olunmuş mühəndislik yanaşmamızın memarlıq layihələrində necə uğurla tətbiq olunduğunu görün.',
			'button'   => 'Portfelə Baxın',
		),
		array(
			'title'    => 'Gələcəyi Qurmağa Hazırsınız?',
			'subtitle' => 'Növbəti layihəniz üçün Azərbaycanın təcrübəli konstruksiya, memarlıq və mühəndislik mütəxəssisləri ilə tərəfdaşlıq edin.',
			'button'   => 'Əlaqə Saxlayın',
		),
	),
	'about'    => array(
		'statement' => 'Mürəkkəb Layihələr Üçün Peşəkar Mühəndislik',
		'text'      => 'Biz mürəkkəb ideyaları reallığa çevirən, nəticəyönümlü mühəndislik və dizayn tərəfdaşınızdır. Azərbaycanda BIM (Bina İnformasiya Modelləşdirilməsi) sahəsində 10 ildən artıq təcrübəmizlə memarlıq, konstruksiya və daxili mühəndislik sistemləri üzrə inteqrasiya olunmuş, kəsişməsiz (clash-free) həllər təqdim edirik.',
		'button'    => 'Bizimlə Əlaqə',
	),
	// The four boxes of the services section (numbered 01–04 by the design). All link to the
	// "Structural Engineering & Technical Consultancy" service, which covers these topics.
	'services' => array(
		'link_service' => 2, // Menu order of the service page the boxes link to.
		'items'        => array(
			array( 'icon' => 'fas fa-building', 'title' => 'Konstruksiya Bölməsi', 'text' => 'Müasir konstruksiya analizi, mühəndis hesabatları, layihələndirmə və detallı işçi cizgilər.' ),
			array( 'icon' => 'fas fa-file-alt', 'title' => 'Uyğunluq və Sənədləşdirmə', 'text' => 'Memarlıq, konstruksiya və mühəndislik bölmələri üzrə layihənin izahat yazısı və texniki sənədlərin hazırlanması.' ),
			array( 'icon' => 'fas fa-leaf', 'title' => 'Ekologiya (ƏMTƏ)', 'text' => 'Normativ tələblərə və davamlı inkişafa uyğunluğu təmin etmək üçün Ətraf Mühitə Təsirin Qiymətləndirilməsi (ƏMTƏ) prosesinin icrası.' ),
			array( 'icon' => 'fas fa-hard-hat', 'title' => 'Tikintinin Təşkili Layihəsi (TTL)', 'text' => 'Riskləri və gecikmələri minimuma endirmək üçün tikinti mərhələsinin strateji planlaşdırılması, sahə logistikası və əlaqələndirmə.' ),
		),
	),
);
