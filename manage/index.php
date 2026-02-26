<?php
session_start();

// --- ADMIN CREDENTIALS ---
$admin_user = 'everest_admin';
$admin_pass_hash = '$2y$10$yEXZGFaf4Vx7tKU8iUs3yeVSl1SJLnkKbL/u0ngRzlv5BH.HsdHgW'; 

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];

    if ($input_user === $admin_user && password_verify($input_pass, $admin_pass_hash)) {
        $_SESSION['logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Invalid username or password!";
    }
}

// Check if logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Connect to Database ONLY if logged in
if ($is_logged_in) {
    require_once __DIR__ . '/../db_connect.php';
}

// Helper: Get clean image filename
function get_image_filename($name_en) {
    $name = preg_replace('/\(.*?\)/', '', $name_en);
    $filename = strtolower(trim($name));
    $filename = preg_replace('/[^a-z0-9]+/', '-', $filename);
    $filename = trim($filename, '-');
    return $filename . '.jpg';
}

$action = $_GET['action'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Everest Curry | Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Noto Sans JP', sans-serif; }</style>
</head>
<body class="bg-gray-100 text-gray-800">

<?php if (!$is_logged_in): ?>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <div class="bg-white p-8 rounded-lg shadow-xl w-96">
            <h1 class="text-2xl font-black text-red-700 text-center mb-6">Admin Login</h1>
            <?php if (isset($login_error)): ?>
                <p class="text-red-500 text-sm mb-4 text-center font-bold"><?= $login_error ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-red-500" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-red-500" required>
                </div>
                <button type="submit" name="login" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded transition">Login</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <nav class="bg-black text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-black tracking-widest text-red-500">EVEREST CURRY <span class="text-white text-sm font-normal ml-2 tracking-normal">Admin Portal</span></h1>
        <a href="?action=logout" class="bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded text-sm font-bold transition">Logout</a>
    </nav>

    <div class="container mx-auto px-4 py-8">

        <?php 
        // =========================================================================
        // VIEW: DASHBOARD (LIST ITEMS)
        // =========================================================================
        if ($action == 'dashboard'): 
            // UPDATED QUERY: Now joins the categories table so we can see what category the item belongs to
            $stmt = $pdo->query("
                SELECT m.*, c.name_ja AS category_name, c.name_en AS category_en 
                FROM menu_items m 
                JOIN categories c ON m.category_id = c.id 
                ORDER BY c.sort_order, m.id
            ");
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-2xl font-bold">Menu Manager</h2>
                <div class="relative w-full md:w-1/3">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search item or category..." class="w-full pl-10 pr-4 py-2 border rounded shadow-sm focus:outline-none focus:border-red-500 transition">
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>Menu item updated successfully.</p>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Photo</th>
                            <th class="px-4 py-3 text-left w-1/2">Item Details</th>
                            <th class="px-4 py-3 text-left">Price</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="menuTableBody" class="divide-y divide-gray-200">
                        <?php foreach ($items as $item): 
                            $photo_file = get_image_filename($item['name_en']);
                            $photo_path = '../images/' . $photo_file;
                            $has_photo = file_exists($photo_path);
                        ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <?php if($has_photo): ?>
                                        <img src="<?= $photo_path ?>?t=<?= time() ?>" class="w-12 h-12 object-cover rounded shadow-sm border">
                                    <?php else: ?>
                                        <span class="inline-block w-12 h-12 bg-gray-200 text-gray-400 text-[10px] flex items-center justify-center rounded border text-center leading-tight">No img</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1">
                                        <?= htmlspecialchars($item['category_name']) ?>
                                    </span>
                                    <br>
                                    <span class="font-bold text-base"><?= htmlspecialchars($item['name_ja']) ?></span><br>
                                    <span class="text-xs text-gray-500"><?= htmlspecialchars($item['name_en']) ?></span>
                                </td>
                                <td class="px-4 py-3 text-red-600 font-bold text-lg">¥<?= htmlspecialchars($item['price']) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <a href="?action=edit&id=<?= $item['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded text-xs font-bold transition shadow-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <script>
                document.getElementById('searchInput').addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase();
                    let rows = document.querySelectorAll('#menuTableBody tr');

                    rows.forEach(row => {
                        // Get all the text inside the row (Japanese name, English name, Category name)
                        let text = row.innerText.toLowerCase();
                        if (text.includes(filter)) {
                            row.style.display = ''; // Show row
                        } else {
                            row.style.display = 'none'; // Hide row
                        }
                    });
                });
            </script>

        <?php 
        // =========================================================================
        // VIEW: EDIT FORM
        // =========================================================================
        elseif ($action == 'edit' && isset($_GET['id'])): 
            $stmt = $pdo->prepare("SELECT m.*, c.name_ja as category_name FROM menu_items m JOIN categories c ON m.category_id = c.id WHERE m.id = ?");
            $stmt->execute([$_GET['id']]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $photo_file = get_image_filename($item['name_en']);
            $current_photo = file_exists('../images/' . $photo_file) ? '../images/' . $photo_file : 'https://placehold.co/600x400/eeeeee/a3a3a3?text=No+Photo';
        ?>
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <div>
                        <span class="text-xs font-bold text-red-500 uppercase tracking-widest block mb-1"><?= htmlspecialchars($item['category_name']) ?></span>
                        <h2 class="text-2xl font-bold">Edit: <span class="text-gray-900"><?= htmlspecialchars($item['name_ja']) ?></span></h2>
                    </div>
                    <a href="index.php" class="text-gray-500 hover:text-gray-800 text-sm font-bold">Cancel</a>
                </div>

                <form method="POST" action="?action=preview" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Price (¥)</label>
                        <input type="number" name="price" value="<?= htmlspecialchars($item['price']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded focus:border-red-500 focus:outline-none" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Description (Japanese / English)</label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded focus:border-red-500 focus:outline-none"><?= htmlspecialchars($item['description']) ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Leave blank if no description is needed.</p>
                    </div>

                    <div class="mb-8 p-4 bg-gray-50 rounded border">
                        <label class="block text-gray-700 font-bold mb-2">Menu Photo</label>
                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            <img src="<?= $current_photo ?>?t=<?= time() ?>" class="w-24 h-24 object-cover rounded shadow border bg-white">
                            <div class="w-full">
                                <input type="file" name="new_photo" accept="image/jpeg, image/png, image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                                <p class="text-xs text-gray-400 mt-2">Uploading a new photo will instantly replace the old one for this item.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 px-4 rounded shadow transition text-lg">Preview Changes</button>
                </form>
            </div>

        <?php 
        // =========================================================================
        // VIEW: PREVIEW & CONFIRMATION
        // =========================================================================
        elseif ($action == 'preview' && $_SERVER['REQUEST_METHOD'] == 'POST'): 
            $id = $_POST['id'];
            $new_price = $_POST['price'];
            $new_desc = $_POST['description'];

            // Fetch old data for comparison
            $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
            $stmt->execute([$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            // Handle temporary image upload for preview
            $temp_image_name = '';
            if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] == 0) {
                $temp_image_name = 'temp_' . $id . '.jpg';
                move_uploaded_file($_FILES['new_photo']['tmp_name'], '../images/' . $temp_image_name);
            }

            // Determine what image to show in the "New" column
            $real_photo_file = get_image_filename($item['name_en']);
            $old_photo_path = file_exists('../images/' . $real_photo_file) ? '../images/' . $real_photo_file : 'https://placehold.co/600x400/eeeeee/a3a3a3?text=No+Photo';
            $new_photo_path = $temp_image_name ? '../images/' . $temp_image_name : $old_photo_path;
        ?>
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-black text-center mb-8">Confirm Changes</h2>
                
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div class="bg-gray-100 p-6 rounded-lg border-2 border-gray-300 opacity-70">
                        <h3 class="font-bold text-gray-500 mb-4 text-center uppercase tracking-widest border-b pb-2">Current Version</h3>
                        <img src="<?= $old_photo_path ?>?t=<?= time() ?>" class="w-full h-48 object-cover rounded mb-4 grayscale">
                        <p class="mb-2"><span class="font-bold text-gray-500">Price:</span> ¥<?= htmlspecialchars($item['price']) ?></p>
                        <p><span class="font-bold text-gray-500 block mb-1">Description:</span> <span class="text-sm bg-white p-2 block rounded border min-h-[60px] whitespace-pre-wrap"><?= htmlspecialchars($item['description']) ?></span></p>
                    </div>

                    <div class="bg-white p-6 rounded-lg border-4 border-green-500 shadow-xl">
                        <h3 class="font-black text-green-600 mb-4 text-center uppercase tracking-widest border-b pb-2">New Version</h3>
                        <img src="<?= $new_photo_path ?>?t=<?= time() ?>" class="w-full h-48 object-cover rounded mb-4 shadow">
                        <p class="mb-2"><span class="font-bold text-gray-700">Price:</span> <span class="text-xl font-bold text-red-600">¥<?= htmlspecialchars($new_price) ?></span></p>
                        <p><span class="font-bold text-gray-700 block mb-1">Description:</span> <span class="text-sm bg-green-50 p-2 block rounded border border-green-200 min-h-[60px] font-medium whitespace-pre-wrap"><?= htmlspecialchars($new_desc) ?></span></p>
                    </div>
                </div>

                <div class="flex gap-4 justify-center">
                    <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded transition">Cancel</a>
                    
                    <form method="POST" action="?action=update">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="price" value="<?= htmlspecialchars($new_price) ?>">
                        <input type="hidden" name="description" value="<?= htmlspecialchars($new_desc) ?>">
                        <input type="hidden" name="temp_image" value="<?= $temp_image_name ?>">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-black py-3 px-8 rounded shadow-lg transition text-lg">Confirm & Update</button>
                    </form>
                </div>
            </div>

        <?php 
        // =========================================================================
        // LOGIC: FINAL UPDATE
        // =========================================================================
        elseif ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST'): 
            $id = $_POST['id'];
            
            // 1. Update Database
            $stmt = $pdo->prepare("UPDATE menu_items SET price = ?, description = ? WHERE id = ?");
            $stmt->execute([$_POST['price'], $_POST['description'], $id]);

            // 2. Handle Image finalization
            if (!empty($_POST['temp_image'])) {
                $stmt = $pdo->prepare("SELECT name_en FROM menu_items WHERE id = ?");
                $stmt->execute([$id]);
                $item_name = $stmt->fetchColumn();

                $real_filename = get_image_filename($item_name);
                
                // Rename the temp file to the permanent file (overwriting the old one)
                rename('../images/' . $_POST['temp_image'], '../images/' . $real_filename);
            }

            // Redirect back to dashboard with success message
            header("Location: index.php?success=1");
            exit;
            
        endif; 
        ?>

    </div>
<?php endif; ?>

</body>
</html>