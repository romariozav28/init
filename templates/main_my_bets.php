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
    
    <section class="rates container">
      <h2>Мои ставки</h2>
      
      <table class="rates__list">
      <?php foreach ($goodlist as $good): ?>
        <?php $result = get_dt_range_finish(htmlspecialchars($good["lot_date_end"])) ?>
        <tr class="rates__item <?= $result[2] ?>">
          
          <td class="rates__info">

            <div class="rates__img">
              <img src="<?= $good['lot_image'] ?>" width="54" height="40" alt="Сноуборд">
            </div>

            <div>
            <h3 class="rates__title"><a href="lot.php ? id=<?= $good['id'] ?>"><?= $good['lot_name'] ?></a></h3>
            <p><?= $good['bet_contact'] ?></p>
            </div>

          </td>

          <td class="rates__category">
            <?= $good['category_name'] ?>
          </td>

          <td class="rates__timer">
            <div>
              
                    <div class="timer <?= $result[1] ?> "> 
                        <?= $result[0] ?>
                    </div>
            </div>
          </td>

          <td class="rates__price">
          <?=format_price(htmlspecialchars($good["bet_user_price"]))?>
          </td>

          <?php $res_bet = get_dt_range_All(htmlspecialchars($good["bet_date_of_placement"])); ?>
          <td class="rates__time">
              <?= $res_bet ?>
          </td>
          
        </tr>
        <?php endforeach; ?>
      </table>
    </section>
  </main>