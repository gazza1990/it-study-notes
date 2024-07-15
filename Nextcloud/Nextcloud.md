# NextCloud Server

## Indice

1. [Installazione Self-Hosted da Source](#installazione-self-hosted-da-source)
   1. [Preparazione macchina virtuale](#preparazione-macchina-virtuale)
   2. [Installazione webserver Apache2](#installare-il-webserver-apache2)
   3. ...

## Installazione Self-Hosted da Source

Queste istruzioni sono valide al 20/06/2024 per la versione Nextcloud 29.

Quello che verrà fatto sarà preparare un VM dove si andrà ad installare uno stack LAMP (Linux Apache MySql Php), infine si scaricherà il file compresso contenente codice sorgente di Nextcloud e lo si scopatterà in una apposita cartella del web server.

Le istruzioni seguite sono prese dalle seguenti fonti:

[How to Install Nextcloud on Debian 12](https://www.howtoforge.com/step-by-step-installing-nextcloud-on-debian-12/)

[Installazione Nextcloud 27 su Raspberry pi 4 - Edmond&#039;s Weblog](https://francoconidi.it/installazione-nextcloud-27-su-raspberry-pi-4/) (per certificato SSL con OpenSSL)

### Preparazione macchina virtuale

Preparare una VM con Debian 12 (anche altri SO vanno bene, come Ubuntu 22), in fase di installazione abilitare il server SSH.

Una volta installata ed avviata la macchina, bisogna accedere come root e installare il pacchetto "sudo" per abilitare l'utente (creato in fase di installazione) ad utilizzare la relativa funzione per eseguire azioni come amministratore.

```bash
# apt update && apt upgrade
# apt install sudo
# usermod -aG sudo utente
# logout
```

Quindi eseguiamo il login con l'utente non root e modifichiamo l'indirizzo IP (tramite la modifica del file /etc/interfaces) e l'hostname tramite il seguente comando.

```bash
$ sudo hostnamectl set-hostname NUOVOHOSTNAME
```

Riavviare il server e verificare che l'hostname sia cambiato, controllare inoltre il file /etc/hosts ed eventualmente sistemare anche qui l'hostname in modo che combaci con quanto configurato in precedenza.

### Installare il webserver Apache2

Installiamo il webserver apache2, abilitiamo e verifichiamo che funzioni.

```bash
$ sudo apt install apache2
$ sudo systemctl is-enabled apache2
$ sudo systemctl status apache2
```

### Installare e configurare il Firewall UFW (Facoltativo)

Non necessario ai fini del funzionamento, ma consigliato per la sicurezza del sistema.

```bash
$ sudo apt install ufw
$ sudo ufw allow OpenSSH
$ sudo ufw enable
$ sudo ufw allow "WWW Full"
$ sudo ufw reload
$ sudo ufw status
```

Per verificare la lista di tutti i profili disponibili in ufw usare il seguente comando

```bash
$ sudo ufw app list
```

### Installare e configurare PHP 8.2

<u>***ATTENZIONE! Seguire il seguente link per installare direttamente PHP 8.3***</u>

[How to install or upgrade to PHP 8.3 on Ubuntu and Debian • PHP.Watch](https://php.watch/articles/php-8.3-install-upgrade-on-debian-ubuntu)

***<u>In seguito installare i pacchetti specificati in questo paragrafo, se ci fossero problemi provare a modificare il prefisso del nome dei pacchetti da php-\* a php8.3-\*</u>***

In Debian 12 Bookworm ci sono già i pacchetti per installare PHP 8.2.

Eseguire il seguente comando per installare PHP 8.2 e tutte le estensioni che servono a Nextcloud, infine verificare la versione di php e le estensioni abilitate.

Attenzione! Se si utilizza un altro sistema operativo o un'altra versione di Debian bisogna aggiungere i repository di PHP e verificare la compatibilità di Nextcloud con le versioni successive alla 8.2.

```bash
$ sudo apt install -y php php-curl php-cli php-mysql php-gd php-common php-xml php-json php-intl php-pear php-imagick php-dev php-common php-mbstring php-zip php-soap php-bz2 php-bcmath php-gmp php-apcu libmagickcore-dev php-redis php-memcached
$ php --version
$ php -m
```

Aprire il file php.ini con un editor di testo

```bash
$ sudo nano /etc/php/8.2/apache2/php.ini
```

Modificare le seguenti righe

```ini
# Togliere il commento a date.timezone e compilarlo correttamente
date.timezione = Europe/Rome

# Modificare i seguenti parametri
memory_limit = 512M
upload_max_filesize = 500M
post_max_size = 600M 
max_execution_time = 300
file_uploads = On
allow_url_fopen = On
display_errors = Off
output_buffering = Off
zend_extension=opcache

#Aggiungere le seguenti righe sotto la sezione [opcache]
opcache.enable = 1
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 10000
opcache.memory_consumption = 128
opcache.save_comments = 1
opcache.revalidate_freq = 10
```

Infine chiudere salvando il file e riavviare il servizio apache2.

```bash
$ sudo systemctl restart apache2
```

### Installare e configurare MariaDB Server

Installare, abilitare e verificare lo stato di mariadb server

```bash
$ sudo apt install mariadb-server
$ sudo systemctl is-enabled mariadb
$ sudo systemctl status mariadb
```

Avviare lo script di installazione sicura di mariadb, utile a rendere più protetto il software.

```bash
$ sudo mariadb-secure-installation
```

Verranno visualizzare diverse richieste a schermo, seguire le seguenti istruzioni:

- Premere INVIO quando viene richiesta la root password.
- Inserire N per il metodo di autenticazione unix_socket.
- Inserire Y per impostare una nuova password per l'utente root di MariaDB, poi ripetere l'inserimento.
- Inserire Y per rimuovere l'utente anonymous di MariaDB.
- Inserire Y per disabilitare il login remoto per l'utente root di MariaDB.
- Inserire Y per rimuovere il database test.
- Inserire Y per ricaricare la tabella dei privilegi ed applicare i cambiamenti.

Accedere al server MariaDB

```bash
$ sudo mariadb -u root -p
```

Inserire la password precedentemente impostata per l'utente root.

Nel prompt di MariaDB inserire le seguenti istruzioni per creare il database per nextcloud e l'utente che verrà utilizzato dal sistema. Verrà creato un nuovo database **nextcloud_db** e l'utente **nextclouduser** con password **StrongPassoword**

```sql
> CREATE DATABASE nextcloud_db;
> CREATE USER nextclouduser@localhost IDENTIFIED BY 'StrongPassword';
> GRANT ALL PRIVILEGES ON nextcloud_db.* TO nextclouduser@localhost;
> FLUSH PRIVILEGES;
> SHOW GRANTS FOR nextclouduser@localhost;
```

L'ultima istruzione serve per verificare che i permessi siano stati impostati correttamente.

Uscire dalla console di MariaDB con il comando `quit`.

### Scaricare il codice sorgente di Nextcloud

Installare curl e unzip che serviranno per scaricare ed estrarre l'archivio contenente il codice sorgente.

```bash
$ sudo apt install curl unzip -y
```

Quindi spostarsi nella root del sito (su Debian /var/www), scaricare l'archivio ed estrarlo, infine assegnare www-data come utente e gruppo proprietario della cartella appena estratta.

```bash
$ cd /var/www/
$ sudo curl -o nextcloud.zip https://download.nextcloud.com/server/releases/latest.zip
$ sudo unzip nextcloud.zip
$ sudo chown -R www-data:www-data nextcloud
```

La root di nextcloud quindi è /var/www/nextcloud.

### Configurare il webserver Apache2

Creare una nuova configurazione per Host Virtuale

```bash
$ sudo nano /etc/apache2/sites-available/nextcloud.conf
```

Copiare le seguenti righe nel file, modificando ServerName con il nome di dominio corretto. In assenza del nome di dominio inserire l'indirizzo IP.

```apacheconf
<VirtualHost *:80>
    ServerName nextcloud.hwdomain.io
    DocumentRoot /var/www/nextcloud/

    # log files
    ErrorLog /var/log/apache2/nextcloud-error.log
    CustomLog /var/log/apache2/nextcloud-access.log combined

    <Directory /var/www/nextcloud/>
        Options +FollowSymlinks
        AllowOverride All

        <IfModule mod_dav.c>
            Dav off
        </IfModule>

        SetEnv HOME /var/www/nextcloud
        SetEnv HTTP_HOME /var/www/nextcloud
    </Directory>
</VirtualHost>
```

Salvare e chiudere il file.

In questo modo diciamo a Apache2 che le richieste in arrivo sulla porta 80 devono essere redirette verso /var/www/nextcloud.

A questo punto bisogna abilitare il file di configurazione appena creato, testare che sia corretto con il comando `configtest`.

```bash
$ sudo a2ensite nextcloud.conf
$ sudo apachectl configtest
```

Se l'output di configtest è Syntax OK, possiamo riavviare il webserver

```bash
$ sudo systemctl reload apache2
$ sudo systemctl restart apache2
```

Collegandosi tramite browser a http://dominio.inserito/, se tutto funziona, dovrebbe aprirsi la pagina di inizializzazione dell'account nextcloud.

### Configurare Nextcloud con Certificato SSL

#### Certificato Self Firmed OpenSSL

Creare la cartella /etc/apache2/ssl e creare con i certificati con il programma openssl.

```bash
$ sudo mkdir -p /etc/apache2/ssl
$ sudo openssl req -x509 -nodes -days 365 -newkey rsa:4096 -keyout /etc/apache2/ssl/apache.key -out /etc/apache2/ssl/apache.crt
```

Inserire i dati richiesti

```bash
Country Name (2 letter code) [AU]:
State or Province Name (full name) [Some-State]:
Locality Name (eg, city) []:
Organization Name (eg, company) [Internet Widgits Pty Ltd]:
Organizational Unit Name (eg, section) []:
Common Name (e.g. server FQDN or YOUR name) []:
Email Address []:
```

Abilitare ssl su apache2 e riavviare il servizio

```bash
$ sudo a2enmod ssl
$ sudo systemctl restart apache2
```

##### Forzare HTTPS alternativa 1

Aprire il file di configurazione di apache2

```bash
$ sudo nano /etc/apache2/sites-available/nextcloud.conf
```

E sostituire il contenuto con il seguente

```apacheconf
<VirtualHost *:80>
        ServerName EDP07VM06SSL
        ServerAlias 192.168.200.4

        Redirect permanent / https://192.168.200.4/
</VirtualHost>

<VirtualHost *:443>
        ServerName EDP07VM06SSL
        ServerAlias 192.168.200.4
        ServerAdmin example@mailadmin.com

        DocumentRoot /var/www/nextcloud/

        # log files
        ErrorLog /var/log/apache2/nextcloud-error.log
        CustomLog /var/log/apache2/nextcloud-access.log combined

        SSLEngine on

        SSLCertificateFile      /etc/apache2/ssl/apache.crt
        SSLCertificateKeyFile   /etc/apache2/ssl/apache.key

        <Directory /var/www/nextcloud/>
                Options +FollowSymlinks
                AllowOverride All

                <IfModule mod_dav.c>
                     Dav off
                </IfModule>

                SetEnv HOME /var/www/nextcloud
                SetEnv HTTP_HOME /var/www/nextcloud
        </Directory>

        <FilesMatch "\.(?:cgi|shtml|phtml|php)$">
                SSLOptions +StdEnvVars
        </FilesMatch>
        <Directory /usr/lib/cgi-bin>
                SSLOptions +StdEnvVars
        </Directory>

</VirtualHost>
```

Probabilmente questo file di configurazione è migliorabile.

Collegandosi con HTTP ora si dovrebbe venire reindirizzati ad HTTPS.

##### Forzare HTTPS alternativa 2

Aprire il file default-ssl.conf

```bash
$ sudo nano /etc/apache2/sites-available/default-ssl.conf
```

e modificare le stringhe da così

```apacheconf
SSLCertificateFile /etc/ssl/certs/ssl-cert-snakeoil.pem
SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key
```

a così

```apacheconf
SSLCertificateFile /etc/apache2/ssl/apache.crt
SSLCertificateKeyFile /etc/apache2/ssl/apache.key
```

Quindi abilitare la configurazione e ricaricare il webserver

```bash
$ sudo a2ensite default-ssl.conf
$ sudo systemctl reload apache2
```

Aprire il file di configurazione 000-default.conf

```bash
$ sudo nano /etc/apache2/sites-available/000-default.conf
```

Sostituire tutto con le righe seguenti

```apacheconf
Alias /nextcloud "/var/www/nextcloud/"

<VirtualHost *:80>
ServerAdmin admin@example

RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
</VirtualHost>
```

Collegandosi con HTTP ora si dovrebbe venire reindirizzati ad HTTPS.

#### Certificato con Let's Encrypt

Per generare un certificato Let's Encrypt si utilizza il programma Certbot che dispone anche di un plugin per permette di configurare automaticamente HTTPS su più webserver.

Installazione di Certbot e del plugin per apache

```bash
$ sudo apt install certbot python3-certbot-apache
```

Avviare il seguente comando per cenerare i certificati SSL/TLS per il dominio Nextcloud e per configurare automaticamente HTTPS nell'host virtuale di Apache2. Cambiare il nome di dominio e la mail utilizzati nell'istruzione.

```bash
$ sudo certbot --apache --agree-tos --redirect --hsts --staple-ocsp --email user@hwdomain.io -d nextcloud.hwdomain.io
```

Collegarsi quindi ad https://dominio.inserito/ per verificare che la connessione HTTPS funzioni correttamente.

### Inizializzazione di NextCloud

Una volta collegati all'interfaccia web del sistema, bisogna inserire alcuni dati:

- Username: Nuovo username per l'utente amministratore

- Password: Nuova password per l'utente amministratore

- Data Folder: Cartella che conterrà tutti i dati caricati su NextCloud

- Database
  
  - user: nextclouduser
  
  - password: StrongPassword
  
  - name: nextcloud_db
  
  - host: localhost

Attendere la schermata successiva dove verrà proposta l'installazione di alcune app. Si può scegliere se installarle o skippare ed eventualmente installare successivamente.

A questo punto dovrebbe comparire la Dashboard, cliccare sull'icona del profilo e andare in Impostazioni di amministrazione, qui verranno mostrati degli avvisi riguardanti alcune raccomandazioni da applicare sull'installazione di Nextcloud.

### Altre configurazioni

#### Abilitare operazioni in background con cron

Dal menu di Amministrazione della webapp, cliccare sulla voce Impostazioni di base dove è possibile scegliere come eseguire le operazioni in background, selezionare Cron.

Andare sul terminale, aprire il crontab

```bash
$ sudo crontab -u www-data -e
```

ed inserire la seguente configurazione

```cron
*/5  *  *  *  * php -f /var/www/nextcloud/cron.php
```

Salvare, chiudere e verificare che la configurazione sia stata presa

```bash
$ sudo crontab -u www-data -l
```

Infine è necessario aggiungere la seguente riga nel file /etc/php/\<version\>/mods-available/acpu.ini

```ini
apc.enable_cli=1
```

Se non la si aggiunge si possono presentare errori nel crontab perchè non vedere il modulo apc attivo, di seguito un errore che potrebbe presentarsi eseguendo cron.php

```log
Memcache OC\Memcache\APCu not available for local cache
```

#### Inserire dei domini fidati (trusted domains)

Per limitare l'accesso a una lista di ip e domini, aprire il file di configurazione.

```bash
$ sudo nano /var/www/nextcloud/config/config.php
```

Inserire le seguenti istruzioni nell'array

```php
'trusted_domains' => 
array (
0 => '192.168.1.122',
1 => 'my-domain.com',
```

#### Abilitare memory caching, redis e default phone region

##### Aprire il file di configurazione di nextcloud

```bash
$ sudo nano /var/www/nextcloud/config/config.php
```

Inserire in fondo le seguenti istruzioni

```php
<?php
$CONFIG = array (
....
  # Additional configurations
  'memcache.local' => '\OC\Memcache\APCu',
);
```

#### Cambiare la porta SSH di default

```bash
$ sudo nano /etc/ssh/sshd_config
```

```ini
#PermitRootLogin prohibit-password
Port 2223
```

#### Abilitare gli aggiornamenti di sicurezza automatici

```bash
$ sudo apt install unattended-upgrades
$ sudo nano /etc/apt/apt.conf.d/02periodic
```

```ini
APT::Periodic::Enable "1";
APT::Periodic::Update-Package-Lists "1";
APT::Periodic::Download-Upgradeable-Packages "1";
APT::Periodic::Unattended-Upgrade "1";
APT::Periodic::AutocleanInterval "1";
APT::Periodic::Verbose "2";
```

```bash
$ sudo unattended-upgrades -d
```

## Alcune applicazioni utili

### Deck

Applicazione stile Trello

### Nextcloud Office

Per aprire i documenti direttamente su web, attenzione che per utilizzarla bisogna installare anche "Collabora Online - Built-in CODE Server" che si trova nella sezione delle integrazioni (oppure si può installare da temrinale) altrimenti non funziona.

### Group Folders

Per gestire delle cartelle slegate da un utente. Di default infatti le cartelle hanno sempre un proprietario che eventualmente le condivide, quindi se si volesse usare Nextcloud in stile NAS bisognerebbe creare un account di servizio per creare una struttura di cartelle e condivisioni. Con "Group Folders" è possibile creare delle cartelle che non hanno un proprietario e possono essere condivise con gli utenti e i gruppi, inoltre è anche possibile assegnare una quota a queste cartelle.

### Calendar

Calendario classico

### Brute Force Protection

Previene gli attacchi brute force

### Talk

### Tasks

### Cad Viewer

### Jira Integration

## Aggiunta manuale di files

E' possibile aggiungere manualmente dei files da filesystem nella cartella usata per i dati da Nextcloud, però per renderli visibili nella webapp bisogna, una volta terminata la copia, eseguire una scansione tramite le seguenti istruzioni:

```bash
$ sudo -u www-data php occ files:scan --all
```

Il programma **occ** si trova nella root di Nextcloud.

In caso di copia manuale prestare attenzione ai permessi sui files copiati, il proprietario deve risultare l'utente **www-data** altrimenti non sarà possibile modificarli o rimuoverli.

```bash
$ sudo chown -R www-data:www-data /cartella/dati/nextcloud/*
```

## Altre istruzioni utili

### Integrare Samba

```bash
$ sudo apt install smbclient cifs-utils php-smbclient libsmbclient-dev php-dev make
# Verificare se è stato generato il file 
# /etc/php/8.3/mods_available/smbclient.ini
# Se non è stato aggiunto provare ad aggiungerlo saltando la riga con 
# il comando "pecl", se l'smb non funziona allora provare a usare il
# comando pecl e ad inserire l'estensione in php.ini
$ sudo pecl install smbclient
$ echo 'extension=smbclient.so' >> /etc/php/8.3/apache2/php.ini
$ sudo reboot
```

L'estensione smbclient.so può anche essere aggiunta a manualmente modificando il file php.ini

Il riavvio non è necessario, può bastare riavviare il servizio di php o apache2.

### Installazione Redis Server per caching e Default phone Region

Attenzione!!! Facoltativo, dovrebbe aumentare le prestazioni, se crea problemi togliere tutto e lasciare solamente la Default Phone Region.

Installare i pacchetti redis.

```bash
$ sudo apt install redis-server php-redis
$ sudo systemctl status redis-server
```

Ua volta verificato che redis-server è attivo, aprire il file /var/www/nextcloud/config/config.php ed aggiungere le seguenti righe all'interno dell'array \$CONFIG.

```php
'memcache.local' => '\OC\Memcache\APCu',
'memcache.locking' => '\OC\Memcache\Redis',
'memcache.distributed' => '\OC\Memcache\Redis',
'redis' => [
'host' => 'localhost',
'port' => 6379,
],
'default_phone_region' => 'IT',
```

### Impostare l'orario per la manutenzione

Si tratta di un orario in cui Nextcloud può eseguire alcune operazioni di manutenzione. Usa il fuso orario UTC.

Bisogna aprire il file /var/www/nextcloud/config/config.php ed aggiungere la seguente riga all'interno dell'array $CONFIG, modificando l'ora a piacimento.

```php
'maintenance_window_start' => 1,
```

### Integrare 2FA MFA

Per integrare la 2FA bisogna installare una delle applicazioni che consentono di utilizzare questa funzionalità.

Se si vuole utilizzare Google Authenticator, abilitare l'applicazione **Two-Factor TOTP Provider**.

### Integrare LDAP Active Directory

Installare il pacchetto php-ldap nel SO

```bash
$ sudo apt install php-ldap
```

Quindi modificare il file php.ini e togliere il commento alla seguente riga

```ini
extension=ldap
```

Quindi dalla GUI di Nextcloud abilitare l'Applicazione **LDAP user and group backend**.

Quando un utente viene rimosso da LDAP, nella GUI di Nextcloud non comparirà più, però i sui file e cartelle saranno ancora presenti.

Per abilitare la pulizia di questi utenti bisogna attivare la LDAP User Cleanup, aggiungendo a config.php la seguente riga

```php
ldapUserCleanupInterval => 10,
```

[LDAP user cleanup &mdash; Nextcloud latest Administration Manual latest documentation](https://docs.nextcloud.com/server/latest/admin_manual/configuration_user/user_auth_ldap_cleanup.html)

La pulizia può anche essere fatta a mano con questi due comandi da terminale portandosi nella cartella di Nextcloud

```bash
$ sudo -u www-data php occ ldap:show-remnants
$ sudo -u www-data php occ user:delete [user]
```

Il primo comando serve per elencare tutti gli utenti che non sono più sincronizzati con LDAP, mentre il secondo comando serve per eliminare un utente (sostituendo \[user\] con il codice dell'utente).

### Impostare scadenza sessione

Aggiungere nel file config.php i seguenti parametri allo scopo di chiudere la sessione del browser

```php
'session_lifetime' => 60*60*24, // 1 giorno
'session_keepalive' => false, // Manda in timeout la sessione anche se è aperta nel browser
'remember_login_cookie_lifetime' => 0, // 0 per non memorizzare la sessione nei cookies, altrimenti deve essere maggiore i session_lifetime 
```

Per altre informazioni consultare il seguente link e ricercare la sezione relativa alle sessioni.

[Configuration Parameters &mdash; Nextcloud latest Administration Manual latest documentation](https://docs.nextcloud.com/server/19/admin_manual/configuration_server/config_sample_php_parameters.html)

## Manutenzione

### Backups

[Backup &mdash; Nextcloud latest Administration Manual latest documentation](https://docs.nextcloud.com/server/stable/admin_manual/maintenance/backup.html)

### Aggiornamento di PHP

Ho seguito le istruzioni a questo link:

[How to install or upgrade to PHP 8.3 on Ubuntu and Debian • PHP.Watch](https://php.watch/articles/php-8.3-install-upgrade-on-debian-ubuntu)

C'erano anche delle istruzioni al seguente link, ma non mi davano troppa fiducia:

](https://help.nextcloud.com/t/nextcloud-28-with-php8-3-on-debian-ubuntu-missing-modules-solution/174766)

### Parametri

[Configuration Parameters &mdash; Nextcloud latest Administration Manual latest documentation](https://docs.nextcloud.com/server/latest/admin_manual/configuration_server/config_sample_php_parameters.html)
