DROP DATABASE IF EXISTS pregunta;
CREATE DATABASE pregunta;
USE pregunta;


CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       nombre_completo VARCHAR(100) NOT NULL,
                       anio_nacimiento YEAR NOT NULL,
                       sexo ENUM('Masculino', 'Femenino', 'Prefiero no cargarlo') NOT NULL,
                       pais VARCHAR(100) NOT NULL,
                       ciudad VARCHAR(100) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
                       foto_perfil VARCHAR(255),
                       verificado BOOLEAN DEFAULT 0,
                       fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- Creación de la tabla Partida
CREATE TABLE Partida
(   id      INT PRIMARY KEY auto_increment,
    nombre  VARCHAR(100) NOT NULL,
    puntaje INT          NOT NULL,
    codigo int not null unique,
    usuario_id INT NOT NULL,
    fechaCreacion DATE,
    FOREIGN KEY (usuario_id) REFERENCES users(id)
);

-- Creación de la tabla Categoría (necesaria para FK en Pregunta)
CREATE TABLE Categoria
(
    id     INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    color VARCHAR(100) NOT NULL
);
-- Creación de la tabla Pregunta
CREATE TABLE Pregunta
(
    id           INT PRIMARY KEY AUTO_INCREMENT,
    descripcion  TEXT,
    punto        INT     NOT NULL,
    esValido     BOOLEAN NOT NULL,
    cantidad_dadas INT,
    acertadas INT,
    porcentaje INT,
    categoria_id INT,
    fechaCreacion DATE,
    FOREIGN KEY (categoria_id) REFERENCES Categoria (id)
);

-- Creación de la tabla Respuesta
CREATE TABLE Respuesta
(
    id          INT PRIMARY KEY AUTO_INCREMENT,
    esCorreto   BOOLEAN NOT NULL,
    descripción TEXT    NOT NULL,
    letra varchar(10) NOT NULL,
    pregunta_id INT,
    FOREIGN KEY (pregunta_id) REFERENCES Pregunta (id)
);



-- Insertar datos en la tabla Categoría
INSERT INTO Categoria (id, nombre, color) VALUES
                                              (1, 'Cultura', '#FFA500'),
                                              (2, 'Ciencia', '#008000'),
                                              (3, 'Entretenimiento', '#FFC0CB'),
                                              (4, 'Deportes', '#8A2BE2'),
                                              (5, 'Geografía', '#87CEEB'),
                                              (6, 'Historia', '#FFFF00');


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES
    (1, "¿Cuál es la capital de Francia?", 10, TRUE, 5, 100, 60, 60),
    (2, "¿Quién formuló la teoría de la relatividad?", 10, TRUE, 2, 80, 25, 31),
    (3, "¿Cuál es el río más largo del mundo?", 10, TRUE, 5, 70, 30, 42),
    (4, "¿En qué año se desintegró la Unión Soviética?", 10, TRUE, 6, 80, 25, 31),
    (5, "¿Quién escribió la novela 'Don Quijote de la Mancha'?", 10, TRUE, 1, 120, 10, 8),
    (6, "¿Cuántos días tiene una semana?", 10, TRUE, 1, 110, 70, 63), -- Corregido a Cultura
    (7, "¿Cuál es la fórmula química del agua?", 10, TRUE, 2, 150, 120, 80),
    (8, "¿Cuántas horas tiene un día?", 10, TRUE, 2, 130, 100, 76), -- Corregido a Ciencia
    (9, "¿Quién fue el primer presidente de los Estados Unidos?", 10, TRUE, 6, 120, 10, 8),
    (10, "¿Cuál es el segundo planeta del sistema solar?", 10, TRUE, 2, 140, 110, 78), -- Corregido a Ciencia
    (11, "¿Cuál es el planeta más grande del sistema solar?", 10, TRUE, 2, 80, 25, 31),
    (12, "¿En qué año comenzó la Segunda Guerra Mundial?", 10, TRUE, 6, 180, 160, 88),
    (13, "¿Quién escribió el libro 'Cien años de soledad'?", 10, TRUE, 1, 120, 10, 8),
    (14, "¿Cuál es el océano más grande del mundo?", 10, TRUE, 5, 220, 200, 90),
    (15, "¿Quién pintó la Mona Lisa?", 10, TRUE, 1, 80, 25, 31);

-- Preguntas Fáciles
INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES
    (16, "¿Cuál es la capital de España?", 10, TRUE, 5, 100, 60, 60),
    (17, "¿Cuál es el color del cielo en un día despejado?", 10, TRUE, 2, 100, 100, 100), -- Corregido a Ciencia
    (18, "¿Qué animal es famoso por tener una cola larga y peluda y colgarse de los árboles?", 10, TRUE, 2, 100, 90, 90),
    (19, "¿Qué tipo de animal es un delfín?", 10, TRUE, 2, 100, 80, 80), -- Corregido a Ciencia
    (20, "¿Cuál es el resultado de sumar 2 + 2?", 10, TRUE, 2, 100, 100, 100);

-- Preguntas de Nivel Medio
INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES
    (21, "¿Cuál es la capital de Australia?", 10, TRUE, 5, 100, 40, 40),
    (22, "¿Qué elemento químico tiene el símbolo 'H'?", 10, TRUE, 2, 100, 30, 30),
    (23, "¿Quién escribió la obra de teatro 'Romeo y Julieta'?", 10, TRUE, 1, 100, 40, 40),
    (24, "¿En qué país se encuentra la Gran Muralla China?", 10, TRUE, 5, 100, 40, 40),
    (25, "¿Cuál es el resultado de multiplicar 8 por 7?", 10, TRUE, 2, 100, 40, 40);

-- Preguntas Difíciles
INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES
    (26, "¿Cuál es el resultado de la raíz cuadrada de 144?", 10, TRUE, 2, 100, 10, 10),
    (27, "¿Cuál es la capital de Islandia?", 10, TRUE, 5, 100, 14, 14),
    (28, "¿En qué año se fundó la Organización de las Naciones Unidas (ONU)?", 10, TRUE, 6, 100, 12, 12),
    (29, "¿Cuál es el componente principal del aire que respiramos?", 10, TRUE, 2, 100, 10, 10),
    (30, "¿Quién fue el primer ser humano en orbitar la Tierra?", 10, TRUE, 6, 100, 8, 8);

-- Preguntas de Deportes
INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES
    (31, "¿Cuántos jugadores tiene un equipo de fútbol en el campo?", 10, TRUE, 4, 100, 70, 70),
    (32, "¿En qué deporte se utiliza una raqueta para golpear una pelota sobre una red?", 10, TRUE, 4, 120, 90, 75),
    (33, "¿Quién ganó el Mundial de Fútbol en 2018?", 10, TRUE, 4, 80, 50, 63),
    (34, "¿Cuál es la distancia de una maratón?", 10, TRUE, 4, 100, 60, 60),
    (35, "¿En qué país se celebraron los Juegos Olímpicos de 2016?", 10, TRUE, 4, 110, 80, 72);

-- Preguntas de Entretenimiento
INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES
    (36, "¿Quién es el director de la película 'Titanic'?", 10, TRUE, 3, 120, 100, 83),
    (37, "¿Cómo se llama el superhéroe cuya identidad secreta es Clark Kent?", 10, TRUE, 3, 150, 120, 80),
    (38, "¿Qué banda lanzó el álbum 'Abbey Road'?", 10, TRUE, 3, 140, 110, 78),
    (39, "¿Qué serie de televisión tiene personajes como Jon Snow y Daenerys Targaryen?", 10, TRUE, 3, 160, 130, 81),
    (40, "¿Qué película animada cuenta la historia de un león llamado Simba?", 10, TRUE, 3, 170, 140, 82);


-- Insertar datos en la tabla Respuesta para cada pregunta
-- Pregunta 1
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (1, TRUE, 'París', 'A', 1),
       (2, FALSE, 'Londres', 'B', 1),
       (3, FALSE, 'Roma', 'C', 1),
       (4, FALSE, 'Berlín', 'D', 1);

-- Pregunta 2
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (5, FALSE, 'Newton', 'A', 2),
       (6, TRUE, 'Einstein', 'B', 2),
       (7, FALSE, 'Galileo', 'C', 2),
       (8, FALSE, 'Tesla', 'D', 2);

-- Pregunta 3
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (9, FALSE, 'Amazonas', 'A', 3),
       (10, FALSE, 'Nilo', 'B', 3),
       (11, TRUE, 'Yangtsé', 'C', 3),
       (12, FALSE, 'Mississippi', 'D', 3);

-- Pregunta 4
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (13, FALSE, '2001', 'A', 4),
       (14, TRUE, '1991', 'B', 4),
       (15, FALSE, '1981', 'C', 4),
       (16, FALSE, '2011', 'D', 4);

-- Pregunta 5
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (17, TRUE, 'Miguel de Cervantes', 'A', 5),
       (18, FALSE, 'Gabriel García Márquez', 'B', 5),
       (19, FALSE, 'Mario Vargas Llosa', 'C', 5),
       (20, FALSE, 'Pablo Neruda', 'D', 5);

-- Pregunta 6
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (21, FALSE, '3', 'A', 6),
       (22, TRUE, '7', 'B', 6),
       (23, FALSE, '5', 'C', 6),
       (24, FALSE, '4', 'D', 6);

-- Pregunta 7
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (25, FALSE, 'O2', 'A', 7),
       (26, FALSE, 'CO2', 'B', 7),
       (27, TRUE, 'H2O', 'C', 7),
       (28, FALSE, 'N2', 'D', 7);

-- Pregunta 8
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (29, FALSE, '20', 'A', 8),
       (30, TRUE, '24', 'B', 8),
       (31, FALSE, '30', 'C', 8),
       (32, FALSE, '28', 'D', 8);

-- Pregunta 9
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (33, TRUE, 'George Washington', 'A', 9),
       (34, FALSE, 'Thomas Jefferson', 'B', 9),
       (35, FALSE, 'Abraham Lincoln', 'C', 9),
       (36, FALSE, 'John Adams', 'D', 9);

-- Pregunta 10
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (37, FALSE, 'Mercurio', 'A', 10),
       (38, TRUE, 'Venus', 'B', 10),
       (39, FALSE, 'Marte', 'C', 10),
       (40, FALSE, 'Júpiter', 'D', 10);

-- Pregunta 11
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (41, FALSE, 'Mercurio', 'A', 11),
       (42, FALSE, 'Venus', 'B', 11),
       (43, FALSE, 'Marte', 'C', 11),
       (44, TRUE, 'Júpiter', 'D', 11);

-- Pregunta 12
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (45, FALSE, '1938', 'A', 12),
       (46, TRUE, '1939', 'B', 12),
       (47, FALSE, '1940', 'C', 12),
       (48, FALSE, '1941', 'D', 12);

-- Pregunta 13
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (49, FALSE, 'Gabriel García Márquez', 'A', 13),
       (50, TRUE, 'Gabriel García Márquez', 'B', 13),
       (51, FALSE, 'Pablo Neruda', 'C', 13),
       (52, FALSE, 'Julio Cortázar', 'D', 13);

-- Pregunta 14
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (53, FALSE, 'Océano Atlántico', 'A', 14),
       (54, TRUE, 'Océano Pacífico', 'B', 14),
       (55, FALSE, 'Océano Índico', 'C', 14),
       (56, FALSE, 'Océano Ártico', 'D', 14);

-- Pregunta 15
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (57, TRUE, 'Leonardo da Vinci', 'A', 15),
       (58, FALSE, 'Pablo Picasso', 'B', 15),
       (59, FALSE, 'Vincent van Gogh', 'C', 15),
       (60, FALSE, 'Rembrandt', 'D', 15);


/*RESPUESTAS DE LAS PREGUNTAS EXTRAS*/
-- Preguntas Fáciles
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (61, TRUE, 'Madrid', 'A', 16),
    (62, FALSE, 'Barcelona', 'B', 16),
    (63, FALSE, 'Londres', 'C', 16),
    (64, FALSE, 'París', 'D', 16);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (65, TRUE, 'Azul', 'A', 17),
    (66, FALSE, 'Verde', 'B', 17),
    (67, FALSE, 'Amarillo', 'C', 17),
    (68, FALSE, 'Rojo', 'D', 17);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (69, TRUE, 'Mono', 'A', 18),
    (70, FALSE, 'Tigre', 'B', 18),
    (71, FALSE, 'Elefante', 'C', 18),
    (72, FALSE, 'León', 'D', 18);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (73, TRUE, 'Mamífero marino', 'A', 19),
    (74, FALSE, 'Pez', 'B', 19),
    (75, FALSE, 'Ave', 'C', 19),
    (76, FALSE, 'Reptil', 'D', 19);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (77, TRUE, '4', 'A', 20),
    (78, FALSE, '2', 'B', 20),
    (79, FALSE, '5', 'C', 20),
    (80, FALSE, '3', 'D', 20);

-- Preguntas de Nivel Medio
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (81, TRUE, 'Canberra', 'A', 21),
    (82, FALSE, 'Sídney', 'B', 21),
    (83, FALSE, 'Melbourne', 'C', 21),
    (84, FALSE, 'Brisbane', 'D', 21);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (85, TRUE, 'Hidrógeno', 'A', 22),
    (86, FALSE, 'Helio', 'B', 22),
    (87, FALSE, 'Oxígeno', 'C', 22),
    (88, FALSE, 'Carbono', 'D', 22);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (89, TRUE, 'William Shakespeare', 'A', 23),
    (90, FALSE, 'Charles Dickens', 'B', 23),
    (91, FALSE, 'Jane Austen', 'C', 23),
    (92, FALSE, 'Federico García Lorca', 'D', 23);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (93, TRUE, 'China', 'A', 24),
    (94, FALSE, 'India', 'B', 24),
    (95, FALSE, 'Rusia', 'C', 24),
    (96, FALSE, 'Estados Unidos', 'D', 24);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (97, TRUE, '56', 'A', 25),
    (98, FALSE, '64', 'B', 25),
    (99, FALSE, '42', 'C', 25),
    (100, FALSE, '36', 'D', 25);

-- Preguntas Difíciles
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (101, TRUE, '12', 'A', 26),
    (102, FALSE, '6', 'B', 26),
    (103, FALSE, '9', 'C', 26),
    (104, FALSE, '16', 'D', 26);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (105, TRUE, 'Reikiavik', 'A', 27),
    (106, FALSE, 'Helsinki', 'B', 27),
    (107, FALSE, 'Oslo', 'C', 27),
    (108, FALSE, 'Estocolmo', 'D', 27);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (109, TRUE, '1945', 'A', 28),
    (110, FALSE, '1918', 'B', 28),
    (111, FALSE, '1939', 'C', 28),
    (112, FALSE, '1954', 'D', 28);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (113, TRUE, 'Nitrógeno', 'A', 29),
    (114, FALSE, 'Oxígeno', 'B', 29),
    (115, FALSE, 'Dióxido de carbono', 'C', 29),
    (116, FALSE, 'Hidrógeno', 'D', 29);

INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES
    (117, TRUE, 'Yuri Gagarin', 'A', 30),
    (118, FALSE, 'Neil Armstrong', 'B', 30),
    (119, FALSE, 'Buzz Aldrin', 'C', 30),
    (120, FALSE, 'John Glenn', 'D', 30);


-- Respuestas para las preguntas de Deportes

-- Pregunta 31: ¿Cuántos jugadores tiene un equipo de fútbol en el campo?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (121, TRUE, '11 jugadores', 'A', 31),
       (122, FALSE, '10 jugadores', 'B', 31),
       (123, FALSE, '12 jugadores', 'C', 31),
       (124, FALSE, '9 jugadores', 'D', 31);

-- Pregunta 32: ¿En qué deporte se utiliza una raqueta para golpear una pelota sobre una red?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (125, TRUE, 'Tenis', 'A', 32),
       (126, FALSE, 'Bádminton', 'B', 32),
       (127, FALSE, 'Squash', 'C', 32),
       (128, FALSE, 'Pádel', 'D', 32);

-- Pregunta 33: ¿Quién ganó el Mundial de Fútbol en 2018?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (129, TRUE, 'Francia', 'A', 33),
       (130, FALSE, 'Croacia', 'B', 33),
       (131, FALSE, 'Brasil', 'C', 33),
       (132, FALSE, 'Alemania', 'D', 33);

-- Pregunta 34: ¿Cuál es la distancia de una maratón?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (133, TRUE, '42.195 km', 'A', 34),
       (134, FALSE, '50 km', 'B', 34),
       (135, FALSE, '21 km', 'C', 34),
       (136, FALSE, '30 km', 'D', 34);

-- Pregunta 35: ¿En qué país se celebraron los Juegos Olímpicos de 2016?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (137, TRUE, 'Brasil', 'A', 35),
       (138, FALSE, 'China', 'B', 35),
       (139, FALSE, 'Reino Unido', 'C', 35),
       (140, FALSE, 'Japón', 'D', 35);

-- Respuestas para las preguntas de Entretenimiento

-- Pregunta 36: ¿Quién es el director de la película 'Titanic'?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (141, TRUE, 'James Cameron', 'A', 36),
       (142, FALSE, 'Steven Spielberg', 'B', 36),
       (143, FALSE, 'Christopher Nolan', 'C', 36),
       (144, FALSE, 'Martin Scorsese', 'D', 36);

-- Pregunta 37: ¿Cómo se llama el superhéroe cuya identidad secreta es Clark Kent?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (145, TRUE, 'Superman', 'A', 37),
       (146, FALSE, 'Batman', 'B', 37),
       (147, FALSE, 'Spiderman', 'C', 37),
       (148, FALSE, 'Ironman', 'D', 37);

-- Pregunta 38: ¿Qué banda lanzó el álbum 'Abbey Road'?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (149, TRUE, 'The Beatles', 'A', 38),
       (150, FALSE, 'The Rolling Stones', 'B', 38),
       (151, FALSE, 'Pink Floyd', 'C', 38),
       (152, FALSE, 'Queen', 'D', 38);

-- Pregunta 39: ¿Qué serie de televisión tiene personajes como Jon Snow y Daenerys Targaryen?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (153, TRUE, 'Juego de Tronos', 'A', 39),
       (154, FALSE, 'Vikingos', 'B', 39),
       (155, FALSE, 'Breaking Bad', 'C', 39),
       (156, FALSE, 'Stranger Things', 'D', 39);

-- Pregunta 40: ¿Qué película animada cuenta la historia de un león llamado Simba?
INSERT INTO Respuesta (id, esCorreto, descripción, letra, pregunta_id)
VALUES (157, TRUE, 'El Rey León', 'A', 40),
       (158, FALSE, 'Madagascar', 'B', 40),
       (159, FALSE, 'Buscando a Nemo', 'C', 40),
       (160, FALSE, 'Shrek', 'D', 40);
