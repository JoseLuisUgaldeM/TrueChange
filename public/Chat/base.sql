CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,           -- ID del usuario que envía
    receiver_id INT NOT NULL,         -- ID del usuario que recibe
    message_text TEXT NOT NULL,       -- Contenido del mensaje
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    -- Asegúrate de tener indices para sender_id y receiver_id
    INDEX (sender_id),
    INDEX (receiver_id)
);