-- ============================================
-- TEST EMPLOI DU TEMPS - DIAGNOSTIC
-- ============================================

-- 1. Vérifier l'étudiant et sa classe
SELECT 
    u.id as user_id,
    u.name as student_name,
    u.user_type,
    sr.my_class_id,
    mc.name as class_name,
    sr.session
FROM users u
LEFT JOIN student_records sr ON u.id = sr.user_id
LEFT JOIN my_classes mc ON sr.my_class_id = mc.id
WHERE u.user_type = 'student'
AND u.name LIKE '%Bedi%'  -- Remplacez par le nom de l'étudiant
LIMIT 5;

-- 2. Vérifier les emplois du temps pour la classe
SELECT 
    ttr.id as ttr_id,
    ttr.name as timetable_name,
    ttr.my_class_id,
    mc.name as class_name,
    ttr.year,
    ttr.exam_id,
    COUNT(DISTINCT tt.id) as nombre_cours,
    COUNT(DISTINCT tt.day) as nombre_jours
FROM time_table_records ttr
JOIN my_classes mc ON ttr.my_class_id = mc.id
LEFT JOIN time_tables tt ON ttr.id = tt.ttr_id
WHERE mc.name = 'JSS 2'  -- Remplacez par le nom de la classe
GROUP BY ttr.id
ORDER BY ttr.created_at DESC;

-- 3. Vérifier TOUS les cours d'un emploi du temps spécifique
SELECT 
    tt.id,
    tt.ttr_id,
    tt.day as jour,
    ts.time_from as heure_debut,
    ts.time_to as heure_fin,
    ts.full as creneau_complet,
    s.name as matiere,
    tt.timestamp_from,
    tt.timestamp_to
FROM time_tables tt
JOIN time_slots ts ON tt.ts_id = ts.id
JOIN subjects s ON tt.subject_id = s.id
WHERE tt.ttr_id = 1  -- Remplacez par l'ID de l'emploi du temps
ORDER BY 
    FIELD(tt.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
    ts.timestamp_from;

-- 4. Compter les cours par jour pour un emploi du temps
SELECT 
    tt.day as jour,
    COUNT(*) as nombre_cours,
    GROUP_CONCAT(s.name ORDER BY ts.timestamp_from SEPARATOR ', ') as matieres
FROM time_tables tt
JOIN time_slots ts ON tt.ts_id = ts.id
JOIN subjects s ON tt.subject_id = s.id
WHERE tt.ttr_id = 1  -- Remplacez par l'ID de l'emploi du temps
GROUP BY tt.day
ORDER BY FIELD(tt.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

-- 5. Vérifier les créneaux horaires d'un emploi du temps
SELECT 
    ts.id,
    ts.ttr_id,
    ts.time_from,
    ts.time_to,
    ts.full,
    ts.timestamp_from,
    ts.timestamp_to
FROM time_slots ts
WHERE ts.ttr_id = 1  -- Remplacez par l'ID de l'emploi du temps
ORDER BY ts.timestamp_from;

-- 6. Vérifier s'il y a des cours sans time_slot ou sans subject
SELECT 
    tt.id,
    tt.ttr_id,
    tt.day,
    tt.ts_id,
    tt.subject_id,
    CASE 
        WHEN tt.ts_id IS NULL THEN 'MANQUE TIME_SLOT'
        WHEN tt.subject_id IS NULL THEN 'MANQUE SUBJECT'
        ELSE 'OK'
    END as statut
FROM time_tables tt
WHERE tt.ttr_id = 1  -- Remplacez par l'ID de l'emploi du temps
AND (tt.ts_id IS NULL OR tt.subject_id IS NULL);

-- 7. Vérifier la session actuelle dans les paramètres
SELECT * FROM settings WHERE type = 'current_session';

-- ============================================
-- INSTRUCTIONS D'UTILISATION
-- ============================================
-- 
-- 1. Ouvrez phpMyAdmin ou MySQL Workbench
-- 2. Sélectionnez votre base de données (probablement 'eschool')
-- 3. Exécutez les requêtes une par une
-- 4. Remplacez les valeurs suivantes:
--    - Nom de l'étudiant dans la requête 1
--    - Nom de la classe dans la requête 2
--    - ID de l'emploi du temps (ttr_id) dans les requêtes 3, 4, 5, 6
-- 
-- RÉSULTATS ATTENDUS:
-- 
-- Requête 1: L'étudiant doit avoir un my_class_id et une session
-- Requête 2: Il doit y avoir au moins 1 emploi du temps avec nombre_cours > 0
-- Requête 3: Doit afficher TOUS les cours (au moins 20-30)
-- Requête 4: Doit afficher au moins 5 jours (Monday à Friday)
-- Requête 5: Doit afficher au moins 5-6 créneaux horaires
-- Requête 6: Ne doit afficher AUCUN résultat (tous les cours doivent avoir time_slot et subject)
-- Requête 7: Doit afficher '2025-2026' (ou la session actuelle)
-- 
-- ============================================
