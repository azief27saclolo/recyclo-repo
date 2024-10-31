CREATE DATABASE accounts;
USE accounts;

CREATE TABLE account_date_created (
    id INT AUTO_INCREMENT PRIMARY KEY,
    time_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    is_seller BOOLEAN NOT NULL DEFAULT FALSE,
    is_buyer BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE credentials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    date_createdID INT NOT NULL,
    FOREIGN KEY (date_createdID) REFERENCES account_date_created(id)
);

CREATE TABLE validation_requirements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    credentialsID INT NOT NULL,
    gov_id_proof BLOB NOT NULL,
    address_proof BLOB NOT NULL,
    business_registration_doc BLOB,
    business_license BLOB,
    FOREIGN KEY (credentialsID) REFERENCES credentials(id)
)