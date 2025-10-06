DROP TABLE IF EXISTS Bookings, Payments, Sessions, Trainers, Facilities, Members, Memberships;


CREATE TABLE Memberships (
    membership_id INT PRIMARY KEY,
    type VARCHAR(50),
    duration_months INT,
    cost DECIMAL(8,2)
);


CREATE TABLE Members (
    member_id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    dob DATE,
    membership_id INT,
    FOREIGN KEY (membership_id) REFERENCES Memberships(membership_id)
);

CREATE TABLE Payments (
    payment_id INT PRIMARY KEY,
    member_id INT,
    amount DECIMAL(8,2),
    payment_date DATE,
    FOREIGN KEY (member_id) REFERENCES Members(member_id)
);

CREATE TABLE Trainers (
    trainer_id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    specialty VARCHAR(100)
);


CREATE TABLE Facilities (
    facility_id INT PRIMARY KEY,
    name VARCHAR(100),
    location VARCHAR(100)
);


CREATE TABLE Sessions (
    session_id INT PRIMARY KEY,
    trainer_id INT,
    session_date DATE,
    facility_id INT,
    FOREIGN KEY (trainer_id) REFERENCES Trainers(trainer_id),
    FOREIGN KEY (facility_id) REFERENCES Facilities(facility_id)
);


CREATE TABLE Bookings (
    booking_id INT PRIMARY KEY,
    member_id INT,
    session_id INT,
    FOREIGN KEY (member_id) REFERENCES Members(member_id),
    FOREIGN KEY (session_id) REFERENCES Sessions(session_id)
);
