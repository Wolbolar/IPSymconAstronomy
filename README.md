# IPSymconAstronomy

Modul für IP-Symcon ab Version 4.1 zeigt Astonomische Daten an und erstellt Astronomie Timer

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
* Sonnenaufgang mit einstellbaren Offset
* Sonnenuntergang mit einstellbaren Offset

### Astronomischer Timer: 
Es können für ein Skript oder eine Variable ein Timer angelegt werden der sich nach einem Astronomischen Ereignis richtet.
Dabei kann zwischen unterschiedlichen Astronomischen Ereignissen ausgewählt werden. Der Timer wird automatisch jeden Tag auf die passende Uhrzeit eingestellt.
Dadaurch lassen sich z.B. Rollläden steuern oder Lampen die mit der Dämmerung geschaltet werden sollen. Es lässt sich eine Grenzzeit einstellen sowie der Offset zum Ereignis.

* Sonnenaufgang
* Sonnenuntergang
* zivile Morgendämmerung
* zivile Abenddämmerung
* nautische Morgendämmerung
* nautische Abenddämmerung
* astronomische Morgendämmerung
* astronomische Abenddämmerung
* Mond Aufgang
* Mond Untergang

Es kann eine Auswahl der Wochentage vorgenommen werden so das der Timer nur an einem bestimmten Wochentag aktiv ist.

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
| sunazimut           | float   | Sonne Azimut    | Sonne Azimut                                 |
| sundistance         | float   | Sonne Entfernung| Sonne Entfernung                             |
| sunaltitude         | float   | Sonne Höhe      | Sonne Höhe                                   |
| sundirection        | integer | Himmelsrichtung | Himmelsrichtung                              |
| season              | integer | Jahreszeit      | Jahreszeit                                   |
| picturemoon         | gif     | Bild Mond       | Bild der aktuellen Ansicht vom Mond          |
| sunmoonview         | string  | Position        | Position Sonne und Mond                      |
| sunset              | integer | Sonnenuntergang | Sonnen-, Monduntergang + entstellbarer Offset|
| sunrise             | integer | Sonnenaufgang   | Sonnen-, Mondaufgang + entstellbarer Offset  |

### Astronomie Timer:

| Eigenschaft         | Typ     | Wert            | Beschreibung                                 |
| :-----------------: | :-----: | :-------------: | :------------------------------------------: |
| timertype           | integer | Typ Timer       | Auswahl Timertyp                             |
| offset              | integer | Offset          | Offset Wert in Minuten                       |
| cutoffselect        | boolean | false/true      | Auswahl Cutofftime                           |
| cutofftime          | string  | Cutofftime      | Cutofftime höhere Priorität als Timerzeit    |
| varwebfrontselect   | boolean | false/true      | Zeigt Uhrzeit des Timers im Webfront         |
| triggerscript       | integer | ObjektID Skript | ObjektID des zu triggernden Skripts          |
| varselect           | boolean | false/true      | Auswahl Variable                             |
| triggervariable     | integer | ObjektID Var    | ObjektID der zu triggernden Variable         |
| varvalue            | string  | Variablenwert   | Angabe des Variablenwerts                    |
| monday              | boolean | Wochentag       | Auswahl Wochentag                            |
| tuesday             | boolean | Wochentag       | Auswahl Wochentag                            |
| wednesday           | boolean | Wochentag       | Auswahl Wochentag                            |
| thursday            | boolean | Wochentag       | Auswahl Wochentag                            |
| friday              | boolean | Wochentag       | Auswahl Wochentag                            |
| saturday            | boolean | Wochentag       | Auswahl Wochentag                            |
| sunday              | boolean | Wochentag       | Auswahl Wochentag                            |

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
Zeitpunkt Erstes Viertel

```php
Astronomy_Moon_Newmoon(int $InstanceID)
```
Zeitpunkt Neumond

```php
Astronomy_Moon_Fullmoon(int $InstanceID)
```
Zeitpunkt Vollmond

#### Astronomie Timer:

```php
AstronomyTimer_Set(int $InstanceID)
```
Setzt den Astronomie Timer mit den in der Instanz eingestellten Werten

__*Um mit Funktionen einen Astronomie Timer anlegen zu können muss mindestens ein Astronomie Timer zuvor in IP-Symconangelegt worden sein. Mit der Funktion wird dann auf diese eine Instanz verwiesen um weitere Timer anzulegen.*__

```php
AstronomyTimer_SetSunrise(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit Sonnenaufgang + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetSunset(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit Sonnenuntergang + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetCivilTwilightStart(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit ziviler Morgendämmerung + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetCivilTwilightEnd(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit ziviler Abenddämmerung + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetNauticTwilightStart(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit nautischer Morgendämmerung + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetNauticTwilightEnd(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit nautischer Abenddämmerung + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetAstronomicTwilightStart(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit astronomischer Morgendämmerung + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetAstronomicTwilightEnd(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit astronomischer Abenddämmerung + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetMoonrise(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit Mondaufgang + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__

```php
AstronomyTimer_SetMoonset(int $InstanceID, int $offset, string $settype, int $objectid, string $varvalue)
```
Setzt einen Astronomie Timer mit Monduntergang + Offset

_$offset_   Offsetwert in Minuten 

_$settype_  Typ des Timers Skriptausführung durch Timer oder Variablenänderung durch Timer. _Mögliche Werte:_ __*Script*__ | __*Variable*__

_$objectid_ ObjektID der Variable oder des Skripts für Timerausführung

_$varvalue_ Wert den der Timer bei einer Variable einstellen soll wenn das Event stattfindet. Auf den passenen Wert zum Variablentyp achten. Wenn ein Skript ausgeführt werden soll ist der Wert hier __*NULL*__


###  b. GUIDs und Datenaustausch:

#### Astronomy:

GUID: `{AE370BEA-2B51-4C64-A147-0CCE3494FE08}` 

#### AstronomyTimer:

GUID: `{5C02271C-D599-4C71-98D3-86C89F94EB96}` 


