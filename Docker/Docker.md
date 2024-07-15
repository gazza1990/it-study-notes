# Docker

Fonti:

- [Il Manuale Docker – Docker per Principianti](https://www.freecodecamp.org/italian/news/il-manuale-docker/#introduzione-alla-containerizzazione-e-a-docker)

- [fhsinchy (Farhan Hasin Chowdhury) · GitHub](https://github.com/fhsinchy)

## Installazione

Seguire le informazioni sul sito ufficiale dove sono dettagliate molto bene.

Per testarne il funzionamento, una volta installato si può eseguire il container "Hello-World"

```bash
$ sudo docker run hello-world
```

## Teoria

### Componenti

#### Container

Si tratta di un pacchetto eseguibile che contiene al suo interno la/e applicazione/i da eseguire e tutte le dipendenze necessarie. Va da sé che si ottiene un sistema eseguibile in un ambiente indipendente (e quindi isolato) dal sistema Host in quanto contiene già tutte le dipendenze di cui ha bisogno. 

A differenza di una Macchina Virtuale, dove il S.O. Host esegue un Hypervisor sul quale vengono avviati diversi S.O. Guest, i container utilizzano il kernel del S.O. Host e vengono avviati come delle applicazioni (utilizzando il runtime del container). Ne consegue che i container sono molto più leggeri rispetto alle macchine virtuali.

Per creare un container sono necessarie una o più immagini.

#### Immagine

Sono dei files autonomi a più livelli che agiscono come modello per la creazione di container. Sono come una copia congelata e di sola lettura di un container. Le immagini possono essere scambiate attraverso i registri.

I container sono semplicemente immagini nello stato di esecuzione. 

Quando ottieni un'immagine da internet ed esegui un container usando un'immagine, crei essenzialmente un altro livello scrivibile temporaneo sopra a quelli di sola lettura.

#### Registri Docker (Repository)

Un registro di immagini è un luogo centralizzato in cui è possibile caricare le proprie immagini e scaricare le immagini create da altri. Docker Hub è il registro pubblico predefinito per Docker.

### Architettura

#### Docker Daemon

il demone (dockerd) è un processo che continua adil *demone* (`dockerd`) è un processo che continua a essere eseguito in background e attende i comandi dal client. Il demone è in grado di gestire vari oggetti Docker.

#### Docker Client

Il client (`docker`) è un'interfaccia da riga di comando (CLI) principalmente responsabile del trasporto dei comandi lanciati dagli utenti.

#### REST API

l'API REST agisce come un ponte tra il demone e il client. Ogni comando lanciato usando il client passa per l'API per raggiungere il demone alla fine.

### Spiegazione Creazione e Avvio container

Prendiamo come esempio l'avvio del container hello-world con il seguente codice

```bash
$ sudo docker run hello-world
```

Di seguito un riepilogo delle varie fasi di esecuzione:

1. Esecuzione del comando docker run hello-world, dove hello-worl è il nome dell'immagine.

2. La CLI comunica con dockerd, dicendogli di prendere l'immagine hello-world ed eseguire un container partendo da essa.

3. dockerd cerca l'immagine in locale, se non la trova restituisce un messaggio sul terminale e la cerca nel registro pubblico e ne scarica l'ultima versione (quella con tag :latest, salvo diversamente specificato)

4. dockerd crea un nuovo container dall'immagine appena scaricata

5. dokcerd esegue il container creato generando l'output dellapplicazione contenuta nel container

## Manipolazione dei Container

### Sintassi Base di Docker

La sintassi base di docker è la seguente

```bash
$ docker <oggetto> <comando> <opzioni>
```

Dove:

- oggetto indica il tipo di oggetto Docker da manipolare. Può essere un oggetto container, image, natwork o volume.

- comadno indica l'azione che deve essere svolta dal demone, come il comando "run"

- opzioni sono i parametri che sovrascrivono il comportamento predefinito del comando, come "-p" per definire il port mapping

Seguendo questa sintassi, il comando "run" può essere scritto come segue

```bash
$ docker container run <nome immagine>
```

L'immagine va SEMPRE inseritA come ultimo parametro del comando run.

In vecchie versioni di docker non era necessario specificare l'oggetto quando si eseguivano i comandi, per retrocompatibilità è ancora possibile utilizzare la vecchia sintassi, però è sconsigliato.

### Pubblicare una porta

Essendo isolati dal sistema Host, non è possibile accedere alle applicazioni all'interno dei container se non aprendo delle porte con il comando --publish o -p.

```bash
$ docker container run -p 8080:80 nginx
```

Nell'esempio precedente viene eseguito un container partendo dall'immagine di "nginx", web server in ascolto sulla porta 80 (all'interno del container) che viene reso accessibile dall'esterno mediante la porta 8080.

Si può stoppare il container con il comando ctrl+c

### Esecuzione in background (opzione detach)

Quando si esegue un container che rimane attivo, come nginx, il terminale rimane occupato per mantenere il container in esecuzione.

Per evitare ciò e mantenere in esecuzione il container in background si può usare l'opzione --detach o -d

```bash
$ docker container run -d -p 8080:80 nginx
```

### Elencare i container

è possibile elencare i container in esecuzione con il comando ls

```bash
$ docker container ls
```

Da notare il CONTAINER ID, una porzione dell'identificativo completo del container, sarà utile per la manipolazione del container dopo averlo avviato la prima volta.

Per elencare tutti i container (anche quelli stoppati) bisogna aggiungere l'opzione --all o  -a

```bash
$ docker container ls -a
```

Questo comando visualizza diverse informazioni utili:

- ID del Container

- Immagine di partenza

- Comando

- Data Creazione

- Stato di Esecuzione (Up, Exited, Created)

- Port Mapping

- Nome

### Modificare i nomi dei container

Ogni container ha due identificatori:

- ID, una stringa composta da un numero casuale di 64 caratteri

- Nome, una combinazione di due parole casuali unite da un trattino basso

è possibile definire manualmente il Nome utilizzando l'opzione --name

```bash
$ docker container run -d -p 8081:80 --name hello-dock-container fhsinchy/hello-dock
```

è anche possibile rinominare dei container già creati con il comando rename

```bash
$ docker container rename gifted_sammet nginx-webserv
```

### Fermare un container in esecuzione

Per bloccare un container in esecuzione si usa il comando stop, indicando l'ID o il nome del container da fermare

```bash
$ docker container stop nginx-webserv
$ docker container stop b1db06e400c4
```

Il comando stop ferma l'esecuzione in modo soft, inviando un segnale SIGTERM. Se il container non si ferma entro un certo periodo, viene inviato un segnale SIGKILL che lo blocca immediatamente.

Se si vuole inviare direttamente un segnale SIGKILL si può usare il comando kill invece di stop

```bash
$ docker container kill b1db06e400c4
```

### Avviare e Riavviare un container

Per avviare un container esistente e precedentemente stoppato, si usa il comando start.

```bash
$ docker container start b1db06e400c4
```

Per riavviare un container che si trova in esecuzione si usa il comando restart

```bash
$ docker container restart nginx-webserv
```

### Creare un container senza eseguirlo

Per creare un container senza eseguirlo si usa il comando create

```bash
$ docker container create -d --name testcontcrea --publish 8080:80 fhsinchy/hello-dock
```

Una volta creato, un container può essere avviato con il comando start

```bash
$ docker container start testcontcrea 
```

Il comando run visto in precedenza è la combinazione dei comandi create e start.

### Rimuovere i container

Per eliminare i container si usa il comando rm

```bash
$ docker container rm testcontcrea 
```

è anche possibile rimuovere tutti i container sospesi (stoppati) con il comando purge

```bash
$ docker container purge
```

esiste anche l'opzione --rm per i comandi run e start che indica la volontà di rimuovere automaticamente il container non appena viene stoppato oppure non appena ne temrina l'esecuzione.

```bash
$ docker container run --rm hello-world
```

### Modalità Interattiva

Non tutte le immagini nascono per eseguire solamente dei semplici programmi, alcune possono incapsulare intere distribuzioni Linux oppure dei runtime come node oppure degli interpreti come python o php.

Queste immagini consentono di eseguire una shell di default. Nel caso di immagini di S.O. può trattarsi di sh o bash, mentre per gli interpreti o i runtime solitamente è la loro CLI predefinita.

Per eseguire un'immagine in modalità interattiva si usa l'opzione -it (scorciatoria di -i -t).

```bash
$ docker container run --rm -it ubuntu
```

Nell'esempio precedente verrà aperta una shell di root interattiva nel container.

-i o --interactive connette il terminale al flusso di input del container, in modo da poter inviare input al bash.

-t o --tty fa sì che l'utente ottenga una buona formattazione e l'esperienza del terminale nativo.

Un altro esempio può essere l'esecuzione di node

```bash
$ docker container run -it node
```

### Eseguire comandi in un container

Si può eseguire un comando specificandolo come ultimo parametro passato dopo l'immagine.

```bash
$ docker container run alpine uname -a
```

Nel precedente esempio viene eseguito il comando "uname -a" nel container avviato con l'immagine di alpine.

Un esempio un pò più complesso è la volontà di codificare una stringa usando il programma base64 (disponibile su qualsiasi SO linux o unix, ma non windows).

La sintassi generica su Linux è la seguente

```bash
$ echo -n my-secret | base64
```

Per eseguirlo tramite docker si può utilizzare un'immagine di "busybox" che è un software che raggruppa diverse applicazioni standard unix.

Il codice è il seguente

```bash
$ docker container run --rm busybox sh -c "echo -n my-secret | base64"
```

In un comando container run o start, qualsiasi cosa venga passata dopo il nome dell'immagine viene passata all'entry-point predefinito dell'immagine.

### Come lavorare con immagini eseguibili

Le immagini eseguibili sono quelle immagini che non prevedono interazioni con una shell e sono progettate per comportarsi come programmi eseguibili.

Usiamo come esempio il programma "rmbyext", un semplice script Python che elimina ricorsivamente dei files in base all'estensione.

Per installare ed eseguire il programma sul linux eseguire i seguenti comandi (richiede Git e Python installati)

```bash
$ pip install git+https://github.com/fhsinchy/rmbyext.git#egg=rmbyext
$ mkdir prova
$ cd prova
$ touch a.pdf b.pdf c.txt d.pdf e.txt
$ ls
$ rmbyext pdf
$ ls
```

Nel precedente esempio è stato scaricato il programma, poi è stata creata una cartella con dei files di prova ed infine è stato eseguito il programma che ha eliminato tutti i files con estensione \*.pdf.

L'immagine docker fhsinchy/rmbyext si comporta in modo simile. Questa immagine contiene una copia dello script rmbyext ed è configurata per eseguire lo script in una cartella /zone all'interno del container.

I container però sono isolati dal sistema locale e quindi non possono accedere al FS dell'host. Un modo per garantire a un container l'accesso diretto al file system locale è usare un bind mount.

Un bind mount consente di formare un collegamento bidirezionale tra il contenuto di un FS della cartella locale dell'host (sorgente) e un'altra cartella all'interno di un container (destinazione). In questo modo, ogni modifica effettuata nella cartella di destinazione avrà effetto sulla cartella sorgente e viceversa.

Per fare un bind mount si usa l'opzione -v o --volume

```bash
$ docker container run --rm -v $(pwd):/zone fhsinchy/rmbyext pdf
```

Nell'esempio precedente la cartella locale nella quale ci si trova (\$(pwd)) viene associata alla cartella /zone del container.

 Il comando --volume accetta 3 parametri separati da ":", il terzo è opzionale

```bash
-v <path assoluto FS Host>:<path assoluto FS container>:<accesso RW>
```

L'opzione -v è valida per i comandi run e create.

La differenza tra un'immagine normale e una eseguibile è che l'entry-point di un'immagine eseguibile è impostato su un programma personalizzato invece di sh, in questo caso il programma rmbyext.

## Manipolazione delle Immagini

Consigliato l'uso di VSCode con l'estensione Docker.

### Come creare un'immagine Docker

Nel seguente esempio verrà creata manualmente un'immagine NGINX.

Per creare un'immagine NGINX personalizzata, bisogna avere un'idea chiara dello stato finale dell'immagine. L'immagine dovrebbe essere come segue:

- Avere NGINX preinstallato, il che può essere fatto usando un gestore di pacchetti o può essere generato dalla sorgente.

- Avviare automaticamente NGINX quando in esecuzione.

Creare una nuova cartella e creare un nuovo file chiamato "Dockerfile" (senza estensione), questo file conterrà le istruzioni che, una volta processate da dockerd, daranno come risultato un'immagine.

Il contenuto di Dockerfile è il seguente

```dockerfile
FROM ubuntu:latest

EXPOSE 80

RUN apt-get update && \
    apt-get install nginx -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

CMD ["nginx", "-g", "daemon off;"]
```

Le immagini sono a più livellie in questo file, ogni riga (detta istruzione) scritta crea un livello dell'immagine.

- Ogni Dockerfile iniza con l'istruzione `FROM`, che imposta la base dell'immagine risultante. Impostando ubuntu:latest si ottengono tutte le funzionalità di ubuntu, come l'uso del gestore di pacchetti apt.

- L'istruzione `EXPOSE `è usata per indicare la porta che deve essere pubblicata. Quando si creerà il containter bisognerà utilizzare comunque l'opzione -p.

- L'istruzione `RUN `esegue un comando all'interno della shell
  
  - Il comando `apt-get update && apt-get install nginx -y` controlla le versioni aggiornate del pacchetto e installa NGINX.
  
  - Il comando `apt-get clean && rm -rf /var/lib/apt/lists/*` è usato per pulire la cache del pacchetto, per non avere fardelli inutili nell'immagine.
  
  Le istruzioni `RUN `possono essere scritte in forma `shell `(come nel precedente esempio) oppure in forma `exec`.

- L'istruzione `CMD `definisce il comando di default per l'immagine. Questa istruzioni è scritta in forma `exec`, costituita da tre parti separate:
  
  - `nginx `si riferisce all'eseguibile NGINX
  
  - `-g` e `daemon off` sono opzioni per NGINX
  
  Anche l'istruzione `CMD `può essere scritta sia in forma `exec `che in forma `shell`

A partire dal Dockerfile appena realizzato è possibile costriuire un'immagine tramite la seguente istruzione.

```bash
$ docker image build .
```

Il precedente comando cerca un Dockerfile all'interno del contesto specificato (. in questo caso, quindi la posizione corrente nel FS) e ne crea l'immagine.

Ora è possibile creare un container a partire dall'immagine appena creata utilizzando l'ID dell'immagine

```bash
$ docker container run --rm -d -name custom-nginx -p 8080:80 3199372aa3fc
```

### Come taggare le immagini Docker

Come per i container, è possibile dare un nome anche alle immagini tramite l'opzione `-t` o `--tag`.

La sintassi generica è

```bash
--tag <nome immagine>:<versione>
```

Il nome immagine è solitamente conosciuto come il repository, mentre la versione è conosciuta come il tag.

Ad esempio, se si volesse eseguire una versione specifica di mysql

```bash
$ docker container run mysql:5.7
```

Per taggare l'immagine personalizzata NGINX realizzata in precedenza si può eseguire il seguente comando

```bash
$ docker image build --tag custom-nginx:packed .
```

Si può cambiare il tag di un'immagine già creata con il comando tag

```bash
$ docker image tag 3199372aa3fc custom-nginx:packed
$ docker image tag mysql:5.7 cusmysql:myvers
```

### Come elencare e rimuovere le immagini

Si usa il comando ls per elencare tutte le immagini presenti in locale

```bash
$ docker image ls
```

Si possono cancellare le immagini con il comando rm, specificando poi gli ID o i nomi delle immagini da eliminare

```bash
$ docker image rm 3199372aa3fc
$ docker image rm custom-nginx:packed
```

Per eliminare tutte le immagini sospese e non taggate si può usare il comando prune

```bash
$ docker image prune --force
```

L'opzione --force o -f salta ogni domanda di conferma.

Si può usare anche l'opzione --all o -a per rimuovere tutte le immagini.

### Comprendere i livelli di un'immagine Docker

Per visualizzare i livelli di un'immagine si usa il comando history.

```bash
$ docker image history custom-nginx
# IMAGE               CREATED             CREATED BY                                      SIZE                COMMENT
# 7f16387f7307        5 minutes ago       /bin/sh -c #(nop)  CMD ["nginx" "-g" "daemon…   0B                             
# 587c805fe8df        5 minutes ago       /bin/sh -c apt-get update &&     apt-get ins…   60MB                
# 6fe4e51e35c1        6 minutes ago       /bin/sh -c #(nop)  EXPOSE 80                    0B                  
# d70eaf7277ea        17 hours ago        /bin/sh -c #(nop)  CMD ["/bin/bash"]            0B                  
# <missing>           17 hours ago        /bin/sh -c mkdir -p /run/systemd && echo 'do…   7B                  
# <missing>           17 hours ago        /bin/sh -c [ -z "$(apt-get indextargets)" ]     0B                  
# <missing>           17 hours ago        /bin/sh -c set -xe   && echo '#!/bin/sh' > /…   811B                
# <missing>           17 hours ago        /bin/sh -c #(nop) ADD file:435d9776fdd3a1834…   72.9MB
```

Nel precedente esempio sono presenti 8 livelli, quello superiore è l'ultimo (quello che in solito si usa per eseguire il container) e mano mano che si scende diventano meno recenti.

Ora, diamo un'occhiata da vicino alle immagini partendo dall'immagine d70eaf7277ea fino a 7f16387f7307. Ignorerò i quattro livelli inferiori in cui IMAGE è <missing> (mancante), che non sono una nostra preoccupazione.

- d70eaf7277ea è stato creato da /bin/sh -c #(nop)  CMD ["/bin/bash"] che indica che la shell predefinita all'interno di Ubuntu è stata caricata con successo.

- 6fe4e51e35c1 è stato creato da /bin/sh -c #(nop)  EXPOSE 80 che era la seconda istruzione nel codice.

- 587c805fe8df è stato creato da /bin/sh -c apt-get update && apt-get install nginx -y && apt-get clean && rm -rf /var/lib/apt/lists/*, la terza istruzione nel codice. Puoi anche vedere che questa immagine ha una dimensione di 60MB visti tutti i pacchetti che sono stati installati durante l'esecuzione di questa istruzione.

- Infine, il livello più alto 7f16387f7307 è stato creato da /bin/sh -c #(nop)  CMD ["nginx", "-g", "daemon off;"], che definisce il comando di default per quest'immagine.

L'immagine comprende molti livelli di sola lettura, ognuno dei quali registra un nuovo set di cambiamenti allo stato innescati da determinate istruzioni. 

Quando si avvia un container utilizzando un'immagine si ottiene un nuovo livello scrivibile sopra agli altri livelli.

Il concetto tecnico che si trova alla base di questa struttura a livelli si chiama Union File System.

### Eseguire il build di NGINX dall sorgente

In questa sezione verrà effettuato il build di un'immagine NGINX dalla sorgente invece che dal gestore di pacchetti apt.

Per prima cosa bisogna procurarsi il sorgente (da GitHub o dal sito di NGINX), quello utilizzato in questo esempio sarà nginx-1.19.2.tar.gz.

I passaggi da fare saranno i seguenti:

- Ottenere una buona immagine di base per il build dell'applicazione.
- Installare le dipendenze di build necessarie sull'immagine di base.
- Copiare il file `nginx-1.19.2.tar.gz` all'interno dell'immagine.
- Estrarre i contenuti dell'archivio e liberarsene.
- Configurare il build, compilare e installare il programma usando lo strumento `make`.
- Liberarsi del codice sorgente estratto.
- Lanciare l'eseguibile `nginx`.

Creare una cartella, entrarci e creare il Dockerfile con il seguente contenuto

```dockerfile
FROM ubuntu:latest

RUN apt-get update && \
    apt-get install build-essential\ 
                    libpcre3 \
                    libpcre3-dev \
                    zlib1g \
                    zlib1g-dev \
                    libssl1.1 \
                    libssl-dev \
                    -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY nginx-1.19.2.tar.gz .

RUN tar -xvf nginx-1.19.2.tar.gz && rm nginx-1.19.2.tar.gz

RUN cd nginx-1.19.2 && \
    ./configure \
        --sbin-path=/usr/bin/nginx \
        --conf-path=/etc/nginx/nginx.conf \
        --error-log-path=/var/log/nginx/error.log \
        --http-log-path=/var/log/nginx/access.log \
        --with-pcre \
        --pid-path=/var/run/nginx.pid \
        --with-http_ssl_module && \
    make && make install

RUN rm -rf /nginx-1.19.2

CMD ["nginx", "-g", "daemon off;"]
```

Il Dockerfile si comone di sette passaggi:

- L'istruzione `FROM` definisce Ubuntu come immagine di base creando un ambiente ideale per il build di qualsiasi applicazione.
- L'istruzione `RUN` installa i pacchetti standard necessari per il build di NGINX dalla sorgente.
- L'istruzione `COPY` è qualcosa di nuovo. Questa istruzione è responsabile di copiare il file `nginx-1.19.2.tar.gz` all'interno dell'immagine. La sintassi generica dell'istruzione `COPY` è `COPY <sorgente> <destinazione>`, dove la sorgente è nel tuo file system locale e la destinazione è all'interno dell'immagine. `.` come destinazione si riferisce alla cartella di lavoro all'interno dell'immagine che è di default `/`, se non impostata diversamente.
- La seconda istruzione `RUN` estrae il contenuto dall'archivio usando `tar` e in seguito se ne sbarazza.
- Il file di archivio contiene una cartella chiamata `nginx-1.19.2` con il codice sorgente. Quindi, nel prossimo passaggio, dovrai spostarti in questa directory (`cd`) e svolgere il processo di build. Se vuoi imparare di più su questo argomento puoi leggere [questo articolo](https://itsfoss.com/install-software-from-source-code/) (risorsa in inglese).
- Una volta che il build e l'installazione sono completati, rimuovi la cartella `nginx-1.19.2` usando il comando `rm`.
- Nello step finale, avvia NGINX nella modalità a processo singolo, proprio come hai fatto prima.

Per eseguire il build eseguire il seguente comando

```bash
$ docker image build --tag custom-nginx:built .
```

Questo codice va bene, ma ci sono dei punti in cui possiamo fare dei miglioramenti.

- Invece di scrivere il nome del file in codifica fissa come `nginx-1.19.2.tar.gz`, puoi creare un argomento usando l'istruzione `ARG`. In questo modo, sarai in grado di cambiare la versione o il nome del file cambiando l'argomento.
- Invece di scaricare manualmente l'archivio, puoi far scaricare il file al demone durante il processo di build. Esiste un'altra istruzione come `COPY`, chiamata `ADD`, che ti consente di aggiungere dei file da internet.

Apri il file `Dockerfile` e aggiorna il suo contenuto come segue:

```dockerfile
FROM ubuntu:latest

RUN apt-get update && \
    apt-get install build-essential\ 
                    libpcre3 \
                    libpcre3-dev \
                    zlib1g \
                    zlib1g-dev \
                    libssl1.1 \
                    libssl-dev \
                    -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

ARG FILENAME="nginx-1.19.2"
ARG EXTENSION="tar.gz"

ADD https://nginx.org/download/${FILENAME}.${EXTENSION} .

RUN tar -xvf ${FILENAME}.${EXTENSION} && rm ${FILENAME}.${EXTENSION}

RUN cd ${FILENAME} && \
    ./configure \
        --sbin-path=/usr/bin/nginx \
        --conf-path=/etc/nginx/nginx.conf \
        --error-log-path=/var/log/nginx/error.log \
        --http-log-path=/var/log/nginx/access.log \
        --with-pcre \
        --pid-path=/var/run/nginx.pid \
        --with-http_ssl_module && \
    make && make install

RUN rm -rf /${FILENAME}}

CMD ["nginx", "-g", "daemon off;"]
```

Il codice è quasi identico al blocco di codice precedente, eccetto per delle nuove istruzioni `ARG`  e per l'uso dell'istruzione `ADD` . La spiegazione per il codice aggiornato è riportata di seguito:

- L'istruzione `ARG` ti permette di dichiarare variabili come in altri linguaggi. Queste variabili o argomenti possono essere consultate in seguito usando la sintassi `${nome argomento}`. Qui ho usato `nginx-1.19.2` come nome del file e `tar.gz` come estensione in due argomenti separati. In questo modo posso passare a nuove versioni di NGINX o al formato archivio facendo una modifica in un solo posto. Nel codice qui sopra, ho aggiunto i valori predefiniti alle variabili. I valori delle variabili possono anche essere passati come opzioni del comando `image build`. Puoi consultare i [riferimenti ufficiali](https://docs.docker.com/engine/reference/builder/#arg) per maggiori dettagli.
- Nell'istruzione `ADD`, ho formato l'URL del download dinamicamente, usando gli argomenti dichiarati in precedenza. La riga `https://nginx.org/download/${FILENAME}.${EXTENSION}` darà come risultato `https://nginx.org/download/nginx-1.19.2.tar.gz` durante il processo di build. Puoi cambiare la versione del file e l'estensione soltanto in un punto grazie all'istruzione `ARG`.
- L'istruzione `ADD` non estrae i file ottenuti da internet in modo predefinito, per cui l'utilizzo di `tar` nella riga 18.

Per fare la build dell'immagine utilizziamo le stesse istruzioni precedenti

```bash
$ docker image build --tag custom-nginx:built .
```

### Ottimizzare le immagine Docker

L'immagine creata nella sezione precedente non è ottimizzata, basta verificarne le dimensioni con il seguente comando

```bash
$ docker image ls
# REPOSITORY         TAG       IMAGE ID       CREATED          SIZE
# custom-nginx       built     1f3aaf40bb54   16 minutes ago   343MB
```

L'immagine ufficiale di NGINX è circa 1/3 di questa.

Andando ad analizzare il Dockerfile, nella prima istruzione RUN ci sono molti pacchetti che servono per fare il build di NGINX, ma che poi non sono necessari per la sua esecuzione.

è una buona idea disinstallare i pacchetti quando non servono più, di seguito il Dockerfile aggiornato

```dockerfile
FROM ubuntu:latest

EXPOSE 80

ARG FILENAME="nginx-1.19.2"
ARG EXTENSION="tar.gz"

ADD https://nginx.org/download/${FILENAME}.${EXTENSION} .

RUN apt-get update && \
    apt-get install build-essential \ 
                    libpcre3 \
                    libpcre3-dev \
                    zlib1g \
                    zlib1g-dev \
                    libssl1.1 \
                    libssl-dev \
                    -y && \
    tar -xvf ${FILENAME}.${EXTENSION} && rm ${FILENAME}.${EXTENSION} && \
    cd ${FILENAME} && \
    ./configure \
        --sbin-path=/usr/bin/nginx \
        --conf-path=/etc/nginx/nginx.conf \
        --error-log-path=/var/log/nginx/error.log \
        --http-log-path=/var/log/nginx/access.log \
        --with-pcre \
        --pid-path=/var/run/nginx.pid \
        --with-http_ssl_module && \
    make && make install && \
    cd / && rm -rfv /${FILENAME} && \
    apt-get remove build-essential \ 
                    libpcre3-dev \
                    zlib1g-dev \
                    libssl-dev \
                    -y && \
    apt-get autoremove -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

CMD ["nginx", "-g", "daemon off;"]
```

Da notare che ora tutti i comandi si trovano sotto un'unica istruzione RUN, questo perchè ogni istruzione crea un livello dell'immagine, quindi utilizzando istruzioni RUN separate per installare e disinstallare i pacchetti verrebbero creati diversi livelli e sebbene l'immagine finale non avrebbe i pacchetti rimossi, la loro dimensione verrebbe comunque aggiunta a quest'ultima in quanto i pacchetti si trovano in uno dei livelli inferiori.

Facendo il build da questo nuovo Dockerfile si otterrà un'immagine molto più piccola.

### Alpine Linux

Alpine è una distribuzione, costruita attorno a musl libc e busybox ed è leggera. Mentre l'ultima immagine di ubuntu pesa circa 28MB, alpine pesa 2.8MB.

Oltre alla caratteristica leggerezza, Alpine è anche sicuro ed è molto più adatto per creare container rispetto ad altre distribuzioni.

Alpine non è user-friendly quando altre distribuzioni, ma è comunque abbastanza semplice.

Di seguito il Dockerfile per creare la precedente immagine di NGINX utilizzando Alpine.

```dockerfile
FROM alpine:latest

EXPOSE 80

ARG FILENAME="nginx-1.19.2"
ARG EXTENSION="tar.gz"

ADD https://nginx.org/download/${FILENAME}.${EXTENSION} .

RUN apk add --no-cache pcre zlib && \
    apk add --no-cache \
            --virtual .build-deps \
            build-base \ 
            pcre-dev \
            zlib-dev \
            openssl-dev && \
    tar -xvf ${FILENAME}.${EXTENSION} && rm ${FILENAME}.${EXTENSION} && \
    cd ${FILENAME} && \
    ./configure \
        --sbin-path=/usr/bin/nginx \
        --conf-path=/etc/nginx/nginx.conf \
        --error-log-path=/var/log/nginx/error.log \
        --http-log-path=/var/log/nginx/access.log \
        --with-pcre \
        --pid-path=/var/run/nginx.pid \
        --with-http_ssl_module && \
    make && make install && \
    cd / && rm -rfv /${FILENAME} && \
    apk del .build-deps

CMD ["nginx", "-g", "daemon off;"]
```

Il codice è quasi identico, eccetto che per alcuni cambiamenti. Elencherò le modifiche e le spiegherò passo passo:

- Invece di usare `apt-get install` per installare i pacchetti, usiamo `apk add`. L'opzione `--no-cache` vuol dire che i pacchetti scaricati non saranno salvati nella cache. Allo stesso modo, useremo `apk del` invece di `apt-get remove` per disinstallare i pacchetti.
- L'opzione `--virtual` per il comando `apk add` viene usata per impacchettare un gruppo di pacchetti in un solo pacchetto virtuale per una gestione più semplice. I pacchetti che sono necessari solo per il build del programma sono etichettati come `.build-deps` e vengono rimossi nella riga 29 eseguendo il comando `apk del .build-deps`. Per saperne di più puoi consultare la [documentazione ufficiale](https://docs.alpinelinux.org/user-handbook/0.1a/Working/apk.html#_virtuals).
- I nomi dei pacchetti sono un po' diversi qui. Di solito, ogni distribuzione Linux ha un suo repository dove cercare pacchetti disponibile per chiunque. Se conosci i pacchetti richiesti per una certa attività, puoi andare dritto all'apposito repository per una distribuzione e cercarlo. Puoi cercare i pacchetti per Alpine Linux [qui](https://pkgs.alpinelinux.org/packages).

Ora non resta che provare a fare la build di questo Dockerfile e verificare la dimensione che dovrebbe essere inferiore a 20MB.

### Creare immagini Docker eseguibili

In questa sezione verrà creata un'immagine con il software rmbyext precedentemente utilizzato, il prerequisito è che è necessario procurarsi il codice sorgente del programma (da GitHub).

Prima di iniziare a lavorare sul `Dockerfile`, ragionare su come dovrebbe essere l'output finale, che in questo caso dovrebbe essere qualcosa del genere:

- L'immagine dovrebbe avere Python preinstallato.
- Dovrebbe contenere una copia dello script `rmbyext`.
- Dovrebbe essere definita una cartella di lavoro in cui verrà eseguito lo script.
- Lo script `rmbyext` dovrebbe essere impostato come entry-point in modo che l'immagine possa prendere i nomi delle estensioni come argomenti.

Per fare il build dell'immagine sopra menzionata, segui i seguenti passaggi:

- Ottieni una buona immagine base per eseguire gli script Python, come [python](https://hub.docker.com/_/python).
- Definisci la cartella di lavoro come una cartella di facile accesso.
- Installa Git in modo che lo script possa essere installato dal repository GitHub.
- Installa lo script usando Git e pip.
- Sbarazzati dei pacchetti di build non necessari.
- Imposta `rmbyext` come entry-point dell'immagine.

Ora crea un nuovo `Dockerfile` nella cartella `rmbyext` e inserisci il seguente codice al suo interno:

```dockerfile
FROM python:3-alpine

WORKDIR /zone

RUN apk add --no-cache git && \
    pip install git+https://github.com/fhsinchy/rmbyext.git#egg=rmbyext && \
    apk del git

ENTRYPOINT [ "rmbyext" ]
```

La spiegazione delle istruzioni in questo file è la seguente:

- L'istruzione `FROM` definisce [python](https://hub.docker.com/_/python) come immagine base, creando un ambiente ideale per eseguire gli script Python. Il tag `3-alpine` indica che vuoi la variante Alpine di Python 3.
- L'istruzione `WORKDIR` imposta la cartella di lavoro predefinita su `/zone`. Il nome della cartella di lavoro è completamente casuale qui. Ho usato zone, ma tu puoi usare il nome che preferisci.
- Dato che lo script `rmbyext` è installato da GitHub, `git` è una dipendenza di installazione. L'istruzione `RUN` nella riga 5, installa `git`, poi installa lo script `rmbyext` usando Git e pip. In seguito, si libera anche di `git`.
- Infine, nella riga 9, l'istruzione `ENTRYPOINT` imposta lo script `rmbyext` come entry-point dell'immagine.

In questo file, nell'ultima riga c'è la magia che trasforma questa immagine apparentemente normale in un'immagine eseguibile.

Quindi fare il build

```bash
$ docker image build --tag rmbyext .
```

Quando non si fornisce nessun tag nel processo di build, viene impostato di default `latest`.

### Condividere immagini Docker online

è necessario possede un account di qualsiasi registro online, il più famoso è [Docker Hub](https://hub.docker.com/).

Con l'account gratuito è possibile ospitare repository pubblici senza limiti e un repository privato.

Una volta creato l'account, bisogna accedere usando la CLI di docker

```bash
$ docker login
```

Per condividere un'immagine online occorre che questa sia taggata con la seguente sintassi `<username docker hub>/<nome immagine>:<tag immagine>`.

Prima di tutto va eseguito il build dell'immagine.

```bash
$ docker image build --tag fhsinchy/custom-nginx:latest --file Dockerfile.built .
```

In questo esempio è stato anche specificato un file preciso (Dockerfile.built).

Una volta caricata, il nome dell'immagine non può più essere cambiato, mentre il tag si può cambiare tutte le volte che si vuole e solitamente riflesse la versione del software o diversi tipi di build.

Se non si specifica nessun tag, l'immagine sarà automaticamente taggata come `latest`.

Una volta che il build è stato effettuato, si può caricare l'immagine eseguendo il seguente comando

```bash
docker image push fhsinchy/custom-nginx:latest
```

## Containerizzare una App Javascript

In questa sezione, lavorerai con il codice sorgente dell'immagine [fhsinchy/hello-dock](https://hub.docker.com/r/fhsinchy/hello-dock) usata nella sezione precedente. Nel processo di containerizzazione di questa semplice applicazione, parleremo dei volumi e dei build multi-stadio, due dei concetti più importanti in Docker.

### Come scrivere il Dockerfile di sviluppo

Per iniziare, aprire la cartella dove è stato copiato il codice dell'applicazione hello-dock.

Questo è un progetto JavaScript molto semplice realizzato dal progetto [vitejs/vite](https://github.com/vitejs/vite).

All'inizio si fa un piano di come si vuole eseguire l'applicazione. come il seguente:

- Ottenere una buona immagine base per eseguire applicazioni JavaScript, come [node](https://hub.docker.com/_/node).
- Definire la cartella di lavoro nell'immagine.
- Copiare il file `package.json` nell'immagine.
- Installare le dipendenze necessarie.
- Copiare  il resto dei file del progetto.
- Avviare il server di sviluppo di `vite` eseguendo il comando `npm run dev`.

Mettendo il piano sopra menzionato all'interno di `Dockerfile.dev`, il file dovrebbe avere questo aspetto:

```dockerfile
FROM node:lts-alpine

EXPOSE 3000

USER node

RUN mkdir -p /home/node/app

WORKDIR /home/node/app

COPY ./package.json .
RUN npm install

COPY . .

CMD [ "npm", "run", "dev" ]
```

La spiegazione di questo codice è la seguente:

- L'istruzione `FROM` imposta l'immagine ufficiale di Node.js come base, rendendo disponibili tutte le qualità di Node.js necessarie per eseguire qualsiasi applicazione JavaScript. Il tag `lts-alpine` indica che vuoi usare la variante Alpine, versione con supporto a lungo termine dell'immagine. I tag disponibili e la documentazione necessaria può essere trovata sulla pagina [node](https://hub.docker.com/_/node).
- L'istruzione `USER` imposta l'utente predefinito per l'immagine come `node`. Di default Docker esegue i container come utente root. Ma secondo le buone pratiche di [Docker e Node.js](https://github.com/nodejs/docker-node/blob/master/docs/BestPractices.md) questo può causare problemi di sicurezza. Quindi è meglio non farlo quando possibile. L'immagine di node ha un utente non-root chiamato `node`, che puoi impostare come utente di default usando l'istruzione `USER`.
- L'istruzione `RUN mkdir -p /home/node/app` crea una cartella chiamata `app` all'interno della cartella home dell'utente `node`. La cartella home per qualsiasi utente non-root in Linux è solitamente `/home/<nome utente>` di default.
- Poi l'istruzione `WORKDIR` imposta la cartella di lavoro predefinita con la nuova cartella `/home/node/app` appena creata. Di default, la cartella di lavoro di ogni immagini è la radice. Dato che non vuoi file non necessari sparsi in giro nella tua directory root, è bene cambiare la cartella di lavoro predefinita in qualcosa di più ragionevole come `/home/node/app` o quello che preferisci. Questa cartella di lavoro sarà applicabile a ogni istruzione `COPY`, `ADD`, `RUN` e `CMD` successiva.
- L'istruzione `COPY` copia il file `package.json` che contiene informazioni riguardo a tutte le dipendenze necessarie per l'applicazione. L'istruzione `RUN` esegue il comando `npm install`, che è il comando predefinito per installare le dipendenze usando un file `package.json` nei progetti Node.js. Il punto `.` alla fine rappresenta la cartella di lavoro.
- La seconda istruzione `COPY` copia il resto del contenuto della cartella corrente (`.`) del file system host nella cartella di lavoro (`.`) all'interno dell'immagine.
- Infine, l'istruzione `CMD` imposta il comando predefinito per l'immagine, che è `npm run dev` scritto in forma `exec`.
- Il server di sviluppo `vite` gira di default sulla porta `3000`, e aggiungere un comando `EXPOSE` sembrava una buona idea.

Per fare il build dell'immagine da `Dockerfile.dev` puoi eseguire il seguente comando

```bash
$ docker image build --file Dockerfile.dev --tag hello-dock:dev .
```

Quindi eseguire il container

```bash
$ docker container run \
    --rm \
    --detach \
    --publish 3000:3000 \
    --name hello-dock-dev \
    hello-dock:dev
```

Visitando `http://127.0.0.1:3000` si vedrà l'applicazione `hello-dock` in azione.

Il codice va bene, ma ci sono alcune cose che si possono migliorare che verranno descritte nelle sezioni seguenti.

### Come lavorare con i bind mount (volumi)

I Server di sviluppo di vari framework (come Flask o Vite), hanno una funzionalità di ricaricamento rapido, ovvero se viene fatta una modifica al codice, questa modifica può essere vista immediatamente, senza riavviare il WebServer.

Se però si lavora con un WebServer che gira in un container, le modifiche non verranno apportato subito perchè il codice si trova sul proprio FS, ma l'applicazione in esecuzione si trova interamente nel container.

Per risolvere questo problema si può fare uso di un  [bind mount](https://docs.docker.com/storage/bind-mounts/), in modo da montare all'interno del container una cartella del FS dell'Host. In questo modo ogni cambiamento fatto alla cartella collegata verrà riflesso anche nel container, innescando il ricaricamente rapido di vite.

I bind mount possono essere creati usando l'opzione --volume o -v per i comandi container run e start.

```bash
--volume <percorso assoluto cartella file system locale>:<percorso assoluto cartella file system container>:<accesso read write>
```

Eseguire quindi un nuovo container aggiungendo il bind mount

```bash
$ docker container run \
    --rm \
    --publish 3000:3000 \
    --name hello-dock-dev \
    --volume $(pwd):/home/node/app \
    hello-dock:dev
```

è stata omessa l'opzione `--detach` per mostrare che l'applicazione, adesso, non funziona. Questo perchè le dipendenze di un progetto Node.js risiedono nella cartella node_modules nella radice del progetto, ma adesso che si sta montando la radice del progetto sul FS dell'host, il contenuto del container viene sostituito inseme alla cartella node_modules, ciò significa che il pacchetto vite è sparito.

Questo problema si risolve con i volumi anonimi.

### Lavorare con volumi anonimi

Nei volumi anonimi non è necessario specificare la directory sorgente (FS dell'host). La sintassi generica è la seguente

```bash
--volume <percorso assoluto cartella file system container>:<accesso read write>
```

Quindi il comando finale per avviare il container `hello-dock` con entrambi i volumi è il seguente:

```bash
$ docker container run \
    --rm \
    --detach \
    --publish 3000:3000 \
    --name hello-dock-dev \
    --volume $(pwd):/home/node/app \
    --volume /home/node/app/node_modules \
    hello-dock:dev
```

Docker prenderà l'intera cartella `node_modules` dall'interno del container, la metterà da parte in un'altra cartella gestita dal demone di Docker sul file system host e la monterà come `node_modules` all'interno del container.

### Eseguire dei build multi-stadio

Quando si vuole fare il build dell'immagine in modalità di produzione, vengono fuori nuove sfide.

In modalità di sviluppo, il comando `npm run serve` avvia un server di sviluppo che serve l'applicazione all'utente. Non serve soltanto i file all'utente, ma fornisce anche la funzionalità di ricaricamento rapido.

In modalità di produzione, il comando `npm run build` compila tutto il codice JavaScript in file HTML statici, CSS e JavaScript. Per eseguire questi file, non c'è bisogno di node o di altre dipendenze di runtime. Tutto ciò di cui hai bisogno è un server come `nginx`, ad esempio.

Per creare un'immagine in cui l'applicazione viene eseguita in modalità di produzione, potresti seguire i seguenti passaggi:

- Usa `node` come immagine base e fai il build dell'applicazione.
- Installa `nginx` all'interno dell'immagine di node e usala per servire i file statici.

Questo approccio è assolutamente valido, ma il problema è che l'immagine di `node` è grande e la maggior parte di ciò che contiene non è necessaria per servire i file statici. Un approccio migliore per questo caso è il seguente:

- Usa `node` come immagine base e fai il build dell'applicazione.
- Copia i file creati usando l'immagine di `node` in un'immagine di `nginx`.
- Crea l'immagine finale sulla base di `nginx` e scarta tutta la roba correlata a `node`.

In questo modo, l'immagine contiene solo i file che sono necessari e diventa molto gestibile.

Questo approccio è un build multi-stadio. Per eseguirlo, crea un nuovo `Dockerfile` all'interno della directory del progetto `hello-dock` e inserisci il seguente contenuto:

```dockerfile
FROM node:lts-alpine as builder

WORKDIR /app

COPY ./package.json ./
RUN npm install

COPY . .
RUN npm run build

FROM nginx:stable-alpine

EXPOSE 80

COPY --from=builder /app/dist /usr/share/nginx/html
```

Come puoi vedere, `Dockerfile` somiglia molto ai precedenti con qualche particolarità. La spiegazione per questo file è la seguente:

- Nella riga 1 inizia il primo stadio di build usando `node:lts-alpine` come immagine base. La sintassi `as builder` assegna a questo stadio un nome con cui farvi riferimento in seguito.
- Dalla riga 3 alla 9, cose standard che hai già visto molte volte. Il comando `RUN npm run build` compila l'intera applicazione e la mette da parte nella cartella `/app/dist` dove `/app` è la cartella di lavoro e `/dist` è la cartella di output predefinita per le applicazioni `vite`.
- Nella riga 11 inizia il secondo stadio del build usando `nginx:stable-alpine` come immagine base.
- Il server NGINX gira sulla porta 80 di default, di qui la riga `EXPOSE 80`.
- L'ultima riga è un'istruzione `COPY`. La parte `--from=builder` indica che vuoi copiare dei file dallo stadio `builder`. Oltre questo, si tratta di un'istruzione copy standard dove `/app/dist` è la sorgente e `/usr/share/nginx/html` è la destinazione. La destinazione usata qui è il percorso predefinito per NGINX, in modo che ogni file statico che metti al suo interno sarà servito automaticamente.

L'immagine risultante è una base `nginx` contenente solo i file necessari per eseguire l'applicazione. Per svolgere il build di quest'immagine esegui il seguente comando.

```bash
$ docker image build --tag hello-dock:prod .
```

Una volta completato il build è possibile eseguire il container

```bash
$ docker container run \
    --rm \
    --detach \
    --name hello-dock-prod \
    --publish 8080:80 \
    hello-dock:prod
```

### Ignorare i file non necessari

Lavorando con git si utilizza il file .gitignore che contiene una lista di file e cartelle da escludere dal repository.

Con docker esiste qualcosa di simile, il file .dockerignore che contiene una lista di file e cartelle da escludere dal build di un'immagine, di seguito un esempio.

```.dockerignore
.git
*Dockerfile*
*docker-compose*
node_modules
```

Il file `.dockerignore` deve essere nel contesto (cartella) di build. I file e le cartelle menzionate qui saranno ignorati dall'istruzione `COPY`. Ma se usi un bind mount, il file `.dockerignore` non avrà effetto.

## Manipolazione base delle reti

Quando si lavora su progetti che prevedono la presenza di più container, diventa necessario acqusire almeno  una familiarità di base con il networking di docker.

I container sono ambienti isolati, ma in alcuni casi è necessario farli comunicare fra loro, come nel caso in cui abbiamo un'applicazione node alimentata da Express.js in un container che deve comunicare con un database PostgreSQL contenuto in un altro container.

Si potrebbe pensare a due soluzioni:

- Accedere al server del database usando una porta esposta.
- Accedere al server del database usando il suo indirizzo IP e la porta predefinita.

La prima implica di esporre una porta dal container `postgres` e `notes-api` si connetterà attraverso di essa. Supponi che la porta esposta dal container `postgres` sia 5432. Se provi a connetterti a `127.0.0.1:5432` dal container `notes-api`, scoprirai che `notes-api` non è in grado di trovare il server del database.

La ragione è che quando dici `127.0.0.1` all'interno del container `notes-api`, fai riferimento al `localhost` soltanto di quel container. Il server `postgres` non esiste lì. Come risultato, l'applicazione `notes-api` fallisce la connessione.

La seconda soluzione che puoi escogitare è trovare l'esatto indirizzo IP del container `postgres` usando il comando `container inspect` e usarlo con la porta. Assumendo che il nome del container `postgres` sia `notes-api-db-server`, puoi ottenere facilmente l'indirizzo IP eseguendo il seguente comando:

```bash
docker container inspect --format='{{range .NetworkSettings.Networks}} {{.IPAddress}} {{end}}' notes-api-db-server
#  172.17.0.2
```

Posto che la porta di default per `postgres` è `5432`, puoi accedere al server del database molto facilmente connettendoti a `172.17.0.2:5432` dal container `notes-api`.

Anche in questo approccio ci sono dei problemi. Usare l'indirizzo IP per fare riferimento a un container non è raccomandato. Inoltre, se il container viene distrutto e ricreato, l'indirizzo IP può cambiare. Tenere traccia dei cambiamenti dell'indirizzo IP può essere un po' caotico.

La soluzione corretta è che bisogna metterli sotto una rete bridge definita dall'utente.

### Basi delle reti

Una rete Docker è un altro oggetto logico come un container o un'immagine.

Per elencare le reti nel sistema, eseguire il seguente comando

```bash
$ docker network ls
```

La colonna DRIVER nel risultato del precedente comando indica il tipo di rete.

Di default, Docker ha cinque driver di rete:

- `bridge` - Il driver di rete predefinito in Docker. Può essere usato quando più container sono in esecuzione in modalità standard e devono comunicare tra di loro.
- `host` - Rimuove completamente l'isolamento della rete. Ogni container in esecuzione sotto una rete `host` è praticamente collegato alla rete del sistema host.
- `none` - Questo driver disabilita del tutto la rete per i container. Non ho ancora trovato dei casi in cui utilizzarlo.
- `overlay` - Usato per connettere più demoni Docker tra computer; fuori dallo scopo di questo libro.
- `macvlan` - Permette di assegnare indirizzi MAC ai container, facendoli funzionare come dispositivi fisici in una rete.

### Come creare un bridge

Docker possiede una rete bridge di default, denominata bridge, ogni container che viene eseguito si collegherà automaticamente a questa rete.

```bash
docker container run --rm --detach --name hello-dock --publish 8080:80 fhsinchy/hello-dock
# a37f723dad3ae793ce40f97eb6bb236761baa92d72a2c27c24fc7fda0756657d

docker network inspect --format='{{range .Containers}}{{.Name}}{{end}}' bridge
# hello-dock
```

I container collegati alla rete bridge predefinita possono comunicare tra loro usando gli indirizzi IP, di cui ne è stato sconsigliato l'uso nella sezione precedente.

Tuttavia, un bridge definito da un utente ha delle funzionalità extra rispetto a quello di default. Secondo la [documentazione ufficiale](https://docs.docker.com/network/bridge/#differences-between-user-defined-bridges-and-the-default-bridge) su questo argomento, alcune funzionalità aggiuntive importanti sono le seguenti:

- **I bridge definiti da un utente offrono la risoluzione DNS automatica tra container**: ciò vuol dire che i container collegati alla stessa rete possono comunicare tra loro usando il nome del container. Dunque, se hai due container chiamati `notes-api` e `notes-db` il container dell'API sarà in grado di connettersi al container del database usando il nome `notes-db`.
- **I bridge definiti da un utente offrono un migliore isolamento**: tutti i container sono collegati alla rete bridge di default, il che può causare dei conflitti tra di loro. Collegare i container a un bridge definito da un utente può garantire un isolamento migliore.
- **I container possono essere collegati e scollegati al volo da reti definite da un utente**: durante la vita di un container, puoi connetterlo e disconnetterlo da reti definite da un utente molto semplicemente. Per rimuovere il container dalla rete bridge predefinita, devi fermare il container e ricrearlo con diverse opzioni di rete.

Una rete può essere creata usando il comando `network create`, di seguito un esempio.

```bash
docker network create skynet

# 7bd5f351aa892ac6ec15fed8619fc3bbb95a7dcdd58980c28304627c8f7eb070

docker network ls

# NETWORK ID     NAME     DRIVER    SCOPE
# be0cab667c4b   bridge   bridge    local
# 124dccee067f   host     host      local
# 506e3822bf1f   none     null      local
# 7bd5f351aa89   skynet   bridge    local
```

Al momento nessun container è collegato alla nuova rete.

### Come collegare un container a una rete

Esistono principalmente due modi per collegare un container a una rete. Il primo modo è usare il comando `network connect`, di seguito un esempio.

```bash
docker network connect skynet hello-dock

docker network inspect --format='{{range .Containers}} {{.Name}} {{end}}' skynet

#  hello-dock

docker network inspect --format='{{range .Containers}} {{.Name}} {{end}}' bridge

#  hello-dock
```

In questo momento il container hello-dock è collegato sia alla rete skynet che alla rete bridge di default.

Il secondo metodo per collegare un container a una rete è utilizzare l'opzione --network con docker run o create.

```bash
docker container run --network skynet --rm --name alpine-box -it alpine sh

# lands you into alpine linux shell

/ # ping hello-dock

# PING hello-dock (172.18.0.2): 56 data bytes
# 64 bytes from 172.18.0.2: seq=0 ttl=64 time=0.191 ms
# 64 bytes from 172.18.0.2: seq=1 ttl=64 time=0.103 ms
# 64 bytes from 172.18.0.2: seq=2 ttl=64 time=0.139 ms
# 64 bytes from 172.18.0.2: seq=3 ttl=64 time=0.142 ms
# 64 bytes from 172.18.0.2: seq=4 ttl=64 time=0.146 ms
# 64 bytes from 172.18.0.2: seq=5 ttl=64 time=0.095 ms
# 64 bytes from 172.18.0.2: seq=6 ttl=64 time=0.181 ms
# 64 bytes from 172.18.0.2: seq=7 ttl=64 time=0.138 ms
# 64 bytes from 172.18.0.2: seq=8 ttl=64 time=0.158 ms
# 64 bytes from 172.18.0.2: seq=9 ttl=64 time=0.137 ms
# 64 bytes from 172.18.0.2: seq=10 ttl=64 time=0.145 ms
# 64 bytes from 172.18.0.2: seq=11 ttl=64 time=0.138 ms
# 64 bytes from 172.18.0.2: seq=12 ttl=64 time=0.085 ms

--- hello-dock ping statistics ---
13 packets transmitted, 13 packets received, 0% packet loss
round-trip min/avg/max = 0.085/0.138/0.191 ms
```

Come puoi vedere, eseguire `ping hello-dock` (il container spostato per primo sulla rete skynet) dall'interno del container `alpine-box` funziona perché entrambi i contenitori sono sotto la stessa rete bridge definita da un utente e la risoluzione DNS automatica sta funzionando.

Affinché la risoluzione DNS automatica funzioni, bisogna assegnare nomi personalizzati ai contenitori. Usando i nomi casuali generati automaticamente non funzionerà.

### Scollegare i container da una rete

Per compiere questa azione si può usare il comando `network disconnect`.

```bash
docker network disconnect skynet hello-dock
```

### Come rimuovere una rete

le reti possono essere rimosse usando il comando `network rm`.

```bash
docker network rm skynet
```

Si può usare anche il comando prune per rimuovere ogni rete inutilizzata dal sistema. Questo comando ha anche le opzioni -f o --force e -a o --all che funzionano come per le immagini

```bash
docker network prune --force
```

## Containerizzare un'applicazione Javascript multi-container

Ora che hai imparato abbastanza sulle reti in Docker, in questa sezione imparerai a containerizzare un progetto multi-container a vero e proprio. Il progetto su cui lavorerai è una semplice `notes-api` alimentata da Express.js e PostgreSQL.

Il progetto è da scaricare da [GitHub - fhsinchy/docker-handbook-projects: Project codes used in &quot;The Docker Handbook&quot;](https://github.com/fhsinchy/docker-handbook-projects)

In questo progetto, ci sono due contenitori che dovrai connettere usando una rete. Oltre questo, imparerai anche concetti come le variabili di ambiente e i volumi. Senza ulteriori indugi, andiamo subito al dunque.

### Come eseguire il server del database

Il server del database in questo progetto è un semplice server PostgreSQL e utilizza l'immagine ufficiale di [postgres](https://hub.docker.com/_/postgres).

Secondo la documentazione ufficiale, per eseguire un container con questa immagine, devi fornire la variabile di ambiente `POSTGRES_PASSWORD`. In aggiunta, darò anche un nome per il database di default usando la variabile di ambiente `POSTGRES_DB`. PostgreSQL di default è in ascolto sulla porta `5432`, quindi devi pubblicare anche quella.

Per eseguire il server del database puoi eseguire il seguente comando:

```bash
docker container run \
    --detach \
    --name=notes-db \
    --env POSTGRES_DB=notesdb \
    --env POSTGRES_PASSWORD=secret \
    --network=notes-api-network \
    --publish 5432 \
    postgres:12
```

L'opzione `--env` per i comandi `container run` e `container create` può essere usata per fornire le variabili di ambiente a un container.

Sebbene il container sia in esecuzione, c'è un piccolo problema. Database come PostgreSQL, MongoDB e MySQL mantengono i propri dati in una cartella. PostgreSQL usa la cartella `/var/lib/postgresql/data` all'interno del container per mantenere i dati.

E se il container venisse distrutto per qualche ragione? Perderesti tutti i dati. Per risolvere questo problema può essere usato un volume con un nome.

### Lavorare con i volumi

Un volume con nome è molto simile ad un volume anonimo con la differenza ci si può far riferimento usando il suo nome.

I volumi sono anche oggetti logici docker e possono essere manipolati usando la CLI con il comando `docker volume`.

Creiamo un volume per i dati del database

```bash
docker volume create notes-db-data
```

Per elencare i volumi si usa ls

```bash
docker volume ls
```

Questo volume può essere montato su `/var/lib/postgresql/data` all'interno del container `notes-db`. Per farlo, blocca e rimuovi il container `notes-db`

```bash
docker container stop notes-db
docker container rm notes-db
```

Adesso esegui un nuovo container e assegna il volume usando l'opzione `--volume` o `-v`.

```bash
docker container run \
    --detach \
    --volume notes-db-data:/var/lib/postgresql/data \
    --name=notes-db \
    --env POSTGRES_DB=notesdb \
    --env POSTGRES_PASSWORD=secret \
    --network=notes-api-network \
    --publish 5432 \
    postgres:12
```

Adesso analizza il container `notes-db` per assicurarti che il montaggio sia  avvenuto con successo:

```bash
docker container inspect --format='{{range .Mounts}} {{ .Name }} {{end}}' notes-db
#  notes-db-data
```

Ora i dati saranno conservati al sicuro all'interno del volume `notes-db-data` e potranno essere riusati in futuro. È possibile usare anche un bind mount, ma per qualcuno è prefeibile usare un volume.

### Come accedere ai log da un container

Per vedere i log da un container si usa il comando container logs.

```bash
docker container logs notes-db
```

Esiste anche l'opzione --follow o -f per questo comando che consente di collegare la console all'output dei log e ottenere un flusso di testo continuo.

### Come creare una rete e collegare il server del database

Creare una rete bridge come visto in precedenza e collegarevi il container notes-db.

```bash
docker network create notes-api-network
docker network connect notes-api-network notes-db
```

### Come scrivere il Dockerfile

Andare nella cartella dove è presente il codice del progetto, al suo interno andare nella cartella notes-api/api e creare un nuovo Dockerfile.

```dockerfile
# stage one
FROM node:lts-alpine as builder

# install dependencies for node-gyp
RUN apk add --no-cache python make g++

WORKDIR /app

COPY ./package.json .
RUN npm install --only=prod

# stage two
FROM node:lts-alpine

EXPOSE 3000
ENV NODE_ENV=production

USER node
RUN mkdir -p /home/node/app
WORKDIR /home/node/app

COPY . .
COPY --from=builder /app/node_modules  /home/node/app/node_modules

CMD [ "node", "bin/www" ]
```

Questo è un build multi-stadio. Il primo stadio viene usato per il build e l'installazione delle dipendenze usando `node-gyp` e il secondo stadio è per eseguire l'applicazione. Analizziamo brevemente i passaggi:

- Lo stadio 1 utilizza `node:lts-alpine` come base e `builder` come nome dello stadio.
- Nella riga 5, installiamo `python`, `make` e `g++`. Lo strumento `node-gyp` richiede questi tre pacchetti per essere eseguito.
- Nella riga 7, impostiamo la cartella `/app` come `WORKDIR` .
- Nelle righe 9 e 10, copiamo il file `package.json` in `WORKDIR` e installiamo tutte le dipendenze.
- Anche lo stadio 2 fa uso di `node-lts:alpine` come base.
- Nella riga 16, impostiamo la variabile di ambiente `NODE_ENV` su `production`. È importante affinché l'API sia eseguita in modo appropriato.
- Dalla riga 18 alla 20, impostiamo utente predefinito su `node`, creiamo la cartella `/home/node/app` e la impostiamo come `WORKDIR`.
- Nella riga 22, copiamo tutti i file del progetto e nella riga 23 copiamo la cartella `node_modules` dallo stadio `builder`. Questa cartella contiene tutte le dipendenze necessarie per eseguire l'applicazione.
- Nella riga 25, definiamo il comando di default.

Eseguire quindi il build dell'immagine

```bash
docker image build --tag notes-api .
```

Prima di eseguire un container usando questa immagine, assicurarsi che il container del database sia in esecuzione e collegato a `notes-api-network`.

```bash
docker containtaer inspect notes-db
```

Il precedente comando restituisce un output in formato JSON dove si possono trovare diverse informazioni fra cui lo stato di esecuzione del container, i volumi utilizzati e le reti alle quali è connesso.

Se il risultato in inspect è corretto (`notes-db` è in esecuzione, utilizza il volume `notes-db-data` ed è collegato alla rete bridge `notes-api-network`), eseguiamo un nuovo container.

```bash
docker container run \
    --detach \
    --name=notes-api \
    --env DB_HOST=notes-db \
    --env DB_DATABASE=notesdb \
    --env DB_PASSWORD=secret \
    --publish=3000:3000 \
    --network=notes-api-network \
    notes-api
```

L'applicazione `notes-api` richiede di impostare tre variabili di ambiente:

- `DB_HOST` - È l'host del server del database. Dato che sia il database del server che l'API sono collegati alla stessa rete bridge definita da un utente, il server del database può essere indicato usando il nome del suo container, che in questo caso è `notes-db`.
- `DB_DATABASE` - Il database che userà questa API. [Eseguendo il server del database](https://www.freecodecamp.org/italian/news/il-manuale-docker/#come-eseguire-il-server-del-database), abbiamo impostato il nome predefinito su `notesdb` usando la variabile di ambiente `POSTGRES_DB`. La useremo qui.
- `DB_PASSWORD` - La password per connettere il database. Anche questa è stata impostata nella sezione [Eseguire il server del database](https://www.freecodecamp.org/italian/news/il-manuale-docker/#come-eseguire-il-server-del-database), usando la variabile di ambiente `POSTGRES_PASSWORD`.

Se il container è avviato e in esecuzione, navigando all'indirizzo http://127.0.0.1:3000/ bisognerebbe vedere l'API in azione.

L'API ha 5 rotte in totale, che puoi vedere nel file `/notes-api/api/api/routes/notes.js`.

Sebbene il container sia in esecuzione, c'è un'ultima cosa che devi fare prima di poterlo usare. Dovrai eseguire la migrazione del database necessaria per configurare le tabelle del database, e puoi farlo eseguendo il comando `npm run db:migrate` all'interno del container.

### Come eseguire i comandi in un container in esecuzione

Si utilizza il comando `exec`, quindi per eseguire la migrazione del database menzionata nella precedente sezione si usa il seguente comando.

```bash
docker container exec notes-api npm db:migrate
```

Se si volesse utilizzare un comando interattivo, bisogna specificare l'opzione `-it`. Di seguito un esempio

```bash
docker container exec -it notes-api sh
```

### Come scrivere degli script di gestione per Docker

E' utile creare degli script per automatizzare delle operazioni manuali, specialmente su progetti multi-container e con diverse reti.

Nella cartella `notes-api` del progetto ci sono quattro files utili a velocizzare alcune operazioni:

- `boot.sh` - Usato per avviare i container, se esistono già.
- `build.sh` - Crea ed esegue i container. All'occorrenza crea anche immagini, volumi e reti.
- `destroy.sh` - Rimuove tutti i container, volumi e reti associati a un progetto.
- `stop.sh` - Ferma tutti i container in esecuzione.

C'è anche un `Makefile` che contiene quattro target chiamati `start`, `stop`, `build` e `destroy`, ognuno dei quali invoca i precedenti script di shell.

Eseguire `make stop` dovrebbe fermare tutti i container in esecuzione sul tuo sistema. Eseguire `make destroy` dovrebbe fermare tutti i container e rimuovere tutto. Assicurati di lanciare gli script all'interno della cartella `notes-api`.

Può essere che sia necessario dare il permesso di esecuzione agli script

```bash
chmod +x boot.sh build.sh destroy.sh shutdown.sh
```

## Comporre progetti con Docker-Compose

[Docker Compose](https://docs.docker.com/compose/) è utile per la gestione di progetti multi-container.

Secondo la [documentazione di Docker](https://docs.docker.com/compose/):

> Compose è uno strumento per definire ed eseguire applicazioni multi-container in Docker. Con compose si utilizza un file YAML per configurare i servizi di un'applicazione. Poi, con un singolo comando, si creano e avviano tutti i servizi dalla configurazione.

Sebbene Compose funzioni in tutti gli ambienti, è più incentrato sullo sviluppo e il collaudo. Usare Compose in un ambiente di produzione non è assolutamente consigliato.

### Le basi di Docker Compose

Andare nella cartella `notes-api/api` del progetto e creare un file `Dockerfile.dev` dove inserire il seguente codice

```dockerfile
# stage one
FROM node:lts-alpine as builder

# install dependencies for node-gyp
RUN apk add --no-cache python make g++

WORKDIR /app

COPY ./package.json .
RUN npm install

# stage two
FROM node:lts-alpine

ENV NODE_ENV=development

USER node
RUN mkdir -p /home/node/app
WORKDIR /home/node/app

COPY . .
COPY --from=builder /app/node_modules /home/node/app/node_modules

CMD [ "./node_modules/.bin/nodemon", "--config", "nodemon.json", "bin/www" ]
```

Il codice è quasi identico al `Dockerfile` con cui hai lavorato nella sezione precedente, eccetto per le seguenti tre differenze:

- Nella riga 10, eseguiamo `npm install` invece di `npm run install --only=prod` perché vogliamo anche le dipendenze di sviluppo.
- Nella riga 15, impostiamo la variabile di ambiente `NODE_ENV` su `development` invece di `production`.
- Nella riga 24, usiamo uno strumento chiamato [nodemon](https://nodemon.io/) per ottenere la funzionalità di ricaricamento rapido per l'API.

Sai già che questo progetto ha due container:

- `notes-db` - Un server del database alimentato da PostgreSQL.
- `notes-api` - Una API REST alimentata da Express.js

Nel mondo di Compose, ogni container che costituisce l'applicazione è detto servizio. Il primo passo nel comporre un progetto multi-container è definire questi servizi.

Proprio come il demone Docker utilizza un `Dockerfile` per il build delle immagini, Docker Compose usa un file `docker-compose.yaml` da cui legge le definizioni dei servizi.

Vai nella cartella `notes-api` e crea un nuovo file `docker-compose.yaml`. Metti il seguente codice all'interno del file appena creato:

```yaml
version: "3.8"

services: 
    db:
        image: postgres:12
        container_name: notes-db-dev
        volumes: 
            - notes-db-dev-data:/var/lib/postgresql/data
        environment:
            POSTGRES_DB: notesdb
            POSTGRES_PASSWORD: secret
    api:
        build:
            context: ./api
            dockerfile: Dockerfile.dev
        image: notes-api:dev
        container_name: notes-api-dev
        environment: 
            DB_HOST: db ## same as the database service name
            DB_DATABASE: notesdb
            DB_PASSWORD: secret
        volumes: 
            - /home/node/app/node_modules
            - ./api:/home/node/app
        ports: 
            - 3000:3000

volumes:
    notes-db-dev-data:
        name: notes-db-dev-data
```

La prima riga rappresenta la versione di docker-compose usata.

I blocchi in un file YAML sono definiti dall'indentazione. Esaminerò ogni blocco e spiegherò cosa fa.

- Il blocco `services` contiene le definizioni per ognuno dei servizi o container nell'applicazione. `db` e `api` sono i due servizi che costituiscono questo progetto.
- Il blocco `db` definisce un nuovo servizio nell'applicazione e contiene le informazioni necessarie per avviare il container. Ogni servizio richiede un'immagine predefinita o un `Dockerfile` per l'esecuzione di un container. Per il servizio `db` stiamo usando l'immagine ufficiale di PostgreSQL.
- A differenza del servizio `db`, non esiste un'immagine di cui è già stato fatto il build per il servizio `api`. Quindi useremo il file `Dockerfile.dev`.
- Il blocco `volumes` definisce ogni volume con nome necessario per ogni servizio. Al momento elenca solo il volume `notes-db-dev-data` usato dal servizio `db`.

Ora che hai avuto una panoramica ad alto livello del file `docker-compose.yaml`, diamo un'occhiata più da vicino ai singoli servizi.

- Sezione `services/db`
  
  - La chiave `image` contiene il repository e il tag dell'immagine usata per questo contenitore. Stiamo eseguendo l'immagine `postgres:12` per eseguire il container del database.
  
  - `container_name` indica il nome del container. Di default i container sono chiamati seguendo la sintassi `<nome directory progetto>_<nome servizio>`. Puoi sovrascriverlo usando `container_name`.
  
  - L'array `volumes` contiene la mappatura dei volumi per il servizio e supporta volumi con nome, volumi anonimi e bind mount. La sintassi `<sorgente>:<destinazione>` è identica a quella che hai già visto prima.
  
  - La mappa `environment` contiene i valori delle diverse variabili di ambiente necessarie per il servizio.

- Sezione `services/api`
  
  - Il servizio `api` non ha un'immagine di cui è già stato fatto il build, ma possiede una configurazione di build. Sotto il blocco `build` definiamo il contesto e il nome del Dockerfile per il build di un'immagine. Ormai dovresti avere una buona comprensione del contesto e del Dockerfile, quindi non mi ci soffermerò oltre.
  
  - La chiave `image` contiene il nome dell'immagine di cui fare il build. Se non assegnato, all'immagine verrà dato un nome seguendo la sintassi `<nome directory progetto>_<nome servizio>`.
  
  - All'interno della mappa `environment`, la variabile `DB_HOST` dà prova di una funzionalità di Compose, ovvero che puoi fare riferimento a un altro servizio nella stessa applicazione usando il suo nome. Quindi, `db` sarà sostituito dall'indirizzo IP del container del servizio `api`. Le variabili `DB_DATABASE` e `DB_PASSWORD` devono corrispondere rispettivamente con `POSTGRES_DB` e `POSTGRES_PASSWORD` dalla definizione del servizio `db`.
  
  - Nella mappa `volumes`, puoi vedere descritti un volume anonimo e un bind mount. La sintassi è identica a quella che hai visto nelle sezionii precedenti.
  
  - La mappa `ports` definisce ogni port mapping. La sintassi, `<porta host>:<porta container>` è identica all'opzione `--publish` usata precedentemente.

- Sezione `volumes`
  
  - Ogni volume con un nome usato in qualsiasi dei servizi deve essere definito qui. Se non definisci un nome, il volume sarà chiamato seguendo `<nome directory progetto>_<chiave volume>` dove, in questo caso, la chiave è `db-data`.

### Come avviare i servizi con docker-compose
