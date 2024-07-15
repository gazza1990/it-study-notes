--Trova Tabella da Nome Campo
select c.name as "nome colonna" ,s.name as "nome tabella"
from
sys.columns as c join sys.sysobjects as s
ON c.object_id=s.id
where c.name like '%<Nome Campo>%'