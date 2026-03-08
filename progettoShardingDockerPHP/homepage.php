<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-start: #f4efe6;
            --bg-end: #e3edf7;
            --surface: rgba(255, 255, 255, 0.8);
            --surface-border: rgba(15, 35, 60, 0.15);
            --text: #112031;
            --muted: #4d6178;
            --accent: #0d9488;
            --accent-strong: #0f766e;
            --danger: #b91c1c;
            --shadow: 0 20px 40px rgba(17, 32, 49, 0.15);
            --radius: 18px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Space Grotesk', sans-serif;
            color: var(--text);
            background: radial-gradient(circle at 15% 15%, #fff8ea 0%, transparent 35%),
                        radial-gradient(circle at 85% 10%, #d7eefb 0%, transparent 40%),
                        linear-gradient(140deg, var(--bg-start), var(--bg-end));
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .shell {
            width: min(760px, 100%);
            padding: 28px;
            border-radius: 28px;
            border: 1px solid var(--surface-border);
            background: var(--surface);
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow);
            animation: reveal 700ms ease-out;
        }

        .header {
            margin-bottom: 22px;
        }

        .eyebrow {
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-size: 12px;
            color: var(--muted);
            margin: 0 0 6px;
        }

        h1 {
            font-size: clamp(30px, 5vw, 42px);
            margin: 0;
            line-height: 1.1;
        }

        .subtitle {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 15px;
        }

        .form-grid {
            display: grid;
            gap: 16px;
        }

        .field {
            display: grid;
            gap: 8px;
            animation: fieldIn 500ms ease-out both;
        }

        .field:nth-child(1) { animation-delay: 80ms; }
        .field:nth-child(2) { animation-delay: 160ms; }
        .field:nth-child(3) { animation-delay: 240ms; }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input,
        textarea {
            width: 100%;
            border: 1px solid #c7d2df;
            border-radius: var(--radius);
            padding: 12px 14px;
            font: inherit;
            color: var(--text);
            background: #ffffff;
            transition: border-color 160ms ease, box-shadow 160ms ease, transform 160ms ease;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.18);
            transform: translateY(-1px);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        button {
            border: none;
            border-radius: 999px;
            padding: 12px 22px;
            font: inherit;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            color: #fff;
            cursor: pointer;
            transition: transform 140ms ease, filter 140ms ease;
        }

        button:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        button:active {
            transform: translateY(0);
        }

        button:disabled {
            cursor: not-allowed;
            filter: saturate(0.6);
            opacity: 0.8;
        }

        .status {
            font-size: 14px;
            color: var(--muted);
            min-height: 20px;
        }

        .status.error {
            color: var(--danger);
        }

        .status.ok {
            color: var(--accent-strong);
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fieldIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 640px) {
            .shell {
                padding: 20px;
                border-radius: 22px;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="shell" id="content">
        <div class="header">
            <p class="eyebrow">Sharding Dashboard</p>
            <h1>Inserisci nuovo contenuto</h1>
            <p class="subtitle">Compila i campi e invia. Il backend reindirizza automaticamente verso il server corretto in base al prezzo.</p>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Es. Prodotto premium">
            </div>

            <div class="field">
                <label for="Contenuto">Contenuto</label>
                <textarea id="Contenuto" name="Contenuto" placeholder="Descrizione del contenuto..."></textarea>
            </div>

            <div class="field">
                <label for="Prezzo">Prezzo</label>
                <input type="number" id="Prezzo" name="Prezzo" min="0" step="1" placeholder="Es. 1200">
            </div>

            <div class="actions">
                <button id="submit">Invia</button>
                <span id="status" class="status"></span>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("submit").addEventListener("click", function() {
            var submitButton = document.getElementById("submit");
            var status = document.getElementById("status");
            var nome = document.getElementById("nome").value;
            var contenuto = document.getElementById("Contenuto").value;
            var prezzo = document.getElementById("Prezzo").value;

            if (!nome || !contenuto || !prezzo) {
                status.textContent = "Compila tutti i campi prima di inviare.";
                status.className = "status error";
                return;
            }

            var dati = {
                nome: nome,
                contenuto: contenuto,
                prezzo: prezzo  
            };
            console.log(dati);

            submitButton.disabled = true;
            status.textContent = "Invio in corso...";
            status.className = "status";

            fetch("http://localhost/php/ProgettoShardingDocker/backend/redirectPage.php", {
                method: "POST",
                body: JSON.stringify(dati)
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                status.textContent = "Richiesta inviata con successo.";
                status.className = "status ok";
            })
            .catch(error => {
                console.error(error);
                status.textContent = "Errore durante l'invio della richiesta.";
                status.className = "status error";
            })
            .finally(() => {
                submitButton.disabled = false;
            });
        });
    </script>
</body>
</html>