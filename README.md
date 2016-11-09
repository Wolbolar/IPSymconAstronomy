# IPSymconAstronomy

Modul für IP-Symcon ab Version 4.1 zeigt Astonomische Daten an.

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Installation](#3-installation)  
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguartion)  
6. [Anhang](#6-anhang)  

## 1. Funktionsumfang

Mit dem Modul wird unter Kerninstanzen eine Instanz mit Astonomischen Werten angelegt. Welche Werte angezeigt werden sollen lässt sich im Modul auswählen.
Berechung der Werte erfolgt über Formeln aus _"Practical Astronomy with your Calculator or Spreadsheet"_ von Peter Duffet-Smith und Jonathan Zwart. 

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
 

## 2. Voraussetzungen

 - IPS 4.1

## 3. Installation

### a. Laden des Moduls

In IP-Symcon (Ver. 4.1) unter Kerninstanzen über _*Modules*_ -> Hinzufügen das Modul ergänzen mit der URL:
	
    `https://github.com/Wolbolar/IPSymconAstronomy`  

### b. Überprüfen von Location

Zur Berechnung der Astronomischen Daten wird der Breitengrad und Längengrad benötigt. Dieser wird aus der Location Instanz unter Kerninstanzen entnommen.
Daher ist zunächst zu prüfen ob in der Instanz Location unter Kerninstanzen ein Breiten und Längengrad hinterlegt wurde.
	
### c. Einrichtung in IPS

In IP-Symcon unter Kern Instanzen Instanz hinzufügen auswählen und Astronomie auswählen.


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

| Eigenschaft         | Typ     | Wert            | Beschreibung                                |
| :-----------------: | :-----: | :-------------: | :-----------------------------------------: |
| juliandate          | float   | JD              | Julianisches Datum                          |
| moonazimut          | float   | Mond Azimut     | Mond Azimut                                 |
| moondistance        | float   | Mond Entfernung | Entfernung des Monds zur Erde               |
| moonaltitude        | float   | Mond Höhe       | Höhe des Monds in Grad                      |
| moonbrightlimbangle | float   | Positionswinkel | Mond Positionswinkel der beleuchteten Fläche|
| moondirection       | string  | Himmelsrichtung | Himmelsrichtung des Monds                   |
| moonvisibility      | float   | Sichtbarkeit    | Sichtbarkeit des Monds                      |
| moonrise            | integer | Mond Aufgang    | Zeitpunkt Mond Aufgang                      |
| moonset             | integer | Mond Untergang  | Zeitpunkt Mond Untergang                    |
| moonphase           | string  | Mond Phase      | Mond Phase                                  |
| newmoon             | string  | Neumond         | Zeitpunkt Neumond                           |
| firstquarter        | string  | Erstes Viertel  | Zeitpunkt Erstes Viertel                    |
| fullmoon            | string  | Vollmond        | Zeitpunkt Vollmond                          |
| lastquarter         | string  | letztes Viertel | Zeitpunkt Letztes Viertel                   |
| sunazimut           | float   | Sonne Azimut    | Sonne Azimut                                |
| sundistance         | float   | Sonne Entfernung| Sonne Entfernung                            |
| sunaltitude         | float   | Sonne Höhe      | Sonne Höhe                                  |
| sundirection        | string  | Himmelsrichtung | Himmelsrichtung                             |
| season              | string  | Jahreszeit      | Jahreszeit                                  |
| picturemoon         | gif     | Bild Mond       | Bild der aktuellen Ansicht vom Mond         |
| sunmoonview         | string  | Position        | Position Sonne und Mond                     |


## 6. Anhang

###  a. Funktionen:

#### Astronomie:

```php
Astronomy_SetAstronomyValues(integer $InstanceID)
```
Aktualisiert alle im Modul ausgewählten Werte

```php
Astronomy_MoonphasePercent(integer $InstanceID)
```
Gibt den Fortschritt der Mondphase in % aus

```php
Astronomy_MoonphaseText(integer $InstanceID)
```
Liefert die Mondphase als Ausgabe Text - % z.B. zunehmender Mond - 84%

```php
Astronomy_Moon_FirstQuarter(integer $InstanceID)
```
Zeitpunkt Erstes Viertel

```php
Astronomy_Moon_Newmoon(integer $InstanceID)
```
Zeitpunkt Neumond

```php
Astronomy_Moon_Fullmoon(integer $InstanceID)
```
Zeitpunkt Vollmond

###  b. GUIDs und Datenaustausch:

#### Astronomy:

GUID: `{AE370BEA-2B51-4C64-A147-0CCE3494FE08}` 




