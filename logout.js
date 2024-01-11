// logout.js
// Imposta un cookie con un timeout di 30 minuti (l'utente viene sloggato dopo 30 minuti)
let date = new Date(Date.now() + 4500000);
date = date.toUTCString();
document.cookie = "sessionID=mySession; Expires=" + date + "; path=/; SameSite=None; Secure;";

// Aggiungi un gestore per l'evento beforeunload 
window.addEventListener("unload", function (event) {
        // Esegui il logout facendo scadere il cookie
        document.cookie = "sessionID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

        // Esegui il logout lato server tramite una chiamata AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "logout.php", false); // Usiamo la modalità sincrona per assicurarti che la richiesta venga completata prima della chiusura del browser
        xhr.send();
    // Altrimenti, l'evento beforeunload verrà ignorato e l'utente rimarrà connesso
});
/*

    ______________________________________________________________________________________________________________________________________________________________________________________

    SPIEGAZIONE DELLE 3 RIGHE

    1) var xhr = new XMLHttpRequest();
        Qui viene creata una nuova istanza dell'oggetto XMLHttpRequest tramite la keyword "new" (LPO *coff coff*). Questo oggetto è una componente di JavaScript che consente di effettuare 
        richieste HTTP asincrone (AJAX) da un browser web a un server (richieste HTTP GET/POST/PUT/DELETE) senza dover ricaricare la pagina (manipolazione del DOM).


    2) xhr.open("GET", "logout.php", true);
        La funzione open viene chiamata sull'oggetto XMLHttpRequest. Questa funzione inizializza la richiesta, specificando il tipo di richiesta (in questo caso "GET"), 
        l'URL del file a cui fare la richiesta ("logout.php") e se la richiesta deve essere gestita in modo asincrono o sincrono (nell'esempio è "true", quindi asincrono).

        SPIEGAZIONE DEI 3 PARAMETRI CHE VENGONO PASSATI A OPEN (E SPIEGAZIONE GENERALE FUNZIONAMENTO AJAX):

        - Richiesta di tipo GET: una richiesta GET è un meccanismo di base per ottenere informazioni da un server web attraverso un URL, ed è ampiamente utilizzata per il recupero di risorse 
            e dati. La richiesta GET è comunemente utilizzata quando si desidera recuperare dati dal server senza apportare modifiche. Ad esempio, quando si richiede una pagina web, 
            un'immagine o qualsiasi altra risorsa. Altri tipi di richiesta sono:
                -POST: Il metodo POST viene utilizzato per inviare dati al server per essere elaborati
                -PUT: Il metodo PUT viene utilizzato per aggiornare o creare una risorsa sul server con i dati forniti 
                    (forse il caso di UpdateProfile dove si aggiornano i campi senza ricaricare la pagina)
                -DELETE: Il metodo DELETE viene utilizzato per richiedere la rimozione di una risorsa specificata dal server.

        - logout.php indica la risorsa (il file) che dev'essere "eseguito" una volta che viene inviata la richiesta. (Ricordiamoci che tutto questo codice viene eseguito sul browser, 
            dobbiamo inviare una richiesta per "ottenere" il file logout.php che sta sul server)

        - Richiesta HTTP asincrona: Una richiesta HTTP asincrona si riferisce a un tipo di operazione in cui il programma che effettua la richiesta non si blocca o non attende la risposta 
            immediata dal server prima di continuare l'esecuzione. Invece, il flusso di esecuzione del programma procede mentre la richiesta viene gestita in background. 
            Questo è particolarmente utile in contesti come applicazioni web, dove la velocità di risposta è un elemento cruciale per fornire un'esperienza utente fluida.
            "true" indica che la richiesta è asincrona e che non voglio aspettare la risposta. "false" se cerco invece sincronia (attendo risposta server)


    3) xhr.send();
        La funzione send viene chiamata sull'oggetto XMLHttpRequest per inviare effettivamente la richiesta HTTP al server. 
        In questo caso, la richiesta è di tipo GET, il che significa che si sta cercando di ottenere informazioni dal server senza inviare dati aggiuntivi nel corpo della richiesta.
    
    _____________________________________________________________________________________________________________________________________________________________________________________

    Di interesse minore (teoria):
        - La variabile alla quale viene assegnato l'oggetto si chiama xhr perché sta per (X)ml(H)ttp(R)equest e viene chiamata cosi per "convenzione"
        - L'acronimo AJAX sta per "Asynchronous JavaScript and XML". Si tratta di una tecnologia che consente di effettuare richieste asincrone 
            verso un server web utilizzando JavaScript, senza la necessità di ricaricare la pagina.
        - L'acronimo XML sta per "eXtensible Markup Language". Si tratta di un linguaggio di markup, ovvero un linguaggio di marcatura, progettato per memorizzare e trasportare dati. 
            XML è stato sviluppato dal World Wide Web Consortium (W3C) ed è stato pubblicato come uno standard aperto.
        - .open() e .send() sono a tutti gli effetti metodi dell'oggetto XMLHttpRequest.
        - I file JSON sono strettamente collegati alle richieste AJAX. È comunemente utilizzato per scambiare dati tra un server e un client web, 
            ma è indipendente dal linguaggio di programmazione, il che significa che può essere utilizzato con molti linguaggi diversi. Lo usiamo in logout.php
            Si usano solitamente con richieste POST o PUT dove bisogna trasportare effettivamente dei dati. La struttura di un JSON è un'insieme di coppie chiave:valore del tipo

            {
                "nome": "Mario",
                "età": 30,
                "città": "Roma",
                "amici": ["Luigi", "Peach", "Yoshi"]
            }

    ______________________________________________________________________________________________________________________________________________________________________________________

    Complementare alle richieste AJAX c'è "fetch" che è una funzione JavaScript che fa la stessa identica cosa di AJAX. L'unica differenza è che è più moderna/potente/flessibile di AJAX
    Il nostro codice di 3 righe della richiesta AJAX con fetch sarebbe:

    fetch('logout.php', {
        method: 'GET',
        credentials: 'same-origin',
    })
    .then(response => {
        if (!response.ok) {
        throw new Error('Network response was not ok');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });

    Fare copia-incolla su ChatGPT per capire riga per riga cosa fa
    _______________________________________________________________________________________________________________________________________________________________________________________


    P.S. 
        In logout.php c'è la riga di codice:
            echo json_encode(["status" => "success"]);
        questa fa uso della funzione json_encode() per convertire un array associativo PHP (coppia chiave:valore come dicevamo sopra) in una stringa in formato JSON, 
        che viene quindi inviata come output al client attraverso l'istruzione echo.
        In breve,  questa riga di codice viene usata per comunicare lo stato di un'operazione lato server a un'applicazione client. 
        In questo caso viene usata per indicare che un'operazione è stata eseguita con successo, restituendo un oggetto JSON con un campo "status" impostato su "success".

    _______________________________________________________________________________________________________________________________________________________________________________________
*/