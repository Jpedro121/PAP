CREATE DATABASE skateshop;

USE skateshop;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

    CREATE TABLE categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL
    );

    INSERT INTO categorias (nome) VALUES ('Deck');
    INSERT INTO categorias (nome) VALUES ('Trucks');
    INSERT INTO categorias (nome) VALUES ('Rodas');
    INSERT INTO categorias (nome) VALUES ('Rolamentos');

    CREATE TABLE produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        imagem VARCHAR(255),
        categoria_id INT,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    );

    INSERT INTO produtos (nome, descricao, preco, imagem, categoria_id)  
    VALUES  
    ('Polar Skate Co. Toba - David Stenstrom Debut Pro Deck', 'Griptape included', 85.00, 'skateboard1.jpg', 1);

    CREATE TABLE decks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        produto_id INT,
        tamanho VARCHAR(50) NOT NULL,
        marca VARCHAR(100),
        estoque INT DEFAULT 0,
        FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
    );

    INSERT INTO decks (produto_id, tamanho, marca, estoque)  
    VALUES  
    (1, '8.5', 'Polar Skate CO,', 10); 

CREATE TABLE trucks (
    id INT(11) NOT NULL AUTO_INCREMENT,
    produto_id INT(11) DEFAULT NULL,
    tamanho VARCHAR(50) NOT NULL,
    marca VARCHAR(100) DEFAULT NULL,
    estoque INT(11) DEFAULT 0,
    descricao TEXT DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

INSERT INTO produtos (nome, descricao, preco, imagem, categoria_id, tamanho)
VALUES
('Ace AF1 Trucks Polished Pair','Par de trucks Ace AF1 com resistência superior.',74.99,'ace-af1-polished.jpg',2,'8.25');

    INSERT INTO trucks (produto_id, tamanho, marca, estoque, descricao)
    VALUES 
    (2, '8.25','Ace',15,'Trucks Ace AF1 com resistência superior.');
