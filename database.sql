CREATE DATABASE IF NOT EXISTS atm_db;
USE atm_db;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM ('admin', 'user') NOT NULL,
    acc_number INT NOT NULL,
    pin INT NOT NULL
);

INSERT INTO users (role,acc_number, pin)
VALUES
    ('admin',1910,1234),
    ('user',1027,5678);

CREATE TABLE IF NOT EXISTS machine (
    machine_id INT AUTO_INCREMENT PRIMARY KEY,
    machine_balance INT NOT NULL
);

INSERT INTO machine (machine_balance)
VALUES (500000);

CREATE TABLE IF NOT EXISTS user_accounts (
    acc_number INT PRIMARY KEY,
    user_id INT,
    account_balance INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (acc_number) REFERENCES users(acc_number)
);

INSERT INTO user_accounts (user_id, account_balance)
VALUES 
    (2, 50000);

CREATE TABLE IF NOT EXISTS transactions (
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

        UPDATE machine
        SET machine_balance = machine_balance + NEW.amount;
    END IF;
END$$

DELIMITER ;

DELIMITER $$

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
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER admin_replenish_transaction
AFTER INSERT ON transactions FOR EACH ROW
BEGIN
    IF NEW.transaction_type = 'admin_replenish' THEN
        UPDATE machine
        SET machine_balance = machine_balance + NEW.amount;
    END IF;
END$$

DELIMITER ;
