# IPSymconFireTV

Modul für IP-Symcon ab Version 4.1 zeigt Astonomische Daten an.
Beta Test

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

### Astronomische Werte:  

 - Up, Down, Left, Right, Enter, Back, Home, Menu, Media Play/Pause, Media Previous, Media Next
 

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

| Eigenschaft | Typ     | Standardwert | Funktion                                  |
| :---------: | :-----: | :----------: | :---------------------------------------: |
| IPFireTV    | string  |              | IP Adresse FireTV                         |


## 6. Anhang

###  a. Funktionen:

#### Astronomie:

```php
Astronomy_Up(integer $InstanceID)
```

Up


###  b. GUIDs und Datenaustausch:

#### Astronomy:

GUID: `{AE370BEA-2B51-4C64-A147-0CCE3494FE08}` 




