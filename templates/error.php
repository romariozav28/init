

    <nav class="nav">
            <ul class="nav__list container">
                <?php foreach ($categorylist as $category): ?>
                <li class="nav__item">
                    <a href="all_lots.php ? id=<?= $category['id'] ?>"><?= $category['category_name'] ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <section class="lot-item container">
        <h2>Что то пошло не так</h2>
        <p><?= $error ?></p>
    </section>



