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
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    phone VARCHAR(100) NOT NULL,
    role ENUM('patient','doctor','admin')
);

CREATE TABLE doctors(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    id_speciality INT NOT NULL,
    is_active BOOL DEFAULT true,
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_speciality) REFERENCES specialities(id)
);

CREATE TABLE timeslots(
    id INT PRIMARY KEY AUTO_INCREMENT,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NOT NULL,
    is_available BOOL DEFAULT true,
    id_doctor INT NOT NULL,
    FOREIGN KEY (id_doctor) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE appointments(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_patient INT,
    id_doctor INT,
    status ENUM('pending','confirmed','cancelled','terminate'),
    id_timeslot INT,
    FOREIGN KEY (id_patient) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (id_doctor) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (id_timeslot) REFERENCES timeslots(id) ON DELETE SET NULL
);

CREATE TABLE prescriptions(
    id INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    id_appointment INT NOT NULL,
    FOREIGN KEY (id_appointment) REFERENCES appointments(id)
)