# IPSymconAstronomy
[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-%3E%205.1-green.svg)](https://www.symcon.de/service/dokumentation/installation/)

Modul für IP-Symcon ab Version 5.1 zeigt Astonomische Daten an

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Installation](#3-installation)  
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguration)  
6. [Anhang](#6-anhang)  

## 1. Funktionsumfang

Mit dem Modul wird unter Kerninstanzen eine Instanz mit Astonomischen Werten angelegt. Welche Werte angezeigt werden sollen lässt sich im Modul auswählen.
Berechnung der Werte erfolgt über Formeln aus _"Practical Astronomy with your Calculator or Spreadsheet"_ von Peter Duffet-Smith und Jonathan Zwart. 

### Astronomische Werte:  

* Julianisches Datum
* Mond Azimut
* Mond Entfernung
* Mond Höhe
* Mond Positionswinkel der beleuchteten Fläche
* Mond Himmelsrichtung 
* Mond Richtung (Grad)
* Mond Sichtbarkeit
* Mond Aufgang
* Mond Untergang
* Mond Phase
* Zeitpunkt Aktueller Zyklus Neumond
* Zeitpunkt Aktueller Zyklus Erstes Viertel
* Zeitpunkt Aktueller Zyklus Vollmond
* Zeitpunkt Aktueller Zyklus Letztes Viertel
* Zeitpunkt Neumond
* Zeitpunkt Erstes Viertel
* Zeitpunkt Vollmond
* Zeitpunkt Letztes Viertel
* Sonne Azimut
* Sonne Entfernung
* Sonne Höhe
* Sonne Richtung
* Sonne Richtung (Grad)
* Jahreszeit
* Sonnenaufgang mit einstellbaren Offset
* Sonnenuntergang mit einstellbaren Offset
* Bildansicht Sonne und Mond
* konfigurierbare Bildansicht Mondphase (eigene Grafiken möglich)
* Grafik Dämmerungszeiten Tag
* Grafik Dämmerungszeiten Jahr

## 2. Voraussetzungen

 - IP-Symcon 5.1

## 3. Installation

### a. Laden des Moduls

Die Webconsole von IP-Symcon mit _http://<IP-Symcon IP>:3777/console/_ öffnen. 


Anschließend oben rechts auf das Symbol für den Modulstore klicken

![Store](img/store_icon.png?raw=true "open store")

Im Suchfeld nun

```
Astronomie
```  

eingeben

![Store](img/module_store_search.png?raw=true "module search")

und schließend das Modul auswählen und auf _Installieren_

![Store](img/install.png?raw=true "install")

drücken.


#### Alternatives Installieren über Modules Instanz

Den Objektbaum _Öffnen_.

![Objektbaum](img/objektbaum.png?raw=true "Objektbaum")	

Die Instanz _'Modules'_ unterhalb von Kerninstanzen im Objektbaum von IP-Symcon (>=Ver. 5.x) mit einem Doppelklick öffnen und das  _Plus_ Zeichen drücken.

![Modules](img/Modules.png?raw=true "Modules")	

![Plus](img/plus.png?raw=true "Plus")	

![ModulURL](img/add_module.png?raw=true "Add Module")
 
Im Feld die folgende URL eintragen und mit _OK_ bestätigen:

```
https://github.com/Wolbolar/IPSymconAstronomy 
```  
	
Anschließend erscheint ein Eintrag für das Modul in der Liste der Instanz _Modules_    

Es wird im Standard der Zweig (Branch) _master_ geladen, dieser enthält aktuelle Änderungen und Anpassungen.
Nur der Zweig _master_ wird aktuell gehalten.

![Master](img/master.png?raw=true "master") 

Sollte eine ältere Version von IP-Symcon die kleiner ist als Version 5.1 (min 4.1) eingesetzt werden, ist auf das Zahnrad rechts in der Liste zu klicken.
Es öffnet sich ein weiteres Fenster,

![SelectBranch](img/select_branch.png?raw=true "select branch") 

hier kann man auf einen anderen Zweig wechseln, für ältere Versionen kleiner als 5.1 ist hier
_Old_ auszuwählen. 
	
### b. Überprüfen von Location

Zur Berechnung der Astronomischen Daten wird der Breitengrad und Längengrad benötigt. Dieser wird aus der Location Instanz unter Kerninstanzen entnommen.
Daher ist zunächst zu prüfen ob in der Instanz Location unter Kerninstanzen ein Breiten und Längengrad hinterlegt wurde.
	
### c. Einrichtung in IP-Symcon

In IP-Symcon unter Kern Instanzen Instanz hinzufügen auswählen und Astronomie auswählen.
Die Instanz zur Konfiguration öffnen und die gewünchten Variablen auswählen.
Anschließend mit _Änderungen übernehmen_ bestätigen.

![ModulURL](img/Accept_Changes.png?raw=true "Add Module")


## 4. Funktionsreferenz

### Astronomische Daten:
* Julianisches Datum
* Mond Azimut
* Mond Entfernung
* Mond Höhe
* Mond Positionswinkel der beleuchteten Fläche
* Mond Himmelsrichtung 
* Mond Richtung (Grad)
* Mond Sichtbarkeit
* Mond Aufgang
* Mond Untergang
* Mond Phase
* Zeitpunkt Neumond
* Zeitpunkt Erstes Viertel
* Zeitpunkt Vollmond
* Zeitpunkt Letztes Viertel
* Sonne Azimut
* Sonne Entfernung
* Sonne Höhe
* Sonne Richtung
* Sonne Richtung (Grad)
* Jahreszeit
	


## 5. Konfiguration:

### Astronomie:

| Eigenschaft         | Typ     | Wert            | Beschreibung                                 |
| :-----------------: | :-----: | :-------------: | :------------------------------------------: |
| juliandate          | float   | JD              | Julianisches Datum                           |
| moonazimut          | float   | Mond Azimut     | Mond Azimut                                  |
| moondistance        | float   | Mond Entfernung | Entfernung des Monds zur Erde                |
| moonaltitude        | float   | Mond Höhe       | Höhe des Monds in Grad                       |
| moonbrightlimbangle | float   | Positionswinkel | Mond Positionswinkel der beleuchteten Fläche |
| moondirection       | integer | Himmelsrichtung | Himmelsrichtung des Monds                    |
| moonvisibility      | float   | Sichtbarkeit    | Sichtbarkeit des Monds                       |
| moonrise            | integer | Mond Aufgang    | Zeitpunkt Mond Aufgang                       |
| moonset             | integer | Mond Untergang  | Zeitpunkt Mond Untergang                     |
| moonphase           | string  | Mond Phase      | Mond Phase                                   |
| newmoon             | string  | Neumond         | Zeitpunkt Neumond                            |
| firstquarter        | string  | Erstes Viertel  | Zeitpunkt Erstes Viertel                     |
| fullmoon            | string  | Vollmond        | Zeitpunkt Vollmond                           |
| lastquarter         | string  | letztes Viertel | Zeitpunkt Letztes Viertel                    |
| currentnewmoon      | string  | Neumond         | Zeitpunkt Neumond Aktueller Zyklus                           |
| currentfirstquarter | string  | Erstes Viertel  | Zeitpunkt Erstes Viertel Aktueller Zyklus                    |
| currentfullmoon     | string  | Vollmond        | Zeitpunkt Vollmond Aktueller Zyklus                           |
| currentlastquarter  | string  | letztes Viertel | Zeitpunkt Letztes Viertel Aktueller Zyklus
| sunazimut           | float   | Sonne Azimut    | Sonne Azimut                                 |
| sundistance         | float   | Sonne Entfernung| Sonne Entfernung                             |
| sunaltitude         | float   | Sonne Höhe      | Sonne Höhe                                   |
| sundirection        | integer | Himmelsrichtung | Himmelsrichtung                              |
| season              | integer | Jahreszeit      | Jahreszeit                                   |
| picturemoon         | gif     | Bild Mond       | Bild der aktuellen Ansicht vom Mond          |
| sunmoonview         | string  | Position        | Position Sonne und Mond                      |
| sunset              | integer | Sonnenuntergang | Sonnen-, Monduntergang + entstellbarer Offset|
| sunrise             | integer | Sonnenaufgang   | Sonnen-, Mondaufgang + entstellbarer Offset  |


## 6. Anhang

###  a. Funktionen:

#### Astronomie:

```php
Astronomy_SetAstronomyValues(int $InstanceID)
```
Aktualisiert alle im Modul ausgewählten Werte

```php
Astronomy_MoonphasePercent(int $InstanceID)
```
Gibt den Fortschritt der Mondphase in % aus

```php
Astronomy_MoonphaseText(int $InstanceID)
```
Liefert die Mondphase als Ausgabe Text - % z.B. zunehmender Mond - 84%

```php
Astronomy_Moon_FirstQuarter(int $InstanceID)
```
Zeitpunkt Erstes Viertel, sollte der Zeitpunkt in der Vergangenheit liegen wird das wird der nächste Zeitpunkt der nächsten Mondphase ausgegeben

```php
Astronomy_Moon_Newmoon(int $InstanceID)
```
Zeitpunkt Neumond, sollte der Zeitpunkt in der Vergangenheit liegen wird das wird der nächste Zeitpunkt der nächsten Mondphase ausgegeben


```php
Astronomy_Moon_Fullmoon(int $InstanceID)
```
Zeitpunkt Vollmond, sollte der Zeitpunkt in der Vergangenheit liegen wird das wird der nächste Zeitpunkt der nächsten Mondphase ausgegeben


```php
Astronomy_Moon_LastQuarter(int $InstanceID)
```
Zeitpunkt Letztes Viertel, Zeitpunkt Erstes Viertel, sollte der Zeitpunkt in der Vergangenheit liegen wird das wird der nächste Zeitpunkt der nächsten Mondphase ausgegeben


```php
Astronomy_Moon_CurrentFirstQuarter(int $InstanceID)
```
Zeitpunkt Erstes Viertel des aktuellen Zyklus

```php
Astronomy_Moon_CurrentNewmoon(int $InstanceID)
```
Zeitpunkt Neumond des aktuellen Zyklus

```php
Astronomy_Moon_CurrentFullmoon(int $InstanceID)
```
Zeitpunkt Vollmond des aktuellen Zyklus

```php
Astronomy_Moon_CurrentLastQuarter(int $InstanceID)
```
Zeitpunkt Letztes Viertel des aktuellen Zyklus

```php
Astronomy_Moon_FirstQuarterDate(int $InstanceID, string $date)
```
Zeitpunkt Erstes Viertel zum übergebenen Datum

_$date_ Datum

```php
Astronomy_Moon_NewmoonDate(int $InstanceID, string $date)
```
Zeitpunkt Neumond zum übergebenen Datum

_$date_ Datum

```php
Astronomy_Moon_FullmoonDate(int $InstanceID, string $date)
```
Zeitpunkt Vollmond zum übergebenen Datum

_$date_ Datum

```php
Astronomy_Moon_LastQuarterDate(int $InstanceID, string $date)
```
Zeitpunkt Letztes Viertel zum übergebenen Datum

_$date_ Datum


###  b. GUIDs und Datenaustausch:

#### Astronomy:

GUID: `{AE370BEA-2B51-4C64-A147-0CCE3494FE08}` 
