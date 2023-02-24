DROP DATABASE IF EXISTS yetycave;
CREATE DATABASE yetycave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yetycave;
CREATE TABLE category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255),
    category_symbol_code VARCHAR(60) unique
);


CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255),
    user_email VARCHAR(255) UNIQUE NOT NULL,
    user_password CHAR(255),
    user_contact TEXT,
    user_date_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lot (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lot_name VARCHAR(255),
    lot_date_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lot_description TEXT,
    lot_image VARCHAR(255),
    lot_price_start INT,
    lot_date_end DATE,
    lot_price_step INT,
    user_id INT,
    winner_id INT,
    category_id INT,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (winner_id) REFERENCES user(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE TABLE bet_lot (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bet_user_price INT,
    bet_date_of_placement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    lot_id INT,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (lot_id) REFERENCES lot(id)
);


SELECT lot_name, lot_description, MATCH (lot_name, lot_description) AGAINST('сноуборд') as наименование
FROM lot
WHERE MATCH(lot_name,lot_description) AGAINST('сноуборд')


