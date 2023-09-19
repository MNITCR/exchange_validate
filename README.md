# exchange_validate
-- Create the register table (main table)
CREATE TABLE register (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    phone_number varchar(50),
    password varchar(50),
    id_number varchar(50),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    last_login_date DATE NULL DEFAULT NULL
);

-- Create the main_bland_table table
CREATE TABLE main_bland_table (
    id_mb INT AUTO_INCREMENT PRIMARY KEY,
    id_number VARCHAR(50) NOT NULL,
    main_bland float(50),
    topup_activity FLOAT(50) NOT NULL,
    num_bland float(50),
    sv_by VARCHAR(50) NOT NULL,
    register_id INT,
    topup_date DATE NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (register_id) REFERENCES register(id)
);

-- Create the exchange table
CREATE TABLE exchange (
    top_id INT AUTO_INCREMENT PRIMARY KEY,
    exchange_bland float(50),
    data_use float(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    register_id INT,
    FOREIGN KEY (register_id) REFERENCES register(id)
);

-- Create table history exchange
CREATE TABLE history_exchange (
    id_hs_ex INT AUTO_INCREMENT PRIMARY KEY,
    transactions float(50) NOT NULL,
    exchange_plan float(50) NOT NULL,
    exchange_data float(50) NOT NULL,
    date TIMESTAMP NULL DEFAULT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES register(id) ON DELETE CASCADE
);


-- Create table history topup
CREATE TABLE history_topup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    card_number float(50) NOT NULL,
    service_by VARCHAR(255) NOT NULL,
    transaction_date TIMESTAMP NULL DEFAULT NULL,
    register_id INT,
    FOREIGN KEY (register_id) REFERENCES register(id)
);
