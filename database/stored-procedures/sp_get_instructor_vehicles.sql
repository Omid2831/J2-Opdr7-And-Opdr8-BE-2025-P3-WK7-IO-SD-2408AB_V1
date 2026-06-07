USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_get_instructor_vehicles;
DELIMITER $$

CREATE PROCEDURE sp_get_instructor_vehicles(IN p_instructor_id INT)
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
    WHERE instructor_id = p_instructor_id
    ORDER BY license_category ASC, vehicle_model ASC;
END $$

DELIMITER ;
