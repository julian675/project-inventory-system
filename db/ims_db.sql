CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(100) NOT NULL,
    lname VARCHAR(100) NOT NULL,
    uname VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'viewer') NOT NULL
);

CREATE TABLE stock (
  stock_id INT AUTO_INCREMENT PRIMARY KEY,
  product_name VARCHAR(100) NOT NULL,
  quantity INT NOT NULL,
  date_added DATE NOT NULL,
  status VARCHAR(50),
  add_stock INT DEFAULT 0
  -- Removed total_quantity (calculate in queries if needed)
);

CREATE TABLE orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  stock_id INT,
  product_name VARCHAR(100), -- Optional redundancy
  date_transferred DATE NOT NULL,
  quantity_transferred INT NOT NULL,
  remaining_quantity INT NOT NULL,
  status VARCHAR(20),
  FOREIGN KEY (stock_id) REFERENCES stock(stock_id)
);

INSERT INTO stock (product_name, quantity, date_added, status, add_stock)
VALUES 
('Shrimps', 50, '2024-12-01', 'Pending', 0),
('Clams', 100, '2025-01-15', 'Pending', 0),
('Mussels', 60, '2025-02-01', 'Pending', 0),
('Lobster', 80, '2025-02-10', 'Pending', 0),
('Crab', 120, '2025-03-01', 'Pending', 0),
('Squid', 150, '2025-03-15', 'In Stock', 0),
('Crawfish', 200, '2025-03-20', 'In Stock', 0),
('Octopus', 90, '2025-03-22', 'In Stock', 0),
('Scallops', 70, '2025-03-25', 'Restock Needed', 0),
('Crayfish', 300, '2025-03-30', 'In Stock', 0);
