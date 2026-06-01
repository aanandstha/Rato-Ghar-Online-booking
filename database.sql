CREATE DATABASE IF NOT EXISTS ratoghar_db;
USE ratoghar_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS menu_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    delivery_type ENUM('pickup', 'delivery') DEFAULT 'pickup',
    delivery_address TEXT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_item_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'successful', 'failed') DEFAULT 'pending',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@ratoghar.com', 'admin'); -- password is 'password'

INSERT INTO menu_categories (name, description) VALUES 
('Momo', 'Authentic Nepali Dumplings'),
('Dal Bhat', 'Traditional Nepali Meal Sets'),
('Appetizers', 'Snacks and Starters'),
('Beverages', 'Refreshing Drinks');

INSERT INTO menu_items (category_id, name, description, price, image_url) VALUES 
(1, 'Steamed Chicken Momo', 'Classic steamed chicken momo with spicy tomato achar.', 12.00, 'assets/img/chicken-momo.jpg'),
(1, 'Jhol Momo', 'Momo served in a tangy and spicy sesame-tomato broth.', 14.50, 'assets/img/jhol-momo.jpg'),
(1, 'Vegetable Momo', 'Steamed momo filled with fresh seasonal vegetables.', 10.00, 'assets/img/veg-momo.jpg'),
(1, 'Chilly Momo (C. Momo)', 'Spicy and tangy fried momo tossed in a special sauce.', 15.50, 'assets/img/chilly-momo.jpg'),
(2, 'Chicken Thakali Thali', 'Traditional Thakali meal with rice, chicken curry, lentils, and pickles.', 22.00, 'assets/img/thakali.jpg'),
(2, 'Veg Thakali Thali', 'Traditional Thakali meal with rice, seasonal veg curries, lentils, and pickles.', 18.00, 'assets/img/veg-thakali.jpg'),
(3, 'Sel Roti', 'Traditional sweet, ring-shaped rice bread/doughnut.', 5.00, 'assets/img/sel-roti.jpg'),
(3, 'Chatpate', 'Popular street food made of puffed rice, instant noodles, and spices.', 7.00, 'assets/img/chatpate.jpg'),
(4, 'Mango Lassi', 'Sweet and creamy mango yogurt drink.', 6.50, 'assets/img/mango-lassi.jpg');
