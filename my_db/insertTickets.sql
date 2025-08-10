create
    definer = root@`%` procedure insertTickets()
BEGIN
    DECLARE i INT DEFAULT 1;

    WHILE i <= 20000
        DO
            INSERT INTO tickets (value)
            VALUES (CONCAT('Test ticket #', i));
            SET i = i + 1;
        END WHILE;
END;

