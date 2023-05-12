<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categorylist as $category): ?>
        <li class="nav__item">
          <a href="all-lots.html"><?= $category['category_name'] ?></a>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>


    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $request ?></span>»</h2>
        <ul class="lots__list">
          <?php foreach ($goodlist as $good): ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="../<?= $good['lot_image'] ?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?= $good['category_name'] ?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php"><?= $good['lot_name'] ?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost"><?= $good['lot_price_start'] ?><b class="rub">р</b></span>
                </div>
                  <?php $res = get_dt_range(htmlspecialchars($good["lot_date_end"])) ?>
                  <div class="lot-item__timer timer <?php if ($res[0] < 1): ?> timer--finishing <?php endif; ?>">
                  <?php if($res[0] < 0):?> Время истекло
                  <?php endif ?> 
                  <?= "$res[0]: $res[1]"; ?>
                </div>
              </div>
            </div>
          </li>  
          <?php endforeach; ?>        
        </ul>
      </section>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
      </ul>
    </div>
  </main>