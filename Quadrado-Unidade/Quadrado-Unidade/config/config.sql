-- Criação do banco de dados
CREATE DATABASE geometria;
USE geometria;

-- Tabela Unidades
CREATE TABLE unidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL, 
    unidade VARCHAR(10) NOT NULL 
);

-- Tabela Forma
CREATE TABLE formas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cor VARCHAR(20) NOT NULL,
    fundo VARCHAR(20),
    id_unidade INT NOT NULL,
    FOREIGN KEY (id_unidade) REFERENCES unidades(id)
);

-- Tabela Círculo
CREATE TABLE circulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_forma INT NOT NULL, 
    raio DOUBLE NOT NULL,
    FOREIGN KEY (id_forma) REFERENCES formas(id)
);

-- Tabela Quadrado
CREATE TABLE quadrados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_forma INT NOT NULL,
    lado INT NOT NULL,
    FOREIGN KEY (id_forma) REFERENCES formas(id)
);

-- Tabela Triângulo
CREATE TABLE triangulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_forma INT NOT NULL, 
    lado1 INT NOT NULL,
    lado2 INT NOT NULL,
    lado3 INT NOT NULL,
    FOREIGN KEY (id_forma) REFERENCES formas(id)
);

-- Tabela Triângulo Isósceles
CREATE TABLE triangulos_isosceles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_triangulo INT NOT NULL,
    altura DOUBLE NOT NULL,
    FOREIGN KEY (id_triangulo) REFERENCES triangulos(id)
);

-- Tabela Triângulo Equilátero
CREATE TABLE triangulos_equilateros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_triangulo INT NOT NULL,
    altura DOUBLE NOT NULL,
    FOREIGN KEY (id_triangulo) REFERENCES triangulos(id)
);

-- Tabela Triângulo Escaleno
CREATE TABLE triangulos_escalenos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_triangulo INT NOT NULL,
    altura DOUBLE NOT NULL,
    FOREIGN KEY (id_triangulo) REFERENCES triangulos(id)
);
