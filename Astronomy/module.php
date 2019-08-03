<?php

declare(strict_types=1);
// Modul Astronomie
// Formeln aus "Practical Astronomy" von Peter Duffett-Smith und Jonathan Zwart, Fourth Edition
// basiert auf den Skripten von ChokZul https://www.symcon.de/forum/threads/31467-Astronomische-Berechnungen?highlight=astronomie
// Twilight Grafiken generiert mit Skripten von Brownson aus der IPSLibrary
// set base dir
define('__ROOT__', dirname(dirname(__FILE__)));

// load ips constants
require_once __ROOT__ . '/libs/ips.constants.php';

require_once __DIR__ . '/../bootstrap.php';

use Fonzo\Moon\Moon;

class Astronomy extends IPSModule
{
    public function Create()
    {
//Never delete this line!
        parent::Create();

        //These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.

        $this->RegisterPropertyFloat('UTC', 1);
        $this->RegisterPropertyInteger('language', 1);
        $this->RegisterPropertyInteger('moonbackground', 1);
        $this->RegisterPropertyBoolean('juliandate', false);
        $this->RegisterPropertyBoolean('moonazimut', false);
        $this->RegisterPropertyBoolean('moondistance', false);
        $this->RegisterPropertyBoolean('moonaltitude', false);
        $this->RegisterPropertyBoolean('moonbrightlimbangle', false);
        $this->RegisterPropertyBoolean('moondirection', false);
        $this->RegisterPropertyBoolean('moonvisibility', false);
        $this->RegisterPropertyBoolean('moonrise', false);
        $this->RegisterPropertyBoolean('moonset', false);
        $this->RegisterPropertyBoolean('moonphase', false);
        $this->RegisterPropertyBoolean('newmoon', false);
        $this->RegisterPropertyBoolean('firstquarter', false);
        $this->RegisterPropertyBoolean('fullmoon', false);
        $this->RegisterPropertyBoolean('lastquarter', false);
        $this->RegisterPropertyBoolean('currentnewmoon', false);
        $this->RegisterPropertyBoolean('currentfirstquarter', false);
        $this->RegisterPropertyBoolean('currentfullmoon', false);
        $this->RegisterPropertyBoolean('currentlastquarter', false);
        $this->RegisterPropertyBoolean('moonstarsign', false);
        $this->RegisterPropertyBoolean('sunazimut', false);
        $this->RegisterPropertyBoolean('sundistance', false);
        $this->RegisterPropertyBoolean('sunaltitude', false);
        $this->RegisterPropertyBoolean('sundirection', false);
        $this->RegisterPropertyBoolean('season', false);
        $this->RegisterPropertyBoolean('sunstarsign', false);
        $this->RegisterPropertyBoolean('pictureyeartwilight', false);
        $this->RegisterPropertyBoolean('picturedaytwilight', false);
        $this->RegisterPropertyBoolean('picturetwilightlimited', false);
        $this->RegisterPropertyBoolean('picturemoonvisible', false);
        $this->RegisterPropertyBoolean('sunmoonview', false);
        $this->RegisterPropertyBoolean('selectionresize', false);
        $this->RegisterPropertyInteger('mediaimgwidth', 100);
        $this->RegisterPropertyInteger('mediaimgheight', 100);
        $this->RegisterPropertyBoolean('picturemoonselection', false);
        $this->RegisterPropertyInteger('firstfullmoonpic', 172);
        $this->RegisterPropertyInteger('lastfullmoonpic', 182);
        $this->RegisterPropertyInteger('firstincreasingmoonpic', 183);
        $this->RegisterPropertyInteger('lastincreasingmoonpic', 352);
        $this->RegisterPropertyInteger('firstnewmoonpic', 353);
        $this->RegisterPropertyInteger('lastnewmoonpic', 362);
        $this->RegisterPropertyInteger('firstdecreasingmoonpic', 8);
        $this->RegisterPropertyInteger('lastdecreasingmoonpic', 171);
        $this->RegisterPropertyString('picturemoonpath', 'media/mondphase');
        $this->RegisterPropertyInteger('filetype', 1);
        $this->RegisterPropertyString('picturename', 'mond');
        $this->RegisterPropertyBoolean('sunriseselect', false);
        $this->RegisterPropertyInteger('risetype', 1);
        $this->RegisterPropertyInteger('sunriseoffset', 0);
        $this->RegisterPropertyBoolean('sunsetselect', false);
        $this->RegisterPropertyInteger('settype', 1);
        $this->RegisterPropertyInteger('sunsetoffset', 0);
        $this->RegisterPropertyInteger('frameheight', 290);
        $this->RegisterPropertyInteger('framewidth', 100);
        $this->RegisterPropertyInteger('canvaswidth', 800);
        $this->RegisterPropertyInteger('canvasheight', 250);
        $this->RegisterPropertyInteger('frameheighttype', 1);
        $this->RegisterPropertyInteger('framewidthtype', 2);
        $this->RegisterPropertyBoolean('extinfoselection', false);
        $this->RegisterPropertyInteger('timeformat', 1);
        $this->RegisterPropertyInteger('Updateinterval', 0);
        $this->RegisterTimer('Update', 0, 'Astronomy_SetAstronomyValues(' . $this->InstanceID . ');');
        $this->RegisterPropertyInteger('zeropointy', 50);
        $this->RegisterPropertyInteger('zeropointx', 50);

        //we will wait until the kernel is ready
        $this->RegisterMessage(0, IPS_KERNELMESSAGE);
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        if (IPS_GetKernelRunlevel() !== KR_READY) {
            return;
        }

        $this->ValidateConfiguration();
        $this->SetCyclicTimerInterval();
    }

    protected function SetCyclicTimerInterval()
    {
        $seconds = $this->ReadPropertyInteger('Updateinterval');
        $Interval = $seconds * 1000;
        $this->SetTimerInterval('Update', $Interval);

    }

    /**
     * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
     * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:.
     */
    private function ValidateConfiguration()
    {
        $associations = [];
        if ($this->ReadPropertyBoolean('juliandate') == true) // float
        {
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Julianisches_Datum', 'Calendar', '', ' Tage', 0, 0, 0, 1, $associations);
            $this->SetupVariable('juliandate', 'Julianisches Datum', 'Astronomie.Julianisches_Datum', 1, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('juliandate', 'Julianisches Datum', 'Astronomie.Julianisches_Datum', 1, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('moonazimut') == true) // float
        {
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Mond_Azimut', 'Moon', '', '°', 0, 0, 0, 2, $associations);
            $this->SetupVariable('moonazimut', 'Mond Azimut', 'Astronomie.Mond_Azimut', 2, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('moonazimut', 'Mond Azimut', 'Astronomie.Mond_Azimut', 2, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('moondistance') == true) // float
        {
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Mond_Entfernung', 'Moon', '', ' km', 0, 0, 0, 0, $associations);
            $this->SetupVariable('moondistance', 'Mond Entfernung', 'Astronomie.Mond_Entfernung', 3, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('moondistance', 'Mond Entfernung', 'Astronomie.Mond_Entfernung', 3, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('moonaltitude') == true) // float
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Mond_Hoehe', 'Moon', '', '°', 0, 0, 0, 2, $associations);
            $this->SetupVariable('moonaltitude', 'Mond Höhe', 'Astronomie.Mond_Hoehe', 4, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('moonaltitude', 'Mond Höhe', 'Astronomie.Mond_Hoehe', 4, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('moonbrightlimbangle') == true) // float
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Mond_Positionswinkel', 'Moon', '', '°', 0, 0, 0, 2, $associations);
            $this->SetupVariable('moonbrightlimbangle', 'Mond Positionswinkel der beleuchteten Fläche', 'Astronomie.Mond_Positionswinkel', 5, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('moonbrightlimbangle', 'Mond Positionswinkel der beleuchteten Fläche', 'Astronomie.Mond_Positionswinkel', 5, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('moondirection') == true) // integer
        {
            $language = $this->ReadPropertyInteger('language');
            if ($language == 1) //ger
            {
                $associations = [
                    [0, 'N', '', -1],
                    [1, 'NNO', '', -1],
                    [2, 'NO', '', -1],
                    [3, 'ONO', '', -1],
                    [4, 'O', '', -1],
                    [5, 'OSO', '', -1],
                    [6, 'SO', '', -1],
                    [7, 'SSO', '', -1],
                    [8, 'S', '', -1],
                    [9, 'SSW', '', -1],
                    [10, 'SW', '', -1],
                    [11, 'WSW', '', -1],
                    [12, 'W', '', -1],
                    [13, 'WNW', '', -1],
                    [14, 'NW', '', -1],
                    [15, 'NNW', '', -1]
                ];
            } elseif ($language == 2) // eng
            {
                $associations = [
                    [0, 'N', '', -1],
                    [1, 'NNE', '', -1],
                    [2, 'NE', '', -1],
                    [3, 'ENE', '', -1],
                    [4, 'E', '', -1],
                    [5, 'ESE', '', -1],
                    [6, 'SE', '', -1],
                    [7, 'SSE', '', -1],
                    [8, 'S', '', -1],
                    [9, 'SSW', '', -1],
                    [10, 'SW', '', -1],
                    [11, 'WSW', '', -1],
                    [12, 'W', '', -1],
                    [13, 'WNW', '', -1],
                    [14, 'NW', '', -1],
                    [15, 'NNW', '', -1]
                ];
            }
            $this->SetupProfile(VARIABLETYPE_INTEGER, 'Astronomie.Mond_Himmelsrichtung', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('moondirection', 'Mond Richtung', 'Astronomie.Mond_Himmelsrichtung', 6, VARIABLETYPE_INTEGER, true);
        } else {
            $this->SetupVariable('moondirection', 'Mond Richtung', 'Astronomie.Mond_Himmelsrichtung', 6, VARIABLETYPE_INTEGER, false);
        }
        if ($this->ReadPropertyBoolean('moonvisibility') == true) // float
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Mond_Sichtbarkeit', 'Moon', '', ' %', 0, 0, 0, 1, $associations);
            $this->SetupVariable('moonvisibility', 'Mond Sichtbarkeit', 'Astronomie.Mond_Sichtbarkeit', 7, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('moonvisibility', 'Mond Sichtbarkeit', 'Astronomie.Mond_Sichtbarkeit', 7, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('moonrise') == true) // int
        {
            $ipsversion = $this->GetIPSVersion();
            if ($ipsversion == 0 || $ipsversion == 1) {
                $objid = $this->SetupVariable('moonrise', 'Mondaufgang', '~UnixTimestamp', 8, VARIABLETYPE_INTEGER, true);
            } else {
                $objid = $this->SetupVariable('moonrise', 'Mondaufgang', '~UnixTimestampTime', 8, VARIABLETYPE_INTEGER, true);
            }

            IPS_SetIcon($objid, 'Moon');
        } else {
            $this->SetupVariable('moonrise', 'Mondaufgang', '~UnixTimestampTime', 8, VARIABLETYPE_INTEGER, false);
        }
        if ($this->ReadPropertyBoolean('moonset') == true) // int
        {
            $ipsversion = $this->GetIPSVersion();
            if ($ipsversion == 0 || $ipsversion == 1) {
                $objid = $this->SetupVariable('moonset', 'Monduntergang', '~UnixTimestamp', 9, VARIABLETYPE_INTEGER, true);
            } else {
                $objid = $this->SetupVariable('moonset', 'Monduntergang', '~UnixTimestampTime', 9, VARIABLETYPE_INTEGER, true);
            }

            IPS_SetIcon($objid, 'Moon');
        } else {
            $this->SetupVariable('moonset', 'Monduntergang', '~UnixTimestampTime', 9, VARIABLETYPE_INTEGER, false);
        }
        if ($this->ReadPropertyBoolean('moonphase') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Phase', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('moonphase', 'Mond Phase', 'Astronomie.Mond_Phase', 10, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('moonphase', 'Mond Phase', 'Astronomie.Mond_Phase', 10, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('newmoon') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Neumond', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('newmoon', 'Neumond', 'Astronomie.Mond_Neumond', 11, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('newmoon', 'Neumond', 'Astronomie.Mond_Neumond', 11, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('firstquarter') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_ErstesViertel', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('firstquarter', 'Erstes Viertel', 'Astronomie.Mond_ErstesViertel', 12, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('firstquarter', 'Erstes Viertel', 'Astronomie.Mond_ErstesViertel', 12, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('fullmoon') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Vollmond', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('fullmoon', 'Vollmond', 'Astronomie.Mond_Vollmond', 13, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('fullmoon', 'Vollmond', 'Astronomie.Mond_Vollmond', 13, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('lastquarter') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_LetztesViertel', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('lastquarter', 'Letztes Viertel', 'Astronomie.Mond_LetztesViertel', 14, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('lastquarter', 'Letztes Viertel', 'Astronomie.Mond_LetztesViertel', 14, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('currentnewmoon') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Neumond', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('currentnewmoon', 'Neumond Aktueller Zyklus', 'Astronomie.Mond_Neumond', 15, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('currentnewmoon', 'Neumond Aktueller Zyklus', 'Astronomie.Mond_Neumond', 15, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('currentfirstquarter') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_ErstesViertel', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('currentfirstquarter', 'Erstes Viertel Aktueller Zyklus', 'Astronomie.Mond_ErstesViertel', 16, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('currentfirstquarter', 'Erstes Viertel Aktueller Zyklus', 'Astronomie.Mond_ErstesViertel', 16, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('currentfullmoon') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Vollmond', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('currentfullmoon', 'Vollmond Aktueller Zyklus', 'Astronomie.Mond_Vollmond', 17, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('currentfullmoon', 'Vollmond Aktueller Zyklus', 'Astronomie.Mond_Vollmond', 17, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('currentlastquarter') == true) // string
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_LetztesViertel', 'Moon', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('currentlastquarter', 'Letztes Viertel Aktueller Zyklus', 'Astronomie.Mond_LetztesViertel', 18, VARIABLETYPE_STRING, true);
        } else {
            $this->SetupVariable('currentlastquarter', 'Letztes Viertel Aktueller Zyklus', 'Astronomie.Mond_LetztesViertel', 18, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('sunazimut') == true) // float
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Sonne_Azimut', 'Sun', '', '°', 0, 0, 0, 2, $associations);
            $this->SetupVariable('sunazimut', 'Sonne Azimut', 'Astronomie.Sonne_Azimut', 19, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('sunazimut', 'Sonne Azimut', 'Astronomie.Sonne_Azimut', 19, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('sundistance') == true) // float
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Sonne_Entfernung', 'Sun', '', ' km', 0, 0, 0, 0, $associations);
            $this->SetupVariable('sundistance', 'Sonne Entfernung', 'Astronomie.Sonne_Entfernung', 20, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('sundistance', 'Sonne Entfernung', 'Astronomie.Sonne_Entfernung', 20, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('sunaltitude') == true) // float
        {
            $associations = [];
            $this->SetupProfile(VARIABLETYPE_FLOAT, 'Astronomie.Sonne_Hoehe', 'Sun', '', '°', 0, 0, 0, 2, $associations);
            $this->SetupVariable('sunaltitude', 'Sonne Höhe', 'Astronomie.Sonne_Hoehe', 21, VARIABLETYPE_FLOAT, true);
        } else {
            $this->SetupVariable('sunaltitude', 'Sonne Höhe', 'Astronomie.Sonne_Hoehe', 21, VARIABLETYPE_FLOAT, false);
        }
        if ($this->ReadPropertyBoolean('sundirection') == true) // integer
        {
            $language = $this->ReadPropertyInteger('language');
            if ($language == 1) //ger
            {
                $associations = [
                    [0, 'N', '', -1],
                    [1, 'NNO', '', -1],
                    [2, 'NO', '', -1],
                    [3, 'ONO', '', -1],
                    [4, 'O', '', -1],
                    [5, 'OSO', '', -1],
                    [6, 'SO', '', -1],
                    [7, 'SSO', '', -1],
                    [8, 'S', '', -1],
                    [9, 'SSW', '', -1],
                    [10, 'SW', '', -1],
                    [11, 'WSW', '', -1],
                    [12, 'W', '', -1],
                    [13, 'WNW', '', -1],
                    [14, 'NW', '', -1],
                    [15, 'NNW', '', -1]
                ];
            } elseif ($language == 2) // eng
            {
                $associations = [
                    [0, 'N', '', -1],
                    [1, 'NNE', '', -1],
                    [2, 'NE', '', -1],
                    [3, 'ENE', '', -1],
                    [4, 'E', '', -1],
                    [5, 'ESE', '', -1],
                    [6, 'SE', '', -1],
                    [7, 'SSE', '', -1],
                    [8, 'S', '', -1],
                    [9, 'SSW', '', -1],
                    [10, 'SW', '', -1],
                    [11, 'WSW', '', -1],
                    [12, 'W', '', -1],
                    [13, 'WNW', '', -1],
                    [14, 'NW', '', -1],
                    [15, 'NNW', '', -1]
                ];
            }
            $this->SetupProfile(VARIABLETYPE_INTEGER, 'Astronomie.Sonne_Himmelsrichtung', 'Sun', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('sundirection', 'Sonne Richtung', 'Astronomie.Sonne_Himmelsrichtung', 22, VARIABLETYPE_INTEGER, true);
        } else {
            $this->SetupVariable('sundirection', 'Sonne Richtung', 'Astronomie.Sonne_Himmelsrichtung', 22, VARIABLETYPE_INTEGER, false);
        }
        if ($this->ReadPropertyBoolean('season') == true) // integer
        {
            $language = $this->ReadPropertyInteger('language');
            if ($language == 1) //ger
            {
                $associations = [
                    [1, 'Frühling', '', -1],
                    [2, 'Sommer', '', -1],
                    [3, 'Herbst', '', -1],
                    [4, 'Winter', '', -1]
                ];
            } elseif ($language == 2) // eng
            {
                $associations = [
                    [1, 'Spring', '', -1],
                    [2, 'Sommer', '', -1],
                    [3, 'Autumn', '', -1],
                    [4, 'Winter', '', -1]
                ];
            }
            $this->SetupProfile(VARIABLETYPE_INTEGER, 'Astronomie.Jahreszeit', 'Sun', '', '', 0, 0, 0, 0, $associations);
            $this->SetupVariable('season', 'Jahreszeit', 'Astronomie.Jahreszeit', 23, VARIABLETYPE_INTEGER, true);
        } else {
            $this->SetupVariable('season', 'Jahreszeit', 'Astronomie.Jahreszeit', 23, VARIABLETYPE_INTEGER, false);
        }
        if ($this->ReadPropertyBoolean('pictureyeartwilight') == true) {
            $limited = $this->ReadPropertyBoolean('picturetwilightlimited');
            if ($limited) {
                $type = 'Limited';
            } else {
                $type = 'Standard';
            }
            $this->TwilightYearPicture($type);
        } else {
            $MediaID = @IPS_GetObjectIDByIdent('TwilightYearPicture', $this->InstanceID);
            if ($MediaID > 0)
                IPS_DeleteMedia($MediaID, true);
            $this->SendDebug('Astronomy:', 'TwilightYearPicture gelöscht', 0);
        }
        if ($this->ReadPropertyBoolean('picturedaytwilight') == true) {
            $limited = $this->ReadPropertyBoolean('picturetwilightlimited');
            if ($limited) {
                $type = 'Limited';
            } else {
                $type = 'Standard';
            }
            $this->TwilightDayPicture($type);
        } else {
            $MediaID = @IPS_GetObjectIDByIdent('TwilightDayPicture', $this->InstanceID);
            if ($MediaID > 0)
                IPS_DeleteMedia($MediaID, true);
        }
        if ($this->ReadPropertyBoolean('picturemoonvisible') == true) {
            $mondphase = $this->MoonphasePercent();
            $picture = $this->GetMoonPicture($mondphase);
            $objid = $this->UpdateMedia($picture['picid']);
            if ($objid > 0) {
                IPS_SetIcon($objid, 'Moon');
            }

        } else {
            //$MediaID = @IPS_GetObjectIDByIdent('picturemoon', $this->InstanceID);
            $MediaID = @$this->GetIDForIdent('picturemoon');
            if ($MediaID > 0) {
                $this->SendDebug('Astronomy:', 'Delete MediaID ' . $MediaID, 0);
                IPS_DeleteMedia($MediaID, true);
            }

        }
        if ($this->ReadPropertyBoolean('sunmoonview') == true) // string
        {
            $objid = $this->SetupVariable('sunmoonview', 'Position Sonne und Mond', '~HTMLBox', 24, VARIABLETYPE_STRING, true);
            IPS_SetIcon($objid, 'Sun');
        } else {
            $this->SetupVariable('sunmoonview', 'Position Sonne und Mond', '~HTMLBox', 24, VARIABLETYPE_STRING, false);
        }
        if ($this->ReadPropertyBoolean('sunsetselect') == true) // string
        {
            $objid = $this->SetupVariable('sunset', 'Sonnenuntergang', '~UnixTimestamp', 25, VARIABLETYPE_INTEGER, true);
            IPS_SetIcon($objid, 'Sun');

            //Moon
            $settype = $this->ReadPropertyInteger('settype');
            if ($settype == 5) {
                $moonset = $this->ReadPropertyBoolean('moonset');
                if ($moonset) {
                    $this->SendDebug('Astronomy:', 'Moonset selected', 0);
                } else {
                    $this->SendDebug('Astronomy:', 'Moonset is not selected, first select moonset to setup variable', 0);
                    $this->SetStatus(210);
                }
            }
        } else {
            $this->SetupVariable('sunset', 'Sonnenuntergang', '~UnixTimestamp', 25, VARIABLETYPE_INTEGER, false);
        }
        if ($this->ReadPropertyBoolean('sunriseselect') == true) // string
        {
            $objid = $this->SetupVariable('sunrise', 'Sonnenaufgang', '~UnixTimestamp', 26, VARIABLETYPE_INTEGER, true);
            IPS_SetIcon($objid, 'Sun');

            // Moon
            $risetype = $this->ReadPropertyInteger('risetype');
            if ($risetype == 5) {
                $moonrise = $this->ReadPropertyBoolean('moonrise');
                if ($moonrise) {
                    $this->SendDebug('Astronomy:', 'Moonrise selected', 0);
                } else {
                    $this->SendDebug('Astronomy:', 'Moonrise is not selected, first select moonset to setup variable', 0);
                    $this->SetStatus(211);
                }
            }
        } else {
            $this->SetupVariable('sunrise', 'Sonnenaufgang', '~UnixTimestamp', 26, VARIABLETYPE_INTEGER, false);
        }
        if ($this->ReadPropertyBoolean('extinfoselection') == true) // string
        {
            if ($this->ReadPropertyBoolean('sunsetselect') == true) // string
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Sonnenuntergang_Zeit', 'Sun', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('sunsettime', 'Sonnenuntergang Uhrzeit', 'Astronomie.Sonnenuntergang_Zeit', 27, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('sunsettime', 'Sonnenuntergang Uhrzeit', 'Astronomie.Sonnenuntergang_Zeit', 27, VARIABLETYPE_INTEGER, false);
            }
            if ($this->ReadPropertyBoolean('sunriseselect') == true) // string
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Sonnenaufgang_Zeit', 'Sun', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('sunrisetime', 'Sonnenaufgang Uhrzeit', 'Astronomie.Sonnenaufgang_Zeit', 28, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('sunrisetime', 'Sonnenaufgang Uhrzeit', 'Astronomie.Sonnenaufgang_Zeit', 28, VARIABLETYPE_INTEGER, false);
            }
            if ($this->ReadPropertyBoolean('newmoon') == true) // string
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Neumond_Datum', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('newmoondate', 'Neumond Datum', 'Astronomie.Mond_Neumond_Datum', 29, VARIABLETYPE_STRING, true);
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Neumond_Zeit', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('newmoontime', 'Neumond Uhrzeit', 'Astronomie.Mond_Neumond_Zeit', 30, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('newmoondate', 'Neumond Datum', 'Astronomie.Mond_Neumond_Datum', 29, VARIABLETYPE_STRING, false);
                $this->SetupVariable('newmoontime', 'Neumond Uhrzeit', 'Astronomie.Mond_Neumond_Zeit', 30, VARIABLETYPE_STRING, false);
            }
            if ($this->ReadPropertyBoolean('firstquarter') == true) // string
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_ErstesViertel_Datum', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('firstquarterdate', 'Erstes Viertel Datum', 'Astronomie.Mond_ErstesViertel_Datum', 31, VARIABLETYPE_STRING, true);
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_ErstesViertel_Zeit', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('firstquartertime', 'Erstes Viertel Uhrzeit', 'Astronomie.Mond_ErstesViertel_Zeit', 32, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('firstquarterdate', 'Erstes Viertel Datum', 'Astronomie.Mond_ErstesViertel_Datum', 31, VARIABLETYPE_STRING, false);
                $this->SetupVariable('firstquartertime', 'Erstes Viertel Uhrzeit', 'Astronomie.Mond_ErstesViertel_Zeit', 32, VARIABLETYPE_STRING, false);
            }
            if ($this->ReadPropertyBoolean('fullmoon') == true) // string
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Vollmond_Datum', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('fullmoondate', 'Vollmond Datum', 'Astronomie.Mond_Vollmond_Datum', 33, VARIABLETYPE_STRING, true);
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Vollmond_Zeit', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('fullmoontime', 'Vollmond Uhrzeit', 'Astronomie.Mond_Vollmond_Zeit', 34, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('fullmoondate', 'Vollmond', 'Astronomie.Mond_Vollmond_Datum', 33, VARIABLETYPE_STRING, false);
                $this->SetupVariable('fullmoontime', 'Vollmond', 'Astronomie.Mond_Vollmond_Zeit', 34, VARIABLETYPE_STRING, false);
            }
            if ($this->ReadPropertyBoolean('lastquarter') == true) // string
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_LetztesViertel_Datum', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('lastquarterdate', 'Letztes Viertel Datum', 'Astronomie.Mond_LetztesViertel_Datum', 35, VARIABLETYPE_STRING, true);
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_LetztesViertel_Zeit', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('lastquartertime', 'Letztes Viertel Uhrzeit', 'Astronomie.Mond_LetztesViertel_Zeit', 36, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('lastquarterdate', 'Letztes Viertel Datum', 'Astronomie.Mond_LetztesViertel_Datum', 35, VARIABLETYPE_STRING, false);
                $this->SetupVariable('lastquartertime', 'Letztes Viertel Zeit', 'Astronomie.Mond_LetztesViertel_Zeit', 36, VARIABLETYPE_STRING, false);
            }
            if ($this->ReadPropertyBoolean('moonrise') == true) // int
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Mondaufgang_Datum', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('moonrisedate', 'Mondaufgang Datum', 'Astronomie.Mond_Mondaufgang_Datum', 37, VARIABLETYPE_STRING, true);
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Mondaufgang_Zeit', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('moonrisetime', 'Mondaufgang Uhrzeit', 'Astronomie.Mond_Mondaufgang_Zeit', 38, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('moonrisedate', 'Mondaufgang Datum', 'Astronomie.Mond_Mondaufgang_Datum', 37, VARIABLETYPE_INTEGER, false);
                $this->SetupVariable('moonrisetime', 'Mondaufgang Uhrzeit', 'Astronomie.Mond_Mondaufgang_Zeit', 38, VARIABLETYPE_INTEGER, false);
            }
            if ($this->ReadPropertyBoolean('moonset') == true) // int
            {
                $associations = [];
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Monduntergang_Datum', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('moonsetdate', 'Monduntergang Datum', 'Astronomie.Mond_Monduntergang_Datum', 39, VARIABLETYPE_STRING, true);
                $this->SetupProfile(VARIABLETYPE_STRING, 'Astronomie.Mond_Monduntergang_Zeit', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('moonsettime', 'Monduntergang Uhrzeit', 'Astronomie.Mond_Monduntergang_Zeit', 40, VARIABLETYPE_STRING, true);
            } else {
                $this->SetupVariable('moonsetdate', 'Monduntergang Datum', 'Astronomie.Mond_Monduntergang_Datum', 39, VARIABLETYPE_INTEGER, false);
                $this->SetupVariable('moonsettime', 'Monduntergang Zeit', 'Astronomie.Mond_Monduntergang_Zeit', 40, VARIABLETYPE_INTEGER, false);
            }
            if ($this->ReadPropertyBoolean('sunstarsign') == true)
            {
                $language = $this->ReadPropertyInteger('language');
                if ($language == 1) //ger
                {
                    $associations = [
                        [1, 'Widder', '', -1],
                        [2, 'Stier', '', -1],
                        [3, 'Zwillinge', '', -1],
                        [4, 'Krebs', '', -1],
                        [5, 'Löwe', '', -1],
                        [6, 'Jungfrau', '', -1],
                        [7, 'Waage', '', -1],
                        [8, 'Skorpion', '', -1],
                        [9, 'Schütze', '', -1],
                        [10, 'Steinbock', '', -1],
                        [11, 'Wassermann', '', -1],
                        [12, 'Fische', '', -1]
                    ];
                } elseif ($language == 2) // eng
                {
                    $associations = [
                        [1, 'Aries', '', -1],
                        [2, 'Taurus', '', -1],
                        [3, 'Gemini', '', -1],
                        [4, 'Cancer', '', -1],
                        [5, 'Leo', '', -1],
                        [6, 'Virgo', '', -1],
                        [7, 'Libra', '', -1],
                        [8, 'Scorpio', '', -1],
                        [9, 'Sagittarius', '', -1],
                        [10, 'Capricorn', '', -1],
                        [11, 'Aquarius', '', -1],
                        [12, 'Pisces', '', -1]
                    ];
                }
                $this->SetupProfile(VARIABLETYPE_INTEGER, 'Astronomie.Sonne_Sternzeichen', 'Sun', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('sunstarsign', $this->Translate('sun in star sign'), 'Astronomie.Sonne_Sternzeichen', 44, VARIABLETYPE_INTEGER, true);
            } else {
                $this->SetupVariable('sunstarsign', $this->Translate('sun in star sign'), 'Astronomie.Sonne_Sternzeichen', 44, VARIABLETYPE_INTEGER, false);
            }
            if ($this->ReadPropertyBoolean('moonstarsign') == true) // integer
            {
                $language = $this->ReadPropertyInteger('language');
                if ($language == 1) //ger
                {
                    $associations = [
                        [1, 'Widder', '', -1],
                        [2, 'Stier', '', -1],
                        [3, 'Zwillinge', '', -1],
                        [4, 'Krebs', '', -1],
                        [5, 'Löwe', '', -1],
                        [6, 'Jungfrau', '', -1],
                        [7, 'Waage', '', -1],
                        [8, 'Skorpion', '', -1],
                        [9, 'Schütze', '', -1],
                        [10, 'Steinbock', '', -1],
                        [11, 'Wassermann', '', -1],
                        [12, 'Fische', '', -1]
                    ];
                } elseif ($language == 2) // eng
                {
                    $associations = [
                        [1, 'Aries', '', -1],
                        [2, 'Taurus', '', -1],
                        [3, 'Gemini', '', -1],
                        [4, 'Cancer', '', -1],
                        [5, 'Leo', '', -1],
                        [6, 'Virgo', '', -1],
                        [7, 'Libra', '', -1],
                        [8, 'Scorpio', '', -1],
                        [9, 'Sagittarius', '', -1],
                        [10, 'Capricorn', '', -1],
                        [11, 'Aquarius', '', -1],
                        [12, 'Pisces', '', -1]
                    ];
                }
                $this->SetupProfile(VARIABLETYPE_INTEGER, 'Astronomie.Mond_Sternzeichen', 'Moon', '', '', 0, 0, 0, 0, $associations);
                $this->SetupVariable('moonstarsign', $this->Translate('moon in star sign'), 'Astronomie.Mond_Sternzeichen', 45, VARIABLETYPE_INTEGER, true);
            } else {
                $this->SetupVariable('moonstarsign', $this->Translate('moon in star sign'), 'Astronomie.Mond_Sternzeichen', 45, VARIABLETYPE_INTEGER, false);
            }
        } else {
            $this->SetupVariable('sunsettime', 'Sonnenuntergang Uhrzeit', 'Astronomie.Sonnenuntergang_Zeit', 27, VARIABLETYPE_INTEGER, false);
            $this->SetupVariable('sunrisetime', 'Sonnenaufgang Uhrzeit', 'Astronomie.Sonnenaufgang_Zeit', 28, VARIABLETYPE_INTEGER, false);
            $this->SetupVariable('newmoondate', 'Neumond Datum', 'Astronomie.Mond_Neumond_Datum', 29, VARIABLETYPE_STRING, false);
            $this->SetupVariable('newmoontime', 'Neumond Uhrzeit', 'Astronomie.Mond_Neumond_Zeit', 30, VARIABLETYPE_STRING, false);
            $this->SetupVariable('firstquarterdate', 'Erstes Viertel Datum', 'Astronomie.Mond_ErstesViertel_Datum', 31, VARIABLETYPE_STRING, false);
            $this->SetupVariable('firstquartertime', 'Erstes Viertel Uhrzeit', 'Astronomie.Mond_ErstesViertel_Zeit', 32, VARIABLETYPE_STRING, false);
            $this->SetupVariable('fullmoondate', 'Vollmond', 'Astronomie.Mond_Vollmond_Datum', 33, VARIABLETYPE_STRING, false);
            $this->SetupVariable('fullmoontime', 'Vollmond', 'Astronomie.Mond_Vollmond_Zeit', 34, VARIABLETYPE_STRING, false);
            $this->SetupVariable('lastquarterdate', 'Letztes Viertel Datum', 'Astronomie.Mond_LetztesViertel_Datum', 35, VARIABLETYPE_STRING, false);
            $this->SetupVariable('lastquartertime', 'Letztes Viertel Zeit', 'Astronomie.Mond_LetztesViertel_Zeit', 36, VARIABLETYPE_STRING, false);
            $this->SetupVariable('moonrisedate', 'Mondaufgang Datum', 'Astronomie.Mond_Mondaufgang_Datum', 37, VARIABLETYPE_INTEGER, false);
            $this->SetupVariable('moonrisetime', 'Mondaufgang Uhrzeit', 'Astronomie.Mond_Mondaufgang_Zeit', 38, VARIABLETYPE_INTEGER, false);
            $this->SetupVariable('moonsetdate', 'Monduntergang Datum', 'Astronomie.Mond_Monduntergang_Datum', 39, VARIABLETYPE_INTEGER, false);
            $this->SetupVariable('moonsettime', 'Monduntergang Zeit', 'Astronomie.Mond_Monduntergang_Zeit', 40, VARIABLETYPE_INTEGER, false);
        }
        // Status Aktiv
        $this->SetStatus(102);

    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {

        switch ($Message) {
            case IM_CHANGESTATUS:
                if ($Data[0] === IS_ACTIVE) {
                    $this->ApplyChanges();
                }
                break;

            case IPS_KERNELMESSAGE:
                if ($Data[0] === KR_READY) {
                    $this->ApplyChanges();
                }
                break;

            default:
                break;
        }
    }

    protected function UpdateMedia($picid)
    {
        //testen ob im Medienpool existent
        $picturename = $this->ReadPropertyString('picturename');
        $selectionresize = $this->ReadPropertyBoolean('selectionresize');
        $mediaimgwidth = $this->ReadPropertyInteger('mediaimgwidth');
        $mediaimgheight = $this->ReadPropertyInteger('mediaimgheight');
        $picturemoonselection = $this->ReadPropertyBoolean('picturemoonselection');
        $ImageFile = '';
        if ($picturemoonselection) {
            $path = $this->ReadPropertyString('picturemoonpath');
            $filetype = $this->ReadPropertyInteger('filetype');
            if ($filetype == 1) {
                $filetype = 'png';
            } elseif ($filetype == 2) {
                $filetype = 'gif';
            } elseif ($filetype == 3) {
                $filetype = 'jpg';
            }
            $ImageFile = IPS_GetKernelDir() . $path . DIRECTORY_SEPARATOR . $picturename . $picid . '.' . $filetype;
        } else {
            $background = $this->ReadPropertyInteger('moonbackground');
            $this->SendDebug('module directory', __DIR__, 0);
            if ($background == 1) {
                $ImageFile =  __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'mond' . DIRECTORY_SEPARATOR . 'mond' . $picid . '.gif';  // Image-Datei
            } elseif ($background == 2) {
                $ImageFile =  __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'mondtransparent' . DIRECTORY_SEPARATOR . 'mond' . $picid . '.png';  // Image-Datei
            }
        }
        if ($selectionresize)//resize image
        {
            $picturename = $picturename . $picid;
            $imageinfo = $this->getimageinfo($ImageFile);
            if ($imageinfo) {
                $image = $this->createimage($ImageFile, $imageinfo['imagetype']);
                $thumb = $this->createthumbnail($mediaimgwidth, $mediaimgheight, $imageinfo['imagewidth'], $imageinfo['imageheight']);
                $thumbimg = $thumb['img'];
                $thumbwidth = intval($thumb['width']);
                $thumbheight = intval($thumb['height']);
                $imagewidth = intval($imageinfo['imagewidth']);
                $imageheight = intval($imageinfo['imageheight']);
                $ImageFile = $this->copyimgtothumbnail($thumbimg, $image, $thumbwidth, $thumbheight, $imagewidth, $imageheight, $picturename);
            } else {
                IPS_LogMessage('Astronomy', 'Bild wurde nicht gefunden.');
            }

        }
        $Content = file_get_contents($ImageFile);
        $name = 'Mond Ansicht';
        $MediaID = $this->CreateMediaImage('picturemoon', $name, $picid, $Content, $ImageFile, 21, 'picturemoonvisible');
        return $MediaID;
    }

    public function GetModuleDirectory()
    {
        $dir = __DIR__;
        return $dir;
    }

    protected function CreateMediaImage($Ident, $name, $picid, $Content, $ImageFile, $position, $visible)
    {
        $MediaID = false;
        if ($this->ReadPropertyBoolean($visible) == true) {
            $MediaID = @$this->GetIDForIdent($Ident);
            if ($MediaID === false) {
                $MediaID = IPS_CreateMedia(1);                  // Image im MedienPool anlegen
                IPS_SetParent($MediaID, $this->InstanceID); // Medienobjekt einsortieren unter dem Modul
                IPS_SetIdent($MediaID, $Ident);
                IPS_SetPosition($MediaID, $position);
                IPS_SetMediaCached($MediaID, true);
                // Das Cachen für das Mediaobjekt wird aktiviert.
                // Beim ersten Zugriff wird dieses von der Festplatte ausgelesen
                // und zukünftig nur noch im Arbeitsspeicher verarbeitet.
                IPS_SetName($MediaID, $name); // Medienobjekt benennen
            }

            IPS_SetMediaFile($MediaID, $ImageFile, false);    // Image im MedienPool mit Image-Datei verbinden
            IPS_SetInfo($MediaID, $picid);
            IPS_SetMediaContent($MediaID, base64_encode($Content));  //Bild Base64 codieren und ablegen
            IPS_SendMediaEvent($MediaID); //aktualisieren
        }
        return $MediaID;
    }

    protected function getimageinfo($imagefile)
    {
        if (!$imagefile == '') {
            $imagesize = getimagesize($imagefile);
            $imagewidth = $imagesize[0];
            $imageheight = $imagesize[1];
            $imagetype = $imagesize[2];
            $imageinfo = ['imagewidth' => $imagewidth, 'imageheight' => $imageheight, 'imagetype' => $imagetype];
        } else {
            $imageinfo = '';
        }
        return $imageinfo;
    }

    protected function createimage($imagefile, $imagetype)
    {
        switch ($imagetype) {
            // Bedeutung von $imagetype:
            // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
            case 1: // GIF
                $image = imagecreatefromgif($imagefile);
                break;
            case 2: // JPEG
                $image = imagecreatefromjpeg($imagefile);
                break;
            case 3: // PNG
                $image = imagecreatefrompng($imagefile);
                //imagealphablending($image, true); // setting alpha blending on
                //imagesavealpha($image, true); // save alphablending setting (important)
                break;
            default:
                die('Unsupported imageformat');
        }
        return $image;
    }

    protected function createthumbnail($mediaimgwidth, $mediaimgheight, $imagewidth, $imageheight)
    {
        // Maximalausmaße
        $maxthumbwidth = $mediaimgwidth;
        $maxthumbheight = $mediaimgheight;
        // Ausmaße kopieren, wir gehen zuerst davon aus, dass das Bild schon Thumbnailgröße hat
        $thumbwidth = $imagewidth;
        $thumbheight = $imageheight;
        // Breite skalieren falls nötig
        if ($thumbwidth > $maxthumbwidth) {
            $factor = $maxthumbwidth / $thumbwidth;
            $thumbwidth *= $factor;
            $thumbheight *= $factor;
        }
        // Höhe skalieren, falls nötig
        if ($thumbheight > $maxthumbheight) {
            $factor = $maxthumbheight / $thumbheight;
            $thumbwidth *= $factor;
            $thumbheight *= $factor;
        }
        // Vergrößern Breite
        if ($thumbwidth < $maxthumbwidth) {
            $factor = $maxthumbheight / $thumbheight;
            $thumbwidth *= $factor;
            $thumbheight *= $factor;
        }
        //vergrößern Höhe
        if ($thumbheight < $maxthumbheight) {
            $factor = $maxthumbheight / $thumbheight;
            $thumbwidth *= $factor;
            $thumbheight *= $factor;
        }

        // Thumbnail erstellen
        $thumbimg = imagecreatetruecolor(intval($thumbwidth), intval($thumbheight));
        imagesavealpha($thumbimg, true);
        $trans_colour = imagecolorallocatealpha($thumbimg, 0, 0, 0, 127);
        imagefill($thumbimg, 0, 0, $trans_colour);
        $thumb = ['img' => $thumbimg, 'width' => $thumbwidth, 'height' => $thumbheight];
        return $thumb;
    }

    protected function copyimgtothumbnail($thumb, $image, int $thumbwidth, int $thumbheight, int $imagewidth, int $imageheight, $picturename)
    {
        imagecopyresampled(
            $thumb,
            $image,
            0, 0, 0, 0, // Startposition des Ausschnittes
            $thumbwidth, $thumbheight,
            $imagewidth, $imageheight
        );
        // In Datei speichern
        $thumbfile = IPS_GetKernelDir() . 'media' . DIRECTORY_SEPARATOR . 'resampled_' . $picturename . '.png';  // Image-Datei
        imagepng($thumb, $thumbfile);
        imagedestroy($thumb);
        return $thumbfile;
    }

    protected function TwilightDayPicture($type)
    {
        $ImagePath = false;
        $MediaID = false;
        if ($type == 'Limited') {
            $filename = 'Astronomy_Twilight_DayLimited';
            $ImagePath = $this->GenerateClockGraphic($filename, true);
        } elseif ($type == 'Standard') {
            $filename = 'Astronomy_Twilight_DayUnlimited';
            $ImagePath = $this->GenerateClockGraphic($filename, false);
        }
        $this->SendDebug('Astronomy:', 'Twilight image path ' . $ImagePath, 0);
        if ($ImagePath) {
            $ContentDay = file_get_contents($ImagePath);
            $nameday = 'Dämmerungszeiten Tag';
            $picid = 'TwilightDayPicture';
            $MediaID = $this->CreateMediaImage('TwilightDayPicture', $nameday, $picid, $ContentDay, $ImagePath, 40, 'picturedaytwilight');
        }
        return ['mediaid_twilight_day' => $MediaID, 'twilight_day_image_path' => $ImagePath];
    }

    protected function TwilightYearPicture($type)
    {
        $ImagePath = false;
        $MediaID = false;
        if ($type == 'Limited') {
            $filename = 'Astronomy_Twilight_YearLimited';
            $ImagePath = $this->GenerateTwilightGraphic($filename, true, 4.4, 1.8);
        } elseif ($type == 'Standard') {
            $filename = 'Astronomy_Twilight_YearUnlimited';
            $ImagePath = $this->GenerateTwilightGraphic($filename, false, 4.4, 1.8);
        }
        if ($ImagePath) {
            $ContentYear = file_get_contents($ImagePath);
            $nameyear = 'Dämmerungszeiten Jahr';
            $picid = 'TwilightYearPicture';
            $MediaID = $this->CreateMediaImage('TwilightYearPicture', $nameyear, $picid, $ContentYear, $ImagePath, 41, 'pictureyeartwilight');
        }
        return ['mediaid_twilight_year' => $MediaID, 'twilight_year_image_path' => $ImagePath];
    }

    protected function GenerateClockGraphic($filename, $useLimited = false, $Width = 180)
    {
        // $location = $this->getlocation();
        // $Latitude = $location["Latitude"];
        // $Longitude = $location["Longitude"];
        $locationinfo = $this->getlocationinfo();
        $sunrise = $locationinfo['Sunrise'];
        $sunset = $locationinfo['Sunset'];
        $civiltwilightstart = $locationinfo['CivilTwilightStart'];
        $civiltwilightend = $locationinfo['CivilTwilightEnd'];
        $nautictwilightstart = $locationinfo['NauticTwilightStart'];
        $nautictwilightend = $locationinfo['NauticTwilightEnd'];
        $astronomictwilightstart = $locationinfo['AstronomicTwilightStart'];
        $astronomictwilightend = $locationinfo['AstronomicTwilightEnd'];
        $clockWidth = $Width;
        $clockHeight = $clockWidth;
        $marginLeft = 20;
        $marginRight = 20;
        $marginTop = 15;
        $marginMiddle = 45;
        $marginBottom = 30;
        $imageWidth = $clockWidth + $marginLeft + $marginRight;
        $imageHeight = $clockHeight * 2 + $marginBottom + $marginTop + $marginMiddle;

        $image = imagecreate($imageWidth, $imageHeight);

        $white = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 250, 250, 250);
        $transparent = imagecolortransparent($image, $white);
        // $black         = imagecolorallocate($image,0,0,0);
        // $red           = imagecolorallocate($image,255,0,0);
        // $green         = imagecolorallocate($image,0,255,0);
        // $blue          = imagecolorallocate($image,0,0,255);
        // $grey_back     = imagecolorallocate($image, 100, 100, 100);
        $grey_line = imagecolorallocate($image, 120, 120, 120);
        $grey_sunrise1 = imagecolorallocate($image, 200, 200, 200);
        $grey_sunrise2 = imagecolorallocate($image, 170, 170, 170);
        $grey_sunrise3 = imagecolorallocate($image, 140, 140, 140);
        $grey = imagecolorallocate($image, 100, 100, 100);
        $yellow = imagecolorallocate($image, 255, 255, 0);

        imagefilledrectangle($image, 1, 1, $imageWidth, $imageHeight, $transparent);

        $sunrise1 = $civiltwilightstart;
        $sunset1 = $civiltwilightend;
        $sunrise2 = $nautictwilightstart;
        $sunset2 = $nautictwilightend;
        $sunrise3 = $astronomictwilightstart;
        $sunset3 = $astronomictwilightend;

        /*
        if ($useLimited ) {
            LimitValues('SunriseLimits', $sunrise, $sunset);
            LimitValues('CivilLimits', $sunrise1, $sunset1);
            LimitValues('NauticLimits', $sunrise2, $sunset2);
            LimitValues('AstronomicLimits', $sunrise3, $sunset3);
        }
        */

        $sunriseMins = (270 + (date('H', $sunrise) * 60 + date('i', $sunrise)) * 360 / 720) % 360;
        $sunsetMins = (270 + (date('H', $sunset) * 60 + date('i', $sunset)) * 360 / 720) % 360;
        $sunrise1Mins = (270 + (date('H', $sunrise1) * 60 + date('i', $sunrise1)) * 360 / 720) % 360;
        $sunset1Mins = (270 + (date('H', $sunset1) * 60 + date('i', $sunset1)) * 360 / 720) % 360;
        $sunrise2Mins = (270 + (date('H', $sunrise2) * 60 + date('i', $sunrise2)) * 360 / 720) % 360;
        $sunset2Mins = (270 + (date('H', $sunset2) * 60 + date('i', $sunset2)) * 360 / 720) % 360;
        $sunrise3Mins = (270 + (date('H', $sunrise3) * 60 + date('i', $sunrise3)) * 360 / 720) % 360;
        $sunset3Mins = (270 + (date('H', $sunset3) * 60 + date('i', $sunset3)) * 360 / 720) % 360;
        $middayMins = (12 * 60);

        // 0h - 12h
        imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth + 2, $clockHeight + 2, 0, 360, $grey_line, IMG_ARC_PIE);
        imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, 0, 360, $grey, IMG_ARC_PIE);

        if ((date('H', $sunset3) * 60 + date('i', $sunset3)) < (date('H', $sunrise3) * 60 + date('i', $sunrise3)) or (date('H', $sunset3) * 60 + date('i', $sunset3)) < $middayMins) {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, $sunrise3Mins, 270, $grey_sunrise3, IMG_ARC_PIE);
        } else {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, $sunrise3Mins, 270, $grey_sunrise3, IMG_ARC_PIE);
        }
        if ((date('H', $sunset2) * 60 + date('i', $sunset2)) < (date('H', $sunrise2) * 60 + date('i', $sunrise2)) or (date('H', $sunset2) * 60 + date('i', $sunset2)) < $middayMins) {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, $sunrise2Mins, 270, $grey_sunrise2, IMG_ARC_PIE);
        } else {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, $sunrise2Mins, 270, $grey_sunrise2, IMG_ARC_PIE);
        }
        if ((date('H', $sunset1) * 60 + date('i', $sunset1)) < (date('H', $sunrise1) * 60 + date('i', $sunrise1)) or (date('H', $sunset1) * 60 + date('i', $sunset1)) < $middayMins) {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, $sunrise1Mins, 270, $grey_sunrise1, IMG_ARC_PIE);
        } else {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, $sunrise1Mins, 270, $grey_sunrise1, IMG_ARC_PIE);
        }
        imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $clockHeight / 2, $clockWidth, $clockHeight, $sunriseMins, 270, $yellow, IMG_ARC_PIE);
        //imagefilledarc($image, $marginLeft+$clockWidth/2, $marginTop+$clockHeight/2, $clockWidth, $clockHeight, $sunriseMins,  $sunriseMins+1,  $red,        IMG_ARC_PIE);

        // 12h - 24h
        imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth + 2, $clockHeight + 2, 0, 360, $grey_line, IMG_ARC_PIE);
        imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, 0, 360, $grey, IMG_ARC_PIE);
        if ((date('H', $sunset3) * 60 + date('i', $sunset3)) < (date('H', $sunrise3) * 60 + date('i', $sunrise3))) {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, $sunset3Mins, 270, $grey_sunrise3, IMG_ARC_PIE);
        } else {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, 270, $sunset3Mins, $grey_sunrise3, IMG_ARC_PIE);
        }
        if ((date('H', $sunset2) * 60 + date('i', $sunset2)) < (date('H', $sunrise2) * 60 + date('i', $sunrise2))) {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, $sunset2Mins, 270, $grey_sunrise2, IMG_ARC_PIE);
        } else {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, 270, $sunset2Mins, $grey_sunrise2, IMG_ARC_PIE);
        }
        if ((date('H', $sunset1) * 60 + date('i', $sunset1)) < (date('H', $sunrise1) * 60 + date('i', $sunrise1))) {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, $sunset1Mins, 270, $grey_sunrise1, IMG_ARC_PIE);
        } else {
            imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, 270, $sunset1Mins, $grey_sunrise1, IMG_ARC_PIE);
        }
        imagefilledarc($image, $marginLeft + $clockWidth / 2, $marginTop + $marginMiddle + $clockHeight + $clockHeight / 2, $clockWidth, $clockHeight, 270, $sunsetMins, $yellow, IMG_ARC_PIE);
        //imagefilledarc($image, $marginLeft+$clockWidth/2, $marginTop+$marginMiddle+$clockHeight+$clockHeight/2, $clockWidth, $clockHeight, $sunsetMins,  $sunsetMins+1,  $red,        IMG_ARC_PIE);

        imagestring($image, 2, $marginLeft + $clockWidth / 2 - 3, $marginTop - 14, '00', $textColor);
        imagestring($image, 2, $marginLeft + $clockWidth / 2 - 3, $marginTop + $clockHeight + 2, '06', $textColor);
        imagestring($image, 2, $marginLeft - 14, $marginTop + $clockHeight / 2 - 6, '09', $textColor);
        imagestring($image, 2, $marginLeft + $clockWidth + 4, $marginTop + $clockHeight / 2 - 6, '03', $textColor);

        imagestring($image, 2, $marginLeft + $clockWidth / 2 - 3, $marginTop + $clockHeight + $marginMiddle - 14, '24', $textColor);
        imagestring($image, 2, $marginLeft + $clockWidth / 2 - 3, $marginTop + $clockHeight * 2 + 2 + $marginMiddle, '18', $textColor);
        imagestring($image, 2, $marginLeft - 14, $marginTop + $clockHeight + $marginMiddle + $clockHeight / 2 - 6, '21', $textColor);
        imagestring($image, 2, $marginLeft + $clockWidth + 4, $marginTop + $clockHeight + $marginMiddle + $clockHeight / 2 - 6, '15', $textColor);

        imagesetthickness($image, 1);
        for ($alpha = 0; $alpha < 360; $alpha = $alpha + 30) {
            imageline($image, intval($marginLeft + $clockWidth / 2 * (1 + cos(deg2rad($alpha)))),
                intval($marginTop + $clockWidth / 2 * (1 + sin(deg2rad($alpha)))),
                intval($marginLeft + 10 + ($clockWidth - 20) / 2 * (1 + cos(deg2rad($alpha)))),
                intval($marginTop + 10 + ($clockWidth - 20) / 2 * (1 + sin(deg2rad($alpha)))), intval($grey_line));

            imageline($image, intval($marginLeft + $clockWidth / 2 * (1 + cos(deg2rad($alpha)))),
                intval($marginTop + $clockHeight + $marginMiddle + $clockWidth / 2 * (1 + sin(deg2rad($alpha)))),
                intval($marginLeft + 10 + ($clockWidth - 20) / 2 * (1 + cos(deg2rad($alpha)))),
                intval($marginTop + $clockHeight + $marginMiddle + 10 + ($clockWidth - 20) / 2 * (1 + sin(deg2rad($alpha)))), intval($grey_line));

        }

        $ImagePath = IPS_GetKernelDir() . 'media' . DIRECTORY_SEPARATOR . $filename . '.gif';
        imagegif($image, $ImagePath);
        imagedestroy($image);
        return $ImagePath;
    }

    protected function GenerateTwilightGraphic($filename, $useLimited = false, $dayDivisor = 4.4, $dayWidth = 1.8)
    {
        $location = $this->getlocation();
        $Latitude = $location['Latitude'];
        $Longitude = $location['Longitude'];
        /*
        $locationinfo = $this->getlocationinfo();
        $sunrise = $locationinfo["Sunrise"];
        $sunset = $locationinfo["Sunset"];
        $civiltwilightstart = $locationinfo["CivilTwilightStart"];
        $civiltwilightend = $locationinfo["CivilTwilightEnd"];
        $nautictwilightstart = $locationinfo["NauticTwilightStart"];
        $nautictwilightend = $locationinfo["NauticTwilightEnd"];
        $astronomictwilightstart = $locationinfo["AstronomicTwilightStart"];
        $astronomictwilightend = $locationinfo["AstronomicTwilightEnd"];
        */
        $dayHeight = 1440 / $dayDivisor;     //24h*60Min=1440Min, 1440/4=360
        $marginLeft = 20;
        $marginTop = 5;
        $marginBottom = 30;
        $marginRight = 5;
        $imageWidth = intval((365 + 30) * $dayWidth + $marginLeft + $marginRight); // 365days, 2*365=730
        $imageHeight = intval($dayHeight + $marginBottom + $marginTop);

        $image = imagecreate($imageWidth, $imageHeight);

        $white = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 250, 250, 250);
        $transparent = intval(imagecolortransparent($image, $white));
        $black = imagecolorallocate($image, 0, 0, 0);
        $red = imagecolorallocate($image, 255, 0, 0);
        //$green         = imagecolorallocate($image,0,255,0);
        $blue = imagecolorallocate($image, 0, 0, 255);
        //$grey_back     = imagecolorallocate($image, 100, 100, 100);
        $grey_line = imagecolorallocate($image, 120, 120, 120);
        $grey_sunrise1 = imagecolorallocate($image, 200, 200, 200);
        $grey_sunrise2 = imagecolorallocate($image, 170, 170, 170);
        $grey_sunrise3 = imagecolorallocate($image, 140, 140, 140);
        $grey = imagecolorallocate($image, 100, 100, 100);
        $yellow = imagecolorallocate($image, 255, 255, 0);

        imagefilledrectangle($image, 1, 1, $imageWidth, $imageHeight, $transparent);
        imagefilledrectangle($image, intval($marginLeft - 2), intval($marginTop - 2), intval($marginLeft + (365 + 30) * $dayWidth + 1), intval($marginTop + $dayHeight + 2), intval($black));

        $timestamp = mktime(12, 0, 0, 1, 1, intval(date('Y'))) - 15 * 3600 * 24;
        for ($day = 0; $day < 365 + 30; $day++) {
            $sunrise = date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 90 + 50 / 60, date('O') / 100);
            // $this->SendDebug('sunrise', $sunrise . ' (' . gettype($sunrise) . ')', 0);
            $sunset = date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 90 + 50 / 60, date('O') / 100);
            // $this->SendDebug('sunset', $sunset . ' (' . gettype($sunset) . ')', 0);
            $sunrise1 = date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 96, date('O') / 100);
            // $this->SendDebug('sunrise', $sunrise1 . ' (' . gettype($sunrise1) . ')', 0);
            $sunset1 = date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 96, date('O') / 100);
            // $this->SendDebug('sunset', $sunset1 . ' (' . gettype($sunset1) . ')', 0);
            $sunrise2 = date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 102, date('O') / 100);
            // $this->SendDebug('sunrise', $sunrise2 . ' (' . gettype($sunrise2) . ')', 0);
            $sunset2 = date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 102, date('O') / 100);
            // $this->SendDebug('sunset', $sunset2 . ' (' . gettype($sunset2) . ')', 0);
            $sunrise3 = date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 108, date('O') / 100);
            //$this->SendDebug('sunrise3', $sunrise3 . ' (' . gettype($sunrise3) . ')', 0);
            $sunset3 = date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $Latitude, $Longitude, 108, date('O') / 100);
            //$this->SendDebug('sunset3', $sunset3 . ' (' . gettype($sunset3) . ')', 0);
            $sunriseMins = (date('H', $sunrise) * 60 + date('i', $sunrise)) / $dayDivisor;
            // $this->SendDebug('sunriseMins', $sunriseMins . ' (' . gettype($sunriseMins) . ')', 0);
            $sunsetMins = (date('H', $sunset) * 60 + date('i', $sunset)) / $dayDivisor;
            // $this->SendDebug('sunsetMins', $sunsetMins . ' (' . gettype($sunsetMins) . ')', 0);
            $sunrise1Mins = (date('H', $sunrise1) * 60 + date('i', $sunrise1)) / $dayDivisor;
            // $this->SendDebug('sunrise1Mins', $sunrise1Mins . ' (' . gettype($sunrise1Mins) . ')', 0);
            $sunset1Mins = (date('H', $sunset1) * 60 + date('i', $sunset1)) / $dayDivisor;
            // $this->SendDebug('sunset1Mins', $sunset1Mins . ' (' . gettype($sunset1Mins) . ')', 0);
            $sunrise2Mins = (date('H', $sunrise2) * 60 + date('i', $sunrise2)) / $dayDivisor;
            // $this->SendDebug('sunrise2Mins', $sunrise2Mins . ' (' . gettype($sunrise2Mins) . ')', 0);
            $sunset2Mins = (date('H', $sunset2) * 60 + date('i', $sunset2)) / $dayDivisor;
            // $this->SendDebug('sunset2Mins', $sunset2Mins . ' (' . gettype($sunset2Mins) . ')', 0);
            if($sunrise3)
            {
                $sunrise3Mins = (date('H', $sunrise3) * 60 + date('i', $sunrise3)) / $dayDivisor;
            }
            // $this->SendDebug('sunrise3Mins', $sunrise3Mins . ' (' . gettype($sunrise3Mins) . ')', 0);
            if($sunset3)
            {
                $sunset3Mins = (date('H', $sunset3) * 60 + date('i', $sunset3)) / $dayDivisor;
            }
            // $this->SendDebug('sunset3Mins', $sunset3Mins . ' (' . gettype($sunset3Mins) . ')', 0);
            $middayMins = (12 * 60) / $dayDivisor;
            // $this->SendDebug('middayMins', $middayMins . ' (' . gettype($middayMins) . ')', 0);
            $dayBeg = $marginLeft + $day * $dayWidth - $dayWidth + 1;
            $dayEnd = $marginLeft + $day * $dayWidth;

            imagefilledrectangle($image, intval($dayBeg), intval($marginTop), intval($marginLeft + $day * $dayWidth), intval($marginTop + $dayHeight), intval($grey));
            if ($sunset3Mins < $sunrise3Mins or $sunset3Mins < $middayMins) {
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop), intval($dayEnd), intval($marginTop + $dayHeight - $sunrise3Mins), intval($grey_sunrise3));
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunset3Mins), intval($dayEnd), intval($marginTop + $dayHeight), intval($grey_sunrise3));
            } else {
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunrise3Mins), intval($dayEnd), intval($marginTop + $dayHeight - $sunset3Mins), intval($grey_sunrise3));
            }
            if ($sunset2Mins < $sunrise2Mins or $sunset2Mins < $middayMins) {
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop), intval($dayEnd), intval($marginTop + $dayHeight - $sunrise2Mins), intval($grey_sunrise2));
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunset2Mins), intval($dayEnd), intval($marginTop + $dayHeight), intval($grey_sunrise2));
            } else {
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunrise2Mins), intval($dayEnd), intval($marginTop + $dayHeight - $sunset2Mins), intval($grey_sunrise2));
            }
            if ($sunset1Mins < $sunrise1Mins or $sunset1Mins < $middayMins) {
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop), intval($dayEnd), intval($marginTop + $dayHeight - $sunrise1Mins), intval($grey_sunrise1));
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunset1Mins), intval($dayEnd), intval($marginTop + $dayHeight), intval($grey_sunrise1));
            } else {
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunrise1Mins), intval($dayEnd), intval($marginTop + $dayHeight - $sunset1Mins), intval($grey_sunrise1));
            }
            imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunriseMins), intval($dayEnd), intval($marginTop + $dayHeight - $sunsetMins), intval($yellow));
            imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunriseMins), intval($dayEnd), intval($marginTop + $dayHeight - $sunriseMins), intval($red));
            imagefilledrectangle($image, intval($dayBeg), intval($marginTop + $dayHeight - $sunsetMins), intval($dayEnd), intval($marginTop + $dayHeight - $sunsetMins), intval($red));

            // Line for new Month
            if (date('d', $timestamp) == 1) {
                imagefilledrectangle($image, intval($dayEnd), intval($marginTop), intval($dayEnd), intval($marginTop + $dayHeight), intval($grey_line));
                if ($day < 365) {
                    imagestring($image, 2, intval($dayBeg + 30 * $dayWidth / 2 - 8), intval($marginTop + $dayHeight + 5), date('M', $timestamp), intval($textColor));
                }
            }
            // Line for current Day
            if (date('d', $timestamp) == date('d', time()) and date('m', $timestamp) == date('m', time())) {
                imagefilledrectangle($image, intval($dayBeg), intval($marginTop), intval($dayEnd), intval($marginTop + $dayHeight), intval($blue));
                imagefilledrectangle($image, intval($dayBeg - 1), intval($marginTop), intval($dayEnd - 1), intval($marginTop + $dayHeight), intval($blue));
            }
            $timestamp = $timestamp + 60 * 60 * 24;
        }

        // Hour Lines/Text
        for ($hour = 0; $hour <= 24; $hour = $hour + 2) {
            imageline($image, intval($marginLeft), intval($marginTop + $dayHeight / 24 * $hour), intval($marginLeft + (365 + 30) * $dayWidth - 2), intval($marginTop + $dayHeight / 24 * $hour), intval($grey_line));
            imagestring($image, 2, 2, intval($marginTop + $dayHeight - 8 - ($dayHeight / 24 * $hour)), str_pad(strval($hour), 2, '0', STR_PAD_LEFT), intval($textColor));
        }

        $ImagePath = IPS_GetKernelDir() . 'media' . DIRECTORY_SEPARATOR . $filename . '.gif';
        imagegif($image, $ImagePath);
        imagedestroy($image);
        return $ImagePath;
    }

    // Profil anlegen
    protected function SetupProfile($vartype, $name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $associations)
    {
        if (!IPS_VariableProfileExists($name)) {
            switch ($vartype) {
                case VARIABLETYPE_BOOLEAN:
                    $this->RegisterProfileAssociation($name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $vartype, $associations);
                    break;
                case VARIABLETYPE_INTEGER:
                    $this->RegisterProfileAssociation($name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $vartype, $associations);
                    break;
                case VARIABLETYPE_FLOAT:
                    $this->RegisterProfileAssociation($name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $vartype, $associations);
                    break;
                case VARIABLETYPE_STRING:
                    $this->RegisterProfileAssociation($name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $vartype, $associations);
                    break;
            }
        }
        return $name;
    }

    // Variable anlegen / löschen
    protected function SetupVariable($ident, $name, $profile, $position, $vartype, $visible)
    {
        $objid = false;
        if ($visible == true) {
            switch ($vartype) {
                case VARIABLETYPE_BOOLEAN:
                    $objid = $this->RegisterVariableBoolean($ident, $name, $profile, $position);
                    break;
                case VARIABLETYPE_INTEGER:
                    $objid = $this->RegisterVariableInteger($ident, $name, $profile, $position);
                    break;
                case VARIABLETYPE_FLOAT:
                    $objid = $this->RegisterVariableFloat($ident, $name, $profile, $position);
                    break;
                case VARIABLETYPE_STRING:
                    $objid = $this->RegisterVariableString($ident, $name, $profile, $position);
                    break;
            }
        } else {
            $objid = @$this->GetIDForIdent($ident);
            if ($objid > 0) {
                $this->UnregisterVariable($ident);
            }
        }

        return $objid;
    }

    protected function SunMoonView($sunazimut, $sunaltitude, $moonazimut, $moonaltitude)
    {
        // Anzeige der Position von Sonne und Mond im WF
        // Erstellung der Grafik mit "Canvas" (HTML-Element)
        // siehe https://de.wikipedia.org/wiki/Canvas_(HTML-Element)
        // 2016-04-25 Bernd Hoffmann

        //Daten für Nullpunkt usw.------------------------------------------------------
        $npx = $this->ReadPropertyInteger('zeropointx'); //Nullpunkt x-achse
        $npy = $this->ReadPropertyInteger('zeropointy'); //Nullpunkt y-achse
        $canvaswidth = $this->ReadPropertyInteger('canvaswidth'); //canvas width
        $canvasheight = $this->ReadPropertyInteger('canvasheight'); //canvas height
        $z = 40;           //Offset y-achse

        $lWt = 2;         //Linienstärke Teilstriche
        $lWh = 2;         //Linienstärke Horizontlinie

        //Waagerechte Linie-------------------------------------------------------------
        $l1 = 360;        //Länge der Horizontlinie

        $x1 = $npx;            //Nullpunkt waagerecht
        $y1 = $npy + $z;        //Nullpunkt senkrecht
        $x2 = $x1 + $l1;        //Nullpunkt + Länge = waagerechte Linie
        $y2 = $npy + $z;

        //Teilstriche-------------------------------------------------------------------
        $l2 = 10;         //Länge der Teilstriche
        //N 0°
        $x3 = $npx;           //Nullpunkt waagerecht
        $y3 = $y1 - $l2 / 2;    //Nullpunkt senkrecht
        $x4 = $x3;
        $y4 = $y3 + $l2;        //Nullpunkt + Länge = senkrechte Linie
        //O
        $x5 = $npx + 90;
        $y5 = $y1 - $l2 / 2;
        $x6 = $x5;
        $y6 = $y5 + $l2;
        //S
        $x7 = $npx + 180;
        $y7 = $y1 - $l2 / 2;
        $x8 = $x7;
        $y8 = $y7 + $l2;
        //W
        $x9 = $npx + 270;
        $y9 = $y1 - $l2 / 2;
        $x10 = $x9;
        $y10 = $y9 + $l2;
        //N 360°
        $x11 = $npx + 360;
        $y11 = $y1 - $l2 / 2;
        $x12 = $x11;
        $y12 = $y11 + $l2;

        //Daten von Sonne und Mond holen------------------------------------------------
        $xsun = round($npx + $sunazimut);
        $ysun = round($npy + $z - $sunaltitude);

        $xmoon = round($npx + $moonazimut);
        $ymoon = round($npy + $z - $moonaltitude);

        //Erstellung der Html Datei-----------------------------------------------------
        $html =
            '<html lang="de">

		<head>
		<script type="text/javascript">

		function draw(){
		var canvas = document.getElementById("canvas1");
		if(canvas.getContext){
			var ctx = canvas.getContext("2d");

			ctx.lineWidth = ' . $lWt . '; //Teilstriche
			ctx.strokeStyle = "rgb(51,102,255)";
			ctx.beginPath();
			ctx.moveTo(' . $x3 . ',' . $y3 . ');
			ctx.lineTo(' . $x4 . ',' . $y4 . ');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo(' . $x5 . ',' . $y5 . ');
			ctx.lineTo(' . $x6 . ',' . $y6 . ');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo(' . $x7 . ',' . $y7 . ');
			ctx.lineTo(' . $x8 . ',' . $y8 . ');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo(' . $x9 . ',' . $y9 . ');
			ctx.lineTo(' . $x10 . ',' . $y10 . ');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo(' . $x11 . ',' . $y11 . ');
			ctx.lineTo(' . $x12 . ',' . $y12 . ');
			ctx.stroke();
			
			ctx.lineWidth = 2; //Text
			ctx.fillStyle = "rgb(139,115,85)";
			ctx.beginPath();
			ctx.font = "18px calibri";
		   ctx.fillText("N", ' . $x4 . '-6,' . $y4 . '+15);
		   ctx.fillText("O", ' . $x6 . '-6,' . $y6 . '+15);
		   ctx.fillText("S", ' . $x8 . '-5,' . $y8 . '+15);
		   ctx.fillText("W", ' . $x10 . '-8,' . $y10 . '+15);
		   ctx.fillText("N", ' . $x12 . '-6,' . $y12 . '+15);
		   ctx.font = "16px calibri";
		   ctx.fillText("Horizont", ' . $x1 . '+368,' . $y1 . '+5);
		   
			ctx.lineWidth = ' . $lWh . '; //Horizontlinie
			ctx.strokeStyle = "rgb(51,102,255)";
			ctx.beginPath();
			ctx.moveTo(' . $x1 . ',' . $y1 . ');
			ctx.lineTo(' . $x2 . ',' . $y2 . ');
			ctx.stroke();
			
			ctx.lineWidth = 1; //Mond
			ctx.fillStyle = "rgb(255,255,255)";
			ctx.beginPath();
		   ctx.arc(' . $xmoon . ',' . $ymoon . ',10,0,Math.PI*2,true);
		   ctx.fill();
		   
		   ctx.lineWidth = 1; //Sonne
			ctx.fillStyle = "rgb(255,255,102)";
			ctx.beginPath();
		   ctx.arc(' . $xsun . ',' . $ysun . ',18,0,Math.PI*2,true);
		   ctx.fill();
			}
		}

		</script><title>sun and moon</title>
		</head>

		<body onload="draw()">
		<canvas id="canvas1" width="' . $canvaswidth . '" height="' . $canvasheight . '" > //style="border:1px solid yellow;"
		</canvas>
		</body>

		</html>';

        //Erstellen des Dateinamens, abspeichern und Aufruf in <iframe>-----------------
        $frameheight = $this->ReadPropertyInteger('frameheight');
        $frameheighttypevalue = $this->ReadPropertyInteger('frameheighttype');
        $frameheighttype = $this->GetFrameType($frameheighttypevalue);
        $framewidth = $this->ReadPropertyInteger('framewidth');
        $framewidthtypevalue = $this->ReadPropertyInteger('framewidthtype');
        $framewidthtype = $this->GetFrameType($framewidthtypevalue);
        $filename = 'sunmoonline.php';
        $fullFilename = IPS_GetKernelDir() . 'webfront' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . $filename;
        $handle = fopen($fullFilename, 'w');
        fwrite($handle, $html);
        fclose($handle);
        $HTMLData = '<iframe src="user' . DIRECTORY_SEPARATOR . 'sunmoonline.php" border="0" frameborder="0" scrolling="no" style= "width:' . $framewidth . $framewidthtype . '; height:' . $frameheight . $frameheighttype . ';"/></iframe>';
        if ($this->ReadPropertyBoolean('sunmoonview') == true) {
            $this->SetValue('sunmoonview', $HTMLData);
        }
    }

    protected function GetFrameType($value)
    {
        $type = 'px';
        if ($value == 1) {
            $type = 'px';
        } elseif ($value == 2) {
            $type = '%';
        }
        return $type;
    }

    protected function GetTimeformat()
    {
        $formatselection = $this->ReadPropertyInteger('timeformat');
        $timeformat = 'H:i';
        if ($formatselection == 1) {
            $timeformat = 'H:i';
        }
        if ($formatselection == 2) {
            $timeformat = 'H:i:s';
        }
        if ($formatselection == 3) {
            $timeformat = 'h:i';
        }
        if ($formatselection == 4) {
            $timeformat = 'h:i:s';
        }
        if ($formatselection == 5) {
            $timeformat = 'g:i';
        }
        if ($formatselection == 6) {
            $timeformat = 'g:i:s';
        }
        if ($formatselection == 7) {
            $timeformat = 'G:i';
        }
        if ($formatselection == 8) {
            $timeformat = 'G:i:s';
        }
        return $timeformat;
    }

    public function SetAstronomyValues()
    {
        $location = $this->getlocation();
        $Latitude = $location['Latitude'];
        $Longitude = $location['Longitude'];
        $locationinfo = $this->getlocationinfo();
        $isday = $locationinfo['IsDay'];
        $sunrise = $locationinfo['Sunrise'];
        $sunset = $locationinfo['Sunset'];
        $civiltwilightstart = $locationinfo['CivilTwilightStart'];
        $civiltwilightend = $locationinfo['CivilTwilightEnd'];
        $nautictwilightstart = $locationinfo['NauticTwilightStart'];
        $nautictwilightend = $locationinfo['NauticTwilightEnd'];
        $astronomictwilightstart = $locationinfo['AstronomicTwilightStart'];
        $astronomictwilightend = $locationinfo['AstronomicTwilightEnd'];
        $sunsetoffset = $this->GetOffset('Sunset');
        $sunriseoffset = $this->GetOffset('Sunrise');
        $risetype = $this->ReadPropertyInteger('risetype');
        $sunrisetimestamp = 0;
        switch ($risetype) {
            case 1:
                $sunrisetimestamp = $sunrise + $sunriseoffset; // "Sunrise"
                break;
            case 2:
                $sunrisetimestamp = $civiltwilightstart + $sunriseoffset; // "CivilTwilightStart"
                break;
            case 3:
                $sunrisetimestamp = $nautictwilightstart + $sunriseoffset; // "NauticTwilightStart"
                break;
            case 4:
                $sunrisetimestamp = $astronomictwilightstart + $sunriseoffset; // "AstronomicTwilightStart"
                break;
            case 5:
                $moonrisedata = $this->Mondaufgang();
                $moonrisetimestring = $moonrisedata['moonrisetime'];
                $moonrisetime = strtotime($moonrisetimestring);
                $sunrisetimestamp = $moonrisetime + $sunriseoffset; // "Moonrise"
                break;
        }

        $settype = $this->ReadPropertyInteger('settype');
        $sunsettimestamp = 0;
        switch ($settype) {
            case 1:
                $sunsettimestamp = $sunset + $sunsetoffset; // "Sunset"
                break;
            case 2:
                $sunsettimestamp = $civiltwilightend + $sunsetoffset; // "CivilTwilightEnd"
                break;
            case 3:
                $sunsettimestamp = $nautictwilightend + $sunsetoffset; // "NauticTwilightEnd"
                break;
            case 4:
                $sunsettimestamp = $astronomictwilightend + $sunsetoffset; // "AstronomicTwilightEnd"
                break;
            case 5:
                $moonsetdata = $this->Monduntergang();
                $moonsettimestring = $moonsetdata['moonsettime'];
                $moonsettime = strtotime($moonsettimestring);
                $sunsettimestamp = $moonsettime + $sunsetoffset; // "Moonset"
                break;
        }
        $sunsetobjid = @$this->GetIDForIdent('sunset');
        $sunriseobjid = @$this->GetIDForIdent('sunrise');
        if ($sunsetobjid > 0) {
            $this->SetValue('sunset', $sunsettimestamp);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $timeformat = $this->GetTimeformat();
                $sunsettime = date($timeformat, $sunsettimestamp);
                $this->SetValue('sunsettime', $sunsettime);
            }

        }
        if ($sunriseobjid > 0) {
            $this->SetValue('sunrise', $sunrisetimestamp);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $timeformat = $this->GetTimeformat();
                $sunrisetime = date($timeformat, $sunrisetimestamp);
                $this->SetValue('sunrisetime', $sunrisetime);
            }
        }
        $P = $Latitude;
        $L = $Longitude;

        $day = intval(date('d'));
        $month = intval(date('m'));
        $year = intval(date('Y'));
        $Hour = intval(date('H'));
        $Minute = intval(date('i'));
        $Second = intval(date('s'));
        $summer = intval(date('I'));

        if ($summer == 0) {   //Summertime
            $DS = 0;
        }         //$DS = Daylight Saving
        else {
            $DS = 1;
        }
        $ZC = $this->ReadPropertyFloat('UTC'); // Zone Correction to Greenwich: 1 = UTC+1

        // $timestamp = time();
        // $mondphase = $this->moon_phase(date('Y', $timestamp), date('n', $timestamp), date('j', $timestamp));
        $moonrise = $this->Mondaufgang();
        // $moonrisedate = $moonrise['moonrisedate'];
        // $moonrisetime = $moonrise['moonrisetime'];
        $moonset = $this->Monduntergang();
        $moonsetdate = $moonset['moonsetdate'];
        $moonsettime = $moonset['moonsettime'];
        $mondphase = $this->MoonphasePercent();
        $picture = $this->GetMoonPicture($mondphase);

        $currentnewmoonstring = $this->Moon_CurrentNewmoon();
        $currentfirstquarterstring = $this->Moon_CurrentFirstQuarter();
        $currentfullmoonstring = $this->Moon_CurrentFullmoon();
        $currentlastquarterstring = $this->Moon_CurrentLastQuarter();

        $newmoonstring = $this->Moon_Newmoon();
        $firstquarterstring = $this->Moon_FirstQuarter();
        $fullmoonstring = $this->Moon_Fullmoon();
        $lastquarterstring = $this->Moon_LastQuarter();

        if ($this->ReadPropertyBoolean('newmoon') == true) {
            $this->SetValue('newmoon', $newmoonstring['newmoon']);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $this->SetValue('newmoondate', $newmoonstring['newmoondate']);
                $this->SetValue('newmoontime', $newmoonstring['newmoontime']);
            }
        }
        if ($this->ReadPropertyBoolean('currentnewmoon') == true) {
            $this->SetValue('currentnewmoon', $currentnewmoonstring['newmoon']);
        }
        if ($this->ReadPropertyBoolean('firstquarter') == true) {
            $this->SetValue('firstquarter', $firstquarterstring['firstquarter']);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $this->SetValue('firstquarterdate', $firstquarterstring['firstquarterdate']);
                $this->SetValue('firstquartertime', $firstquarterstring['firstquartertime']);
            }
        }
        if ($this->ReadPropertyBoolean('currentfirstquarter') == true) {
            $this->SetValue('currentfirstquarter', $currentfirstquarterstring['firstquarter']);
        }
        if ($this->ReadPropertyBoolean('fullmoon') == true) {
            $this->SetValue('fullmoon', $fullmoonstring['fullmoon']);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $this->SetValue('fullmoondate', $fullmoonstring['fullmoondate']);
                $this->SetValue('fullmoontime', $fullmoonstring['fullmoontime']);
            }
        }
        if ($this->ReadPropertyBoolean('currentfullmoon') == true) {
            $this->SetValue('currentfullmoon', $currentfullmoonstring['fullmoon']);
        }
        if ($this->ReadPropertyBoolean('lastquarter') == true) {
            $this->SetValue('lastquarter', $lastquarterstring['lastquarter']);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $this->SetValue('lastquarterdate', $lastquarterstring['lastquarterdate']);
                $this->SetValue('lastquartertime', $lastquarterstring['lastquartertime']);
            }
        }
        if ($this->ReadPropertyBoolean('currentlastquarter') == true) {
            $this->SetValue('currentlastquarter', $currentlastquarterstring['lastquarter']);
        }

        $moonphasearr = $this->MoonphaseText();
        $moonphasetext = $moonphasearr['moonphasetext'];
        $moonphasepercent = $moonphasearr['moonphasepercent'];
        $this->UpdateMedia($picture['picid']);

        $limited = $this->ReadPropertyBoolean('picturetwilightlimited');
        if ($limited) {
            $type = 'Limited';
        } else {
            $type = 'Standard';
        }
        $twilightyearpic = $this->TwilightYearPicture($type);
        $mediaid_twilight_year = $twilightyearpic['mediaid_twilight_year'];
        $twilight_year_image_path = $twilightyearpic['twilight_year_image_path'];
        $twilightdaypic = $this->TwilightDayPicture($type);
        $mediaid_twilight_day = $twilightdaypic['mediaid_twilight_day'];
        $twilight_day_image_path = $twilightdaypic['twilight_day_image_path'];

        $HMSDec = $this->HMSDH(floatval($Hour), $Minute, $Second); //Local Time HMS in Decimal Hours
        // $UTDec = $this->LctUT($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year)["UTDec"];
        $GD = floatval($this->LctUT(intval($Hour), $Minute, $Second, $DS, $ZC, $day, $month, $year)['GD']);
        $GM = intval($this->LctUT(intval($Hour), $Minute, $Second, $DS, $ZC, $day, $month, $year)['GM']);
        $GY = intval($this->LctUT(intval($Hour), $Minute, $Second, $DS, $ZC, $day, $month, $year)['GY']);
        $JD = $this->CDJD($GD, $GM, $GY);  //UT Julian Date
        if ($this->ReadPropertyBoolean('juliandate') == true) {
            $this->SetValue('juliandate', $JD);
        }

        $LCH = $this->DHHour($HMSDec);  //LCT Hour --> Local Time
        $LCM = $this->DHMin($HMSDec);   //LCT Minute
        $LCS = $this->DHSec($HMSDec);   //LCT Second
        //Universal Time
        // $UH = $this->DHHour($UTDec);      //UT Hour
        // $UM = $this->DHMin($UTDec);    //UT Minute
        // $US = $this->DHSec($UTDec);    //UT Second
        // $UT_value = $UH.":".$UM.":".$US;
        // $UDate_value = $GD.":".$GM.":".$GY;

        //Calculation Sun---------------------------------------------------------------

        //Sun's ecliptic longitude in decimal degrees
        $Sunlong = $this->SunLong($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);
        if($this->ReadPropertyBoolean('sunstarsign') == true)
        {
            $this->SetValue('sunstarsign', floor($Sunlong/30)+1);
        }
        $this->SendDebug('Astronomy:', "Sun's ecliptic longitude " . $Sunlong, 0);
        $SunlongDeg = $this->DDDeg($Sunlong);
        $SunlongMin = $this->DDMin($Sunlong);
        $SunlongSec = $this->DDSec($Sunlong);

        //Sun's RA
        $SunRAh = $this->DDDH($this->ECRA($SunlongDeg, $SunlongMin, $SunlongSec, 0, 0, 0, $GD, $GM, $GY));    //returns RA in hours
        $SunRAhour = $this->DHHour($SunRAh);
        $SunRAm = $this->DHmin($SunRAh);
        $SunRAs = $this->DHSec($SunRAh);
        // $SunRAhms = $SunRAhour.":".$SunRAm.":".$SunRAs;

        $season = '';
        if (($SunRAh >= 0) and ($SunRAh < 6)) {
            $season = 1;
        }        //Frühling
        if (($SunRAh >= 6) and ($SunRAh < 12)) {
            $season = 2;
        }        //Sommer
        if (($SunRAh >= 12) and ($SunRAh < 18)) {
            $season = 3;
        }        //Herbst
        if (($SunRAh >= 18) and ($SunRAh < 24)) {
            $season = 4;
        }        //Winter
        if ($this->ReadPropertyBoolean('season') == true) {
            $this->SetValue('season', $season);
        }

        //Sun's Dec
        $SunDec = $this->ECDec($SunlongDeg, $SunlongMin, $SunlongSec, 0, 0, 0, $GD, $GM, $GY);            //returns declination in decimal degrees
        $SunDecd = $this->DDDeg($SunDec);
        $SunDecm = $this->DDmin($SunDec);
        $SunDecs = $this->DDSec($SunDec);
        $SunDecdms = $SunDecd . ':' . $SunDecm . ':' . $SunDecs;
        $this->SendDebug('Astronomy:', 'Sun decimal degrees ' . $SunDecdms, 0);

        //RH Right Ascension in HMS, LH Local Civil Time in HMS, DS Daylight saving, ZC Zonecorrection,
        //LD Local Calender Date in DMY, L geographical Longitude in Degrees
        $SunH = $this->RAHA($SunRAhour, $SunRAm, $SunRAs, $Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year, $L); //Hour Angle H
        $SunHh = $this->DHHour($SunH);
        $SunHm = $this->DHMin($SunH);
        $SunHs = $this->DHSec($SunH);

        //Equatorial to Horizon coordinate conversion (Az)
        //HH HourAngle in HMS, DD Declination in DMS, P Latitude in decimal Degrees
        $sunazimut = $this->EQAz($SunHh, $SunHm, $SunHs, $SunDecd, $SunDecm, $SunDecs, $P);
        $sunaltitude = $this->EQAlt($SunHh, $SunHm, $SunHs, $SunDecd, $SunDecm, $SunDecs, $P);
        $SunDazimut = $this->direction($sunazimut);

        // $SunDist = $this->SunDist($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year);
        $SunTA = $this->Radians($this->SunTrueAnomaly($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year));
        $SunEcc = $this->SunEcc($GD, $GM, $GY);
        $fSun = (1 + $SunEcc * cos($SunTA)) / (1 - $SunEcc * $SunEcc);
        $rSun = round(149598500 / $fSun, -2);

        if ($this->ReadPropertyBoolean('sunazimut') == true) // float
        {
            $this->SetValue('sunazimut', $sunazimut);
        }
        if ($this->ReadPropertyBoolean('sundirection') == true) // float
        {
            $this->SetValue('sundirection', $SunDazimut);
        }
        if ($this->ReadPropertyBoolean('sunaltitude') == true) // float
        {
            $this->SetValue('sunaltitude', $sunaltitude);
        }
        if ($this->ReadPropertyBoolean('sundistance') == true) // float
        {
            $this->SetValue('sundistance', $rSun);
        }

        //Calculation Moon--------------------------------------------------------------

        $MoonLong = $this->MoonLong($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year); //Moon ecliptic longitude (degrees)
        $this->SendDebug('Astronomy:', 'Moon ecliptic longitude ' . $MoonLong, 0);
        $MoonLat = $this->MoonLat($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year); //Moon elciptic latitude (degrees)
        $this->SendDebug('Astronomy:', 'Moon elciptic latitude ' . $MoonLat, 0);
        if($this->ReadPropertyBoolean('moonstarsign') == true)
        {
            $this->SetValue('moonstarsign', floor($MoonLong/30)+1);
        }
        $Nutation = $this->NutatLong($GD, $GM, $GY); //nutation in longitude (degrees)
        $this->SendDebug('Astronomy:', 'nutation in longitude ' . $Nutation, 0);
        $Moonlongcorr = $MoonLong + $Nutation; //corrected longitude (degrees)
        $MoonHP = $this->MoonHP($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);    //Moon's horizontal parallax (degrees)
        $this->SendDebug('Astronomy:', "Moon's horizontal parallax " . $MoonHP, 0);
        $MoonDist = $this->MoonDist($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);   //Moon Distance to Earth
        $this->SendDebug('Astronomy:', 'Moon Distance to Earth ' . $MoonDist, 0);
        $Moonphase = $this->MoonPhase($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year); //Moonphase in %
        $this->SendDebug('Astronomy:', 'Moonphase ' . $Moonphase . '%', 0);
        $MoonBrightLimbAngle = $this->MoonPABL($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);   //Moon Bright Limb Angle (degrees)
        $this->SendDebug('Astronomy:', 'Moon Bright Limb Angle ' . $MoonBrightLimbAngle, 0);

        if ($MoonBrightLimbAngle < 0) {
            $MoonBrightLimbAngle = $MoonBrightLimbAngle + 360;
        }

        $EcLonDeg = $this->DDDeg($Moonlongcorr); // Ecliptic Longitude Moon - geographische Länge (Längengrad)
        $EcLonMin = $this->DDMin($Moonlongcorr);
        $EcLonSec = $this->DDSec($Moonlongcorr);

        $EcLatDeg = $this->DDDeg($MoonLat); // Ecliptic Latitude Moon - geographische Breite (Breitengrad)
        $EcLatMin = $this->DDMin($MoonLat);
        $EcLatSec = $this->DDSec($MoonLat);

        //Ecliptic to Equatorial Coordinate Conversion (Decimal Degrees)
        //ELD Ecliptic Longitude in DMS, BD Ecliptic Latitude in DMS, GD Greenwich Calendar Date in DMY
        $MoonRA = $this->DDDH($this->ECRA($EcLonDeg, $EcLonMin, $EcLonSec, $EcLatDeg, $EcLatMin, $EcLatSec, $GD, $GM, $GY));
        $MoonDec = $this->ECDec($EcLonDeg, $EcLonMin, $EcLonSec, $EcLatDeg, $EcLatMin, $EcLatSec, $GD, $GM, $GY);
        $MoonRAh = $this->DHHour($MoonRA);    //Right Ascension Hours
        $MoonRAm = $this->DHMin($MoonRA);
        $MoonRAs = $this->DHSec($MoonRA);
        $this->SendDebug('Astronomy:', 'Moon Right Ascension Hours ' . $MoonRAh . ':' . $MoonRAm . ':' . $MoonRAs, 0);
        $MoonDECd = $this->DDDeg($MoonDec);  //Declination Degrees
        $MoonDECm = $this->DDMin($MoonDec);
        $MoonDECs = $this->DDSec($MoonDec);
        $this->SendDebug('Astronomy:', 'Moon Declination Degrees ' . $MoonDECd . ':' . $MoonDECm . ':' . $MoonDECs, 0);

        //RH Right Ascension in HMS, LH Local Civil Time in HMS, DS Daylight saving, ZC Zonecorrection,
        //LD Local Calender Date in DMY, L geographical Longitude in Degrees
        $MoonH = $this->RAHA($MoonRAh, $MoonRAm, $MoonRAs, $Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year, $L); //Hour Angle H
        $MoonHh = $this->DHHour($MoonH);
        $MoonHm = $this->DHMin($MoonH);
        $MoonHs = $this->DHSec($MoonH);

        $moonazimut = $this->EQAz($MoonHh, $MoonHm, $MoonHs, $MoonDECd, $MoonDECm, $MoonDECs, $P);
        $moonaltitude = $this->EQAlt($MoonHh, $MoonHm, $MoonHs, $MoonDECd, $MoonDECm, $MoonDECs, $P);

        $dazimut = $this->direction($moonazimut);
        $this->SunMoonView($sunazimut, $sunaltitude, $moonazimut, $moonaltitude);

        if ($this->ReadPropertyBoolean('moonazimut') == true) // float
        {
            $this->SetValue('moonazimut', $moonazimut);
        }
        if ($this->ReadPropertyBoolean('moonaltitude') == true) // float
        {
            $this->SetValue('moonaltitude', $moonaltitude);
        }
        if ($this->ReadPropertyBoolean('moondirection') == true) // float
        {
            $this->SetValue('moondirection', $dazimut);
        }
        if ($this->ReadPropertyBoolean('moondistance') == true) // float
        {
            $this->SetValue('moondistance', $MoonDist);
        }
        if ($this->ReadPropertyBoolean('moonvisibility') == true) // float
        {
            $this->SetValue('moonvisibility', $Moonphase);
        }
        if ($this->ReadPropertyBoolean('moonbrightlimbangle') == true) // float
        {
            $this->SetValue('moonbrightlimbangle', $MoonBrightLimbAngle);
        }
        $moonrisedate = $moonrise['moonrisedate'];
        $moonrisetime = $moonrise['moonrisetime'];

        $astronomyinfo = ['IsDay' => $isday, 'Sunrise' => $sunrise, 'Sunset' => $sunset, 'moonsetdate' => $moonsetdate, 'moonsettime' => $moonsettime, 'moonrisedate' => $moonrisedate, 'moonrisetime' => $moonrisetime, 'CivilTwilightStart' => $civiltwilightstart, 'CivilTwilightEnd' => $civiltwilightend, 'NauticTwilightStart' => $nautictwilightstart, 'NauticTwilightEnd' => $nautictwilightend, 'AstronomicTwilightStart' => $astronomictwilightstart, 'AstronomicTwilightEnd' => $astronomictwilightend,
            'latitude'            => $Latitude, 'longitude' => $Longitude, 'juliandate' => $JD, 'season' => $season, 'sunazimut' => $sunazimut, 'sundirection' => $SunDazimut, 'sunaltitude' => $sunaltitude, 'sundistance' => $rSun, 'moonazimut' => $moonazimut, 'moonaltitude' => $moonaltitude, 'moondirection' => $dazimut, 'moondistance' => $MoonDist, 'moonvisibility' => $Moonphase, 'moonbrightlimbangle' => $MoonBrightLimbAngle,
            'newmoon'             => $currentnewmoonstring, 'firstquarter' => $currentfirstquarterstring, 'fullmoon' => $currentfullmoonstring, 'lastquarter' => $currentlastquarterstring, 'moonphasetext' => $moonphasetext, 'moonphasepercent' => $moonphasepercent, 'picid' => $picture['picid'], 'mediaid_twilight_year' => $mediaid_twilight_year, 'twilight_year_image_path' => $twilight_year_image_path, 'mediaid_twilight_day' => $mediaid_twilight_day, 'twilight_day_image_path' => $twilight_day_image_path];

        return $astronomyinfo;
    }

    protected function getlocation()
    {
        //Location auslesen
        $LocationID = IPS_GetInstanceListByModuleID('{45E97A63-F870-408A-B259-2933F7EABF74}')[0];
        $ipsversion = $this->GetIPSVersion();
        if ($ipsversion >= 5) {
            $Location = json_decode(IPS_GetProperty($LocationID, 'Location'));
            $Latitude = $Location->latitude;
            $Longitude = $Location->longitude;
        } else {
            $Latitude = IPS_GetProperty($LocationID, 'Latitude');
            $Longitude = IPS_GetProperty($LocationID, 'Longitude');
        }
        $location = ['Latitude' => $Latitude, 'Longitude' => $Longitude];
        return $location;
    }

    protected function getlocationinfo()
    {
        $LocationID = IPS_GetInstanceListByModuleID('{45E97A63-F870-408A-B259-2933F7EABF74}')[0];
        $isday = GetValue(IPS_GetObjectIDByIdent('IsDay', $LocationID));
        $sunrise = GetValue(IPS_GetObjectIDByIdent('Sunrise', $LocationID));
        $sunset = GetValue(IPS_GetObjectIDByIdent('Sunset', $LocationID));
        $civiltwilightstart = GetValue(IPS_GetObjectIDByIdent('CivilTwilightStart', $LocationID));
        $civiltwilightend = GetValue(IPS_GetObjectIDByIdent('CivilTwilightEnd', $LocationID));
        $nautictwilightstart = GetValue(IPS_GetObjectIDByIdent('NauticTwilightStart', $LocationID));
        $nautictwilightend = GetValue(IPS_GetObjectIDByIdent('NauticTwilightEnd', $LocationID));
        $astronomictwilightstart = GetValue(IPS_GetObjectIDByIdent('AstronomicTwilightStart', $LocationID));
        $astronomictwilightend = GetValue(IPS_GetObjectIDByIdent('AstronomicTwilightEnd', $LocationID));
        $locationinfo = ['IsDay' => $isday, 'Sunrise' => $sunrise, 'Sunset' => $sunset, 'CivilTwilightStart' => $civiltwilightstart, 'CivilTwilightEnd' => $civiltwilightend, 'NauticTwilightStart' => $nautictwilightstart, 'NauticTwilightEnd' => $nautictwilightend, 'AstronomicTwilightStart' => $astronomictwilightstart, 'AstronomicTwilightEnd' => $astronomictwilightend];
        return $locationinfo;
    }

    protected function GetOffset($type)
    {
        $offset = 0;
        if ($type == 'Sunrise') {
            $offset = $this->ReadPropertyInteger('sunriseoffset');
        } elseif ($type == 'Sunset') {
            $offset = $this->ReadPropertyInteger('sunsetoffset');
        }
        $offset = $offset * 60;
        return $offset;
    }

    //FormelScript zur Berechnung von Astronomischen Ereignissen
    //nach dem Buch "Practical Astronomy with your Calculator or Spreadsheet"
    //von Peter Duffet-Smith und Jonathan Zwart

    protected function roundvariantfix($value)
    {
        $roundvalue = 0;
        if ($value >= 0)
            $roundvalue = floor($value);
        elseif ($value < 0)
            $roundvalue = ceil($value);
        return $roundvalue;
    }

    protected function roundvariantint($value)
    {
        $roundvalue = floor($value);
        return $roundvalue;
    }

    protected function dayName($time)
    {
        $day = date('D', ($time));
        $daygerman = 'So';
        if ($day == 'Mon') {
            $daygerman = 'Mo';
        } elseif ($day == 'Tue') {
            $daygerman = 'Di';
        } elseif ($day == 'Wed') {
            $daygerman = 'Mi';
        } elseif ($day == 'Thu') {
            $daygerman = 'Do';
        } elseif ($day == 'Fri') {
            $daygerman = 'Fr';
        } elseif ($day == 'Sat') {
            $daygerman = 'Sa';
        } elseif ($day == 'Sun') {
            $daygerman = 'So';
        }
        return $daygerman;
    }

    protected function direction($degree)
    {
        $direction = 0;
        if (($degree >= 0) and ($degree < 22.5)) {
            $direction = 0;
        }
        if (($degree >= 22.5) and ($degree < 45)) {
            $direction = 1;
        }
        if (($degree >= 45) and ($degree < 67.5)) {
            $direction = 2;
        }
        if (($degree >= 67.5) and ($degree < 90)) {
            $direction = 3;
        }
        if (($degree >= 90) and ($degree < 112.5)) {
            $direction = 4;
        }
        if (($degree >= 112.5) and ($degree < 135)) {
            $direction = 5;
        }
        if (($degree >= 135) and ($degree < 157.5)) {
            $direction = 6;
        }
        if (($degree >= 157.5) and ($degree < 180)) {
            $direction = 7;
        }
        if (($degree >= 180) and ($degree < 202.5)) {
            $direction = 8;
        }
        if (($degree >= 202.5) and ($degree < 225)) {
            $direction = 9;
        }
        if (($degree >= 225) and ($degree < 247.5)) {
            $direction = 10;
        }
        if (($degree >= 247.5) and ($degree < 270)) {
            $direction = 11;
        }
        if (($degree >= 270) and ($degree < 292.5)) {
            $direction = 12;
        }
        if (($degree >= 292.5) and ($degree < 315)) {
            $direction = 13;
        }
        if (($degree >= 315) and ($degree < 337.5)) {
            $direction = 14;
        }
        if (($degree >= 337.5) and ($degree <= 360)) {
            $direction = 15;
        }
        return $direction;
    }

    // Greenwich calendar date to Julian date conversion
    public function CDJD(float $day, int $month, int $year)
    {
        if ($month < 3) {
            $Y = $year - 1;
            $M = $month + 12;
        } else {
            $Y = $year;
            $M = $month;
        }

        if ($year > 1582) {
            $A = $this->roundvariantfix($Y / 100);
            $B = 2 - $A + $this->roundvariantfix($A / 4);
        } else {
            if (($year == 1582) and ($month > 10)) {
                $A = $this->roundvariantfix($Y / 100);
                $B = 2 - $A + $this->roundvariantfix($A / 4);
            } else {
                if (($year == 1582) and ($month == 10) and ($day >= 15)) {
                    $A = $this->roundvariantfix($Y / 100);
                    $B = 2 - $A + $this->roundvariantfix($A / 4);
                } else {
                    $B = 0;
                }
            }
        }

        if ($Y < 0) {
            $C = $this->roundvariantfix((365.25 * $Y) - 0.75);
        } else {
            $C = $this->roundvariantfix(365.25 * $Y);
        }

        $D = $this->roundvariantfix(30.6001 * ($M + 1));
        $JD = $B + $C + $D + $day + 1720994.5;
        return $JD;
    }

    // Julian date to Greenwich calendar date conversion
    public function JDCD(float $JD)
    {
        $day = $this->JDCDay($JD);
        $month = $this->JDCMonth($JD);
        $year = $this->JDCYear($JD);
        $dateCD = ['day' => $day, 'month' => $month, 'year' => $year];
        return $dateCD;
    }

    protected function JDCDay(float $JD)
    {
        $I = $this->roundvariantfix($JD + 0.5);
        $F = $JD + 0.5 - $I;
        $A = $this->roundvariantfix(($I - 1867216.25) / 36524.25);

        if ($I > 2299160) {
            $B = $I + 1 + $A - $this->roundvariantfix($A / 4);
        } else {
            $B = $I;
        }

        $C = $B + 1524;
        $D = $this->roundvariantfix(($C - 122.1) / 365.25);
        $E = $this->roundvariantfix(365.25 * $D);
        $G = $this->roundvariantfix(($C - $E) / 30.6001);
        $JDCDay = $C - $E + $F - $this->roundvariantfix(30.6001 * $G);
        return $JDCDay;
    }

    protected function JDCMonth(float $JD)
    {
        $I = $this->roundvariantfix($JD + 0.5);
        // $F = $JD + 0.5 - $I;
        $A = $this->roundvariantfix(($I - 1867216.25) / 36524.25);

        if ($I > 2299160) {
            $B = $I + 1 + $A - $this->roundvariantfix($A / 4);
        } else {
            $B = $I;
        }

        $C = $B + 1524;
        $D = $this->roundvariantfix(($C - 122.1) / 365.25);
        $E = $this->roundvariantfix(365.25 * $D);
        $G = $this->roundvariantfix(($C - $E) / 30.6001);

        if ($G < 13.5) {
            $JDCMonth = $G - 1;
        } else {
            $JDCMonth = $G - 13;
        }
        return $JDCMonth;
    }

    protected function JDCYear($JD)
    {
        $I = $this->roundvariantfix($JD + 0.5);
        // $F = $JD + 0.5 - $I;
        $A = $this->roundvariantfix(($I - 1867216.25) / 36524.25);

        if ($I > 2299160) {
            $B = $I + 1 + $A - $this->roundvariantfix($A / 4);
        } else {
            $B = $I;
        }

        $C = $B + 1524;
        $D = $this->roundvariantfix(($C - 122.1) / 365.25);
        $E = $this->roundvariantfix(365.25 * $D);
        $G = $this->roundvariantfix(($C - $E) / 30.6001);

        if ($G < 13.5) {
            $H = $G - 1;
        } else {
            $H = $G - 13;
        }

        if ($H > 2.5) {
            $JDCYear = $D - 4716;
        } else {
            $JDCYear = $D - 4715;
        }
        return $JDCYear;
    }

    // Converting hours, minutes and seconds to decimal hours
    public function HMSDH(float $Hour, int $Minute, int $Second)
    {
        $A = abs($Second) / 60;
        $B = (abs($Minute) + $A) / 60;
        $C = abs($Hour) + $B;

        if (($Hour < 0) or ($Minute < 0) or ($Second < 0)) {
            $HMSDH = $C * (-1);
        } else {
            $HMSDH = $C;
        }
        return $HMSDH;
    }

    // Converting decimal hours to hours, minutes and seconds
    public function DHHMS(float $DH)
    {
        $hours = $this->DHHour($DH);
        $minutes = $this->DHMin($DH);
        $seconds = $this->DHSec($DH);
        $HMS = ['hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds];
        return $HMS;
    }

    //Decimal Hours to Hours
    protected function DHHour(float $DH)
    {
        $A = abs($DH);
        $B = $A * 3600;
        $C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

        if ($C == 60) {
            // $D = 0;
            $E = $B + 60;
        } else {
            // $D = $C;
            $E = $B;
        }

        if ($DH < 0) {
            $DHHour = $this->roundvariantfix($E / 3600) * (-1);
        } else {
            $DHHour = $this->roundvariantfix($E / 3600);
        }
        return $DHHour;
    }

    protected function DHMin($DH)
    {
        $A = abs($DH);
        $B = $A * 3600;
        $C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

        if ($C == 60) {
            // $D = 0;
            $E = $B + 60;
        } else {
            // $D = $C;
            $E = $B;
        }

        $DHMin = fmod(floor($E / 60), 60);
        return $DHMin;
    }

    protected function DHSec($DH)
    {
        $A = abs($DH);
        $B = $A * 3600;
        $C = round($B - 60 * $this->roundvariantfix($B / 60), 2);
        if ($C == 60) {
            $D = 0;
        } else {
            $D = $C;
        }

        $DHSec = $D;
        return $DHSec;
    }

    // Conversion of Local Civil Time to UT (Universal Time) --- Achtung: hier wird ein Array ausgegeben !!!
    public function LctUT(int $Hour, int $Minute, int $Second, int $DS, float $ZC, int $day, int $month, int $year)
    {
        $A = $this->HMSDH(floatval($Hour), $Minute, $Second);     //LCT
        $B = $A - $DS - $ZC;                   //UT
        $C = floatval($day + ($B / 24));                 //G day
        $D = $this->CDJD(floatval($C), intval($month), intval($year));  //JD
        $GD = $this->JDCDay($D);                       //G day
        $GM = $this->JDCMonth($D);                    //G month
        $GY = $this->JDCYear($D);                      //G year
        $GDfix = $this->roundvariantfix($GD);
        $UTDec = 24 * ($GD - $GDfix);
        return ['UTDec' => $UTDec, 'GD' => $GD, 'GM' => $GM, 'GY' => $GY];
    }

    // Conversion of UT (Universal Time) to Local Civil Time --- Achtung: hier wird ein Array ausgegeben !!!
    public function UTLct(float $UH, float $UM, float $US, int $DS, float $ZC, int $GD, int $GM, int $GY)
    {
        $A = $this->HMSDH($UH, intval($UM), intval($US));
        $B = $A + $ZC;
        $C = $B + $DS;
        $D = $this->CDJD(floatval($GD), intval($GM), intval($GY)) + ($C / 24);
        $E = $this->JDCDay($D);
        $F = $this->JDCMonth($D);
        $G = $this->JDCYear($D);
        $E1 = $this->roundvariantfix($E);
        $UTLct = 24 * ($E - $E1);
        return [$UTLct, $E1, $F, $G];
    }

    //Conversion of UT to GST (Greenwich Sideral Time)
    public function UTGST(float $UH, float $UM, float $US, int $GD, int $GM, int $GY)
    {
        $A = $this->CDJD(floatval($GD), intval($GM), intval($GY));
        $B = $A - 2451545;
        $C = $B / 36525;
        $D = 6.697374558 + (2400.051336 * $C) + (0.000025862 * $C * $C);
        $E = $D - (24 * $this->roundvariantint($D / 24));
        $F = $this->HMSDH($UH, intval($UM), intval($US));
        $G = $F * 1.002737909;
        $H = $E + $G;
        $UTGST = $H - (24 * $this->roundvariantint($H / 24));
        return $UTGST;
    }

    //Conversion of GST to UT --- Achtung: hier wird ein Array ausgegeben !!!
    public function GSTUT(float $GSH, int $GSM, int $GSS, int $GD, int $GM, int $GY)
    {
        $A = $this->CDJD(floatval($GD), intval($GM), intval($GY));
        $B = $A - 2451545;
        $C = $B / 36525;
        $D = 6.697374558 + (2400.051336 * $C) + (0.000025862 * $C * $C);
        $E = $D - (24 * $this->roundvariantint($D / 24));
        $F = $this->HMSDH($GSH, $GSM, $GSS);
        $G = $F - $E;
        $H = $G - (24 * $this->roundvariantint($G / 24));
        $GSTUT = $H * 0.9972695663;
        if ($GSTUT < (4 / 60)) {
            $eGSTUT = 'Warning';
        } else {
            $eGSTUT = 'OK';
        }
        return [$GSTUT, $eGSTUT];
    }

    //Conversion of GST to LST (Local Sideral Time)
    public function GSTLST(float $GH, int $GM, int $GS, float $L)
    {
        $A = $this->HMSDH($GH, $GM, $GS);
        $B = $L / 15;
        $C = $A + $B;
        $GSTLST = $C - (24 * $this->roundvariantint($C / 24));
        return $GSTLST;
    }

    //Conversion of LST to GST (Greenwich Sideral Time)
    public function LSTGST(float $LH, int $LM, int $LS, float $L)
    {
        $A = $this->HMSDH($LH, $LM, $LS);
        $B = $L / 15;
        $C = $A - $B;
        $LSTGST = $C - (24 * $this->roundvariantint($C / 24));
        return $LSTGST;
    }

    public function UTDayAdjust(int $UT, int $G1)
    {
        $UTDayAdjust = $UT;

        if (($UT - $G1) < -6) {
            $UTDayAdjust = $UT + 24;
        }

        if (($UT - $G1) > 6) {
            $UTDayAdjust = $UT - 24;
        }
        return $UTDayAdjust;
    }

    //Converting degrees, minutes and seconds to decimal degrees
    public function DMSDD(float $D, int $M, int $S)
    {
        $A = abs($S) / 60;
        $B = (abs($M) + $A) / 60;
        $C = abs($D) + $B;

        if (($D < 0) or ($M < 0) or ($S < 0)) {
            $DMSDD = $C * (-1);
        } else {
            $DMSDD = $C;
        }
        return $DMSDD;
    }

    //decimal degrees to degrees
    protected function DDDeg(float $DD)
    {
        $A = abs($DD);
        $B = $A * 3600;
        $C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

        if ($C == 60) {
            //$D = 0;
            $E = $B + 60;
        } else {
            //$D = $C;
            $E = $B;
        }

        if ($DD < 0) {
            $DDDeg = $this->roundvariantfix($E / 3600) * (-1);
        } else {
            $DDDeg = $this->roundvariantfix($E / 3600);
        }
        return $DDDeg;
    }

    //decimal degrees to minutes
    protected function DDMin($DD)
    {
        $A = abs($DD);
        $B = $A * 3600;
        $C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

        if ($C == 60) {
            //$D = 0;
            $E = $B + 60;
        } else {
            //$D = $C;
            $E = $B;
        }

        $DDMin = fmod(floor($E / 60), 60);
        return $DDMin;
    }

    //decimal degrees to seconds
    protected function DDSec($DD)
    {
        $A = abs($DD);
        $B = $A * 3600;
        $C = round($B - 60 * $this->roundvariantfix($B / 60), 2);
        if ($C == 60) {
            $D = 0;
        } else {
            $D = $C;
        }

        $DDSec = $D;
        return $DDSec;
    }

    //Decimal Degrees to Decimal Hours
    protected function DDDH($DD)
    {
        $DDDH = $DD / 15;
        return $DDDH;
    }

    //Decimal Hours to Decimal Degrees
    protected function DHDD($DH)
    {
        $DHDD = $DH * 15;
        return $DHDD;
    }

    protected function LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
    {
        $A = $this->HMSDH(floatval($LCH), intval($LCM), intval($LCS));
        $B = $A - $DS - $ZC;
        $C = floatval($LD + ($B / 24));
        $D = $this->CDJD(floatval($C), intval($LM), intval($LY));
        $E = $this->JDCDay($D);
        $LctGDay = $this->roundvariantfix($E);
        return $LctGDay;
    }

    protected function LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
    {
        $A = $this->HMSDH(floatval($LCH), intval($LCM), intval($LCS));
        $B = $A - $DS - $ZC;
        $C = floatval($LD + ($B / 24));
        $D = $this->CDJD(floatval($C), intval($LM), intval($LY));
        $LctGMonth = $this->JDCMonth($D);
        return $LctGMonth;
    }

    protected function LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
    {
        $A = $this->HMSDH(floatval($LCH), intval($LCM), intval($LCS));
        $B = $A - $DS - $ZC;
        $C = floatval($LD + ($B / 24));
        $D = $this->CDJD(floatval($C), intval($LM), intval($LY));
        $LctGYear = $this->JDCYear($D);
        return $LctGYear;
    }

    //Conversion of right ascension to hour angle
    //RH Right Ascension in HMS, LH Local Civil Time in HMS, DS Daylight saving, ZC Zonecorrection,
    //LD Local Calender Date in DMY, L geographical Longitude in Degrees
    protected function RAHA($RH, $RM, $RS, $LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY, $L)
    {
        $A = $this->LctUT(intval($LCH), intval($LCM), intval($LCS), intval($DS), floatval($ZC), intval($LD), intval($LM), intval($LY))['UTDec'];
        $B = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $C = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $D = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $E = $this->UTGST(floatval($A), 0, 0, intval($B), intval($C), intval($D));
        $F = $this->GSTLST($E, 0, 0, $L);
        $G = $this->HMSDH(floatval($RH), intval($RM), intval($RS));
        $H = $F - $G;
        if ($H < 0) {
            $RAHA = 24 + $H;
        } else {
            $RAHA = $H;
        }
        return $RAHA;
    }

    //Conversion of hour angle to right ascension
    protected function HARA($HH, $HM, $HS, $LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY, $L)
    {
        $A = $this->LctUT(intval($LCH), intval($LCM), intval($LCS), intval($DS), floatval($ZC), intval($LD), intval($LM), intval($LY))['UTDec'];
        $B = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $C = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $D = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $E = $this->UTGST(floatval($A), 0, 0, intval($B), intval($C), intval($D));
        $F = $this->GSTLST($E, 0, 0, $L);
        $G = $this->HMSDH(floatval($HH), intval($HM), intval($HS));
        $H = $F - $G;
        if ($H < 0) {
            $HARA = 24 + $H;
        } else {
            $HARA = $H;
        }
        return $HARA;
    }

    protected function Radians($W)
    {
        $Radians = $W * 0.01745329252;
        return $Radians;
    }

    protected function Degrees($W)
    {
        $Degrees = $W * 57.29577951;
        return $Degrees;
    }

    //Equatorial to Horizon coordinate conversion (Az)
    //HH HourAngle in HMS, DD Declination in DMS, P Latitude in decimal Degrees
    protected function EQAz($HH, $HM, $HS, $DD, $DM, $DS, $P)
    {
        $A = $this->HMSDH(floatval($HH), intval($HM), intval($HS));
        $B = $A * 15;
        $C = $this->Radians($B);
        $D = $this->DMSDD(floatval($DD), intval($DM), intval($DS));
        $E = $this->Radians($D);
        $F = $this->Radians($P);
        $G = sin($E) * sin($F) + cos($E) * cos($F) * cos($C);
        $H = -cos($E) * cos($F) * sin($C);
        $I = sin($E) - (sin($F) * $G);
        $J = $this->Degrees(atan2($H, $I));
        $EQAz = $J - 360 * $this->roundvariantint($J / 360);
        return $EQAz;
    }

    ////Equatorial to Horizon coordinate conversion (Alt)
    protected function EQAlt($HH, $HM, $HS, $DD, $DM, $DS, $P)
    {
        $A = $this->HMSDH(floatval($HH), intval($HM), intval($HS));
        $B = $A * 15;
        $C = $this->Radians($B);
        $D = $this->DMSDD(floatval($DD), intval($DM), intval($DS));
        $E = $this->Radians($D);
        $F = $this->Radians($P);
        $G = sin($E) * sin($F) + cos($E) * cos($F) * cos($C);
        $EQAlt = $this->Degrees(asin($G));
        return $EQAlt;
    }

    protected function HORDec($AZD, $AZM, $AZS, $ALD, $ALM, $ALS, $P)
    {
        $A = $this->DMSDD(floatval($AZD), intval($AZM), intval($AZS));
        $B = $this->DMSDD(floatval($ALD), intval($ALM), intval($ALS));
        $C = $this->Radians($A);
        $D = $this->Radians($B);
        $E = $this->Radians($P);
        $F = sin($D) * sin($E) + cos($D) * cos($E) * cos($C);
        $HORDec = $this->Degrees(asin($F));
        return $HORDec;
    }

    protected function HORHa($AZD, $AZM, $AZS, $ALD, $ALM, $ALS, $P)
    {
        $A = $this->DMSDD(floatval($AZD), intval($AZM), intval($AZS));
        $B = $this->DMSDD(floatval($ALD), intval($ALM), intval($ALS));
        $C = $this->Radians($A);
        $D = $this->Radians($B);
        $E = $this->Radians($P);
        $F = sin($D) * sin($E) + cos($D) * cos($E) * cos($C);
        $G = -cos($D) * cos($E) * sin($C);
        $H = sin($D) - sin($E) * $F;
        $I = $this->DDDH($this->Degrees(atan2($G, $H)));
        $HORHa = $I - 24 * $this->roundvariantint($I / 24);
        return $HORHa;
    }

    protected function Obliq($GD, $GM, $GY)
    {
        $A = $this->CDJD(floatval($GD), intval($GM), intval($GY));
        $B = $A - 2415020;
        $C = ($B / 36525) - 1;
        $D = $C * (46.815 + $C * (0.0006 - ($C * 0.00181)));
        $E = $D / 3600;
        $Obliq = 23.43929167 - $E + $this->NutatObl($GD, $GM, $GY);
        return $Obliq;
    }

    protected function ECDec($ELD, $ELM, $ELS, $BD, $BM, $BS, $GD, $GM, $GY)
    {
        $A = $this->Radians($this->DMSDD(floatval($ELD), intval($ELM), intval($ELS)));                      //eclon
        $B = $this->Radians($this->DMSDD(floatval($BD), intval($BM), intval($BS)));                         //eclat
        $C = $this->Radians($this->Obliq($GD, $GM, $GY));                         //obliq
        $D = sin($B) * cos($C) + cos($B) * sin($C) * sin($A);   //sin Dec
        $ECDec = $this->Degrees(asin($D));                             //Dec Deg
        return $ECDec;
    }

    protected function ECRA($ELD, $ELM, $ELS, $BD, $BM, $BS, $GD, $GM, $GY)
    {
        $A = $this->Radians($this->DMSDD(floatval($ELD), intval($ELM), intval($ELS)));       //eclon
        $B = $this->Radians($this->DMSDD(floatval($BD), intval($BM), intval($BS)));          //eclat
        $C = $this->Radians($this->Obliq($GD, $GM, $GY));          //obliq
        $D = sin($A) * cos($C) - tan($B) * sin($C); //y
        $E = cos($A);                                //x
        $F = $this->Degrees(atan2($D, $E));                //RA Deg
        $ECRA = $F - 360 * $this->roundvariantint($F / 360);   //RA Deg
        return $ECRA;
    }

    protected function EQElat($RAH, $RAM, $RAS, $DD, $DM, $DS, $GD, $GM, $GY)
    {
        $A = $this->Radians($this->DHDD($this->HMSDH(floatval($RAH), intval($RAM), intval($RAS))));
        $B = $this->Radians($this->DMSDD(floatval($DD), intval($DM), intval($DS)));
        $C = $this->Radians($this->Obliq($GD, $GM, $GY));
        $D = sin($B) * cos($C) - cos($B) * sin($C) * sin($A);
        $EQElat = $this->Degrees(asin($D));
        return $EQElat;
    }

    protected function EQElong($RAH, $RAM, $RAS, $DD, $DM, $DS, $GD, $GM, $GY)
    {
        $A = $this->Radians($this->DHDD($this->HMSDH(floatval($RAH), intval($RAM), intval($RAS))));
        $B = $this->Radians($this->DMSDD(floatval($DD), intval($DM), intval($DS)));
        $C = $this->Radians($this->Obliq($GD, $GM, $GY));
        $D = sin($A) * cos($C) + tan($B) * sin($C);
        $E = cos($A);
        $F = $this->Degrees(atan2($D, $E));
        $EQElong = $F - 360 * $this->roundvariantint($F / 360);
        return $EQElong;
    }

    protected function EQGlong($RAH, $RAM, $RAS, $DD, $DM, $DS)
    {
        $A = $this->Radians($this->DHDD($this->HMSDH(floatval($RAH), intval($RAM), intval($RAS))));
        $B = $this->Radians($this->DMSDD(floatval($DD), intval($DM), intval($DS)));
        $C = cos($this->Radians(27.4));
        $D = sin($this->Radians(27.4));
        $E = $this->Radians(192.25);
        $F = cos($B) * $C * cos($A - $E) + sin($B) * $D;
        $G = sin($B) - $F * $D;
        $H = cos($B) * sin($A - $E) * $C;
        $I = $this->Degrees(atan2($G, $H)) + 33;
        $EQGlong = $I - 360 * $this->roundvariantint($I / 360);
        return $EQGlong;
    }

    protected function EQGlat($RAH, $RAM, $RAS, $DD, $DM, $DS)
    {
        $A = $this->Radians($this->DHDD($this->HMSDH(floatval($RAH), intval($RAM), intval($RAS))));
        $B = $this->Radians($this->DMSDD(floatval($DD), intval($DM), intval($DS)));
        $C = cos($this->Radians(27.4));
        $D = sin($this->Radians(27.4));
        $E = $this->Radians(192.25);
        $F = cos($B) * $C * cos($A - $E) + sin($B) * $D;
        $EQGlat = $this->Degrees(asin($F));
        return $EQGlat;
    }

    protected function EqOfTime($GD, $GM, $GY)
    {
        $A = $this->SunLong(12, 0, 0, 0, 0, $GD, $GM, $GY);
        $B = $this->DDDH($this->ECRA($A, 0, 0, 0, 0, 0, $GD, $GM, $GY));
        $C = $this->GSTUT($B, 0, 0, $GD, $GM, $GY)[0];
        $EqOfTime = $C - 12;
        return $EqOfTime;
    }

    protected function NutatLong($GD, $GM, $GY)
    {
        $DJ = $this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020;
        $T = $DJ / 36525;
        $T2 = $T * $T;
        $A = 100.0021358 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $L1 = 279.6967 + 0.000303 * $T2 + $B;
        $l2 = 2 * $this->Radians($L1);
        $A = 1336.855231 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $D1 = 270.4342 - 0.001133 * $T2 + $B;
        $D2 = 2 * $this->Radians($D1);
        $A = 99.99736056 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M1 = 358.4758 - 0.00015 * $T2 + $B;
        $M1 = $this->Radians($M1);
        $A = 1325.552359 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M2 = 296.1046 + 0.009192 * $T2 + $B;
        $M2 = $this->Radians($M2);
        $A = 5.372616667 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $N1 = 259.1833 + 0.002078 * $T2 - $B;
        $N1 = $this->Radians($N1);
        $N2 = 2 * $N1;

        $DP = (-17.2327 - 0.01737 * $T) * sin($N1);
        $DP = $DP + (-1.2729 - 0.00013 * $T) * sin($l2) + 0.2088 * sin($N2);
        $DP = $DP - 0.2037 * sin($D2) + (0.1261 - 0.00031 * $T) * sin($M1);
        $DP = $DP + 0.0675 * sin($M2) - (0.0497 - 0.00012 * $T) * sin($l2 + $M1);
        $DP = $DP - 0.0342 * sin($D2 - $N1) - 0.0261 * sin($D2 + $M2);
        $DP = $DP + 0.0214 * sin($l2 - $M1) - 0.0149 * sin($l2 - $D2 + $M2);
        $DP = $DP + 0.0124 * sin($l2 - $N1) + 0.0114 * sin($D2 - $M2);

        $NutatLong = $DP / 3600;
        return $NutatLong;
    }

    protected function NutatObl($GD, $GM, $GY)
    {
        $DJ = $this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020;
        $T = $DJ / 36525;
        $T2 = $T * $T;
        $A = 100.0021358 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $L1 = 279.6967 + 0.000303 * $T2 + $B;
        $l2 = 2 * $this->Radians($L1);
        $A = 1336.855231 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $D1 = 270.4342 - 0.001133 * $T2 + $B;
        $D2 = 2 * $this->Radians($D1);
        $A = 99.99736056 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M1 = 358.4758 - 0.00015 * $T2 + $B;
        $M1 = $this->Radians($M1);
        $A = 1325.552359 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M2 = 296.1046 + 0.009192 * $T2 + $B;
        $M2 = $this->Radians($M2);
        $A = 5.372616667 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $N1 = 259.1833 + 0.002078 * $T2 - $B;
        $N1 = $this->Radians($N1);
        $N2 = 2 * $N1;

        $DDO = (9.21 + 0.00091 * $T) * cos($N1);
        $DDO = $DDO + (0.5522 - 0.00029 * $T) * cos($l2) - 0.0904 * cos($N2);
        $DDO = $DDO + 0.0884 * cos($D2) + 0.0216 * cos($l2 + $M1);
        $DDO = $DDO + 0.0183 * cos($D2 - $N1) + 0.0113 * cos($D2 + $M2);
        $DDO = $DDO - 0.0093 * cos($l2 - $M1) - 0.0066 * cos($l2 - $N1);

        $NutatObl = $DDO / 3600;
        return $NutatObl;
    }

    protected function MoonDist($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $HP = $this->Radians($this->MoonHP($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR));
        $R = 6378.14 / sin($HP);
        $MoonDist = $R;
        return $MoonDist;
    }

    protected function MoonSize($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $HP = $this->Radians($this->MoonHP($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR));
        $R = 6378.14 / sin($HP);
        $TH = 384401 * 0.5181 / $R;
        $MoonSize = $TH;
        return $MoonSize;
    }

    protected function sign($number)
    {
        return ($number > 0) ? 1 : (($number < 0) ? -1 : 0);
    }

    protected function IINT($W)
    {
        $IINT = $this->sign($W) * $this->roundvariantint(abs($W));
        return $IINT;
    }

    protected function LINT($W)
    {
        $LINT = $this->IINT($W) + $this->IINT(((1 * sign($W)) - 1) / 2);
        return $LINT;
    }

    protected function FRACT($W)
    {
        $FRACT = $W - $this->LINT($W);
        return $FRACT;
    }

    protected function FullMoon($DS, $ZC, $DY, $MN, $YR)
    {
        $D0 = $this->LctGDay(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
        $M0 = $this->LctGMonth(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
        $Y0 = $this->LctGYear(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);

        if ($Y0 < 0) {
            $Y0 = $Y0 + 1;
        }

        $J0 = $this->CDJD(0, 1, intval($Y0)) - 2415020;
        $DJ = $this->CDJD(floatval($D0), intval($M0), intval($Y0)) - 2415020;
        $K = $this->LINT((($Y0 - 1900 + (($DJ - $J0) / 365)) * 12.3685) + 0.5);
        // $TN = $K / 1236.85;
        $TF = ($K + 0.5) / 1236.85;

        // $E1 = $this->roundvariantint($E);
        // $B = $B + $DD + ($E - $E1);
        // $B1 = $this->roundvariantint($B);
        // $A = $E1 + $B1;
        // $B = $B - $B1;
        //$NI = $A;
        //$NF = $B;
        //$NB = $F;
        $T = $TF;
        $K = $K + 0.5;
        $T2 = $T * $T;
        $E = 29.53 * $K;
        $C = 166.56 + (132.87 - 0.009173 * $T) * $T;
        $C = $this->Radians($C);
        $B = 0.00058868 * $K + (0.0001178 - 0.000000155 * $T) * $T2;
        $B = $B + 0.00033 * sin($C) + 0.75933;
        $A = $K / 12.36886;
        $A1 = 359.2242 + 360 * $this->FRACT($A) - (0.0000333 + 0.00000347 * $T) * $T2;
        $A2 = 306.0253 + 360 * $this->FRACT($K / 0.9330851);
        $A2 = $A2 + (0.0107306 + 0.00001236 * $T) * $T2;
        $A = $K / 0.9214926;
        $F = 21.2964 + 360 * $this->FRACT($A) - (0.0016528 + 0.00000239 * $T) * $T2;
        $A1 = $this->UnwindDeg($A1);
        $A2 = $this->UnwindDeg($A2);
        $F = $this->UnwindDeg($F);
        $A1 = $this->Radians($A1);
        $A2 = $this->Radians($A2);
        $F = $this->Radians($F);

        $DD = (0.1734 - 0.000393 * $T) * sin($A1) + 0.0021 * sin(2 * $A1);
        $DD = $DD - 0.4068 * sin($A2) + 0.0161 * sin(2 * $A2) - 0.0004 * sin(3 * $A2);
        $DD = $DD + 0.0104 * sin(2 * $F) - 0.0051 * sin($A1 + $A2);
        $DD = $DD - 0.0074 * sin($A1 - $A2) + 0.0004 * sin(2 * $F + $A1);
        $DD = $DD - 0.0004 * sin(2 * $F - $A1) - 0.0006 * sin(2 * $F + $A2) + 0.001 * sin(2 * $F - $A2);
        $DD = $DD + 0.0005 * sin($A1 + 2 * $A2);
        $E1 = $this->roundvariantint($E);
        $B = $B + $DD + ($E - $E1);
        $B1 = $this->roundvariantint($B);
        $A = $E1 + $B1;
        $B = $B - $B1;
        $FI = $A;
        $FF = $B;
        // $FB = $F;
        $FullMoon = $FI + 2415020 + $FF;
        return $FullMoon;
    }

    protected function Fpart($W)
    {
        $Fpart = $W - $this->LINT($W);
        return $Fpart;
    }

    protected function MoonHP($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $UT = $this->LctUT(intval($LH), intval($LM), intval($LS), intval($DS), floatval($ZC), intval($DY), intval($MN), intval($YR))['UTDec'];
        $GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $T = (($this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020) / 36525) + ($UT / 876600);
        $T2 = $T * $T;

        // $M1 = 27.32158213;
        $M2 = 365.2596407;
        $M3 = 27.55455094;
        $M4 = 29.53058868;
        $M5 = 27.21222039;
        $M6 = 6798.363307;
        $Q = $this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020 + ($UT / 24);
        // $M1 = $Q / $M1;
        $M2 = $Q / $M2;
        $M3 = $Q / $M3;
        $M4 = $Q / $M4;
        $M5 = $Q / $M5;
        $M6 = $Q / $M6;
        // $M1 = 360 * ($M1 - $this->roundvariantint($M1));
        $M2 = 360 * ($M2 - $this->roundvariantint($M2));
        $M3 = 360 * ($M3 - $this->roundvariantint($M3));
        $M4 = 360 * ($M4 - $this->roundvariantint($M4));
        $M5 = 360 * ($M5 - $this->roundvariantint($M5));
        $M6 = 360 * ($M6 - $this->roundvariantint($M6));

        // $ML = 270.434164 + $M1 - (0.001133 - 0.0000019 * $T) * $T2;
        $MS = 358.475833 + $M2 - (0.00015 + 0.0000033 * $T) * $T2;
        $MD = 296.104608 + $M3 + (0.009192 + 0.0000144 * $T) * $T2;
        $ME1 = 350.737486 + $M4 - (0.001436 - 0.0000019 * $T) * $T2;
        $MF = 11.250889 + $M5 - (0.003211 + 0.0000003 * $T) * $T2;
        $NA = 259.183275 - $M6 + (0.002078 + 0.0000022 * $T) * $T2;
        $A = $this->Radians(51.2 + 20.2 * $T);
        $S1 = sin($A);
        $S2 = sin($this->Radians($NA));
        $B = 346.56 + (132.87 - 0.0091731 * $T) * $T;
        $S3 = 0.003964 * sin($this->Radians($B));
        $C = $this->Radians($NA + 275.05 - 2.3 * $T);
        $S4 = sin($C);
        // $ML = $ML + 0.000233 * $S1 + $S3 + 0.001964 * $S2;
        $MS = $MS - 0.001778 * $S1;
        $MD = $MD + 0.000817 * $S1 + $S3 + 0.002541 * $S2;
        $MF = $MF + $S3 - 0.024691 * $S2 - 0.004328 * $S4;
        $ME1 = $ME1 + 0.002011 * $S1 + $S3 + 0.001964 * $S2;
        $E = 1 - (0.002495 + 0.00000752 * $T) * $T;
        $E2 = $E * $E;
        // $ML = $this->Radians($ML);
        $MS = $this->Radians($MS);
        // $NA = $this->Radians($NA);
        $ME1 = $this->Radians($ME1);
        $MF = $this->Radians($MF);
        $MD = $this->Radians($MD);

        $PM = 0.950724 + 0.051818 * cos($MD) + 0.009531 * cos(2 * $ME1 - $MD);
        $PM = $PM + 0.007843 * cos(2 * $ME1) + 0.002824 * cos(2 * $MD);
        $PM = $PM + 0.000857 * cos(2 * $ME1 + $MD) + $E * 0.000533 * cos(2 * $ME1 - $MS);
        $PM = $PM + $E * 0.000401 * cos(2 * $ME1 - $MD - $MS);
        $PM = $PM + $E * 0.00032 * cos($MD - $MS) - 0.000271 * cos($ME1);
        $PM = $PM - $E * 0.000264 * cos($MS + $MD) - 0.000198 * cos(2 * $MF - $MD);
        $PM = $PM + 0.000173 * cos(3 * $MD) + 0.000167 * cos(4 * $ME1 - $MD);
        $PM = $PM - $E * 0.000111 * cos($MS) + 0.000103 * cos(4 * $ME1 - 2 * $MD);
        $PM = $PM - 0.000084 * cos(2 * $MD - 2 * $ME1) - $E * 0.000083 * cos(2 * $ME1 + $MS);
        $PM = $PM + 0.000079 * cos(2 * $ME1 + 2 * $MD) + 0.000072 * cos(4 * $ME1);
        $PM = $PM + $E * 0.000064 * cos(2 * $ME1 - $MS + $MD) - $E * 0.000063 * cos(2 * $ME1 + $MS - $MD);
        $PM = $PM + $E * 0.000041 * cos($MS + $ME1) + $E * 0.000035 * cos(2 * $MD - $MS);
        $PM = $PM - 0.000033 * cos(3 * $MD - 2 * $ME1) - 0.00003 * cos($MD + $ME1);
        $PM = $PM - 0.000029 * cos(2 * ($MF - $ME1)) - $E * 0.000029 * cos(2 * $MD + $MS);
        $PM = $PM + $E2 * 0.000026 * cos(2 * ($ME1 - $MS)) - 0.000023 * cos(2 * ($MF - $ME1) + $MD);
        $PM = $PM + $E * 0.000019 * cos(4 * $ME1 - $MS - $MD);

        $MoonHP = $PM;
        return $MoonHP;
    }

    protected function CRN($GD, $GM, $GY)
    {
        $A = $this->CDJD(floatval($GD), intval($GM), intval($GY));
        $CRN = 1690 + round(($A - 2444235.34) / 27.2753, 0);
        return $CRN;
    }

    protected function UnwindDeg($W)
    {
        $UnwindDeg = $W - 360 * $this->roundvariantint($W / 360);
        return $UnwindDeg;
    }

    protected function UnwindRad($W)
    {
        $UnwindRad = $W - 6.283185308 * $this->roundvariantint($W / 6.283185308);
        return $UnwindRad;
    }

    protected function Unwind($W)
    {
        $Unwind = $W - 6.283185308 * $this->roundvariantint($W / 6.283185308);
        return $Unwind;
    }

    protected function MoonMeanAnomaly($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $UT = $this->LctUT(intval($LH), intval($LM), intval($LS), intval($DS), floatval($ZC), intval($DY), intval($MN), intval($YR))['UTDec'];
        $GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $T = (($this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020) / 36525) + ($UT / 876600);
        $T2 = $T * $T;

        // $M1 = 27.32158213;
        // $M2 = 365.2596407;
        $M3 = 27.55455094;
        // $M4 = 29.53058868;
        // $M5 = 27.21222039;
        $M6 = 6798.363307;
        $Q = $this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020 + ($UT / 24);
        // $M1 = $Q / $M1;
        // $M2 = $Q / $M2;
        $M3 = $Q / $M3;
        // $M4 = $Q / $M4;
        // $M5 = $Q / $M5;
        $M6 = $Q / $M6;
        // $M1 = 360 * ($M1 - $this->roundvariantint($M1));
        // $M2 = 360 * ($M2 - $this->roundvariantint($M2));
        $M3 = 360 * ($M3 - $this->roundvariantint($M3));
        // $M4 = 360 * ($M4 - $this->roundvariantint($M4));
        // $M5 = 360 * ($M5 - $this->roundvariantint($M5));
        $M6 = 360 * ($M6 - $this->roundvariantint($M6));

        // $ML = 270.434164 + $M1 - (0.001133 - 0.0000019 * $T) * $T2;
        // $MS = 358.475833 + $M2 - (0.00015 + 0.0000033 * $T) * $T2;
        $MD = 296.104608 + $M3 + (0.009192 + 0.0000144 * $T) * $T2;
        // $ME1 = 350.737486 + $M4 - (0.001436 - 0.0000019 * $T) * $T2;
        // $MF = 11.250889 + $M5 - (0.003211 + 0.0000003 * $T) * $T2;
        $NA = 259.183275 - $M6 + (0.002078 + 0.0000022 * $T) * $T2;
        $A = $this->Radians(51.2 + 20.2 * $T);
        $S1 = sin($A);
        $S2 = sin($this->Radians($NA));
        $B = 346.56 + (132.87 - 0.0091731 * $T) * $T;
        $S3 = 0.003964 * sin($this->Radians($B));
        // $C = $this->Radians($NA + 275.05 - 2.3 * $T);
        // $S4 = sin($C);
        // $ML = $ML + 0.000233 * $S1 + $S3 + 0.001964 * $S2;
        // $MS = $MS - 0.001778 * $S1;
        $MD = $MD + 0.000817 * $S1 + $S3 + 0.002541 * $S2;

        $MoonMeanAnomaly = $this->Radians($MD);
        return $MoonMeanAnomaly;
    }

    protected function MoonPhase($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $CD = cos($this->Radians($this->MoonLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR) - $this->SunLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR))) * cos($this->Radians($this->MoonLat($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)));
        $D = acos($CD);
        $SD = sin($D);
        $I = 0.1468 * $SD * (1 - 0.0549 * sin($this->MoonMeanAnomaly($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)));
        $I = $I / (1 - 0.0167 * sin($this->SunMeanAnomaly($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)));
        $I = 3.141592654 - $D - $this->Radians($I);
        $K = (1 + cos($I)) / 2;
        $MoonPhase = number_format($K * 100, 1, ',', ''); //*100 is %
        return $MoonPhase;
    }

    protected function MoonPABL($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $LLS = $this->SunLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $LLM = $this->MoonLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $BM = $this->MoonLat($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $RAS = $this->Radians($this->ECRA($LLS, 0, 0, 0, 0, 0, $GD, $GM, $GY));
        $RAM = $this->Radians($this->ECRA($LLM, 0, 0, $BM, 0, 0, $GD, $GM, $GY));
        $DDS = $this->Radians($this->ECDec($LLS, 0, 0, 0, 0, 0, $GD, $GM, $GY));
        $DM = $this->Radians($this->ECDec($LLM, 0, 0, $BM, 0, 0, $GD, $GM, $GY));
        $Y = cos($DDS) * sin($RAS - $RAM);
        $X = cos($DM) * sin($DDS) - sin($DM) * cos($DDS) * cos($RAS - $RAM);
        $CHI = atan2($Y, $X);

        $MoonPABL = $this->Degrees($CHI);
        return $MoonPABL;
    }

    protected function MoonLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $UT = $this->LctUT(intval($LH), intval($LM), intval($LS), intval($DS), floatval($ZC), intval($DY), intval($MN), intval($YR))['UTDec'];
        $GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $T = (($this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020) / 36525) + ($UT / 876600);
        $T2 = $T * $T;

        $M1 = 27.32158213;
        $M2 = 365.2596407;
        $M3 = 27.55455094;
        $M4 = 29.53058868;
        $M5 = 27.21222039;
        $M6 = 6798.363307;
        $Q = $this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020 + ($UT / 24);
        $M1 = $Q / $M1;
        $M2 = $Q / $M2;
        $M3 = $Q / $M3;
        $M4 = $Q / $M4;
        $M5 = $Q / $M5;
        $M6 = $Q / $M6;
        $M1 = 360 * ($M1 - $this->roundvariantint($M1));
        $M2 = 360 * ($M2 - $this->roundvariantint($M2));
        $M3 = 360 * ($M3 - $this->roundvariantint($M3));
        $M4 = 360 * ($M4 - $this->roundvariantint($M4));
        $M5 = 360 * ($M5 - $this->roundvariantint($M5));
        $M6 = 360 * ($M6 - $this->roundvariantint($M6));

        $ML = 270.434164 + $M1 - (0.001133 - 0.0000019 * $T) * $T2;
        $MS = 358.475833 + $M2 - (0.00015 + 0.0000033 * $T) * $T2;
        $MD = 296.104608 + $M3 + (0.009192 + 0.0000144 * $T) * $T2;
        $ME1 = 350.737486 + $M4 - (0.001436 - 0.0000019 * $T) * $T2;
        $MF = 11.250889 + $M5 - (0.003211 + 0.0000003 * $T) * $T2;
        $NA = 259.183275 - $M6 + (0.002078 + 0.0000022 * $T) * $T2;
        $A = $this->Radians(51.2 + 20.2 * $T);
        $S1 = sin($A);
        $S2 = sin($this->Radians($NA));
        $B = 346.56 + (132.87 - 0.0091731 * $T) * $T;
        $S3 = 0.003964 * sin($this->Radians($B));
        $C = $this->Radians($NA + 275.05 - 2.3 * $T);
        $S4 = sin($C);
        $ML = $ML + 0.000233 * $S1 + $S3 + 0.001964 * $S2;
        $MS = $MS - 0.001778 * $S1;
        $MD = $MD + 0.000817 * $S1 + $S3 + 0.002541 * $S2;
        $MF = $MF + $S3 - 0.024691 * $S2 - 0.004328 * $S4;
        $ME1 = $ME1 + 0.002011 * $S1 + $S3 + 0.001964 * $S2;
        $E = 1 - (0.002495 + 0.00000752 * $T) * $T;
        $E2 = $E * $E;
        $ML = $this->Radians($ML);
        $MS = $this->Radians($MS);
        // $NA = $this->Radians($NA);
        $ME1 = $this->Radians($ME1);
        $MF = $this->Radians($MF);
        $MD = $this->Radians($MD);

        $L = 6.28875 * sin($MD) + 1.274018 * sin(2 * $ME1 - $MD);
        $L = $L + 0.658309 * sin(2 * $ME1) + 0.213616 * sin(2 * $MD);
        $L = $L - $E * 0.185596 * sin($MS) - 0.114336 * sin(2 * $MF);
        $L = $L + 0.058793 * sin(2 * ($ME1 - $MD));
        $L = $L + 0.057212 * $E * sin(2 * $ME1 - $MS - $MD) + 0.05332 * sin(2 * $ME1 + $MD);
        $L = $L + 0.045874 * $E * sin(2 * $ME1 - $MS) + 0.041024 * $E * sin($MD - $MS);
        $L = $L - 0.034718 * sin($ME1) - $E * 0.030465 * sin($MS + $MD);
        $L = $L + 0.015326 * sin(2 * ($ME1 - $MF)) - 0.012528 * sin(2 * $MF + $MD);
        $L = $L - 0.01098 * sin(2 * $MF - $MD) + 0.010674 * sin(4 * $ME1 - $MD);
        $L = $L + 0.010034 * sin(3 * $MD) + 0.008548 * sin(4 * $ME1 - 2 * $MD);
        $L = $L - $E * 0.00791 * sin($MS - $MD + 2 * $ME1) - $E * 0.006783 * sin(2 * $ME1 + $MS);
        $L = $L + 0.005162 * sin($MD - $ME1) + $E * 0.005 * sin($MS + $ME1);
        $L = $L + 0.003862 * sin(4 * $ME1) + $E * 0.004049 * sin($MD - $MS + 2 * $ME1);
        $L = $L + 0.003996 * sin(2 * ($MD + $ME1)) + 0.003665 * sin(2 * $ME1 - 3 * $MD);
        $L = $L + $E * 0.002695 * sin(2 * $MD - $MS) + 0.002602 * sin($MD - 2 * ($MF + $ME1));
        $L = $L + $E * 0.002396 * sin(2 * ($ME1 - $MD) - $MS) - 0.002349 * sin($MD + $ME1);
        $L = $L + $E2 * 0.002249 * sin(2 * ($ME1 - $MS)) - $E * 0.002125 * sin(2 * $MD + $MS);
        $L = $L - $E2 * 0.002079 * sin(2 * $MS) + $E2 * 0.002059 * sin(2 * ($ME1 - $MS) - $MD);
        $L = $L - 0.001773 * sin($MD + 2 * ($ME1 - $MF)) - 0.001595 * sin(2 * ($MF + $ME1));
        $L = $L + $E * 0.00122 * sin(4 * $ME1 - $MS - $MD) - 0.00111 * sin(2 * ($MD + $MF));
        $L = $L + 0.000892 * sin($MD - 3 * $ME1) - $E * 0.000811 * sin($MS + $MD + 2 * $ME1);
        $L = $L + $E * 0.000761 * sin(4 * $ME1 - $MS - 2 * $MD);
        $L = $L + $E2 * 0.000704 * sin($MD - 2 * ($MS + $ME1));
        $L = $L + $E * 0.000693 * sin($MS - 2 * ($MD - $ME1));
        $L = $L + $E * 0.000598 * sin(2 * ($ME1 - $MF) - $MS);
        $L = $L + 0.00055 * sin($MD + 4 * $ME1) + 0.000538 * sin(4 * $MD);
        $L = $L + $E * 0.000521 * sin(4 * $ME1 - $MS) + 0.000486 * sin(2 * $MD - $ME1);
        $L = $L + $E2 * 0.000717 * sin($MD - 2 * $MS);
        $MM = $this->Unwind($ML + $this->Radians($L));

        $MoonLong = $this->Degrees($MM);
        return $MoonLong;
    }

    protected function MoonLat($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $UT = $this->LctUT(intval($LH), intval($LM), intval($LS), intval($DS), floatval($ZC), intval($DY), intval($MN), intval($YR))['UTDec'];
        $GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $T = (($this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020) / 36525) + ($UT / 876600);
        $T2 = $T * $T;

        // $M1 = 27.32158213;
        $M2 = 365.2596407;
        $M3 = 27.55455094;
        $M4 = 29.53058868;
        $M5 = 27.21222039;
        $M6 = 6798.363307;
        $Q = $this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020 + ($UT / 24);
        // $M1 = $Q / $M1;
        $M2 = $Q / $M2;
        $M3 = $Q / $M3;
        $M4 = $Q / $M4;
        $M5 = $Q / $M5;
        $M6 = $Q / $M6;
        //$M1 = 360 * ($M1 - $this->roundvariantint($M1));
        $M2 = 360 * ($M2 - $this->roundvariantint($M2));
        $M3 = 360 * ($M3 - $this->roundvariantint($M3));
        $M4 = 360 * ($M4 - $this->roundvariantint($M4));
        $M5 = 360 * ($M5 - $this->roundvariantint($M5));
        $M6 = 360 * ($M6 - $this->roundvariantint($M6));

        // $ML = 270.434164 + $M1 - (0.001133 - 0.0000019 * $T) * $T2;
        $MS = 358.475833 + $M2 - (0.00015 + 0.0000033 * $T) * $T2;
        $MD = 296.104608 + $M3 + (0.009192 + 0.0000144 * $T) * $T2;
        $ME1 = 350.737486 + $M4 - (0.001436 - 0.0000019 * $T) * $T2;
        $MF = 11.250889 + $M5 - (0.003211 + 0.0000003 * $T) * $T2;
        $NA = 259.183275 - $M6 + (0.002078 + 0.0000022 * $T) * $T2;
        $A = $this->Radians(51.2 + 20.2 * $T);
        $S1 = sin($A);
        $S2 = sin($this->Radians($NA));
        $B = 346.56 + (132.87 - 0.0091731 * $T) * $T;
        $S3 = 0.003964 * sin($this->Radians($B));
        $C = $this->Radians($NA + 275.05 - 2.3 * $T);
        $S4 = sin($C);
        // $ML = $ML + 0.000233 * $S1 + $S3 + 0.001964 * $S2;
        $MS = $MS - 0.001778 * $S1;
        $MD = $MD + 0.000817 * $S1 + $S3 + 0.002541 * $S2;
        $MF = $MF + $S3 - 0.024691 * $S2 - 0.004328 * $S4;
        $ME1 = $ME1 + 0.002011 * $S1 + $S3 + 0.001964 * $S2;
        $E = 1 - (0.002495 + 0.00000752 * $T) * $T;
        $E2 = $E * $E;
        // $ML = $this->Radians($ML);
        $MS = $this->Radians($MS);
        $NA = $this->Radians($NA);
        $ME1 = $this->Radians($ME1);
        $MF = $this->Radians($MF);
        $MD = $this->Radians($MD);

        $G = 5.128189 * sin($MF) + 0.280606 * sin($MD + $MF);
        $G = $G + 0.277693 * sin($MD - $MF) + 0.173238 * sin(2 * $ME1 - $MF);
        $G = $G + 0.055413 * sin(2 * $ME1 + $MF - $MD) + 0.046272 * sin(2 * $ME1 - $MF - $MD);
        $G = $G + 0.032573 * sin(2 * $ME1 + $MF) + 0.017198 * sin(2 * $MD + $MF);
        $G = $G + 0.009267 * sin(2 * $ME1 + $MD - $MF) + 0.008823 * sin(2 * $MD - $MF);
        $G = $G + $E * 0.008247 * sin(2 * $ME1 - $MS - $MF) + 0.004323 * sin(2 * ($ME1 - $MD) - $MF);
        $G = $G + 0.0042 * sin(2 * $ME1 + $MF + $MD) + $E * 0.003372 * sin($MF - $MS - 2 * $ME1);
        $G = $G + $E * 0.002472 * sin(2 * $ME1 + $MF - $MS - $MD);
        $G = $G + $E * 0.002222 * sin(2 * $ME1 + $MF - $MS);
        $G = $G + $E * 0.002072 * sin(2 * $ME1 - $MF - $MS - $MD);
        $G = $G + $E * 0.001877 * sin($MF - $MS + $MD) + 0.001828 * sin(4 * $ME1 - $MF - $MD);
        $G = $G - $E * 0.001803 * sin($MF + $MS) - 0.00175 * sin(3 * $MF);
        $G = $G + $E * 0.00157 * sin($MD - $MS - $MF) - 0.001487 * sin($MF + $ME1);
        $G = $G - $E * 0.001481 * sin($MF + $MS + $MD) + $E * 0.001417 * sin($MF - $MS - $MD);
        $G = $G + $E * 0.00135 * sin($MF - $MS) + 0.00133 * sin($MF - $ME1);
        $G = $G + 0.001106 * sin($MF + 3 * $MD) + 0.00102 * sin(4 * $ME1 - $MF);
        $G = $G + 0.000833 * sin($MF + 4 * $ME1 - $MD) + 0.000781 * sin($MD - 3 * $MF);
        $G = $G + 0.00067 * sin($MF + 4 * $ME1 - 2 * $MD) + 0.000606 * sin(2 * $ME1 - 3 * $MF);
        $G = $G + 0.000597 * sin(2 * ($ME1 + $MD) - $MF);
        $G = $G + $E * 0.000492 * sin(2 * $ME1 + $MD - $MS - $MF) + 0.00045 * sin(2 * ($MD - $ME1) - $MF);
        $G = $G + 0.000439 * sin(3 * $MD - $MF) + 0.000423 * sin($MF + 2 * ($ME1 + $MD));
        $G = $G + 0.000422 * sin(2 * $ME1 - $MF - 3 * $MD) - $E * 0.000367 * sin($MS + $MF + 2 * $ME1 - $MD);
        $G = $G - $E * 0.000353 * sin($MS + $MF + 2 * $ME1) + 0.000331 * sin($MF + 4 * $ME1);
        $G = $G + $E * 0.000317 * sin(2 * $ME1 + $MF - $MS + $MD);
        $G = $G + $E2 * 0.000306 * sin(2 * ($ME1 - $MS) - $MF) - 0.000283 * sin($MD + 3 * $MF);
        $W1 = 0.0004664 * cos($NA);
        $W2 = 0.0000754 * cos($C);
        $BM = $this->Radians($G) * (1 - $W1 - $W2);

        $MoonLat = $this->Degrees($BM);
        return $MoonLat;
    }

    protected function MoonNodeLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
    {
        $UT = $this->LctUT(intval($LH), intval($LM), intval($LS), intval($DS), floatval($ZC), intval($DY), intval($MN), intval($YR))['UTDec'];
        $GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
        $T = (($this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020) / 36525) + ($UT / 876600);
        $T2 = $T * $T;

        // $M1 = 27.32158213;
        // $M2 = 365.2596407;
        // $M3 = 27.55455094;
        // $M4 = 29.53058868;
        // $M5 = 27.21222039;
        $M6 = 6798.363307;
        $Q = $this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020 + ($UT / 24);
        // $M1 = $Q / $M1;
        // $M2 = $Q / $M2;
        // $M3 = $Q / $M3;
        // $M4 = $Q / $M4;
        // $M5 = $Q / $M5;
        $M6 = $Q / $M6;
        // $M1 = 360 * ($M1 - $this->roundvariantint($M1));
        // $M2 = 360 * ($M2 - $this->roundvariantint($M2));
        // $M3 = 360 * ($M3 - $this->roundvariantint($M3));
        // $M4 = 360 * ($M4 - $this->roundvariantint($M4));
        // $M5 = 360 * ($M5 - $this->roundvariantint($M5));
        $M6 = 360 * ($M6 - $this->roundvariantint($M6));

        // $ML = 270.434164 + $M1 - (0.001133 - 0.0000019 * $T) * $T2;
        // $MS = 358.475833 + $M2 - (0.00015 + 0.0000033 * $T) * $T2;
        // $MD = 296.104608 + $M3 + (0.009192 + 0.0000144 * $T) * $T2;
        // $ME1 = 350.737486 + $M4 - (0.001436 - 0.0000019 * $T) * $T2;
        // $MF = 11.250889 + $M5 - (0.003211 + 0.0000003 * $T) * $T2;
        $NA = 259.183275 - $M6 + (0.002078 + 0.0000022 * $T) * $T2;

        $MoonNodeLong = $NA;
        return $MoonNodeLong;
    }

    protected function NewMoon($DS, $ZC, $DY, $MN, $YR)
    {
        $D0 = $this->LctGDay(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
        $M0 = $this->LctGMonth(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
        $Y0 = $this->LctGYear(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);

        if ($Y0 < 0) {
            $Y0 = $Y0 + 1;
        }

        $J0 = $this->CDJD(0, 1, intval($Y0)) - 2415020;
        $DJ = $this->CDJD(floatval($D0), intval($M0), intval($Y0)) - 2415020;
        $K = $this->LINT((($Y0 - 1900 + (($DJ - $J0) / 365)) * 12.3685) + 0.5);
        $TN = $K / 1236.85;
        //  $TF = ($K + 0.5) / 1236.85;
        $T = $TN;
        $T2 = $T * $T;
        $E = 29.53 * $K;
        $C = 166.56 + (132.87 - 0.009173 * $T) * $T;
        $C = $this->Radians($C);
        $B = 0.00058868 * $K + (0.0001178 - 0.000000155 * $T) * $T2;
        $B = $B + 0.00033 * sin($C) + 0.75933;
        $A = $K / 12.36886;
        $A1 = 359.2242 + 360 * $this->FRACT($A) - (0.0000333 + 0.00000347 * $T) * $T2;
        $A2 = 306.0253 + 360 * $this->FRACT($K / 0.9330851);
        $A2 = $A2 + (0.0107306 + 0.00001236 * $T) * $T2;
        $A = $K / 0.9214926;
        $F = 21.2964 + 360 * $this->FRACT($A) - (0.0016528 + 0.00000239 * $T) * $T2;
        $A1 = $this->UnwindDeg($A1);
        $A2 = $this->UnwindDeg($A2);
        $F = $this->UnwindDeg($F);
        $A1 = $this->Radians($A1);
        $A2 = $this->Radians($A2);
        $F = $this->Radians($F);

        $DD = (0.1734 - 0.000393 * $T) * sin($A1) + 0.0021 * sin(2 * $A1);
        $DD = $DD - 0.4068 * sin($A2) + 0.0161 * sin(2 * $A2) - 0.0004 * sin(3 * $A2);
        $DD = $DD + 0.0104 * sin(2 * $F) - 0.0051 * sin($A1 + $A2);
        $DD = $DD - 0.0074 * sin($A1 - $A2) + 0.0004 * sin(2 * $F + $A1);
        $DD = $DD - 0.0004 * sin(2 * $F - $A1) - 0.0006 * sin(2 * $F + $A2) + 0.001 * sin(2 * $F - $A2);
        $DD = $DD + 0.0005 * sin($A1 + 2 * $A2);
        $E1 = $this->roundvariantint($E);
        $B = $B + $DD + ($E - $E1);
        $B1 = $this->roundvariantint($B);
        $A = $E1 + $B1;
        $B = $B - $B1;
        $NI = $A;
        $NF = $B;
        // $NB = $F;
        // $T = $TF;
        // $K = $K + 0.5;
        // $T2 = $T * $T;
        // $E = 29.53 * $K;
        // $C = 166.56 + (132.87 - 0.009173 * $T) * $T;
        // $C = $this->Radians($C);
        // $B = 0.00058868 * $K + (0.0001178 - 0.000000155 * $T) * $T2;
        // $B = $B + 0.00033 * sin($C) + 0.75933;
        // $A = $K / 12.36886;
        // $A1 = 359.2242 + 360 * $this->FRACT($A) - (0.0000333 + 0.00000347 * $T) * $T2;
        // $A2 = 306.0253 + 360 * $this->FRACT($K / 0.9330851);
        // $A2 = $A2 + (0.0107306 + 0.00001236 * $T) * $T2;
        // $A = $K / 0.9214926;
        // $F = 21.2964 + 360 * $this->FRACT($A) - (0.0016528 + 0.00000239 * $T) * $T2;
        // $A1 = $this->UnwindDeg($A1);
        //  $A2 = $this->UnwindDeg($A2);
        //  $F = $this->UnwindDeg($F);
        // $A1 = $this->Radians($A1);
        // $A2 = $this->Radians($A2);
        // $F = $this->Radians($F);

        // $DD = (0.1734 - 0.000393 * $T) * sin($A1) + 0.0021 * sin(2 * $A1);
        // $DD = $DD - 0.4068 * sin($A2) + 0.0161 * sin(2 * $A2) - 0.0004 * sin(3 * $A2);
        // $DD = $DD + 0.0104 * sin(2 * $F) - 0.0051 * sin($A1 + $A2);
        // $DD = $DD - 0.0074 * sin($A1 - $A2) + 0.0004 * sin(2 * $F + $A1);
        // $DD = $DD - 0.0004 * sin(2 * $F - $A1) - 0.0006 * sin(2 * $F + $A2) + 0.001 * sin(2 * $F - $A2);
        // $DD = $DD + 0.0005 * sin($A1 + 2 * $A2);
        // $E1 = $this->roundvariantint($E);
        // $B = $B + $DD + ($E - $E1);
        // $B1 = $this->roundvariantint($B);
        // $A = $E1 + $B1;
        // $B = $B - $B1;
        // $FI = $A;
        // $FF = $B;
        // $FB = $F;
        $NewMoon = $NI + 2415020 + $NF;
        return $NewMoon;
    }

    //$Y2 = altitude of the star in degrees, $W = 'true' or all strings without 'true', $PR = Atmospheric Pressure, $TR = Temperature
    protected function Refract($Y2, $SW, $PR, $TR)
    {
        $Y = $this->Radians($Y2);
        if (preg_match('/true/i', $SW) == 1) {
            $D = -1;
        } else {
            $D = 1;
        }

        if ($D == -1) {
            $Y3 = $Y;
            $Y1 = $Y;
            $R1 = 0;
            step1: //3020
            $Y = $Y1 + $R1;
            // $Q = $Y;
            if ($Y < 0.2617994) {
                if ($Y < -0.087) {
                    $Q = 0;
                    $RF = 0;
                    goto ends; //3075
                }

                $YD = $this->Degrees($Y);
                $A = ((0.00002 * $YD + 0.0196) * $YD + 0.1594) * $PR;
                $B = (273 + $TR) * ((0.0845 * $YD + 0.505) * $YD + 1);
                $RF = $this->Radians(-($A / $B) * $D);
            } else {
                $RF = -$D * 0.00007888888 * $PR / ((273 + $TR) * tan($Y));
            }
            $R2 = $RF;
            if (($R2 == 0) or (abs($R2 - $R1) < 0.000001)) {
                $Q = $Y3;
                goto ends; //3075
            }
            $R1 = $R2;
            goto step1; //3020
        } else {
            if ($Y < 0.2617994) {
                if ($Y < -0.087) {
                    $Q = 0;
                    $RF = 0;
                    goto ends; //3075
                }

                $YD = $this->Degrees($Y);
                $A = ((0.00002 * $YD + 0.0196) * $YD + 0.1594) * $PR;
                $B = (273 + $TR) * ((0.0845 * $YD + 0.505) * $YD + 1);
                $RF = $this->Radians(-($A / $B) * $D);
            } else {
                $RF = -$D * 0.00007888888 * $PR / ((273 + $TR) * tan($Y));
            }
            $Q = $Y;
            goto ends; //3075
        }

        ends: //3075
        $Refract = $this->Degrees($Q + $RF);
        return $Refract;
    }

    protected function SunMeanAnomaly($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
    {
        $AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $UT = $this->LctUT(intval($LCH), intval($LCM), intval($LCS), intval($DS), floatval($ZC), intval($LD), intval($LM), intval($LY))['UTDec'];
        $DJ = $this->CDJD(floatval($AA), intval($BB), intval($CC)) - 2415020;
        $T = ($DJ / 36525) + ($UT / 876600);
        $T2 = $T * $T;
        $A = 100.0021359 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M1 = 358.47583 - (0.00015 + 0.0000033 * $T) * $T2 + $B;
        $AM = $this->Unwind($this->Radians($M1));
        $SunMeanAnomaly = $AM;
        return $SunMeanAnomaly;
    }

    protected function SunLong($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
    {
        $AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $UT = $this->LctUT(intval($LCH), intval($LCM), intval($LCS), intval($DS), floatval($ZC), intval($LD), intval($LM), intval($LY))['UTDec'];
        $DJ = $this->CDJD(floatval($AA), intval($BB), intval($CC)) - 2415020;
        $T = ($DJ / 36525) + ($UT / 876600);
        $T2 = $T * $T;
        $A = 100.0021359 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $L = 279.69668 + 0.0003025 * $T2 + $B;
        $A = 99.99736042 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M1 = 358.47583 - (0.00015 + 0.0000033 * $T) * $T2 + $B;
        $EC = 0.01675104 - 0.0000418 * $T - 0.000000126 * $T2;

        $AM = $this->Radians($M1);
        $AT = $this->TrueAnomaly($AM, $EC);
        //  $AE = $this->EccentricAnomaly($AM, $EC);

        $A = 62.55209472 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $A1 = $this->Radians(153.23 + $B);
        $A = 125.1041894 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $B1 = $this->Radians(216.57 + $B);
        $A = 91.56766028 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $C1 = $this->Radians(312.69 + $B);
        $A = 1236.853095 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $D1 = $this->Radians(350.74 - 0.00144 * $T2 + $B);
        $E1 = $this->Radians(231.19 + 20.2 * $T);
        // $A = 183.1353208 * $T;
        // $B = 360 * ($A - $this->roundvariantint($A));
        // $H1 = $this->Radians(353.4 + $B);

        $D2 = 0.00134 * cos($A1) + 0.00154 * cos($B1) + 0.002 * cos($C1);
        $D2 = $D2 + 0.00179 * sin($D1) + 0.00178 * sin($E1);
        // $D3 = 0.00000543 * sin($A1) + 0.00001575 * sin($B1);
        // $D3 = $D3 + 0.00001627 * sin($C1) + 0.00003076 * cos($D1);
        // $D3 = $D3 + 0.00000927 * sin($H1);

        $SR = $AT + $this->Radians($L - $M1 + $D2);
        $TP = 6.283185308;
        $SR = $SR - $TP * $this->roundvariantint($SR / $TP);
        $SunLong = $this->Degrees($SR);
        return $SunLong;
    }

    protected function SunDist($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
    {
        $AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $UT = $this->LctUT(intval($LCH), intval($LCM), intval($LCS), intval($DS), floatval($ZC), intval($LD), intval($LM), intval($LY))['UTDec'];
        $DJ = $this->CDJD(floatval($AA), intval($BB), intval($CC)) - 2415020;
        $T = ($DJ / 36525) + ($UT / 876600);
        $T2 = $T * $T;
        // $A = 100.0021359 * $T;
        // $B = 360 * ($A - $this->roundvariantint($A));
        // $L = 279.69668 + 0.0003025 * $T2 + $B;
        $A = 99.99736042 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M1 = 358.47583 - (0.00015 + 0.0000033 * $T) * $T2 + $B;
        $EC = 0.01675104 - 0.0000418 * $T - 0.000000126 * $T2;

        $AM = $this->Radians($M1);
        // $AT = $this->TrueAnomaly($AM, $EC);
        $AE = $this->EccentricAnomaly($AM, $EC);

        $A = 62.55209472 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $A1 = $this->Radians(153.23 + $B);
        $A = 125.1041894 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $B1 = $this->Radians(216.57 + $B);
        $A = 91.56766028 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $C1 = $this->Radians(312.69 + $B);
        $A = 1236.853095 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $D1 = $this->Radians(350.74 - 0.00144 * $T2 + $B);
        // $E1 = $this->Radians(231.19 + 20.2 * $T);
        $A = 183.1353208 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $H1 = $this->Radians(353.4 + $B);

        // $D2 = 0.00134 * cos($A1) + 0.00154 * cos($B1) + 0.002 * cos($C1);
        // $D2 = $D2 + 0.00179 * sin($D1) + 0.00178 * sin($E1);
        $D3 = 0.00000543 * sin($A1) + 0.00001575 * sin($B1);
        $D3 = $D3 + 0.00001627 * sin($C1) + 0.00003076 * cos($D1);
        $D3 = $D3 + 0.00000927 * sin($H1);

        $RR = 1.0000002 * (1 - $EC * cos($AE)) + $D3;
        $SunDist = $RR;
        return $SunDist;
    }

    protected function SunTrueAnomaly($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
    {
        $AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
        $UT = $this->LctUT(intval($LCH), intval($LCM), intval($LCS), intval($DS), floatval($ZC), intval($LD), intval($LM), intval($LY))['UTDec'];
        $DJ = $this->CDJD(floatval($AA), intval($BB), intval($CC)) - 2415020;
        $T = ($DJ / 36525) + ($UT / 876600);
        $T2 = $T * $T;
        // $A = 100.0021359 * $T;
        // $B = 360 * ($A - $this->roundvariantint($A));
        // $L = 279.69668 + 0.0003025 * $T2 + $B;
        $A = 99.99736042 * $T;
        $B = 360 * ($A - $this->roundvariantint($A));
        $M1 = 358.47583 - (0.00015 + 0.0000033 * $T) * $T2 + $B;
        $EC = 0.01675104 - 0.0000418 * $T - 0.000000126 * $T2;

        $AM = $this->Radians($M1);
        $SunTrueAnomaly = $this->Degrees($this->TrueAnomaly($AM, $EC));
        return $SunTrueAnomaly;
    }

    protected function SunEcc($GD, $GM, $GY)
    {
        $T = ($this->CDJD(floatval($GD), intval($GM), intval($GY)) - 2415020) / 36525;
        $T2 = $T * $T;
        $SunEcc = 0.01675104 - 0.0000418 * $T - 0.000000126 * $T2;
        return $SunEcc;
    }

    protected function TrueAnomaly($AM, $EC)
    {
        $TP = 6.283185308;
        $M = $AM - $TP * $this->roundvariantint($AM / $TP);
        $AE = $M;
        step1: //3305
        $D = $AE - ($EC * sin($AE)) - $M;

        if (abs($D) < 0.000001) {
            goto step2; //3320
        }

        $D = $D / (1 - ($EC * cos($AE)));
        $AE = $AE - $D;
        goto step1; //3305

        step2: //3320
        $A = sqrt((1 + $EC) / (1 - $EC)) * tan($AE / 2);
        $AT = 2 * atan($A);
        $TrueAnomaly = $AT;
        return $TrueAnomaly;
    }

    protected function EccentricAnomaly($AM, $EC)
    {
        $TP = 6.283185308;
        $M = $AM - $TP * $this->roundvariantint($AM / $TP);
        $AE = $M;
        step1: //3305
        $D = $AE - ($EC * sin($AE)) - $M;

        if (abs($D) < 0.000001) {
            goto step2; //3320
        }

        $D = $D / (1 - ($EC * cos($AE)));
        $AE = $AE - $D;
        goto step1; //3305

        step2: //3320
        $EccentricAnomaly = $AE;
        return $EccentricAnomaly;
    }

    //Funktion um zu Prüfen ob der UNIX Time Stamp heute ist
    protected function isToday($time)
    {
        $begin = mktime(0, 0, 0);
        $end = mktime(23, 59, 59);
        // check if given time is between begin and end
        if ($time >= $begin && $time <= $end)
            return true;
        else
            return false;
    }

    protected function moon_phase($year, $month, $day)
    {
        /*    modified from http://www.voidware.com/moon_phase.htm    */
        // $c = $e = $jd = $b = 0;
        if ($month < 3) {
            $year--;
            $month += 12;
        }
        $month++;
        $c = 365.25 * $year;
        $e = 30.6 * $month;
        $jd = $c + $e + $day - 694039.09;    //jd is total days elapsed
        $jd /= 29.5305882;                    //divide by the moon cycle
        $b = (int) $jd;                        //int(jd) -> b, take integer part of jd
        $jd -= $b;                            //subtract integer part to leave fractional part of original jd
        $b = round($jd * 8);                //scale fraction from 0-8 and round
        if ($b >= 8) {
            $b = 0; //0 and 8 are the same so turn 8 into 0
        }
        switch ($b) {
            case 0:
                return 'Neumond';
                break;

            case 1:
                return 'erstes Viertel';
                break;

            case 2:
                return 'zunehmender Halbmond';
                break;

            case 3:
                return 'zweites Viertel';
                break;

            case 4:
                return 'Vollmond';
                break;

            case 5:
                return 'drittes Viertel';
                break;

            case 6:
                return 'abnehmender Vollmond';
                break;

            case 7:
                return 'letztes Viertel';
                break;

            default:
                return 'Error';
        }
    }

    protected function CalculateMoonphase($year)
    {
        // ============================================================
        //
        // Phasen:    phase = 0 für Neumond
        //            phase = 0.25 für erstes Viertel
        //            phase = 0.5 für Vollmond
        //            phase = 0.75 für letztes Viertel
        //            Für Werte anders als 0, 0.25, 0.5 oder 0.75 ist nachstehendes Script ungültig.
        // Angabe des Zeitpunktes als Fließkomma-Jahreszahl
        // Bsp.: 1.8.2006 = ca. 2006.581
        //
        // Ergebnis: $JDE
        // ============================================================

        $rads = 3.14159265359 / 180;

        $moondate = [];
        $i = 0;

        for ($phase = 0; $phase < 1; $phase += 0.25) {
            // Anzahl der Mondphasen seit 2000
            $k = floor(($year - 2000) * 12.36853087) + $phase;
            // Mittlerer JDE Wert des Ereignisses
            $JDE = 2451550.09766 + 29.530588861 * $k;
            // Relevante Winkelwerte in [Radiant]
            $M = (2.5534 + 29.10535670 * $k) * $rads;
            $Ms = (201.5643 + 385.81693528 * $k) * $rads;
            $F = (160.7108 + 390.67050284 * $k) * $rads;

            if ($phase == 0) {
                // Korrekturterme JDE für Neumond
                $JDE += -0.40720 * sin($Ms);
                $JDE += 0.17241 * sin($M);
                $JDE += 0.01608 * sin(2 * $Ms);
                $JDE += 0.01039 * sin(2 * $F);
                $JDE += 0.00739 * sin($Ms - $M);
                $JDE += -0.00514 * sin($Ms + $M);
                $JDE += 0.00208 * sin(2 * $M);
                $JDE += -0.00111 * sin($Ms - 2 * $F);
            } elseif ($phase == 0.5) {
                // Korrekturterme JDE für Vollmond
                $JDE += -0.40614 * sin($Ms);
                $JDE += 0.17302 * sin($M);
                $JDE += 0.01614 * sin(2 * $Ms);
                $JDE += 0.01043 * sin(2 * $F);
                $JDE += 0.00734 * sin($Ms - $M);
                $JDE += -0.00515 * sin($Ms + $M);
                $JDE += 0.00209 * sin(2 * $M);
                $JDE += -0.00111 * sin($Ms - 2 * $F);
            }

            if ($phase == 0.25 || $phase == 0.75) {
                // Korrekturterme für JDE für das  1. bzw. letzte Viertel
                $JDE += -0.62801 * sin($Ms);
                $JDE += 0.17172 * sin($M);
                $JDE += -0.01183 * sin($Ms + $M);
                $JDE += 0.00862 * sin(2 * $Ms);
                $JDE += 0.00804 * sin(2 * $F);
                $JDE += 0.00454 * sin($Ms - $M);
                $JDE += 0.00204 * sin(2 * $M);
                $JDE += -0.00180 * sin($Ms - 2 * $F);

                // Weiterer Korrekturterm für Viertelphasen
                if ($phase == 0.25) {
                    $JDE += 0.00306;
                } else {
                    $JDE += -0.00306;
                }
            }

            // Konvertierung von Julianischem Datum auf Gregorianisches Datum
            $z = floor($JDE + 0.5);
            $f = ($JDE + 0.5) - floor($JDE + 0.5);
            if ($z < 2299161) {
                $a = $z;
            } else {
                $g = floor(($z - 1867216.25) / 36524.25);
                $a = $z + 1 + $g - floor($g / 4);
            }
            $b = $a + 1524;
            $c = floor(($b - 122.1) / 365.25);
            $d = floor(365.25 * $c);
            $e = floor(($b - $d) / 30.6001);

            $tag_temp = $b - $d - floor(30.6001 * $e) + $f; //Tag incl. Tagesbruchteilen
            $stunde_temp = ($tag_temp - floor($tag_temp)) * 24;
            $minute_temp = ($stunde_temp - floor($stunde_temp)) * 60;

            $stunde = floor($stunde_temp);
            $minute = floor($minute_temp);
            $sekunde = round(($minute_temp - floor($minute_temp)) * 60);

            $tag = floor($tag_temp);

            if ($e < 14) {
                $monat = $e - 1;
            } else {
                $monat = $e - 13;
            }
            if ($monat > 2) {
                $jahr = $c - 4716;
            } else {
                $jahr = $c - 4715;
            }

            $sommerzeit = date('I');
            if ($sommerzeit == 0) {
                $datum = mktime(intval($stunde), intval($minute), intval($sekunde + 3600), intval($monat), intval($tag), intval($jahr));
            } else {
                $datum = mktime(intval($stunde), intval($minute), intval($sekunde + 7200), intval($monat), intval($tag), intval($jahr));
            }

            switch ($phase) {
                case 0:
                    $phasename = 'Neumond';
                    break;
                case 0.25:
                    $phasename = 'erstes Viertel';
                    break;
                case 0.5:
                    $phasename = 'Vollmond';
                    break;
                case 0.75:
                    $phasename = 'letztes Viertel';
                    break;
                default:
                    $phasename = 'Neumond';
                    break;
            }

            $date = date('D', ($datum));
            $wt = 'Mo';
            if ($date == 'Mon') {
                $wt = 'Mo';
            } elseif ($date == 'Tue') {
                $wt = 'Di';
            } elseif ($date == 'Wed') {
                $wt = 'Mi';
            } elseif ($date == 'Thu') {
                $wt = 'Do';
            } elseif ($date == 'Fri') {
                $wt = 'Fr';
            } elseif ($date == 'Sat') {
                $wt = 'Sa';
            } elseif ($date == 'Sun') {
                $wt = 'So';
            }

            $timeformat = $this->GetTimeformat();
            $moontime = date($timeformat, $datum);
            $date = date('d.m.Y', $datum);
            $moondate[$i] = ['name' => $phasename, 'date' => $date, 'weekday' => $wt, 'time' => $moontime];
            $i++;
        }

        $newmoonstring = $moondate[0]['weekday'] . ', ' . $moondate[0]['date'] . ' ' . $moondate[0]['time'];
        $firstquarterstring = $moondate[1]['weekday'] . ', ' . $moondate[1]['date'] . ' ' . $moondate[1]['time'];
        $fullmoonstring = $moondate[2]['weekday'] . ', ' . $moondate[2]['date'] . ' ' . $moondate[2]['time'];
        $lastquarterstring = $moondate[3]['weekday'] . ', ' . $moondate[3]['date'] . ' ' . $moondate[3]['time'];

        $moonphase = ['newmoon' => $newmoonstring, 'firstquarter' => $firstquarterstring, 'fullmoon' => $fullmoonstring, 'lastquarter' => $lastquarterstring, 'moondate' => $moondate];
        return $moonphase;
    }

    public function MoonphasePercent()
    {
        // Formel nach http://www.die-seite.eu/wm-mondphasen.php

        $ursprung = mktime(19, 19, 54, 02, 22, 2016);
        $akt_date = time(); //mktime(18,19,54,04,24,2016);//
        $mondphase = round(((($akt_date - $ursprung) / (floor(29.530588861 * 86400))) - floor(($akt_date - $ursprung) / (floor(29.530588861 * 86400)))) * 100, 0);

        return $mondphase;
    }

    public function MoonphaseText()
    {
        $mondphase = $this->MoonphasePercent();
        $picture = $this->GetMoonPicture($mondphase);
        $phase = $picture['phase'];
        if ($this->ReadPropertyBoolean('moonphase') == true) {
            $this->SetValue('moonphase', $phase . ' - ' . $mondphase . '%');
        }
        $phasetext = ['moonphasetext' => $phase, 'moonphasepercent' => $mondphase];
        return $phasetext;
    }

    public function Moon_FirstQuarter()
    {
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $firstquarter = $moonphase['firstquarter'];
        $firstquarterdate = $moonphase['moondate'][1]['date'];
        $firstquartertime = $moonphase['moondate'][1]['time'];

        $ispast = $this->CompareDateWithToday($firstquarterdate);
        if ($ispast) {
            $year = $this->GetNextPhase();
            $nextmoonphase = $this->CalculateMoonphase($year);
            $nextfirstquarter = $nextmoonphase['firstquarter'];
            $nextfirstquarterdate = $nextmoonphase['moondate'][1]['date'];
            $nextfirstquartertime = $nextmoonphase['moondate'][1]['time'];
            return ['firstquarter' => $nextfirstquarter, 'firstquarterdate' => $nextfirstquarterdate, 'firstquartertime' => $nextfirstquartertime];
        } else {
            return ['firstquarter' => $firstquarter, 'firstquarterdate' => $firstquarterdate, 'firstquartertime' => $firstquartertime];
        }
    }

    public function Moon_Newmoon()
    {
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $newmoon = $moonphase['newmoon'];
        $newmoondate = $moonphase['moondate'][0]['date'];
        $newmoontime = $moonphase['moondate'][0]['time'];

        $ispast = $this->CompareDateWithToday($newmoondate);
        if ($ispast) {
            $year = $this->GetNextPhase();
            $nextmoonphase = $this->CalculateMoonphase($year);
            $nextnewmoon = $nextmoonphase['newmoon'];
            $nextnewmoondate = $nextmoonphase['moondate'][0]['date'];
            $nextnewmoontime = $nextmoonphase['moondate'][0]['time'];
            return ['newmoon' => $nextnewmoon, 'newmoondate' => $nextnewmoondate, 'newmoontime' => $nextnewmoontime];
        } else {
            return ['newmoon' => $newmoon, 'newmoondate' => $newmoondate, 'newmoontime' => $newmoontime];
        }
    }

    public function Moon_Fullmoon()
    {
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $fullmoon = $moonphase['fullmoon'];
        $fullmoondate = $moonphase['moondate'][2]['date'];
        $fullmoontime = $moonphase['moondate'][2]['time'];

        $ispast = $this->CompareDateWithToday($fullmoondate);
        if ($ispast) {
            $year = $this->GetNextPhase();
            $nextmoonphase = $this->CalculateMoonphase($year);
            $nextfullmoon = $nextmoonphase['fullmoon'];
            $nextfullmoondate = $nextmoonphase['moondate'][2]['date'];
            $nextfullmoontime = $nextmoonphase['moondate'][2]['time'];
            return ['fullmoon' => $nextfullmoon, 'fullmoondate' => $nextfullmoondate, 'fullmoontime' => $nextfullmoontime];
        } else {
            return ['fullmoon' => $fullmoon, 'fullmoondate' => $fullmoondate, 'fullmoontime' => $fullmoontime];
        }
    }

    public function Moon_LastQuarter()
    {
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $lastquarter = $moonphase['lastquarter'];
        $lastquarterdate = $moonphase['moondate'][3]['date'];
        $lastquartertime = $moonphase['moondate'][3]['time'];

        $ispast = $this->CompareDateWithToday($lastquarterdate);
        if ($ispast) {
            $year = $this->GetNextPhase();
            $nextmoonphase = $this->CalculateMoonphase($year);
            $nextlastquarter = $nextmoonphase['lastquarter'];
            $nextlastquarterdate = $nextmoonphase['moondate'][3]['date'];
            $nextlastquartertime = $nextmoonphase['moondate'][3]['time'];
            return ['lastquarter' => $nextlastquarter, 'lastquarterdate' => $nextlastquarterdate, 'lastquartertime' => $nextlastquartertime];
        } else {
            return ['lastquarter' => $lastquarter, 'lastquarterdate' => $lastquarterdate, 'lastquartertime' => $lastquartertime];
        }
    }

    public function Moon_CurrentFirstQuarter()
    {
        // aktuelles Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $firstquarter = $moonphase['firstquarter'];
        $firstquarterdate = $moonphase['moondate'][1]['date'];
        $firstquartertime = $moonphase['moondate'][1]['time'];
        return ['firstquarter' => $firstquarter, 'firstquarterdate' => $firstquarterdate, 'firstquartertime' => $firstquartertime];
    }

    public function Moon_CurrentNewmoon()
    {
        // aktuelles Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $newmoon = $moonphase['newmoon'];
        $newmoondate = $moonphase['moondate'][0]['date'];
        $newmoontime = $moonphase['moondate'][0]['time'];
        return ['newmoon' => $newmoon, 'newmoondate' => $newmoondate, 'newmoontime' => $newmoontime];
    }

    public function Moon_CurrentFullmoon()
    {
        // aktuelles Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $fullmoon = $moonphase['fullmoon'];
        $fullmoondate = $moonphase['moondate'][2]['date'];
        $fullmoontime = $moonphase['moondate'][2]['time'];
        return ['fullmoon' => $fullmoon, 'fullmoondate' => $fullmoondate, 'fullmoontime' => $fullmoontime];
    }

    public function Moon_CurrentLastQuarter()
    {
        // aktuelles Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z') - 1) / (365 + (date('L'))) + date('Y');
        $moonphase = $this->CalculateMoonphase($year);
        $lastquarter = $moonphase['lastquarter'];
        $lastquarterdate = $moonphase['moondate'][3]['date'];
        $lastquartertime = $moonphase['moondate'][3]['time'];
        return ['lastquarter' => $lastquarter, 'lastquarterdate' => $lastquarterdate, 'lastquartertime' => $lastquartertime];
    }

    public function Moon_FirstQuarterDate(string $date)
    {
        $timestamp = strtotime($date);
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z', $timestamp) - 1) / (365 + (date('L', $timestamp))) + date('Y', $timestamp);
        $moonphase = $this->CalculateMoonphase($year);
        $firstquarter = $moonphase['firstquarter'];
        $firstquarterdate = $moonphase['moondate'][1]['date'];
        $firstquartertime = $moonphase['moondate'][1]['time'];
        return ['firstquarter' => $firstquarter, 'firstquarterdate' => $firstquarterdate, 'firstquartertime' => $firstquartertime];
    }

    public function Moon_NewmoonDate(string $date)
    {
        $timestamp = strtotime($date);
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z', $timestamp) - 1) / (365 + (date('L', $timestamp))) + date('Y', $timestamp);
        $moonphase = $this->CalculateMoonphase($year);
        $newmoon = $moonphase['newmoon'];
        $newmoondate = $moonphase['moondate'][0]['date'];
        $newmoontime = $moonphase['moondate'][0]['time'];
        return ['newmoon' => $newmoon, 'newmoondate' => $newmoondate, 'newmoontime' => $newmoontime];
    }

    public function Moon_FullmoonDate(string $date)
    {
        $timestamp = strtotime($date);
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z', $timestamp) - 1) / (365 + (date('L', $timestamp))) + date('Y', $timestamp);
        $moonphase = $this->CalculateMoonphase($year);
        $fullmoon = $moonphase['fullmoon'];
        $fullmoondate = $moonphase['moondate'][2]['date'];
        $fullmoontime = $moonphase['moondate'][2]['time'];
        return ['fullmoon' => $fullmoon, 'fullmoondate' => $fullmoondate, 'fullmoontime' => $fullmoontime];
    }

    public function Moon_LastQuarterDate(string $date)
    {
        $timestamp = strtotime($date);
        // Datum in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + date('z', $timestamp) - 1) / (365 + (date('L', $timestamp))) + date('Y', $timestamp);
        $moonphase = $this->CalculateMoonphase($year);
        $lastquarter = $moonphase['lastquarter'];
        $lastquarterdate = $moonphase['moondate'][3]['date'];
        $lastquartertime = $moonphase['moondate'][3]['time'];
        return ['lastquarter' => $lastquarter, 'lastquarterdate' => $lastquarterdate, 'lastquartertime' => $lastquartertime];
    }

    protected function CompareDateWithToday($datetocompare)
    {
        $datetimetoday = new DateTime(date('d.m.Y', time()));
        $datetimecompare = new DateTime($datetocompare);
        $interval = $datetimetoday->diff($datetimecompare);
        $daydifference = intval($interval->format('%R%a')); // int
        if ($daydifference >= 0) // present or future
        {
            return false;
        } else // past
        {
            return true;
        }
    }

    protected function GetNextPhase()
    {
        $currentnewmoon = $this->Moon_CurrentNewmoon();
        $currentnewmoondate = $currentnewmoon['newmoondate'];
        $datetimenewmoon = new DateTime($currentnewmoondate);
        $datetimenewmoon->add(new DateInterval('P30D'));
        // Datum für nächsten Zyklus in Jahre umrechnen
        $year = ((((((date('s') / 60) + date('i')) / 60) + date('G')) / 24) + $datetimenewmoon->format('z') - 1) / (365 + ($datetimenewmoon->format('L'))) + $datetimenewmoon->format('Y');
        return $year;
    }

    public function GetMoonPicture(float $mondphase)
    {
        $language = $this->ReadPropertyInteger('language');
        $picturemoonselection = $this->ReadPropertyBoolean('picturemoonselection');
        if ($picturemoonselection) {
            $firstfullmoonpic = $this->ReadPropertyInteger('firstfullmoonpic');
            $lastfullmoonpic = $this->ReadPropertyInteger('lastfullmoonpic');
            $firstincreasingmoonpic = $this->ReadPropertyInteger('firstincreasingmoonpic');
            $lastincreasingmoonpic = $this->ReadPropertyInteger('lastincreasingmoonpic');
            $firstnewmoonpic = $this->ReadPropertyInteger('firstnewmoonpic');
            $lastnewmoonpic = $this->ReadPropertyInteger('lastnewmoonpic');
            $firstdecreasingmoonpic = $this->ReadPropertyInteger('firstdecreasingmoonpic');
            $lastdecreasingmoonpic = $this->ReadPropertyInteger('lastdecreasingmoonpic');
        } else {
            $firstfullmoonpic = 172;
            $lastfullmoonpic = 182;
            $firstincreasingmoonpic = 183;
            $lastincreasingmoonpic = 352;
            $firstnewmoonpic = 353;
            $lastnewmoonpic = 362;
            $firstdecreasingmoonpic = 8;
            $lastdecreasingmoonpic = 171;
        }
        if ($mondphase <= 1 || $mondphase >= 99)  //--Vollmond
        {
            if ($language == 1) {
                $phase_text = 'Vollmond';
            } else {
                $phase_text = 'full moon';
            }
            if ($picturemoonselection) {
                if ($mondphase >= 99) {
                    $pic = $this->rescale([99, 100], [$firstfullmoonpic, $lastfullmoonpic]); // ([Mondphasen von,bis],[Bildnummern von,bis])
                } else {
                    $pic = $this->rescale([0, 1], [$firstfullmoonpic, $lastfullmoonpic]);
                }
            } else {
                if ($mondphase >= 99) {
                    $pic = $this->rescale([99, 100], [172, 177]); // ([Mondphasen von,bis],[Bildnummern von,bis])
                } else {
                    $pic = $this->rescale([0, 1], [178, 182]);
                }
            }
            $pic_n = floor($pic($mondphase));
            if ($pic_n < 10) {
                $pic_n = '00' . $pic_n;
            } elseif ($pic_n < 100) {
                $pic_n = '0' . $pic_n;
            }
        } elseif ($mondphase > 1 && $mondphase < 49) {  //--abnehmender Mond
            if ($language == 1) {
                $phase_text = 'abnehmender Mond';
            } else {
                $phase_text = 'decreasing moon';
            }
            $pic = $this->rescale([2, 48], [$firstincreasingmoonpic, $lastincreasingmoonpic]);
            $pic_n = floor($pic($mondphase));
            if ($pic_n < 10) {
                $pic_n = '00' . $pic_n;
            } elseif ($pic_n < 100) {
                $pic_n = '0' . $pic_n;
            }
        } elseif ($mondphase >= 49 && $mondphase <= 51) {  //--Neumond
            if ($language == 1) {
                $phase_text = 'Neumond';
            } else {
                $phase_text = 'new moon';
            }
            $pic = $this->rescale([49, 51], [$firstnewmoonpic, $lastnewmoonpic]);
            $pic_n = floor($pic($mondphase));
            if ($pic_n < 10) {
                $pic_n = '00' . $pic_n;
            } elseif ($pic_n < 100) {
                $pic_n = '0' . $pic_n;
            }
        } else {  //--zunehmender Mond
            if ($language == 1) {
                $phase_text = 'zunehmender Mond';
            } else {
                $phase_text = 'increasing moon';
            }
            $pic = $this->rescale([52, 98], [$firstdecreasingmoonpic, $lastdecreasingmoonpic]);
            $pic_n = floor($pic($mondphase));
            if ($pic_n < 10) {
                $pic_n = '00' . $pic_n;
            } elseif ($pic_n < 100) {
                $pic_n = '0' . $pic_n;
            }
        }

        $picture = ['picid' => $pic_n, 'phase' => $phase_text];
        return $picture;
    }

    protected function rescale($ab, $cd) //--Funktion zum anpassen der Mondphase 0-100 an Bildnummer 001-362 (Bilder der Seite http://www.avgoe.de)
    {
        list($a1, $b1) = $ab;
        list($c1, $d1) = $cd;
        if ($a1 == $b1) {
            trigger_error('Invalid scale', E_USER_WARNING);
            return false;
        }
        $o = ($b1 * $c1 - $a1 * $d1) / ($b1 - $a1);
        $s = ($d1 - $c1) / ($b1 - $a1);
        return function ($x) use ($o, $s) {
            return $s * $x + $o;
        };
    }

    // Berechnung der Mondauf/untergangs Zeiten
    public function Mondaufgang()
    {
        $month = date('m');
        $day = date('d');
        $year = date('Y');
        $location = $this->getlocation();
        $latitude = $location['Latitude'];
        $longitude = $location['Longitude'];
        $data = (Moon::calculateMoonTimes($month, $day, $year, $latitude, $longitude));

        $moonrise = $data->{'moonrise'}; //Aufgang
        $timeformat = $this->GetTimeformat();
        $moonrisedate = date('d.m.Y', $moonrise);
        $moonrisetime = date($timeformat, $moonrise);
        if ($this->ReadPropertyBoolean('moonrise') == true) // float
        {
            $this->SetValue('moonrise', $moonrise);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $this->SetValue('moonrisedate', $moonrisedate);
                $this->SetValue('moonrisetime', $moonrisetime);
            }
        }
        $moonrisedata = ['moonrisedate' => $moonrisedate, 'moonrisetime' => $moonrisetime];
        return $moonrisedata;
    }

    public function Monduntergang()
    {
        $month = date('m');
        $day = date('d');
        $year = date('Y');
        $location = $this->getlocation();
        $latitude = $location['Latitude'];
        $longitude = $location['Longitude'];
        $data = (Moon::calculateMoonTimes($month, $day, $year, $latitude, $longitude));

        $moonset = $data->{'moonset'}; //Untergang
        $timeformat = $this->GetTimeformat();
        $moonsetdate = date('d.m.Y', $moonset);
        $moonsettime = date($timeformat, $moonset);
        if ($this->ReadPropertyBoolean('moonset') == true) // float
        {
            $this->SetValue('moonset', $moonset);
            if ($this->ReadPropertyBoolean('extinfoselection') == true) // float
            {
                $this->SetValue('moonsetdate', $moonsetdate);
                $this->SetValue('moonsettime', $moonsettime);
            }
        }
        $moonsetdata = ['moonsetdate' => $moonsetdate, 'moonsettime' => $moonsettime];
        return $moonsetdata;
    }

    // ------------------------------

    //Profile

    /**
     * register profiles.
     *
     * @param $Name
     * @param $Icon
     * @param $Prefix
     * @param $Suffix
     * @param $MinValue
     * @param $MaxValue
     * @param $StepSize
     * @param $Digits
     * @param $Vartype
     */
    protected function RegisterProfile($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Vartype)
    {

        if (!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, $Vartype); // 0 boolean, 1 int, 2 float, 3 string,
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != $Vartype) {
                $this->SendDebug('profile', 'Variable profile type does not match for profile ' . $Name, 0);
            }
        }

        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
        if ($Vartype != VARIABLETYPE_STRING) {
            IPS_SetVariableProfileDigits($Name, $Digits); //  Nachkommastellen
            IPS_SetVariableProfileValues(
                $Name, $MinValue, $MaxValue, $StepSize
            ); // string $ProfilName, float $Minimalwert, float $Maximalwert, float $Schrittweite
        }
    }

    /**
     * register profile association.
     *
     * @param $Name
     * @param $Icon
     * @param $Prefix
     * @param $Suffix
     * @param $MinValue
     * @param $MaxValue
     * @param $Stepsize
     * @param $Digits
     * @param $Vartype
     * @param $Associations
     */
    protected function RegisterProfileAssociation($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Vartype, $Associations)
    {
        if (is_array($Associations) && count($Associations) === 0) {
            $MinValue = 0;
            $MaxValue = 0;
        }
        $this->RegisterProfile($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Vartype);

        if (is_array($Associations)) {
            foreach ($Associations as $Association) {
                IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
            }
        } else {
            $Associations = $this->$Associations;
            foreach ($Associations as $code => $association) {
                IPS_SetVariableProfileAssociation($Name, $code, $this->Translate($association), $Icon, -1);
            }
        }

    }

    /***********************************************************
     * Configuration Form
     ***********************************************************/

    /**
     * build configuration form.
     *
     * @return string
     */
    public function GetConfigurationForm()
    {
        // return current form
        return json_encode([
            'elements' => $this->FormHead(),
            'actions'  => $this->FormActions(),
            'status'   => $this->FormStatus()
        ]);
    }

    /**
     * return form configurations on configuration step.
     *
     * @return array
     */
    protected function FormHead()
    {
        $form = [
            [
                'type'  => 'Image',
                'image' => 'data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAALIAAABkCAIAAACtj/0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAxRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OUYzNEQ1QUYyRTAyMTFFOThDODlEQTQzNzI5RTZFRDAiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OUYzNEQ1QUUyRTAyMTFFOThDODlEQTQzNzI5RTZFRDAiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgV2luZG93cyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJEMzZDMzNGMTM3RTAxMDM0M0IwM0I1QUFGRjY4MEVGQSIgc3RSZWY6ZG9jdW1lbnRJRD0iRDM2QzMzRjEzN0UwMTAzNDNCMDNCNUFBRkY2ODBFRkEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6V9qdlAABkBklEQVR42ny9WY8lSZYeZotvd40tI/eqXqq7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'
            ],
            [
                'type'    => 'Label',
                'caption' => 'Astronomy values'
            ],
            [
                'type'    => 'ExpansionPanel',
                'caption' => 'Display language Webfront',
                'items'   => [
                    [
                        'type'    => 'Label',
                        'caption' => 'Display language Webfront:'
                    ],
                    [
                        'type'    => 'Select',
                        'name'    => 'language',
                        'caption' => 'language',
                        'options' => [
                            [
                                'label' => 'German',
                                'value' => 1
                            ],
                            [
                                'label' => 'English',
                                'value' => 2
                            ]
                        ]

                    ]
                ]
            ]
        ];
        $form = array_merge_recursive(
            $form,
            [
                [
                    'type'    => 'ExpansionPanel',
                    'caption' => 'Update Interval:',
                    'items'   => [
                        [
                            'type'    => 'NumberSpinner',
                            'name'    => 'Updateinterval',
                            'caption' => 'Seconds',
                            'suffix'  => 'seconds'
                        ]
                    ]
                ]
            ]
        );
        $UTC = $this->ReadPropertyFloat('UTC');
        $form = array_merge_recursive(
            $form,
            [
                [
                    'type'    => 'ExpansionPanel',
                    'caption' => 'Coordinated Universal Time (UTC):',
                    'items'   => $this->FormUTCText($UTC)
                ]
            ]
        );
        $form = array_merge_recursive(
            $form,
            [
                [
                    'type'    => 'ExpansionPanel',
                    'caption' => 'select values for display',
                    'items'   => [
                        [
                            'type'    => 'Label',
                            'caption' => 'select values for display:'
                        ],
                        [
                            'name'    => 'juliandate',
                            'type'    => 'CheckBox',
                            'caption' => 'Julian Date'
                        ],
                        [
                            'name'    => 'moonazimut',
                            'type'    => 'CheckBox',
                            'caption' => 'moon azimut'
                        ],
                        [
                            'name'    => 'moondistance',
                            'type'    => 'CheckBox',
                            'caption' => 'moon distance'
                        ],
                        [
                            'name'    => 'moonaltitude',
                            'type'    => 'CheckBox',
                            'caption' => 'moon altitude'
                        ],
                        [
                            'name'    => 'moonbrightlimbangle',
                            'type'    => 'CheckBox',
                            'caption' => 'moon bright limb angle'
                        ],
                        [
                            'name'    => 'moondirection',
                            'type'    => 'CheckBox',
                            'caption' => 'moon direction'
                        ],
                        [
                            'name'    => 'moonvisibility',
                            'type'    => 'CheckBox',
                            'caption' => 'moon visibility'
                        ],
                        [
                            'name'    => 'moonrise',
                            'type'    => 'CheckBox',
                            'caption' => 'moon rise'
                        ],
                        [
                            'name'    => 'moonset',
                            'type'    => 'CheckBox',
                            'caption' => 'moon set'
                        ],
                        [
                            'name'    => 'moonphase',
                            'type'    => 'CheckBox',
                            'caption' => 'moon phase'
                        ],
                        [
                            'name'    => 'newmoon',
                            'type'    => 'CheckBox',
                            'caption' => 'new moon'
                        ],
                        [
                            'name'    => 'firstquarter',
                            'type'    => 'CheckBox',
                            'caption' => 'first quarter'
                        ],
                        [
                            'name'    => 'fullmoon',
                            'type'    => 'CheckBox',
                            'caption' => 'full moon'
                        ],
                        [
                            'name'    => 'lastquarter',
                            'type'    => 'CheckBox',
                            'caption' => 'last quarter'
                        ],
                        [
                            'name'    => 'currentnewmoon',
                            'type'    => 'CheckBox',
                            'caption' => 'current cycle new moon'
                        ],
                        [
                            'name'    => 'currentfirstquarter',
                            'type'    => 'CheckBox',
                            'caption' => 'current cycle first quarter'
                        ],
                        [
                            'name'    => 'currentfullmoon',
                            'type'    => 'CheckBox',
                            'caption' => 'current cycle full moon'
                        ],
                        [
                            'name'    => 'currentlastquarter',
                            'type'    => 'CheckBox',
                            'caption' => 'current cycle last quarter'
                        ],
                        [
                            'name'    => 'moonstarsign',
                            'type'    => 'CheckBox',
                            'caption' => 'sun azimut'
                        ],
                        [
                            'name'    => 'sunazimut',
                            'type'    => 'CheckBox',
                            'caption' => 'moon in star sign'
                        ],
                        [
                            'name'    => 'sundistance',
                            'type'    => 'CheckBox',
                            'caption' => 'sun distance'
                        ],
                        [
                            'name'    => 'sunaltitude',
                            'type'    => 'CheckBox',
                            'caption' => 'sun altitude'
                        ],
                        [
                            'name'    => 'sundirection',
                            'type'    => 'CheckBox',
                            'caption' => 'sun direction'
                        ],
                        [
                            'name'    => 'season',
                            'type'    => 'CheckBox',
                            'caption' => 'season'
                        ],
                        [
                            'name'    => 'sunstarsign',
                            'type'    => 'CheckBox',
                            'caption' => 'sun in star sign'
                        ]
                    ]
                ],
                [
                    'type'    => 'ExpansionPanel',
                    'caption' => 'picture twilight',
                    'items'   => [
                        [
                            'name'    => 'pictureyeartwilight',
                            'type'    => 'CheckBox',
                            'caption' => 'picture year twilight'
                        ],
                        [
                            'name'    => 'picturedaytwilight',
                            'type'    => 'CheckBox',
                            'caption' => 'picture day twilight'
                        ],
                        [
                            'name'    => 'picturetwilightlimited',
                            'type'    => 'CheckBox',
                            'caption' => 'show pictures twilight limited'
                        ]
                    ]
                ],
                [
                    'type'    => 'ExpansionPanel',
                    'caption' => 'moon picture',
                    'items'   => [
                        [
                            'name'    => 'picturemoonvisible',
                            'type'    => 'CheckBox',
                            'caption' => 'picture moon'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'background moonpicture:'
                        ],
                        [
                            'type'    => 'Select',
                            'name'    => 'moonbackground',
                            'caption' => 'background',
                            'options' => [
                                [
                                    'label' => 'black background',
                                    'value' => 1
                                ],
                                [
                                    'label' => 'transparent background',
                                    'value' => 2
                                ]
                            ]

                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'resize images for the media element (module images are 100x100):'
                        ],
                        [
                            'name'    => 'selectionresize',
                            'type'    => 'CheckBox',
                            'caption' => 'resize images'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'media image width:'
                        ],
                        [
                            'name'    => 'mediaimgwidth',
                            'type'    => 'NumberSpinner',
                            'caption' => 'width'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'media image height:'
                        ],
                        [
                            'name'    => 'mediaimgheight',
                            'type'    => 'NumberSpinner',
                            'caption' => 'height'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'alternative use own moonpictures:'
                        ],
                        [
                            'name'    => 'picturemoonselection',
                            'type'    => 'CheckBox',
                            'caption' => 'use own moon pictures'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'pictures must have the number 001 to XXX for example mond001'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'pictures name (without number)'
                        ],
                        [
                            'name'    => 'picturename',
                            'type'    => 'ValidationTextBox',
                            'caption' => 'picture name'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture file type'
                        ],
                        [
                            'type'    => 'Select',
                            'name'    => 'filetype',
                            'caption' => 'file type',
                            'options' => [
                                [
                                    'label' => 'png',
                                    'value' => 1
                                ],
                                [
                                    'label' => 'gif',
                                    'value' => 2
                                ],
                                [
                                    'label' => 'jpg',
                                    'value' => 2
                                ]
                            ]

                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the first and last picture of the moon phase:'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'full moon:'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the first picture full moon:'
                        ],
                        [
                            'name'    => 'firstfullmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'first picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the last picture full moon:'
                        ],
                        [
                            'name'    => 'lastfullmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'last picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'increasing moon:'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the first picture increasing moon:'
                        ],
                        [
                            'name'    => 'firstincreasingmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'first picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the last picture increasing moon:'
                        ],
                        [
                            'name'    => 'lastincreasingmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'last picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'new moon:'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the first picture new moon:'
                        ],
                        [
                            'name'    => 'firstnewmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'first picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the last picture new moon:'
                        ],
                        [
                            'name'    => 'lastnewmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'last picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'decreasing moon:'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the first picture decreasing moon:'
                        ],
                        [
                            'name'    => 'firstdecreasingmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'first picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'picture number of the last picture decreasing moon:'
                        ],
                        [
                            'name'    => 'lastdecreasingmoonpic',
                            'type'    => 'NumberSpinner',
                            'caption' => 'last picture'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'path of the moonphase pictures relative to the IP-Symcon folder:'
                        ],
                        [
                            'name'    => 'picturemoonpath',
                            'type'    => 'ValidationTextBox',
                            'caption' => 'path moon pictures'
                        ],
                    ]
                ]
            ]
        );
        $form = array_merge_recursive(
            $form,
            [
                [
                    'type'    => 'ExpansionPanel',
                    'caption' => 'view position sun and moon',
                    'items'   => [
                        [
                            'name'    => 'sunmoonview',
                            'type'    => 'CheckBox',
                            'caption' => 'view position sun and moon'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'Zero point x-axis:'
                        ],
                        [
                            'name'    => 'zeropointx',
                            'type'    => 'NumberSpinner',
                            'caption' => 'zero point x'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'Zero point y-axis:'
                        ],
                        [
                            'name'    => 'zeropointy',
                            'type'    => 'NumberSpinner',
                            'caption' => 'zero point y'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'frame width in px or %:'
                        ],
                        [
                            'type'    => 'Select',
                            'name'    => 'framewidthtype',
                            'caption' => 'frame width type',
                            'options' => [
                                [
                                    'caption' => 'px',
                                    'value'   => 1
                                ],
                                [
                                    'caption' => '%',
                                    'value'   => 2
                                ]
                            ]

                        ],
                        [
                            'name'    => 'framewidth',
                            'type'    => 'NumberSpinner',
                            'caption' => 'width'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'frame height in px or %:'
                        ],
                        [
                            'type'    => 'Select',
                            'name'    => 'frameheighttype',
                            'caption' => 'frame height type',
                            'options' => [
                                [
                                    'label' => 'px',
                                    'value' => 1
                                ],
                                [
                                    'label' => '%',
                                    'value' => 2
                                ]
                            ]

                        ],
                        [
                            'name'    => 'frameheight',
                            'type'    => 'NumberSpinner',
                            'caption' => 'height'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'canvas width in px:'
                        ],
                        [
                            'name'    => 'canvaswidth',
                            'type'    => 'NumberSpinner',
                            'caption' => 'canvas width'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'canvas height in px:'
                        ],
                        [
                            'name'    => 'canvasheight',
                            'type'    => 'NumberSpinner',
                            'caption' => 'canvas height'
                        ],
                    ]
                ]
            ]
        );
        $form = array_merge_recursive(
            $form,
            [
                [
                    'type'    => 'ExpansionPanel',
                    'caption' => 'sunrise and sunset with offset',
                    'items'   => [
                        [
                            'type'    => 'Label',
                            'caption' => 'sunrise and sunset with offset:'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'sunrise with offset:'
                        ],
                        [
                            'name'    => 'sunriseselect',
                            'type'    => 'CheckBox',
                            'caption' => 'sunrise'
                        ],
                        [
                            'type'    => 'Select',
                            'name'    => 'risetype',
                            'caption' => 'sun- or moonrise',
                            'options' => [
                                [
                                    'label' => 'sunrise',
                                    'value' => 1
                                ],
                                [
                                    'label' => 'civilTwilightStart',
                                    'value' => 2
                                ],
                                [
                                    'label' => 'nauticTwilightStart',
                                    'value' => 3
                                ],
                                [
                                    'label' => 'astronomicTwilightStart',
                                    'value' => 4
                                ],
                                [
                                    'label' => 'moonrise',
                                    'value' => 5
                                ]
                            ]

                        ],
                        [
                            'name'    => 'sunriseoffset',
                            'type'    => 'NumberSpinner',
                            'caption' => 'offset (minute)'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'sunset with offset:'
                        ],
                        [
                            'name'    => 'sunsetselect',
                            'type'    => 'CheckBox',
                            'caption' => 'sunset'
                        ],
                        [
                            'type'    => 'Select',
                            'name'    => 'settype',
                            'caption' => 'sun- or moonset',
                            'options' => [
                                [
                                    'label' => 'sunset',
                                    'value' => 1
                                ],
                                [
                                    'label' => 'civilTwilightEnd',
                                    'value' => 2
                                ],
                                [
                                    'label' => 'nauticTwilightEnd',
                                    'value' => 3
                                ],
                                [
                                    'label' => 'astronomicTwilightEnd',
                                    'value' => 4
                                ],
                                [
                                    'label' => 'moonset',
                                    'value' => 5
                                ]
                            ]

                        ],
                        [
                            'name'    => 'sunsetoffset',
                            'type'    => 'NumberSpinner',
                            'caption' => 'offset (minute)'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => '____________________________________________________________________'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'optional create extended information:'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'for moonrise, moonset, sunrise, sunset, newmoon, first qarter, full moon,'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'last quarter separate variables with date and time are created if selected'
                        ],
                        [
                            'name'    => 'extinfoselection',
                            'type'    => 'CheckBox',
                            'caption' => 'extended information'
                        ],
                        [
                            'type'    => 'Label',
                            'caption' => 'time format, see description of date in the PHP manual for more format information'
                        ],
                        [
                            'type'    => 'Select',
                            'name'    => 'timeformat',
                            'caption' => 'sun- or moonset',
                            'options' => [
                                [
                                    'label' => 'H:i',
                                    'value' => 1
                                ],
                                [
                                    'label' => 'H:i:s',
                                    'value' => 2
                                ],
                                [
                                    'label' => 'h:i',
                                    'value' => 3
                                ],
                                [
                                    'label' => 'h:i:s',
                                    'value' => 4
                                ],
                                [
                                    'label' => 'g:i',
                                    'value' => 5
                                ],
                                [
                                    'label' => 'g:i:s',
                                    'value' => 6
                                ],
                                [
                                    'label' => 'G:i',
                                    'value' => 7
                                ],
                                [
                                    'label' => 'G:i:s',
                                    'value' => 8
                                ]
                            ]

                        ]
                    ]
                ]
            ]
        );
        return $form;
    }

    protected function FormUTCText($UTC)
    {
        $form = [];
        if ($UTC == 14) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +14 Tonga and 2 more LINT Kiritimati'
                    ]
                ]
            );
        }
        if ($UTC == 13.75) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => '"UTC +13:45 Chatham Islands / New Zealand CHADT Chatham Islands'
                    ]
                ]
            );
        }
        if ($UTC == 13) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +13 New Zealand with exceptions and 4 more NZDT Auckland'
                    ]
                ]
            );
        }
        if ($UTC == 12) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +12 Small area in Russia and 6 more ANAT Anadyr'
                    ]
                ]
            );
        }
        if ($UTC == 11) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +11 Much of Australia and 8 more AEDT Melbourne'
                    ]
                ]
            );
        }
        if ($UTC == 10.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +10:30 Small Australia Territory ACDT Adelaide'
                    ]
                ]
            );
        }
        if ($UTC == 10) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +10 Queensland / Australia and 6 more AEST Brisbane'
                    ]
                ]
            );
        }
        if ($UTC == 9.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +9:30 Northern Territory / Australia ACST Darwin'
                    ]
                ]
            );
        }
        if ($UTC == 9) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +9 Japan, South Korea and 4 more JST Tokyo'
                    ]
                ]
            );
        }
        if ($UTC == 8.75) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +8:45 Western Australia / Australia ACWST Eucla'
                    ]
                ]
            );
        }
        if ($UTC == 8.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +8:30 North Korea PYT Pyongyang'
                    ]
                ]
            );
        }
        if ($UTC == 8) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +8 China, Philippines and 10 more CST Beijing'
                    ]
                ]
            );
        }
        if ($UTC == 7) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +7 Majority of Indonesia and 8 more WIB Jakarta'
                    ]
                ]
            );
        }
        if ($UTC == 6.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +6:30 Myanmar and Cocos Islands MMT Yangon'
                    ]
                ]
            );
        }
        if ($UTC == 6) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +6 Bangladesh and 6 more BST Dhaka'
                    ]
                ]
            );
        }
        if ($UTC == 5.75) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +5:45 Nepal NPT Kathmandu'
                    ]
                ]
            );
        }
        if ($UTC == 5.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +5:30 India and Sri Lanka is New Delhi'
                    ]
                ]
            );
        }
        if ($UTC == 5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +5 Pakistan and 8 more UZT Tashkent'
                    ]
                ]
            );
        }
        if ($UTC == 4.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +4:30 Afghanistan AFT Kabul'
                    ]
                ]
            );
        }
        if ($UTC == 4) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +4 Azerbaijan and 8 more GST Dubai'
                    ]
                ]
            );
        }
        if ($UTC == 3.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +3:30 Iran IRST Tehran'
                    ]
                ]
            );
        }
        if ($UTC == 3) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +3 Moscow / Russia and 24 more MSK Moscow'
                    ]
                ]
            );
        }
        if ($UTC == 2) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +2 Greece and 30 other OEZ Cairo'
                    ]
                ]
            );
        }
        if ($UTC == 1) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +1 Germany and 43 more CET Berlin'
                    ]
                ]
            );
        }
        if ($UTC == 0) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC +0 UK and 26 more GMT London'
                    ]
                ]
            );
        }
        if ($UTC == -1) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -1 Cabo Verde and 2 more CVT Praia'
                    ]
                ]
            );
        }
        if ($UTC == -2) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -2 Brazil (some regions) and South Georgia and the South Sandwich Islands BRST Rio de Janeiro'
                    ]
                ]
            );
        }
        if ($UTC == -3) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -3 Brazil (some regions) and 10 other ART Buenos Aires'
                    ]
                ]
            );
        }
        if ($UTC == -3.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -3:30 Newfoundland and Labrador / Canada NST St. John\'s'
                    ]
                ]
            );
        }
        if ($UTC == -4) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -4 some areas of Canada and 29 more VET Caracas'
                    ]
                ]
            );
        }
        if ($UTC == -5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -5 United States (some regions) and 13 other EST New York'
                    ]
                ]
            );
        }
        if ($UTC == -6) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -6 United States (some regions) and 9 more CST Mexico City'
                    ]
                ]
            );
        }
        if ($UTC == -7) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -7 some areas of United States and 2 more MST Calgary'
                    ]
                ]
            );
        }
        if ($UTC == -8) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -8 United States (some regions) and 3 other PST Los Angeles'
                    ]
                ]
            );
        }
        if ($UTC == -9) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -9 Alaska / United States and French Polynesia (some regions) AKST Anchorage'
                    ]
                ]
            );
        }
        if ($UTC == -9.5) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -9:30 Marquesas / French Polynesia MART Taiohae'
                    ]
                ]
            );
        }
        if ($UTC == -10) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -10 Small area in United States and 2 other HAST Honolulu'
                    ]
                ]
            );
        }
        if ($UTC == -11) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -11 American Samoa and 2 more NUT Alofi'
                    ]
                ]
            );
        }
        if ($UTC == -12) {
            $form = array_merge_recursive(
                $form,
                [
                    [
                        'type'    => 'Label',
                        'caption' => 'UTC -12 Majority of US Minor Outlying Islands AoE Baker Island'
                    ]
                ]
            );
        }
        $form = array_merge_recursive(
            $form,
            [
                [
                    'type'    => 'Select',
                    'name'    => 'UTC',
                    'caption' => 'UTC',
                    'options' => [
                        [
                            'label' => 'UTC +14 LINT Kiritimati',
                            'value' => 14
                        ],
                        [
                            'label' => 'UTC +13:45 CHADT Chatham Islands',
                            'value' => 13.75
                        ],
                        [
                            'label' => 'UTC +13 NZDT Auckland',
                            'value' => 13
                        ],
                        [
                            'label' => 'UTC +12 ANAT Anadyr',
                            'value' => 12
                        ],
                        [
                            'label' => 'UTC +11 AEDT Melbourne',
                            'value' => 11
                        ],
                        [
                            'label' => 'UTC +10:30 ACDT Adelaide',
                            'value' => 10.5
                        ],
                        [
                            'label' => 'UTC +10 AEST Brisbane',
                            'value' => 10
                        ],
                        [
                            'label' => 'UTC +9:30 ACST Darwin',
                            'value' => 9.5
                        ],
                        [
                            'label' => 'UTC +9 JST Tokio',
                            'value' => 9
                        ],
                        [
                            'label' => 'UTC +8:45 ACWST Eucla',
                            'value' => 8.75
                        ],
                        [
                            'label' => 'UTC +8:30 PYT Pjöngjang',
                            'value' => 8.5
                        ],
                        [
                            'label' => 'UTC +8 CST Beijing',
                            'value' => 8
                        ],
                        [
                            'label' => 'UTC +7 WIB Jakarta',
                            'value' => 7
                        ],
                        [
                            'label' => 'UTC +6:30 MMT Rangun',
                            'value' => 6.5
                        ],
                        [
                            'label' => 'UTC +6 BST Dhaka',
                            'value' => 6
                        ],
                        [
                            'label' => 'UTC +5:45 NPT Kathmandu',
                            'value' => 5.75
                        ],
                        [
                            'label' => 'UTC +5:30 IS New Delhi',
                            'value' => 5.5
                        ],
                        [
                            'label' => 'UTC +5 UZT Tashkent',
                            'value' => 5
                        ],
                        [
                            'label' => 'UTC +4:30 AFT Kabul',
                            'value' => 4.5
                        ],
                        [
                            'label' => 'UTC +4 GST Dubai',
                            'value' => 4
                        ],
                        [
                            'label' => 'UTC +3:30 IRST Tehran',
                            'value' => 3.5
                        ],
                        [
                            'label' => 'UTC +3 MSK Moscow',
                            'value' => 3
                        ],
                        [
                            'label' => 'UTC +2 OEZ Cairo',
                            'value' => 2
                        ],
                        [
                            'label' => 'UTC +1 MEZ Berlin',
                            'value' => 1
                        ],
                        [
                            'label' => 'UTC +0 GMT London',
                            'value' => 0
                        ],
                        [
                            'label' => 'UTC -1 CVT Praia',
                            'value' => -1
                        ],
                        [
                            'label' => 'UTC -2 BRST Rio de Janeiro',
                            'value' => -2
                        ],
                        [
                            'label' => 'UTC -3 ART Buenos Aires',
                            'value' => -3
                        ],
                        [
                            'label' => 'UTC -3:30 NST St. John\'s',
                            'value' => -3.5
                        ],
                        [
                            'label' => 'UTC -4 VET Caracas',
                            'value' => -4
                        ],
                        [
                            'label' => 'UTC -5 EST New York',
                            'value' => -5
                        ],
                        [
                            'label' => 'UTC -6 CST Mexico City',
                            'value' => -6
                        ],
                        [
                            'label' => 'UTC -7 MST Calgary',
                            'value' => -7
                        ],
                        [
                            'label' => 'UTC -8 PST Los Angeles',
                            'value' => -8
                        ],
                        [
                            'label' => 'UTC -9 AKST Anchorage',
                            'value' => -9
                        ],
                        [
                            'label' => 'UTC -9:30 MART Taiohae',
                            'value' => -9.5
                        ],
                        [
                            'label' => 'UTC -10 HAST Honolulu',
                            'value' => -10
                        ],
                        [
                            'label' => 'UTC -11 NUT Alofi',
                            'value' => -11
                        ],
                        [
                            'label' => 'UTC -12 AoE Baker Island',
                            'value' => -12
                        ]
                    ]

                ]
            ]
        );
        return $form;
    }

    /**
     * return form actions by token.
     *
     * @return array
     */
    protected function FormActions()
    {
        $form = [
            [
                'type'    => 'Label',
                'caption' => 'update values'
            ],
            [
                'type'    => 'Button',
                'caption' => 'update',
                'onClick' => 'Astronomy_SetAstronomyValues($id);'
            ]
        ];

        return $form;
    }

    /**
     * return from status.
     *
     * @return array
     */
    protected function FormStatus()
    {
        $form = [
            [
                'code'    => 101,
                'icon'    => 'inactive',
                'caption' => 'Creating instance.'
            ],
            [
                'code'    => 102,
                'icon'    => 'active',
                'caption' => 'Astronomy ok'
            ],
            [
                'code'    => 104,
                'icon'    => 'inactive',
                'caption' => 'interface closed.'
            ],
            [
                'code'    => 201,
                'icon'    => 'inactive',
                'caption' => 'Please follow the instructions.'
            ],
            [
                'code'    => 210,
                'icon'    => 'error',
                'caption' => 'select moonset first to setup variable'
            ],
            [
                'code'    => 211,
                'icon'    => 'error',
                'caption' => 'select moonrise first to setup variable'
            ]
        ];

        return $form;
    }

    public function AlexaResponse()
    {
        $astronomyinfo = $this->SetAstronomyValues();
        $isday = $astronomyinfo['IsDay'];
        if ($isday) {
            $isday = 'Tag';
        } else {
            $isday = 'Nacht';
        }
        $timeformat = $this->GetTimeformat();
        $sunrise = $astronomyinfo['Sunrise'];
        $sunset = $astronomyinfo['Sunset'];
        $sunsetdate = date('d.m.Y', $sunset);
        $sunsettime = date($timeformat, $sunset);
        $sunrisedate = date('d.m.Y', $sunrise);
        $sunrisetime = date($timeformat, $sunrise);
        $moonsetdate = $astronomyinfo['moonsetdate'];
        $moonsettime = $astronomyinfo['moonsettime'];
        $moonrisedate = $astronomyinfo['moonrisedate'];
        $moonrisetime = $astronomyinfo['moonrisetime'];
        $civiltwilightstart = date($timeformat, $astronomyinfo['CivilTwilightStart']);
        $civiltwilightend = date($timeformat, $astronomyinfo['CivilTwilightEnd']);
        $nautictwilightstart = date($timeformat, $astronomyinfo['NauticTwilightStart']);
        $nautictwilightend = date($timeformat, $astronomyinfo['NauticTwilightEnd']);
        $astronomictwilightstart = date($timeformat, $astronomyinfo['AstronomicTwilightStart']);
        $astronomictwilightend = date($timeformat, $astronomyinfo['AstronomicTwilightEnd']);
        $Latitude = $astronomyinfo['latitude'] . ' Grad';
        $Longitude = $astronomyinfo['longitude'] . ' Grad';
        $JD = $astronomyinfo['juliandate'] . ' Tage';
        $season = $astronomyinfo['season'];
        if ($season == 1) {
            $season = 'Frühling';
        } elseif ($season == 2) {
            $season = 'Sommer';
        }
        if ($season == 3) {
            $season = 'Herbst';
        }
        if ($season == 4) {
            $season = 'Winter';
        }
        $sunazimut = round($astronomyinfo['sunazimut'], 2) . ' Grad';
        $SunDazimut = $this->GetSpokenDirection($astronomyinfo['sundirection']);
        $sunaltitude = round($astronomyinfo['sunaltitude'], 2) . ' Grad';
        $rSun = round($astronomyinfo['sundistance'], 0) . ' Kilometer';
        $moonazimut = round($astronomyinfo['moonazimut'], 2) . ' Grad';
        $moonaltitude = round($astronomyinfo['moonaltitude'], 2) . ' Grad';
        $dazimut = $this->GetSpokenDirection($astronomyinfo['moondirection']);
        $MoonDist = round($astronomyinfo['moondistance'], 0) . ' Kilometer';
        $Moonphase = $astronomyinfo['moonvisibility'] . ' Prozent';
        $Moonpabl = round($astronomyinfo['moonbrightlimbangle'], 2) . ' Grad';
        $newmoonstring = $astronomyinfo['newmoon'];
        $firstquarterstring = $astronomyinfo['firstquarter'];
        $fullmoonstring = $astronomyinfo['fullmoon'];
        $lastquarterstring = $astronomyinfo['lastquarter'];
        $moonphasepercent = $astronomyinfo['moonphasepercent'];
        $moonphasetext = $astronomyinfo['moonphasetext'];
        $alexaresponse = ['isday' => $isday, 'sunrisetime' => $sunrisetime, 'sunrisedate' => $sunrisedate, 'sunsettime' => $sunsettime, 'sunsetdate' => $sunsetdate, 'moonsetdate' => $moonsetdate, 'moonsettime' => $moonsettime, 'moonrisedate' => $moonrisedate, 'moonrisetime' => $moonrisetime, 'CivilTwilightStart' => $civiltwilightstart, 'CivilTwilightEnd' => $civiltwilightend, 'NauticTwilightStart' => $nautictwilightstart, 'NauticTwilightEnd' => $nautictwilightend, 'AstronomicTwilightStart' => $astronomictwilightstart, 'AstronomicTwilightEnd' => $astronomictwilightend,
            'latitude'            => $Latitude, 'longitude' => $Longitude, 'juliandate' => $JD, 'season' => $season, 'sunazimut' => $sunazimut, 'sundirection' => $SunDazimut, 'sunaltitude' => $sunaltitude, 'sundistance' => $rSun, 'moonazimut' => $moonazimut, 'moonaltitude' => $moonaltitude, 'moondirection' => $dazimut, 'moondistance' => $MoonDist, 'moonvisibility' => $Moonphase, 'moonbrightlimbangle' => $Moonpabl,
            'newmoon'             => $newmoonstring, 'firstquarter' => $firstquarterstring, 'fullmoon' => $fullmoonstring, 'lastquarter' => $lastquarterstring, 'moonphasetext' => $moonphasetext, 'moonphasepercent' => $moonphasepercent];
        return $alexaresponse;
    }

    protected function GetSpokenDirection($direction)
    {
        if ($direction == 0) {
            $direction = 'Nord';
        } elseif ($direction == 1) {
            $direction = 'Nord Nord Ost';
        } elseif ($direction == 2) {
            $direction = 'Nord Ost';
        } elseif ($direction == 3) {
            $direction = 'Ost Nord Ost';
        } elseif ($direction == 4) {
            $direction = 'Ost';
        } elseif ($direction == 5) {
            $direction = 'Ost Süd Ost';
        } elseif ($direction == 6) {
            $direction = 'Süd Ost';
        } elseif ($direction == 7) {
            $direction = 'Süd Süd Ost';
        } elseif ($direction == 8) {
            $direction = 'Süd';
        } elseif ($direction == 9) {
            $direction = 'Süd Süd West';
        } elseif ($direction == 10) {
            $direction = 'Süd West';
        } elseif ($direction == 11) {
            $direction = 'West Süd West';
        } elseif ($direction == 12) {
            $direction = 'West';
        } elseif ($direction == 13) {
            $direction = 'West Nord West';
        } elseif ($direction == 14) {
            $direction = 'Nord West';
        } elseif ($direction == 15) {
            $direction = 'Nord Nord West';
        }
        return $direction;
    }

    protected function GetIPSVersion()
    {
        $ipsversion = floatval(IPS_GetKernelVersion());
        if ($ipsversion < 4.1) // 4.0
        {
            $ipsversion = 0;
        } elseif ($ipsversion >= 4.1 && $ipsversion < 4.2) // 4.1
        {
            $ipsversion = 1;
        } elseif ($ipsversion >= 4.2 && $ipsversion < 4.3) // 4.2
        {
            $ipsversion = 2;
        } elseif ($ipsversion >= 4.3 && $ipsversion < 4.4) // 4.3
        {
            $ipsversion = 3;
        } elseif ($ipsversion >= 4.4 && $ipsversion < 5) // 4.4
        {
            $ipsversion = 4;
        } else   // 5
        {
            $ipsversion = 5;
        }

        return $ipsversion;
    }

    //Add this Polyfill for IP-Symcon 4.4 and older
    protected function SetValue($Ident, $Value)
    {

        if (IPS_GetKernelVersion() >= 5) {
            parent::SetValue($Ident, $Value);
        } else {
            SetValue($this->GetIDForIdent($Ident), $Value);
        }
    }
}
