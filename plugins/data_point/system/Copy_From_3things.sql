 
INSERT INTO Plugin_Data_Point(name,data,user_id,person_id, status) 
	SELECT '3things' AS name, CONCAT(answer_1,"\n", answer_2, "\n", answer_3), user_id, person_id, '1' AS status FROM Plugin_3things
	