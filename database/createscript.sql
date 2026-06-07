-- DATABASE RESET
DROP DATABASE IF EXISTS J2_Opdr7_BE;
CREATE DATABASE IF NOT EXISTS J2_Opdr7_BE;
USE J2_Opdr7_BE;

-- TABEL: TypeVoertuig
DROP TABLE IF EXISTS TypeVoertuig;
CREATE TABLE IF NOT EXISTS TypeVoertuig (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    TypeVoertuig VARCHAR(50) NOT NULL,
    Rijbewijscategorie VARCHAR(5) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABEL: Voertuig
DROP TABLE IF EXISTS Voertuig;
CREATE TABLE IF NOT EXISTS Voertuig (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Kenteken VARCHAR(15) NOT NULL,
    Type VARCHAR(50) NOT NULL,
    Bouwjaar DATE NOT NULL,
    Brandstof VARCHAR(20) NOT NULL,
    TypeVoertuigId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (TypeVoertuigId) REFERENCES TypeVoertuig(Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABEL: Instructeur
DROP TABLE IF EXISTS Instructeur;
CREATE TABLE IF NOT EXISTS Instructeur (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Voornaam VARCHAR(50) NOT NULL,
    Tussenvoegsel VARCHAR(15) NULL,
    Achternaam VARCHAR(50) NOT NULL,
    Mobiel VARCHAR(15) NOT NULL,
    DatumInDienst DATE NOT NULL,
    AantalSterren INT NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABEL: VoertuigInstructeur
DROP TABLE IF EXISTS VoertuigInstructeur;
CREATE TABLE IF NOT EXISTS VoertuigInstructeur (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    VoertuigId INT UNSIGNED NOT NULL,
    InstructeurId INT UNSIGNED NOT NULL,
    DatumToekenning DATE NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(255) NULL,
    DatumAangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DatumGewijzigd DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (VoertuigId) REFERENCES Voertuig(Id),
    FOREIGN KEY (InstructeurId) REFERENCES Instructeur(Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------- VOORBEELD INSERTS ---------
-- TypeVoertuig
INSERT INTO TypeVoertuig (Id, TypeVoertuig, Rijbewijscategorie, IsActief, Opmerking, DatumAangemaakt)
VALUES
(1, 'Personenauto', 'B', 1, NULL, NOW()),
(2, 'Vrachtwagen', 'C', 1, NULL, NOW()),
(3, 'Bus', 'D', 1, NULL, NOW()),
(4, 'Bromfiets', 'AM', 1, NULL, NOW());

-- Voertuig
INSERT INTO Voertuig (Id, Kenteken, Type, Bouwjaar, Brandstof, TypeVoertuigId, IsActief, Opmerking, DatumAangemaakt)
VALUES
(1, 'AU-67-IO', 'Golf', '2017-06-12', 'Diesel', 1, 1, NULL, NOW()),
(2, 'TR-24-OP', 'DAF', '2019-05-23', 'Diesel', 2, 1, NULL, NOW()),
(3, 'TH-78-KL', 'Mercedes', '2023-01-01', 'Benzine', 1, 1, NULL, NOW()),
(4, '90-KL-TR', 'Fiat 500', '2021-09-12', 'Benzine', 1, 1, NULL, NOW()),
(5, '34-TK-LP', 'Scania', '2015-03-13', 'Diesel', 2, 1, NULL, NOW()),
(6, 'YY-OP-78', 'BMW M5', '2022-05-13', 'Diesel', 1, 1, NULL, NOW()),
(7, 'UU-HH-JK', 'M.A.N', '2017-12-03', 'Diesel', 2, 1, NULL, NOW()),
(8, 'ST-FZ-28', 'Citroën', '2018-01-20', 'Elektrisch', 1, 1, NULL, NOW()),
(9, '123-FR-T', 'Piaggio ZIP', '2021-02-01', 'Benzine', 4, 1, NULL, NOW()),
(10, 'DRS-52-P', 'Vespa', '2022-03-21', 'Benzine', 4, 1, NULL, NOW()),
(11, 'STP-12-U', 'Kymco', '2022-07-02', 'Benzine', 4, 1, NULL, NOW()),
(12, '45-SD-23', 'Renault', '2023-01-01', 'Diesel', 3, 1, NULL, NOW());

-- Instructeur
INSERT INTO Instructeur (Id, Voornaam, Tussenvoegsel, Achternaam, Mobiel, DatumInDienst, AantalSterren, IsActief, Opmerking, DatumAangemaakt)
VALUES
(1, 'Li', NULL, 'Zhan', '06-28493827', '2015-04-17', 3, 1, NULL, NOW()),
(2, 'Leroy', NULL, 'Boerhaven', '06-39398734', '2018-06-25', 1, 1, NULL, NOW()),
(3, 'Yoeri', 'Van', 'Veen', '06-24383291', '2010-05-12', 3, 1, NULL, NOW()),
(4, 'Bert', 'Van', 'Sali', '06-48293823', '2023-01-10', 4, 1, NULL, NOW()),
(5, 'Mohammed', 'El', 'Yassidi', '06-34291234', '2010-06-14', 5, 1, NULL, NOW());

-- VoertuigInstructeur
INSERT INTO VoertuigInstructeur (Id, VoertuigId, InstructeurId, DatumToekenning, IsActief, Opmerking, DatumAangemaakt)
VALUES
(1, 1, 5, '2017-06-18', 1, NULL, NOW()),
(2, 3, 1, '2021-09-26', 1, NULL, NOW()),
(3, 9, 1, '2021-09-27', 1, NULL, NOW()),
(4, 4, 4, '2022-08-01', 1, NULL, NOW()),
(5, 5, 1, '2019-08-30', 1, NULL, NOW()),
(6, 10, 5, '2020-02-02', 1, NULL, NOW());
