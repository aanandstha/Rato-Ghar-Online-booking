<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Fetch categories
$stmt = $pdo->query("SELECT * FROM menu_categories");
$categories = $stmt->fetchAll();

// Fetch all available menu items
$stmt = $pdo->query("SELECT * FROM menu_items WHERE is_available = 1");
$menu_items = $stmt->fetchAll();
?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1>Taste the Tradition</h1>
        <p>Authentic Nepali cuisine delivered hot and fresh to your doorstep.</p>
        <a href="#menu" class="btn btn-primary btn-lg mt-3">Order Now</a>
    </div>
</div>

<div class="container" id="menu">
    <div class="text-center mb-5">
        <h2 class="display-4" style="color: var(--rato-dark);">Our Menu</h2>
        <p class="lead text-muted">Discover the rich flavors of Nepal</p>
    </div>

    <?php foreach($categories as $category): ?>
        <h3 class="mb-4 text-uppercase border-bottom pb-2"><?= htmlspecialchars($category->name) ?></h3>
        <p class="text-muted mb-4"><?= htmlspecialchars($category->description) ?></p>
        
        <div class="row mb-5">
            <?php foreach($menu_items as $item): ?>
                <?php if($item->category_id == $category->id): ?>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100">
                            <!-- Placeholder image fallback -->
                            <img src="<?= htmlspecialchars($item->image_url ?: 'https://via.placeholder.com/300x200.png?text=Rato+Ghar') ?>" class="card-img-top" alt="<?= htmlspecialchars($item->name) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($item->name) ?></h5>
                                <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($item->description) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="price-tag">$<?= number_format($item->price, 2) ?></span>
                                    <form action="cart_action.php" method="POST">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="item_id" value="<?= $item->id ?>">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
