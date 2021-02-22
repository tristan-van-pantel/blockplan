

# Blockplan - Schulverwaltung und Stundenplanerstellung für Schulen mit Blockunterricht

## Wofür ist die Anwendung gedacht?

1. #### Stundenplanerstellung

Die Anwendung soll es Bildungseinrichtungen mit Blockunterricht ermöglichen ihre Stundenpläne fehlerfrei zu erstellen, da dies in der Praxis oft noch mit Hilfe von Stecktafeln, auf Papier oder mit Hilfe von Tabellenkalkulationen geschieht.

Anders: die "Ressourcen" Klassen, Dozenten, Räume und Fächer sollen von den Administratoren auf der Zeitachse fehlerfrei und komfortabel zu "Fachterminen" zusammengefügt werden können.

 2. #### Verwaltung der beteiligten Ressourcen

Neben der Erstellung des Stundenplans können die Administratoren selbstverständlich die daran beteiligten Ressourcen verwalten:

- Klassen
- Fächer
- Schüler
- Dozenten
- Räume
- Ferien/Feiertage

3. #### Weitere administrative Funktionen

- Nutzer-/Rollen-Verwaltung
- Veröffentlichung von Nachrichten (Schulkassen als Empfänger)
- Veröffentlichung von Arbeitsangeboten (Schulklassen als Empfänger)
- Fehlzeiten verwalten

4. #### Stundenplandarstellung

Gleichzeitig soll der daraus resultierende Stundenplan den jeweiligen Schülern und Dozenten im öffentlichen Bereich dargestellt werden.

5. #### Virtuelles Klassenzimmer
Ergänzend zum Präsenzunterricht sollen Schüler und Dozenten Informationen zu jeweils aktuellen Kurs austauschen können.

5. #### Krankmeldung
Schüler können sich über die Anwendung krankmelden.
Diese Funktion ist allerdings nicht datenschutzkonform und sollte deswegen nicht in Produktivumgebungen genutzt werden.

6. #### Dashboard
Schüler könnten persönliche Informationen wie etwa absolvierte Fächer, geplante Fächer und Fehltage einsehen.  


## Installation
1. Eine Datenbank im DBMS der Wahl anlegen

2. Im Rootverzeichnis liegt die `.env`-Konfigurationsdatei.. Hier müssen die folgenden Zeilen dem DMBS und der gerade erstellen Datenbank entsprechend angepasst werden:
    `database.default.hostname`, 
    `database.default.database`,
    `database.default.password`

3. Im Produktivbetrieb muss zusätzlich noch die Zeile `CI_ENVIRONMENT = development` geändert werden in: `CI_ENVIRONMENT = production`

4. Falls NICHT lokal getestet werden soll folgende Datei öffnen:
`app/Config/`**`App.php`**
Hier muss die folgende Zeile an die Base-URL der Anwendung angepasst werden:
`public $baseURL = 'http://localhost:8080/'`

5. Migration der Datenbankstruktur mittels `php spark migrate -all`
Anschließend müssten alle Tabellen in der Datenbank vorhanden sein.

6. Falls lokal getestet wird und somit die `$baseURL` unter `4.` nicht geändert wurde, lässt sich die Anwendung mittels `php spark serve` lokal hosten.

7. Bevor jetzt über das Register-Form der erste Nutzer angelegt werden kann, muss noch ein manueller Schritt auf Datenbank-Ebene erfolgen, da der neue Nutzer sonst keiner Gruppe zugefügt werden kann:
    ```
    INSERT INTO auth_groups (name)
    VALUES ('admins'), ('lecturers'), ('students'), ('users');`
    ```

8. Im Anschluss über das Register-Form der Anwendung den neuen (ersten) Nutzer anlegen.
Dieser erste Nutzer ist automatisch der Superadmin, der auch nicht von den anderen Admins
gelöscht oder entmachtet werden kann.

9. Damit dieser erste Nutzer auch wirklich Admin ist, muss einer weiterer Schritt auf Datenbank-Ebene ausgeführt werden:
    ```
    UPDATE highschool.auth_groups_users
    SET group_id = 1
    WHERE user_id = 1;
    ``` 

10. Anschließend in der Datei `vendor/myth/auth/src/Controllers/`**`AuthController.php`**
nach folgender Zeile suchen: ` //uncomment the next three lines,after you have have created your first user and put him into group 1 ("admins")`
und entsprechend der Anweisung die darauf folgenden drei Zeilen dekommentieren. Nun können lediglich Administratoren neue Nutzer anlegen.

11. Der nun nutzlose Link zum Register-Form auf der Login-Seite muss noch entfernt werden. Dazu bitte `vendor/myth/auth/src/Views/`**`login.php`** öffnen und folgende Zeilen löschen:
    ```
    <?php if ($config->allowRegistration) : ?>
    <p><a href="<?= route_to('register') ?>"><?=lang('Auth.needAnAccount')?</a></p>
    <?php endif; ?>
    ```


## Server Requirements

PHP version 7.2 or higher is required, with the following extensions installed: 

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)


More information can be found at the [official site](http://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the 
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [the announcement](http://forum.codeigniter.com/thread-62615.html) on the forums.

The user guide corresponding to this version of the framework can be found
[here](https://codeigniter4.github.io/userguide/). 

