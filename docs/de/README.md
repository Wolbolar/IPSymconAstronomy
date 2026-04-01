# IPSymconAstronomy
[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-%3E%209.0-green.svg)](https://www.symcon.de/service/dokumentation/installation/)
![Code](https://img.shields.io/badge/Code-PHP-blue.svg)
[![PHP Style](https://github.com/Wolbolar/IPSymconAstronomy/actions/workflows/php-style.yml/badge.svg)](https://github.com/Wolbolar/IPSymconAstronomy/actions/workflows/php-style.yml)

Modul fuer IP-Symcon ab Version 9.0 zur Anzeige astronomischer Werte, zur Erzeugung von Zeit-/Phasenbildern und zur Bereitstellung einer modernen Tile-Visualisierung.

## Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Installation](#3-installation)
4. [Visualisierung](#4-visualisierung)
5. [Konfiguration](#5-konfiguration)
6. [Funktionen](#6-funktionen)

## 1. Funktionsumfang

Das Modul legt unter den Kerninstanzen eine Astronomie-Instanz an. Die Auswahl der Variablen, Bilder und Zusatzinformationen erfolgt direkt im Instanzformular. Die Berechnung basiert auf Formeln aus _"Practical Astronomy with your Calculator or Spreadsheet"_ von Peter Duffett-Smith und Jonathan Zwart.

Unterstuetzt werden aktuell:

* klassische Variablen fuer WebFront, IPSView und eigene Skripte
* Medien fuer Mondbild sowie Tages- und Jahres-Daemmerung
* konfigurierbare Sonnenaufgangs- und Sonnenuntergangsvariablen mit Offset
* optionale Zusatzvariablen fuer Datum und Uhrzeit
* HTML-Ansicht fuer die Position von Sonne und Mond
* moderne Symcon-Tile-Visualisierung

Auswaehlbare astronomische Werte:

* Julianisches Datum
* Mond Azimut
* Mond Entfernung
* Mond Hoehe
* Mond Positionswinkel der beleuchteten Flaeche
* Mond Himmelsrichtung
* Mond Sichtbarkeit
* Mondaufgang
* Monduntergang
* Mondphase
* Neumond
* Erstes Viertel
* Vollmond
* Letztes Viertel
* Neumond aktueller Zyklus
* Erstes Viertel aktueller Zyklus
* Vollmond aktueller Zyklus
* Letztes Viertel aktueller Zyklus
* Sonne Azimut
* Sonne Entfernung
* Sonne Hoehe
* Sonne Himmelsrichtung
* Strahlungsleistung
* Jahreszeit
* Mond im Sternzeichen
* Sonne im Sternzeichen
* Mondalter
* Sonnenhoechststand
* Tageslaenge
* Nachtlaenge
* Goldene Stunde morgens Start/Ende
* Goldene Stunde abends Start/Ende
* Blaue Stunde morgens Start/Ende
* Blaue Stunde abends Start/Ende
* Mond Kulmination
* Mond untere Kulmination
* Sonne ueber dem Horizont
* Mond ueber dem Horizont
* Aktuelle Daemmerungsphase
* Rektaszension Sonne
* Deklination Sonne
* Rektaszension Mond
* Deklination Mond
* Zodiakalaenge Sonne
* Zodiakalaenge Mond
* Parallaktischer Winkel Sonne
* Parallaktischer Winkel Mond

## 2. Voraussetzungen

* IP-Symcon 9.0 oder neuer
* konfigurierte `Location`-Instanz mit Breiten- und Laengengrad

## 3. Installation

### a. Laden des Moduls

Die WebConsole von IP-Symcon mit `http://<IP-Symcon IP>:3777/console/` oeffnen.

Anschliessend oben rechts auf das Symbol fuer den Modulstore klicken.

![Store](img/store_icon.png?raw=true "Store")

Im Suchfeld nun

```text
Astronomie
```

eingeben.

![Suche](img/module_store_search.png?raw=true "Modulsuche")

Danach das Modul auswaehlen und auf _Installieren_ klicken.

![Installation](img/install.png?raw=true "Installieren")

### b. Alternative Installation ueber die Modules-Instanz

Den Objektbaum oeffnen.

![Objektbaum](img/objektbaum.png?raw=true "Objektbaum")

Die Instanz _Modules_ unterhalb der Kerninstanzen per Doppelklick oeffnen und das _Plus_-Symbol waehlen.

![Modules](img/Modules.png?raw=true "Modules")

![Plus](img/plus.png?raw=true "Plus")

![Modul hinzufuegen](img/add_module.png?raw=true "Modul hinzufuegen")

Im Feld folgende URL eintragen:

```text
https://github.com/Wolbolar/IPSymconAstronomy
```

Danach mit _OK_ bestaetigen. Standardmaessig wird der Branch `master` geladen.

![Master](img/master.png?raw=true "Master")

### c. Einrichtung

In IP-Symcon unter _Kerninstanzen -> Instanz hinzufuegen_ das Modul _Astronomie_ auswaehlen. Anschliessend die Instanz oeffnen, die gewuenschten Werte und Visualisierungen auswaehlen und mit _Aenderungen uebernehmen_ speichern.

![Aenderungen uebernehmen](img/Accept_Changes.png?raw=true "Aenderungen uebernehmen")

## 4. Visualisierung

Das Modul stellt neben klassischen Variablen auch eine moderne Tile-Darstellung fuer Symcon 9 bereit.

![Kachel Ueberblick](img/tile-overview.png?raw=true "Kachel Ueberblick")

![Kachel Mond](img/tile-moon.png?raw=true "Kachel Mond")

![Kachel Position](img/tile-position.png?raw=true "Kachel Position")

![Kachel Daemmerung](img/tile-twilight.png?raw=true "Kachel Daemmerung")

![Kachel Werte](img/tile-values.png?raw=true "Kachel Werte")

![Kachel Ueberblick kompakt](img/tile-overview-compact.png?raw=true "Kachel Ueberblick kompakt")

## 5. Konfiguration

### Allgemein

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `language` | Auswahl | Sprache der Ausgaben und Tile-Beschriftungen (`German`, `English`) |
| `Updateinterval` | Integer | Aktualisierungsintervall in Sekunden |
| `UTC` | Float | Zeitzonen-Korrektur fuer die astronomischen Berechnungen |

### Auswaehlbare Werte

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `juliandate` | Float | Julianisches Datum |
| `moonazimut` | Float | Mond Azimut |
| `moondistance` | Float | Entfernung Erde -> Mond |
| `moonaltitude` | Float | Mond Hoehe |
| `moonbrightlimbangle` | Float | Positionswinkel der beleuchteten Mondflaeche |
| `moondirection` | Integer | Himmelsrichtung des Mondes |
| `moonvisibility` | Float | Sichtbarkeit des Mondes in Prozent |
| `moonrise` | Integer | Mondaufgang |
| `moonset` | Integer | Monduntergang |
| `moonphase` | String | Mondphase |
| `newmoon` | String | Zeitpunkt Neumond |
| `firstquarter` | String | Zeitpunkt erstes Viertel |
| `fullmoon` | String | Zeitpunkt Vollmond |
| `lastquarter` | String | Zeitpunkt letztes Viertel |
| `currentnewmoon` | String | Neumond im aktuellen Zyklus |
| `currentfirstquarter` | String | Erstes Viertel im aktuellen Zyklus |
| `currentfullmoon` | String | Vollmond im aktuellen Zyklus |
| `currentlastquarter` | String | Letztes Viertel im aktuellen Zyklus |
| `sunazimut` | Float | Sonne Azimut |
| `sundistance` | Float | Entfernung Erde -> Sonne |
| `sunaltitude` | Float | Sonne Hoehe |
| `sundirection` | Integer | Himmelsrichtung der Sonne |
| `radiant_power` | Float | Strahlungsleistung |
| `season` | Integer | Jahreszeit |
| `moonstarsign` | String | Sternzeichen des Mondes |
| `sunstarsign` | String | Sternzeichen der Sonne |
| `moonage` | Float | Alter des Mondes in Tagen |
| `solarnoon` | Integer | Sonnenhoechststand |
| `daylength` | Integer | Tageslaenge |
| `nightlength` | Integer | Nachtlaenge |
| `goldenhourmorningstart` | Integer | Beginn goldene Stunde morgens |
| `goldenhourmorningend` | Integer | Ende goldene Stunde morgens |
| `goldenhoureveningstart` | Integer | Beginn goldene Stunde abends |
| `goldenhoureveningend` | Integer | Ende goldene Stunde abends |
| `bluehourmorningstart` | Integer | Beginn blaue Stunde morgens |
| `bluehourmorningend` | Integer | Ende blaue Stunde morgens |
| `bluehoureveningstart` | Integer | Beginn blaue Stunde abends |
| `bluehoureveningend` | Integer | Ende blaue Stunde abends |
| `moonculmination` | Integer | Mond Kulmination |
| `moonlowerculmination` | Integer | Mond untere Kulmination |
| `sunabovehorizon` | Boolean | Sonne ueber dem Horizont |
| `moonabovehorizon` | Boolean | Mond ueber dem Horizont |
| `twilightphase` | String | Aktuelle Daemmerungsphase |
| `sunrightascension` | Float | Rektaszension Sonne |
| `sundeclination` | Float | Deklination Sonne |
| `moonrightascension` | Float | Rektaszension Mond |
| `moondeclination` | Float | Deklination Mond |
| `sunzodiaclongitude` | Float | Zodiakalaenge Sonne |
| `moonzodiaclongitude` | Float | Zodiakalaenge Mond |
| `sunparallacticangle` | Float | Parallaktischer Winkel Sonne |
| `moonparallacticangle` | Float | Parallaktischer Winkel Mond |

### Daemmerungsbilder

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `pictureyeartwilight` | Boolean | Jahresgrafik der Daemmerungszeiten erzeugen |
| `picturedaytwilight` | Boolean | Tagesgrafik der Daemmerungszeiten erzeugen |
| `picturetwilightlimited` | Boolean | Begrenzte Daemmerungsdarstellung verwenden |

### Mondbild

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `picturemoonvisible` | Boolean | Mondbild als Medium anlegen |
| `moonbackground` | Auswahl | Hintergrund `black background` oder `transparent background` |
| `selectionresize` | Boolean | Bilder fuer das Media-Element skalieren |
| `mediaimgwidth` | Integer | Zielbreite der Media-Grafik |
| `mediaimgheight` | Integer | Zielhoehe der Media-Grafik |
| `picturemoonselection` | Boolean | Eigene Mondgrafiken verwenden |
| `picturename` | String | Dateiname ohne laufende Nummer |
| `filetype` | Auswahl | Dateityp (`png`, `gif`, `jpg`) |
| `firstfullmoonpic` / `lastfullmoonpic` | Integer | erster/letzter Bildindex fuer Vollmond |
| `firstincreasingmoonpic` / `lastincreasingmoonpic` | Integer | erster/letzter Bildindex fuer zunehmenden Mond |
| `firstnewmoonpic` / `lastnewmoonpic` | Integer | erster/letzter Bildindex fuer Neumond |
| `firstdecreasingmoonpic` / `lastdecreasingmoonpic` | Integer | erster/letzter Bildindex fuer abnehmenden Mond |
| `picturemoonpath` | String | relativer Pfad zum Bilderordner im IP-Symcon-Verzeichnis |

### Position Sonne und Mond

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `sunmoonview` | Boolean | HTML-Ansicht fuer die Position von Sonne und Mond erzeugen |
| `zeropointx` | Integer | Nullpunkt der x-Achse |
| `zeropointy` | Integer | Nullpunkt der y-Achse |
| `framewidthtype` | Auswahl | Rahmenbreite in `px` oder `%` |
| `framewidth` | Integer | Rahmenbreite |
| `frameheighttype` | Auswahl | Rahmenhoehe in `px` oder `%` |
| `frameheight` | Integer | Rahmenhoehe |
| `canvaswidth` | Integer | Canvas-Breite in Pixel |
| `canvasheight` | Integer | Canvas-Hoehe in Pixel |
| `canvasbackground` | Farbe | Hintergrundfarbe |
| `canvasbackgroundtransparency` | Integer | Hintergrund-Transparenz |

### Sonnenaufgang / Sonnenuntergang mit Offset

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `sunriseselect` | Boolean | Variable fuer Aufgang mit Offset erzeugen |
| `risetype` | Auswahl | `sunrise`, `civilTwilightStart`, `nauticTwilightStart`, `astronomicTwilightStart`, `moonrise` |
| `sunriseoffset` | Integer | Offset in Minuten |
| `sunsetselect` | Boolean | Variable fuer Untergang mit Offset erzeugen |
| `settype` | Auswahl | `sunset`, `civilTwilightEnd`, `nauticTwilightEnd`, `astronomicTwilightEnd`, `moonset` |
| `sunsetoffset` | Integer | Offset in Minuten |
| `extinfoselection` | Boolean | zusaetzliche Datums-/Zeitvariablen erzeugen |
| `timeformat` | Auswahl | Ausgabeformat fuer Uhrzeit (`H:i`, `H:i:s`, `h:i`, `h:i:s`, `g:i`, `g:i:s`, `G:i`, `G:i:s`) |

## 6. Funktionen

## 7. Versionshistorie

* `3.0`
  * Modernisierung fuer IP-Symcon 9 mit `IPSModuleStrict`
  * neue Symcon-Tile zur kompakten Visualisierung astronomischer Daten
  * neue zusaetzliche Werte wie Tages-/Nachtlaenge, Goldene Stunde, Blaue Stunde, Kulmination, Daemmerungsphase sowie weitere Sonnen-/Mondkoordinaten
  * Fehlerbehebungen bei Objektzugriffen und robustere Aktualisierung von Medien- und Tile-Inhalten
  * Doku und Screenshots auf den aktuellen Stand gebracht
* `2.1`
  * Umstellung der als deprecated markierten Funktionen `date_sunrise()` und `date_sunset()` auf `date_sun_info()`
  * Annahme korrigiert, dass es nautischen beziehungsweise astronomischen Sonnenauf- und -untergang immer gibt
* `2.0`
  * Kompatibilitaet mit IPS 7

```php
Astronomy_SetAstronomyValues(int $InstanceID)
```

Aktualisiert alle im Modul aktivierten Werte.

```php
Astronomy_MoonphasePercent(int $InstanceID)
```

Liefert den Fortschritt der Mondphase in Prozent.

```php
Astronomy_MoonphaseText(int $InstanceID)
```

Liefert die Mondphase als Text.

```php
Astronomy_Moon_FirstQuarter(int $InstanceID)
Astronomy_Moon_Newmoon(int $InstanceID)
Astronomy_Moon_Fullmoon(int $InstanceID)
Astronomy_Moon_LastQuarter(int $InstanceID)
```

Liefert den naechsten berechneten Zeitpunkt der jeweiligen Mondphase.
