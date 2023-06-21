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
    <?php $classname=isset($errors) ? "form--invalid" : "" ; ?>
    <form class="form form--add-lot container <?= $classname; ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">

            <?php $classname=isset($errors['lot_name']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname ?> "> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot_name" placeholder="Введите наименование лота" value="<?= $lot['lot_name']; ?>">
                <span class="form__error"><?= $errors['lot_name'] ?></span>
            </div>

            <?php $classname=isset($errors['category_name']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category_name">

                    <?php if(!$lot['category_name'] or $errors['category_name']): ?>
                        <option value="">Введите наименование категории</option>
                    <?php endif; ?>
                    <?php if($lot['category_name'] and !$errors['category_name']): ?>
                        <option value="<?= $lot['category_name'] ?>"><?= $cats_names[$lot['category_name']-1] ?></option>
                    <?php endif; ?>
                        <?php foreach ($categorylist as $category): ?>
                        <option value="<?= $category['id'] ?>" ><?= $category['category_name']?></option>
                        <?php endforeach; ?>

                </select>
                <span class="form__error"><?= $errors['category_name']; ?></span>
            </div>
        </div>

        <?php $classname=isset($errors['lot_description']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--wide <?= $classname ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="lot_description" placeholder="Напишите описание лота"><?= $lot['lot_description'] ?></textarea>
            <span class="form__error"><?= $errors['lot_description'] ?></span>
        </div>

        <?php $classname=isset($errors['lot_img']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--file <?= $classname ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot_img" value="" name="lot_img">
                <label for="lot_img">
                    Добавить
                </label>
                <span class="form__error"><?= $errors["lot_img"] ?></span>
            </div>
        </div>
        
        <div class="form__container-three">
        <?php $classname=isset($errors['lot_price_start']) ? "form__item--invalid" : ""; ?>
            <div class="form__item form__item--small <?= $classname ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot_price_start" placeholder="0" value="<?= $lot['lot_price_start'] ?>">
                <span class="form__error"><?= $errors['lot_price_start'] ?></span>
            </div>

            <?php $classname=isset($errors['lot_price_step']) ? "form__item--invalid" : ""; ?>
            <div class="form__item form__item--small <?= $classname ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot_price_step" placeholder="0" value="<?= $lot['lot_price_step'] ?>">
                <span class="form__error"><?= $errors['lot_price_step'] ?></span>
            </div>

            <?php $classname=isset($errors['lot_date_end']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname ?>">
                <label for="lot_date_end">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot_date_end" type="text" name="lot_date_end" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $lot['lot_date_end']?>">
                <span class="form__error"><?= $errors['lot_date_end'] ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Отправить лот</button>
    </form>
</main>

