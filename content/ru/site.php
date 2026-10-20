<?php
/**
 * Russian version of the site. Used by tools/add-russian.php.
 *
 * - map:      English text as it stands in the English Elementor pages => Russian. The Russian
 *             home, contact and service pages are copies of the English ones with these texts swapped.
 * - pages / services / form / menu / seo: Russian titles, addresses and meta.
 * - strings:  Polylang string translations (header, footer, services and projects pages).
 */

return array(
	'pages'    => array(
		'home'    => array( 'title' => 'Главная', 'slug' => 'glavnaya' ),
		'contact' => array( 'title' => 'Контакты', 'slug' => 'kontakty' ),
	),
	'seo'      => array(
		'title'       => 'BIM и инженерные услуги в Азербайджане | Via Ad Lucem',
		'description' => 'Архитектура, конструктивное проектирование, MEP и гражданская инфраструктура в Азербайджане и Центральной Азии. Более 10 лет опыта в передовых BIM-решениях.',
	),
	'menu'     => array(
		'name'     => 'Главное меню (RU)',
		'home'     => 'Главная',
		'services' => 'Услуги',
		'projects' => 'Проекты',
		'contact'  => 'Контакты',
	),
	'tagline'  => 'Студия архитектуры и дизайна интерьеров',

	'form'     => array(
		'title'    => 'Форма обратной связи (RU)',
		'name'     => 'Ваше имя',
		'email'    => 'Эл. почта',
		'phone'    => 'Телефон',
		'subject'  => 'Тема',
		'message'  => 'Сообщение',
		'submit'   => 'Отправить сообщение',
		'messages' => array(
			'mail_sent_ok'     => 'Спасибо! Ваше сообщение отправлено.',
			'mail_sent_ng'     => 'При отправке сообщения произошла ошибка. Пожалуйста, попробуйте позже.',
			'validation_error' => 'В одном или нескольких полях есть ошибка. Пожалуйста, проверьте и попробуйте снова.',
			'invalid_required' => 'Пожалуйста, заполните это поле.',
			'invalid_email'    => 'Пожалуйста, введите корректный адрес эл. почты.',
		),
	),

	// In the order of the services (menu order).
	'services' => array(
		array(
			'title'           => 'Архитектурное проектирование и генеральное планирование',
			'slug'            => 'arkhitekturnoe-proektirovanie-i-genplan',
			'tagline'         => 'От концепции до рабочей документации.',
			'seo_title'       => 'Архитектурное проектирование и генеральное планирование | Via Ad Lucem',
			'seo_description' => 'Via Ad Lucem выполняет архитектурное проектирование, пространственное планирование и строительную документацию для МСП и девелоперов в Азербайджане и Центральной Азии.',
		),
		array(
			'title'           => 'Конструктивное проектирование и технический консалтинг',
			'slug'            => 'konstruktivnoe-proektirovanie-i-tekhnicheskiy-konsalting',
			'tagline'         => 'Конструктивная надёжность, техническая документация и планирование проекта.',
			'seo_title'       => 'Конструктивное проектирование и технический консалтинг | Via Ad Lucem',
			'seo_description' => 'Обеспечьте безопасность и соответствие нормам вашего проекта с услугами Via Ad Lucem по конструктивному проектированию, планированию и технической документации в Азербайджане.',
		),
		array(
			'title'           => 'Инженерные системы MEP и системы здания',
			'slug'            => 'inzhenernye-sistemy-mep',
			'tagline'         => 'Комплексные решения: механические, электрические, сантехнические системы и технологии здания.',
			'seo_title'       => 'Проектирование инженерных систем MEP | Via Ad Lucem',
			'seo_description' => 'Комплексное проектирование MEP, ОВиК, электроснабжения, водоснабжения и канализации для современной инфраструктуры в Азербайджане и Центральной Азии.',
		),
		array(
			'title'           => 'Гражданская инфраструктура и наружные сети',
			'slug'            => 'grazhdanskaya-infrastruktura-i-naruzhnye-seti',
			'tagline'         => 'Связанная инфраструктура, инженерные сети и транспортные системы.',
			'seo_title'       => 'Гражданская инфраструктура и наружные инженерные сети | Via Ad Lucem',
			'seo_description' => 'Гражданское проектирование, наружные инженерные сети и транспортная инфраструктура для проектов в Азербайджане и Центральной Азии.',
		),
	),

	'strings'  => array(
		'Menu'                                      => 'Меню',
		'Close'                                     => 'Закрыть',
		'Start your next project with confidence.'  => 'Начните свой следующий проект уверенно.',
		'Leverage a decade of advanced BIM expertise in Azerbaijan. Let’s engineer your success together.' => 'Воспользуйтесь нашим десятилетним опытом в передовых BIM-технологиях в Азербайджане. Давайте вместе спроектируем ваш успех.',
		'Contact us today'                          => 'Свяжитесь с нами сегодня',
		'A results-driven engineering and design firm with more than 10 years of BIM experience in Azerbaijan.' => 'Инженерно-проектная компания, ориентированная на результат, с более чем 10-летним опытом BIM в Азербайджане.',
		'Visit us'                                  => 'Наш адрес',
		'Talk to us'                                => 'Связь с нами',
		'Social media'                              => 'Социальные сети',
		'16e Keykab Khanim Safaraliyeva St, Time Tower, 3rd floor, Baku 1010' => 'ул. Кёвкаб Сафаралиевой 16e, Time Tower, 3-й этаж, Баку AZ1010',
		'© %s Via Adlucem. All rights reserved.'   => '© %s Via Adlucem. Все права защищены.',
		'Back to top'                               => 'Наверх',
		'Services'                                  => 'Услуги',
		'Design and engineering services for developers, SMEs and project owners across Azerbaijan and Central Asia.' => 'Проектные и инженерные услуги для девелоперов, МСП и владельцев проектов в Азербайджане и Центральной Азии.',
		'Other services'                            => 'Другие услуги',
		'Learn more'                                => 'Подробнее',
	),

	'map'      => array(
		// Hero slider.
		'Comprehensive BIM & Engineering Solutions' => 'Комплексные BIM и инженерные решения',
		'Comprehensive BIM & Engineering Solutions in Azerbaijan' => 'Комплексные BIM и инженерные решения в Азербайджане',
		'From master planning to advanced MEP and civil infrastructure, we engineer precision at every phase of your project.' => 'От генерального планирования до передовых систем MEP и гражданской инфраструктуры — мы обеспечиваем точность на каждом этапе вашего проекта.',
		'Explore Our Services'                      => 'Наши услуги',
		'Delivering Excellence: Discover Our Latest Project' => 'Безупречный результат: познакомьтесь с нашим последним проектом',
		'See how our advanced modeling and integrated engineering translate into world-class architectural success.' => 'Узнайте, как передовое моделирование и комплексная инженерия превращаются в архитектурный успех мирового уровня.',
		'View Portfolio'                            => 'Смотреть портфолио',
		'Ready to Build the Future? Let’s Talk.'   => 'Готовы строить будущее? Давайте обсудим.',
		'Partner with Azerbaijan\'s premier structural, architectural, and MEP experts for your next development.' => 'Сотрудничайте с ведущими специалистами Азербайджана по конструкциям, архитектуре и MEP в вашем следующем проекте.',
		'Get in Touch'                              => 'Связаться с нами',

		// About.
		'About us'                                  => 'О нас',
		'Building the Future with <em>Precision</em>' => 'Строим будущее с <em>точностью</em>',
		'<p>We are a results-driven engineering and design firm dedicated to bringing complex visions to reality. With more than 10 years of experience in Azerbaijan in BIM (Building Information Modeling), we deliver integrated, clash-free solutions across architecture, structural engineering, and MEP systems.</p>' => '<p>Мы — инженерно-проектная компания, ориентированная на результат и воплощающая сложные идеи в реальность. Более 10 лет опыта в Азербайджане в области BIM (информационного моделирования зданий) позволяют нам предлагать комплексные, бесколлизионные решения в архитектуре, конструктивном проектировании и системах MEP.</p>',
		'Contact us'                                => 'Связаться с нами',

		// What we do.
		'What we do'                                => 'Что мы делаем',
		'Our Services'                              => 'Наши услуги',
		'Architecture & Master Planning'            => 'Архитектура и генеральное планирование',
		'From initial master planning to highly detailed construction documents, we design visionary spaces tailored to your project goals. Our expert team ensures every schematic and working drawing is flawlessly executed for seamless development.' => 'От генерального плана до детальной рабочей документации — мы проектируем перспективные пространства в соответствии с целями вашего проекта. Наша команда обеспечивает безупречное выполнение каждого эскиза и рабочего чертежа для беспрепятственной реализации.',
		'Structural Engineering, Compliance & Documentation' => 'Конструктивное проектирование, соответствие нормам и документация',
		'We provide robust structural calculations, environmental impact assessments, and detailed project specifications. Our engineering guarantees that your building is safe, legally compliant, and perfectly planned for the construction phase.' => 'Мы выполняем надёжные конструктивные расчёты, оценку воздействия на окружающую среду и подробные спецификации проекта. Наши инженерные решения гарантируют, что ваше здание безопасно, полностью соответствует нормам и идеально подготовлено к этапу строительства.',
		'MEP Engineering & Technology'              => 'Инженерные системы MEP и технологии',
		'Our advanced designs seamlessly integrate efficient HVAC, secure electrical grids, and precise plumbing into your building\'s framework. We optimize energy performance and incorporate modern life safety, ELV, and process piping technologies.' => 'Наши проекты органично встраивают в здание эффективные системы ОВиК, надёжные электросети и точную сантехнику. Мы оптимизируем энергоэффективность и применяем современные системы безопасности, слаботочные системы (ELV) и технологические трубопроводы.',
		'Infrastructure & Civil Engineering'        => 'Инфраструктура и гражданское строительство',
		'We engineer resilient external civil utilities—including water, gas, and power grids—to effectively connect your site to the wider network. Our expert roadway, pavement, and stormwater management designs ensure durable and sustainable surface infrastructure.' => 'Мы проектируем надёжные наружные инженерные сети — водоснабжение, газо- и электроснабжение — для эффективного подключения вашего объекта к городским сетям. Наши проекты дорог, покрытий и ливневой канализации обеспечивают долговечную и устойчивую наземную инфраструктуру.',

		// Projects strip.
		'Recent projects'                           => 'Последние проекты',

		// Services accordion (home).
		'Services'                                  => 'Услуги',
		'A complete service, from the first idea to the finished space.' => 'Полный цикл услуг — от первой идеи до готового пространства.',
		'<p>We guide each project through every phase, so that the design intent survives all the way to the final detail.</p>' => '<p>Мы ведём каждый проект через все этапы, чтобы замысел сохранился до последней детали.</p>',
		'Concept & feasibility'                     => 'Концепция и ТЭО',
		'<p>We study the site, the brief and the budget, and turn them into a clear concept that sets the direction for the whole project.</p>' => '<p>Мы изучаем участок, задание и бюджет и превращаем их в ясную концепцию, задающую направление всему проекту.</p>',
		'Architectural design'                      => 'Архитектурное проектирование',
		'<p>From schematic design to detailed drawings, we develop buildings that are functional, efficient and full of natural light.</p>' => '<p>От эскизного проекта до рабочих чертежей мы создаём функциональные, эффективные здания, наполненные естественным светом.</p>',
		'Interior design'                           => 'Дизайн интерьеров',
		'<p>Layouts, materials, lighting and custom furniture — composed into interiors that are calm, practical and personal.</p>' => '<p>Планировки, материалы, освещение и мебель на заказ — в спокойных, практичных и индивидуальных интерьерах.</p>',
		'Construction documents'                    => 'Строительная документация',
		'<p>Precise technical drawings and specifications that let contractors build exactly what was designed.</p>' => '<p>Точные технические чертежи и спецификации, позволяющие подрядчикам построить именно то, что спроектировано.</p>',
		'Site supervision'                          => 'Авторский надзор',
		'<p>Regular site visits and coordination with contractors to keep quality, cost and schedule under control.</p>' => '<p>Регулярные выезды на объект и координация с подрядчиками для контроля качества, затрат и сроков.</p>',

		// Studio + counters.
		'Our studio'                                => 'Наша студия',
		'Building spaces with purpose since 2015'   => 'Создаём осмысленные пространства с 2015 года',
		'<p>Small enough to give every project personal attention, experienced enough to deliver complex buildings on time.</p>' => '<p>Достаточно компактны, чтобы уделять внимание каждому проекту, и достаточно опытны, чтобы сдавать сложные объекты в срок.</p>',
		'Realized projects'                         => 'Реализованных проектов',
		'Years of experience'                       => 'Лет опыта',
		'Team members'                              => 'Специалистов в команде',
		'Cities'                                    => 'Городов',

		// Contact page.
		'Contact'                                   => 'Контакты',
		'Let’s talk about your next project'       => 'Давайте обсудим ваш следующий проект',
		'<p>Tell us about your plans — we usually reply within one working day.</p>' => '<p>Расскажите нам о своих планах — обычно мы отвечаем в течение одного рабочего дня.</p>',
		'Contact details'                           => 'Контактная информация',
		'Write to us'                               => 'Напишите нам',
		'Send us a message'                         => 'Отправьте нам сообщение',
		'<p>Fill in the form and our team will get back to you shortly.</p>' => '<p>Заполните форму, и наша команда скоро свяжется с вами.</p>',

		// Service pages: shared labels.
		'Overview'                                  => 'Обзор',
		'Core services'                             => 'Основные услуги',
		'Why Via Ad Lucem?'                         => 'Почему Via Ad Lucem?',
		'Discuss your project'                      => 'Обсудить ваш проект',

		// Architectural Design & Master Planning.
		'<p>Via Ad Lucem provides comprehensive architectural design and master planning services tailored to the unique regulatory and environmental landscapes of Azerbaijan and Central Asia. We guide project owners, developers, and SMEs through every phase of spatial development, ensuring that functional requirements seamlessly integrate with innovative aesthetic solutions.</p>' => '<p>Via Ad Lucem предлагает комплексные услуги архитектурного проектирования и генерального планирования с учётом нормативных и природных особенностей Азербайджана и Центральной Азии. Мы сопровождаем владельцев проектов, девелоперов и МСП на каждом этапе пространственного развития, органично сочетая функциональные требования с инновационными эстетическими решениями.</p>',
		'Master Planning'                           => 'Генеральное планирование',
		'<p>Comprehensive site layout design, spatial planning, and macro-level project visualization.</p>' => '<p>Комплексная планировка территории, пространственное планирование и визуализация проекта на макроуровне.</p>',
		'Concept & Schematic Design'                => 'Концепция и эскизный проект',
		'<p>Conceptual development, schematic design (SD), and rigorous design development coordination.</p>' => '<p>Разработка концепции, эскизный проект (SD) и тщательная координация проектирования.</p>',
		'Detailed Architectural Design'             => 'Детальное архитектурное проектирование',
		'<p>Detailed design (DD) execution, including architectural plans, precise sections, elevations, and structural integrations.</p>' => '<p>Детальный проект (DD): архитектурные планы, точные разрезы, фасады и увязка с конструкциями.</p>',
		'Construction Documentation'                => 'Рабочая документация',
		'<p>Fully compliant construction documents (CD), working drawings, technical detailing, and design coordination for seamless contractor handover.</p>' => '<p>Рабочая документация (CD), полностью соответствующая нормам: рабочие чертежи, технические узлы и координация проекта для беспрепятственной передачи подрядчику.</p>',
		'<p>Our multidisciplinary approach ensures that architectural vision is grounded in technical viability, delivering scalable, future-proof facilities for commercial, industrial, and residential sectors across the region.</p>' => '<p>Наш междисциплинарный подход гарантирует, что архитектурный замысел опирается на техническую реализуемость, создавая масштабируемые и перспективные объекты для коммерческого, промышленного и жилого секторов региона.</p>',

		// Structural Engineering & Technical Consultancy.
		'<p>A successful building relies on uncompromising structural integrity and meticulous planning. Via Ad Lucem offers robust structural engineering and technical consultancy services to ensure your construction projects in Azerbaijan and the broader Central Asian market meet the highest international safety and performance standards.</p>' => '<p>Успешное здание опирается на бескомпромиссную конструктивную надёжность и тщательное планирование. Via Ad Lucem предлагает услуги конструктивного проектирования и технического консалтинга, чтобы ваши проекты в Азербайджане и Центральной Азии соответствовали самым высоким международным стандартам безопасности и эксплуатационных характеристик.</p>',
		'Structural Engineering'                    => 'Конструктивное проектирование',
		'<p>Advanced structural analysis, engineering calculations, structural design, and highly detailed working drawings.</p>' => '<p>Расширенный конструктивный анализ, инженерные расчёты, проектирование конструкций и подробные рабочие чертежи.</p>',
		'Technical Documentation'                   => 'Техническая документация',
		'<p>Preparation of design statements, project specifications, and technical notes covering architectural, structural, and MEP disciplines.</p>' => '<p>Подготовка пояснительных записок, спецификаций и технических примечаний по архитектурному, конструктивному разделам и разделу MEP.</p>',
		'Environmental Assessment'                  => 'Экологическая оценка',
		'<p>Coordination and execution of Environmental Impact Assessments (EIA) to ensure regulatory compliance and sustainable development.</p>' => '<p>Координация и проведение оценки воздействия на окружающую среду (ОВОС) для соответствия нормам и устойчивого развития.</p>',
		'Construction Planning'                     => 'Планирование строительства',
		'<p>Strategic construction-phase planning, site logistics layout, and ongoing construction coordination to minimize risk and delay.</p>' => '<p>Стратегическое планирование этапа строительства, схема логистики площадки и постоянная координация строительства для снижения рисков и задержек.</p>',
		'<p>We bridge the gap between design and execution. By consolidating structural analysis and technical compliance under one roof, we streamline approvals and optimize construction site efficiency for SMEs and enterprise developers alike.</p>' => '<p>Мы устраняем разрыв между проектированием и реализацией. Объединяя конструктивный анализ и техническое соответствие в одних руках, мы ускоряем согласования и повышаем эффективность стройплощадки как для МСП, так и для крупных девелоперов.</p>',

		// MEP Engineering & Building Systems.
		'<p>Modern facilities require intelligent, energy-efficient core systems. Via Ad Lucem engineers high-performance MEP (Mechanical, Electrical, and Plumbing) systems that optimize energy usage, ensure life safety, and provide reliable building operations for complex commercial and industrial projects.</p>' => '<p>Современным объектам нужны интеллектуальные и энергоэффективные инженерные системы. Via Ad Lucem проектирует высокоэффективные системы MEP (механические, электрические и сантехнические), которые оптимизируют энергопотребление, обеспечивают безопасность людей и надёжную эксплуатацию зданий в сложных коммерческих и промышленных проектах.</p>',
		'Mechanical Engineering'                    => 'Механические системы',
		'<p>HVAC systems design, gas supply and piping, alongside industrial and commercial process piping.</p>' => '<p>Проектирование систем ОВиК, газоснабжения и трубопроводов, а также промышленных и коммерческих технологических трубопроводов.</p>',
		'Electrical Engineering'                    => 'Электротехнические системы',
		'<p>Comprehensive electrical power supply and distribution, plus interior, exterior, and emergency lighting design.</p>' => '<p>Комплексное электроснабжение и распределение электроэнергии, проектирование внутреннего, наружного и аварийного освещения.</p>',
		'Public Health Engineering (PHE)'           => 'Водоснабжение и канализация (PHE)',
		'<p>Domestic water supply, sanitary plumbing, wastewater drainage, and integrated building drainage systems.</p>' => '<p>Хозяйственно-питьевое водоснабжение, сантехнические системы, отведение сточных вод и комплексные дренажные системы здания.</p>',
		'ELV, ICT & Life Safety Systems'            => 'Слаботочные, ИКТ-системы и системы безопасности',
		'<p>Fire alarm systems, telecommunications, ICT networks, and Extra-Low Voltage (ELV) building technology.</p>' => '<p>Пожарная сигнализация, телекоммуникации, сети ИКТ и слаботочные (ELV) системы здания.</p>',
		'Energy & Thermal Performance'              => 'Энергоэффективность и теплотехника',
		'<p>Precision energy performance calculations, thermal loss, and heat load analysis.</p>' => '<p>Точные расчёты энергоэффективности, теплопотерь и тепловых нагрузок.</p>',
		'Process Systems'                           => 'Технологические системы',
		'<p>Technological process layouts for industrial facilities and commercial kitchens.</p>' => '<p>Технологические планировки для промышленных объектов и профессиональных кухонь.</p>',
		'<p>Our coordinated MEP approach prevents costly clashes during the build phase. We design adaptable, sustainable building systems tailored to the climatic and infrastructural demands of the Central Asian region.</p>' => '<p>Наш скоординированный подход к MEP предотвращает дорогостоящие коллизии на этапе строительства. Мы проектируем гибкие и устойчивые инженерные системы с учётом климатических и инфраструктурных условий Центральной Азии.</p>',

		// Civil Infrastructure & External Utilities.
		'<p>Reliable site infrastructure is the foundation of any successful development. Via Ad Lucem delivers highly engineered civil infrastructure and external utility solutions that seamlessly connect your site to wider regional networks. From traffic flow to stormwater management, we engineer the essential lifelines of your project.</p>' => '<p>Надёжная инфраструктура участка — основа любого успешного проекта. Via Ad Lucem предлагает тщательно проработанные решения по гражданской инфраструктуре и наружным сетям, органично связывающие ваш объект с региональными сетями. От транспортных потоков до ливневой канализации — мы проектируем жизненно важные артерии вашего проекта.</p>',
		'External Utility Networks'                 => 'Наружные инженерные сети',
		'<p>Design and routing of external water, gas, and electrical utility mains.</p>' => '<p>Проектирование и трассировка наружных магистралей водоснабжения, газо- и электроснабжения.</p>',
		'Site Utility Connections'                  => 'Подключения на участке',
		'<p>Precise utility routing from site boundaries to primary building connection points.</p>' => '<p>Точная трассировка сетей от границ участка до основных точек подключения здания.</p>',
		'Road & Highway Engineering'                => 'Проектирование дорог и магистралей',
		'<p>Roadway geometry, alignment, and detailed cross-sectional design.</p>' => '<p>Геометрия дороги, трассирование и детальное проектирование поперечных профилей.</p>',
		'Traffic Engineering'                       => 'Организация дорожного движения',
		'<p>Traffic circulation optimization and comprehensive traffic management design.</p>' => '<p>Оптимизация транспортных потоков и комплексные схемы организации движения.</p>',
		'Stormwater Management'                     => 'Ливневая канализация',
		'<p>Surface water collection, sustainable stormwater drainage, and discharge systems.</p>' => '<p>Сбор поверхностных вод, устойчивый дренаж дождевых стоков и системы их отведения.</p>',
		'Pavement Engineering'                      => 'Проектирование дорожных покрытий',
		'<p>Structural design and typical cross-sections for highly durable pavements.</p>' => '<p>Конструирование и типовые поперечные профили долговечных дорожных покрытий.</p>',
		'<p>We master the complexities of underground utilities and surface infrastructure, ensuring your project integrates flawlessly with existing municipal frameworks while supporting long-term operational growth.</p>' => '<p>Мы владеем всеми тонкостями подземных коммуникаций и наземной инфраструктуры, обеспечивая безупречную интеграцию вашего проекта с городскими системами и его долгосрочное развитие.</p>',
	),
);
