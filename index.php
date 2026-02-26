<?php
// Require the database connection file securely
require_once __DIR__ . '/db_connect.php';

$query = "
    SELECT 
        c.name_en AS cat_en, c.name_ja AS cat_ja, 
        m.name_en, m.name_ja, m.price, m.description 
    FROM categories c 
    JOIN menu_items m ON c.id = m.category_id 
    ORDER BY c.sort_order, m.id
";

$stmt = $pdo->query($query);

$menu_data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cat_key = $row['cat_ja'] . '|' . $row['cat_en'];
    $menu_data[$cat_key][] = $row;
}

// --- DYNAMIC IMAGE PATH HELPER ---
function get_image_path($name_en) {
    $name = preg_replace('/\(.*?\)/', '', $name_en);
    $filename = strtolower(trim($name));
    $filename = preg_replace('/[^a-z0-9]+/', '-', $filename);
    $filename = trim($filename, '-');

    $expected_path = 'images/' . $filename . '.jpg';

    if (file_exists($expected_path)) {
        return $expected_path;
    } else {
        return 'https://placehold.co/600x400/eeeeee/a3a3a3?text=Photo+Coming+Soon&font=montserrat';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エベレストカレー | Everest Curry Kokura</title>
    <link rel="icon" type="image/jpeg" href="logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
        }

        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('banner_everest.jpg');
            background-size: cover;
            background-position: center;
        }

        .menu-bg {
        /* The warm cream 'paper' base color from your physical menu */
        background-color: #FCFAF5; 
        
        /* A subtle, elegant, seamless graphic overlay */
        background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
        background-repeat: repeat;
        background-attachment: fixed;
        }
        
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <nav class="bg-black text-white p-3 md:p-4 sticky top-0 z-50 shadow-xl border-b-4 border-red-700">
        <div class="container mx-auto flex items-center justify-between px-2 md:px-4 relative">
            <div class="hidden md:flex w-1/3"></div>
            <div class="w-full md:w-1/3 text-left md:text-center">
                <a href="#" class="text-xl md:text-3xl font-black text-white tracking-widest hover:text-red-500 transition">
                    エベレスト<span class="text-red-600">カレー</span>
                </a>
            </div>
            <div class="w-auto md:w-1/3 flex justify-end">
                <ul class="flex space-x-3 md:space-x-6 text-[10px] md:text-base font-bold tracking-wider whitespace-nowrap">
                    <li><a href="#menu" class="hover:text-red-500 transition">メニュー</a></li>
                    <li><a href="#access" class="hover:text-red-500 transition">アクセス</a></li>
                    <li><a href="#about" class="hover:text-red-500 transition">お問い合わせ</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-bg h-screen flex items-center justify-center text-center px-4 border-b-8 border-red-700">
        <div class="text-white max-w-2xl flex flex-col items-center">
            <h2 class="text-xl md:text-3xl mb-4 md:mb-6 font-black tracking-widest text-yellow-400 drop-shadow-lg">2月27日 NEW OPEN !</h2>
            <img src="logo.jpg" alt="Everest Curry Logo" class="w-48 sm:w-64 md:w-80 lg:w-96 h-auto mb-6 md:mb-8 shadow-2xl rounded-full border-2 md:border-4 border-red-700 bg-black">
            <p class="text-base md:text-xl mb-6 md:mb-8 font-bold tracking-wide drop-shadow-md">本場のインド・ネパール料理を小倉で。</p>
            <div class="flex flex-col md:flex-row justify-center gap-3 md:gap-4 w-full px-4 md:px-0">
                <span class="bg-red-700 px-4 md:px-6 py-2 md:py-3 rounded-full font-bold shadow-lg text-xs md:text-base border border-red-500 text-white">駐車場 5台有</span>
                <span class="bg-orange-600 px-4 md:px-6 py-2 md:py-3 rounded-full font-bold shadow-lg text-xs md:text-base border border-orange-400 text-white">テイクアウト OK</span>
                <span class="bg-yellow-500 px-4 md:px-6 py-2 md:py-3 rounded-full font-bold shadow-lg text-black text-xs md:text-base border border-yellow-300">飲み放題 有</span>
            </div>
        </div>
    </header>

    <section id="menu" class="py-12 md:py-16 menu-bg container mx-auto px-3 md:px-4 shadow-inner">
        <div class="text-center mb-6 md:mb-8">
            <h2 class="text-3xl md:text-4xl font-black text-red-700 tracking-wider">GRAND MENU</h2>
            <p class="text-gray-600 mt-1 md:mt-2 text-sm md:text-base font-bold tracking-widest">グランドメニュー</p>
        </div>

        <div class="max-w-6xl mx-auto">

            <?php if (empty($menu_data)): ?>
                <p class="text-center text-red-500 font-bold">メニューの読み込みに失敗しました。</p>
            <?php else: ?>

                <?php foreach ($menu_data as $category_combined => $items): ?>
                    <?php
                    list($cat_ja, $cat_en) = explode('|', $category_combined);
                    $is_set_menu = (stripos($cat_en, 'set') !== false);
                    $is_drink_menu = (stripos($cat_en, 'drink') !== false); // Detect if this is the Drinks section
                    
                    // Decide how many columns the grid should have based on the category
                    $grid_classes = 'grid-cols-1 lg:grid-cols-2 gap-3 md:gap-6'; // Default (Curries, Snacks)
                    if ($is_set_menu) $grid_classes = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8';
                    if ($is_drink_menu) $grid_classes = 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4'; // Tighter grid for drinks
                    ?>

                    <div class="mt-8 md:mt-12 mb-4 md:mb-6 w-full">
                        <div class="bg-gradient-to-r from-red-800 to-red-600 text-white inline-block px-5 md:px-8 py-2 md:py-3 rounded-r-full shadow-lg border-l-4 md:border-l-8 border-yellow-400">
                            <h3 class="text-lg md:text-2xl font-black tracking-widest drop-shadow-sm"><?= htmlspecialchars($cat_ja) ?></h3>
                            <p class="text-yellow-200 text-[10px] md:text-xs tracking-widest uppercase mt-0.5 md:mt-1 font-bold"><?= htmlspecialchars($cat_en) ?></p>
                        </div>
                    </div>

                    <div class="grid <?= $grid_classes ?>">

                        <?php foreach ($items as $item): ?>
                            <?php $photo_url = get_image_path($item['name_en']); ?>

                            <?php if ($is_set_menu): ?>
                                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 flex flex-col border border-orange-100 overflow-hidden">
                                    <div class="h-40 md:h-56 bg-gray-200 relative">
                                        <div class="absolute top-2 right-2 bg-yellow-400 text-red-800 text-[10px] md:text-xs font-black px-2 md:px-3 py-1 rounded-full shadow-sm z-10">SET</div>
                                        <img src="<?= $photo_url ?>" alt="<?= htmlspecialchars($item['name_en']) ?>" class="w-full h-full object-cover">
                                    </div>

                                    <div class="p-4 md:p-6 flex-grow flex flex-col">
                                        <div class="flex justify-between items-start mb-1 md:mb-2 gap-2">
                                            <h3 class="text-lg md:text-xl font-black text-gray-900 leading-tight"><?= htmlspecialchars($item['name_ja']) ?></h3>
                                            <span class="text-xl md:text-2xl font-black text-red-700 whitespace-nowrap">¥<?= htmlspecialchars($item['price']) ?></span>
                                        </div>
                                        <p class="text-gray-500 text-xs md:text-sm font-bold tracking-wide"><?= htmlspecialchars($item['name_en']) ?></p>

                                        <?php if (!empty($item['description'])): ?>
                                            <?php
                                            $desc_text = htmlspecialchars($item['description']);
                                            $desc_text = preg_replace('/(※.*?)(?=<|$)/', '<span class="text-red-600 font-bold">$1</span>', $desc_text);
                                            ?>
                                            <p class="text-gray-600 text-[10px] md:text-xs mt-3 md:mt-4 font-medium border-t border-dashed border-gray-200 pt-2 md:pt-3 flex-grow leading-relaxed">
                                                <?= $desc_text ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            <?php elseif ($is_drink_menu): ?>
                                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-3 md:p-4 border border-gray-200 flex flex-col justify-between h-full">
                                    <div>
                                        <h3 class="text-sm md:text-base font-bold text-gray-900 leading-tight"><?= htmlspecialchars($item['name_ja']) ?></h3>
                                        <p class="text-gray-500 text-[10px] md:text-xs tracking-wide mb-2"><?= htmlspecialchars($item['name_en']) ?></p>
                                        
                                        <?php if (!empty($item['description'])): ?>
                                            <p class="text-gray-400 text-[9px] md:text-[10px] mt-1 mb-2 italic">
                                                <?= htmlspecialchars($item['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex justify-between items-end mt-auto pt-2 border-t border-gray-100">
                                        <span class="text-xl md:text-2xl opacity-70">🍹</span>
                                        <span class="text-base md:text-lg font-black text-red-700 whitespace-nowrap">¥<?= htmlspecialchars($item['price']) ?></span>
                                    </div>
                                </div>

                            <?php else: ?>
                                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition flex overflow-hidden border border-gray-200">
                                    <div class="w-1/4 min-w-[90px] md:w-1/3 md:min-w-[120px] max-w-[150px] bg-orange-50 flex items-center justify-center p-2 md:p-3 border-r border-gray-100">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full border-2 md:border-4 border-[#b87333] shadow-inner overflow-hidden bg-white">
                                            <img src="<?= $photo_url ?>" alt="<?= htmlspecialchars($item['name_en']) ?>" class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <div class="w-3/4 md:w-2/3 p-3 md:p-4 flex flex-col justify-center">
                                        <div class="flex justify-between items-start mb-0.5 md:mb-1 gap-2">
                                            <h3 class="text-base md:text-xl font-bold text-gray-900 leading-tight"><?= htmlspecialchars($item['name_ja']) ?></h3>
                                            <span class="text-lg md:text-xl font-black text-red-700 whitespace-nowrap mt-[-2px]">¥<?= htmlspecialchars($item['price']) ?></span>
                                        </div>
                                        <p class="text-gray-500 text-[10px] md:text-sm font-medium tracking-wide mb-1 md:mb-2"><?= htmlspecialchars($item['name_en']) ?></p>

                                        <?php if (!empty($item['description'])): ?>
                                            <?php
                                            $desc_text = htmlspecialchars($item['description']);
                                            $desc_text = preg_replace('/(※.*?)(?=<|$)/', '<span class="text-red-600 font-bold">$1</span>', $desc_text);
                                            ?>
                                            <p class="text-gray-600 text-[10px] md:text-xs mt-auto pt-1.5 md:pt-2 border-t border-dashed border-gray-200 leading-relaxed">
                                                <?= $desc_text ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

        </div>

        <div class="max-w-3xl mx-auto mt-10 md:mt-16 bg-white rounded-lg shadow-md border-t-4 border-red-700 p-4 md:p-6 text-center border-b border-gray-200">
            <h4 class="text-base md:text-lg font-bold text-gray-800 mb-3 md:mb-4 border-b pb-2">カレーの辛さ / Spiciness</h4>
            <div class="flex flex-wrap justify-center gap-3 md:gap-8 text-xs md:text-sm font-bold">
                <div class="flex flex-col items-center"><span class="text-gray-400 text-lg md:text-xl">0</span><span class="text-gray-700">甘口</span><span class="text-gray-400 text-[10px] md:text-xs">Mild</span></div>
                <div class="flex flex-col items-center"><span class="text-green-500 text-lg md:text-xl">1</span><span class="text-green-600">普通</span><span class="text-green-500 text-[10px] md:text-xs">Normal</span></div>
                <div class="flex flex-col items-center"><span class="text-yellow-500 text-lg md:text-xl">2</span><span class="text-yellow-600">中辛</span><span class="text-yellow-500 text-[10px] md:text-xs">Medium</span></div>
                <div class="flex flex-col items-center"><span class="text-orange-500 text-lg md:text-xl">3</span><span class="text-orange-600">辛口</span><span class="text-orange-500 text-[10px] md:text-xs">Hot</span></div>
                <div class="flex flex-col items-center"><span class="text-red-600 text-lg md:text-xl">4</span><span class="text-red-700">激辛</span><span class="text-red-600 text-[10px] md:text-xs">Very Hot</span></div>
            </div>
            <p class="text-[10px] md:text-xs text-red-600 mt-3 md:mt-4 font-bold">※激辛以上の辛さも対応いたします</p>
        </div>

    </section>

    <section id="access" class="bg-gray-900 text-white py-12 md:py-16 border-t-8 border-red-700">
        <div class="container mx-auto px-4 grid md:grid-cols-2 gap-8 md:gap-12 items-center">
            <div>
                <h2 class="text-2xl md:text-3xl font-black mb-4 md:mb-6 border-l-4 border-red-600 pl-3 md:pl-4 tracking-wider">店舗情報</h2>
                <div class="space-y-3 md:space-y-4 text-base md:text-lg font-medium">
                    <p><span class="text-red-400 block text-[10px] md:text-xs tracking-widest uppercase mb-0.5 md:mb-1">Store Name</span> エベレストカレー (小倉店)</p>
                    <p><span class="text-red-400 block text-[10px] md:text-xs tracking-widest uppercase mb-0.5 md:mb-1">Address</span> 〒802-0016 <br>福岡県北九州市小倉北区宇佐町1丁目5-14</p>
                    <p><span class="text-red-400 block text-[10px] md:text-xs tracking-widest uppercase mb-0.5 md:mb-1">Phone</span> 000-0000-0000</p>
                    <p><span class="text-red-400 block text-[10px] md:text-xs tracking-widest uppercase mb-0.5 md:mb-1">Parking</span> お店専用駐車場 5台あり</p>
                </div>
            </div>
            <div class="h-56 sm:h-64 md:h-96 w-full rounded-lg overflow-hidden shadow-2xl border-2 md:border-4 border-gray-700 bg-gray-800">
                <iframe
                    width="100%" height="100%" frameborder="0" style="border:0"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3312.898656113576!2d130.884176!3d33.866504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3543b8cc78151cb1!2s1-chome-5-14%20Usamachi%2C%20Kokurakita%20Ward%2C%20Kitakyushu%2C%20Fukuoka%20802-0016!5e0!3m2!1sen!2sjp!4v1700000000000!5m2!1sen!2sjp"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <section id="about" class="py-12 md:py-16 bg-white container mx-auto px-4">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 tracking-wider">お問い合わせ</h2>
            <p class="text-gray-500 mt-1 md:mt-2 text-sm font-bold tracking-widest">Contact</p>
        </div>

        <div class="max-w-4xl mx-auto bg-[#FCFAF5] rounded-lg shadow-md p-5 md:p-8 text-center border border-orange-100 border-t-4 border-t-red-700">
            <h3 class="text-xl md:text-2xl font-bold text-red-700 mb-3 md:mb-4">本格インド・ネパール料理を皆様に</h3>
            <p class="text-sm md:text-base text-gray-700 mb-6 md:mb-8 font-medium leading-relaxed max-w-2xl mx-auto">
                スパイス香る本場のカレーや、焼きたてのふっくらナンをご用意して皆様をお待ちしております。お客様の声を大切にしておりますので、お気づきの点やご感想がございましたら、下記メールアドレスまでお気軽にご連絡ください。
            </p>

            <div class="grid md:grid-cols-2 gap-4 md:gap-6">
                <div class="bg-white p-4 md:p-6 rounded shadow-sm border border-gray-200 hover:shadow-md transition border-l-4 border-l-orange-500">
                    <h4 class="font-bold text-gray-800 mb-1 md:mb-2 text-base md:text-lg">🍽️ お料理に関するご意見</h4>
                    <p class="text-[10px] md:text-xs text-orange-500 mb-2 md:mb-3 font-bold tracking-widest uppercase">Food Feedback</p>
                    <a href="mailto:feedback@everestcurry.store" class="text-sm md:text-lg font-black text-red-700 hover:text-red-500 transition break-all">
                        feedback@everestcurry.store
                    </a>
                </div>

                <div class="bg-white p-4 md:p-6 rounded shadow-sm border border-gray-200 hover:shadow-md transition border-l-4 border-l-yellow-500">
                    <h4 class="font-bold text-gray-800 mb-1 md:mb-2 text-base md:text-lg">💻 サイトに関するお問い合わせ</h4>
                    <p class="text-[10px] md:text-xs text-yellow-600 mb-2 md:mb-3 font-bold tracking-widest uppercase">Website Support</p>
                    <a href="mailto:support@everestcurry.store" class="text-sm md:text-lg font-black text-red-700 hover:text-red-500 transition break-all">
                        support@everestcurry.store
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-black text-center py-6 md:py-8 text-gray-500 text-xs md:text-sm font-medium border-t border-gray-800">
        <div class="mb-3 md:mb-4">
            <img src="logo.jpg" alt="Everest Curry" class="h-10 md:h-12 w-auto mx-auto grayscale opacity-50">
        </div>
        © 2026 Everest Curry. All Rights Reserved.
    </footer>

</body>

</html>