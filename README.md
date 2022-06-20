# Prog3
Progetto sul random workout generator
// --- DECISO. FILE DI VERSIONING. OGNI COMMIT E' DESCRITTO QUI --- // <br>
10/06/2022 -> COSE FATTE: <br>
<ul>
  <li> Fixato deezer. Deciso di rimuovere il file esterno </li>
  <li> Fixato alcuni bug sui flag interattivi delle pagine FORTIME , TABATA e EMOM </li>
</ul>
<br>
13/06/2022 -> COSE FATTE: <br>
<ul>
  <li> Aggiunte le logiche di pesi disponibili per gli esercizi in
  tutte le modalità di allenamento, e dove serviva anche il timer random  </li>
  <li> Bug fixato nella scelta dei gruppi muscolari in scheda.html </li>
  <li> Automatizzati i calcoli nel tabata, rimossa le possibilità di parametri random per scelta, fixati gli intervalli </li>
  <li> Aggiornato il dataset ed il relativo file, per inserire esercizi e relativi gruppi colpiti </li>
  <li> Aggiunto DEEZER anche su index.html </li>
</ul> <br>

17/06/2022 -> COSE FATTE: <br>
<ul>
  <li> Aggiunte le logiche coi tasti di stop workout , resume workout e pause workout ad: AMRAP <br>
  , TABATA, EMOM e FORTIME. Inoltre è stato modificato il file timer.js per alcune di esse. <br>
  Il fortime ha la particolarità che anche senza timer (timecap = no) ha i pulsanti. </li>
  <li> Levati i riferimenti agli elementi class="invisibles" con la funzione getElementsByclassName <br>
  li ho ripresi dall'id </li>
  <li> Il div di scelta del timeCap è stato messo in una variabile, date le svariate chiamate </li>
  <li> Gestito anche il comportamento della barra e del colore del timer in relazione ai pulsanti </li>
</ul> <br>

20/06/2022 -> COSE FATTE: <br>
<ul>
  <li> Modificato su AMRAP la didascalia del flag "pesiSiNo" </li>
  <li> Spostati i pulsanti in un div separato detto pulsanti, e creato un div a parte prima di essi, per gli esercizi </li>
  <li> Ottimizzata la gestione della chiamata che genera la lista di gruppi muscolari su scheda.html </li>
  <li> Gestita la richiesta dell'AMRAP randomico </li>
  <li> Aggiunta logica ulteriore con pulsante. Pulsante di richiesta allenamento. Se prima non viene richiesto, non si può abilitare lo start </li>
</ul>

FIX DA RIVEDERE: <br>
<ul>
  <li> ***Migliorare l'interattività nel tabata; automatizzare il calcolo del timer in base agli esercizi <br>
    selezionati o limitarne la scelta.*** </li>
  <li> CAPIRE SE DEEZER HA PROBLEMI DI SUO O STO SBAGLIANDO QUALCOSA IO. Guardare con attenzione gli errori lanciati <br>
  in console. EVENTUALMENTE fixare il problema dell'autoplay iniziale: non parte in automatico la canzone all'inizio <br>
  Devo necessariamente farla partire io col click </li>
  <li> ***AGGIUNGERE tasto stop al workout*** </li>
  <li> RISCRIVERE ERRORE QUERY IN apiDb.php </li>
  <li> CAPIRE a che pro ho il div "rounds" in TABATA.html </li>
  <li> Vagliare l'ipotesi di usare più variabili al posto delle chiamate ai div classiche </li>
  <li> Rivedere logica dei "workoutParameters" , se mantenerla o meno </li>
  <li> Rimuovere funzione/alert della funzione che fa vedere la lista dei gruppi selezionati in scheda.html </li>
  <li> Gestire in try-catch chiamate ajax (?) </li>
  <li> Decidere come implementare gli aiuti per gli esercizi(se usare GIF da piazzare o usare dei link) </li>
  <li> Ideare pagina per sostituzioni esercizi </li>
</ul> <br>

TIPS AND TRICKS: <br>
<ul>
  <li> Quando la chiamata json da problemi, posso debuggare gli errori su php <br>
  facendo il console.log di ajax.responseText </li>
  <li> Per refreshare i sorgenti quando non leggono le nuove modifiche: Ctrl+fn+f5 </li>
</ul>
