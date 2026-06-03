# MedFlow - Application de Gestion de Clinique Médicale

## 📌 Présentation du projet

MedFlow est une plateforme web de gestion de clinique médicale inspirée de Doctolib. Elle permet aux patients de rechercher des médecins, réserver des rendez-vous et consulter leur historique médical. Les médecins peuvent gérer leur planning, confirmer les rendez-vous et rédiger des ordonnances. Les administrateurs disposent d'un espace de gestion des médecins, des spécialités et des statistiques globales de la clinique.

---

## 🎯 Objectifs

* Faciliter la prise de rendez-vous médicaux.
* Optimiser la gestion des consultations.
* Assurer un suivi efficace des patients.
* Mettre en place un système sécurisé basé sur les rôles utilisateurs (RBAC).
* Appliquer les principes de la Programmation Orientée Objet en PHP 8.

---

## 👥 Acteurs

### Patient

* Rechercher un médecin.
* Consulter les créneaux disponibles.
* Réserver un rendez-vous.
* Consulter son historique.
* Télécharger ses ordonnances.

### Médecin

* Consulter son planning.
* Valider ou annuler les rendez-vous.
* Clôturer une consultation.
* Rédiger une ordonnance.

### Administrateur

* Gérer les médecins.
* Gérer les spécialités.
* Consulter les statistiques de la clinique.

---

# 🛠 Technologies utilisées

* PHP 8
* MySQL
* PDO
* HTML5
* CSS3
* Bootstrap / Tailwind CSS
* JavaScript
* Git & GitHub
* UML

---

# 📂 Architecture du projet

```text
medflow/
├── config/
│   ├── database.php
│   └── security.php
│
├── public/
│   ├── css/
│   ├── js/
│   └── index.php
│
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Enum/
│   ├── Middleware/
│   └── Repository/
│
├── templates/
│   ├── admin/
│   ├── doctor/
│   ├── patient/
│   ├── auth/
│   └── layout/
│
├── .env
└── README.md
```

---

# 📊 Base de données

## Table users

| Champ     | Type    |
| --------- | ------- |
| id        | INT     |
| firstname | VARCHAR |
| lastname  | VARCHAR |
| email     | VARCHAR |
| password  | VARCHAR |
| phone     | VARCHAR |
| role      | ENUM    |

## Table specialities

| Champ       | Type    |
| ----------- | ------- |
| id          | INT     |
| name        | VARCHAR |
| description | VARCHAR |

## Table doctors

| Champ         | Type    |
| ------------- | ------- |
| id            | INT     |
| id_user       | INT     |
| id_speciality | INT     |
| is_active     | BOOLEAN |

## Table timeslots

| Champ        | Type     |
| ------------ | -------- |
| id           | INT      |
| start_time   | DATETIME |
| end_time     | DATETIME |
| is_available | BOOLEAN  |
| id_doctor    | INT      |

## Table appointments

| Champ       | Type |
| ----------- | ---- |
| id          | INT  |
| id_patient  | INT  |
| id_doctor   | INT  |
| status      | ENUM |
| id_timeslot | INT  |

## Table prescriptions

| Champ          | Type |
| -------------- | ---- |
| id             | INT  |
| description    | TEXT |
| id_appointment | INT  |

---

# 🔐 Gestion des rôles

## Patient

* Réservation des rendez-vous.
* Consultation de l'historique.

## Médecin

* Gestion des rendez-vous.
* Gestion des ordonnances.

## Administrateur

* Gestion des médecins.
* Gestion des spécialités.
* Consultation des statistiques.

---

# 🔄 Cycle de vie d'un rendez-vous

```text
EN_ATTENTE
     ↓
CONFIRME
     ↓
TERMINE

OU

EN_ATTENTE
     ↓
ANNULE
```

---

# 📋 User Stories

## Épic 1 : Espace Patient

* Recherche de médecin par spécialité.
* Consultation des créneaux.
* Réservation d'un rendez-vous.
* Historique des consultations.

## Épic 2 : Espace Médecin

* Visualisation du planning.
* Validation des rendez-vous.
* Annulation des rendez-vous.
* Création des ordonnances.

## Épic 3 : Administration

* CRUD Médecins.
* CRUD Spécialités.
* Dashboard statistiques.

---

# 📈 Statistiques Administrateur

* Nombre total des médecins.
* Nombre total des patients.
* Nombre total des rendez-vous.
* Taux d'annulation.
* Nombre de consultations terminées par médecin.

---

# 🧩 Diagramme Use Case

(Insérer ici l'image du diagramme Use Case)

---

# 🧩 Diagramme de Classes

(Insérer ici l'image du diagramme de Classes)

---

# 🧩 Diagramme ERD

(Insérer ici l'image du diagramme ERD)

---

# 🚀 Installation

## Cloner le projet

```bash
git clone https://github.com/votre-repository/medflow.git
```

## Accéder au projet

```bash
cd medflow
```

## Importer la base de données

```sql
CREATE DATABASE medflow;
```

Importer ensuite le script SQL fourni.

## Configurer la connexion

Modifier le fichier :

```php
config/database.php
```

## Lancer le serveur

```bash
php -S localhost:8000 -t public
```

---

# 👨‍💻 Équipe du projet

* Membre 1 : Authentification & Sécurité
* Membre 2 : Espace Patient
* Membre 3 : Espace Médecin
* Membre 4 : Administration & Base de données

---

# 📄 Licence

Projet réalisé dans le cadre de la formation Développeur Web et Web Mobile.
