DO
$body$
BEGIN
   IF NOT EXISTS (
      SELECT *
      FROM   pg_catalog.pg_user
      WHERE  usename = 'mediatok'
   )
   THEN
      CREATE ROLE mediatok SUPERUSER LOGIN;
   END IF;
END
$body$;
