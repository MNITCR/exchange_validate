# exchange_validate

create database

-- Create the register table (main table)
CREATE TABLE register (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    phone_number VARCHAR(20),
    password VARCHAR(255),
    id_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create the main_bland_table table
CREATE TABLE main_bland_table (
    id_mb INT AUTO_INCREMENT PRIMARY KEY,
    main_bland VARCHAR(255),
    num_bland INT,
    register_id INT,
    FOREIGN KEY (register_id) REFERENCES register(id)
);

-- Create the topup table
CREATE TABLE topup (
    top_id INT AUTO_INCREMENT PRIMARY KEY,
    exchange_bland VARCHAR(255),
    data_use DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    register_id INT,
    FOREIGN KEY (register_id) REFERENCES register(id)
);

--Create the topup_transactions table
CREATE TABLE topup_transactions (
    id_ttr INT AUTO_INCREMENT PRIMARY KEY,
    register_id INT,
    transaction_date DATE,
    FOREIGN KEY (register_id) REFERENCES main_bland_table(id_mb)
);
