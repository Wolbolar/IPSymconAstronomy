# IPSymconAstronomy
[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-%3E%209.0-green.svg)](https://www.symcon.de/service/dokumentation/installation/)
![Code](https://img.shields.io/badge/Code-PHP-blue.svg)
[![PHP Style](https://github.com/Wolbolar/IPSymconAstronomy/actions/workflows/php-style.yml/badge.svg)](https://github.com/Wolbolar/IPSymconAstronomy/actions/workflows/php-style.yml)

Modul für IP-Symcon ab Version 9.0 zur Anzeige astronomischer Werte, zur Erzeugung von Zeit-/Phasenbildern und zur Bereitstellung einer modernen Tile-Visualisierung.

## Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Installation](#3-installation)
4. [Visualisierung](#4-visualisierung)
5. [Konfiguration](#5-konfiguration)
6. [Funktionen](#6-funktionen)

## 1. Funktionsumfang

Das Modul legt unter den Kerninstanzen eine Astronomie-Instanz an. Die Auswahl der Variablen, Bilder und Zusatzinformationen erfolgt direkt im Instanzformular. Die Berechnung basiert auf Formeln aus _"Practical Astronomy with your Calculator or Spreadsheet"_ von Peter Duffett-Smith und Jonathan Zwart.

Unterstützt werden aktuell:

* klassische Variablen für WebFront, IPSView und eigene Skripte
* Medien für Mondbild sowie Tages- und Jahres-Dämmerung
* konfigurierbare Sonnenaufgangs- und Sonnenuntergangsvariablen mit Offset
* optionale Zusatzvariablen für Datum und Uhrzeit
* HTML-Ansicht für die Position von Sonne und Mond
* moderne Symcon-Tile-Visualisierung

Auswählbare astronomische Werte:

* Julianisches Datum
* Mond Azimut
* Mond Entfernung
* Mond Höhe
* Mond Positionswinkel der beleuchteten Fläche
* Mond Himmelsrichtung
* Mond Sichtbarkeit
* Mondaufgang
* Monduntergang
* Nächster Mondaufgang
* Nächster Monduntergang
* Mond Fortschritt
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
* Sonne Höhe
* Sonne Himmelsrichtung
* Strahlungsleistung
* Jahreszeit
* Mond im Sternzeichen
* Sonne im Sternzeichen
* Mondalter
* Sonnenhöchststand
* Tageslänge
* Nachtlänge
* Sonnen Fortschritt
* Goldene Stunde morgens Start/Ende
* Goldene Stunde abends Start/Ende
* Blaue Stunde morgens Start/Ende
* Blaue Stunde abends Start/Ende
* Mond Kulmination
* Mond untere Kulmination
* Sonne über dem Horizont
* Mond über dem Horizont
* Aktuelle Dämmerungsphase
* Rektaszension Sonne
* Deklination Sonne
* Rektaszension Mond
* Deklination Mond
* Zodiakalänge Sonne
* Zodiakalänge Mond
* Parallaktischer Winkel Sonne
* Parallaktischer Winkel Mond

## 2. Voraussetzungen

* IP-Symcon 9.0 oder neuer
* konfigurierte `Location`-Instanz mit Breiten- und Längengrad

## 3. Installation

### a. Laden des Moduls

Die WebConsole von IP-Symcon mit `http://<IP-Symcon IP>:3777/console/` öffnen.

Anschließend oben rechts auf das Symbol für den Modulstore klicken.

![Store](img/store_icon.png?raw=true "Store")

Im Suchfeld nun

```text
Astronomie
```

eingeben.

![Suche](img/module_store_search.png?raw=true "Modulsuche")

Danach das Modul auswählen und auf _Installieren_ klicken.

![Installation](img/install.png?raw=true "Installieren")

### b. Alternative Installation über die Modules-Instanz

Den Objektbaum öffnen.

![Objektbaum](img/objektbaum.png?raw=true "Objektbaum")

Die Instanz _Modules_ unterhalb der Kerninstanzen per Doppelklick öffnen und das _Plus_-Symbol wählen.

![Modules](img/Modules.png?raw=true "Modules")

![Plus](img/plus.png?raw=true "Plus")

![Modul hinzufügen](img/add_module.png?raw=true "Modul hinzufügen")

Im Feld folgende URL eintragen:

```text
https://github.com/Wolbolar/IPSymconAstronomy
```

Danach mit _OK_ bestätigen. Standardmäßig wird der Branch `master` geladen.

![Master](img/master.png?raw=true "Master")

### c. Einrichtung

In IP-Symcon unter _Kerninstanzen -> Instanz hinzufügen_ das Modul _Astronomie_ auswählen. Anschließend die Instanz öffnen, die gewünschten Werte und Visualisierungen auswählen und mit _Änderungen übernehmen_ speichern.

![Änderungen übernehmen](img/Accept_Changes.png?raw=true "Änderungen übernehmen")

## 4. Visualisierung

Das Modul stellt neben klassischen Variablen auch eine moderne Tile-Darstellung für Symcon 9 bereit.

![Kachel Überblick](img/tile-overview.png?raw=true "Kachel Überblick")

![Kachel Mond](img/tile-moon.png?raw=true "Kachel Mond")

![Kachel Position](img/tile-position.png?raw=true "Kachel Position")

![Kachel Dämmerung](img/tile-twilight.png?raw=true "Kachel Dämmerung")

![Kachel Werte](img/tile-values.png?raw=true "Kachel Werte")

![Kachel Überblick kompakt](img/tile-overview-compact.png?raw=true "Kachel Überblick kompakt")

## 5. Konfiguration

### Allgemein

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `language` | Auswahl | Sprache der Ausgaben und Tile-Beschriftungen (`German`, `English`) |
| `Updateinterval` | Integer | Aktualisierungsintervall in Sekunden |
| `UTC` | Float | Zeitzonen-Korrektur für die astronomischen Berechnungen |

### Auswählbare Werte

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `juliandate` | Float | Julianisches Datum |
| `moonazimut` | Float | Mond Azimut |
| `moondistance` | Float | Entfernung Erde -> Mond |
| `moonaltitude` | Float | Mond Höhe |
| `moonbrightlimbangle` | Float | Positionswinkel der beleuchteten Mondfläche |
| `moondirection` | Integer | Himmelsrichtung des Mondes |
| `moonvisibility` | Float | Sichtbarkeit des Mondes in Prozent |
| `moonrise` | Integer | Mondaufgang |
| `moonset` | Integer | Monduntergang |
| `nextmoonrise` | Integer | nächster zukünftiger Mondaufgang ab jetzt |
| `nextmoonset` | Integer | nächster zukünftiger Monduntergang ab jetzt |
| `moonprogress` | Integer | Fortschritt des aktuellen Mondsichtbarkeitsintervalls in Prozent |
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
| `sunaltitude` | Float | Sonne Höhe |
| `sundirection` | Integer | Himmelsrichtung der Sonne |
| `radiant_power` | Float | Strahlungsleistung |
| `season` | Integer | Jahreszeit |
| `moonstarsign` | String | Sternzeichen des Mondes |
| `sunstarsign` | String | Sternzeichen der Sonne |
| `moonage` | Float | Alter des Mondes in Tagen |
| `solarnoon` | Integer | Sonnenhöchststand |
| `daylength` | Integer | Tageslänge |
| `nightlength` | Integer | Nachtlänge |
| `sunprogress` | Integer | Fortschritt des aktuellen Tages zwischen Sonnenaufgang und Sonnenuntergang in Prozent |
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
| `sunabovehorizon` | Boolean | Sonne über dem Horizont |
| `moonabovehorizon` | Boolean | Mond über dem Horizont |
| `twilightphase` | String | Aktuelle Dämmerungsphase |
| `sunrightascension` | Float | Rektaszension Sonne |
| `sundeclination` | Float | Deklination Sonne |
| `moonrightascension` | Float | Rektaszension Mond |
| `moondeclination` | Float | Deklination Mond |
| `sunzodiaclongitude` | Float | Zodiakalänge Sonne |
| `moonzodiaclongitude` | Float | Zodiakalänge Mond |
| `sunparallacticangle` | Float | Parallaktischer Winkel Sonne |
| `moonparallacticangle` | Float | Parallaktischer Winkel Mond |

### Dämmerungsbilder

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `pictureyeartwilight` | Boolean | Jahresgrafik der Dämmerungszeiten erzeugen |
| `picturedaytwilight` | Boolean | Tagesgrafik der Dämmerungszeiten erzeugen |
| `picturetwilightlimited` | Boolean | Begrenzte Dämmerungsdarstellung verwenden |

### Mondbild

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `picturemoonvisible` | Boolean | Mondbild als Medium anlegen |
| `moonbackground` | Auswahl | Hintergrund `black background` oder `transparent background` |
| `selectionresize` | Boolean | Bilder für das Media-Element skalieren |
| `mediaimgwidth` | Integer | Zielbreite der Media-Grafik |
| `mediaimgheight` | Integer | Zielhöhe der Media-Grafik |
| `picturemoonselection` | Boolean | Eigene Mondgrafiken verwenden |
| `picturename` | String | Dateiname ohne laufende Nummer |
| `filetype` | Auswahl | Dateityp (`png`, `gif`, `jpg`) |
| `firstfullmoonpic` / `lastfullmoonpic` | Integer | erster/letzter Bildindex für Vollmond |
| `firstincreasingmoonpic` / `lastincreasingmoonpic` | Integer | erster/letzter Bildindex für zunehmenden Mond |
| `firstnewmoonpic` / `lastnewmoonpic` | Integer | erster/letzter Bildindex für Neumond |
| `firstdecreasingmoonpic` / `lastdecreasingmoonpic` | Integer | erster/letzter Bildindex für abnehmenden Mond |
| `picturemoonpath` | String | relativer Pfad zum Bilderordner im IP-Symcon-Verzeichnis |

### Position Sonne und Mond

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `sunmoonview` | Boolean | HTML-Ansicht für die Position von Sonne und Mond erzeugen |
| `zeropointx` | Integer | Nullpunkt der x-Achse |
| `zeropointy` | Integer | Nullpunkt der y-Achse |
| `framewidthtype` | Auswahl | Rahmenbreite in `px` oder `%` |
| `framewidth` | Integer | Rahmenbreite |
| `frameheighttype` | Auswahl | Rahmenhöhe in `px` oder `%` |
| `frameheight` | Integer | Rahmenhöhe |
| `canvaswidth` | Integer | Canvas-Breite in Pixel |
| `canvasheight` | Integer | Canvas-Höhe in Pixel |
| `canvasbackground` | Farbe | Hintergrundfarbe |
| `canvasbackgroundtransparency` | Integer | Hintergrund-Transparenz |

### Besondere Zeitwerte

`moonrise` und `moonset` bleiben die Tageswerte für den aktuellen Bezugstag.

`nextmoonrise` und `nextmoonset` liefern dagegen immer das nächste zukünftige Ereignis ab dem aktuellen Zeitpunkt. Dadurch kann `nextmoonset` bereits auf den Folgetag zeigen, auch wenn `moonset` noch den Tageswert für heute enthält.

`sunprogress` liefert den prozentualen Fortschritt des aktuellen Tagesintervalls zwischen Sonnenaufgang und Sonnenuntergang. Vor Sonnenaufgang ist der Wert `0`, nach Sonnenuntergang `100`.

`moonprogress` liefert den prozentualen Fortschritt des aktuellen Mondsichtbarkeitsintervalls zwischen letztem Mondaufgang und nächstem Monduntergang. Ist der Mond aktuell nicht über dem Horizont, wird `0` geliefert.

### Sonnenaufgang / Sonnenuntergang mit Offset

| Eigenschaft | Typ | Beschreibung |
| :-- | :-- | :-- |
| `sunriseselect` | Boolean | Variable für Aufgang mit Offset erzeugen |
| `risetype` | Auswahl | `sunrise`, `civilTwilightStart`, `nauticTwilightStart`, `astronomicTwilightStart`, `moonrise` |
| `sunriseoffset` | Integer | Offset in Minuten |
| `sunsetselect` | Boolean | Variable für Untergang mit Offset erzeugen |
| `settype` | Auswahl | `sunset`, `civilTwilightEnd`, `nauticTwilightEnd`, `astronomicTwilightEnd`, `moonset` |
| `sunsetoffset` | Integer | Offset in Minuten |
| `extinfoselection` | Boolean | zusätzliche Datums-/Zeitvariablen erzeugen |
| `timeformat` | Auswahl | Ausgabeformat für Uhrzeit (`H:i`, `H:i:s`, `h:i`, `h:i:s`, `g:i`, `g:i:s`, `G:i`, `G:i:s`) |

## 6. Funktionen

## 7. Versionshistorie

* `3.0`
  * Modernisierung für IP-Symcon 9 mit `IPSModuleStrict`
  * neue Symcon-Tile zur kompakten Visualisierung astronomischer Daten
  * neue zusätzliche Werte wie Tages-/Nachtlänge, Goldene Stunde, Blaue Stunde, Kulmination, Dämmerungsphase sowie weitere Sonnen-/Mondkoordinaten
  * Fehlerbehebungen bei Objektzugriffen und robustere Aktualisierung von Medien- und Tile-Inhalten
  * Doku und Screenshots auf den aktuellen Stand gebracht
* `2.1`
  * Umstellung der als deprecated markierten Funktionen `date_sunrise()` und `date_sunset()` auf `date_sun_info()`
  * Annahme korrigiert, dass es nautischen beziehungsweise astronomischen Sonnenauf- und -untergang immer gibt
* `2.0`
  * Kompatibilität mit IPS 7

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

Liefert den nächsten berechneten Zeitpunkt der jeweiligen Mondphase.
