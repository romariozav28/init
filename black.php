<?php if(!isset($_POST['category_name']) or isset($errors['category_name'])): ?>
                    <option>Ведите наименование категории</option>
                <?php endif; ?>

                <?php if(isset($_POST['category_name'])) : ?>
                    <option value="<?= $_POST['category_name'] ?>"><?= $cats_names[$_POST['category_name']-1] ?></option>
                <?php endif; ?>


                pagination-item-active