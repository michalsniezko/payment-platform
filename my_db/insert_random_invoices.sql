create
    definer = root@`%` procedure insert_random_invoices()
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE rand_user_id INT;

    WHILE i < 50
        DO
            -- Losowo wybieramy user_id z istniejących
            SELECT id
            INTO rand_user_id
            FROM my_db.users
            ORDER BY RAND()
            LIMIT 1;

            -- Wstawiamy fakturę
            INSERT INTO my_db.invoices (amount, user_id, status)
            VALUES (ROUND(RAND() * 10000, 4),
                    rand_user_id,
                    FLOOR(RAND() * 3));

            SET i = i + 1;
        END WHILE;
END;

