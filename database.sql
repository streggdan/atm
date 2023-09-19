CREATE DATABASE atm_db;
USE atm_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM ('admin', 'user') NOT NULL,
    pin INT NOT NULL
);

INSERT INTO users (role, pin)
VALUES
    ('admin', 1234),
    ('user', 5678);

CREATE TABLE machine (
    machine_id INT AUTO_INCREMENT PRIMARY KEY,
    machine_balance INT NOT NULL
);

INSERT INTO machine (machine_balance)
VALUES (500000);

CREATE TABLE user_accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    account_balance INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

INSERT INTO user_accounts (user_id, account_balance)
VALUES 
    (2, 50000);

CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    transaction_type ENUM ('deposit', 'withdrawal', 'admin_replenish') NOT NULL,
    amount INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

DELIMITER $$


CREATE TRIGGER deposit_transaction
AFTER INSERT ON transactions FOR EACH ROW
BEGIN
    DECLARE account_balance INT;
    
    IF NEW.transaction_type = 'deposit' THEN
        SELECT account_balance INTO account_balance
        FROM user_accounts
        WHERE user_id = NEW.user_id;

        UPDATE user_accounts
        SET account_balance = account_balance + NEW.amount
        WHERE user_id = NEW.user_id;

        UPDATE machine
        SET machine_balance = machine_balance + NEW.amount;
    END IF;
END$$

CREATE TRIGGER deposit_transaction
AFTER INSERT ON transactions FOR EACH ROW
BEGIN
    DECLARE account_balance INT;
    
    IF NEW.transaction_type = 'deposit' THEN
        SELECT account_balance INTO account_balance
        FROM user_accounts
        WHERE user_id = NEW.user_id;

        UPDATE user_accounts
        SET account_balance = account_balance + NEW.amount
        WHERE user_id = NEW.user_id;

        UPDATE machine
        SET machine_balance = machine_balance + NEW.amount;
    END IF;
END;

CREATE TRIGGER withdrawal_transaction
AFTER INSERT ON transactions FOR EACH ROW
BEGIN
    DECLARE account_balance INT;
    
    IF NEW.transaction_type = 'withdrawal' THEN
        SELECT account_balance INTO account_balance
        FROM user_accounts
        WHERE user_id = NEW.user_id;

        IF account_balance >= NEW.amount THEN
            UPDATE user_accounts
            SET account_balance = account_balance - NEW.amount
            WHERE user_id = NEW.user_id;

            UPDATE machine
            SET machine_balance = machine_balance - NEW.amount;
        ELSE
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient balance for withdrawal';
        END IF;
    END IF;
END;

CREATE TRIGGER admin_replenish_transaction
AFTER INSERT ON transactions FOR EACH ROW
BEGIN
    IF NEW.transaction_type = 'admin_replenish' THEN
        UPDATE machine
        SET machine_balance = machine_balance + NEW.amount;
    END IF;
END;
