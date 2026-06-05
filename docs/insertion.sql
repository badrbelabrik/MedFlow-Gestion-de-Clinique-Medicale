INSERT INTO specialities (name, description) VALUES
                                                 ('Cardiologie', 'Maladies du coeur'),
                                                 ('Dermatologie', 'Maladies de la peau'),
                                                 ('Médecine générale', 'Soins généraux'),
                                                 ('Pédiatrie', 'Soins des enfants');

-- Admin
INSERT INTO users (firstname, lastname, email, password, phone, role)
VALUES ('Admin', 'System', 'admin@medflow.com', 'admin123', '0600000000', 'admin');

-- Doctors (no speciality here ❗)
INSERT INTO users (firstname, lastname, email, password, phone, role)
VALUES
    ('Gregory', 'House', 'house@medflow.com', '1234', '0611111111', 'doctor'),
    ('Stephen', 'Strange', 'strange@medflow.com', '1234', '0622222222', 'doctor'),
    ('John', 'Skinner', 'skinner@medflow.com', '1234', '0633333333', 'doctor');

-- Patients
INSERT INTO users (firstname, lastname, email, password, phone, role)
VALUES
    ('John', 'Doe', 'john@example.com', '1234', '0644444444', 'patient'),
    ('Jane', 'Smith', 'jane@example.com', '1234', '0655555555', 'patient');

INSERT INTO doctors (id_user, id_speciality, is_active) VALUES
                                                            (2, 3, true), -- House → Généraliste
                                                            (3, 1, true), -- Strange → Cardiologie
                                                            (4, 2, true); -- Skinner → Dermatologie


INSERT INTO timeslots (start_time, end_time, is_available, id_doctor) VALUES
-- House (id_user = 2)
('2026-06-03 09:00:00', '2026-06-03 09:30:00', true, 2),
('2026-06-03 09:30:00', '2026-06-03 10:00:00', true, 2),

-- Strange (id_user = 3)
('2026-06-03 10:00:00', '2026-06-03 10:30:00', true, 3),
('2026-06-03 10:30:00', '2026-06-03 11:00:00', true, 3),

-- Skinner (id_user = 4)
('2026-06-03 11:00:00', '2026-06-03 11:30:00', true, 4);


INSERT INTO appointments (id_patient, id_doctor, status, id_timeslot) VALUES
                                                                          (5, 2, 'pending', 1),
                                                                          (6, 3, 'confirmed', 3),
                                                                          (5, 4, 'cancelled', 5);


INSERT INTO prescriptions (description, id_appointment) VALUES
    ('Ibuprofen 400mg - 3 fois par jour pendant 3 jours', 2);