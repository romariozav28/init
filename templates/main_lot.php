
<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categorylist as $category): ?>
        <li class="nav__item">
            <a href="all_lots.php ? id=<?= $category['id'] ?>"><?= $category['category_name'] ?> </a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
  
    <section class="lot-item container">
        <?php foreach ($goodlist as $good): ?>

            <h2> <?= $good['lot_name'] ?> </h2>
            <div class="lot-item__content">
                <div class="lot-item__left">
                    <div class="lot-item__image">
                        <img src=" <?= $good['lot_image'] ?>" width="730" height="548" alt="<?= $good['category_name'] ?>">
                    </div>
                    <p class="lot-item__category"> Категория: <span> <?= $good['category_name'] ?> </span> </p>
                    <p class="lot-item__description"> <?= $good['lot_description'] ?> </p>
                </div>
           
                <div class="lot-item__right">
                    
                    <div class="lot-item__state ">
                        <?php $res = get_dt_range_finish(htmlspecialchars($good["lot_date_end"])) ?>
                        <div class="lot-item__timer timer <?= $res[1] ?>">
                            <?= $res[0]; ?>
                        </div>

                        <div class="lot-item__cost-state">

                            <div class="lot-item__rate">

                            <?php $current_bet = !$start_bet ? $good['lot_price_start'] : $start_bet ?>
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?=format_price($current_bet)?></span>
                            </div>

                            <div class="lot-item__min-cost">
                                <?php $min_bet = $current_bet + $good['lot_price_step']?>
                                 Мин. ставка <span><?= format_price($min_bet) ?></span>
                            </div>
                        </div>
        
                        <?php $classname = ($class_error or !$is_auth) ? "form__item--invalid" : ""  ?>
                        <form class="lot-item__form" action="bet.php" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?= $classname?> ">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder=<?= $min_bet ?>>
                                <?php if(!$is_auth): ?>
                                <span class="form__error">Делать ставки могут только авторизованные пользователи</span>
                                <?php endif; ?>
                                <span class="form__error "><?= $class_error ?></span>
                            </p>
                            <?php if($is_auth): ?>
                            <button type="submit" class="button">Сделать ставку</button>
                            <?php endif; ?>
                            <input type="hidden" id="min_bet" name="min_bet" value="<?= $min_bet ?>">
                            <input type="hidden" id="lot_id" name="lot_id" value="<?= $good["id"] ?>">
                        </form>

                    </div>
        <?php endforeach; ?>
                    <div class="history">
                        
                        <?php $count_bet = $count_bet ? $count_bet : "Ставок по данному лоту пока нет" ?>
                        <h3>История ставок (<span><?= $count_bet ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($res_bet as $history_bet) : ?>
                            <tr class="history__item">
                                <td class="history__name"><?= $history_bet['user_name'] ?></td>
                                <td class="history__price"><?= $history_bet['price'] ?></td>
                                <?php $time_bet = get_dt_range_All($history_bet['bet_date']) ?>
                                <td class="history__time"><?= $time_bet ?></td>
                            </tr>
                            <?php endforeach; ?>
                            
                        </table>
                    </div>
                </div>
            </div>
        
    </section>