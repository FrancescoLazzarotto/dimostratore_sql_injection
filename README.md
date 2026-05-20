# Progetto Web sicuro e Personalizzato - Modulo Web Sicuro
# Dimostratore: SQL Injection — Bypass del Login

## Vulnerabilità dimostrata
**SQL Injection** — Interferenza con la logica dell'applicazione (bypass del meccanismo di autenticazione).

## Scenario
L'applicazione simula un portale bancario con login. Un attaccante sfrutta una SQL Injection nel form di login per accedere all'area riservata **senza conoscere la password**, manipolando la struttura della query SQL inviata al database.

## Struttura del progetto
```
progetto_sqli/
├── vulnerabile/    → Versione con la vulnerabilità (concatenazione diretta dell'input)
├── sicuro/         → Versione corretta (prepared statements)
└── progetto_sqli.sql
```

## Configurazione (XAMPP / WAMP / MAMP)
1. Importare il database:
   ```
   mysql -u root -p < progetto_sqli.sql
   ```
2. Copiare le cartelle `vulnerabile/` e `sicuro/` nella document root (es. `htdocs/`)
3. Verificare le credenziali in `config/config.php` (default: utente/password)
4. Aprire nel browser:
   - `http://localhost/vulnerabile/`
   - `http://localhost/sicuro/`

## Credenziali utenti di test
| Username | Password |
|----------|----------|
| admin    | admin123 |
| alice    | alice456 |
| bob      | bob789   |

## Come eseguire l'attacco (versione vulnerabile)
Nel campo **username** inserire uno dei seguenti payload (la password può essere qualsiasi):

| Payload              | Effetto                                    |
|----------------------|--------------------------------------------|
| `admin' -- `         | Accede come admin bypassando la password   |
| `' OR '1'='1' -- `   | Accede come primo utente (admin)           |
| `alice' -- `         | Accede come alice senza conoscere la password |

### Spiegazione del meccanismo
La query vulnerabile generata con il payload `admin' -- ` diventa:

```sql
SELECT id, username, nome_completo, ruolo
FROM utenti
WHERE username='admin' -- ' AND password='qualsiasi'
```

Il doppio trattino `--` introduce un commento SQL: tutto ciò che segue viene ignorato dal DBMS, incluso il controllo sulla password. Il database restituisce la riga dell'utente `admin` senza verificare la password.

## Correzione (versione sicura)
Uso di **prepared statements** (query parametrizzate con MySQLi):

```php
$stmt = mysqli_prepare($db,
    "SELECT id, username, nome_completo, ruolo
     FROM utenti WHERE username=? AND password=?");
mysqli_stmt_bind_param($stmt, "ss", $username, $pwd);
mysqli_stmt_execute($stmt);
```

I `?` sono segnaposto: il valore dell'input viene passato separatamente rispetto alla struttura SQL e il database lo tratta sempre e solo come **dato**, mai come **codice**. Qualunque payload SQL nell'input verrà cercato letteralmente come stringa nel database, senza effetto.

## Ricerca avanzata (punto 1)
Nuove pagine per una demo piu' completa di SQL Injection:
- `http://localhost/vulnerabile/ricerca.php`
- `http://localhost/sicuro/ricerca.php`

Payload di esempio (solo versione vulnerabile):
- `%' OR '1'='1' -- `
- `%' UNION SELECT id, numero_conto, saldo, ultima_operazione FROM conti -- `
- `%' OR IF(1=1, SLEEP(2), 0) -- `

Nella versione sicura gli stessi input vengono trattati come stringa letterale.
