### Решение

1. Создаем временную траблицу, в которой мы отмечаем, является ли данное мероприятие уникальным или является копией?

1.1 Указываем в этой таблице `parent_session_id` который является правильным значением `session_id` для данного мероприятия, то есть для дубликатов `parent_session_id` отличается от `id`
2. По временной таблице изменяем значения в **session_members** на `parent_session_id`
3. Удаляем все дубликаты записей для **sessions** и **session_members**
   
```sql
-- Start transaction  
START TRANSACTION;

-- Create temporally table from "sessions" 
CREATE TEMPORARY TABLE sessions_temp AS SELECT * FROM sessions;

-- Add column to store parent_id 
ALTER TABLE sessions_temp ADD parent_id INTEGER(20);

-- Update "sessions_temp" table by adding `parent_id` column to all rows, adding parent_id
--   value for all rows as MIN(id) for rows with same `start_time` and `session_configuration_id`
UPDATE sessions_temp AS s_t SET parent_id=(
    SELECT MIN(id)
    FROM (SELECT * FROM sessions) as s
    GROUP BY s.start_time, s.session_configuration_id
    HAVING s_t.start_time = s.start_time AND s_t.session_configuration_id=s.session_configuration_id
);

-- Update table "session_members" to change `session_id` from current value to 
-- `parent_id` from table "sessions_temp"    
UPDATE session_members AS s_m SET session_id=(
    SELECT parent_id
    FROM sessions_temp
    WHERE id=s_m.session_id
);

-- Delete all duplicates rows from "sessions" and "session_members"
DELETE FROM sessions WHERE id IN (
    SELECT id FROM sessions_temp WHERE id <> parent_id
);

DELETE FROM `session_members` WHERE id NOT IN (
    SELECT * FROM (
                      SELECT MIN(id) as id FROM `session_members` as sm_ids GROUP BY session_id, client_id
                  ) AS s_m
);

-- Show "session_members" table 
SELECT * FROM session_members;

-- Show "sessions" table 
SELECT * FROM sessions;

-- Exec transaction
COMMIT;
```
