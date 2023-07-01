CREATE DATABASE labprog;
USE labprog;
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


INSERT INTO `users` (`name`, `email`, `password`, `user_type`) 
VALUES ('Grant Admin', 'grant@grant.com', '$2y$10$QpVgUO1NYiL0Bep/oagcgOXNBwy8JYKnrQljUZMnq0nVWBWYTVr6y', 'grant_admin');

INSERT INTO `users` (`name`, `email`, `password`, `user_type`) 
VALUES ('Admin', 'admin@admin.com', '$2y$10$QpVgUO1NYiL0Bep/oagcgOXNBwy8JYKnrQljUZMnq0nVWBWYTVr6y', 'admin');

INSERT INTO `users` (`name`, `email`, `password`, `user_type`) 
VALUES ('Regular', 'regular@regular.com', '$2y$10$QpVgUO1NYiL0Bep/oagcgOXNBwy8JYKnrQljUZMnq0nVWBWYTVr6y', 'regular');

INSERT INTO `categories` (`name`) VALUES ('Free');
INSERT INTO `categories` (`name`) VALUES ('Executivo');
INSERT INTO `categories` (`name`) VALUES ('Premium');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category_id`, `price`, `images`) 
VALUES ('Lolla Palooza', 'Teste1', '2023-07-20', '00:30:00', 'SP', '1', '10.00', 'https://encurtador.com.br/amsSU');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category_id`, `price`, `images`) 
VALUES ('Rock In Rio', 'Teste2', '2023-10-30', '12:30:00', 'RJ', '2', '200.00', 'https://encurtador.com.br/abftE');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category_id`, `price`, `images`) 
VALUES ('The Town', 'Teste3', '2024-02-20', '09:30:00', 'SP', '3', '500.00', 'https://encurtador.com.br/klW59');x 