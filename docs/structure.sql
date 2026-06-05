-- Active: 1777028296278@@127.0.0.1@3306@medflow
CREATE DATABASE medflow;
USE medflow;

CREATE TABLE specialities(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(100) NOT NULL
);

CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(100) NOT NULL,
    role ENUM('patient','doctor','admin') NOT NULL
);

CREATE TABLE doctors(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    id_speciality INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,

    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_speciality) REFERENCES specialities(id)
);

CREATE TABLE timeslots(
    id INT PRIMARY KEY AUTO_INCREMENT,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    id_doctor INT NOT NULL,
    FOREIGN KEY (id_doctor) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE appointments(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_patient INT NULL,
    id_doctor INT NULL,
    id_timeslot INT NULL,

    status ENUM(
        'pending',
        'confirmed',
        'cancelled',
        'terminated'
    ) DEFAULT 'pending',

    FOREIGN KEY (id_patient) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (id_doctor) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (id_timeslot) REFERENCES timeslots(id) ON DELETE SET NULL
);

CREATE TABLE prescriptions(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    id_appointment INT NOT NULL,

    FOREIGN KEY (id_appointment) REFERENCES appointments(id) ON DELETE CASCADE
);


INSERT INTO specialities (name, description) VALUES
('Cardiologie', 'Heart specialist'),
('Dermatologie', 'Skin specialist'),
('Pédiatrie', 'Children specialist');

-- USERS
INSERT INTO users (firstname, lastname, email, password, phone, role) VALUES
('Admin', 'System', 'admin@med.com', '123456', '0600000000', 'admin'),
('John', 'Doe', 'doctor1@med.com', '123456', '0611111111', 'doctor'),
('Sara', 'Ali', 'patient1@med.com', '123456', '0622222222', 'patient');

-- DOCTORS
INSERT INTO doctors (id_user, id_speciality, is_active) VALUES
(2, 1, TRUE);

-- TIMESLOTS
INSERT INTO timeslots (start_time, end_time, is_available, id_doctor) VALUES
('2026-06-10 09:00:00', '2026-06-10 09:30:00', TRUE, 2),
('2026-06-10 10:00:00', '2026-06-10 10:30:00', TRUE, 2);

-- APPOINTMENTS
INSERT INTO appointments (id_patient, id_doctor, id_timeslot, status) VALUES
(3, 2, 1, 'pending');

-- PRESCRIPTIONS
INSERT INTO prescriptions (description, id_appointment) VALUES
('Take rest and drink water', 1);


