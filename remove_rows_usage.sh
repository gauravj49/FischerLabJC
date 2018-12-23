select id,paper_title from current_entries;
delete from current_entries where id in (114,115,116,117,118,119,120,121,122,123,124,125,126);

select id,paper_title,current from current_entries;
UPDATE current_entries SET current = 1 WHERE id = 113;


