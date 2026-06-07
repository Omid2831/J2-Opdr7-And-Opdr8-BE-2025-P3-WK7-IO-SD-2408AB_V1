
USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_get_all_vehicles;
DELIMITER $$

CREATE PROCEDURE sp_get_all_vehicles()
BEGIN
    SELECT
        v.id AS vehicle_id,
        v.instructor_id,
        v.vehicle_type AS vehicle_type,
        v.vehicle_model AS vehicle_model,
        v.license_plate AS license_plate,
        v.build_year,
        v.fuel_type AS fuel_type,
        v.license_category AS license_category,
        u.first_name AS first_name,
        u.tussenvoegsel AS tussenvoegsel,
        u.last_name AS last_name,
        'assigned' AS source
    FROM vehicles v
    LEFT JOIN users u ON v.instructor_id = u.id

    UNION ALL

    SELECT
        NULL AS vehicle_id,
        NULL AS instructor_id,
        tv.TypeVoertuig AS vehicle_type,
        v.Type AS vehicle_model,
        v.Kenteken AS license_plate,
        YEAR(v.Bouwjaar) AS build_year,
        v.Brandstof AS fuel_type,
        tv.Rijbewijscategorie AS license_category,
        NULL AS first_name,
        NULL AS tussenvoegsel,
        NULL AS last_name,
        'unassigned' AS source
    FROM Voertuig v
    INNER JOIN TypeVoertuig tv ON v.TypeVoertuigId = tv.Id
    WHERE v.IsActief = 1
      AND v.Id NOT IN (
          SELECT VoertuigId
          FROM VoertuigInstructeur
          WHERE IsActief = 1
      )

    ORDER BY build_year DESC, last_name DESC;
END $$

DELIMITER ;
