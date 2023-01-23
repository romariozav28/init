USE yetycave;
INSERT INTO category (category_name, category_symbol_code) VALUES 
('Доски и лыжи', 'boards'),
('Крепления', 'bracing'),
('Ботинки', 'boots'),
('Одежда', 'clothes'),
('Инструменты', 'tools'),
('Разное', 'other');

INSERT INTO user (user_name, user_email, user_password, user_contact) VALUES 
('Вася Петров', 'petr@mail.ru', '123', '+79821564578'),
('Вася Обломов', 'oblom@mail.ru', 'qawe', '+9547777777'),
('Петр Кошкин', 'koshka@yandex.ru', 'dgf', '+7521578585'),
('Дмитрий Песков', 'pesok@gmail.com', 'bcn', '+76666666666'),
('Владимир Путин', 'put1951@mail.ru', '15641', '+71313131313'),
('Михаил Силуанов', 'sila@gmail.com', 'kijbdvfy364t', '+77777777777'),
('Герман Греф', 'gref@yandex.ru', 'kjav74', '+75555555555'),
('Анатолий Чубайс', 'chuba@mail.ru', '564kafj54', '+79815844545'),
('Максим Галкин', 'galkin123@mail.ru', 'sf', '+79635435454'),
('Алла Пугачева', 'puga_alla@gmail.com', '789654', '+71111111111');

INSERT INTO lot (lot_name, lot_description, lot_image, lot_price_start, lot_date_end, lot_price_step, user_id, category_id) VALUES 
('2014 Rossignol District Snowboard', 'Очень быстрые, легкие, с ними почувствуешь свободу полета!!', 'img\lot-1.jpg', 10999, '2023-01-25', 100, 5, 1),
('DC Ply Mens 2016/2017 Snowboard', 'Очень быстрые, легкие, единственные!!', 'img\lot-2.jpg', 159999, '2023-01-26', 1000, 6, 1),
('Крепления Union Contact Pro 2015 года размер L/XL', 'Очень крепкие, легкие, удобные!!', 'img\lot-3.jpg', 8000, '2023-01-27', 500, 7, 2),
('Ботинки для сноуборда DC Mutiny Charocal', 'Очень быстрые, легкие, с ними почувствуешь свободу полета!!', 'img\lot-4.jpg', 10999, '2023-01-28', 500, 9, 3),
('Куртка для сноуборда DC Mutiny Charocal', 'Очень теплая, комфортная и износостойкая!!', 'img\lot-5.jpg', 7500, '2023-01-29', 100, 2, 4),
('Маска Oakley Canopy', 'Забудь про слезы и ветер, а также блеск от снега!!', 'img\lot-6.jpg', 5400, '2023-01-30', 300, 9, 6);

INSERT INTO bet_lot (bet_user_price, user_id, lot_id) VALUES 
(11199, 5, 1),
(161999, 6, 2),
(9000, 7, 3),
(11499, 9, 4),
(7600, 2, 5),
(6000, 9, 6);

INSERT INTO lot (lot_name, lot_description, lot_image, lot_price_start, lot_date_end, lot_price_step, user_id, category_id) VALUES 
('Joint STASH POWER 2021', 'Просто надежно и очень красиво!!', 'img\lot-7.jpg', 15999, '2023-01-19', 500, 5, 1)

SELECT * AS From user;
SELECT category_name AS "Категория" From category;
SELECT * From lot;
SELECT * From bet_lot;

SELECT lot.lot_name AS "Название", lot.lot_price_start AS "Стартовая цена", lot.lot_image AS "Ссылка на изображение", category.category_name AS "Категория" FROM lot JOIN category ON lot.category_id = category.id;

UPDATE lot SET lot_name='Ботинки супер простые' WHERE id=4;

INSERT INTO bet_lot (bet_user_price, user_id, lot_id) VALUES 
(11199, 5, 3),
(161999, 6, 4),
(9000, 7, 5),
(11499, 9, 6),
(7600, 2, 2),
(6000, 9, 1);

SELECT bet_lot.bet_user_price AS "Ставка", user.user_name AS "Пользователь", lot.lot_name AS "Название лота", bet_lot.bet_date_of_placement AS "Дата ставки" FROM bet_lot
JOIN user ON bet_lot.user_id = user.id 
JOIN lot ON bet_lot.lot_id = lot.id
WHERE lot.id=3
ORDER BY bet_lot.bet_date_of_placement DESC; 