# IPSymconAstronomy
[![Version](https://img.shields.io/badge/Symcon-PHPModule-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-%3E%209.0-green.svg)](https://www.symcon.de/en/service/documentation/installation/)
![Code](https://img.shields.io/badge/Code-PHP-blue.svg)
[![PHP Style](https://github.com/Wolbolar/IPSymconAstronomy/actions/workflows/php-style.yml/badge.svg)](https://github.com/Wolbolar/IPSymconAstronomy/actions/workflows/php-style.yml)

Module for IP-Symcon 9.0 and newer to display astronomical data, create time/phase images, and provide a modern Symcon tile visualization.

## Table of contents

1. [Features](#1-features)
2. [Requirements](#2-requirements)
3. [Installation](#3-installation)
4. [Visualization](#4-visualization)
5. [Configuration](#5-configuration)
6. [Functions](#6-functions)

## 1. Features

The module creates an Astronomy instance below the core instances. Variables, media images, and optional extras are selected directly in the instance form. Calculations are based on formulas from _"Practical Astronomy with your Calculator or Spreadsheet"_ by Peter Duffett-Smith and Jonathan Zwart.

The module currently provides:

* classic variables for WebFront, IPSView, and custom scripts
* media objects for moon image plus day and year twilight graphics
* configurable sunrise and sunset variables with offsets
* optional extra date/time variables
* HTML view for the position of sun and moon
* modern Symcon tile visualization

Selectable astronomical values:

* Julian date
* Moon azimuth
* Moon distance
* Moon altitude
* Moon bright limb angle
* Moon direction
* Moon visibility
* Moonrise
* Moonset
* Next moonrise
* Next moonset
* Moon progress
* Moon phase
* New moon
* First quarter
* Full moon
* Last quarter
* Current cycle new moon
* Current cycle first quarter
* Current cycle full moon
* Current cycle last quarter
* Sun azimuth
* Sun distance
* Sun altitude
* Sun direction
* Radiant power
* Season
* Moon in zodiac sign
* Sun in zodiac sign
* Moon age
* Solar noon
* Day length
* Night length
* Sun progress
* Golden hour morning start/end
* Golden hour evening start/end
* Blue hour morning start/end
* Blue hour evening start/end
* Moon culmination
* Moon lower culmination
* Sun above horizon
* Moon above horizon
* Current twilight phase
* Sun right ascension
* Sun declination
* Moon right ascension
* Moon declination
* Sun zodiac longitude
* Moon zodiac longitude
* Sun parallactic angle
* Moon parallactic angle

## 2. Requirements

* IP-Symcon 9.0 or newer
* configured `Location` instance with latitude and longitude

## 3. Installation

### a. Load the module

Open the IP-Symcon web console via `http://<IP-Symcon IP>:3777/console/`.

Click the module store icon in the upper right corner.

![Store](img/store_icon.png?raw=true "Store")

Enter

```text
Astronomy
```

in the search field.

![Search](img/module_store_search_en.png?raw=true "Module search")

Then select the module and click _Install_.

![Install](img/install_en.png?raw=true "Install")

### b. Alternative installation via the Modules instance

Open the object tree.

![Object tree](img/object_tree.png?raw=true "Object tree")

Open the _Modules_ instance below core instances and click the _Plus_ icon.

![Modules](img/modules.png?raw=true "Modules")

![Plus](img/plus.png?raw=true "Plus")

![Add module](img/add_module.png?raw=true "Add module")

Enter the following URL:

```text
https://github.com/Wolbolar/IPSymconAstronomy
```

Confirm with _OK_. By default the `master` branch is loaded.

![Master](img/master.png?raw=true "Master")

### c. Setup

In IP-Symcon choose _Core Instances -> Add Instance_ and select _Astronomy_. Then open the instance, enable the required values and visualizations, and confirm with _Apply Changes_.

![Apply changes](img/apply_changes_en.png?raw=true "Apply changes")

## 4. Visualization

Besides classic variables, the module also provides a modern tile view for Symcon 9.

![Tile overview](img/tile-overview.png?raw=true "Tile overview")

![Tile moon](img/tile-moon.png?raw=true "Tile moon")

![Tile position](img/tile-position.png?raw=true "Tile position")

![Tile twilight](img/tile-twilight.png?raw=true "Tile twilight")

![Tile values](img/tile-values.png?raw=true "Tile values")

![Tile overview compact](img/tile-overview-compact.png?raw=true "Tile overview compact")

## 5. Configuration

### General

| Property | Type | Description |
| :-- | :-- | :-- |
| `language` | Select | output language and tile labels (`German`, `English`) |
| `Updateinterval` | Integer | update interval in seconds |
| `UTC` | Float | UTC correction used for astronomical calculations |

### Selectable values

| Property | Type | Description |
| :-- | :-- | :-- |
| `juliandate` | Float | Julian date |
| `moonazimut` | Float | Moon azimuth |
| `moondistance` | Float | Earth -> moon distance |
| `moonaltitude` | Float | Moon altitude |
| `moonbrightlimbangle` | Float | Moon bright limb angle |
| `moondirection` | Integer | Moon direction |
| `moonvisibility` | Float | Moon visibility in percent |
| `moonrise` | Integer | Moonrise |
| `moonset` | Integer | Moonset |
| `nextmoonrise` | Integer | next future moonrise starting from now |
| `nextmoonset` | Integer | next future moonset starting from now |
| `moonprogress` | Integer | progress of the current moon visibility interval in percent |
| `moonphase` | String | Moon phase |
| `newmoon` | String | New moon time |
| `firstquarter` | String | First quarter time |
| `fullmoon` | String | Full moon time |
| `lastquarter` | String | Last quarter time |
| `currentnewmoon` | String | Current cycle new moon |
| `currentfirstquarter` | String | Current cycle first quarter |
| `currentfullmoon` | String | Current cycle full moon |
| `currentlastquarter` | String | Current cycle last quarter |
| `sunazimut` | Float | Sun azimuth |
| `sundistance` | Float | Earth -> sun distance |
| `sunaltitude` | Float | Sun altitude |
| `sundirection` | Integer | Sun direction |
| `radiant_power` | Float | Radiant power |
| `season` | Integer | Season |
| `moonstarsign` | String | Moon zodiac sign |
| `sunstarsign` | String | Sun zodiac sign |
| `moonage` | Float | Moon age in days |
| `solarnoon` | Integer | Solar noon |
| `daylength` | Integer | Day length |
| `nightlength` | Integer | Night length |
| `sunprogress` | Integer | progress of the current day between sunrise and sunset in percent |
| `goldenhourmorningstart` | Integer | Golden hour morning start |
| `goldenhourmorningend` | Integer | Golden hour morning end |
| `goldenhoureveningstart` | Integer | Golden hour evening start |
| `goldenhoureveningend` | Integer | Golden hour evening end |
| `bluehourmorningstart` | Integer | Blue hour morning start |
| `bluehourmorningend` | Integer | Blue hour morning end |
| `bluehoureveningstart` | Integer | Blue hour evening start |
| `bluehoureveningend` | Integer | Blue hour evening end |
| `moonculmination` | Integer | Moon culmination |
| `moonlowerculmination` | Integer | Moon lower culmination |
| `sunabovehorizon` | Boolean | Sun above horizon |
| `moonabovehorizon` | Boolean | Moon above horizon |
| `twilightphase` | String | Current twilight phase |
| `sunrightascension` | Float | Sun right ascension |
| `sundeclination` | Float | Sun declination |
| `moonrightascension` | Float | Moon right ascension |
| `moondeclination` | Float | Moon declination |
| `sunzodiaclongitude` | Float | Sun zodiac longitude |
| `moonzodiaclongitude` | Float | Moon zodiac longitude |
| `sunparallacticangle` | Float | Sun parallactic angle |
| `moonparallacticangle` | Float | Moon parallactic angle |

### Twilight images

| Property | Type | Description |
| :-- | :-- | :-- |
| `pictureyeartwilight` | Boolean | create year twilight graphic |
| `picturedaytwilight` | Boolean | create day twilight graphic |
| `picturetwilightlimited` | Boolean | use limited twilight view |

### Moon image

| Property | Type | Description |
| :-- | :-- | :-- |
| `picturemoonvisible` | Boolean | create moon image media object |
| `moonbackground` | Select | `black background` or `transparent background` |
| `selectionresize` | Boolean | resize images for the media element |
| `mediaimgwidth` | Integer | target media width |
| `mediaimgheight` | Integer | target media height |
| `picturemoonselection` | Boolean | use custom moon images |
| `picturename` | String | base filename without numeric suffix |
| `filetype` | Select | file type (`png`, `gif`, `jpg`) |
| `firstfullmoonpic` / `lastfullmoonpic` | Integer | first/last image index for full moon |
| `firstincreasingmoonpic` / `lastincreasingmoonpic` | Integer | first/last image index for waxing moon |
| `firstnewmoonpic` / `lastnewmoonpic` | Integer | first/last image index for new moon |
| `firstdecreasingmoonpic` / `lastdecreasingmoonpic` | Integer | first/last image index for waning moon |
| `picturemoonpath` | String | relative path to the moon image folder inside IP-Symcon |

### Sun and moon position view

| Property | Type | Description |
| :-- | :-- | :-- |
| `sunmoonview` | Boolean | create HTML view for the position of sun and moon |
| `zeropointx` | Integer | x-axis zero point |
| `zeropointy` | Integer | y-axis zero point |
| `framewidthtype` | Select | frame width in `px` or `%` |
| `framewidth` | Integer | frame width |
| `frameheighttype` | Select | frame height in `px` or `%` |
| `frameheight` | Integer | frame height |
| `canvaswidth` | Integer | canvas width in pixels |
| `canvasheight` | Integer | canvas height in pixels |

### Special time values

`moonrise` and `moonset` remain the daily values for the current reference day.

`nextmoonrise` and `nextmoonset` always return the next future event from the current moment. Because of that, `nextmoonset` may already point to the following day while `moonset` still contains today's daily value.

`sunprogress` returns the percentage progress of the current daytime interval between sunrise and sunset. Before sunrise the value is `0`, after sunset it is `100`.

`moonprogress` returns the percentage progress of the current moon visibility interval between the last moonrise and the next moonset. If the moon is currently below the horizon, the value is `0`.
| `canvasbackground` | Color | background color |
| `canvasbackgroundtransparency` | Integer | background transparency |

### Sunrise / sunset with offset

| Property | Type | Description |
| :-- | :-- | :-- |
| `sunriseselect` | Boolean | create rise variable with offset |
| `risetype` | Select | `sunrise`, `civilTwilightStart`, `nauticTwilightStart`, `astronomicTwilightStart`, `moonrise` |
| `sunriseoffset` | Integer | offset in minutes |
| `sunsetselect` | Boolean | create set variable with offset |
| `settype` | Select | `sunset`, `civilTwilightEnd`, `nauticTwilightEnd`, `astronomicTwilightEnd`, `moonset` |
| `sunsetoffset` | Integer | offset in minutes |
| `extinfoselection` | Boolean | create additional date/time variables |
| `timeformat` | Select | time format (`H:i`, `H:i:s`, `h:i`, `h:i:s`, `g:i`, `g:i:s`, `G:i`, `G:i:s`) |

## 6. Functions

## 7. Version history

* `3.0`
  * modernization for IP-Symcon 9 with `IPSModuleStrict`
  * new Symcon tile for a compact astronomy visualization
  * additional values such as day/night length, golden hour, blue hour, culmination, twilight phase, and further sun/moon coordinates
  * fixes for object lookup and more robust media/tile updates
  * documentation and screenshots refreshed
* `2.1`
  * conversion of deprecated `date_sunrise()` and `date_sunset()` calls to `date_sun_info()`
  * corrected the assumption that nautical and astronomical sunrise/sunset always exist
* `2.0`
  * compatibility with IPS 7

```php
Astronomy_SetAstronomyValues(int $InstanceID)
```

Updates all values enabled in the module.

```php
Astronomy_MoonphasePercent(int $InstanceID)
```

Returns the moon phase progress in percent.

```php
Astronomy_MoonphaseText(int $InstanceID)
```

Returns the moon phase as text.

```php
Astronomy_Moon_FirstQuarter(int $InstanceID)
Astronomy_Moon_Newmoon(int $InstanceID)
Astronomy_Moon_Fullmoon(int $InstanceID)
Astronomy_Moon_LastQuarter(int $InstanceID)
```

Returns the next calculated time of the respective moon phase.
