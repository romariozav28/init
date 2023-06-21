<main>
<nav class="nav">
      <ul class="nav__list container">
        <?php foreach($categorylist as $category): ?>
            <li class="nav__item">
                <a href="all_lots.php ? id=<?= $category['id'] ?>"><?= $category['category_name'] ?></a>
            </li>
        <?php endforeach; ?> 
      </ul>
    </nav>

    <div class="container">
      <section class="lots">
        
        <h2>Все лоты в категории <span><?= $category_id ?> (Всего <?= $all_count_lot ?>)</span></h2>
        
        <ul class="lots__list">
            <?php foreach($goodlist as $good): ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?= $good['lot_image'] ?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?= $good['category_name'] ?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php ? id=<?= $good['id'] ?>"><?= $good['lot_name'] ?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  
                  <span class="lot__cost"><?=format_price(htmlspecialchars($good["lot_price_start"]))?><b class="rub">р</b></span>
                </div>
                
                <?php $res = get_dt_range(htmlspecialchars($good["lot_date_end"])) ?>
                    <div class="lot__timer timer <?= $res[1] ?>"> 
                        <?= "$res[0]" ?>
                </div>
              
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </section>
      <ul class="pagination-list">
      <?php $back_pagination = (($pages-1)<1) ? '<a>Назад</a>' : '<a href="all_lots.php ? id=' . $id_lot . '& page=' . $pages-1 . '">Назад</a>' ?>
        <li class="pagination-item pagination-item-prev"><?= $back_pagination ?></li>

        <?php for ($i=1; $i<=$count_page; $i++) : ?>
          <?php $classname_active = ($i == $pages) ? "pagination-item-active" : "" ?>
          <?php $link_page = ($i == $pages) ? '<a>' . $i .  '</a>' :  '<a href="all_lots.php ? id=' . $id_lot . '& page=' . $i . '">' . $i . '</a>' ?>
        <li class="pagination-item <?= $classname_active ?>"><?= $link_page ?></li>
        <?php endfor; ?>
        
      <?php $next_pagination = (($pages+1)>$count_page) ? '<a>Вперед</a>' : '<a href="all_lots.php ? id=' . $id_lot . '& page=' . $pages+1 . '">Вперед</a>' ?>  
        <li class="pagination-item pagination-item-next"><?= $next_pagination ?></li>
      </ul>
    </div>
  </main>