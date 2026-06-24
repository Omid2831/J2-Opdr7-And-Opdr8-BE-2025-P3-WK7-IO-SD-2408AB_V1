USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_get_instructor_vehicles;
DELIMITER $$

CREATE PROCEDURE sp_get_instructor_vehicles(IN p_instructor_id INT)
BEGIN
    SELECT
        v.id,
        v.instructor_id,
        v.vehicle_type,
        v.vehicle_model,
        v.license_plate,
        v.build_year,
        v.fuel_type,
        v.license_category
    FROM vehicles v
    INNER JOIN users u ON v.instructor_id = u.id
    LEFT JOIN Instructeur i ON u.mobile = i.Mobiel
    WHERE v.instructor_id = p_instructor_id
      AND COALESCE(i.IsActief, 1) = 1
      AND NOT EXISTS (
          SELECT 1
          FROM VoertuigInstructeur vi
          INNER JOIN Voertuig vg ON vi.VoertuigId = vg.Id
          WHERE vg.Kenteken = v.license_plate
            AND vi.IsActief = 1
            AND vi.InstructeurId != i.Id
      )
    ORDER BY v.license_category ASC, v.vehicle_model ASC;
END $$

DELIMITER ;
