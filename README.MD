**Webshop NerdyGadgets**

Dit is de repository voor de webshop NerdyGadgets.

**Installatie**
1. Importeer de database
2. Voer het database-aditions.sql script uit
3. Voer het database-improvements.sql script uit
4. Installeer de ARCHIVE storage engine, zie onderstaande beschrijving

**Installatie van de ARCHIVE-engine**

Download de goede versie van de ARCHIVE-engine
Bepaal de exacte versie van MariaDB op jouw systeem:
1. Geef in een SQL-window het commando ‘show variables like ‘%version’;’.
2. Controleer de waarden voor ‘version’ (bijv. 10.4.6-MariaDB) en ‘version_compile_os’ (bijv. Win64).
3. Ga naar de site https://downloads.mariadb.com/MariaDB/, en ga naar het juiste bestand:
4. Klik de link voor jouw versie van MariaDB (bijv. mariadb-10.4.6).
5. Klik de link voor jouw Windows-versie (bijv. winx64-packages).
6. Download het ZIP-bestand in die directory (bijv. mariadb-10.4.6-winx64.zip).
7. Pak het ZIP-bestand uit.
8. Klaarzetten van de ARCHIVE-omgeving
9. Installeer de ARCHIVE-engine:
10. Maak een directory lib\plugin aan in de xampp\mysql-directory (als deze nog niet bestaat).
11. Kopieer de twee bestanden ha-archive.dll naar de xampp\mysql\lib\plugin-directory
12. Open de command line interface (CLI)[1] van MySQL met de volgende commando 's:
13. Open ‘shell’ in XAMPP
14. Geef het commando ‘mysql -u root’ (zonder quotes; als je een andere user hebt aangemaakt voor MariaDB dan moet je die gebruiken)
week5b.png
15. De prompt van de CLI verschijnt:
MariaDB [(none)]>
16. Geef het commando: INSTALL SONAME 'ha_archive';
17. Controleer of de ARCHIVE-engine beschikbaar is: SHOW ENGINES;
[1] In de CLI moet je ieder commando afsluiten met de punt-komma ‘;’ of de ‘\g’ (zonder quotes).
Log in op de MySQl shell: