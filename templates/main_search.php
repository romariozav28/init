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
        <h2>Результаты поиска по запросу «<span>Union</span>»</h2>
        <ul class="lots__list">
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="../img/lot-1.jpg" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category">Доски и лыжи</span>
              <h3 class="lot__title"><a class="text-link" href="lot.html">2014 Rossignol District Snowboard</a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost">10 999<b class="rub">р</b></span>
                </div>
                <div class="lot__timer timer">
                  16:54:12
                </div>
              </div>
            </div>
          </li>          
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