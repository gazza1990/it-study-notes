--Trova Colonna in DB
Select * From <DATABASE_NAME>.INFORMATION_SCHEMA.COLUMNS
where COLUMN_NAME like '%<nome_colonna>%'