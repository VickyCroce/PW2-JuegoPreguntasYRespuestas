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
                       cantidad_dadas INT NOT NULL,
                       cantidad_acertadas INT NOT NULL,
                       ratio INT NOT NULL,
                       verificado BOOLEAN DEFAULT 0,
                       fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
                       rol VARCHAR(50)  NOT NULL);
-- Creación de la tabla Básico
CREATE TABLE jugador
(
    Usuario_id INT PRIMARY KEY,
    FOREIGN KEY (Usuario_id) REFERENCES users (id)
);

-- Creación de la tabla Editor
CREATE TABLE Editor
(
    Usuario_id INT PRIMARY KEY,
    FOREIGN KEY (Usuario_id) REFERENCES users (id)
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
    correcta   BOOLEAN NOT NULL,
    descripcion TEXT    NOT NULL,
    letra varchar(10) NOT NULL,
    pregunta_id INT,
    FOREIGN KEY (pregunta_id) REFERENCES Pregunta (id)
);

INSERT INTO users(id,nombre_completo,anio_nacimiento,sexo,pais,ciudad,email,password,nombre_usuario,foto_perfil,cantidad_dadas,cantidad_acertadas,ratio,verificado,fecha_registro,rol)
    VALUES (1,'Editor', "1985-01-02", "Masculino","Argentina","Buenos Aires",'editor@gmail.com',
        'editor1234', 'editor',null,0,0,0,1,"2000-01-10",'Editor');
        
INSERT INTO users (id,nombre_completo,anio_nacimiento,sexo,pais,ciudad,email,password,nombre_usuario,foto_perfil,cantidad_dadas,cantidad_acertadas,ratio,verificado,fecha_registro,rol)
VALUES (2,'Administrador', "2000-01-01",'Prefiero no cargarlo', "Argentina", "Buenos Aires", 'administrador@admin.com', 'admin1234', 'administrador', null,0,0,0,1,"2000-01-01", 'Administrador');

        -- Insertar datos en la tabla Categoría
INSERT INTO Categoria (id, nombre, color) VALUES
                                              (1, 'Historia', '#FFFF00'),
                                              (2, 'Ciencia', '#008000'),
                                              (3, 'Entretenimiento', '#FFC0CB'),
                                              (4, 'Deportes', '#8A2BE2'),
                                              (5, 'Geografía', '#87CEEB');
-- Insertar datos en la tabla Pregunta


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (1, '¿En qué año comenzó la Segunda Guerra Mundial?', 1, TRUE, 1, 100, 80, 80),
       (2, '¿Quién fue el primer presidente de los Estados Unidos?', 1, TRUE, 1, 100, 80, 80),
       (3, '¿Qué civilización construyó las pirámides de Egipto?', 1, TRUE, 1, 100, 80, 80),
       (4, '¿En qué país ocurrió la Revolución Francesa?', 1, TRUE, 1, 100, 80, 80),
       (5, '¿Quién fue conocido como el “Libertador de América”?', 1, TRUE, 1, 100, 80, 80),
       (6, '¿En qué año se independizó Estados Unidos?', 1, TRUE, 1, 100, 80, 80),
       (7, '¿Quién fue el último emperador romano?', 1, TRUE, 1, 100, 80, 80),
       (8, '¿Cuál fue el primer país en industrializarse?', 1, TRUE, 1, 100, 80, 80),
       (9, '¿Quién escribió la Declaración de Independencia de los EE.UU.?', 1, TRUE, 1, 100, 80, 80),
       (10, '¿Qué fue el Titanic?', 1, TRUE, 1, 100, 80, 80);



INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (11, '¿Qué país construyó el Muro de Berlín?', 1, TRUE, 1, 100, 50, 50),
       (12, '¿En qué país nació Adolf Hitler?', 1, TRUE, 1, 100, 50, 50),
       (13, '¿Quién descubrió América?', 1, TRUE, 1, 100, 50, 50),
       (14, '¿En qué año cayó el Imperio Romano de Occidente?', 1, TRUE, 1, 100, 50, 50),
       (15, '¿Quién fue el último emperador romano?', 1, TRUE, 1, 100, 50, 50);



INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (16, '¿En qué año cayó Constantinopla, marcando el fin del Imperio Bizantino?', 1, TRUE, 1, 100, 10, 10),
       (17, '¿Quién fue el líder de la Revolución de Octubre en Rusia?', 1, TRUE, 1, 100, 10, 10),
       (18, '¿Cuál fue la principal causa de la Guerra de los Treinta Años?', 1, TRUE, 1, 100, 10, 10),
       (19, '¿En qué año se firmó la Declaración de Derechos en Inglaterra?', 1, TRUE, 1, 100, 10, 10),
       (20, '¿Quién fue el rey de Inglaterra durante la Guerra de las Rosas?', 1, TRUE, 1, 100, 10, 10);



INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (21, '¿Cuál es el elemento químico con el símbolo O?', 1, TRUE, 2, 100, 80, 80),
       (22, '¿Cuál es el planeta más cercano al sol?', 1, TRUE, 2, 100, 80, 80),
       (23, '¿Qué órgano bombea la sangre en el cuerpo humano?', 1, TRUE, 2, 100, 80, 80),
       (24, '¿Cuál es el metal más abundante en la corteza terrestre?', 1, TRUE, 2, 100, 80, 80),
       (25, '¿Quién formuló la ley de la gravedad?', 1, TRUE, 2, 100, 80, 80),
       (26, '¿Cuál es el animal más grande del mundo?', 1, TRUE, 2, 100, 80, 80),
       (27, '¿Cómo se llama la galaxia en la que está la Tierra?', 1, TRUE, 2, 100, 80, 80),
       (28, '¿Cuántos planetas tiene el sistema solar?', 1, TRUE, 2, 100, 80, 80),
       (29, '¿Qué gas es esencial para la respiración?', 1, TRUE, 2, 100, 80, 80),
       (30, '¿Qué animal produce la leche?', 1, TRUE, 2, 100, 80, 80);




INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (31, '¿Cuál es el segundo elemento más abundante en la atmósfera terrestre?', 1, TRUE, 2, 100, 50, 50),
       (32, '¿Qué científico es famoso por su teoría de la evolución por selección natural?', 2, TRUE, 2, 100, 50, 50),
       (33, '¿Qué tipo de enlace químico une a los átomos en una molécula de agua?', 1, TRUE, 2, 100, 50, 50),
       (34, '¿Qué unidad se utiliza para medir la intensidad de la corriente eléctrica?', 1, TRUE, 2, 100, 50, 50),
       (35, '¿Qué parte de la célula se considera el “centro de control” debido a su ADN?', 1, TRUE, 2, 100, 50, 50);



INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (36, '¿Cuál es la constante de Planck (h) en el Sistema Internacional de Unidades?', 1, TRUE, 2, 100, 10, 10),
       (37, '¿Qué nombre recibe la reacción nuclear que ocurre en el núcleo del Sol?', 1, TRUE, 2, 100, 10, 10),
       (38, '¿Quién propuso la ecuación de Schrödinger en la mecánica cuántica?', 1, TRUE, 2, 100, 10, 10),
       (39, '¿Qué órgano del cuerpo humano produce insulina?', 1, TRUE, 2, 100, 10, 10),
       (40, '¿Cuál es el metal con el punto de fusión más alto?', 1, TRUE, 2, 100, 10, 10);


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (41, '¿Quién es el protagonista de la película "El Rey León"?', 1, TRUE, 3, 100, 80, 80),
       (42, '¿Cuál es el nombre del ratón más famoso de Disney?', 1, TRUE, 3, 100, 80, 80),
       (43, '¿En qué saga se encuentra el personaje "Frodo"?', 1, TRUE, 3, 100, 80, 80),
       (44, '¿Quién pintó "La Mona Lisa"?', 1, TRUE, 3, 100, 80, 80),
       (45, '¿Quién es el mejor amigo de Harry Potter?', 1, TRUE, 3, 100, 80, 80),
       (46, '¿Cuál es el nombre del parque de atracciones de Disney?', 1, TRUE, 3, 100, 80, 80),
       (47, '¿Quién dirigió la película "Jurassic Park"?', 1, TRUE, 3, 100, 80, 80),
       (48, '¿En qué año se estrenó "Star Wars"?', 1, TRUE, 3, 100, 80, 80),
       (49, '¿Cuál es la princesa de Disney con cabello largo y rubio?', 1, TRUE, 3, 100, 80, 80),
       (50, '¿Qué animal representa a Dumbo?', 1, TRUE, 3, 100, 80, 80);



INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (51, '¿Quién es el autor de la serie de libros "Harry Potter"?', 1, TRUE, 3, 100, 50, 50),
       (52, '¿En qué año se estrenó la película "Titanic"?', 1, TRUE, 3, 100, 50, 50),
       (53, '¿Qué banda lanzó el álbum "Abbey Road" en 1969?', 1, TRUE, 3, 100, 50, 50),
       (54, '¿Quién interpretó a Indiana Jones en la famosa franquicia de películas?', 1, TRUE, 3, 100, 50, 50),
       (55, '¿Cuál es el nombre del superhéroe conocido como el "Hombre Araña"?', 1, TRUE, 3, 100, 50, 50);

INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (56, '¿Quién dirigió la película "El Padrino"?', 1, TRUE, 3, 100, 10, 10),
       (57, '¿Cuál es el nombre real de la cantante conocida como Lady Gaga?', 1, TRUE, 3, 100, 10, 10),
       (58, '¿Qué película ganó el premio Óscar a la Mejor Película en 1994?', 1, TRUE, 3, 100, 10, 10),
       (59, '¿Qué personaje de "Star Wars" es conocido por decir "Que la Fuerza te acompañe"?', 1, TRUE, 3, 100, 10, 10),
       (60, '¿Cuál es el nombre del villano principal en "Los Vengadores: Infinity War"?', 1, TRUE, 3, 100, 10, 10);


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (61, '¿Cuántos jugadores tiene un equipo de fútbol en el campo?', 1, TRUE, 4, 100, 80, 80),
       (62, '¿En qué deporte se usa una raqueta y una pelota amarilla?', 1, TRUE, 4, 100, 80, 80),
       (63, '¿Cuál es el deporte más popular en Estados Unidos?', 1, TRUE, 4, 100, 80, 80),
       (64, '¿Cuántos anillos olímpicos hay?', 1, TRUE, 4, 100, 80, 80),
       (65, '¿En qué deporte compite Usain Bolt?', 1, TRUE, 4, 100, 80, 80),
       (66, '¿Cuál es el país de origen del fútbol?', 1, TRUE, 4, 100, 80, 80),
       (67, '¿Qué deporte se juega en Wimbledon?', 1, TRUE, 4, 100, 80, 80),
       (68, '¿Cuántos puntos vale un gol en fútbol?', 1, TRUE, 4, 100, 80, 80),
       (69, '¿Cuántas bases hay en el béisbol?', 1, TRUE, 4, 100, 80, 80),
       (70, '¿Cuál es el deporte en el que se utiliza una tabla y olas?', 1, TRUE, 4, 100, 80, 80);



INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (71, '¿Cuántos sets necesita ganar un jugador para ganar un partido en tenis masculino de Grand Slam?', 1, TRUE, 4, 100, 50, 50),
       (72, '¿Cuál es el país con más Copas Mundiales de Fútbol ganadas?', 1, TRUE, 4, 100, 50, 50),
       (73, '¿Qué jugador tiene el récord de puntos en la NBA?', 1, TRUE, 4, 100, 50, 50),
       (74, '¿Cuántos jugadores conforman un equipo de rugby en el campo?', 1, TRUE, 4, 100, 50, 50),
       (75, '¿En qué ciudad se celebraron los Juegos Olímpicos de 2016?', 1, TRUE, 4, 100, 50, 50);


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (76, '¿Cuál es el único equipo de la NFL que ha tenido una temporada perfecta?', 1, TRUE, 4, 100, 10, 10),
       (77, '¿Quién fue el primer jugador de baloncesto en anotar más de 100 puntos en un solo juego de la NBA?', 3, TRUE, 4, 100, 10, 10),
       (78, '¿En qué país se originó el cricket?', 1, TRUE, 4, 100, 10, 10),
       (79, '¿Cuál es el equipo de fútbol más antiguo del mundo?', 1, TRUE, 4, 100, 10, 10),
       (80, '¿Qué velocista ostentó el récord de los 100 metros planos antes de Usain Bolt?', 1, TRUE, 4, 100, 10, 10);


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (81, '¿Cuál es la capital de Francia?', 1, TRUE, 5, 100, 80, 80),
       (82, '¿En qué continente se encuentra Brasil?', 1, TRUE, 5, 100, 80, 80),
       (83, '¿Cuál es el país más grande del mundo por área?', 1, TRUE, 5, 100, 80, 80),
       (84, '¿Cuál es el desierto más grande del mundo?', 1, TRUE, 5, 100, 80, 80),
       (85, '¿Cuál es el océano más grande del mundo?', 1, TRUE, 5, 100, 80, 80),
       (86, '¿Cuál es el río más largo del mundo?', 1, TRUE, 5, 100, 80, 80),
       (87, '¿En qué país se encuentra la Torre de Pisa?', 1, TRUE, 5, 100, 80, 80),
       (88, '¿En qué continente está Egipto?', 1, TRUE, 5, 100, 80, 80),
       (89, '¿Cuál es la capital de Japón?', 1, TRUE, 5, 100, 80, 80),
       (90, '¿En qué país está el Monte Everest?', 1, TRUE, 5, 100, 80, 80);


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (91, '¿Cuál es el país con más habitantes en el mundo?', 1, TRUE, 5, 100, 50, 50),
       (92, '¿Qué país tiene el mayor número de islas en el mundo?', 1, TRUE, 5, 100, 50, 50),
       (93, '¿En qué ciudad se encuentra el Coliseo?', 1, TRUE, 5, 100, 50, 50),
       (94, '¿Cuál es el país más pequeño del mundo?', 1, TRUE, 5, 100, 50, 50),
       (95, '¿Qué cordillera se extiende a lo largo de la costa oeste de América del Sur?', 2, TRUE, 5, 100, 50, 50);


INSERT INTO Pregunta (id, descripcion, punto, esValido, categoria_id, cantidad_dadas, acertadas, porcentaje)
VALUES (96, '¿Cuál es el lago más profundo del mundo?', 1, TRUE, 5, 100, 10, 10),
       (97, '¿Cuál es el país más joven del mundo?', 1, TRUE, 5, 100, 10, 10),
       (98, '¿Cuál es el segundo país más grande en extensión territorial?', 1, TRUE, 5, 100, 10, 10),
       (99, '¿Qué desierto se considera el más antiguo del mundo?', 1, TRUE, 5, 100, 10, 10),
       (100, '¿Cuál es la capital más alta del mundo?', 1, TRUE, 5, 100, 10, 10);




INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (1, TRUE, '1939', 'A', 1),
       (2, FALSE, '1941', 'B', 1),
       (3, FALSE, '1914', 'C', 1),
       (4, FALSE, '1945', 'D', 1),

       (5, FALSE, 'Thomas Jefferson', 'A', 2),
       (6, FALSE, 'Abraham Lincoln', 'B', 2),
       (7, TRUE, 'George Washington', 'C', 2),
       (8, FALSE, 'Benjamin Franklin', 'D', 2),

       (9, FALSE, 'Roma', 'A', 3),
       (10, TRUE, 'Egipto', 'B', 3),
       (11, FALSE, 'China', 'C', 3),
       (12, FALSE, 'India', 'D', 3),

       (13, FALSE, 'Alemania', 'A', 4),
       (14, FALSE, 'Italia', 'B', 4),
       (15, TRUE, 'Francia', 'C', 4),
       (16, FALSE, 'España', 'D', 4),

       (17, TRUE, 'Simón Bolívar', 'A', 5),
       (18, FALSE, 'José de San Martín', 'B', 5),
       (19, FALSE, 'Bernardo O’Higgins', 'C', 5),
       (20, FALSE, 'Miguel Hidalgo', 'D', 5),

       (21, FALSE, '1783', 'A', 6),
       (22, TRUE, '1776', 'B', 6),
       (23, FALSE, '1804', 'C', 6),
       (24, FALSE, '1812', 'D', 6),

       (25, FALSE, 'Julio César', 'A', 7),
       (26, TRUE, 'Rómulo Augústulo', 'B', 7),
       (27, FALSE, 'Nerón', 'C', 7),
       (28, FALSE, 'Constantino', 'D', 7),

       (29, FALSE, 'Francia', 'A', 8),
       (30, TRUE, 'Inglaterra', 'B', 8),
       (31, FALSE, 'España', 'C', 8),
       (32, FALSE, 'Alemania', 'D', 8),

       (33, FALSE, 'George Washington', 'A', 9),
       (34, TRUE, 'Thomas Jefferson', 'B', 9),
       (35, FALSE, 'Alexander Hamilton', 'C', 9),
       (36, FALSE, 'James Madison', 'D', 9),

       (37, TRUE, 'Un barco', 'A', 10),
       (38, FALSE, 'Un tren', 'B', 10),
       (39, FALSE, 'Un edificio', 'C', 10),
       (40, FALSE, 'Un submarino', 'D', 10);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (41, FALSE, 'Francia', 'A', 11),
       (42, FALSE, 'Rusia', 'B', 11),
       (43, TRUE, 'Alemania Oriental', 'C', 11),
       (44, FALSE, 'Reino Unido', 'D', 11),

       (45, TRUE, 'Austria', 'A', 12),
       (46, FALSE, 'Alemania', 'B', 12),
       (47, FALSE, 'Francia', 'C', 12),
       (48, FALSE, 'Suiza', 'D', 12),

       (49, TRUE, 'Cristóbal Colón', 'A', 13),
       (50, FALSE, 'Amerigo Vespucci', 'B', 13),
       (51, FALSE, 'Juan Ponce de León', 'C', 13),
       (52, FALSE, 'Fernando de Magallanes', 'D', 13),

       (53, FALSE, '410', 'A', 14),
       (54, TRUE, '476', 'B', 14),
       (55, FALSE, '395', 'C', 14),
       (56, FALSE, '500', 'D', 14),

       (57, FALSE, 'Julio César', 'A', 15),
       (58, TRUE, 'Rómulo Augústulo', 'B', 15),
       (59, FALSE, 'Nerón', 'C', 15),
       (60, FALSE, 'Constantino', 'D', 15);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (61, TRUE, '1453', 'A', 16),
       (62, FALSE, '1492', 'B', 16),
       (63, FALSE, '1521', 'C', 16),
       (64, FALSE, '1204', 'D', 16),

       (65, TRUE, 'Vladimir Lenin', 'A', 17),
       (66, FALSE, 'Joseph Stalin', 'B', 17),
       (67, FALSE, 'Leon Trotsky', 'C', 17),
       (68, FALSE, 'Mijaíl Gorbachov', 'D', 17),

       (69, FALSE, 'Colonización', 'A', 18),
       (70, TRUE, 'Conflictos religiosos', 'B', 18),
       (71, FALSE, 'Revolución industrial', 'C', 18),
       (72, FALSE, 'Disputas territoriales', 'D', 18),

       (73, TRUE, '1689', 'A', 19),
       (74, FALSE, '1701', 'B', 19),
       (75, FALSE, '1714', 'C', 19),
       (76, FALSE, '1603', 'D', 19),

       (77, FALSE, 'Ricardo III', 'A', 20),
       (78, FALSE, 'Eduardo IV', 'B', 20),
       (79, TRUE, 'Enrique VI', 'C', 20),
       (80, FALSE, 'Enrique VII', 'D', 20);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (81, TRUE, 'Oxígeno', 'A', 21),
       (82, FALSE, 'Hidrógeno', 'B', 21),
       (83, FALSE, 'Carbono', 'C', 21),
       (84, FALSE, 'Nitrógeno', 'D', 21),

       (85, TRUE, 'Mercurio', 'A', 22),
       (86, FALSE, 'Venus', 'B', 22),
       (87, FALSE, 'Marte', 'C', 22),
       (88, FALSE, 'Tierra', 'D', 22),

       (89, FALSE, 'Pulmones', 'A', 23),
       (90, TRUE, 'Corazón', 'B', 23),
       (91, FALSE, 'Hígado', 'C', 23),
       (92, FALSE, 'Estómago', 'D', 23),

       (93, FALSE, 'Hierro', 'A', 24),
       (94, TRUE, 'Aluminio', 'B', 24),
       (95, FALSE, 'Cobre', 'C', 24),
       (96, FALSE, 'Plata', 'D', 24),

       (97, FALSE, 'Albert Einstein', 'A', 25),
       (98, FALSE, 'Galileo Galilei', 'B', 25),
       (99, TRUE, 'Isaac Newton', 'C', 25),
       (100, FALSE, 'Nikola Tesla', 'D', 25),

       (101, TRUE, 'Ballena azul', 'A', 26),
       (102, FALSE, 'Elefante', 'B', 26),
       (103, FALSE, 'Tiburón blanco', 'C', 26),
       (104, FALSE, 'Jirafa', 'D', 26),

       (105, TRUE, 'Vía Láctea', 'A', 27),
       (106, FALSE, 'Andrómeda', 'B', 27),
       (107, FALSE, 'Triángulo', 'C', 27),
       (108, FALSE, 'Sagitario', 'D', 27),

       (109, FALSE, '10', 'A', 28),
       (110, TRUE, '8', 'B', 28),
       (111, FALSE, '9', 'C', 28),
       (112, FALSE, '7', 'D', 28),

       (113, TRUE, 'Oxígeno', 'A', 29),
       (114, FALSE, 'Nitrógeno', 'B', 29),
       (115, FALSE, 'Dióxido de carbono', 'C', 29),
       (116, FALSE, 'Hidrógeno', 'D', 29),

       (117, FALSE, 'Tiburón', 'A', 30),
       (118, FALSE, 'Elefante', 'B', 30),
       (119, TRUE, 'Vaca', 'C', 30),
       (120, FALSE, 'Gato', 'D', 30);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (121, TRUE, 'Oxígeno', 'A', 31),
       (122, FALSE, 'Dióxido de carbono', 'B', 31),
       (123, FALSE, 'Nitrógeno', 'C', 31),
       (124, FALSE, 'Argón', 'D', 31),

       (125, TRUE, 'Charles Darwin', 'A', 32),
       (126, FALSE, 'Albert Einstein', 'B', 32),
       (127, FALSE, 'Isaac Newton', 'C', 32),
       (128, FALSE, 'Nikola Tesla', 'D', 32),

       (129, FALSE, 'Iónico', 'A', 33),
       (130, TRUE, 'Covalente', 'B', 33),
       (131, FALSE, 'Metálico', 'C', 33),
       (132, FALSE, 'Hidrógeno', 'D', 33),

       (133, FALSE, 'Voltio', 'A', 34),
       (134, TRUE, 'Amperio', 'B', 34),
       (135, FALSE, 'Julio', 'C', 34),
       (136, FALSE, 'Ohmio', 'D', 34),

       (137, TRUE, 'Núcleo', 'A', 35),
       (138, FALSE, 'Mitocondria', 'B', 35),
       (139, FALSE, 'Citoplasma', 'C', 35),
       (140, FALSE, 'Membrana', 'D', 35);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (141, TRUE, '6.626 x 10^-34 J·s', 'A', 36),
       (142, FALSE, '9.81 m/s^2', 'B', 36),
       (143, FALSE, '3.00 x 10^8 m/s', 'C', 36),
       (144, FALSE, '1.67 x 10^-27 kg', 'D', 36),

       (145, TRUE, 'Fusión nuclear', 'A', 37),
       (146, FALSE, 'Fisión nuclear', 'B', 37),
       (147, FALSE, 'Radiactividad', 'C', 37),
       (148, FALSE, 'Química cuántica', 'D', 37),

       (149, TRUE, 'Erwin Schrödinger', 'A', 38),
       (150, FALSE, 'Albert Einstein', 'B', 38),
       (151, FALSE, 'Niels Bohr', 'C', 38),
       (152, FALSE, 'Max Planck', 'D', 38),

       (153, FALSE, 'Hígado', 'A', 39),
       (154, TRUE, 'Páncreas', 'B', 39),
       (155, FALSE, 'Riñón', 'C', 39),
       (156, FALSE, 'Corazón', 'D', 39),

       (157, FALSE, 'Hierro', 'A', 40),
       (158, TRUE, 'Tungsteno', 'B', 40),
       (159, FALSE, 'Cobre', 'C', 40),
       (160, FALSE, 'Oro', 'D', 40);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (161, TRUE, 'Simba', 'A', 41),
       (162, FALSE, 'Mufasa', 'B', 41),
       (163, FALSE, 'Scar', 'C', 41),
       (164, FALSE, 'Timon', 'D', 41),

       (165, FALSE, 'Donald', 'A', 42),
       (166, TRUE, 'Mickey', 'B', 42),
       (167, FALSE, 'Goofy', 'C', 42),
       (168, FALSE, 'Pluto', 'D', 42),

       (169, FALSE, 'Harry Potter', 'A', 43),
       (170, TRUE, 'El Señor de los Anillos', 'B', 43),
       (171, FALSE, 'Las Crónicas de Narnia', 'C', 43),
       (172, FALSE, 'Juego de Tronos', 'D', 43),

       (173, FALSE, 'Pablo Picasso', 'A', 44),
       (174, FALSE, 'Vincent van Gogh', 'B', 44),
       (175, TRUE, 'Leonardo da Vinci', 'C', 44),
       (176, FALSE, 'Claude Monet', 'D', 44),

       (177, TRUE, 'Ron Weasley', 'A', 45),
       (178, FALSE, 'Draco Malfoy', 'B', 45),
       (179, FALSE, 'Neville Longbottom', 'C', 45),
       (180, FALSE, 'Hermione Granger', 'D', 45),

       (181, FALSE, 'Universal Studios', 'A', 46),
       (182, TRUE, 'Disneyland', 'B', 46),
       (183, FALSE, 'Six Flags', 'C', 46),
       (184, FALSE, 'SeaWorld', 'D', 46),

       (185, FALSE, 'George Lucas', 'A', 47),
       (186, TRUE, 'Steven Spielberg', 'B', 47),
       (187, FALSE, 'Martin Scorsese', 'C', 47),
       (188, FALSE, 'James Cameron', 'D', 47),

       (189, TRUE, '1977', 'A', 48),
       (190, FALSE, '1980', 'B', 48),
       (191, FALSE, '1983', 'C', 48),
       (192, FALSE, '1999', 'D', 48),

       (193, FALSE, 'Blancanieves', 'A', 49),
       (194, TRUE, 'Rapunzel', 'B', 49),
       (195, FALSE, 'Ariel', 'C', 49),
       (196, FALSE, 'Cenicienta', 'D', 49),

       (197, TRUE, 'Elefante', 'A', 50),
       (198, FALSE, 'Ratón', 'B', 50),
       (199, FALSE, 'Perro', 'C', 50),
       (200, FALSE, 'Pájaro', 'D', 50);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (201, TRUE, 'J.K. Rowling', 'A', 51),
       (202, FALSE, 'J.R.R. Tolkien', 'B', 51),
       (203, FALSE, 'C.S. Lewis', 'C', 51),
       (204, FALSE, 'Stephen King', 'D', 51),

       (205, TRUE, '1997', 'A', 52),
       (206, FALSE, '1999', 'B', 52),
       (207, FALSE, '2000', 'C', 52),
       (208, FALSE, '1996', 'D', 52),

       (209, TRUE, 'The Beatles', 'A', 53),
       (210, FALSE, 'The Rolling Stones', 'B', 53),
       (211, FALSE, 'Queen', 'C', 53),
       (212, FALSE, 'Led Zeppelin', 'D', 53),

       (213, FALSE, 'Tom Cruise', 'A', 54),
       (214, TRUE, 'Harrison Ford', 'B', 54),
       (215, FALSE, 'Mel Gibson', 'C', 54),
       (216, FALSE, 'Sylvester Stallone', 'D', 54),

       (217, TRUE, 'Peter Parker', 'A', 55),
       (218, FALSE, 'Clark Kent', 'B', 55),
       (219, FALSE, 'Bruce Wayne', 'C', 55),
       (220, FALSE, 'Tony Stark', 'D', 55);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (221, TRUE, 'Francis Ford Coppola', 'A', 56),
       (222, FALSE, 'Martin Scorsese', 'B', 56),
       (223, FALSE, 'Stanley Kubrick', 'C', 56),
       (224, FALSE, 'Steven Spielberg', 'D', 56),

       (225, FALSE, 'Britney Spears', 'A', 57),
       (226, TRUE, 'Stefani Germanotta', 'B', 57),
       (227, FALSE, 'Madonna', 'C', 57),
       (228, FALSE, 'Katy Perry', 'D', 57),

       (229, TRUE, 'Forrest Gump', 'A', 58),
       (230, FALSE, 'Pulp Fiction', 'B', 58),
       (231, FALSE, 'The Shawshank Redemption', 'C', 58),
       (232, FALSE, 'El Rey León', 'D', 58),

       (233, FALSE, 'Han Solo', 'A', 59),
       (234, FALSE, 'Darth Vader', 'B', 59),
       (235, TRUE, 'Obi-Wan Kenobi', 'C', 59),
       (236, FALSE, 'Yoda', 'D', 59),

       (237, TRUE, 'Thanos', 'A', 60),
       (238, FALSE, 'Loki', 'B', 60),
       (239, FALSE, 'Ultron', 'C', 60),
       (240, FALSE, 'Red Skull', 'D', 60);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (241, TRUE, '11', 'A', 61),
       (242, FALSE, '10', 'B', 61),
       (243, FALSE, '12', 'C', 61),
       (244, FALSE, '9', 'D', 61),

       (245, TRUE, 'Tenis', 'A', 62),
       (246, FALSE, 'Ping pong', 'B', 62),
       (247, FALSE, 'Bádminton', 'C', 62),
       (248, FALSE, 'Squash', 'D', 62),

       (249, FALSE, 'Béisbol', 'A', 63),
       (250, TRUE, 'Fútbol americano', 'B', 63),
       (251, FALSE, 'Hockey', 'C', 63),
       (252, FALSE, 'Baloncesto', 'D', 63),

       (253, FALSE, '4', 'A', 64),
       (254, FALSE, '3', 'B', 64),
       (255, TRUE, '5', 'C', 64),
       (256, FALSE, '6', 'D', 64),

       (257, FALSE, 'Natación', 'A', 65),
       (258, FALSE, 'Ciclismo', 'B', 65),
       (259, FALSE, 'Esquí', 'C', 65),
       (260, TRUE, 'Atletismo', 'D', 65),

       (261, FALSE, 'España', 'A', 66),
       (262, TRUE, 'Inglaterra', 'B', 66),
       (263, FALSE, 'Francia', 'C', 66),
       (264, FALSE, 'Brasil', 'D', 66),

       (265, TRUE, 'Tenis', 'A', 67),
       (266, FALSE, 'Golf', 'B', 67),
       (267, FALSE, 'Bádminton', 'C', 67),
       (268, FALSE, 'Baloncesto', 'D', 67),

       (269, TRUE, '1', 'A', 68),
       (270, FALSE, '2', 'B', 68),
       (271, FALSE, '3', 'C', 68),
       (272, FALSE, '4', 'D', 68),

       (273, TRUE, '4', 'A', 69),
       (274, FALSE, '3', 'B', 69),
       (275, FALSE, '5', 'C', 69),
       (276, FALSE, '6', 'D', 69),

       (277, TRUE, 'Surf', 'A', 70),
       (278, FALSE, 'Esquí', 'B', 70),
       (279, FALSE, 'Snowboard', 'C', 70),
       (280, FALSE, 'Remo', 'D', 70);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (281, TRUE, '3', 'A', 71),
       (282, FALSE, '5', 'B', 71),
       (283, FALSE, '2', 'C', 71),
       (284, FALSE, '4', 'D', 71),

       (285, FALSE, 'Alemania', 'A', 72),
       (286, TRUE, 'Brasil', 'B', 72),
       (287, FALSE, 'Italia', 'C', 72),
       (288, FALSE, 'Argentina', 'D', 72),

       (289, FALSE, 'Michael Jordan', 'A', 73),
       (290, TRUE, 'Kareem Abdul-Jabbar', 'B', 73),
       (291, FALSE, 'LeBron James', 'C', 73),
       (292, FALSE, 'Wilt Chamberlain', 'D', 73),

       (293, FALSE, '13', 'A', 74),
       (294, TRUE, '15', 'B', 74),
       (295, FALSE, '12', 'C', 74),
       (296, FALSE, '14', 'D', 74),

       (297, FALSE, 'Tokio', 'A', 75),
       (298, FALSE, 'Londres', 'B', 75),
       (299, TRUE, 'Río de Janeiro', 'C', 75),
       (300, FALSE, 'Pekín', 'D', 75);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (301, TRUE, 'Miami Dolphins', 'A', 76),
       (302, FALSE, 'New England Patriots', 'B', 76),
       (303, FALSE, 'Dallas Cowboys', 'C', 76),
       (304, FALSE, 'Pittsburgh Steelers', 'D', 76),

       (305, FALSE, 'Michael Jordan', 'A', 77),
       (306, FALSE, 'Larry Bird', 'B', 77),
       (307, FALSE, 'Kobe Bryant', 'C', 77),
       (308, TRUE, 'Wilt Chamberlain', 'D', 77),

       (309, TRUE, 'Inglaterra', 'A', 78),
       (310, FALSE, 'Australia', 'B', 78),
       (311, FALSE, 'India', 'C', 78),
       (312, FALSE, 'Sudáfrica', 'D', 78),

       (313, TRUE, 'Sheffield FC', 'A', 79),
       (314, FALSE, 'Manchester United', 'B', 79),
       (315, FALSE, 'Liverpool FC', 'C', 79),
       (316, FALSE, 'Real Madrid', 'D', 79),

       (317, FALSE, 'Asafa Powell', 'A', 80),
       (318, FALSE, 'Carl Lewis', 'B', 80),
       (319, TRUE, 'Tim Montgomery', 'C', 80),
       (320, FALSE, 'Maurice Greene', 'D', 80);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (321, TRUE, 'París', 'A', 81),
       (322, FALSE, 'Londres', 'B', 81),
       (323, FALSE, 'Madrid', 'C', 81),
       (324, FALSE, 'Roma', 'D', 81),

       (325, TRUE, 'América del Sur', 'A', 82),
       (326, FALSE, 'Asia', 'B', 82),
       (327, FALSE, 'Europa', 'C', 82),
       (328, FALSE, 'África', 'D', 82),

       (329, TRUE, 'Rusia', 'A', 83),
       (330, FALSE, 'China', 'B', 83),
       (331, FALSE, 'Canadá', 'C', 83),
       (332, FALSE, 'Estados Unidos', 'D', 83),

       (333, TRUE, 'Sahara', 'A', 84),
       (334, FALSE, 'Kalahari', 'B', 84),
       (335, FALSE, 'Gobi', 'C', 84),
       (336, FALSE, 'Atacama', 'D', 84),

       (337, TRUE, 'Pacífico', 'A', 85),
       (338, FALSE, 'Atlántico', 'B', 85),
       (339, FALSE, 'Índico', 'C', 85),
       (340, FALSE, 'Ártico', 'D', 85),

       (341, TRUE, 'Nilo', 'A', 86),
       (342, FALSE, 'Amazonas', 'B', 86),
       (343, FALSE, 'Yangtsé', 'C', 86),
       (344, FALSE, 'Misisipi', 'D', 86),

       (345, TRUE, 'Italia', 'A', 87),
       (346, FALSE, 'Francia', 'B', 87),
       (347, FALSE, 'España', 'C', 87),
       (348, FALSE, 'Grecia', 'D', 87),

       (349, TRUE, 'África', 'A', 88),
       (350, FALSE, 'Asia', 'B', 88),
       (351, FALSE, 'Europa', 'C', 88),
       (352, FALSE, 'América del Norte', 'D', 88),

       (353, TRUE, 'Tokio', 'A', 89),
       (354, FALSE, 'Seúl', 'B', 89),
       (355, FALSE, 'Beijing', 'C', 89),
       (356, FALSE, 'Osaka', 'D', 89),

       (357, TRUE, 'Nepal', 'A', 90),
       (358, FALSE, 'China', 'B', 90),
       (359, FALSE, 'India', 'C', 90),
       (360, FALSE, 'Bután', 'D', 90);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (361, TRUE, 'China', 'A', 91),
       (362, FALSE, 'India', 'B', 91),
       (363, FALSE, 'Estados Unidos', 'C', 91),
       (364, FALSE, 'Brasil', 'D', 91),

       (365, TRUE, 'Suecia', 'A', 92),
       (366, FALSE, 'Noruega', 'B', 92),
       (367, FALSE, 'Canadá', 'C', 92),
       (368, FALSE, 'Filipinas', 'D', 92),

       (369, TRUE, 'Roma', 'A', 93),
       (370, FALSE, 'Atenas', 'B', 93),
       (371, FALSE, 'París', 'C', 93),
       (372, FALSE, 'Estambul', 'D', 93),

       (373, TRUE, 'Vaticano', 'A', 94),
       (374, FALSE, 'Mónaco', 'B', 94),
       (375, FALSE, 'San Marino', 'C', 94),
       (376, FALSE, 'Liechtenstein', 'D', 94),

       (377, TRUE, 'Los Andes', 'A', 95),
       (378, FALSE, 'Rocosas', 'B', 95),
       (379, FALSE, 'Alpes', 'C', 95),
       (380, FALSE, 'Himalayas', 'D', 95);


INSERT INTO Respuesta (id, correcta, descripcion, letra, pregunta_id)
VALUES (381, TRUE, 'Lago Baikal', 'A', 96),
       (382, FALSE, 'Lago Tanganica', 'B', 96),
       (383, FALSE, 'Lago Superior', 'C', 96),
       (384, FALSE, 'Lago Victoria', 'D', 96),

       (385, TRUE, 'Sudán del Sur', 'A', 97),
       (386, FALSE, 'Timor Oriental', 'B', 97),
       (387, FALSE, 'Kosovo', 'C', 97),
       (388, FALSE, 'Eritrea', 'D', 97),

       (389, TRUE, 'Canadá', 'A', 98),
       (390, FALSE, 'China', 'B', 98),
       (391, FALSE, 'Rusia', 'C', 98),
       (392, FALSE, 'Estados Unidos', 'D', 98),

       (393, TRUE, 'Desierto de Namib', 'A', 99),
       (394, FALSE, 'Desierto del Sahara', 'B', 99),
       (395, FALSE, 'Desierto de Atacama', 'C', 99),
       (396, FALSE, 'Desierto de Gobi', 'D', 99),

       (397, TRUE, 'La Paz', 'A', 100),
       (398, FALSE, 'Quito', 'B', 100),
       (399, FALSE, 'Bogotá', 'C', 100),
       (400, FALSE, 'Katmandú', 'D', 100);
