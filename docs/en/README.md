# IPSymconAstronomy
[![Version](https://img.shields.io/badge/Symcon-PHPModule-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-%3E%205.1-green.svg)](https://www.symcon.de/en/service/documentation/installation/)
![Code](https://img.shields.io/badge/Code-PHP-blue.svg)
[![StyleCI](https://github.styleci.io/repos/73331095/shield?branch=master)](https://github.styleci.io/repos/73331095)

Module for IP-Symcon version 5.1 and higher displays Astonomic data

## Documentation

**Table of Contents**

1. [Features](#1-features)
2. [Requirements](#2-requirements)
3. [Installation](#3-installation)
4. [Function reference](#4-functionreference)
5. [Configuration](#5-configuration)
6. [Annex](#6-annex)

## 1. Features

The module creates an instance with astonomic values under core instances. Which values should be displayed can be selected in the module.
The values are calculated using formulas from _"Practical Astronomy with your Calculator or Spreadsheet"_ by Peter Duffet-Smith and Jonathan Zwart.


### Astronomical values:  

* Julian date
* Moon azimuth
* Moon distance
* Mooon altitude
* Moon bright limb angle
* Moon direction 
* Moon direction (degrees)
* Moon visibility
* Moonrise
* Moonset
* Moon phase
* Time current cycle new moon
* Time current cycle first quarter
* Time current cycle full moon
* Time current cycle last quarter
* Date new moon
* Date first quarter
* Time full moon
* Time last quarter
* Sun azimuth
* Sun removal
* Sun altitude
* Sun direction
* Sun direction (degrees)
* Season
* Sunrise with adjustable offset
* Sunset with adjustable offset
* Image view sun and moon
* configurable image view moon phase (own graphics possible)
* Graphic twilight day
* Graphic twilight years
* Season

## 2. Requirements

 - IP-Symcon 5.1

## 3. Installation

### a. Loading the module

Open the IP Console's web console with _http://<IP-Symcon IP>:3777/console/_.

Then click on the module store icon in the upper right corner.

![Store](img/store_icon.png?raw=true "open store")

In the search field type

```
Astronomy
```  


![Store](img/module_store_search_en.png?raw=true "module search")

Then select the module and click _Install_

![Store](img/install_en.png?raw=true "install")


#### Install alternative via Modules instance

_Open_ the object tree.

![Objektbaum](img/object_tree.png?raw=true "object tree")	

Open the instance _'Modules'_ below core instances in the object tree of IP-Symcon (>= Ver 5.x) with a double-click and press the _Plus_ button.

![Modules](img/modules.png?raw=true "modules")	

![Plus](img/plus.png?raw=true "Plus")	

![ModulURL](img/add_module.png?raw=true "Add Module")
 
Enter the following URL in the field and confirm with _OK_:


```	
https://github.com/Wolbolar/IPSymconAstronomy  
```
    
and confirm with _OK_.    
    
Then an entry for the module appears in the list of the instance _Modules_

By default, the branch _master_ is loaded, which contains current changes and adjustments.
Only the _master_ branch is kept current.

![Master](img/master.png?raw=true "master") 

If an older version of IP-Symcon smaller than version 5.1 (min 4.1) is used, click on the gear on the right side of the list.
It opens another window,

![SelectBranch](img/select_branch_en.png?raw=true "select branch") 

here you can switch to another branch, for older versions smaller than 5.1 select _Old_ .

### b. Check Location

Zur Berechnung der Astronomischen Daten wird der Breitengrad und Längengrad benötigt. Dieser wird aus der Location Instanz unter Kerninstanzen entnommen.
Daher ist zunächst zu prüfen ob in der Instanz Location unter Kerninstanzen ein Breiten und Längengrad hinterlegt wurde.
	
### c. Setup in IP-Symcon

In IP Symcon, under core instances, select Add Instance and select _Astronomy_.
Open the instance for configuration and select the desired variables.
Then confirm with _Apply Changes_.

![ModulURL](img/apply_changes_en.png?raw=true "Add Module")


## 4. Function reference

### Astronomical values:  

* Julian date
* Moon azimuth
* Moon distance
* Mooon altitude
* Moon bright limb angle
* Moon direction 
* Moon direction (degrees)
* Moon visibility
* Moonrise
* Moonset
* Moon phase
* Time current cycle new moon
* Time current cycle first quarter
* Time current cycle full moon
* Time current cycle last quarter
* Date new moon
* Date first quarter
* Time full moon
* Time last quarter
* Sun azimuth
* Sun distance
* Sun altitude
* Sun direction
* Sun direction (degrees)
* Season
* Sunrise with adjustable offset
* Sunset with adjustable offset
* Image view sun and moon
* configurable image view moon phase (own graphics possible)
* Graphic twilight day
* Graphic twilight years


## 5. Configuration:

### Astronomie:

| Property            | Type    | Value           | Description                                  |
| :-----------------: | :-----: | :-------------: | :------------------------------------------: |
| juliandate          | float   | JD              | Julian date                                  |
| moonazimut          | float   | Moon azimuth    | Moon azimuth                                 |
| moondistance        | float   | Moon distance   | Distance from the moon to the earth          |
| moonaltitude        | float   | Mooon altitude  | Mooon altitude in degree                     |
| moonbrightlimbangle | float   | angle           | Moon bright limb angle                       |
| moondirection       | integer | Moon direction  | Moon direction                               |
| moonvisibility      | float   | Moon visibility | Moon visibility                              |
| moonrise            | integer | Moonrise        | Time Moonrise                                |
| moonset             | integer | Moonset         | Time Moonset                                 |
| moonphase           | string  | Moon phase      | Moon phase                                   |
| newmoon             | string  | new moon        | Time new moon                                |
| firstquarter        | string  | Erstes Viertel  | Time cycle first quarter                     |
| fullmoon            | string  | Vollmond        | Time cycle full moon                         |
| lastquarter         | string  | letztes Viertel | Time cycle last quarter                      |
| currentnewmoon      | string  | Neumond         | Time current cycle new moon                  |
| currentfirstquarter | string  | Erstes Viertel  | Time current cycle first quarter             |
| currentfullmoon     | string  | Vollmond        | Time current cycle full moon                 |
| currentlastquarter  | string  | letztes Viertel | Time current cycle last quarter              |
| sunazimut           | float   | Sun azimuth     | Sun azimuth                                  |
| sundistance         | float   | Sun distance    | Sun distance                                 |
| sunaltitude         | float   | Sun altitude    | Sun altitude                                 |
| sundirection        | integer | Sun direction   | Sun direction                                |
| season              | integer | Season          | Season                                       |
| picturemoon         | gif     | picture moon    | picture with current view of the moon        |
| sunmoonview         | string  | Position        | Image view sun and moon                      |
| sunset              | integer | Sunset          | Sun-, Moonset +  Offset                      |
| sunrise             | integer | Sunrise         | Sun-, Moonrise + Offset                      |

## 6. Annex

###  a. Functions:

#### Astronomy:

```php
Astronomy_SetAstronomyValues(int $InstanceID)
```
Updates all values selected in the module

```php
Astronomy_MoonphasePercent(int $InstanceID)
```
Returns the progress of the moon phase in %

```php
Astronomy_MoonphaseText(int $InstanceID)
```
Returns the moon phase as output text -% e.g. rising moon - 84%

```php
Astronomy_Moon_FirstQuarter(int $InstanceID)
```
Time First quarter, if the time is in the past, the next time of the next moon phase will be shown

```php
Astronomy_Moon_Newmoon(int $InstanceID)
```
Date of the new moon, if the time is in the past, the next time of the next moon phase will be shown


```php
Astronomy_Moon_Fullmoon(int $InstanceID)
```
Time Full moon, the time should be in the past, the next time the next moon phase is shown


```php
Astronomy_Moon_LastQuarter(int $InstanceID)
```
Time Last quarter, time First quarter, should be the time in the past will be shown the next time of the next phase of the moon


```php
Astronomy_Moon_CurrentFirstQuarter(int $InstanceID)
```
Time First quarter of the current cycle

```php
Astronomy_Moon_CurrentNewmoon(int $InstanceID)
```
Time New moon of the current cycle

```php
Astronomy_Moon_CurrentFullmoon(int $InstanceID)
```
Time Full moon of the current cycle

```php
Astronomy_Moon_CurrentLastQuarter(int $InstanceID)
```
Time Last quarter of the current cycle

```php
Astronomy_Moon_FirstQuarterDate(int $InstanceID, string $date)
```
Time First quarter of the submitted date

_$date_ Datum

```php
Astronomy_Moon_NewmoonDate(int $InstanceID, string $date)
```
Time New moon for the given date

_$date_ Datum

```php
Astronomy_Moon_FullmoonDate(int $InstanceID, string $date)
```
Time of full moon at the given date

_$date_ Datum

```php
Astronomy_Moon_LastQuarterDate(int $InstanceID, string $date)
```
Time Last quarter at the given date

_$date_ Datum

###  b. GUIDs and data exchange:

#### Astronomy:

GUID: `{AE370BEA-2B51-4C64-A147-0CCE3494FE08}` 
