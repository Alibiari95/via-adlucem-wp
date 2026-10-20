<?php
/**
 * Layihələr — Azərbaycan dili (mənbə: 3-Project.docx). Source language for the projects;
 * en.php, uz.php and ru.php are translations with the same structure and order.
 * Applied by tools/seed-projects.php.
 */

return array(
	'menu'           => 'Layihələr',
	'categories'     => array(
		'residential'    => 'Yaşayış',
		'commercial'     => 'Kommersiya',
		'infrastructure' => 'İctimai və infrastruktur',
	),
	'tags'           => array(
		'bim'          => 'BIM',
		'master-plan'  => 'Baş plan',
		'architecture' => 'Memarlıq',
		'structural'   => 'Konstruktiv mühəndislik',
		'mep'          => 'MEP',
		'civil'        => 'Mülki infrastruktur',
		'agdam'        => 'Ağdam',
	),
	'labels'         => array(
		'location'      => 'Məkan',
		'period'        => 'Dövr',
		'services'      => 'Xidmətlər',
		'overview'      => 'Layihə haqqında ümumi məlumat',
		'features'      => 'Ərazinin xüsusiyyətləri və mühəndis yanaşması',
		'objects'       => 'Layihə obyektləri və texniki göstəricilər',
		'col_object'    => 'Obyekt',
		'col_area'      => 'Ərazi sahəsi',
		'col_elevation' => 'Mütləq hündürlük (±0.00)',
	),
	'services_intro' => 'Daxili ekspertizamıza əsaslanaraq həyata keçirdiyimiz layihələndirmə üzrə əsas xidmətlər:',
	'services_list'  => array( 'Baş plan', 'Memarlıq', 'Konstruksiya', 'Mühəndis sistemləri' ),
	'overview'       => 'Bu kompleks layihə Sifarişçimiz olan "Qarabağ İqtisadi Rayonunda Bərpa, Tikinti və İdarəetmə Xidməti" Publik Hüquqi Şəxsi ilə sıx əməkdaşlıq çərçivəsində, müvafiq müqavilə və layihə tapşırığı əsasında işlənib hazırlanmışdır. Layihələndirmə prosesində Ağdam şəhərinin təsdiq edilmiş yeni baş planına uyğun hüdud nöqtələri və komandamız tərəfindən hazırlanmış ərazinin topoqrafik xəritəsi əsas götürülmüşdür.',
	'features'       => array(
		array( 'Relyef və Geologiya:', 'Ərazi qismən dağıntılara məruz qalsa da, kiçik nisbətdə məişət tullantıları qarışmış münbit torpaq qatı mövcuddur. Sakit relyefə malik olan ərazi, mühəndis-geoloji tədqiqatların nəticələrinə əsasən 8 ballıq seysmik zonaya aid edilmişdir.' ),
		array( 'İnfrastruktur və Təhlükəsizlik:', 'Bütün binaların ətrafında yanğınsöndürən və təcili tibbi yardım maşınlarının manevr edə bilməsi üçün xüsusi yollar, eləcə də rahat və təhlükəsiz piyada hərəkətini təmin edən binaətrafı səkilər nəzərdə tutulub.' ),
		array( 'Abadlaşdırma:', 'Layihələndirilən ərazilər yeni baş plana uyğun olaraq yaşıllıq zolaqları, tədbirlər üçün meydançalar və müasir yol-səki infrastrukturu ilə tam abadlaşdırılır.' ),
	),
	'projects'       => array(
		array(
			'title'         => 'Göytəpə: Kompleks işçi layihənin hazırlanması',
			'slug'          => 'goytepe-kompleks-isci-layihe',
			'excerpt'       => 'Ağdam rayonunun Göytəpə kəndində uşaq bağçası, tibb məntəqəsi, inzibati bina və məktəbdən ibarət kompleksin baş plan, memarlıq, konstruksiya və mühəndis sistemləri layihəsi.',
			'location'      => 'Ağdam rayonu, Göytəpə kəndi',
			'objects_intro' => 'Layihə çərçivəsində hər bir binanın 1-ci mərtəbəsinin döşəmə səviyəsi şərti olaraq 0.000 qəbul edilmişdir. Aşağıda kompleksə daxil olan əsas binaların baş plan göstəriciləri təqdim olunur:',
			'objects'       => array(
				array( '2 mərtəbəli, 80 yerlik Uşaq bağçası (1-ci mərhələ)', '1.28 ha', '+287.90' ),
				array( '1 mərtəbəli Tibb məntəqəsi', '3.94 ha', '+286.65' ),
				array( '2 mərtəbəli İnzibati bina', '1.15 ha', '+286.40' ),
				array( '2 mərtəbəli, 432 yerlik Məktəb binası', '2.59 ha', '+287.20' ),
			),
		),
		array(
			'title'         => 'Güllücə: Kompleks işçi layihənin hazırlanması',
			'slug'          => 'gulluce-kompleks-isci-layihe',
			'excerpt'       => 'Ağdam rayonunun Güllücə kəndində məktəblər, uşaq bağçaları, inzibati bina, tibb məntəqəsi və qəbiristanlıq kompleksinin baş plan, memarlıq, konstruksiya və mühəndis sistemləri layihəsi.',
			'location'      => 'Ağdam rayonu, Güllücə kəndi',
			'objects_intro' => 'Layihə çərçivəsində hər bir binanın 1-ci mərtəbəsinin döşəmə səviyəsi şərti olaraq 0.000 qəbul edilmişdir. Aşağıda kompleksə daxil olan müxtəlif təyinatlı binaların və zonaların baş plan göstəriciləri təqdim olunur:',
			'objects'       => array(
				array( '2 mərtəbəli, 80 yerlik Uşaq bağçası (2-ci mərhələ)', '2.23 ha', '+248.35' ),
				array( '2 mərtəbəli İnzibati bina və 1 mərtəbəli Tibb məntəqəsi', '5.63 ha', 'İnzibati bina: +229.55 · Tibb məntəqəsi: +228.05' ),
				array( '2 mərtəbəli, 480 yerlik Məktəb binası və 2 mərtəbəli, 120 yerlik Uşaq bağçası (1-ci mərhələ)', '5.36 ha', 'Məktəb binası: +235.60 · Bağça binası: +234.55' ),
				array( '2 mərtəbəli, 480 yerlik Məktəb binası və 2 mərtəbəli, 80 yerlik Uşaq bağçası (2-ci mərhələ)', '3.75 ha', 'Məktəb binası: +218.83 · Uşaq bağçası: +219.52' ),
				array( 'Qəbiristanlıq kompleksi (konservasiya olunmuş)', '2.86 ha', 'Yuyatxana: +242.58 · Mühafizə köşkü: +241.44' ),
			),
		),
		array(
			'title'         => 'Salahlı: Kompleks işçi layihənin hazırlanması',
			'slug'          => 'salahli-kompleks-isci-layihe',
			'excerpt'       => 'Ağdam rayonunun Salahlı kəndində inzibati bina, tibb məntəqəsi, məktəb və uşaq bağçasının baş plan, memarlıq, konstruksiya və mühəndis sistemləri layihəsi.',
			'location'      => 'Ağdam rayonu, Salahlı kəndi',
			'objects_intro' => 'Layihə çərçivəsində hər bir binanın 1-ci mərtəbəsinin döşəmə səviyəsi şərti olaraq 0.000 qəbul edilmişdir. Aşağıda kompleksə daxil olan müxtəlif təyinatlı binaların baş plan göstəriciləri təqdim olunur:',
			'objects'       => array(
				array( '2 mərtəbəli İnzibati bina və 1 mərtəbəli Tibb məntəqəsi', '4.26 ha', 'İnzibati bina: +344.70 · Tibb məntəqəsi: +343.65' ),
				array( '2 mərtəbəli, 480 yerlik Məktəb binası və 2 mərtəbəli, 80 yerlik Uşaq bağçası (2-ci mərhələ)', '3.75 ha', 'Məktəb binası: +218.83 · Uşaq bağçası: +219.52' ),
			),
		),
	),
	'strings'        => array(
		'Projects'              => 'Layihələr',
		'All'                   => 'Hamısı',
		'Home'                  => 'Ana səhifə',
		'All projects'          => 'Bütün layihələr',
		'No projects here yet.' => 'Burada hələ layihə yoxdur.',
		'Share'                 => 'Paylaş',
		'Previous project'      => 'Əvvəlki layihə',
		'Next project'          => 'Növbəti layihə',
	),
);
