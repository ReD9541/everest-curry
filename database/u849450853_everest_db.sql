-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 26, 2026 at 09:08 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u849450853_everest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `name_ja` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name_en`, `name_ja`, `sort_order`) VALUES
(1, 'Lunch Sets 11:00 ~ 15:00', 'ランチセット', 1),
(2, 'Dinner Sets 17:00 ~ 22:00', 'ディナーセット', 2),
(3, 'Curry - Onion Base', 'オニオンベース', 3),
(4, 'Curry - Tomato Base', 'トマトベース', 4),
(5, 'Curry - Spinach Base', 'ほうれん草ベース', 5),
(6, 'Curry - Mix Base', 'ミックスベース', 6),
(7, 'Naan', 'ナン', 7),
(8, 'Salad', 'サラダ', 8),
(9, 'Nepali Food Sets', 'ネパール料理セット', 9),
(10, 'Tandoori Food', 'タンドリー料理', 10),
(11, 'Momo & Noodles', 'モモ・麺料理', 11),
(12, 'Snacks & Sides', 'おつまみ・サイドメニュー', 12),
(13, 'Drinks', 'ドリンク', 13);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `name_ja` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `menu_type` enum('Lunch','Dinner','Grand') DEFAULT 'Grand',
  `is_spicy_customizable` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category_id`, `name_en`, `name_ja`, `price`, `description`, `menu_type`, `is_spicy_customizable`) VALUES
(1, 1, 'Lunch A Set', 'ランチAセット', 850, '●下記からカレー1つ(チキン/キーマ/ベジタブル/ダール(豆)) ●ナン or ライス ●セットドリンク ●サラダ ※焼きたてナン・ライスおかわり自由', 'Lunch', 1),
(2, 1, 'Lunch B Set', 'ランチBセット', 950, '●選べるカレーから1つ ●ナン or ライス ●セットドリンク ●サラダ ※焼きたてナン・ライスおかわり自由', 'Lunch', 1),
(3, 1, 'Lunch C Set', 'ランチCセット', 1150, '●選べるカレーから1つ ★スペシャルナン1つ ●セットドリンク ●サラダ ※焼きたてナン・ライスおかわり自由 ※おかわりナンはプレーンナンになります。', 'Lunch', 1),
(4, 1, 'Ladies Lunch Set', 'レディースランチセット', 1250, '●選べるカレーから1つ ★スペシャルナン1つ or ライス ●タンドリーエビ 1pcs or ガーリックティッカ 1pcs ●セットドリンク ●サラダ ●デザート ※焼きたてナン・ライスおかわり自由 ※おかわりナンはプレーンナンになります。', 'Lunch', 1),
(5, 1, 'Special Lunch Set', 'スペシャルランチセット', 1790, '●選べるカレーから1つ ★スペシャルナン1つ or ライス ●タンドリーチキン 1pcs ●セットドリンク ●サラダ ●デザート ※焼きたてナン・ライスおかわり自由 ※おかわりナンはプレーンナンになります。', 'Lunch', 1),
(6, 1, 'Children\'s Lunch Set', 'お子さまランチセット', 650, '●カレー1つ(チキン or バターチキン) ●ナン or ライス ●ポテトフライ ●セットドリンク ●デザート ※12歳未満限定メニューです', 'Lunch', 0),
(7, 1, 'Sri Lanka Curry Set', 'スリランカカレーセット', 990, '●スリランカカレー ●セットドリンク ●サラダ ※おかわりはできません', 'Lunch', 1),
(8, 1, 'Double Curry Set', 'ダブルカレーセット', 1090, '●選べるカレーから2つ ●ナン or ライス ●サラダ ●セットドリンク ※焼きたてナン・ライスおかわり自由', 'Lunch', 1),
(9, 1, 'Omu Rice Set', 'オムライスセット', 990, '●オムライス ●セットドリンク ●サラダ ※おかわりはできません', 'Lunch', 0),
(10, 1, 'Chicken Biryani Set', 'チキンビリヤニセット', 1050, '●チキンビリヤニ ●セットドリンク ●ライタ ※おかわりはできません', 'Lunch', 0),
(11, 1, 'Mutton Biryani Set', 'マトンビリヤニセット', 1250, '●マトンビリヤニ ●セットドリンク ●ライタ ※おかわりはできません', 'Lunch', 0),
(12, 1, 'Paratha Set', 'パラタセット', 990, '●プレーンパラタ2枚 ●ライス ●ネパールチキンカレー ●サラダ ●セットドリンク ※おかわりはできません', 'Lunch', 1),
(13, 2, 'Dinner A Set', 'ディナーAセット', 690, '●生ビール ●チキンティッカ 1pcs ●ガーリックティッカ 1pcs ●パパド', 'Dinner', 1),
(14, 2, 'Dinner B Set', 'ディナーBセット', 1090, '●選べるカレー1つ ●ナン or ライス ●チキンティッカ 1pcs ●サラダ ●セットドリンク ※焼きたてナン・ライスおかわり自由', 'Dinner', 1),
(15, 2, 'Dinner C Set', 'ディナーCセット', 1350, '●選べるカレー1つ ★スペシャルナン1つ ●チキンティッカ 1pcs ●サラダ ●セットドリンク ※焼きたてナン・ライスおかわり自由 ※おかわりナンはプレーンナンになります。', 'Dinner', 1),
(16, 2, 'Double Curry Set', 'ダブルカレーセット', 1190, '●選べるカレーから2つ ●ナン or ライス ●サラダ ●セットドリンク ※焼きたてナン・ライスおかわり自由', 'Dinner', 1),
(17, 2, 'Ladies Set', 'レディースセット', 1250, '●選べるカレー1つ ★スペシャルナン1つ or ライス ●タンドリーエビ 1pcs or ガーリックティッカ 1pcs ●セットドリンク ●サラダ ●デザート ※焼きたてナン・ライスおかわり自由 ※おかわりナンはプレーンナンになります。', 'Dinner', 1),
(18, 2, 'Special Set', 'スペシャルセット', 1790, '●選べるカレー2つ ★スペシャルナン1つ or ライス ●タンドリーチキン 1pcs ●サラダ ●セットドリンク ●デザート ※焼きたてナン・ライスおかわり自由 ※おかわりナンはプレーンナンになります。', 'Dinner', 1),
(19, 2, 'Couple Set', 'カップルセット', 3690, '●選べるカレー2つ ★スペシャルナン1つ ●ナン or ライス ●ミックスグリル ●ミックスサラダ ●セットドリンク2つ ●デザート2つ ※焼きたてナン・ライスおかわり自由 ※おかわりナンはプレーンナンになります。', 'Dinner', 1),
(20, 2, 'Paratha Set', 'パラタセット', 990, '●プレーンパラタ2枚 ●ライス ●ネパールチキンカレー ●サラダ ●セットドリンク ※おかわりはできません', 'Dinner', 1),
(21, 2, 'Children\'s Set', 'お子さまセット', 650, '●カレー1つ(チキン or バターチキン) ●ナン or ライス ●ポテトフライ ●セットドリンク ●デザート ※12歳未満限定メニューです', 'Dinner', 0),
(22, 2, 'Sri Lanka Curry Set', 'スリランカカレーセット', 990, '●スリランカカレー ●セットドリンク ●サラダ ※おかわりはできません', 'Dinner', 1),
(23, 2, 'Omu Rice Set', 'オムライスセット', 990, '●オムライス ●セットドリンク ●サラダ ※おかわりはできません', 'Dinner', 0),
(24, 2, 'Taco Rice Set', 'タコライスセット', 1090, '●タコライス ●セットドリンク ●サラダ ※おかわりはできません', 'Dinner', 0),
(25, 2, 'Chicken Over Rice Set', 'チキンオーバーライスセット', 990, '●チキンオーバーライス ●セットドリンク ※おかわりはできません', 'Dinner', 0),
(26, 2, 'Chicken Biryani Set', 'チキンビリヤニセット', 1050, '●チキンビリヤニ ●セットドリンク ●ライタ ※おかわりはできません', 'Dinner', 0),
(27, 2, 'Mutton Biryani Set', 'マトンビリヤニセット', 1250, '●マトンビリヤニ ●セットドリンク ●ライタ ※おかわりはできません', 'Dinner', 0),
(28, 3, 'Chicken Curry', 'チキンカレー', 690, '', 'Grand', 1),
(29, 3, 'Keema Curry', 'キーマカレー', 690, '', 'Grand', 1),
(30, 3, 'Vegetable Curry', 'ベジタブルカレー', 650, '', 'Grand', 1),
(31, 3, 'Dal Curry', 'ダル(豆) カレー', 650, '', 'Grand', 1),
(32, 3, 'Mutton Curry', 'マトンカレー', 850, '', 'Grand', 1),
(33, 3, 'Nepali Chicken Curry', 'ネパールチキンカレー', 690, '', 'Grand', 1),
(34, 3, 'Nepali Mutton Curry', 'ネパールマトンカレー', 850, '', 'Grand', 1),
(35, 4, 'Butter Chicken Curry', 'バターチキンカレー', 790, '', 'Grand', 1),
(36, 4, 'Butter Shrimp Curry', 'バターエビカレー', 790, '', 'Grand', 1),
(37, 4, 'Seafood Curry', 'シーフードカレー', 790, '', 'Grand', 1),
(38, 5, 'Horenso Chicken Curry', 'ほうれん草チキンカレー', 750, NULL, 'Grand', 1),
(39, 5, 'Horenso Mutton Curry', 'ほうれん草マトンカレー', 850, NULL, 'Grand', 1),
(40, 5, 'Horenso Shrimp Curry', 'ほうれん草エビカレー', 790, NULL, 'Grand', 1),
(41, 5, 'Horenso Cheese Curry', 'ほうれん草チーズカレー', 750, NULL, 'Grand', 1),
(42, 6, 'Mutton Masala Curry', 'マトンマサラカレー', 850, NULL, 'Grand', 1),
(43, 6, 'Chicken Masala Curry', 'チキンマサラカレー', 790, NULL, 'Grand', 1),
(44, 7, 'Plane Naan', 'プレーンナン', 250, NULL, 'Grand', 0),
(45, 7, 'Sesame Naan', 'ゴマナン', 350, NULL, 'Grand', 0),
(46, 7, 'Garlic Naan', 'ガーリックナン', 500, NULL, 'Grand', 0),
(47, 7, 'Garlic Cheese Naan', 'ガーリックチーズナン', 600, NULL, 'Grand', 0),
(48, 7, 'Cheese Naan', 'チーズナン', 600, NULL, 'Grand', 0),
(49, 7, 'Chocolate Cheese Naan', 'チョコチーズナン', 600, NULL, 'Grand', 0),
(50, 7, 'Keema Naan', 'キーマナン', 600, NULL, 'Grand', 0),
(51, 7, 'Chicken Roll Naan', 'チキンロールナン', 600, NULL, 'Grand', 0),
(52, 7, 'Cheese Coconut Naan', 'チーズココナッツナン', 600, NULL, 'Grand', 0),
(53, 8, 'Tuna Salad', 'ツナサラダ', 350, NULL, 'Grand', 0),
(54, 8, 'Tikka Salad', 'ティッカサラダ', 350, NULL, 'Grand', 0),
(55, 8, 'Corn Salad', 'コーンサラダ', 290, NULL, 'Grand', 0),
(56, 8, 'Stick Salad', 'スティックサラダ', 390, '(マヨネーズ&ピンクソルト)', 'Grand', 0),
(57, 8, 'Mix Salad', 'ミックスサラダ', 550, NULL, 'Grand', 0),
(58, 8, 'Nepali Salad', 'ネパールサラダ', 350, '(ニンジンとキュウリのサラダ)', 'Grand', 0),
(59, 9, 'Chicken Khana', 'チキン カナ', 870, '※全てドリンク付き', 'Grand', 0),
(60, 9, 'Mutton Khana', 'マトン カナ', 990, '※全てドリンク付き', 'Grand', 0),
(61, 9, 'Vegetable Khana', 'ベジタブル カナ', 850, '※全てドリンク付き', 'Grand', 0),
(62, 9, 'Chicken Thakali', 'チキン タカリ', 1250, '※全てドリンク付き', 'Grand', 0),
(63, 9, 'Mutton Thakali', 'マトン タカリ', 1390, '※全てドリンク付き', 'Grand', 0),
(64, 9, 'Vegetable Thakali', 'ベジタブル タカリ', 1250, '※全てドリンク付き', 'Grand', 0),
(65, 9, 'Sukuti Khaja', 'スクティカジャ', 1390, '※全てドリンク付き', 'Grand', 0),
(66, 9, 'Mix Bhutton', 'ミックス ブタン', 1290, NULL, 'Grand', 0),
(67, 9, 'Chicken Khaja', 'チキン カジャ', 1290, '※全てドリンク付き', 'Grand', 0),
(68, 9, 'Mutton Taas', 'マトンタース', 1100, '※全てドリンク付き', 'Grand', 0),
(69, 10, 'Chicken Tikka (2pcs)', 'チキン ティッカ (2pcs)', 400, NULL, 'Grand', 0),
(70, 10, 'Chicken Tikka (4pcs)', 'チキン ティッカ (4pcs)', 780, NULL, 'Grand', 0),
(71, 10, 'Tandoori Chicken (1pcs)', 'タンドリーチキン (1pcs)', 500, NULL, 'Grand', 0),
(72, 10, 'Tandoori Chicken (2pcs)', 'タンドリーチキン (2pcs)', 900, NULL, 'Grand', 0),
(73, 10, 'Chicken Seekh Kabab (2pcs)', 'チキンシークカバブ (2pcs)', 490, NULL, 'Grand', 0),
(74, 10, 'Chicken Seekh Kabab (4pcs)', 'チキンシークカバブ (4pcs)', 950, NULL, 'Grand', 0),
(75, 10, 'Garlic Tikka (2pcs)', 'ガーリックティッカ (2pcs)', 450, NULL, 'Grand', 0),
(76, 10, 'Garlic Tikka (4pcs)', 'ガーリックティッカ (4pcs)', 850, NULL, 'Grand', 0),
(77, 10, 'Tebasaki Kamayaki (3pcs)', '手羽先窯焼き (3pcs)', 590, NULL, 'Grand', 0),
(78, 10, 'Tandoori Prawn (2pcs)', 'タンドリーエビ (2pcs)', 490, NULL, 'Grand', 0),
(79, 10, 'Tandoori Prawn (4pcs)', 'タンドリーエビ (4pcs)', 950, NULL, 'Grand', 0),
(80, 10, 'Tandoori Mix', 'タンドリーミックス', 1200, '●タンドリーチキン 1pcs ●チキンティッカ 1pcs ●ガーリックティッカ 1pcs ●チキンシークカバブ 1pcs ●タンドリーエビ 1pcs', 'Grand', 0),
(81, 11, 'Momo (5pcs)', 'モモ (5pcs)', 390, '(蒸し餃子)', 'Grand', 0),
(82, 11, 'Momo (10pcs)', 'モモ (10pcs)', 750, '(蒸し餃子)', 'Grand', 0),
(83, 11, 'Fried Momo (6pcs)', 'フライドモモ (6pcs)', 600, NULL, 'Grand', 0),
(84, 11, 'Chilli Momo (6pcs)', 'チリモモ (6pcs)', 700, NULL, 'Grand', 0),
(85, 11, 'Soup Momo (6pcs)', 'スープモモ (6pcs)', 700, NULL, 'Grand', 0),
(86, 11, 'Vegetable Chowmein', 'ベジタブル チョウミン', 600, '(ネパール風焼きそば)', 'Grand', 0),
(87, 11, 'Egg Chowmein', 'エッグ チョウミン', 650, '(ネパール風焼きそば)', 'Grand', 0),
(88, 11, 'Chicken Chowmein', 'チキン チョウミン', 700, '(ネパール風焼きそば)', 'Grand', 0),
(89, 11, 'Egg & Chicken Mix Chowmein', 'エッグチキンミックス チョウミン', 750, '(ネパール風焼きそば)', 'Grand', 0),
(90, 11, 'Sukuti Chowmein', 'スクティ チョウミン', 990, '(ネパール風焼きそば)', 'Grand', 0),
(91, 11, 'Vegetable Thukpa', 'ベジタブル トゥクパ', 600, '(ネパール風うどん/麺料理)', 'Grand', 0),
(92, 11, 'Egg Thukpa', 'エッグ トゥクパ', 650, '(ネパール風うどん/麺料理)', 'Grand', 0),
(93, 11, 'Chicken Thukpa', 'チキン トゥクパ', 700, '(ネパール風うどん/麺料理)', 'Grand', 0),
(94, 11, 'Egg & Chicken Mix Thukpa', 'エッグチキンミックス トゥクパ', 750, '(ネパール風うどん/麺料理)', 'Grand', 0),
(95, 11, 'Momo Mix Thukpa', 'モモミックス トゥクパ', 990, '(ネパール風うどん/麺料理)', 'Grand', 0),
(96, 12, 'Samosa (1pcs)', 'サモサ (1pcs)', 300, 'スパイシーコロッケのパイ風', 'Grand', 0),
(97, 12, 'Samosa (2pcs)', 'サモサ (2pcs)', 550, 'スパイシーコロッケのパイ風', 'Grand', 0),
(98, 12, 'Samosa Chat', 'サモサ チャット', 790, NULL, 'Grand', 0),
(99, 12, 'Mutton Sekuwa', 'マトン セクワ', 950, '(串焼き料理)', 'Grand', 0),
(100, 12, 'Mutton Hyakula Fry', 'マトン ヒャクラ フライ', 950, NULL, 'Grand', 0),
(101, 12, 'Pork Sekuwa', 'ポーク セクワ', 790, '(串焼き料理)', 'Grand', 0),
(102, 12, 'Chicken Sekuwa', 'チキン セクワ', 790, '(串焼き料理)', 'Grand', 0),
(103, 12, 'Tebasaki (3pcs)', '手羽先 (3pcs)', 590, NULL, 'Grand', 0),
(104, 12, 'Mutton Choila', 'マトン チョイラ', 950, NULL, 'Grand', 0),
(105, 12, 'Pork Choila', 'ポーク チョイラ', 790, NULL, 'Grand', 0),
(106, 12, 'Chicken Choila', 'チキン チョイラ', 790, NULL, 'Grand', 0),
(107, 12, 'Mix Bhutton', 'ミックス ブタン', 750, NULL, 'Grand', 0),
(108, 12, 'Skuti', 'スクティ', 950, '(肉料理)', 'Grand', 0),
(109, 12, 'Chicken Chilly', 'チキン チリー', 790, NULL, 'Grand', 0),
(110, 12, 'Sunazuri Chilly', '砂ズリ チリー', 790, NULL, 'Grand', 0),
(111, 12, 'Vegetable Fried Rice', 'ベジタブル チャーハン', 790, NULL, 'Grand', 0),
(112, 12, 'Egg Fried Rice', 'エッグ チャーハン', 850, NULL, 'Grand', 0),
(113, 12, 'Chicken Fried Rice', 'チキン チャーハン', 890, NULL, 'Grand', 0),
(114, 12, 'Egg & Chicken Mix Fried Rice', 'エッグチキンミックス チャーハン', 990, NULL, 'Grand', 0),
(115, 12, 'Shrimp Fried Rice', 'エビ チャーハン', 990, NULL, 'Grand', 0),
(116, 12, 'Chatpat', 'チャットパット', 500, 'ライスパス・砕いたインスタント麺・野菜・スパイス・レモンを和えたおつまみです。', 'Grand', 0),
(117, 12, 'Aalu Jeera', 'アルジーラ', 500, 'ネパール風じゃがいもの和え物です。', 'Grand', 0),
(118, 12, 'Keema Noodles', 'キーマヌードル', 690, NULL, 'Grand', 0),
(119, 12, 'Pani Puri', 'パニプリ', 500, 'サクサクに揚げたボール状の生地に具材を詰めて一口で食べるスナックです。スパイシーな味わいです。', 'Grand', 0),
(120, 12, 'Dahipuri', 'ダヒプリ', 690, 'サクサクに揚げたボール状の生地に具材を詰めて一口で食べるスナックです。甘酸っぱいヨーグルトが濃厚な味わいです。', 'Grand', 0),
(121, 12, 'Bhatmas Sadeko', 'バトマス サデコ', 500, 'ネパール風大豆の和え物です。', 'Grand', 0),
(122, 12, 'Peanuts Sadeko', 'ピーナッツ サデコ', 500, 'ネパール風ピーナッツの和え物です。', 'Grand', 0),
(123, 12, 'Chawchaw Sadeko', 'チャウチャウ サデコ', 450, '砕いたインスタント麺の和え物です。', 'Grand', 0),
(124, 12, 'Dry Laphing', 'ドライ ラフィン', 450, 'ネパールの汁なし麺料理です。', 'Grand', 0),
(125, 12, 'Soup Laphing', 'スープラフィン', 500, 'ネパールの汁あり麺料理です。', 'Grand', 0),
(126, 12, 'Fried Poteto', 'フライドポテト', 350, NULL, 'Grand', 0),
(127, 12, 'Edamame', 'えだまめ', 290, NULL, 'Grand', 0),
(128, 13, 'Lassi', 'ラッシー', 300, '自家製ヨーグルトドリンク', 'Grand', 0),
(129, 13, 'Mango Lassi', 'マンゴーラッシー', 350, 'マンゴー風味の自家製ヨーグルトドリンク', 'Grand', 0),
(130, 13, 'Calpis', 'カルピス', 300, NULL, 'Grand', 0),
(131, 13, 'Coca Cola', 'コカコーラ', 300, NULL, 'Grand', 0),
(132, 13, 'Orange Juice', 'オレンジジュース', 300, NULL, 'Grand', 0),
(133, 13, 'Oolong Tea', 'ウーロン茶', 300, NULL, 'Grand', 0),
(134, 13, 'Coffee (Hot/Ice)', 'コーヒー (ホットorアイス)', 300, NULL, 'Grand', 0),
(135, 13, 'Draft Beer', '生ビール', 500, NULL, 'Grand', 0);

-- --------------------------------------------------------

--
-- Table structure for table `spiciness_levels`
--

CREATE TABLE `spiciness_levels` (
  `level_id` int(11) NOT NULL,
  `name_en` varchar(50) NOT NULL,
  `name_ja` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spiciness_levels`
--

INSERT INTO `spiciness_levels` (`level_id`, `name_en`, `name_ja`) VALUES
(0, 'Mild', '甘口'),
(1, 'Normal', '普通'),
(2, 'Medium', '中辛'),
(3, 'Hot', '辛口'),
(4, 'Very Hot', '激辛');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `spiciness_levels`
--
ALTER TABLE `spiciness_levels`
  ADD PRIMARY KEY (`level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
