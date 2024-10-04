CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    address VARCHAR(255),
    email VARCHAR(100) NOT NULL UNIQUE,
    contact VARCHAR(15),
    password VARCHAR(255) NOT NULL
);