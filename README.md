# SIT Booking

System for å booke tid i SIT-s hybel.
Bookinger kan legges inn av alle som ligger under SIT i Studentersamfundets medlemsregister.
Bookinger godkjennes av medlemmer som er tildelt admin-tilgang av [ITK](https://itk.samfundet.no/).

Systemet er bygget med [PHP](https://www.php.net/), [Twig](https://twig.symfony.com/) og [PostgreSQL](https://www.postgresql.org/).

Issues og Pull Requests er velkomne.

## Lokal utvikling

Prosjektet er satt opp for å kunne kjøre lokalt ved hjelp av [Vagrant](https://www.vagrantup.com/).
Du må gjøre følgende for å komme i gang:

1. Installer [VirtualBox](https://www.virtualbox.org/wiki/Downloads).
2. Installer [Vagrant](https://www.vagrantup.com/downloads).
3. Klon (eller om du vil bidra, fork) prosjektet til din maskin og gå inn i mappen
    ```shell
    $ git clone https://github.com/Studentersamfundets-Interne-Teater/sit-booking.git
    $ cd sit-booking
    ```
4. Start den lokale maskinen
   ```shell
   $ vagrant up
   ```
   Dette laster ned en virtuell Debian-boks som Vagrant kjører ved hjelp av VirtualBox. Deretter kjøres installeringsskriptene [`vmsetup.sh`](./vmsetup.sh) og [`devsetup.sh`](./devsetup.sh). Førstnevnte installerer Apache, PHP og PostgreSQL på maskinen, sistnevnte installerer PHP-bibliotekene som kreves og konfigurerer databasen for Bookingsiden.
5. Gå til http://localhost:8080 for å se din lokale versjon av siden.

Første gang du går inn på siden blir du bedt om å opprette en bruker. Dette er en del av brukerflyten, og brukeren du oppretter vil kun lagres i din lokale versjon av databasen.

### Fjern den virtuelle maskinen

Om du vil fjerne den virtuelle maskinen kan du bruke kommandoen
```shell
$ vagrant destroy
```
Dette sletter alle filer maskinen har opprettet, inkludert databasen.

Om du vil lage en ny er det bare å kjøre
```shell
$ vagrant up
```
for å starte forfra. For mer detaljer om Vagrant, se [dokumentasjonen](https://www.vagrantup.com/docs).