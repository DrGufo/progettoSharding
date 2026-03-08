# ProgettoSharding

## Descrizione
**ProgettoSharding** e un progetto dimostrativo orientato alla distribuzione dei dati su piu nodi MongoDB, con instradamento applicativo basato su soglie di prezzo.

L'architettura combina:
- una homepage PHP per la raccolta dati;
- un backend PHP di redirect;
- tre microservizi Node.js/Express (`serverA`, `serverB`, `serverC`) per la persistenza su collezioni differenti;
- un cluster MongoDB multi-nodo avviabile tramite Docker Compose.

Obiettivo del progetto: mostrare una strategia di partizionamento logico (simulazione di sharding applicativo) dove la fascia di prezzo determina il server di destinazione.

## Stack Tecnologico
- **Frontend/Form**: PHP + HTML/CSS/JS
- **Backend routing**: PHP
- **Application services**: Node.js + Express + Jade
- **Database**: MongoDB 5.x
- **Containerizzazione**: Docker + Docker Compose

## Architettura Logica
Flusso previsto:
1. L'utente inserisce `nome`, `contenuto` e `prezzo` nella homepage.
2. La richiesta viene inviata a `backend/redirectPage.php`.
3. Il backend seleziona il server target in base al prezzo:
	 - `prezzo <= 1000` -> `serverA` (porta `3000`, collection `collectionA`)
	 - `1000 < prezzo <= 10000` -> `serverB` (porta `3010`, collection `collectionB`)
	 - `prezzo > 10000` -> `serverC` (porta `3020`, collection `collectionC`)
4. Il microservizio Node salva il documento in `dbSharded` sul nodo Mongo associato.

## Struttura Repository
- `progettoShardingDockerPHP/`
	- `homepage.php`: interfaccia di inserimento dati
	- `backend/redirectPage.php`: instradamento richieste verso i server Node
	- `serverA/`: servizio Node/Express (porta 3000)
	- `serverB/`: servizio Node/Express (porta 3010)
	- `mongodbCluster/`: configurazione Docker per MongoDB
- `progettoShardingDocker/`
	- `serverC/`: servizio Node/Express (porta 3020)
	- `serverB/`: risorse route/view/public del servizio B

## Requisiti
- **Node.js**: 18 LTS o superiore
- **npm**: incluso con Node.js
- **Docker Desktop** (oppure Docker Engine + Compose)
- **PHP**: 8.x consigliato (con server locale Apache/Nginx o built-in server)

## Installazione
### 1) Clonare il repository
```bash
git clone https://github.com/DrGufo/progettoSharding.git
cd progettoSharding
```

### 2) Installare le dipendenze Node
Eseguire i comandi nelle cartelle che contengono `package.json`:

```bash
cd progettoShardingDockerPHP/serverA
npm install

cd ../serverB
npm install

cd ../../progettoShardingDocker/serverC
npm install
```

## Avvio del Cluster MongoDB (Docker)
Dal percorso `progettoShardingDockerPHP/mongodbCluster`:

```bash
docker compose up -d
```

Servizi esposti in locale:
- `mongo1` -> `localhost:27018`
- `mongo2` -> `localhost:27019`
- `mongo3` -> `localhost:27020`

Per arrestare i container:
```bash
docker compose down
```

## Avvio dei Servizi Applicativi
Aprire tre terminali separati.

### Terminale 1 - serverA
```bash
cd progettoShardingDockerPHP/serverA
npm start
```

### Terminale 2 - serverB
```bash
cd progettoShardingDockerPHP/serverB
npm start
```

### Terminale 3 - serverC
```bash
cd progettoShardingDocker/serverC
npm start
```

## Avvio Frontend PHP
Posizionarsi in `progettoShardingDockerPHP` e avviare un server PHP locale (esempio):

```bash
php -S localhost:8080
```

Aprire quindi:
- `http://localhost:8080/homepage.php`

## Verifica Rapida
1. Avviare MongoDB via Docker.
2. Avviare i tre server Node.
3. Aprire la homepage PHP e inserire un record.
4. Verificare la persistenza consultando:
	 - `http://localhost:3000/`
	 - `http://localhost:3010/`
	 - `http://localhost:3020/`

## Note Operative
- Il progetto contiene componenti distribuite tra due cartelle (`progettoShardingDockerPHP` e `progettoShardingDocker`); questa organizzazione riflette lo stato corrente del prototipo.
- In `homepage.php` l'endpoint fetch e impostato su `http://localhost/php/ProgettoShardingDocker/backend/redirectPage.php`: in base al proprio ambiente locale potrebbe essere necessario allinearlo al percorso reale del progetto.
- La composizione Docker avvia i tre nodi MongoDB; lo script `mongo_setup.sh` e presente ma il servizio di setup e commentato nel `compose.yaml`.

## Troubleshooting
- Se `serverB` in `progettoShardingDockerPHP/serverB` non si avvia per moduli mancanti (`./routes/index`), verificare la coerenza con le risorse presenti in `progettoShardingDocker/serverB`.
- Se `serverA` restituisce un errore di parsing in `routes/index.js`, controllare la firma della funzione `main(...)` ed eliminare eventuali caratteri residui non validi.
- Se la homepage non invia correttamente i dati, verificare CORS, URL del backend PHP e disponibilita delle porte `3000`, `3010`, `3020`.

## Conclusioni
Questo progetto dimostra un'architettura distribuita con MongoDB e microservizi Node.js, con un semplice meccanismo di instradamento basato su soglie di prezzo. La configurazione Docker semplifica l'avvio del cluster MongoDB, mentre i servizi Node.js gestiscono la logica applicativa e la persistenza dei dati.