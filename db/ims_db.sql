CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(100) NOT NULL,
    lname VARCHAR(100) NOT NULL,
    uname VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager') NOT NULL
);

CREATE TABLE instock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    items INT NOT NULL,
    status ENUM('good', 'critical', 'warning') NOT NULL
);

CREATE TABLE order_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_address VARCHAR(500) NOT NULL,
    contact_number VARCHAR(50) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    order_timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES order_details(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES instock(id) ON DELETE CASCADE
);

-- Sample data for instock
INSERT INTO instock (product, price, items, status) VALUES
('Product A', 10.00, 100, 'good'),
('Product B', 15.50, 50, 'warning'),
('Product C', 7.25, 200, 'good');

-- Sample data for order_details
INSERT INTO order_details (client_name, client_address, contact_number, company_name) VALUES
('John Doe', '123 Main St', '+1234567890', 'Example Corp');

-- Sample data for order_items (assuming the order_id is 1 and product_id is 1 and 2)
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 2, 10.00),  -- 2 units of Product A
(1, 2, 1, 15.50);  -- 1 unit of Product B