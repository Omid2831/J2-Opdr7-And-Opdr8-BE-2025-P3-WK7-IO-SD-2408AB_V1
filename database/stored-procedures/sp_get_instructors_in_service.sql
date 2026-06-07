USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_get_instructors_in_service;
DELIMITER $$

CREATE PROCEDURE sp_get_instructors_in_service()
BEGIN
    SELECT
        id,
        first_name,
        tussenvoegsel,
        last_name,
        mobile,
        datum_in_dienst,
        aantal_sterren
    FROM users
    WHERE role = 'instructeur'
    ORDER BY aantal_sterren DESC, last_name ASC, first_name ASC;
END $$

DELIMITER ;
