CREATE TABLE system_settings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    site_title VARCHAR(100) NOT NULL,
    contact_email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL
);

INSERT INTO system_settings (site_title, contact_email, phone_number) VALUES ('Mhark Photography', 'contact@mharkphotography.com', '123-456-7890');
