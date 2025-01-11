-- Dropping tables if they exist
DROP VIEW IF EXISTS vehiculesList;
DROP PROCEDURE IF EXISTS AddReservation;

DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS dislikes;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS ratings;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS places;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS types;
DROP TABLE IF EXISTS articles_tags;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS themes;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;


-- Creating tables
CREATE TABLE roles (
    id INT AUTO_INCREMENT,
    name ENUM('client', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT,
    first_name VARCHAR(25),
    last_name VARCHAR(25),
    email VARCHAR(255),
    password_hash VARCHAR(255),
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY(role_id) REFERENCES roles(id)
);

CREATE TABLE places (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE TABLE types (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE TABLE vehicles (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
    model VARCHAR(4),
    seats INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_name VARCHAR(255),
    type_id INT,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY(type_id) REFERENCES types(id),
    FOREIGN KEY(category_id) REFERENCES categories(id)
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT,
    from_date DATE NOT NULL,
    to_date DATE NOT NULL,
    place_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    client_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY(place_id) REFERENCES places(id),
    FOREIGN KEY(vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY(client_id) REFERENCES users(id)
);


CREATE TABLE ratings (
    id INT AUTO_INCREMENT,
    rate INT,
    vehicle_id INT NOT NULL,
    client_id INT NOT NULL,
    is_deleted BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY(vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY(client_id) REFERENCES users(id)
);


CREATE TABLE themes (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
    description VARCHAR(255),
    image_name VARCHAR(255),
    PRIMARY KEY(id)
);

CREATE TABLE articles (
    id INT AUTO_INCREMENT,
    title VARCHAR(255),
    content TEXT,
    image_name VARCHAR(255),
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    views INT DEFAULT 0,
    theme_id INT,
    client_id INT,
    PRIMARY KEY(id),
    FOREIGN KEY(theme_id) REFERENCES themes(id) ON DELETE CASCADE,
    FOREIGN KEY(client_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT,
    content TEXT,
    is_deleted BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_edited BOOLEAN DEFAULT FALSE,
    article_id INT,
    client_id INT,
    PRIMARY KEY(id),
    FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY(client_id) REFERENCES users(id)
);

CREATE TABLE favorites (
    article_id INT,
    client_id INT,
    FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY(client_id) REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY(article_id, client_id)
);

CREATE TABLE tags (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
    PRIMARY KEY(id)
);

CREATE TABLE articles_tags (
    id INT AUTO_INCREMENT,
    article_id INT,
    tag_id INT,
    FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY(tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY(id)
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT,
    article_id INT,
    client_id INT,
    FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY(client_id) REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY(id)
);

CREATE TABLE dislikes (
    id INT AUTO_INCREMENT,
    article_id INT,
    client_id INT,
    FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY(client_id) REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY(id)
);



CREATE VIEW vehiculesList AS
SELECT 
    v.id AS vehicle_id,
    v.name AS vehicle_name,
    v.model AS vehicle_model,
    v.seats AS vehicle_seats,
    v.price AS daily_price,
    c.name AS category_name,
    t.name AS vehicle_type,
    AVG(r.rate) AS average_rating,
    CASE WHEN EXISTS (
            SELECT *
            FROM reservations res
            WHERE res.vehicle_id = v.id 
              AND CURRENT_DATE BETWEEN res.from_date AND res.to_date
        )
        THEN 'Not Available'
        ELSE 'Available'
    END AS availability_status
FROM 
    vehicles v
JOIN categories c ON v.category_id = c.id
JOIN types t ON v.type_id = t.id
LEFT JOIN ratings r ON v.id = r.vehicle_id
GROUP BY 
    v.id;


CREATE PROCEDURE AddReservation (
    IN p_from_date DATE,
    IN p_to_date DATE,
    IN p_place_id INT,
    IN p_vehicle_id INT,
    IN p_client_id INT
)
BEGIN
    INSERT INTO reservations (from_date, to_date, place_id, vehicle_id, client_id, created_at)
    VALUES (p_from_date, p_to_date, p_place_id, p_vehicle_id, p_client_id, NOW());
END;

-- Seeding roles
INSERT INTO roles (name) VALUES
('client'),
('admin');

-- Seeding users (200 users, 100 active)
INSERT INTO users (first_name, last_name, email, password_hash, role_id, created_at) VALUES
    ('Ilyass', 'Anida', 'admin@example.com', '$2y$10$aAhj2MDgUweotrurnsCMGeh8PQJ26E0N2l2MnOxAUR5nxpUt3J5yu', 2, NOW()),
    ('Aya', 'El Mansouri', 'aya.elmansouri@example.com', 'hashed_password_1', 1, NOW() - INTERVAL 1 DAY),
    ('Youssef', 'Benali', 'youssef.benali@example.com', 'hashed_password_2', 1, NOW() - INTERVAL 2 DAY),
    ('Hana', 'Ait Baali', 'hana.aitbaali@example.com', 'hashed_password_3', 1, NOW() - INTERVAL 3 DAY),
    ('Omar', 'Idrissi', 'omar.idrissi@example.com', 'hashed_password_4', 1, NOW() - INTERVAL 4 DAY),
    ('Salma', 'El Hajji', 'salma.elhajji@example.com', 'hashed_password_5', 1, NOW() - INTERVAL 5 DAY),
    ('Reda', 'Bennani', 'reda.bennani@example.com', 'hashed_password_6', 1, NOW() - INTERVAL 6 DAY),
    ('Imane', 'Chakir', 'imane.chakir@example.com', 'hashed_password_7', 1, NOW() - INTERVAL 7 DAY),
    ('Kamal', 'Lamrani', 'kamal.lamrani@example.com', 'hashed_password_8', 1, NOW() - INTERVAL 8 DAY),
    ('Nadia', 'Zouhairi', 'nadia.zouhairi@example.com', 'hashed_password_9', 1, NOW() - INTERVAL 9 DAY),
    ('Karim', 'Fahim', 'karim.fahim@example.com', 'hashed_password_10', 1, NOW() - INTERVAL 10 DAY),
    ('Lina', 'Essadiki', 'lina.essadiki@example.com', 'hashed_password_11', 1, NOW() - INTERVAL 11 DAY),
    ('Amine', 'Radi', 'amine.radi@example.com', 'hashed_password_12', 1, NOW() - INTERVAL 12 DAY),
    ('Kenza', 'Alaoui', 'kenza.alaoui@example.com', 'hashed_password_13', 1, NOW() - INTERVAL 13 DAY),
    ('Othmane', 'Bahaji', 'othmane.bahaji@example.com', 'hashed_password_14', 1, NOW() - INTERVAL 14 DAY),
    ('Sarah', 'Elalami', 'sarah.elalami@example.com', 'hashed_password_15', 1, NOW() - INTERVAL 15 DAY),
    ('Hicham', 'Bensaid', 'hicham.bensaid@example.com', 'hashed_password_16', 1, NOW() - INTERVAL 16 DAY),
    ('Fatima', 'Ouali', 'fatima.ouali@example.com', 'hashed_password_17', 1, NOW() - INTERVAL 17 DAY),
    ('Mehdi', 'Sefrioui', 'mehdi.sefrioui@example.com', 'hashed_password_18', 1, NOW() - INTERVAL 18 DAY),
    ('Hind', 'Bouhaddou', 'hind.bouhaddou@example.com', 'hashed_password_19', 1, NOW() - INTERVAL 19 DAY),
    ('Ayoub', 'Tazi', 'ayoub.tazi@example.com', 'hashed_password_20', 1, NOW() - INTERVAL 20 DAY),
    ('Meryem', 'Bekkali', 'meryem.bekkali@example.com', 'hashed_password_21', 1, NOW() - INTERVAL 21 DAY),
    ('Zineb', 'Chraibi', 'zineb.chraibi@example.com', 'hashed_password_22', 1, NOW() - INTERVAL 22 DAY),
    ('Soufiane', 'Jabri', 'soufiane.jabri@example.com', 'hashed_password_23', 1, NOW() - INTERVAL 23 DAY),
    ('Aicha', 'Filali', 'aicha.filali@example.com', 'hashed_password_24', 1, NOW() - INTERVAL 24 DAY),
    ('Jalal', 'Haddad', 'jalal.haddad@example.com', 'hashed_password_25', 1, NOW() - INTERVAL 25 DAY),
    ('Ghita', 'Kettani', 'ghita.kettani@example.com', 'hashed_password_26', 1, NOW() - INTERVAL 26 DAY),
    ('Anas', 'Bouazza', 'anas.bouazza@example.com', 'hashed_password_27', 1, NOW() - INTERVAL 27 DAY),
    ('Safae', 'Benjelloun', 'safae.benjelloun@example.com', 'hashed_password_28', 1, NOW() - INTERVAL 28 DAY),
    ('Bilal', 'El Ghazi', 'bilal.elghazi@example.com', 'hashed_password_29', 1, NOW() - INTERVAL 29 DAY),
    ('Nihal', 'El Idrissi', 'nihal.elidrissi@example.com', 'hashed_password_30', 1, NOW() - INTERVAL 30 DAY),
    ('Khalid', 'Sbai', 'khalid.sbai@example.com', 'hashed_password_31', 1, NOW() - INTERVAL 31 DAY),
    ('Fouad', 'El Alami', 'fouad.elalami@example.com', 'hashed_password_32', 1, NOW() - INTERVAL 32 DAY),
    ('Laila', 'Moussaoui', 'laila.moussaoui@example.com', 'hashed_password_33', 1, NOW() - INTERVAL 33 DAY),
    ('Hamza', 'Ait Oubella', 'hamza.aitoubella@example.com', 'hashed_password_34', 1, NOW() - INTERVAL 34 DAY),
    ('Chaimaa', 'El Harrak', 'chaimaa.elharrak@example.com', 'hashed_password_35', 1, NOW() - INTERVAL 35 DAY),
    ('Yassine', 'Hassani', 'yassine.hassani@example.com', 'hashed_password_36', 1, NOW() - INTERVAL 36 DAY),
    ('Saloua', 'Tachfine', 'saloua.tachfine@example.com', 'hashed_password_37', 1, NOW() - INTERVAL 37 DAY),
    ('Abdelilah', 'Berrada', 'abdelilah.berrada@example.com', 'hashed_password_38', 1, NOW() - INTERVAL 38 DAY),
    ('Ihssane', 'Khattabi', 'ihssane.khattabi@example.com', 'hashed_password_39', 1, NOW() - INTERVAL 39 DAY),
    ('Tarik', 'Lamrani', 'tarik.lamrani@example.com', 'hashed_password_40', 1, NOW() - INTERVAL 40 DAY),
    ('Kaoutar', 'Rachidi', 'kaoutar.rachidi@example.com', 'hashed_password_41', 1, NOW() - INTERVAL 41 DAY),
    ('Zakaria', 'El Bouhali', 'zakaria.elbouhali@example.com', 'hashed_password_42', 1, NOW() - INTERVAL 42 DAY),
    ('Najat', 'Hammad', 'najat.hammad@example.com', 'hashed_password_43', 1, NOW() - INTERVAL 43 DAY),
    ('Souad', 'Fellous', 'souad.fellous@example.com', 'hashed_password_44', 1, NOW() - INTERVAL 44 DAY),
    ('Nabil', 'El Ghazi', 'nabil.elghazi@example.com', 'hashed_password_45', 1, NOW() - INTERVAL 45 DAY),
    ('Samira', 'Elkhaldi', 'samira.elkhaldi@example.com', 'hashed_password_46', 1, NOW() - INTERVAL 46 DAY),
    ('Imad', 'Taleb', 'imad.taleb@example.com', 'hashed_password_47', 1, NOW() - INTERVAL 47 DAY),
    ('Naima', 'Essakali', 'naima.essakali@example.com', 'hashed_password_48', 1, NOW() - INTERVAL 48 DAY),
    ('Fahd', 'Berrada', 'fahd.berrada@example.com', 'hashed_password_49', 1, NOW() - INTERVAL 49 DAY),
    ('Mounir', 'Ouahabi', 'mounir.ouahabi@example.com', 'hashed_password_50', 1, NOW() - INTERVAL 50 DAY),
    ('Hafsa', 'Benmoussa', 'hafsa.benmoussa@example.com', 'hashed_password_51', 1, NOW() - INTERVAL 51 DAY),
    ('Rachid', 'Boutahar', 'rachid.boutahar@example.com', 'hashed_password_52', 1, NOW() - INTERVAL 52 DAY),
    ('Hasnaa', 'El Alami', 'hasnaa.elalami@example.com', 'hashed_password_53', 1, NOW() - INTERVAL 53 DAY),
    ('Amina', 'Benkirane', 'amina.benkirane@example.com', 'hashed_password_54', 1, NOW() - INTERVAL 54 DAY),
    ('Ismail', 'Saidi', 'ismail.saidi@example.com', 'hashed_password_55', 1, NOW() - INTERVAL 55 DAY),
    ('Abir', 'Belkhayat', 'abir.belkhayat@example.com', 'hashed_password_56', 1, NOW() - INTERVAL 56 DAY),
    ('Younes', 'Chafik', 'younes.chafik@example.com', 'hashed_password_57', 1, NOW() - INTERVAL 57 DAY),
    ('Nora', 'Zouhri', 'nora.zouhri@example.com', 'hashed_password_58', 1, NOW() - INTERVAL 58 DAY),
    ('Anouar', 'Tazi', 'anouar.tazi@example.com', 'hashed_password_59', 1, NOW() - INTERVAL 59 DAY),
    ('Aya', 'Ghazali', 'aya.ghazali@example.com', 'hashed_password_60', 1, NOW() - INTERVAL 60 DAY),
    ('Mustapha', 'Rachidi', 'mustapha.rachidi@example.com', 'hashed_password_61', 1, NOW() - INTERVAL 61 DAY),
    ('Ilham', 'El Alaoui', 'ilham.elalaoui@example.com', 'hashed_password_62', 1, NOW() - INTERVAL 62 DAY),
    ('Omar', 'Kabbaj', 'omar.kabbaj@example.com', 'hashed_password_63', 1, NOW() - INTERVAL 63 DAY),
    ('Laila', 'Boukili', 'laila.boukili@example.com', 'hashed_password_64', 1, NOW() - INTERVAL 64 DAY),
    ('Badr', 'El Moussaoui', 'badr.elmoussaoui@example.com', 'hashed_password_65', 1, NOW() - INTERVAL 65 DAY),
    ('Sarah', 'El Idrissi', 'sarah.elidrissi@example.com', 'hashed_password_66', 1, NOW() - INTERVAL 66 DAY),
    ('Bilal', 'Moussaid', 'bilal.moussaid@example.com', 'hashed_password_67', 1, NOW() - INTERVAL 67 DAY),
    ('Nada', 'El Fahimi', 'nada.elfahimi@example.com', 'hashed_password_68', 1, NOW() - INTERVAL 68 DAY),
    ('Yassir', 'Naji', 'yassir.naji@example.com', 'hashed_password_69', 1, NOW() - INTERVAL 69 DAY),
    ('Chaima', 'Bouaziz', 'chaima.bouaziz@example.com', 'hashed_password_70', 1, NOW() - INTERVAL 70 DAY),
    ('Hassan', 'Ait Ali', 'hassan.aitali@example.com', 'hashed_password_71', 1, NOW() - INTERVAL 71 DAY),
    ('Zineb', 'El Amri', 'zineb.elamri@example.com', 'hashed_password_72', 1, NOW() - INTERVAL 72 DAY),
    ('Reda', 'Benkirane', 'reda.benkirane@example.com', 'hashed_password_73', 1, NOW() - INTERVAL 73 DAY),
    ('Imane', 'Moutawakil', 'imane.moutawakil@example.com', 'hashed_password_74', 1, NOW() - INTERVAL 74 DAY),
    ('Khalil', 'El Youssoufi', 'khalil.elyoussoufi@example.com', 'hashed_password_75', 1, NOW() - INTERVAL 75 DAY),
    ('Salma', 'El Hassani', 'salma.elhassani@example.com', 'hashed_password_76', 1, NOW() - INTERVAL 76 DAY),
     ('Nabil', 'El Alaoui', 'nabil.elalaoui@example.com', 'hashed_password_77', 1, NOW() - INTERVAL 77 DAY),
    ('Kenza', 'Berrada', 'kenza.berrada@example.com', 'hashed_password_78', 1, NOW() - INTERVAL 78 DAY),
    ('Othmane', 'Moutaib', 'othmane.moutaib@example.com', 'hashed_password_79', 1, NOW() - INTERVAL 79 DAY),
    ('Lina', 'El Kadi', 'lina.elkadi@example.com', 'hashed_password_80', 1, NOW() - INTERVAL 80 DAY),
     ('Anas', 'El Fassi', 'anas.elfassi@example.com', 'hashed_password_81', 1, NOW() - INTERVAL 81 DAY),
    ('Fatima', 'El Harrak', 'fatima.elharrak@example.com', 'hashed_password_82', 1, NOW() - INTERVAL 82 DAY),
    ('Youssef', 'El Gnaoui', 'youssef.elgnaoui@example.com', 'hashed_password_83', 1, NOW() - INTERVAL 83 DAY),
    ('Meryem', 'El Mouden', 'meryem.elmouden@example.com', 'hashed_password_84', 1, NOW() - INTERVAL 84 DAY),
    ('Rachid', 'El Bakkali', 'rachid.elbakkali@example.com', 'hashed_password_85', 1, NOW() - INTERVAL 85 DAY),
    ('Samira', 'El Ouali', 'samira.elouali@example.com', 'hashed_password_86', 1, NOW() - INTERVAL 86 DAY),
    ('Karim', 'Kabbaj', 'karim.kabbaj@example.com', 'hashed_password_87', 1, NOW() - INTERVAL 87 DAY),
    ('Leila', 'El Amrani', 'leila.elamrani@example.com', 'hashed_password_88', 1, NOW() - INTERVAL 88 DAY),
    ('Abdel', 'El Omari', 'abdel.elomari@example.com', 'hashed_password_89', 1, NOW() - INTERVAL 89 DAY),
    ('Nouhaila', 'El Messaoudi', 'nouhaila.elmessaoudi@example.com', 'hashed_password_90', 1, NOW() - INTERVAL 90 DAY),
     ('Mohamed', 'El Mahdi', 'mohamed.elmahdi@example.com', 'hashed_password_91', 1, NOW() - INTERVAL 91 DAY),
    ('Ilham', 'El Idrissi', 'ilham.elidrissi@example.com', 'hashed_password_92', 1, NOW() - INTERVAL 92 DAY),
    ('Adam', 'Bennani', 'adam.bennani@example.com', 'hashed_password_93', 1, NOW() - INTERVAL 93 DAY),
    ('Sanae', 'El Yousfi', 'sanae.elyousfi@example.com', 'hashed_password_94', 1, NOW() - INTERVAL 94 DAY),
    ('Fayssal', 'El Aissaoui', 'fayssal.elaissaoui@example.com', 'hashed_password_95', 1, NOW() - INTERVAL 95 DAY),
    ('Assia', 'El Ouafi', 'assia.elouafi@example.com', 'hashed_password_96', 1, NOW() - INTERVAL 96 DAY),
    ('Redwane', 'El Filali', 'redwane.elfilali@example.com', 'hashed_password_97', 1, NOW() - INTERVAL 97 DAY),
    ('Zouhair', 'El Harrak', 'zouhair.elharrak@example.com', 'hashed_password_98', 1, NOW() - INTERVAL 98 DAY),
    ('Saad', 'El Moudni', 'saad.elmoudni@example.com', 'hashed_password_99', 1, NOW() - INTERVAL 99 DAY),
    ('Amina', 'El Ouali', 'amina.elouali@example.com', 'hashed_password_100', 1, NOW() - INTERVAL 100 DAY),
    -- Inactive users
    ('Yassine', 'Ait Omar', 'yassine.aitomar@example.com', 'hashed_password_101', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Nadia', 'Benjelloun', 'nadia.benjelloun@example.com', 'hashed_password_102', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Hassan', 'Chakir', 'hassan.chakir@example.com', 'hashed_password_103', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Salima', 'El Amrani', 'salima.elamrani@example.com', 'hashed_password_104', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Rachid', 'Bennani', 'rachid.bennani@example.com', 'hashed_password_105', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Khadija', 'Fahim', 'khadija.fahim@example.com', 'hashed_password_106', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Brahim', 'Essakali', 'brahim.essakali@example.com', 'hashed_password_107', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Soukaina', 'Radi', 'soukaina.radi@example.com', 'hashed_password_108', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Amine', 'Lamrani', 'amine.lamrani@example.com', 'hashed_password_109', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Kenza', 'Zouhairi', 'kenza.zouhairi@example.com', 'hashed_password_110', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Othmane', 'Hassani', 'othmane.hassani@example.com', 'hashed_password_111', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Samira', 'Moutawakil', 'samira.moutawakil@example.com', 'hashed_password_112', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Karim', 'Ouahabi', 'karim.ouahabi@example.com', 'hashed_password_113', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Fatima', 'Taleb', 'fatima.taleb@example.com', 'hashed_password_114', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Mehdi', 'El Idrissi', 'mehdi.elidrissi@example.com', 'hashed_password_115', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Aicha', 'Berrada', 'aicha.berrada@example.com', 'hashed_password_116', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Younes', 'Bouhaddou', 'younes.bouhaddou@example.com', 'hashed_password_117', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Laila', 'Chafik', 'laila.chafik@example.com', 'hashed_password_118', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Fouad', 'Kabbaj', 'fouad.kabbaj@example.com', 'hashed_password_119', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Hind', 'Filali', 'hind.filali@example.com', 'hashed_password_120', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Tarik', 'Bakkali', 'tarik.bakkali@example.com', 'hashed_password_121', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Abdelilah', 'El Alaoui', 'abdelilah.elalaoui@example.com', 'hashed_password_122', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Imane', 'Ghazali', 'imane.ghazali@example.com', 'hashed_password_123', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Reda', 'Benmoussa', 'reda.benmoussa@example.com', 'hashed_password_124', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Zineb', 'Messaoudi', 'zineb.messaoudi@example.com', 'hashed_password_125', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Bilal', 'El Gnaoui', 'bilal.elgnaoui@example.com', 'hashed_password_126', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Ilham', 'Haddad', 'ilham.haddad@example.com', 'hashed_password_127', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Yassir', 'Hajji', 'yassir.hajji@example.com', 'hashed_password_128', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Chaimaa', 'Rachidi', 'chaimaa.rachidi@example.com', 'hashed_password_129', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Zakaria', 'El Harrak', 'zakaria.elharrak@example.com', 'hashed_password_130', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
     ('Kawtar', 'Boutahar', 'kawtar.boutahar@example.com', 'hashed_password_131', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Ayoub', 'Khattabi', 'ayoub.khattabi@example.com', 'hashed_password_132', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Hafsa', 'El Alaoui', 'hafsa.elalaoui@example.com', 'hashed_password_133', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Mounir', 'Berrada', 'mounir.berrada@example.com', 'hashed_password_134', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Saloua', 'Benkirane', 'saloua.benkirane@example.com', 'hashed_password_135', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Othmane', 'Filali', 'othmane.filali@example.com', 'hashed_password_136', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Lina', 'Ait Baali', 'lina.aitbaali@example.com', 'hashed_password_137', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Hassan', 'El Mansouri', 'hassan.elmansouri@example.com', 'hashed_password_138', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Leila', 'Essakali', 'leila.essakali@example.com', 'hashed_password_139', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Nadia', 'Moussaoui', 'nadia.moussaoui@example.com', 'hashed_password_140', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Nabil', 'El Ghazi', 'nabil.elghazi@example.com', 'hashed_password_141', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Fahd', 'Boutahar', 'fahd.boutahar@example.com', 'hashed_password_142', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Imad', 'Khattabi', 'imad.khattabi@example.com', 'hashed_password_143', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Kaoutar', 'El Harrak', 'kaoutar.elharrak@example.com', 'hashed_password_144', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Samira', 'Berrada', 'samira.berrada@example.com', 'hashed_password_145', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Yassine', 'Ait Omar', 'yassine.aitomar@example.com', 'hashed_password_146', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Amina', 'Benjelloun', 'amina.benjelloun@example.com', 'hashed_password_147', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Meryem', 'Chakir', 'meryem.chakir@example.com', 'hashed_password_148', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Soufiane', 'El Amrani', 'soufiane.elamrani@example.com', 'hashed_password_149', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	 ('Ghita', 'Bennani', 'ghita.bennani@example.com', 'hashed_password_150', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Anas', 'Fahim', 'anas.fahim@example.com', 'hashed_password_151', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Nihal', 'Essakali', 'nihal.essakali@example.com', 'hashed_password_152', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Ayoub', 'Radi', 'ayoub.radi@example.com', 'hashed_password_153', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Chaima', 'Lamrani', 'chaima.lamrani@example.com', 'hashed_password_154', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Bilal', 'Zouhairi', 'bilal.zouhairi@example.com', 'hashed_password_155', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Salma', 'Hassani', 'salma.hassani@example.com', 'hashed_password_156', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Adam', 'Moutawakil', 'adam.moutawakil@example.com', 'hashed_password_157', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Hafsa', 'Ouahabi', 'hafsa.ouahabi@example.com', 'hashed_password_158', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Khalid', 'Taleb', 'khalid.taleb@example.com', 'hashed_password_159', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Aicha', 'El Idrissi', 'aicha.elidrissi@example.com', 'hashed_password_160', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Mounir', 'Berrada', 'mounir.berrada@example.com', 'hashed_password_161', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Reda', 'Bouhaddou', 'reda.bouhaddou@example.com', 'hashed_password_162', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Imane', 'Chafik', 'imane.chafik@example.com', 'hashed_password_163', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Karim', 'Kabbaj', 'karim.kabbaj@example.com', 'hashed_password_164', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Souad', 'Filali', 'souad.filali@example.com', 'hashed_password_165', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Leila', 'El Alami', 'leila.elalami@example.com', 'hashed_password_166', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Youssef', 'El Amrani', 'youssef.elamrani@example.com', 'hashed_password_167', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Hicham', 'El Moussaoui', 'hicham.elmoussaoui@example.com', 'hashed_password_168', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Zineb', 'Ghazali', 'zineb.ghazali@example.com', 'hashed_password_169', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Nora', 'Bennani', 'nora.bennani@example.com', 'hashed_password_170', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Ilyass', 'El Ouali', 'ilyass.elouali@example.com', 'hashed_password_171', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Fouli', 'El Hassani', 'f.elhassani@example.com', 'hashed_password_172', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Doua', 'El Hassani', 'Doua.elhassani@example.com', 'hashed_password_173', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Mustapha', 'Chraibi', 'mustapha.chraibi@example.com', 'hashed_password_174', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Nada', 'El Bouhali', 'nada.elbouhali@example.com', 'hashed_password_175', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Othmane', 'Radi', 'othmane.radi@example.com', 'hashed_password_176', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Fati', 'Tazi', 'fati.tazi@example.com', 'hashed_password_177', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Abdelali', 'Ait Oubella', 'abdelali.aitoubella@example.com', 'hashed_password_178', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Salim', 'El Omari', 'salim.elomari@example.com', 'hashed_password_179', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Yassine', 'Hammad', 'yassine.hammad@example.com', 'hashed_password_180', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Aya', 'Jabri', 'aya.jabri@example.com', 'hashed_password_181', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Imane', 'Fellous', 'imane.fellous@example.com', 'hashed_password_182', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Reda', 'Moutaib', 'reda.moutaib@example.com', 'hashed_password_183', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Soufiane', 'Bensaid', 'soufiane.bensaid@example.com', 'hashed_password_184', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Lina', 'El Moussaoui', 'lina.elmoussaoui@example.com', 'hashed_password_185', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Nihal', 'Moussaid', 'nihal.moussaid@example.com', 'hashed_password_186', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Oussama', 'El Faraji', 'oussama.elfaraji@example.com', 'hashed_password_187', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Rachid', 'Kabbaj', 'rachid.kabbaj@example.com', 'hashed_password_188', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Nadia', 'El Alaoui', 'nadia.elalaoui@example.com', 'hashed_password_189', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Khalil', 'Bensaid', 'khalil.bensaid@example.com', 'hashed_password_190', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Hassan', 'El Amrani', 'hassan.elamrani@example.com', 'hashed_password_191', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Naima', 'Kettani', 'naima.kettani@example.com', 'hashed_password_192', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Meryem', 'Bennani', 'meryem.bennani@example.com', 'hashed_password_193', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Amina', 'Filali', 'amina.filali@example.com', 'hashed_password_194', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Soukaina', 'Hassani', 'soukaina.hassani@example.com', 'hashed_password_195', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Anas', 'Bekkali', 'anas.bekkali@example.com', 'hashed_password_196', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Ikram', 'Moutawakil', 'ikram.moutawakil@example.com', 'hashed_password_197', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Bilal', 'Jabri', 'bilal.jabri@example.com', 'hashed_password_198', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
	('Hanane', 'El Idrissi', 'hanane.elidrissi@example.com', 'hashed_password_199', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY),
    ('Younes', 'Tazi', 'younes.tazi@example.com', 'hashed_password_200', 1, NOW() - INTERVAL FLOOR(RAND() * 200) DAY);

    
-- Seeding places (Moroccan cities)
INSERT INTO places (name) VALUES
('Casablanca'),
('Rabat'),
('Marrakech'),
('Fes'),
('Tangier'),
('Agadir'),
('Oujda'),
('Kenitra'),
('Tetouan'),
('Safi'),
('Meknes'),
('Nador'),
('El Jadida'),
('Essaouira'),
('Dakhla');

-- Seeding categories
INSERT INTO categories (name, created_at) VALUES
('SUV', NOW()),
('Sedan', NOW()),
('Hatchback', NOW()),
('Truck', NOW()),
('Minivan', NOW()),
('Convertible', NOW()),
('Sports Car', NOW()),
('Luxury Car', NOW()),
('Economy Car', NOW()),
('Off-Road Car', NOW());

-- Seeding types
INSERT INTO types (name, created_at) VALUES
('Gas', NOW()),
('Electric', NOW()),
('Hybrid', NOW());

-- Seeding vehicles
INSERT INTO vehicles (name, model, seats, price, type_id, category_id, image_name) VALUES
('Toyota RAV4', '2022', 5, 65.00, 1, 1, "Toyota RAV4.webp"),
('Honda Civic', '2023', 5, 55.00, 1, 2, "Honda Civic.jpg"),
('Volkswagen Golf', '2021', 5, 60.00, 1, 3, "Volkswagen Golf.webp"),
('Ford F-150', '2023', 6, 90.00, 1, 4, "Ford F-150.jpg"),
('Chrysler Pacifica', '2022', 7, 75.00, 1, 5, "Chrysler Pacifica.jpg"),
('BMW Z4', '2021', 2, 110.00, 1, 6, "BMW Z4.jpg"),
('Porsche 911', '2023', 4, 180.00, 1, 7, "Porsche 911.webp"),
('Mercedes-Benz S-Class', '2022', 5, 150.00, 1, 8, "Mercedes-Benz S-Class.jpg"),
('Kia Rio', '2023', 5, 45.00, 1, 9, "Kia Rio.webp"),
('Jeep Wrangler', '2023', 5, 85.00, 1, 10, "Jeep Wrangler.jpg"),
('Tesla Model 3', '2023', 5, 120.00, 2, 2, "Tesla Model 3.webp"),
('Nissan Leaf', '2022', 5, 50.00, 2, 3, "Nissan Leaf.jpg"),
('Rivian R1T', '2023', 5, 95.00, 2, 4, "Rivian R1T.jpeg"),
('Toyota Sienna', '2023', 8, 80.00, 3, 5, "Toyota Sienna.png"),
('Chevrolet Corvette', '2022', 2, 130.00, 1, 7, "Chevrolet Corvette.webp"),
('Audi A8', '2023', 5, 160.00, 1, 8, "Audi A8.jpg"),
('Hyundai i10', '2023', 4, 40.00, 1, 9, "Hyundai i10.jpg"),
('Land Rover Defender', '2023', 5, 100.00, 1, 10, "Land Rover Defender.webp"),
('Mazda MX-5', '2022', 2, 70.00, 1, 6, "Mazda MX-5.jpg"),
('Subaru Outback', '2023', 5, 70.00, 1, 1, "Subaru Outback.webp"),
('Ford Explorer', '2022', 7, 80.00, 1, 1, "Ford Explorer.webp"),
('Honda Accord', '2023', 5, 60.00, 1, 2, "Honda Accord.webp"),
('Volkswagen Passat', '2021', 5, 65.00, 1, 3, "Volkswagen Passat.jpg"),
('GMC Sierra', '2023', 6, 95.00, 1, 4, "GMC Sierra.webp"),
('Dodge Grand Caravan', '2022', 7, 80.00, 1, 5, "Dodge Grand Caravan.jpg"),
('Mercedes-Benz C-Class', '2021', 4, 100.00, 1, 8, "Mercedes-Benz C-Class.jpg"),
('Lexus ES', '2023', 5, 140.00, 1, 8, "Lexus ES.jpg"),
('BMW 3 Series', '2022', 5, 110.00, 1, 8, "BMW 3 Series.jpg"),
('Audi A4', '2023', 5, 120.00, 1, 8, "Audi A4.jpg"),
('Jaguar XF', '2021', 5, 130.00, 1, 8, "Jaguar XF.jpg"),
('Tesla Model Y', '2022', 5, 130.00, 2, 1, "Tesla Model Y.webp"),
('Hyundai Kona Electric', '2023', 5, 55.00, 2, 2, "Hyundai Kona Electric.jpg"),
('Volkswagen ID.4', '2021', 5, 60.00, 2, 3, "Volkswagen ID.4.webp"),
('Ford Mustang Mach-E', '2023', 5, 85.00, 2, 4, "Ford Mustang Mach-E.jpeg"),
('Chevrolet Bolt', '2022', 5, 50.00, 2, 5, "Chevrolet Bolt.webp");


-- Seeding reservations (200 reservations across 7 months)
-- Seeding reservations (200 reservations with static data)
INSERT INTO reservations (from_date, to_date, place_id, vehicle_id, client_id, created_at) VALUES
    ('2024-06-05', '2024-06-07', 1, 1, 1, '2024-06-05 10:00:00'),
    ('2024-06-10', '2024-06-12', 2, 2, 2, '2024-06-10 14:30:00'),
    ('2024-06-15', '2024-06-18', 3, 3, 3, '2024-06-15 16:00:00'),
    ('2024-06-20', '2024-06-22', 4, 4, 4, '2024-06-20 11:00:00'),
    ('2024-07-01', '2024-07-03', 5, 5, 5, '2024-07-01 09:00:00'),
    ('2024-07-05', '2024-07-08', 6, 6, 6, '2024-07-05 18:00:00'),
    ('2024-07-10', '2024-07-14', 7, 7, 7, '2024-07-10 12:00:00'),
    ('2024-07-15', '2024-07-17', 8, 8, 8, '2024-07-15 20:00:00'),
    ('2024-08-01', '2024-08-04', 9, 9, 9, '2024-08-01 10:00:00'),
    ('2024-08-05', '2024-08-08', 10, 10, 10, '2024-08-05 13:00:00'),
    ('2024-08-10', '2024-08-13', 11, 11, 11, '2024-08-10 08:00:00'),
    ('2024-08-15', '2024-08-16', 12, 12, 12, '2024-08-15 15:00:00'),
    ('2024-09-01', '2024-09-03', 13, 13, 13, '2024-09-01 17:00:00'),
    ('2024-09-05', '2024-09-07', 14, 14, 14, '2024-09-05 09:30:00'),
    ('2024-09-10', '2024-09-11', 15, 15, 15, '2024-09-10 21:00:00'),
    ('2024-09-15', '2024-09-18', 1, 16, 16, '2024-09-15 14:00:00'),
    ('2024-10-01', '2024-10-04', 2, 17, 17, '2024-10-01 12:00:00'),
    ('2024-10-05', '2024-10-07', 3, 18, 18, '2024-10-05 10:30:00'),
    ('2024-10-10', '2024-10-12', 4, 19, 19, '2024-10-10 13:00:00'),
    ('2024-10-15', '2024-10-17', 5, 20, 20, '2024-10-15 18:00:00'),
    ('2024-11-01', '2024-11-03', 6, 21, 21, '2024-11-01 11:30:00'),
    ('2024-11-05', '2024-11-07', 7, 22, 22, '2024-11-05 15:30:00'),
    ('2024-11-10', '2024-11-12', 8, 23, 23, '2024-11-10 17:00:00'),
    ('2024-11-15', '2024-11-16', 9, 24, 24, '2024-11-15 09:00:00'),
    ('2024-12-01', '2024-12-03', 10, 25, 25, '2024-12-01 13:00:00'),
    ('2024-12-05', '2024-12-06', 11, 26, 26, '2024-12-05 16:00:00'),
    ('2024-12-10', '2024-12-11', 12, 27, 27, '2024-12-10 12:00:00'),
    ('2024-12-15', '2024-12-16', 13, 28, 28, '2024-12-15 19:00:00'),
     ('2024-06-08', '2024-06-10', 14, 29, 29, '2024-06-08 11:00:00'),
    ('2024-06-12', '2024-06-14', 15, 30, 30, '2024-06-12 16:30:00'),
    ('2024-06-17', '2024-06-19', 1, 31, 1, '2024-06-17 10:00:00'),
    ('2024-06-22', '2024-06-24', 2, 32, 2, '2024-06-22 15:00:00'),
     ('2024-07-07', '2024-07-09', 3, 33, 3, '2024-07-07 12:30:00'),
    ('2024-07-12', '2024-07-13', 4, 34, 4, '2024-07-12 17:30:00'),
    ('2024-07-17', '2024-07-20', 5, 35, 5, '2024-07-17 21:00:00'),
     ('2024-08-04', '2024-08-06', 6, 1, 6, '2024-08-04 11:00:00'),
    ('2024-08-08', '2024-08-09', 7, 2, 7, '2024-08-08 18:00:00'),
     ('2024-08-12', '2024-08-14', 8, 3, 8, '2024-08-12 10:00:00'),
    ('2024-09-03', '2024-09-05', 9, 4, 9, '2024-09-03 15:00:00'),
    ('2024-09-07', '2024-09-09', 10, 5, 10, '2024-09-07 20:00:00'),
    ('2024-09-12', '2024-09-13', 11, 6, 11, '2024-09-12 12:30:00'),
    ('2024-10-03', '2024-10-04', 12, 7, 12, '2024-10-03 10:00:00'),
    ('2024-10-06', '2024-10-08', 13, 8, 13, '2024-10-06 14:00:00'),
     ('2024-10-12', '2024-10-14', 14, 9, 14, '2024-10-12 17:30:00'),
	('2024-11-03', '2024-11-05', 15, 10, 15, '2024-11-03 19:00:00'),
    ('2024-11-07', '2024-11-08', 1, 11, 16, '2024-11-07 20:00:00'),
    ('2024-11-12', '2024-11-13', 2, 12, 17, '2024-11-12 13:00:00'),
      ('2024-12-03', '2024-12-04', 3, 13, 18, '2024-12-03 16:00:00'),
    ('2024-12-07', '2024-12-08', 4, 14, 19, '2024-12-07 14:00:00'),
       (
        '2025-01-05', '2025-01-07', 5, 15, 20, '2025-01-04 10:00:00'
    ),
      (
        '2025-01-10', '2025-01-12', 6, 16, 21, '2025-01-01 14:30:00'
    ),
      (
        '2025-01-15', '2025-01-18', 7, 17, 22, '2024-12-30 16:00:00'
    ),
    (
        '2025-01-20', '2025-01-22', 8, 18, 23, '2025-01-02 11:00:00'
    ),
    ('2024-06-05', '2024-06-07', 1, 1, 2, '2024-06-05 10:00:00'),
    ('2024-06-10', '2024-06-12', 2, 2, 3, '2024-06-10 14:30:00'),
    ('2024-06-15', '2024-06-18', 3, 3, 4, '2024-06-15 16:00:00'),
    ('2024-06-20', '2024-06-22', 4, 4, 5, '2024-06-20 11:00:00'),
    ('2024-07-01', '2024-07-03', 5, 5, 6, '2024-07-01 09:00:00'),
    ('2024-07-05', '2024-07-08', 6, 6, 7, '2024-07-05 18:00:00'),
    ('2024-07-10', '2024-07-14', 7, 7, 8, '2024-07-10 12:00:00'),
    ('2024-07-15', '2024-07-17', 8, 8, 9, '2024-07-15 20:00:00'),
    ('2024-08-01', '2024-08-04', 9, 9, 10, '2024-08-01 10:00:00'),
    ('2024-08-05', '2024-08-08', 10, 10, 11, '2024-08-05 13:00:00'),
    ('2024-08-10', '2024-08-13', 11, 11, 12, '2024-08-10 08:00:00'),
    ('2024-08-15', '2024-08-16', 12, 12, 13, '2024-08-15 15:00:00'),
    ('2024-09-01', '2024-09-03', 13, 13, 14, '2024-09-01 17:00:00'),
    ('2024-09-05', '2024-09-07', 14, 14, 15, '2024-09-05 09:30:00'),
    ('2024-09-10', '2024-09-11', 15, 15, 16, '2024-09-10 21:00:00'),
    ('2024-09-15', '2024-09-18', 1, 16, 17, '2024-09-15 14:00:00'),
    ('2024-10-01', '2024-10-04', 2, 17, 18, '2024-10-01 12:00:00'),
    ('2024-10-05', '2024-10-07', 3, 18, 19, '2024-10-05 10:30:00'),
    ('2024-10-10', '2024-10-12', 4, 19, 20, '2024-10-10 13:00:00'),
    ('2024-10-15', '2024-10-17', 5, 20, 21, '2024-10-15 18:00:00'),
    ('2024-11-01', '2024-11-03', 6, 21, 22, '2024-11-01 11:30:00'),
    ('2024-11-05', '2024-11-07', 7, 22, 23, '2024-11-05 15:30:00'),
    ('2024-11-10', '2024-11-12', 8, 23, 24, '2024-11-10 17:00:00'),
    ('2024-11-15', '2024-11-16', 9, 24, 25, '2024-11-15 09:00:00'),
    ('2024-12-01', '2024-12-03', 10, 25, 26, '2024-12-01 13:00:00'),
    ('2024-12-05', '2024-12-06', 11, 26, 27, '2024-12-05 16:00:00'),
    ('2024-12-10', '2024-12-11', 12, 27, 28, '2024-12-10 12:00:00'),
    ('2024-12-15', '2024-12-16', 13, 28, 29, '2024-12-15 19:00:00'),
     ('2024-06-08', '2024-06-10', 14, 29, 30, '2024-06-08 11:00:00'),
    ('2024-06-12', '2024-06-14', 15, 30, 31, '2024-06-12 16:30:00'),
    ('2024-06-17', '2024-06-19', 1, 31, 32, '2024-06-17 10:00:00'),
    ('2024-06-22', '2024-06-24', 2, 32, 33, '2024-06-22 15:00:00'),
     ('2024-07-07', '2024-07-09', 3, 33, 34, '2024-07-07 12:30:00'),
    ('2024-07-12', '2024-07-13', 4, 34, 35, '2024-07-12 17:30:00'),
    ('2024-07-17', '2024-07-20', 5, 35, 36, '2024-07-17 21:00:00'),
     ('2024-08-04', '2024-08-06', 6, 1, 37, '2024-08-04 11:00:00'),
    ('2024-08-08', '2024-08-09', 7, 2, 38, '2024-08-08 18:00:00'),
     ('2024-08-12', '2024-08-14', 8, 3, 39, '2024-08-12 10:00:00'),
    ('2024-09-03', '2024-09-05', 9, 4, 40, '2024-09-03 15:00:00'),
    ('2024-09-07', '2024-09-09', 10, 5, 41, '2024-09-07 20:00:00'),
    ('2024-09-12', '2024-09-13', 11, 6, 42, '2024-09-12 12:30:00'),
    ('2024-10-03', '2024-10-04', 12, 7, 43, '2024-10-03 10:00:00'),
    ('2024-10-06', '2024-10-08', 13, 8, 44, '2024-10-06 14:00:00'),
     ('2024-10-12', '2024-10-14', 14, 9, 45, '2024-10-12 17:30:00'),
	('2024-11-03', '2024-11-05', 15, 10, 46, '2024-11-03 19:00:00'),
    ('2024-11-07', '2024-11-08', 1, 11, 47, '2024-11-07 20:00:00'),
    ('2024-11-12', '2024-11-13', 2, 12, 48, '2024-11-12 13:00:00'),
      ('2024-12-03', '2024-12-04', 3, 13, 49, '2024-12-03 16:00:00'),
    ('2024-12-07', '2024-12-08', 4, 14, 50, '2024-12-07 14:00:00'),
     (
        '2025-01-05', '2025-01-07', 5, 15, 51, '2024-12-20 10:00:00'
    ),
      (
        '2025-01-10', '2025-01-12', 6, 16, 52, '2024-01-10 14:30:00'
    ),
      (
        '2025-01-15', '2025-01-18', 7, 17, 53, '2024-09-15 16:00:00'
    ),
    (
        '2025-01-20', '2025-01-22', 8, 18, 54, '2024-12-20 11:00:00'
    ),
    ('2024-06-05', '2024-06-07', 1, 1, 55, '2024-06-05 10:00:00'),
    ('2024-06-10', '2024-06-12', 2, 2, 56, '2024-06-10 14:30:00'),
    ('2024-06-15', '2024-06-18', 3, 3, 57, '2024-06-15 16:00:00'),
    ('2024-06-20', '2024-06-22', 4, 4, 58, '2024-06-20 11:00:00'),
    ('2024-07-01', '2024-07-03', 5, 5, 59, '2024-07-01 09:00:00'),
    ('2024-07-05', '2024-07-08', 6, 6, 60, '2024-07-05 18:00:00'),
    ('2024-07-10', '2024-07-14', 7, 7, 61, '2024-07-10 12:00:00'),
    ('2024-07-15', '2024-07-17', 8, 8, 62, '2024-07-15 20:00:00'),
    ('2024-08-01', '2024-08-04', 9, 9, 63, '2024-08-01 10:00:00'),
    ('2024-08-05', '2024-08-08', 10, 10, 64, '2024-08-05 13:00:00'),
    ('2024-08-10', '2024-08-13', 11, 11, 65, '2024-08-10 08:00:00'),
    ('2024-08-15', '2024-08-16', 12, 12, 66, '2024-08-15 15:00:00'),
    ('2024-09-01', '2024-09-03', 13, 13, 67, '2024-09-01 17:00:00'),
    ('2024-09-05', '2024-09-07', 14, 14, 68, '2024-09-05 09:30:00'),
    ('2024-09-10', '2024-09-11', 15, 15, 69, '2024-09-10 21:00:00'),
    ('2024-09-15', '2024-09-18', 1, 16, 70, '2024-09-15 14:00:00'),
    ('2024-10-01', '2024-10-04', 2, 17, 71, '2024-10-01 12:00:00'),
    ('2024-10-05', '2024-10-07', 3, 18, 72, '2024-10-05 10:30:00'),
    ('2024-10-10', '2024-10-12', 4, 19, 73, '2024-10-10 13:00:00'),
    ('2024-10-15', '2024-10-17', 5, 20, 74, '2024-10-15 18:00:00'),
    ('2024-11-01', '2024-11-03', 6, 21, 75, '2024-11-01 11:30:00'),
    ('2024-11-05', '2024-11-07', 7, 22, 76, '2024-11-05 15:30:00'),
    ('2024-11-10', '2024-11-12', 8, 23, 77, '2024-11-10 17:00:00'),
    ('2024-11-15', '2024-11-16', 9, 24, 78, '2024-11-15 09:00:00'),
    ('2024-12-01', '2024-12-03', 10, 25, 79, '2024-12-01 13:00:00'),
    ('2024-12-05', '2024-12-06', 11, 26, 80, '2024-12-05 16:00:00'),
    ('2024-12-10', '2024-12-11', 12, 27, 81, '2024-12-10 12:00:00'),
    ('2024-12-15', '2024-12-16', 13, 28, 82, '2024-12-15 19:00:00'),
     ('2024-06-08', '2024-06-10', 14, 29, 83, '2024-06-08 11:00:00'),
    ('2024-06-12', '2024-06-14', 15, 30, 84, '2024-06-12 16:30:00'),
    ('2024-06-17', '2024-06-19', 1, 31, 85, '2024-06-17 10:00:00'),
    ('2024-06-22', '2024-06-24', 2, 32, 86, '2024-06-22 15:00:00'),
     ('2024-07-07', '2024-07-09', 3, 33, 87, '2024-07-07 12:30:00'),
    ('2024-07-12', '2024-07-13', 4, 34, 88, '2024-07-12 17:30:00');




-- Seeding ratings (200 ratings with static data before 2025-01-04)
INSERT INTO ratings (rate, vehicle_id, client_id, created_at) VALUES
    (4, 1, 1, '2024-07-05 11:00:00'),
    (5, 2, 2, '2024-07-10 15:00:00'),
    (3, 3, 3, '2024-07-15 17:00:00'),
    (4, 4, 4, '2024-07-20 09:30:00'),
    (5, 5, 5, '2024-08-01 11:30:00'),
    (3, 6, 6, '2024-08-05 19:00:00'),
    (4, 7, 7, '2024-08-10 13:00:00'),
    (5, 8, 8, '2024-08-15 21:00:00'),
    (3, 9, 9, '2024-09-01 12:00:00'),
    (4, 10, 10, '2024-09-05 14:00:00'),
    (5, 11, 11, '2024-09-10 10:00:00'),
    (3, 12, 12, '2024-09-15 16:00:00'),
     (4, 13, 13, '2024-10-01 18:00:00'),
    (5, 14, 14, '2024-10-05 09:30:00'),
    (3, 15, 15, '2024-10-10 22:00:00'),
    (4, 16, 16, '2024-10-15 14:30:00'),
    (5, 17, 17, '2024-11-01 13:00:00'),
    (3, 18, 18, '2024-11-05 11:00:00'),
    (4, 19, 19, '2024-11-10 14:00:00'),
    (5, 20, 20, '2024-11-15 17:00:00'),
     (4, 21, 21, '2024-12-01 14:00:00'),
    (5, 22, 22, '2024-12-05 17:00:00'),
    (3, 23, 23, '2024-12-10 12:00:00'),
    (4, 24, 24, '2024-12-15 13:00:00'),
    (5, 25, 25, '2024-12-20 14:00:00'),
    (3, 26, 26, '2024-12-25 16:00:00'),
    (4, 27, 27, '2024-12-30 21:00:00'),
    (5, 28, 28, '2024-12-31 15:00:00'),
     (4, 29, 29, '2024-07-08 12:00:00'),
    (5, 30, 30, '2024-07-12 18:00:00'),
    (3, 1, 31, '2024-07-17 11:30:00'),
    (4, 2, 32, '2024-07-22 16:00:00'),
    (5, 3, 33, '2024-08-07 14:00:00'),
    (3, 4, 34, '2024-08-12 20:00:00'),
     (4, 5, 35, '2024-08-17 10:00:00'),
    (5, 6, 36, '2024-09-04 13:00:00'),
    (3, 7, 37, '2024-09-08 15:00:00'),
    (4, 8, 38, '2024-09-12 18:00:00'),
     (5, 9, 39, '2024-10-03 10:30:00'),
    (3, 10, 40, '2024-10-07 14:30:00'),
    (4, 11, 41, '2024-10-12 12:00:00'),
    (5, 12, 42, '2024-11-01 17:00:00'),
    (3, 13, 43, '2024-11-06 16:30:00'),
    (4, 14, 44, '2024-11-12 11:30:00'),
     (5, 15, 45, '2024-12-01 17:00:00'),
    (3, 16, 46, '2024-12-05 12:00:00'),
    (4, 17, 47, '2024-12-10 20:00:00'),
    (5, 18, 48, '2024-12-15 13:00:00'),
    (3, 19, 49, '2024-12-20 11:00:00'),
    (4, 20, 50, '2024-12-28 17:00:00'),
     (5, 1, 51, '2024-07-05 16:00:00'),
    (3, 2, 52, '2024-07-10 11:00:00'),
    (4, 3, 53, '2024-07-15 19:00:00'),
    (5, 4, 54, '2024-07-20 14:00:00'),
    (3, 5, 55, '2024-08-01 08:30:00'),
    (4, 6, 56, '2024-08-05 11:30:00'),
    (5, 7, 57, '2024-08-10 20:30:00'),
    (3, 8, 58, '2024-08-15 10:00:00'),
    (4, 9, 59, '2024-09-01 17:00:00'),
    (5, 10, 60, '2024-09-05 22:00:00'),
    (3, 11, 61, '2024-09-10 12:30:00'),
    (4, 12, 62, '2024-09-15 11:00:00'),
     (5, 13, 63, '2024-10-01 13:00:00'),
    (3, 14, 64, '2024-10-05 16:00:00'),
    (4, 15, 65, '2024-10-10 19:00:00'),
    (5, 16, 66, '2024-10-15 11:00:00'),
    (3, 17, 67, '2024-11-01 13:00:00'),
    (4, 18, 68, '2024-11-05 18:00:00'),
     (5, 19, 69, '2024-11-10 10:00:00'),
    (3, 20, 70, '2024-11-15 13:00:00'),
     (4, 1, 71, '2024-12-01 16:30:00'),
    (5, 2, 72, '2024-12-05 20:00:00'),
    (3, 3, 73, '2024-12-10 11:30:00'),
    (4, 4, 74, '2024-12-15 10:00:00'),
    (5, 5, 75, '2024-07-05 12:00:00'),
    (3, 6, 76, '2024-07-10 16:00:00'),
    (4, 7, 77, '2024-07-15 19:00:00'),
    (5, 8, 78, '2024-07-20 11:00:00'),
    (3, 9, 79, '2024-08-01 13:30:00'),
    (4, 10, 80, '2024-08-05 10:00:00'),
    (5, 11, 81, '2024-08-10 14:30:00'),
    (3, 12, 82, '2024-08-15 10:00:00'),
    (4, 13, 83, '2024-09-01 15:00:00'),
    (5, 14, 84, '2024-09-05 13:00:00'),
    (3, 15, 85, '2024-09-10 18:00:00'),
    (4, 16, 86, '2024-09-15 17:00:00'),
    (5, 17, 87, '2024-10-01 11:00:00'),
    (3, 18, 88, '2024-10-05 15:30:00'),
    (4, 19, 89, '2024-10-10 11:00:00'),
    (5, 20, 90, '2024-10-15 18:30:00'),
    (3, 1, 91, '2024-11-01 12:00:00'),
    (4, 2, 92, '2024-11-05 17:00:00'),
    (5, 3, 93, '2024-11-10 10:00:00'),
    (3, 4, 94, '2024-11-15 16:00:00'),
    (4, 5, 95, '2024-12-01 15:00:00'),
    (5, 6, 96, '2024-12-05 11:00:00'),
    (3, 7, 97, '2024-12-10 10:00:00'),
    (4, 8, 98, '2024-12-15 18:00:00'),
     (5, 9, 99, '2025-01-01 14:00:00'),
    (3, 10, 100, '2025-01-04 10:00:00'),
    (4, 11, 1, '2024-06-05 11:00:00'),
    (5, 12, 2, '2024-06-10 15:00:00'),
    (3, 13, 3, '2024-06-15 17:00:00'),
    (4, 14, 4, '2024-06-20 09:30:00'),
    (5, 15, 5, '2024-07-01 11:30:00'),
    (3, 16, 6, '2024-07-05 19:00:00'),
    (4, 17, 7, '2024-07-10 13:00:00'),
    (5, 18, 8, '2024-07-15 21:00:00'),
    (3, 19, 9, '2024-08-01 12:00:00'),
    (4, 20, 10, '2024-08-05 14:00:00'),
    (5, 11, 11, '2024-08-10 10:00:00'),
    (3, 12, 12, '2024-08-15 16:00:00'),
     (4, 13, 13, '2024-09-01 18:00:00'),
    (5, 14, 14, '2024-09-05 09:30:00'),
    (3, 15, 15, '2024-09-10 22:00:00'),
    (4, 16, 16, '2024-09-15 14:30:00'),
    (5, 17, 17, '2024-10-01 13:00:00'),
    (3, 18, 18, '2024-10-05 11:00:00'),
    (4, 19, 19, '2024-10-10 14:00:00'),
    (5, 20, 20, '2024-10-15 17:00:00'),
     (4, 21, 21, '2024-11-01 14:00:00'),
    (5, 22, 22, '2024-11-05 17:00:00'),
    (3, 23, 23, '2024-11-10 12:00:00'),
    (4, 24, 24, '2024-11-15 13:00:00'),
    (5, 25, 25, '2024-12-01 14:00:00'),
    (3, 26, 26, '2024-12-05 16:00:00'),
    (4, 27, 27, '2024-12-10 21:00:00'),
    (5, 28, 28, '2024-12-15 15:00:00'),
     (4, 29, 29, '2024-07-08 12:00:00'),
    (5, 30, 30, '2024-07-12 18:00:00'),
    (3, 1, 31, '2024-07-17 11:30:00'),
    (4, 2, 32, '2024-07-22 16:00:00'),
    (5, 3, 33, '2024-08-07 14:00:00'),
    (3, 4, 34, '2024-08-12 20:00:00'),
     (4, 5, 35, '2024-08-17 10:00:00'),
    (5, 6, 36, '2024-09-04 13:00:00'),
    (3, 7, 37, '2024-09-08 15:00:00'),
    (4, 8, 38, '2024-09-12 18:00:00'),
     (5, 9, 39, '2024-10-03 10:30:00'),
    (3, 10, 40, '2024-10-07 14:30:00'),
    (4, 11, 41, '2024-10-12 12:00:00'),
    (5, 12, 42, '2024-11-01 17:00:00'),
    (3, 13, 43, '2024-11-06 16:30:00'),
    (4, 14, 44, '2024-11-12 11:30:00'),
     (5, 15, 45, '2024-12-01 17:00:00'),
    (3, 16, 46, '2024-12-05 12:00:00'),
    (4, 17, 47, '2024-12-10 20:00:00'),
    (5, 18, 48, '2024-12-15 13:00:00'),
    (3, 19, 49, '2024-12-20 11:00:00'),
    (4, 20, 50, '2024-12-28 17:00:00'),
     (5, 1, 51, '2024-07-05 16:00:00'),
    (3, 2, 52, '2024-07-10 11:00:00'),
    (4, 3, 53, '2024-07-15 19:00:00'),
    (5, 4, 54, '2024-07-20 14:00:00'),
    (3, 5, 55, '2024-08-01 08:30:00'),
    (4, 6, 56, '2024-08-05 11:30:00'),
    (5, 7, 57, '2024-08-10 20:30:00'),
    (3, 8, 58, '2024-08-15 10:00:00'),
    (4, 9, 59, '2024-09-01 17:00:00'),
    (5, 10, 60, '2024-09-05 22:00:00'),
    (3, 11, 61, '2024-09-10 12:30:00'),
    (4, 12, 62, '2024-09-15 11:00:00'),
     (5, 13, 63, '2024-10-01 13:00:00'),
    (3, 14, 64, '2024-10-05 16:00:00'),
    (4, 15, 65, '2024-10-10 19:00:00'),
    (5, 16, 66, '2024-10-15 11:00:00'),
    (3, 17, 67, '2024-11-01 13:00:00'),
    (4, 18, 68, '2024-11-05 18:00:00'),
     (5, 19, 69, '2024-11-10 10:00:00'),
    (3, 20, 70, '2024-11-15 13:00:00'),
     (4, 1, 71, '2024-12-01 16:30:00'),
    (5, 2, 72, '2024-12-05 20:00:00'),
    (3, 3, 73, '2024-12-10 11:30:00'),
    (4, 4, 74, '2024-12-15 10:00:00'),
    (5, 5, 75, '2024-07-05 12:00:00'),
    (3, 6, 76, '2024-07-10 16:00:00'),
    (4, 7, 77, '2024-07-15 19:00:00'),
    (5, 8, 78, '2024-07-20 11:00:00'),
    (3, 9, 79, '2024-08-01 13:30:00'),
    (4, 10, 80, '2024-08-05 10:00:00'),
    (5, 11, 81, '2024-08-10 14:30:00'),
    (3, 12, 82, '2024-08-15 10:00:00'),
    (4, 13, 83, '2024-09-01 15:00:00'),
    (5, 14, 84, '2024-09-05 13:00:00'),
    (3, 15, 85, '2024-09-10 18:00:00'),
    (4, 16, 86, '2024-09-15 17:00:00'),
    (5, 17, 87, '2024-10-01 11:00:00'),
    (3, 18, 88, '2024-10-05 15:30:00'),
    (4, 19, 89, '2024-10-10 11:00:00'),
    (5, 20, 90, '2024-10-15 18:30:00'),
    (3, 1, 91, '2024-11-01 12:00:00'),
    (4, 2, 92, '2024-11-05 17:00:00'),
    (5, 3, 93, '2024-11-10 10:00:00'),
    (3, 4, 94, '2024-11-15 16:00:00'),
    (4, 5, 95, '2024-12-01 15:00:00'),
    (5, 6, 96, '2024-12-05 11:00:00'),
    (3, 7, 97, '2024-12-10 10:00:00'),
    (4, 8, 98, '2024-12-15 18:00:00'),
     (5, 9, 99, '2025-01-01 14:00:00'),
    (3, 10, 100, '2025-01-04 10:00:00'),
    (4, 11, 1, '2024-07-05 11:00:00'),
    (5, 12, 2, '2024-07-10 15:00:00');



INSERT INTO themes (name, description) VALUES 
    ('Car Repair and Maintenance', 'Tips and guides for maintaining and repairing your vehicle to keep it running smoothly.'),
    ('High-Speed Vehicles', 'Exploring the world of fast cars, supercars, and the technology behind high-speed performance.'),
    ('Custom Car Modifications', 'Creative ways to personalize and upgrade your car for style and performance.'),
    ('Eco-Friendly Driving', 'Sustainable driving practices and eco-conscious vehicle choices for a greener future.'),
    ('Car Safety Features', 'Understanding modern safety technologies and how they protect drivers and passengers.'),
    ('Road Trips and Travel', 'Planning unforgettable road trips and exploring scenic routes around the world.'),
    ('Car Detailing and Cleaning', 'Expert advice on keeping your car spotless and maintaining its aesthetic appeal.'),
    ('Driving Techniques', 'Mastering advanced driving skills and techniques for a safer and more enjoyable ride.'),
    ('Car Audio Systems', 'Enhancing your driving experience with high-quality sound systems and audio upgrades.'),
    ('Vintage Car Restoration', 'Reviving classic cars to their former glory through restoration and preservation.');


INSERT INTO tags (name) VALUES 
('Engine Tuning'),
('Autonomous Vehicles'),
('Sports Cars'),
('Vintage Cars'),
('Car Interior Design'),
('Fuel Efficiency'),
('Car Batteries'),
('Driving Safety'),
('Car Insurance'),
('Car Financing'),
('Car Shows'),
('Car Photography'),
('Performance Upgrades'),
('Winter Driving'),
('Summer Driving'),
('Car Gadgets'),
('Car Paint Jobs'),
('Exhaust Systems'),
('Car Suspension'),
('Car Brakes'),
('Car Tires'),
('Car Navigation'),
('Car Lighting'),
('Car Alarms'),
('Car Storage'),
('Car Shipping'),
('Car Rentals'),
('Car History'),
('Car Museums'),
('Car Racing Events');


INSERT INTO articles (title, content, image_name, is_published, theme_id, client_id) VALUES
-- Articles for "Car Repair and Maintenance" (theme_id = 1)
('10 Essential Car Maintenance Tips', 'Learn the top 10 tips to keep your car in perfect condition and avoid costly repairs.', 'car_maintenance.jpg', TRUE, 1, 1),
('How to Change Your Cars Oil Like a Pro', 'A step-by-step guide to changing your cars oil and keeping your engine running smoothly.', 'oil_change.jpg', TRUE, 1, 2),
('Common Car Problems and How to Fix Them', 'Identify and troubleshoot common car issues with this handy guide.', 'car_problems.jpg', TRUE, 1, 3),

-- Articles for "High-Speed Vehicles" (theme_id = 2)
('The Future of High-Speed Electric Vehicles', 'Discover how electric vehicles are revolutionizing the world of high-speed performance.', 'high_speed_ev.jpg', TRUE, 2, 4),
('Top 5 Fastest Cars in the World in 2023', 'A look at the fastest production cars and what makes them so powerful.', 'fastest_cars.jpg', TRUE, 2, 5),

-- Articles for "Custom Car Modifications" (theme_id = 3)
('How to Customize Your Car on a Budget', 'Affordable ways to upgrade your cars appearance and performance without breaking the bank.', 'custom_car.jpg', TRUE, 3, 6),
('The Best Custom Modifications for Sports Cars', 'Explore the most popular modifications for enhancing sports car performance and style.', 'sports_car_mods.jpg', TRUE, 3, 7),

-- Articles for "Eco-Friendly Driving" (theme_id = 4)
('Eco-Friendly Driving: Tips for a Greener Commute', 'Simple changes you can make to reduce your carbon footprint while driving.', 'eco_driving.jpg', TRUE, 4, 8),
('The Benefits of Hybrid and Electric Vehicles', 'Why switching to a hybrid or electric car is good for the environment and your wallet.', 'hybrid_benefits.jpg', TRUE, 4, 9),

-- Articles for "Car Safety Features" (theme_id = 5)
('Top 5 Car Safety Features You Need in 2023', 'Explore the latest safety technologies that are making cars safer than ever.', 'car_safety.jpg', TRUE, 5, 10),
('How Adaptive Cruise Control Works', 'A deep dive into the technology behind adaptive cruise control and its benefits.', 'cruise_control.jpg', TRUE, 5, 1),

-- Articles for "Road Trips and Travel" (theme_id = 6)
('Planning the Ultimate Road Trip: A Step-by-Step Guide', 'Everything you need to know to plan an unforgettable road trip adventure.', 'road_trip.jpg', TRUE, 6, 2),
('Top 10 Scenic Drives in the United States', 'Discover the most breathtaking routes for your next road trip.', 'scenic_drives.jpg', TRUE, 6, 3),

-- Articles for "Car Detailing and Cleaning" (theme_id = 7)
('The Art of Car Detailing: Tips from the Pros', 'Expert advice on how to clean and detail your car like a professional.', 'car_detailing.jpg', TRUE, 7, 4),
('How to Remove Stains from Your Cars Interior', 'Effective methods for cleaning and maintaining your cars interior.', 'interior_cleaning.jpg', TRUE, 7, 5),

-- Articles for "Driving Techniques" (theme_id = 8)
('Mastering Advanced Driving Techniques', 'Improve your driving skills with these advanced techniques for a safer and smoother ride.', 'driving_techniques.jpg', TRUE, 8, 6),
('How to Drive Safely in Heavy Rain', 'Essential tips for staying safe on the road during heavy rainfall.', 'rain_driving.jpg', TRUE, 8, 7),

-- Articles for "Car Audio Systems" (theme_id = 9)
('Upgrade Your Car Audio System: A Beginners Guide', 'Everything you need to know to enhance your cars audio experience.', 'car_audio.jpg', TRUE, 9, 8),
('The Best Car Speakers for 2023', 'A review of the top car speakers to take your audio system to the next level.', 'car_speakers.jpg', TRUE, 9, 9),

-- Articles for "Vintage Car Restoration" (theme_id = 10)
('Restoring a Vintage Car: Where to Start', 'A beginners guide to restoring classic cars and bringing them back to life.', 'vintage_restoration.jpg', TRUE, 10, 10),
('Top Tools for Vintage Car Restoration', 'The must-have tools for anyone looking to restore a classic car.', 'restoration_tools.jpg', TRUE, 10, 1);


INSERT INTO likes(article_id, client_id) VALUES
(3, 4),
(3, 5),
(1, 4);

INSERT INTO dislikes(article_id, client_id) VALUES
(3, 3),
(3, 2),
(1, 12);

INSERT INTO comments (content, is_deleted, article_id, client_id, created_at) VALUES
('Great article! Really enjoyed reading about the new BMW M3.', FALSE, 1, 2, '2024-01-15 10:00:00'),
('I disagree with some points, but overall a good read.', FALSE, 1, 3, '2024-01-16 11:15:00'),
('This is exactly what I needed to know about car maintenance. Thanks!', FALSE, 2, 4, '2024-01-17 12:30:00'),
('The article on eco-friendly driving was very informative.', FALSE, 3, 5, '2024-01-18 13:45:00'),
('I think the author missed some key points about car safety.', FALSE, 4, 16, '2024-01-19 14:00:00'),
('Loved the tips on car detailing. My car looks brand new now!', FALSE, 5, 7, '2024-01-20 15:15:00'),
('The article on vintage car restoration inspired me to start my own project.', FALSE, 6, 8, '2024-01-21 16:30:00'),
('I found the section on driving techniques very helpful.', FALSE, 7, 9, '2024-01-22 17:45:00'),
('The audio system upgrade guide was spot on. Thanks for the recommendations!', FALSE, 8, 10, '2024-01-23 18:00:00'),
('This article on road trips has me planning my next adventure already.', FALSE, 9, 11, '2024-01-24 19:15:00'),
('I wish there were more details about custom car modifications.', FALSE, 10, 12, '2024-01-25 20:30:00'),
('The article on high-speed vehicles was thrilling to read.', FALSE, 1, 13, '2024-01-26 21:45:00'),
('I learned a lot about car batteries from this article. Thanks!', FALSE, 2, 14, '2024-01-27 22:00:00'),
('The winter driving tips were very timely. Much appreciated!', FALSE, 3, 15, '2024-01-28 23:15:00'),
('I disagree with the authors opinion on car insurance.', FALSE, 4, 38, '2024-01-29 10:30:00'),
('The article on car financing was very insightful.', FALSE, 5, 17, '2024-01-30 11:45:00'),
('I loved the section on car photography. Great tips!', FALSE, 6, 18, '2024-01-31 12:00:00'),
('The article on car paint jobs was very detailed and helpful.', FALSE, 7, 19, '2024-02-01 13:15:00'),
('I found the exhaust system guide very useful.', FALSE, 8, 20, '2024-02-02 14:30:00'),
('The article on car suspension was a bit technical but informative.', FALSE, 9, 21, '2024-02-03 15:45:00'),
('I wish there were more examples in the car brakes section.', FALSE, 10, 22, '2024-02-04 16:00:00'),
('The article on car tires was very practical. Thanks!', FALSE, 1, 23, '2024-02-05 17:15:00'),
('I enjoyed the car navigation guide. Very helpful for long trips.', FALSE, 2, 24, '2024-02-06 18:30:00');
