-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Май 12 2016 г., 15:35
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `u8048_fusionchef`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_category_id` varchar(255) DEFAULT NULL,
  `sort_index` int(11) NOT NULL DEFAULT '1',
  `menu_visible` int(11) NOT NULL DEFAULT '1',
  `image_url` varchar(511) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_parent_category_fk` (`parent_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`, `parent_category_id`, `sort_index`, `menu_visible`, `image_url`) VALUES
('susi', 'Суши', NULL, 1, 1, 'susi-syake.jpg'),
('rolls', 'Роллы', NULL, 2, 1, 'roll-unagi.jpg'),
('duykans', 'Дуйканы', NULL, 3, 1, 'duikan-kuro.jpg'),
('zharenye-rolly', 'Жареные роллы', 'rolls', 1, 1, 'zharenye-rolly.jpg'),
('podarki', 'Подарки', NULL, 1, 0, NULL),
('salaty', 'Салаты', NULL, 4, 0, 'salaty.jpg'),
('zakuski', 'Закуски', NULL, 5, 0, 'zakuski.jpg'),
('pervye-blyuda', 'Первые блюда', NULL, 6, 0, 'pervye-blyuda.jpg'),
('goryachie-blyuda', 'Горячие блюда', NULL, 7, 0, 'goryachie-blyuda.jpg'),
('uzbekskaya-kuznya', 'Узбекская кухня', NULL, 8, 0, 'uzbekskaya-kuznya.jpg'),
('picca', 'Пицца', NULL, 9, 1, 'picca.jpg'),
('mangal', 'Мангал', NULL, 10, 1, 'mangal.jpg'),
('ris-lapsha', 'Рис / Лапша', NULL, 11, 1, 'ris-lapsha.jpg'),
('sousy', 'Соусы', 'ris-lapsha', 12, 1, 'sousy.jpg'),
('dopolnitel-no', 'Дополнительно', 'ris-lapsha', 13, 1, NULL),
('zapechennye', 'Запеченные', 'rolls', 14, 1, NULL),
('populyarnoe', 'Популярное', NULL, 15, 0, 'populyarnoe.png');

-- --------------------------------------------------------

--
-- Структура таблицы `good`
--

CREATE TABLE IF NOT EXISTS `good` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `description` varchar(511) DEFAULT NULL,
  `sort_index` int(11) NOT NULL DEFAULT '1',
  `menu_visible` int(11) NOT NULL DEFAULT '1',
  `image_url` varchar(511) DEFAULT NULL,
  `kcal_per_100g` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `good_category_fk` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `good`
--

INSERT INTO `good` (`id`, `name`, `category_id`, `description`, `sort_index`, `menu_visible`, `image_url`, `kcal_per_100g`) VALUES
('susi-syake', 'Сяке', 'susi', 'рис, лосось', 1, 1, 'susi-syake.jpg', 175),
('roll-unagi', 'Унаги Ролл', 'rolls', 'рис, угорь копченый, свежий огурец, кунжут', 1, 1, 'roll-unagi.jpg', 97),
('duykan-kuro', 'Куро', 'duykans', 'черная икра летучей рыбы', 1, 1, 'duykan-kuro.jpg', 73),
('susi-ebi', 'Эби', 'susi', 'рис, тигровая креветка', 1, 1, 'susi-ebi.jpg', 77),
('roll-syake', 'Сяке Ролл', 'rolls', 'рис, сёмга', 1, 1, 'roll-syake.jpg', 111),
('duykan-kani', 'Суси кани', 'duykans', 'краб', 1, 1, 'duykan-kani.jpg', 73),
('susi_unagi', 'Унаги', 'susi', 'рис, копченый угорь', 1, 1, 'susi_unagi.png', 35),
('podarki_diskontnaya-karta-10', 'Дисконтная карта 10%', 'podarki', 'Дисконтная карта со скидкой 10%', 1, 0, 'podarki_diskontnaya-karta-10.jpg', 0),
('podarki_teyshoku', 'Тейшоку', 'podarki', 'Рис, семга копченая, маринованный огурец, сыр сливочный', 1, 0, 'podarki_teyshoku.jpg', 98),
('podarki_sok-1l', 'Сок 1л', 'podarki', 'любой сок на выбор', 1, 0, 'podarki_sok-1l.jpg', 0),
('podarki_shampanskoe', 'Шампанское', 'podarki', '', 1, 0, 'podarki_shampanskoe.jpg', 1),
('duykans_ikura', 'Икура', 'duykans', 'икра лососевая', 2, 1, 'duykans_ikura.jpg', 73),
('duykans_susi-filadel-fiya', 'Суси филадельфия', 'duykans', 'сливочный сыр, филе лосося, тобико', 3, 1, 'duykans_susi-filadel-fiya.jpg', 73),
('salaty_kayso-sarado', 'Кайсо сарадо', 'salaty', 'морские водоросли маринованные нежные с ореховым соусом', 4, 1, 'salaty_kayso-sarado.jpg', 256),
('salaty_kadi-cha', 'Кади ча', 'salaty', 'мясо жареное с баклажанами по-корейски', 5, 1, 'salaty_kadi-cha.jpg', 151),
('salaty_glamur', 'Гламур', 'salaty', 'креветки, огурец, тобико, авокадо', 6, 1, 'salaty_glamur.jpg', 196),
('zakuski_myasnoe-assorti', 'Мясное ассорти', 'zakuski', 'язык, карбонат, буженина, хрен', 7, 1, 'zakuski_myasnoe-assorti.jpg', 149),
('zakuski_kol-ca-kal-marov', 'Кольца кальмаров', 'zakuski', 'жаренные в сухарях', 8, 1, 'zakuski_kol-ca-kal-marov.jpg', 482),
('zakuski_krevetki-k-pivu', 'Креветки к пиву', 'zakuski', 'креветки, чеснок, соус', 9, 1, 'zakuski_krevetki-k-pivu.jpg', 184),
('pervye-blyuda_solyanka-myasnaya', 'Солянка мясная', 'pervye-blyuda', '', 10, 1, 'pervye-blyuda_solyanka-myasnaya.jpg', 111),
('pervye-blyuda_domashnyaya-lapsha', 'Домашняя лапша', 'pervye-blyuda', 'лапша, куриное филе', 11, 1, 'pervye-blyuda_domashnyaya-lapsha.jpg', 48),
('pervye-blyuda_bangkok', 'Бангкок', 'pervye-blyuda', 'говядина, лапша, удон, грибы шитаки, лук порей', 12, 1, 'pervye-blyuda_bangkok.jpg', 265),
('goryachie-blyuda_yaki-soba', 'Яки соба', 'goryachie-blyuda', 'свинина, лапша гречневая с овощами', 13, 1, 'goryachie-blyuda_yaki-soba.jpg', 236),
('goryachie-blyuda_esenabi', 'Ёсенаби', 'goryachie-blyuda', 'лапша яичная, свинина, овощи, соус', 14, 1, 'goryachie-blyuda_esenabi.jpg', 236),
('goryachie-blyuda_karbonaro', 'Карбонаро', 'goryachie-blyuda', '', 15, 1, 'goryachie-blyuda_karbonaro.jpg', 87),
('uzbekskaya-kuznya_lagman', 'Лагман', 'uzbekskaya-kuznya', 'узбекская лапша, с мясом и овощами', 16, 1, 'uzbekskaya-kuznya_lagman.jpg', 70),
('uzbekskaya-kuznya_shurpa-uzbekskaya', 'Шурпа узбекская', 'uzbekskaya-kuznya', 'мясной суп с овощами', 17, 1, 'uzbekskaya-kuznya_shurpa-uzbekskaya.jpg', 91),
('uzbekskaya-kuznya_plov', 'Плов', 'uzbekskaya-kuznya', 'рис, говядина, морковь, лук', 18, 1, 'uzbekskaya-kuznya_plov.jpg', 116),
('picca_s-govyadinoy-ostraya', 'С говядиной (острая)', 'picca', 'говядина, перец, помидоры, сыр, соус томатный', 19, 1, NULL, 0),
('picca_s-moreproduktami', 'С морепродуктами', 'picca', 'кальмары, мидии, креветки, семга с/с, маслян. рыба, соус белый, маслины, сыр', 20, 1, NULL, 0),
('zharenye-rolly_boston-roll', 'Бостон ролл', 'zharenye-rolly', 'рис, крабовое мясо, сыр сливочный, св. огурец, омлет, кабояки, обжареные во фритюре панир. сухарями', 21, 1, 'zharenye-rolly_boston-roll.jpg', 210),
('zharenye-rolly_goryachiy-roll', 'Горячий ролл', 'zharenye-rolly', 'семга, св. огурец, авокадо, сыр сл., икра летучей рыбы, кабояки, обжаренные в кляре с панир. сухарями', 22, 1, 'zharenye-rolly_goryachiy-roll.jpg', 220),
('mangal_vyrezka-govyazh-ya', 'Вырезка говяжья', 'mangal', '', 23, 1, NULL, 0),
('susi_hotate', 'Хотатэ', 'susi', 'рис, нори, морской гребешок', 24, 1, 'susi_hotate.jpg', 153),
('susi_tay', 'Тай', 'susi', 'рис, нори, окунь', 25, 1, 'susi_tay.jpg', 73),
('duykans_chuka', 'Чука', 'duykans', 'маринованные водоросли', 26, 1, 'duykans_chuka.jpg', 73),
('duykans_spaysi-kani', 'Спайси кани', 'duykans', 'острые суси с крабом', 27, 1, 'duykans_spaysi-kani.jpg', 73),
('duykans_spaysi-unagi', 'Спайси унаги', 'duykans', 'острые суси с копченым угрем', 28, 1, 'duykans_spaysi-unagi.jpg', 73),
('duykans_spaysi-syake', 'Спайси сяке', 'duykans', 'острые суси с лососем', 29, 1, 'duykans_spaysi-syake.jpg', 73),
('duykans_spaysi-maguro', 'Спайси магуро', 'duykans', 'острые суси с тунцом', 30, 1, 'duykans_spaysi-maguro.jpg', 73),
('duykans_tobiko', 'Тобико', 'duykans', 'оранжевая икра летучей рыбы', 31, 1, 'duykans_tobiko.jpg', 73),
('duykans_vasabiko', 'Васабико', 'duykans', 'пикантная зеленая икра летучей рыбы', 32, 1, 'duykans_vasabiko.jpg', 73),
('rolls_yasay-roll', 'Ясай ролл', 'rolls', 'рис, морковь, свежий огурец, лист салата, перец болгарский', 33, 1, 'rolls_yasay-roll.jpg', 70),
('rolls_kappa-maki', 'Каппа маки', 'rolls', 'рис, свежий огурец, кунжут', 34, 1, 'rolls_kappa-maki.jpg', 78),
('rolls_tekka-roll', 'Текка ролл', 'rolls', 'рис, тунец', 35, 1, 'rolls_tekka-roll.jpg', 91),
('rolls_ebi-roll', 'Эби ролл', 'rolls', 'рис, креветка', 36, 1, 'rolls_ebi-roll.jpg', 85),
('rolls_teyshoku', 'Тейшоку', 'rolls', 'рис, сёмга копченая, маринованный огурец, сыр сливочный', 37, 1, 'rolls_teyshoku.jpg', 98),
('rolls_piramida-roll', 'Пирамида ролл', 'rolls', 'рис, нори, угорь копченый, икра летучей рыбы, сыр, огурец', 38, 1, 'rolls_piramida-roll.jpg', 170),
('rolls_ika-ebi-maki', 'Ика эби маки', 'rolls', 'рис, нори, кальмар, креветка, огурец свежий, сливочный сыр', 39, 1, 'rolls_ika-ebi-maki.jpg', 130),
('rolls_vulkan', 'Вулкан', 'rolls', 'гребешок, лосось, майонез, тобико', 40, 1, 'rolls_vulkan.jpg', 90),
('rolls_tori-spays', 'Тори спайс', 'rolls', 'куриное филе гриль, салат айсберг, спайс соус, унаги соус, кунжут', 41, 1, 'rolls_tori-spays.jpg', 90),
('rolls_lava-maki', 'Лава маки', 'rolls', 'угорь копченый, креветка, васабико, спайс соус', 42, 1, 'rolls_lava-maki.jpg', 90),
('rolls_futo-maki', 'Футо маки', 'rolls', 'рис, нори, сёмга, авокадо, креветки', 43, 1, 'rolls_futo-maki.jpg', 98),
('rolls_evraziya-roll', 'Евразия ролл', 'rolls', 'рис, угорь копч. сёмга жареная, сыр сливочный, св. огурец, зеленый лук', 44, 1, 'rolls_evraziya-roll.jpg', 196),
('rolls_tokado', 'Токадо', 'rolls', 'рис, нори, перец болгар., салат, сёмга, сыр тельзитер', 45, 1, 'rolls_tokado.jpg', 97),
('rolls_yodzhi', 'Йоджи', 'rolls', 'рис, угорь копчёный, лосось, огурец, авокадо, майонез, кунжут', 46, 1, 'rolls_yodzhi.jpg', 127),
('rolls_acuy-maki', 'Ацуй маки', 'rolls', 'рис, лук зелёный, нори, сыр, салат', 47, 1, 'rolls_acuy-maki.jpg', 90),
('rolls_bimi-roll', 'Бими ролл', 'rolls', 'рис, лосось, икра лососевая, сыр, авокадо', 48, 1, 'rolls_bimi-roll.jpg', 146),
('rolls_segun-maki', 'Сёгун маки', 'rolls', 'креветка в панко, лосось, угорь копченый, тунец, тобико, огурец, авокадо, унаги соус', 49, 1, 'rolls_segun-maki.jpg', 90),
('rolls_okinava-maki', 'Окинава маки', 'rolls', 'креветка в сухарях панко, филе лосося, сливочный сыр, огурец', 50, 1, 'rolls_okinava-maki.jpg', 90),
('rolls_kurimu-unagi', 'Куриму унаги', 'rolls', 'копченый угорь, салат айсберг, сливочный сыр', 51, 1, 'rolls_kurimu-unagi.jpg', 90),
('rolls_hokkaydo-maki', 'Хоккайдо маки', 'rolls', 'салат айсберг, сливочный сыр', 52, 1, 'rolls_hokkaydo-maki.jpg', 90),
('rolls_kaliforniya-maki', 'Калифорния маки', 'rolls', 'тобико, авокадо, майонез, краб', 53, 1, 'rolls_kaliforniya-maki.jpg', 90),
('rolls_tay-spaysi', 'Тай спайси', 'rolls', 'рис, нори, окунь, авокадо, свежий огурец, спайси соус, в стружке тунца', 54, 1, 'rolls_tay-spaysi.jpg', 101),
('rolls_syake-spaysi', 'Сяке спайси', 'rolls', 'рис, нори, сёмга, авокадо, свежий огурец, спайси соус, в стружке тунца', 55, 1, 'rolls_syake-spaysi.jpg', 122),
('rolls_maguro-spaysi', 'Магуро спайси', 'rolls', 'рис, нори, тунец, авокадо, свежий огурец, спайси соус, в стружке тунца', 56, 1, NULL, 110),
('rolls_drakon-maki', 'Дракон маки', 'rolls', 'угорь копченый, огурец, сливочный сыр, унаги соус, кунжут', 57, 1, 'rolls_drakon-maki.jpg', 90),
('rolls_royal-filadel-fiya', 'Роял филадельфия', 'rolls', 'филе лосося, сливочный сыр, тобико, икра лососевая', 58, 1, 'rolls_royal-filadel-fiya.jpg', 90),
('rolls_kado-maki', 'Кадо маки', 'rolls', 'угорь копченый, лосось, сливочный сыр, унаги соус, кунжут', 59, 1, 'rolls_kado-maki.jpg', 90),
('rolls_aysberg', 'Айсберг', 'rolls', 'рис, нори, сыр, тунец в сухарях спайси, майонез', 60, 1, 'rolls_aysberg.jpg', 90),
('rolls_himavari-maki', 'Химавари маки', 'rolls', 'филе лосося, краб, тобико, икра лососевая, спайс соус, огурец, авокадо', 61, 1, 'rolls_himavari-maki.jpg', 90),
('rolls_kaydzhi-maki', 'Кайджи маки', 'rolls', 'филе лосося, угорь копченый, сливочный сыр, панко, сухари, корнишон', 62, 1, 'rolls_kaydzhi-maki.jpg', 90),
('rolls_akay-maki', 'Акай маки', 'rolls', 'филе лосося, гребешок, сливочный сыр, тобико, соевая бумага, лук зеленый, огурцы', 63, 1, 'rolls_akay-maki.jpg', 90),
('rolls_fusion', 'Fusion', 'rolls', 'угорь копченый, филе лосося, гребешок, соевая бумага, огурец, тобико, унаги соус, кунжут, майонез', 64, 1, 'rolls_fusion.jpg', 90),
('rolls_iskushenie', 'Искушение', 'rolls', 'рис, нори, сыр, ананас, окунь, унаги соус, кунжут, авокадо', 65, 1, 'rolls_iskushenie.jpg', 111),
('rolls_nezhnyy', 'Нежный', 'rolls', 'рис, сыр, майонез, краб, омлет', 66, 1, 'rolls_nezhnyy.jpg', 111),
('rolls_veneciya', 'Венеция', 'rolls', 'рис, нори, угорь, тобико, кунжут, сыр, ананас в сухарях', 67, 1, 'rolls_veneciya.jpg', 111),
('rolls_hitoshi', 'Хитоши', 'rolls', 'рис, огурец, нори, авокадо, сыр, кунжут, угорь, стружка тунца', 68, 1, 'rolls_hitoshi.jpg', 101),
('rolls_totigi', 'Тотиги', 'rolls', 'рис, сёмга жареная, икра тобико, авокадо, свежий огурец, японский майонез, омлет', 69, 1, 'rolls_totigi.jpg', 130),
('rolls_ikura-maki', 'Икура маки', 'rolls', 'рис, нори, сёмга, красная икра, огурец, сливочный сыр, авокадо, лист салата', 70, 1, 'rolls_ikura-maki.jpg', 133),
('rolls_grin-roll', 'Грин ролл', 'rolls', 'рис, лосось копчёный, икра лососевая, сыр, зелень, авокадо', 71, 1, 'rolls_grin-roll.jpg', 132),
('rolls_domashniy', 'Домашний', 'rolls', 'рис, угорь копч. крабовое мясо, авокадо, огурец, кунжут, икра летучей рыбы, японский майонез, омлет', 72, 1, 'rolls_domashniy.jpg', 129),
('rolls_tokio-maki', 'Токио маки', 'rolls', 'лосось гриль, сливочный сыр, угорь, авокадо, тобико, кунжут', 73, 1, 'rolls_tokio-maki.jpg', 90),
('zharenye-rolly_roll-evropa', 'Ролл Европа', 'zharenye-rolly', 'рис, сёмга, авокадо, сыр сливочный свежий огурец, лист салата, соус кабояки, обжаренные во фритюре', 74, 1, 'zharenye-rolly_roll-evropa.jpg', 209),
('zharenye-rolly_unagi-tempura', 'Унаги темпура', 'zharenye-rolly', 'рис, водоросли чука, угорь, лист салата, ореховый соус', 75, 1, 'zharenye-rolly_unagi-tempura.jpg', 207),
('zharenye-rolly_higari-roru', 'Хигари рору', 'zharenye-rolly', 'острый теплый ролл с семгой и морским гребешком', 76, 1, 'zharenye-rolly_higari-roru.jpg', 215),
('zharenye-rolly_makedoniya', 'Македония', 'zharenye-rolly', 'рис, нори, креветки, мидии, тобико, майонез, жареный в кляре', 77, 1, 'zharenye-rolly_makedoniya.jpg', 209),
('zharenye-rolly_ital-yanskiy', 'Итальянский', 'zharenye-rolly', 'рис, нори, слив. сыр, очищенные креветки, омлет, болг. перец. жареный в кляре.', 78, 1, 'zharenye-rolly_ital-yanskiy.jpg', 169),
('zharenye-rolly_francuzskiy', 'Французский', 'zharenye-rolly', 'рис, нори, раковые шейки, сыр сливочный, авокадо, св. огурец, лист салата, жарен. в кляре, подается со сливочным соусом', 79, 1, 'zharenye-rolly_francuzskiy.jpg', 225),
('zharenye-rolly_umino-roll', 'Умино ролл', 'zharenye-rolly', 'рис, нори, сёмга, мидии, креветки, кальмар, сыр сливочный, огурец свежий, сухари панко, обжаренный в кляре', 80, 1, 'zharenye-rolly_umino-roll.jpg', 240),
('zharenye-rolly_sakura', 'Сакура', 'zharenye-rolly', 'рис, окунь, кунжут, омлет, огурец, унаги соус, сухари панко', 81, 1, 'zharenye-rolly_sakura.jpg', 209),
('zharenye-rolly_geysha', 'Гейша', 'zharenye-rolly', 'рис, нори, сыр, креветка, кальмар, огурец, сухари, соус унаги', 82, 1, 'zharenye-rolly_geysha.jpg', 90),
('zharenye-rolly_inari', 'Инари', 'zharenye-rolly', 'рис, нори, перец болг. спайси, мосаго, окунь, кальмар, сухари, соус унаги', 83, 1, 'zharenye-rolly_inari.jpg', 78),
('mangal_shashlyk-iz-baraniny', 'Шашлык из баранины', 'mangal', '', 84, 1, NULL, 0),
('mangal_shashlyk-iz-svininy', 'Шашлык из свинины', 'mangal', '', 85, 1, NULL, 0),
('mangal_semga', 'Сёмга', 'mangal', '', 86, 1, NULL, 0),
('mangal_kefal-chernomorskaya', 'Кефаль черноморская', 'mangal', '', 87, 1, NULL, 0),
('mangal_cyplenok', 'Цыплёнок', 'mangal', '', 88, 1, NULL, 0),
('mangal_kryl-ya-kurinye', 'Крылья куриные', 'mangal', '', 89, 1, NULL, 0),
('mangal_rebryshki-baran-i', 'Ребрышки бараньи', 'mangal', '', 90, 1, NULL, 0),
('mangal_rebryshki-svinnye', 'Ребрышки свинные', 'mangal', '', 91, 1, NULL, 0),
('mangal_pomidor', 'Помидор', 'mangal', '', 92, 1, NULL, 0),
('mangal_baklazhan', 'Баклажан', 'mangal', '', 93, 1, NULL, 0),
('mangal_perec-bolgarskiy', 'Перец болгарский', 'mangal', '', 94, 1, NULL, 0),
('mangal_shampin-ony', 'Шампиньоны', 'mangal', '', 95, 1, NULL, 0),
('mangal_kartofel', 'Картофель', 'mangal', '', 96, 1, NULL, 0),
('picca_cezar', 'Цезарь', 'picca', 'куриное филе, помидор, лист салата, соус &quot;Цезарь&quot;, сыр &quot;Гауда&quot;', 97, 1, NULL, 0),
('picca_s-vetchinoy-i-gribami', 'С ветчиной и грибами', 'picca', 'ветчина, грибы, сыр, помидоры, соус томатно-сливочный маслины', 98, 1, NULL, 0),
('picca_s-kurichec-i-baklazhanami', 'С куричец и баклажанами', 'picca', 'Куриное филе, перец. помидоры, баклажаны, соус чесночный, сыр', 99, 1, NULL, 0),
('picca_s-salyami-i-gribami', 'С салями и грибами', 'picca', 'салями, грибы, перец, соус томатный, маслины, сыр', 100, 1, NULL, 0),
('picca_assorti-myasnaya', 'Ассорти-мясная', 'picca', 'ветчина, курица, салями, помидоры, перец, грибы, соус томатный, маслины, сыр', 101, 1, NULL, 0),
('picca_4-sezona', '4 сезона', 'picca', 'ветчина, салями, грибы, помидоры, баклажаны, соус томатный, маслины, сыр', 102, 1, NULL, 0),
('picca_4-syra', '4 сыра', 'picca', 'сыр дор-блю, пармезан, маасдам, бри', 103, 1, NULL, 0),
('picca_margarita', 'Маргарита', 'picca', 'соус томатный, сыр, маслины, помидоры', 104, 1, NULL, 0),
('ris-lapsha_lapsha-ruchnoy-raboty', 'Лапша ручной работы', 'ris-lapsha', 'морковь, перец болгарский, зелень, грибы, баклажан, пекинская капуста', 105, 0, NULL, 0),
('ris-lapsha_lapsha-grechnevaya', 'Лапша гречневая', 'ris-lapsha', 'морковь, перец болгарский, зелень, грибы, баклажан, пекинская капуста', 106, 1, NULL, 0),
('ris-lapsha_yaichnaya', 'Лапша яичная', 'ris-lapsha', 'морковь, перец болгарский, зелень, грибы, баклажан, пекинская капуста', 107, 1, NULL, 0),
('ris-lapsha_lapsha-steklyannaya', 'Лапша стеклянная', 'ris-lapsha', 'морковь, перец болгарский, зелень, грибы, баклажан, пекинская капуста', 108, 1, NULL, 0),
('ris-lapsha_lapsha-pshenichnaya', 'Лапша пшеничная', 'ris-lapsha', 'морковь, перец болгарский, зелень, грибы, баклажан, пекинская капуста', 109, 1, NULL, 0),
('ris-lapsha_ris', 'Рис', 'ris-lapsha', 'морковь, перец болгарский, зелень, грибы, баклажан, пекинская капуста', 110, 1, 'ris-lapsha_ris.jpg', 0),
('sousy_kislo-sladkiy', 'Кисло-сладкий', 'sousy', '', 111, 1, NULL, 0),
('sousy_slivochnyy', 'Сливочный', 'sousy', '', 112, 1, NULL, 0),
('sousy_ustrichnyy', 'Устричный', 'sousy', '', 113, 1, NULL, 0),
('sousy_teriyaki', 'Терияки', 'sousy', '', 114, 1, NULL, 0),
('sousy_kimchi', 'Кимчи', 'sousy', '', 115, 1, NULL, 0),
('dopolnitel-no_govyadina', 'Говядина', 'dopolnitel-no', '', 116, 1, NULL, 0),
('dopolnitel-no_svinina', 'Свинина', 'dopolnitel-no', '', 117, 1, NULL, 0),
('dopolnitel-no_kurica', 'Курица', 'dopolnitel-no', '', 118, 1, NULL, 0),
('dopolnitel-no_bekon', 'Бекон', 'dopolnitel-no', '', 119, 1, NULL, 0),
('dopolnitel-no_semga', 'Сёмга', 'dopolnitel-no', '', 120, 1, NULL, 0),
('dopolnitel-no_kal-mar', 'Кальмар', 'dopolnitel-no', '', 121, 1, NULL, 0),
('dopolnitel-no_krevetki', 'Креветки', 'dopolnitel-no', '', 122, 1, NULL, 0),
('dopolnitel-no_midii', 'Мидии', 'dopolnitel-no', '', 123, 1, NULL, 0),
('dopolnitel-no_moreprodukty', 'Морепродукты', 'dopolnitel-no', '', 124, 1, NULL, 0),
('dopolnitel-no_ugor', 'Угорь', 'dopolnitel-no', '', 125, 1, NULL, 0),
('zapechennye_banzay', 'Банзай', 'zapechennye', 'угорь, майонез, огурец, лук зеленый', 126, 1, 'zapechennye_banzay.jpg', 257),
('zapechennye_s-bekonom', 'С беконом', 'zapechennye', 'бекон, огурец, майонез, помидор, лист салата', 127, 1, 'zapechennye_s-bekonom.jpg', 207),
('zapechennye_kanzas', 'Канзас', 'zapechennye', 'икра масаго, угорь, сыр сливочный креветка, лосось, спайси соус, кунжут', 128, 1, 'zapechennye_kanzas.jpg', 207),
('zapechennye_s-semgoy', 'С сёмгой', 'zapechennye', 'сёмга, огурец, сыр сливочный, майонез с икрой масаго, угорь, нори', 129, 1, 'zapechennye_s-semgoy.jpg', 207),
('zapechennye_mayami', 'Майами', 'zapechennye', 'сёмга, мидии, сыр моцарела, майонез, огурец', 130, 1, 'zapechennye_mayami.jpg', 207),
('zapechennye_siciliya', 'Сицилия', 'zapechennye', 'угорь, майонез, сыр сливочный, кунжут, соус унаги, куриное филе', 131, 1, 'zapechennye_siciliya.jpg', 207);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `menu_visible` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `self_take` tinyint(4) NOT NULL DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `is_birthday` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `order`
--

INSERT INTO `order` (`id`, `date_time`, `phone_number`, `customer_name`, `address`, `self_take`, `status`, `is_birthday`) VALUES
(1, '2013-12-29 09:57:40', '77756756', 'ппвпв', 'аврварв', 1, 0, 0),
(2, '2013-12-29 10:04:15', '9898090444', 'Станислав', 'ул. Линейная 19 кв.16', 0, 3, 0),
(3, '2014-01-07 11:46:14', '89878016666', 'Покупатель', 'Саратов. Долгопрудная 15 а', 0, 3, 0),
(4, '2014-01-07 18:06:17', '9999996666', 'папа', 'Комсомольская д.15 кв 1', 0, 3, 0),
(5, '2014-01-07 18:14:17', '879788765', 'тест', 'ул.Акопова, д.18, кв.10', 0, 1, 0),
(8, '2014-01-25 21:42:11', '0000000000', 'TEST', '', 1, 0, 1),
(9, '2014-01-25 21:48:44', '0000000000', 'TEST2', '', 1, 0, 1),
(10, '2016-05-05 00:47:28', '9878558662', 'Пегол', '410512 Саратов ул. 45я', 0, 0, 0),
(11, '2016-05-05 01:16:41', '8528585655', 'авов', 'впвыпвпывпыв ыпывпвыпыв', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `order_detail`
--

CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `portion_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '1',
  `cost` decimal(18,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id`),
  KEY `order_detail_order_fk` (`order_id`),
  KEY `order_detail_portion_fk` (`portion_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `portion_id`, `amount`, `cost`) VALUES
(1, 1, 2, 1, '119.000000'),
(2, 1, 1, 1, '147.000000'),
(3, 2, 6, 3, '1221.000000'),
(4, 2, 4, 5, '855.000000'),
(5, 2, 2, 1, '119.000000'),
(6, 2, 3, 2, '386.000000'),
(7, 3, 2, 4, '476.000000'),
(8, 3, 1, 3, '441.000000'),
(9, 3, 4, 3, '513.000000'),
(10, 3, 3, 1, '193.000000'),
(11, 4, 4, 1, '171.000000'),
(12, 4, 3, 1, '193.000000'),
(13, 5, 2, 1, '119.000000'),
(14, 5, 1, 1, '147.000000'),
(15, 5, 4, 1, '171.000000'),
(16, 6, 32, 10, '1700.000000'),
(17, 7, 33, 10, '1800.000000'),
(18, 0, 3, 8, '1080.000000'),
(19, 0, 30, 2, '640.000000'),
(20, 0, 31, 2, '600.000000'),
(21, 0, 30, 2, '640.000000'),
(22, 0, 31, 2, '600.000000'),
(23, 0, 30, 2, '640.000000'),
(24, 0, 31, 2, '600.000000'),
(25, 0, 30, 2, '640.000000'),
(26, 0, 31, 2, '600.000000'),
(27, 0, 30, 2, '640.000000'),
(28, 0, 31, 2, '600.000000'),
(29, 8, 30, 2, '640.000000'),
(30, 8, 31, 2, '600.000000'),
(31, 9, 30, 2, '640.000000'),
(32, 9, 31, 2, '600.000000'),
(33, 10, 4, 11, '1980.000000'),
(34, 11, 6, 12, '540.000000'),
(35, 11, 30, 7, '2240.000000');

-- --------------------------------------------------------

--
-- Структура таблицы `portion`
--

CREATE TABLE IF NOT EXISTS `portion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `good_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `gramms` int(11) NOT NULL DEFAULT '0',
  `milliliters` int(11) NOT NULL DEFAULT '0',
  `price` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `menu_visible` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `good_id` (`good_id`,`amount`,`gramms`,`milliliters`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=143 ;

--
-- Дамп данных таблицы `portion`
--

INSERT INTO `portion` (`id`, `good_id`, `amount`, `gramms`, `milliliters`, `price`, `menu_visible`) VALUES
(1, 'duykan-kani', 1, 100, 0, '147.000000', 1),
(2, 'duykan-kuro', 1, 100, 0, '119.000000', 1),
(3, 'roll-syake', 6, 105, 0, '135.000000', 1),
(4, 'roll-unagi', 6, 110, 0, '180.000000', 1),
(5, 'susi-ebi', 1, 35, 0, '60.000000', 1),
(6, 'susi-syake', 1, 35, 0, '45.000000', 1),
(7, 'susi_unagi', 1, 35, 0, '60.000000', 1),
(9, 'podarki_teyshoku', 6, 210, 0, '0.000000', 1),
(10, 'podarki_sok-1l', 1, 0, 1000, '0.000000', 1),
(11, 'podarki_diskontnaya-karta-10', 1, 0, 0, '0.000000', 1),
(12, 'podarki_shampanskoe', 1, 0, 0, '0.000000', 1),
(13, 'duykans_ikura', 1, 30, 0, '65.000000', 1),
(14, 'duykans_susi-filadel-fiya', 1, 30, 0, '65.000000', 1),
(15, 'salaty_kayso-sarado', 1, 150, 0, '150.000000', 1),
(16, 'salaty_kadi-cha', 1, 150, 0, '95.000000', 1),
(17, 'salaty_glamur', 1, 150, 0, '160.000000', 1),
(18, 'zakuski_myasnoe-assorti', 1, 150, 0, '180.000000', 1),
(19, 'zakuski_kol-ca-kal-marov', 7, 100, 0, '120.000000', 1),
(20, 'zakuski_krevetki-k-pivu', 1, 160, 0, '190.000000', 1),
(21, 'pervye-blyuda_solyanka-myasnaya', 1, 400, 0, '150.000000', 1),
(22, 'pervye-blyuda_domashnyaya-lapsha', 1, 400, 0, '110.000000', 1),
(23, 'pervye-blyuda_bangkok', 1, 400, 0, '135.000000', 1),
(24, 'goryachie-blyuda_yaki-soba', 1, 200, 0, '140.000000', 1),
(25, 'goryachie-blyuda_esenabi', 1, 200, 0, '140.000000', 1),
(26, 'goryachie-blyuda_karbonaro', 1, 200, 0, '170.000000', 1),
(27, 'uzbekskaya-kuznya_lagman', 1, 300, 0, '120.000000', 1),
(28, 'uzbekskaya-kuznya_shurpa-uzbekskaya', 1, 300, 0, '150.000000', 1),
(29, 'uzbekskaya-kuznya_plov', 1, 300, 0, '120.000000', 1),
(30, 'picca_s-govyadinoy-ostraya', 1, 600, 0, '320.000000', 1),
(31, 'picca_s-moreproduktami', 1, 600, 0, '300.000000', 1),
(32, 'zharenye-rolly_boston-roll', 8, 170, 0, '170.000000', 1),
(33, 'zharenye-rolly_goryachiy-roll', 8, 215, 0, '180.000000', 1),
(34, 'mangal_vyrezka-govyazh-ya', 1, 0, 0, '180.000000', 1),
(35, 'susi_hotate', 1, 35, 0, '80.000000', 1),
(36, 'susi_tay', 1, 30, 0, '40.000000', 1),
(37, 'duykans_chuka', 1, 30, 0, '55.000000', 1),
(38, 'duykans_spaysi-kani', 1, 30, 0, '75.000000', 1),
(39, 'duykans_spaysi-unagi', 1, 30, 0, '75.000000', 1),
(40, 'duykans_spaysi-syake', 1, 30, 0, '70.000000', 1),
(41, 'duykans_spaysi-maguro', 1, 30, 0, '75.000000', 1),
(42, 'duykans_tobiko', 1, 1, 0, '55.000000', 1),
(43, 'duykans_vasabiko', 1, 30, 0, '55.000000', 1),
(44, 'rolls_yasay-roll', 8, 125, 0, '150.000000', 1),
(45, 'rolls_kappa-maki', 6, 105, 0, '90.000000', 1),
(46, 'rolls_tekka-roll', 6, 105, 0, '160.000000', 1),
(47, 'rolls_ebi-roll', 6, 105, 0, '160.000000', 1),
(48, 'rolls_teyshoku', 6, 120, 0, '165.000000', 1),
(49, 'rolls_piramida-roll', 6, 190, 0, '160.000000', 1),
(50, 'rolls_ika-ebi-maki', 8, 175, 0, '195.000000', 1),
(51, 'rolls_vulkan', 8, 255, 0, '190.000000', 1),
(52, 'rolls_tori-spays', 8, 220, 0, '145.000000', 1),
(53, 'rolls_lava-maki', 8, 230, 0, '190.000000', 1),
(54, 'rolls_futo-maki', 8, 165, 0, '155.000000', 1),
(55, 'rolls_evraziya-roll', 10, 240, 0, '500.000000', 1),
(56, 'rolls_tokado', 8, 195, 0, '185.000000', 1),
(57, 'rolls_yodzhi', 8, 250, 0, '295.000000', 1),
(58, 'rolls_acuy-maki', 8, 160, 0, '165.000000', 1),
(59, 'rolls_bimi-roll', 6, 210, 0, '205.000000', 1),
(60, 'rolls_segun-maki', 8, 220, 0, '295.000000', 1),
(61, 'rolls_okinava-maki', 8, 240, 0, '255.000000', 1),
(62, 'rolls_kurimu-unagi', 8, 160, 0, '145.000000', 1),
(63, 'rolls_hokkaydo-maki', 8, 220, 0, '245.000000', 1),
(64, 'rolls_kaliforniya-maki', 8, 200, 0, '275.000000', 1),
(65, 'rolls_tay-spaysi', 6, 170, 0, '140.000000', 1),
(66, 'rolls_syake-spaysi', 6, 170, 0, '160.000000', 1),
(67, 'rolls_maguro-spaysi', 6, 170, 0, '180.000000', 1),
(68, 'rolls_drakon-maki', 8, 200, 0, '195.000000', 1),
(69, 'rolls_royal-filadel-fiya', 8, 250, 0, '285.000000', 1),
(70, 'rolls_kado-maki', 6, 200, 0, '185.000000', 1),
(71, 'rolls_aysberg', 8, 200, 0, '280.000000', 1),
(72, 'rolls_himavari-maki', 6, 260, 0, '305.000000', 1),
(73, 'rolls_kaydzhi-maki', 8, 220, 0, '170.000000', 1),
(74, 'rolls_akay-maki', 1, 310, 0, '395.000000', 1),
(75, 'rolls_fusion', 1, 370, 0, '500.000000', 1),
(76, 'rolls_iskushenie', 8, 195, 0, '140.000000', 1),
(77, 'rolls_nezhnyy', 8, 130, 0, '175.000000', 1),
(78, 'rolls_veneciya', 8, 0, 0, '195.000000', 1),
(79, 'rolls_hitoshi', 6, 175, 0, '175.000000', 1),
(80, 'rolls_totigi', 6, 195, 0, '145.000000', 1),
(81, 'rolls_ikura-maki', 6, 215, 0, '170.000000', 1),
(82, 'rolls_grin-roll', 6, 210, 0, '140.000000', 1),
(83, 'rolls_domashniy', 6, 205, 0, '240.000000', 1),
(84, 'rolls_tokio-maki', 8, 200, 0, '165.000000', 1),
(85, 'zharenye-rolly_roll-evropa', 8, 160, 0, '170.000000', 1),
(86, 'zharenye-rolly_unagi-tempura', 10, 300, 0, '170.000000', 1),
(87, 'zharenye-rolly_higari-roru', 6, 260, 0, '170.000000', 1),
(88, 'zharenye-rolly_makedoniya', 10, 270, 0, '170.000000', 1),
(89, 'zharenye-rolly_ital-yanskiy', 8, 195, 0, '170.000000', 1),
(90, 'zharenye-rolly_francuzskiy', 8, 270, 0, '170.000000', 1),
(91, 'zharenye-rolly_umino-roll', 8, 220, 0, '190.000000', 1),
(92, 'zharenye-rolly_sakura', 8, 162, 0, '170.000000', 1),
(93, 'zharenye-rolly_geysha', 10, 10, 0, '170.000000', 1),
(94, 'zharenye-rolly_inari', 10, 255, 0, '170.000000', 1),
(95, 'mangal_shashlyk-iz-baraniny', 1, 0, 0, '120.000000', 1),
(96, 'mangal_shashlyk-iz-svininy', 1, 0, 0, '80.000000', 1),
(97, 'mangal_semga', 1, 0, 0, '150.000000', 1),
(98, 'mangal_kefal-chernomorskaya', 1, 0, 0, '90.000000', 1),
(99, 'mangal_cyplenok', 1, 0, 0, '60.000000', 1),
(100, 'mangal_kryl-ya-kurinye', 1, 0, 0, '50.000000', 1),
(101, 'mangal_rebryshki-baran-i', 1, 0, 0, '120.000000', 1),
(102, 'mangal_rebryshki-svinnye', 1, 0, 0, '70.000000', 1),
(103, 'mangal_pomidor', 1, 0, 0, '50.000000', 1),
(104, 'mangal_baklazhan', 1, 0, 0, '50.000000', 1),
(105, 'mangal_perec-bolgarskiy', 1, 0, 0, '50.000000', 1),
(106, 'mangal_shampin-ony', 1, 0, 0, '50.000000', 1),
(107, 'mangal_kartofel', 1, 0, 0, '40.000000', 1),
(108, 'picca_cezar', 1, 600, 0, '270.000000', 1),
(109, 'picca_s-vetchinoy-i-gribami', 1, 600, 0, '270.000000', 1),
(110, 'picca_s-kurichec-i-baklazhanami', 1, 600, 0, '270.000000', 1),
(111, 'picca_s-salyami-i-gribami', 1, 600, 0, '270.000000', 1),
(112, 'picca_assorti-myasnaya', 1, 600, 0, '270.000000', 1),
(113, 'picca_4-sezona', 1, 600, 0, '270.000000', 1),
(114, 'picca_4-syra', 1, 600, 0, '270.000000', 1),
(115, 'picca_margarita', 1, 600, 0, '270.000000', 1),
(116, 'ris-lapsha_lapsha-ruchnoy-raboty', 1, 0, 0, '120.000000', 1),
(117, 'ris-lapsha_lapsha-grechnevaya', 1, 0, 0, '120.000000', 1),
(118, 'ris-lapsha_yaichnaya', 1, 0, 0, '120.000000', 1),
(119, 'ris-lapsha_lapsha-steklyannaya', 1, 0, 0, '120.000000', 1),
(120, 'ris-lapsha_lapsha-pshenichnaya', 1, 0, 0, '120.000000', 1),
(121, 'ris-lapsha_ris', 1, 300, 0, '120.000000', 1),
(122, 'sousy_kislo-sladkiy', 1, 0, 0, '20.000000', 1),
(123, 'sousy_slivochnyy', 1, 0, 0, '20.000000', 1),
(124, 'sousy_ustrichnyy', 1, 0, 0, '20.000000', 1),
(125, 'sousy_teriyaki', 1, 0, 0, '20.000000', 1),
(126, 'sousy_kimchi', 1, 0, 0, '20.000000', 1),
(127, 'dopolnitel-no_govyadina', 1, 0, 0, '85.000000', 1),
(128, 'dopolnitel-no_svinina', 1, 0, 0, '65.000000', 1),
(129, 'dopolnitel-no_kurica', 1, 0, 0, '45.000000', 1),
(130, 'dopolnitel-no_bekon', 1, 0, 0, '70.000000', 1),
(131, 'dopolnitel-no_semga', 1, 0, 0, '80.000000', 1),
(132, 'dopolnitel-no_kal-mar', 1, 0, 0, '55.000000', 1),
(133, 'dopolnitel-no_krevetki', 1, 0, 0, '85.000000', 1),
(134, 'dopolnitel-no_midii', 1, 0, 0, '70.000000', 1),
(135, 'dopolnitel-no_moreprodukty', 1, 0, 0, '85.000000', 1),
(136, 'dopolnitel-no_ugor', 1, 0, 0, '90.000000', 1),
(137, 'zapechennye_banzay', 6, 170, 0, '170.000000', 1),
(138, 'zapechennye_s-bekonom', 6, 180, 0, '200.000000', 1),
(139, 'zapechennye_kanzas', 6, 240, 0, '250.000000', 1),
(140, 'zapechennye_s-semgoy', 6, 250, 0, '240.000000', 1),
(141, 'zapechennye_mayami', 6, 235, 0, '220.000000', 1),
(142, 'zapechennye_siciliya', 8, 290, 0, '280.000000', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `session_start` datetime NOT NULL,
  `login` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `buy_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Дамп данных таблицы `stats`
--

INSERT INTO `stats` (`id`, `session_start`, `login`, `role`, `session_id`, `buy_count`) VALUES
(3, '2014-01-25 13:41:00', 'admin', 'admin', '252dc540a45ce4417391d29524d6bd87', NULL),
(4, '2014-01-25 13:43:11', '', '', '629c1a01af815b07a8c0f6eb7018157f', NULL),
(5, '2014-01-25 20:03:05', '', '', '7ce7ec17b46587c12dc0b716168e8a61', NULL),
(6, '2014-01-25 20:35:18', '', '', '8f355bdf7ad32dd9428c654aa11d0128', NULL),
(7, '2014-01-25 20:35:25', '', '', '72a5aec06164a748901953cf7778eedf', NULL),
(8, '2014-01-26 12:17:11', '', '', 'd3c0726e3e33f3c5d3d1cc6eb9c4cefe', NULL),
(9, '2014-01-26 12:30:49', '', '', '04d69e6788189ad096c1c194165b6520', NULL),
(10, '2014-01-26 12:54:46', '', '', '629c1a01af815b07a8c0f6eb7018157f', NULL),
(11, '2016-04-28 12:16:46', '', '', 'vkahtc89ukujn71f5neium5ig3', NULL),
(12, '2016-04-28 12:17:23', '', '', 'fbrb5na963b1mfkn2vh1tjf8d1', NULL),
(13, '2016-04-28 23:57:08', '', '', '5nfsmovcj46u1018andbnfph77', NULL),
(14, '2016-04-29 00:00:23', '', '', 'pratjmqavj7p6ps3p0hmm3vfv0', NULL),
(15, '2016-04-29 00:37:53', '', '', '8rfkvn2rdqbsbli6rutjv73ub6', NULL),
(16, '2016-04-29 00:38:51', '', '', 'l19gk5d6ae50vh0m69siuk2nc2', NULL),
(17, '2016-04-29 00:54:14', '', '', 'gaokk169laqvnh5ppdg4ho6uq6', NULL),
(18, '2016-04-29 00:54:38', '', '', 'ekl3fpgakuvgejk6iq6m796m90', NULL),
(19, '2016-04-29 00:57:48', '', '', 'qitjagligufio11lhp4qrtpji7', NULL),
(20, '2016-04-29 01:06:32', '', '', 'rj0jv38vubnca3qfv85dvcj0m5', NULL),
(21, '2016-04-29 01:06:46', '', '', 'hf4p2llibn39jt96pveh8uhlj3', NULL),
(22, '2016-04-29 01:34:51', '', '', 'osmb5f5didfsmeg4bqg7mr0em1', NULL),
(23, '2016-04-29 01:34:55', '', '', 'q8v7ad44v9bjegu22v7vviajs0', NULL),
(24, '2016-04-29 01:36:18', '', '', 'fipt6baklerdro0ipgn9cnj5v2', NULL),
(25, '2016-04-29 11:34:26', '', '', '0lnd0uie5uotifsc7q42us1ft0', NULL),
(26, '2016-04-29 11:38:30', '', '', 'agkc3rp60jsa49rgigektpc5q0', NULL),
(27, '2016-04-29 21:41:40', '', '', '65lp6igihgm29jje8h6aabbv02', NULL),
(28, '2016-04-29 21:41:43', '', '', '8159r306rfo1ih7j6ahdd2qan2', NULL),
(29, '2016-05-03 09:54:18', '', '', 'mr8hpg73l90uaqtnrua3kfa611', NULL),
(30, '2016-05-03 09:54:22', '', '', '1r11qtlacv4v5o1cvndgff6ue3', NULL),
(31, '2016-05-04 13:31:03', '', '', '55psdh7hsmjat6uhvih24e4j16', NULL),
(32, '2016-05-04 23:19:05', '', '', '6av5g3a0arachuh9oj33gbvp26', NULL),
(33, '2016-05-05 00:04:30', '', '', 'vmmqhdp5tj56rrefmmvelpi6o4', NULL),
(34, '2016-05-05 00:04:35', '', '', '278j5h81q9t4s8o2kqn5569h05', NULL),
(35, '2016-05-05 01:12:30', '', '', '4shh5qecop6c1e5evv9ikoic35', NULL),
(36, '2016-05-05 12:00:17', '', '', '10tvmvgqqb1e0vr3hgmoj0rtt7', NULL),
(37, '2016-05-05 12:00:19', '', '', 'ki213dqv0363sjonsl7dej6316', NULL),
(38, '2016-05-05 23:50:25', '', '', 'hd02uqmie38eljnl1fp1um1t14', NULL),
(39, '2016-05-05 23:50:39', 'admin', 'admin', '3eh1fj0bv6f4qhenm8g4rmka44', NULL),
(40, '2016-05-06 12:17:04', '', '', 'qd0c7dpbs541uinunvrtuns8t5', NULL),
(41, '2016-05-06 12:17:10', '', '', 'c17rbvsjf6k0bsouqc9lcvth62', NULL),
(42, '2016-05-12 13:06:41', '', '', 'hdin1s3ccort6fu3kli2m8it62', NULL),
(43, '2016-05-12 13:47:56', '', '', 'nfpmu1uti7mv825tb5t0uauet7', NULL),
(44, '2016-05-12 13:48:06', 'admin', 'admin', 'ce0u2vap6jeb8c1k8rrog3iqm7', NULL),
(45, '2016-05-12 15:22:01', '', '', 'naii3ubsuhhicko3v49jp13r00', NULL),
(46, '2016-05-12 15:28:41', '', '', 'i6vbpu8047578spqbbgrhnlba4', NULL),
(47, '2016-05-12 15:28:46', '', '', '6akfqrafvnsgege74hupkp09b6', NULL),
(48, '2016-05-12 15:28:52', '', '', '77vphsm1bb4irlsgurhvf9n8d5', NULL),
(49, '2016-05-12 15:28:54', '', '', 'rlv4lrbareqppuu8ttdpmbj2n4', NULL),
(50, '2016-05-12 15:29:15', '', '', 'hbg8tl4kv4ccn9u797vbhih766', NULL),
(51, '2016-05-12 15:31:41', '', '', 'r0krk9fgrthvbegfugji2as766', NULL),
(52, '2016-05-12 15:31:43', '', '', 'kc4kf9r4pmratvf3agu6e6drd7', NULL),
(53, '2016-05-12 15:32:04', '', '', 'c2ajio25m3jdv3egojns1hoo14', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `login` varchar(63) NOT NULL,
  `password` varchar(63) NOT NULL,
  `role` varchar(63) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `role`) VALUES
(1, 'admin', '__a12345', 'admin'),
(2, 'operator', '!!654321q', 'operator');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
