-- Criação da tabela "users"
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255),
  user_type VARCHAR(255)
);

-- Criação da tabela "categories"
CREATE TABLE categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255)
);

-- Criação da tabela "events"
CREATE TABLE events (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255),
  description TEXT,
  date DATE,
  time TIME,
  location VARCHAR(255),
  category_id INT,
  price DECIMAL(10,2),
  images VARCHAR(255),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Criação da tabela "registrations"
CREATE TABLE registrations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  event_id INT,
  payment_status VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (event_id) REFERENCES events(id)
);

-- Criação da tabela "reviews"
CREATE TABLE reviews (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  event_id INT,
  score INT,
  comment TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (event_id) REFERENCES events(id)
);


