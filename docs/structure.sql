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
    FOREIGN KEY (id_doctor) REFERENCES doctors(id) ON DELETE CASCADE
);

CREATE TABLE appointments(
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_patient INT,
    id_doctor INT,
    status ENUM('pending','confirmed','cancelled','terminate') DEFAULT 'pending',
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
);
-- Specialities
INSERT INTO specialities (name, description) VALUES
('Cardiologie', 'Maladies du coeur'),
('Dermatologie', 'Maladies de la peau'),
('Pédiatrie', 'Soins des enfants');

-- Users
INSERT INTO users (firstname, lastname, email, password, phone, role) VALUES
('Admin', 'System', 'admin@medflow.com', '123456', '0600000000', 'admin'),
('Ahmed', 'Benali', 'ahmed.doctor@medflow.com', '123456', '0611111111', 'doctor'),
('Sara', 'Alaoui', 'sara.doctor@medflow.com', '123456', '0622222222', 'doctor'),
('Youssef', 'Karimi', 'youssef.patient@medflow.com', '123456', '0633333333', 'patient'),
('Fatima', 'Zahra', 'fatima.patient@medflow.com', '123456', '0644444444', 'patient');

-- Doctors
INSERT INTO doctors (id_user, id_speciality, is_active) VALUES
(2, 1, true),
(3, 2, true);

-- Timeslots
INSERT INTO timeslots (start_time, end_time, is_available, id_doctor) VALUES
('2026-06-10 09:00:00', '2026-06-10 09:30:00', true, 2),
('2026-06-10 10:00:00', '2026-06-10 10:30:00', true, 2),
('2026-06-10 14:00:00', '2026-06-10 14:30:00', true, 3);

-- Appointments
INSERT INTO appointments (id_patient, id_doctor, status, id_timeslot) VALUES
(4, 2, 'confirmed', 1),
(5, 3, 'pending', 3);

-- Prescriptions
INSERT INTO prescriptions (description, id_appointment) VALUES
('Paracétamol 500mg - 3 fois par jour pendant 5 jours', 1);
SELECT users.firstname , users.lastname , appointments.status , timeslots.end_time , timeslots.start_time    from appointments 
join users on appointments.id_patient=users.id
join timeslots on appointments.id_timeslot = timeslots.id
WHERE id_doctor=2;
SELECT users.firstname , users.lastname , appointments.status  from appointments 
join users on appointments.id_patient=users.id
WHERE id_doctor=2;
INSERT INTO timeslots (start_time, end_time, is_available, id_doctor) VALUES
('2026-06-15 09:00:00', '2026-06-15 09:30:00', false, 2), -- Ghadi n-rbtouh b rdv Confirmed
('2026-06-15 10:00:00', '2026-06-15 10:30:00', false, 2), -- Ghadi n-rbtouh b rdv Pending
('2026-06-15 11:00:00', '2026-06-15 11:30:00', false, 2), -- Ghadi n-rbtouh b rdv Terminate (Clôturée)
('2026-06-15 15:00:00', '2026-06-15 15:30:00', true, 2);  -- Hada ba9i khawi (Disponible)
INSERT INTO appointments (id_patient, id_doctor, status, id_timeslot) VALUES
(4, 2, 'pending', 5),
(5, 2, 'confirmed', 4),
(4, 2, 'terminate', 6);
INSERT INTO prescriptions (description, id_appointment) VALUES
('Amoxicilline 1g - 2 fois par jour pendant 7 jours. Doliprane 1g si douleur.', 4);
ALTER TABLE timeslots ADD status VARCHAR(50) DEFAULT 'disponible';
)
