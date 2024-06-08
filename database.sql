CREATE DATABASE supermarket CHARACTER SET utf8 COLLATE utf8_general_ci;
USE supermarket;

CREATE TABLE Staff (
    staff_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birthDate DATE NOT NULL,
    contact_info VARCHAR(255) NOT NULL,
    homeTown VARCHAR(255) NOT NULL
);

CREATE TABLE Customer (
    customer_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birthDate DATE NOT NULL,
    last_payment DATE DEFAULT NULL,
    allergy VARCHAR(255)
);

CREATE TABLE Product (
    product_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(6,2) NOT NULL,
    description VARCHAR(255),
    allergy VARCHAR(255),
    age_restriction INTEGER
);

CREATE TABLE Payment (
    payment_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    staff_id INTEGER NOT NULL,
    customer_id INTEGER NOT NULL,
    amount DECIMAL(6,2) NOT NULL,
    payment_date DATE DEFAULT CURDATE(),
    store_address VARCHAR(50) DEFAULT 'KNM, Revolucna 1344',
    FOREIGN KEY (staff_id) REFERENCES Staff(staff_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
);

CREATE TABLE Payment_Details (
    paymentDetail_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    payment_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER,
    discount_percentage INTEGER DEFAULT 0, 
    FOREIGN KEY (payment_id) REFERENCES Payment(payment_id),
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

INSERT INTO Staff (first_name, last_name, birthDate, contact_info, homeTown)
VALUES 
('Emily', 'Brown', '1992-03-10', 'emily.brown@example.com', 'Chicago'),
('Michael', 'Johnson', '1987-06-25', 'michael.johnson@example.com', 'Los Angeles'),
('Sophia', 'Miller', '1990-09-18', 'sophia.miller@example.com', 'New York'),
('William', 'Garcia', '1985-12-05', 'william.garcia@example.com', 'Miami'),
('Olivia', 'Martinez', '1994-02-20', 'olivia.martinez@example.com', 'Houston');

INSERT INTO Customer (first_name, last_name, birthDate, allergy)
VALUES
('Noah', 'Wilson', '1978-04-15', NULL),
('Ava', 'Lopez', '1989-07-20', NULL),
('Liam', 'Perez', '1983-11-05', 'Shellfish'),
('Emma', 'Gonzalez', '1996-01-30', NULL),
('James', 'Rodriguez', '1980-08-10', 'Gluten'),
('Isabella', 'Hernandez', '1991-02-25', 'Peanuts'),
('Logan', 'Torres', '1986-05-07', NULL),
('Mia', 'Flores', '1993-10-12', NULL),
('Lucas', 'Gomez', '1984-03-17', 'Lactose'),
('Charlotte', 'Diaz', '1997-06-22', NULL),
('Mason', 'Rivera', '1981-09-27', NULL),
('Amelia', 'Smith', '1990-01-01', 'Nuts'),
('Ethan', 'Ramirez', '1988-04-05', NULL),
('Harper', 'Campbell', '1985-07-15', NULL),
('Alexander', 'Mitchell', '1992-11-20', 'Soy'),
('Evelyn', 'Roberts', '1979-12-25', NULL),
('Benjamin', 'Cook', '1984-03-01', 'Eggs'),
('William', 'Bailey', '1995-06-06',  'Shellfish'),
('Victoria', 'Kelly', '1982-09-11', NULL),
('Liam', 'Howard', '1993-12-16', 'Soy');

INSERT INTO Product (name, price, description, allergy, age_restriction)
VALUES 
('Organic Red Apples', 2.99, 'Fresh organic red apples, perfect for snacking or baking', NULL, NULL),
('Whole Wheat Bread', 4.49, 'Healthy whole wheat bread, rich in fiber and nutrients', 'Gluten', NULL),
('Farm-Fresh Eggs', 5.49, 'Farm-fresh eggs, great for breakfast or baking', NULL, NULL),
('Atlantic Salmon Fillet', 3.49, 'Premium quality Atlantic salmon fillet, rich in omega-3 fatty acids', NULL, 18),
('Plain Yogurt', 1.79, 'Creamy plain yogurt, perfect for smoothies and desserts', 'Lactose', NULL),
('Trail Mix', 4.99, 'Nutritious trail mix, a perfect on-the-go snack', 'Nuts', NULL),
('Organic Kale', 1.99, 'Fresh organic kale leaves, great for salads or smoothies', NULL, NULL),
('Brown Lentils', 2.49, 'Nutritious brown lentils, a versatile ingredient for soups and stews', NULL, NULL),
('Coconut Oil', 8.99, 'Pure coconut oil, ideal for cooking and skincare', NULL, NULL),
('Jasmine Rice', 1.29, 'Fragrant jasmine rice, perfect for Asian-inspired dishes', NULL, NULL),
('Mango', 0.99, 'Ripe mango, delicious and refreshing', NULL, NULL),
('Kiwi', 0.79, 'Juicy kiwi fruit, packed with vitamin C', NULL, NULL),
('Organic Ground Beef', 2.99, 'Lean organic ground beef, perfect for burgers or meatballs', NULL, 18),
('Cashew Butter', 6.49, 'Smooth cashew butter made from roasted cashews, rich in flavor', 'Nuts', NULL),
('Baby Carrots', 1.49, 'Sweet baby carrots, great for snacking or cooking', NULL, NULL),
('Gluten-Free Pasta', 3.99, 'Gluten-free pasta made from corn and rice flour, perfect for gluten-sensitive diets', NULL, NULL),
('Turkey Burger Patties', 2.79, 'Lean turkey burger patties, a healthier alternative to beef', NULL, 18),
('Cherry Juice', 5.99, 'Pure cherry juice, naturally sweet and refreshing', NULL, NULL),
('Fresh Cilantro', 0.69, 'Fragrant cilantro leaves, perfect for adding flavor to dishes', NULL, NULL),
('Maple Syrup', 7.49, 'Pure maple syrup, delicious on pancakes or waffles', NULL, NULL),
('Organic Blueberries', 3.49, 'Sweet organic blueberries, packed with antioxidants', NULL, NULL);


SET @price_product_2 = (SELECT price * (1 - (0 / 100)) FROM Product WHERE product_id = 2);
SET @price_product_5 = (SELECT price * (1 - (20 / 100)) FROM Product WHERE product_id = 5);
SET @price_product_9 = (SELECT price * (1 - (0 / 100)) FROM Product WHERE product_id = 9);
SET @price_product_12 = (SELECT price * (1 - (0 / 100)) FROM Product WHERE product_id = 12);
SET @price_product_18 = (SELECT price * (1 - (10 / 100)) FROM Product WHERE product_id = 18);
SET @price_product_10 = (SELECT price * (1 - (0 / 100)) FROM Product WHERE product_id = 10);
SET @price_product_17 = (SELECT price * (1 - (50 / 100)) FROM Product WHERE product_id = 17);

SET @total_amount = 
    (5 * @price_product_2) +
    (2 * @price_product_5) +
    (1 * @price_product_9) +
    (1 * @price_product_12) +
    (2 * @price_product_18) +
    (5 * @price_product_10) +
    (8 * @price_product_17);

INSERT INTO Payment (staff_id, customer_id, amount)
VALUES (3, 5, @total_amount);

SET @payment_id = LAST_INSERT_ID();

INSERT INTO Payment_Details (payment_id, product_id, quantity, discount_percentage)
VALUES
(@payment_id, 2, 5, 0),
(@payment_id, 5, 2, 20),
(@payment_id, 9, 1, 0),
(@payment_id, 12, 1, 0),
(@payment_id, 18, 2, 10),
(@payment_id, 10, 5, 0),
(@payment_id, 17, 8, 50);