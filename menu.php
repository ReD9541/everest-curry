<?php
// Securely require the database connection
require_once __DIR__ . '/db_connect.php';

// Fetch the categories and menu items
$query = "
    SELECT 
        c.name_en AS cat_en, c.name_ja AS cat_ja, 
        m.name_en, m.name_ja, m.price, m.description 
    FROM categories c 
    JOIN menu_items m ON c.id = m.category_id 
    ORDER BY c.sort_order, m.id
";
$stmt = $pdo->query($query);

// Group items by category for easy display
$menu_data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Combine Japanese and English category names as the key
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
    <title>Everest Curry - Grand Menu</title>
    <link rel="icon" type="image/jpeg" href="logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Noto Sans JP', sans-serif; 
        }
        .menu-bg {
            background-color: #FCFAF5;
            background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
            background-repeat: repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-black text-white p-3 md:p-4 sticky top-0 z-50 shadow-xl border-b-4 border-red-700 flex justify-between items-center px-4">
        <a href="index.php" class="text-sm font-bold text-gray-300 hover:text-white transition">← ホームに戻る (Home)</a>
        <div class="text-xl md:text-2xl font-black text-white tracking-widest text-center">
            エベレスト<span class="text-red-600">カレー</span>
        </div>
        <div class="w-[100px]"></div> </nav>

    <section class="py-12 md:py-16 menu-bg shadow-inner min-h-screen">
        <div class="container mx-auto px-3 md:px-4">
            
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-4xl md:text-5xl font-black text-red-700 tracking-wider drop-shadow-sm">GRAND MENU</h1>
                <p class="text-gray-600 mt-2 text-sm md:text-base font-bold tracking-widest">グランドメニュー</p>
            </div>

            <div class="max-w-6xl mx-auto">
                <?php if (empty($menu_data)): ?>
                    <p class="text-center text-red-500 font-bold bg-white p-6 rounded shadow">メニューがありません。 (No menu items found.)</p>
                <?php else: ?>

                    <?php foreach ($menu_data as $category_combined => $items): ?>
                        <?php
                        list($cat_ja, $cat_en) = explode('|', $category_combined);
                        $is_set_menu = (stripos($cat_en, 'set') !== false);
                        $is_drink_menu = (stripos($cat_en, 'drink') !== false); 
                        
                        $grid_classes = 'grid-cols-1 lg:grid-cols-2 gap-3 md:gap-6'; 
                        if ($is_set_menu) $grid_classes = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8';
                        if ($is_drink_menu) $grid_classes = 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4'; 
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

        </div>
    </section>

    <footer class="bg-black text-center py-6 md:py-8 text-gray-500 text-xs md:text-sm font-medium border-t border-gray-800">
        © 2026 Everest Curry. All Rights Reserved.
    </footer>

</body>
</html>