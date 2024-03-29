INSERT INTO gruppomuscolare (nome)
VALUES ("Polpacci"),("Femorali"),("Glutei"),("Quadricipiti"),("Core"),("Pettorali"),
("Deltoidi anteriori"),("Deltoidi posteriori"),("Spalle"),("Trapezi"),("Bicipiti"),
("Tricipiti"),("Lombari"),("Gran dorsale")

INSERT INTO esercizio (Nome,MinRep,MaxRep,Tipologia)
VALUES ("Air Squat",5,50,0),("Mountain Climbers",10,100,0),("Push Up",5,20,0),("Pull Up",2,15,0),
("Ponte Glutei",10,25,0),("Sit Up",10,30,0),("Pike Push Up",5,15,0),("Front Squat",5,15,1),
("Bench Press",5,15,1),("Military Press",5,15,1),("Curl Bicipiti",5,15,1)

INSERT INTO `appartenenza` (`IdAppartenenza`, `Esercizio`, `GruppoMuscolare`)
VALUES (NULL, '1', '3'), (NULL, '1', '4'), (NULL, '1', '2'),
(NULL, '2', '5'), (NULL, '2', '2'), (NULL, '2', '3'), (NULL, '2', '4'),
(NULL, '2', '6'), (NULL, '2', '9'), (NULL, '3', '6'), (NULL, '3', '7'),
(NULL, '3', '5'), (NULL, '4', '14'), (NULL, '4', '8'), (NULL, '4', '10'), (NULL, '4', '11'),
(NULL, '5', '3'), (NULL, '5', '2'), (NULL, '6', '5'), (NULL, '7', '10'), (NULL, '7', '7'),
(NULL, '7', '12'), (NULL, '8', '3'), (NULL, '8', '2'), (NULL, '8', '4'), (NULL, '9', '6'),
(NULL, '9', '7'), (NULL, '9', '12'), (NULL, '10', '9'), (NULL, '10', '10'), (NULL, '11', '11');

/* QUERY PER AREA PERSONALE : Non usata
SELECT u.IdUtente,ag.IdWorkoutGen,ag.Data,ag.Durata,ag.Tipo,cw.NumRep,cw.NumSets,e.IdEsercizio,e.Nome,e.Tipologia
FROM utente u INNER JOIN svolgimento s on u.IdUtente = s.Utente
INNER JOIN allenamentogenerato ag on s.AllenamentoGenerato = ag.IdWorkoutGen
INNER JOIN composizionewe cw on ag.IdWorkoutGen = cw.AllenamentoGenerato
INNER JOIN esercizio e on cw.Esercizio = e.IdEsercizio
WHERE u.IdUtente = 1

QUERY PER GLI ALLENAMENTI DELL'UTENTE:
select ag.* from utente u INNER JOIN svolgimento s on u.IdUtente = s.Utente
inner JOIN allenamentogenerato ag on s.AllenamentoGenerato = ag.IdWorkoutGen
where u.IdUtente = 1

QUERY PER I DETTAGLI DEGLI ALLENMENTI:
select cw.NumRep,cw.NumSets,e.Nome,e.Tipologia from allenamentogenerato ag inner join composizionewe cw on ag.IdWorkoutGen = cw.AllenamentoGenerato
inner join esercizio e on cw.Esercizio = e.IdEsercizio
where ag.IdWorkoutGen = '62d7c11f32dab';
*/
