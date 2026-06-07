
USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_get_vehicle_by_id;
DELIMITER $$

CREATE PROCEDURE sp_get_vehicle_by_id(IN p_vehicle_id BIGINT)
BEGIN
    SELECT
        id,
        instructor_id,
        vehicle_type,
        vehicle_model,
        license_plate,
        build_year,
        fuel_type,
        license_category
    FROM vehicles
    WHERE id = p_vehicle_id;
END $$

DELIMITER ;
