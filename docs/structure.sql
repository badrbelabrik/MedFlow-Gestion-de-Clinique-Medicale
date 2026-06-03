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