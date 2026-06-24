USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_get_reclaimable_vehicles;
DELIMITER $$

CREATE PROCEDURE sp_get_reclaimable_vehicles(
    IN p_instructeur_id INT UNSIGNED
)
BEGIN
    SELECT
        v.Id AS legacy_vehicle_id,
        tv.TypeVoertuig AS vehicle_type,
        v.Type AS vehicle_model,
        v.Kenteken AS license_plate,
        YEAR(v.Bouwjaar) AS build_year,
        v.Brandstof AS fuel_type,
        tv.Rijbewijscategorie AS license_category,
        i_cur.Voornaam AS current_instructor_first_name,
        i_cur.Tussenvoegsel AS current_instructor_tussenvoegsel,
        i_cur.Achternaam AS current_instructor_last_name
    FROM VoertuigInstructeur vi_orig
    INNER JOIN Voertuig v ON vi_orig.VoertuigId = v.Id AND v.IsActief = 1
    INNER JOIN TypeVoertuig tv ON v.TypeVoertuigId = tv.Id
    INNER JOIN VoertuigInstructeur vi_current
        ON vi_orig.VoertuigId = vi_current.VoertuigId
        AND vi_current.IsActief = 1
        AND vi_current.InstructeurId != vi_orig.InstructeurId
    INNER JOIN Instructeur i_cur ON vi_current.InstructeurId = i_cur.Id
    WHERE vi_orig.InstructeurId = p_instructeur_id
      AND vi_orig.IsActief = 0;
END $$

DELIMITER ;
