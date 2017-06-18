<?
// Modul AstronomieTimer
require_once(__DIR__ . "/../bootstrap.php");

use Fonzo\IPS\IPSVarType;
use Fonzo\Moon\Moon;

class AstronomyTimer extends IPSModule
{

    public function Create()
    {
//Never delete this line!
        parent::Create();
		
		//These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
		
		$this->RegisterPropertyInteger("timertype", 1);
		$this->RegisterPropertyInteger("offset", 0);
		$this->RegisterPropertyBoolean("cutoffselect", false);
		$this->RegisterPropertyString("cutofftime", "00:00:00");
		$this->RegisterPropertyBoolean("varwebfrontselect", false);
		$this->RegisterPropertyInteger("triggerscript", 0);
		$this->RegisterPropertyBoolean("varselect", false);
		$this->RegisterPropertyInteger("triggervariable", 0);
		$this->RegisterPropertyString("varvalue", "");
		$this->RegisterPropertyBoolean("monday", true);
		$this->RegisterPropertyBoolean("tuesday", true);
		$this->RegisterPropertyBoolean("wednesday", true);
		$this->RegisterPropertyBoolean("thursday", true);
		$this->RegisterPropertyBoolean("friday", true);
		$this->RegisterPropertyBoolean("saturday", true);
		$this->RegisterPropertyBoolean("sunday", true);
		$this->RegisterTimer('AstroTimerUpdate', 0, 'AstronomyTimer_Set('.$this->InstanceID.');');
    }

    public function ApplyChanges()
    {
	//Never delete this line!
        parent::ApplyChanges();
		
		$this->ValidateConfiguration(); 
		$this->SetCyclicTimerInterval();
    }

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        *
        */
		
	private function ValidateConfiguration()
	{
		
		if($this->ReadPropertyBoolean("varwebfrontselect") == true) // int
		{
			$ipsversion = $this->GetIPSVersion ();
			if($ipsversion == 1)
			{
				$objid = $this->SetupVariable("eventtime", "Time Event", "~UnixTimestamp", 1, IPSVarType::vtInteger, true);
			}
			else
			{
				$objid = $this->SetupVariable("eventtime", "Time Event", "~UnixTimestampTime", 1, IPSVarType::vtInteger, true);
			}
			
			IPS_SetIcon($objid, "Clock");
		}
		else
		{
			$this->SetupVariable("eventtime", "Time Event", "~UnixTimestampTime", 1, IPSVarType::vtInteger, false);
		}
		
		
		$varselect = $this->ReadPropertyBoolean("varselect");
		$triggerscript = $this->ReadPropertyInteger("triggerscript");
		$triggervariable = $this->ReadPropertyInteger("triggervariable");
		$cutoffselect = $this->ReadPropertyBoolean("cutoffselect");
		$cutofftime = $this->GetCutoffTime("Sunset");
		if ($cutoffselect == true && $cutofftime == false)
		{
			$this->SetStatus(210); //check format time
		}
		
		if($varselect)
		{
			if($triggervariable > 0)
			{	
				$varvalueinfo = $this->GetTriggerVarValue();
				$varvaluetype = $varvalueinfo["VarType"];
				$vartype = $this->GetVarType($triggervariable);
				$vartypecheck = false;
				if($vartype === $varvaluetype)
				{
					$vartypecheck = true;
				}
				if ($vartypecheck)
				{
					$this->Set();
				}
				else
				{
					$this->SetStatus(213); // wrong value for vartype
				}
			}
			else
			{
				$this->SetStatus(211); //select variable
			}	
		}
		else
		{
			if($triggerscript > 0)
			{
				$this->Set();
			}
			else
			{
				$this->SetStatus(212); //select variable
			}
		}
		
		// Status Aktiv
		$this->SetStatus(102);	
		
	}
		
	protected function SetCyclicTimerInterval()
	{
		$Now = new DateTime(); 
		$Target = new DateTime(); 
		$Target->modify('+1 day'); 
		$Target->setTime(0,0,5); 
		$Diff =  $Target->getTimestamp() - $Now->getTimestamp(); 
		$Interval = $Diff *1000;  
		$this->SetTimerInterval("AstroTimerUpdate", $Interval);
	}
	
	// Profil anlegen
	protected function SetupProfile($vartype, $name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $associations)
	{
		if (!IPS_VariableProfileExists($name))
		{
			switch ($vartype)
			{
				case IPSVarType::vtBoolean:
					
					break;
				case IPSVarType::vtInteger:
					$this->RegisterProfileIntegerAss($name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $associations);
					break;
				case IPSVarType::vtFloat:
					$this->RegisterProfileFloatAss($name, $icon, $prefix, $suffix, $minvalue, $maxvalue, $stepsize, $digits, $associations);
					break;
				case IPSVarType::vtString:
					$this->RegisterProfileString($name, $icon);
					break;
			}	
		}
		return $name;
	}
	
	// Variable anlegen / löschen
	protected function SetupVariable($ident, $name, $profile, $position, $vartype, $visible)
	{
        $objid = false;
	    if($visible == true)
		{
			switch ($vartype)
			{
				case IPSVarType::vtBoolean:
					$objid = $this->RegisterVariableBoolean ( $ident, $name, $profile, $position );
					break;
				case IPSVarType::vtInteger:
					$objid = $this->RegisterVariableInteger ( $ident, $name, $profile, $position );
					break;
				case IPSVarType::vtFloat:
					$objid = $this->RegisterVariableFloat ( $ident, $name, $profile, $position );
					break;
				case IPSVarType::vtString:
					$objid = $this->RegisterVariableString ( $ident, $name, $profile, $position );
					break;
			}	
		}
		else
		{
			$objid = @$this->GetIDForIdent($ident);
			if ($objid > 0)
			{
				$this->UnregisterVariable($ident);
			}
		}
		
		return $objid;
	}
	
	public function Set()
	{
		$debug = false;
		$timertype = $this->GetTimerName();
		$offset = $this->ReadPropertyInteger("offset");
		$varselect = $this->ReadPropertyBoolean("varselect");
		if($varselect)
		{
			$settype = "Variable";
			$objectid = $this->ReadPropertyInteger("triggervariable");
			$varvalue = $this->GetTriggerVarValue();
			$this->SendDebug("ObjektID Variable:",$objectid,0);
			if($debug)
					IPS_LogMessage("ObjektID Variable: ", $objectid);
			
		}
		else
		{
			$settype = "Script";
			$objectid = $this->ReadPropertyInteger("triggerscript");
			$varvalue = 0;
			$this->SendDebug("ObjektID Skript:",$objectid,0);
			if($debug)
				IPS_LogMessage("ObjektID Skript: ", $objectid);
		}
		
		switch ($timertype)
			{
				case "Sunrise":
					$timertype = "Sunrise";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "Sunset":
					$timertype = "Sunset";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "CivilTwilightStart":
					$timertype = "CivilTwilightStart";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "CivilTwilightEnd":
					$timertype = "CivilTwilightEnd";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "NauticTwilightStart":
					$timertype = "NauticTwilightStart";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "NauticTwilightEnd":
					$timertype = "NauticTwilightEnd";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "AstronomicTwilightStart":
					$timertype = "AstronomicTwilightStart";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "AstronomicTwilightEnd":
					$timertype = "AstronomicTwilightEnd";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "Moonrise":
					$timertype = "Moonrise";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;
				case "Moonset":
					$timertype = "Moonset";
					$this->RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue);
					break;	
			}	
		
	}
		
	public function SetSunrise(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 1;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		$this->Set();
	}
	
	public function SetSunset(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 2;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetCivilTwilightStart(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 3;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetCivilTwilightEnd(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 4;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetNauticTwilightStart(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 5;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetNauticTwilightEnd(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 6;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetAstronomicTwilightStart(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 7;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetAstronomicTwilightEnd(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 8;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetMoonrise(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 9;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	public function SetMoonset(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 10;
		$this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
        $this->Set();
	}
	
	protected function CreateAstroTimerCategory()
	{
		$CatID = @IPS_GetCategoryIDByName("Astronomie Timer", 0);
		if ($CatID === false)
		{
			$CatID = IPS_CreateCategory();       // Kategorie anlegen
			IPS_SetName($CatID, "Astronomie Timer"); // Kategorie benennen
			IPS_SetIdent($CatID, "AstrotimerCat");
			IPS_SetParent($CatID, 0); // Kategorie einsortieren unter dem Root
		}
		return $CatID;
	}
	
	protected function CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue)
	{
		$targetobjectname = IPS_GetName($objectid);
		$timername = $this->GetTypeTimer($timertype);
		$ident = "Timertype_".$timername."_Offset_".$offset."_objid_".$objectid;
		$astrotimerobjid = @IPS_GetObjectIDByIdent($ident, $CatID);
		if(!$astrotimerobjid)
		{
			$astrotimerobjid = IPS_CreateInstance("{5C02271C-D599-4C71-98D3-86C89F94EB96}");
			$name = $timername." + ".$offset." Min ".$targetobjectname;
			IPS_SetProperty($astrotimerobjid, 'timertype', $timertype);
			IPS_SetProperty($astrotimerobjid, 'offset', $offset);
			if ($settype !== "Script" && $settype !== "Variable")
				{
					echo "Settype nicht gültig. Script oder Variable auswählen.";
				}
			if ($settype == "Script")
				{
					IPS_SetProperty($astrotimerobjid, 'triggerscript', $objectid);
				}
			if ($settype == "Variable")
				{
					IPS_SetProperty($astrotimerobjid, 'varselect', true);
					IPS_SetProperty($astrotimerobjid, 'triggervariable', $objectid);
					IPS_SetProperty($astrotimerobjid, 'varvalue', $varvalue);
				}	
			IPS_SetParent($astrotimerobjid, $CatID);
			IPS_SetName($astrotimerobjid, $name);
			IPS_SetIdent($astrotimerobjid, $ident);
			IPS_LogMessage('AstroTimer', $name.' mit ObjID '.$astrotimerobjid.'erstellt.');
			IPS_ApplyChanges($astrotimerobjid);
		}
		return $astrotimerobjid; 
	}
	
	protected function GetOffset()
	{
		$offset = $this->ReadPropertyInteger("offset");
		$offset = $offset * 60; 
		return $offset;
	}
	
	protected function GetTimerName()
	{
		$timertype = $this->ReadPropertyInteger("timertype");
		$timername = $this->GetTypeTimer($timertype);
		return $timername;
	}
	
	protected function GetTypeTimer($timertype)
	{
		switch ($timertype)
			{
				case 1:
					$timertype = "Sunrise";
					break;
				case 2:
					$timertype = "Sunset";
					break;
				case 3:
					$timertype = "CivilTwilightStart";
					break;
				case 4:
					$timertype = "CivilTwilightEnd";
					break;
				case 5:
					$timertype = "NauticTwilightStart";
					break;
				case 6:
					$timertype = "NauticTwilightEnd";
					break;
				case 7:
					$timertype = "AstronomicTwilightStart";
					break;
				case 8:
					$timertype = "AstronomicTwilightEnd";
					break;
				case 9:
					$timertype = "Moonrise";
					break;
				case 10:
					$timertype = "Moonset";
					break;	
			}	
		return $timertype;
	}
	
	protected function GetCutoffTime($timertype)
	{
		$cutofftimeinput = $this->ReadPropertyString("cutofftime");
		$locationinfo = $this->getlocationinfo();
		$sunastrodate = date("d.m.Y", $locationinfo[$timertype]);
		$cutofftime = $sunastrodate." ".$cutofftimeinput;
		$cutofftime = strtotime($cutofftime);
		return $cutofftime;
	}
	
	protected function GetVarType($objectid)
	{
		// VariableType (ab 4.0)	integer	Enthält den Variablentyp (0: Boolean, 1: Integer, 2: Float, 3: String)
		$vartype = IPS_GetVariable($objectid)["VariableType"];
		return $vartype;
	}
	
	protected function GetTriggerVarValue()
	{
		$varvalue = $this->ReadPropertyString("varvalue"); // string
		$varvaluetype = 3; // string
		$numeric = is_numeric($varvalue);
		$varvaluebool = strtolower($varvalue);// bolean
		if($varvaluebool == "false")
		{
			$varvalue = false;
			$varvaluetype = 0; // boolean
		}
		if($varvaluebool == "true")
		{
			$varvalue = true;
			$varvaluetype = 0; // boolean
		}
		if($numeric)
		{
			$varvaluefloat = $this->isfloat($varvalue);
			if($varvaluefloat)
			{
				$varvalue = floatval($varvalue);// float
				$varvaluetype = 2;
			}
			else
			{
				$varvalue = intval($varvalue);// int
				$varvaluetype = 1;
			}
		}
		$varvalue = array("VarType" => $varvaluetype, "Value" => $varvalue);
		return $varvalue;
	}
	
	public function WriteVariableValue()
	{
		$triggervariable = $this->ReadPropertyInteger("triggervariable");
		$varvalueinfo = $this->GetTriggerVarValue();
		$varvalue = $varvalueinfo["Value"];
		$varvaluetype = $varvalueinfo["VarType"];
		$vartype = $this->GetVarType($triggervariable);
		$vartypecheck = false;
		if($vartype === $varvaluetype)
		{
			$vartypecheck = true;
		}
		if ($vartypecheck)
		{
			SetValue($triggervariable, $varvalue);
		}
		else
		{
			echo "Variablenwert und Variablentyp stimmen nicht überein.";
		}
	}
	
	protected function isfloat($value)
	{
		// PHP automagically tries to coerce $value to a number
		return is_float($value + 0);
	}
	
	protected function SetVarWebFront($value)
	{
		$objectid = @$this->GetIDForIdent("eventtime");
		if ($objectid > 0)
		SetValue($objectid, $value);
	}
	
	protected function GetTimerSettings($timertype)
	{
		$locationinfo = $this->getlocationinfo();
		$sunrise = $locationinfo["Sunrise"];
		$sunset = $locationinfo["Sunset"];
		$civiltwilightstart = $locationinfo["CivilTwilightStart"];
		$civiltwilightend = $locationinfo["CivilTwilightEnd"];
		$nautictwilightstart = $locationinfo["NauticTwilightStart"];
		$nautictwilightend = $locationinfo["NauticTwilightEnd"];
		$astronomictwilightstart = $locationinfo["AstronomicTwilightStart"];
		$astronomictwilightend = $locationinfo["AstronomicTwilightEnd"];
		$offset = $this->GetOffset();
		$cutoffselect = $this->ReadPropertyBoolean("cutoffselect");
		$cutoff = $this->GetCutoffTime($timertype);  
		$timestamp = 0;
		$direction = "";
		$Stunde = 0;
		$Minute = 0;
		$Sekunde = 0;
		switch ($timertype)
			{
				case "Sunrise":
					$direction = "up";
					$timestamp = $sunrise + $offset;
					break;
				case "Sunset":
					$direction = "down";
					$timestamp = $sunset + $offset;
					break;
				case "CivilTwilightStart":
					$direction = "up";
					$timestamp = $civiltwilightstart + $offset;
					break;
				case "CivilTwilightEnd":
					$direction = "down";
					$timestamp = $civiltwilightend + $offset;
					break;
				case "NauticTwilightStart":
					$direction = "up";
					$timestamp = $nautictwilightstart + $offset;
					break;
				case "NauticTwilightEnd":
					$direction = "down";
					$timestamp = $nautictwilightend + $offset;
					break;
				case "AstronomicTwilightStart":
					$direction = "up";
					$timestamp = $astronomictwilightstart + $offset;
					break;
				case "AstronomicTwilightEnd":
					$direction = "down";
					$timestamp = $astronomictwilightend + $offset;
					break;
				case "Moonrise":
					$moonrise = $this->Mondaufgang();
					$direction = "up";
					$timestamp = $moonrise + $offset;
					break;
				case "Moonset":
					$moonset = $this->Monduntergang();
					$direction = "down";
					$timestamp = $moonset + $offset;
					break;	
			}
		if($cutoffselect)
		{
			if (($cutoff > $timestamp && $direction == "up")||($cutoff < $timestamp && $direction == "down"))
			{
				$Stunde = intval(date("G", $cutoff));
				$Minute = intval(date("i", $cutoff));
				$Sekunde = intval(date("s", $cutoff));
				$timestamp = $cutoff;
				IPS_LogMessage("AstroTimer: ", "Cutoff Set");
			}
			if (($cutoff < $timestamp && $direction == "up") || ($cutoff > $timestamp && $direction == "down"))
			{
				$Stunde = intval(date("G", $timestamp));
				$Minute = intval(date("i", $timestamp));
				$Sekunde = intval(date("s", $timestamp));
				IPS_LogMessage("AstroTimer: ", "Timer Set");
			}
		}
		else
		{
			$Stunde = intval(date("G", $timestamp));
			$Minute = intval(date("i", $timestamp));
			$Sekunde = intval(date("s", $timestamp));
			IPS_LogMessage("AstroTimer: ", "Timer Set");
		}	
			
		$timersettings = array("timestamp" => $timestamp, "direction" => $direction, "Stunde" => $Stunde, "Minute" => $Minute, "Sekunde" => $Sekunde, "cutofftime" => $cutoff, "offset" => $offset);
		return $timersettings;
	}
	
	protected function RegisterAstroTimer($timertype, $offset, $settype, $objectid, $varvalue)
	{
		$identoffset = str_replace("-", "minus", $offset);
	    if($varvalue == "true")
		{
		$ident = $timertype.$objectid."_".$identoffset."_on";
		}
		elseif($varvalue == "false")
		{
		$ident = $timertype."_".$objectid."_".$identoffset."_off";
		}
		else
		{
			$ident = $timertype."_".$objectid."_".$identoffset;
		}
		
		$name = $timertype." + ".$offset." Minuten";
		
		$timersettings = $this->GetTimerSettings($timertype);
		$timestamp = $timersettings["timestamp"];
		$Stunde = $timersettings["Stunde"];
		$Minute = $timersettings["Minute"];
		$Sekunde = $timersettings["Sekunde"];
        $eventid = false;
		switch ($settype)
			{
				case "Script":
					$eventid = $this->RegisterAstroTimerScript($timestamp, $Stunde, $Minute, $Sekunde, $objectid, $ident, $name);
					break;
				case "Variable":
					$eventid = $this->RegisterAstroTimerVariable($timestamp, $Stunde, $Minute, $Sekunde, $objectid, $varvalue, $ident, $name);
					break;
			}	
		
        return $eventid;
    }
		
	protected function RegisterAstroTimerVariable($timestamp, $Stunde, $Minute, $Sekunde, $objectid, $varvalue, $ident, $name)
	{
		$eventid = @IPS_GetObjectIDByIdent($ident, $objectid);
		if(!$eventid)
        {
            $eventid = IPS_CreateEvent(1);
			IPS_SetParent($eventid, $objectid);
			IPS_SetIdent($eventid, $ident);
            IPS_SetInfo($eventid, "Timer was created by AstroTimer ".$this->InstanceID);
            IPS_SetEventScript($eventid, $objectid);
			$script = "AstronomyTimer_WriteVariableValue(".$this->InstanceID.")";
			IPS_SetEventScript($eventid, "\$id = \$_IPS['TARGET'];\n$script;");
            $activeday = $this->CheckActiveDay();
			if($activeday)
			{
				IPS_SetEventActive($eventid, true);
			}
			else
			{
				IPS_SetEventActive($eventid, false);
			}
        }
		IPS_SetName($eventid, $name);
        IPS_SetEventCyclic($eventid, 0, 0, 0, 0, 0, 0);
		IPS_SetEventCyclicTimeFrom($eventid, $Stunde, $Minute, $Sekunde );
		IPS_SetEventCyclicTimeTo($eventid, 0, 0, 0 );
		$this->SetVarWebFront($timestamp);
		return $eventid;
	}
	
	protected function RegisterAstroTimerScript($timestamp, $Stunde, $Minute, $Sekunde, $objectid, $ident, $name)
	{
		$eventid = @IPS_GetObjectIDByIdent($ident, $objectid);
		if(!$eventid)
        {
            $eventid = IPS_CreateEvent(1);
			IPS_SetParent($eventid, $objectid);
			IPS_SetIdent($eventid, $ident);
            IPS_SetInfo($eventid, "Timer was created by AstronomyTimer ".$this->InstanceID);
            IPS_SetEventScript($eventid, $objectid);
			$activeday = $this->CheckActiveDay();
			if($activeday)
			{
				IPS_SetEventActive($eventid, true);
			}
			else
			{
				IPS_SetEventActive($eventid, false);
			}
            
        }
		IPS_SetName($eventid, $name);
        IPS_SetEventCyclic($eventid, 0, 0, 0, 0, 0, 0);
        IPS_SetEventCyclicTimeFrom($eventid, $Stunde, $Minute, $Sekunde );
		IPS_SetEventCyclicTimeTo($eventid, 0, 0, 0 ); 
		$this->SetVarWebFront($timestamp);
		return $eventid;
	}
	
	protected function CheckActiveDay()
	{
		$activeday = true;
		$currentday = date("w"); // Wochentag in Zahlenwert, 0 für Sonntag, 6 für Samstag
		$monday = $this->ReadPropertyBoolean("monday");
		$tuesday = $this->ReadPropertyBoolean("tuesday");
		$wednesday = $this->ReadPropertyBoolean("wednesday");
		$thursday = $this->ReadPropertyBoolean("thursday");
		$friday = $this->ReadPropertyBoolean("friday");
		$saturday = $this->ReadPropertyBoolean("saturday");
		$sunday = $this->ReadPropertyBoolean("sunday");
		if($currentday == 1 && $monday == false)
		{
			$activeday = false;
		}
		elseif($currentday == 2 && $tuesday == false)
		{
			$activeday = false;
		}
		elseif($currentday == 3 && $wednesday == false)
		{
			$activeday = false;
		}
		elseif($currentday == 4 && $thursday == false)
		{
			$activeday = false;
		}
		elseif($currentday == 5 && $friday == false)
		{
			$activeday = false;
		}
		elseif($currentday == 6 && $saturday == false)
		{
			$activeday = false;
		}
		elseif($currentday == 0 && $sunday == false)
		{
			$activeday = false;
		}
		return $activeday;
	}
	
	protected function getlocation()
	{
		//Location auslesen
		$LocationID = IPS_GetInstanceListByModuleID("{45E97A63-F870-408A-B259-2933F7EABF74}")[0];
		$Latitude = IPS_GetProperty($LocationID, "Latitude");
		$Longitude = IPS_GetProperty($LocationID, "Longitude");
		$location = array ("Latitude" => $Latitude, "Longitude" => $Longitude);
		return $location;
	}
	
	protected function getlocationinfo()
	{
		$LocationID = IPS_GetInstanceListByModuleID("{45E97A63-F870-408A-B259-2933F7EABF74}")[0];
		$isday = GetValue(IPS_GetObjectIDByIdent("IsDay", $LocationID));
		$sunrise = GetValue(IPS_GetObjectIDByIdent("Sunrise", $LocationID));
		$sunset = GetValue(IPS_GetObjectIDByIdent("Sunset", $LocationID));
		$civiltwilightstart = GetValue(IPS_GetObjectIDByIdent("CivilTwilightStart", $LocationID));
		$civiltwilightend = GetValue(IPS_GetObjectIDByIdent("CivilTwilightEnd", $LocationID));
		$nautictwilightstart = GetValue(IPS_GetObjectIDByIdent("NauticTwilightStart", $LocationID));
		$nautictwilightend = GetValue(IPS_GetObjectIDByIdent("NauticTwilightEnd", $LocationID));
		$astronomictwilightstart = GetValue(IPS_GetObjectIDByIdent("AstronomicTwilightStart", $LocationID));
		$astronomictwilightend = GetValue(IPS_GetObjectIDByIdent("AstronomicTwilightEnd", $LocationID));
		$locationinfo = array ("IsDay" => $isday, "Sunrise" => $sunrise, "Sunset" => $sunset, "CivilTwilightStart" => $civiltwilightstart, "CivilTwilightEnd" => $civiltwilightend, "NauticTwilightStart" => $nautictwilightstart, "NauticTwilightEnd" => $nautictwilightend, "AstronomicTwilightStart" => $astronomictwilightstart, "AstronomicTwilightEnd" => $astronomictwilightend);
		return $locationinfo;
	}

	protected function roundvariantfix ($value)
	{
        $roundvalue = 0;
	    if($value >= 0)
        {
            $roundvalue = floor($value);
        }
		elseif($value < 0)
        {
            $roundvalue = ceil($value);
        }
		return $roundvalue;
	}

	protected function roundvariantint ($value)
	{
		$roundvalue = floor($value);
		return $roundvalue;
	}

	protected function dayName($time)
	{
		$day = date("D",($time));
		if     ($day == "Mon"){$daygerman = "Mo";}
		elseif ($day == "Tue"){$daygerman = "Di";}
		elseif ($day == "Wed"){$daygerman = "Mi";}
		elseif ($day == "Thu"){$daygerman = "Do";}
		elseif ($day == "Fri"){$daygerman = "Fr";}
		elseif ($day == "Sat"){$daygerman = "Sa";}
		elseif ($day == "Sun"){$daygerman = "So";}
		else {$daygerman = "Mo";}
		return ($daygerman);
	}

	

	// Berechnung der Mondauf/untergangs Zeiten
	public function Mondaufgang()
	{
		$month = date("m");
		$day = date("d");
		$year = date("Y");
        $latitude = false;
        $longitude = false;
		$InstanzenListe = IPS_GetInstanceListByModuleID("{45E97A63-F870-408A-B259-2933F7EABF74}");
		foreach ($InstanzenListe as $InstanzID)
			{
		   
				$latitude = IPS_GetProperty($InstanzID, "Latitude"); // Location
				$longitude = IPS_GetProperty($InstanzID, "Longitude");
			}
		$data = (Moon::calculateMoonTimes($month, $day, $year, $latitude, $longitude));

		$moonrise = $data->{'moonrise'}; //Aufgang
		return $moonrise;
	}
	
	public function Monduntergang()
	{
		$month = date("m");
		$day = date("d");
		$year = date("Y");
        $latitude = false;
        $longitude = false;
		$InstanzenListe = IPS_GetInstanceListByModuleID("{45E97A63-F870-408A-B259-2933F7EABF74}");
		foreach ($InstanzenListe as $InstanzID)
			{
		   
				$latitude = IPS_GetProperty($InstanzID, "Latitude"); // Location
				$longitude = IPS_GetProperty($InstanzID, "Longitude");
			}
		$data = (Moon::calculateMoonTimes($month, $day, $year, $latitude, $longitude));

		$moonset = $data->{'moonset'}; //Untergang
		return $moonset;
	}	
	
	// ------------------------------
	
	
	//Profile
	protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits)
	{
        
        if(!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, 1);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if($profile['ProfileType'] != 1)
            throw new Exception("Variable profile type does not match for profile ".$Name);
        }
        
        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
		IPS_SetVariableProfileDigits($Name, $Digits); //  Nachkommastellen
        IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize); // string $ProfilName, float $Minimalwert, float $Maximalwert, float $Schrittweite
        
    }
	
	protected function RegisterProfileIntegerAss($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Associations)
	{
        if ( sizeof($Associations) === 0 ){
            $MinValue = 0;
            $MaxValue = 0;
        } 
		/*
		else {
            //undefiened offset
			$MinValue = $Associations[0][0];
            $MaxValue = $Associations[sizeof($Associations)-1][0];
        }
        */
        $this->RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits);
        
		//boolean IPS_SetVariableProfileAssociation ( string $ProfilName, float $Wert, string $Name, string $Icon, integer $Farbe )
        foreach($Associations as $Association) {
            IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
        }
        
    }
			
	protected function RegisterProfileString($Name, $Icon)
	{
        
        if(!IPS_VariableProfileExists($Name))
			{
            IPS_CreateVariableProfile($Name, 3);
			IPS_SetVariableProfileIcon($Name, $Icon);
			} 
		else {
            $profile = IPS_GetVariableProfile($Name);
            if($profile['ProfileType'] != 3)
            throw new Exception("Variable profile type does not match for profile ".$Name);
        }
        
        
        //IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
        //IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
        
    }
	
	protected function RegisterProfileFloat($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits)
	{
        
        if(!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, 2);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if($profile['ProfileType'] != 2)
            throw new Exception("Variable profile type does not match for profile ".$Name);
        }
        
        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
		IPS_SetVariableProfileDigits($Name, $Digits); //  Nachkommastellen
        IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
        
    }
	
	protected function RegisterProfileFloatAss($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Associations)
	{
        if ( sizeof($Associations) === 0 ){
            $MinValue = 0;
            $MaxValue = 0;
        } 
		/*
		else {
            //undefiened offset
			$MinValue = $Associations[0][0];
            $MaxValue = $Associations[sizeof($Associations)-1][0];
        }
        */
        $this->RegisterProfileFloat($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits);
        
		//boolean IPS_SetVariableProfileAssociation ( string $ProfilName, float $Wert, string $Name, string $Icon, integer $Farbe )
        foreach($Associations as $Association) {
            IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
        }
        
    }
	
	//Configuration Form
		public function GetConfigurationForm()
		{
			$formhead = $this->FormHead();
			$formselection = $this->FormSelection();
			$formstatus = $this->FormStatus();
			$formactions = $this->FormActions();
			$formelementsend = '{ "type": "Label", "label": "__________________________________________________________________________________________________" }';
			
			return	'{ '.$formhead.$formselection.$formelementsend.'],'.$formactions.$formstatus.' }';
		}
		
		
		
		protected function FormSelection()
		{			 
			$form = '{ "type": "Select", "name": "timertype", "caption": "event for the timer",
					"options": [
						{ "label": "sunrise", "value": 1 },
						{ "label": "sunset", "value": 2 },
						{ "label": "civilTwilightStart", "value": 3 },
						{ "label": "civilTwilightEnd", "value": 4 },
						{ "label": "nauticTwilightStart", "value": 5 },
						{ "label": "nauticTwilightEnd", "value": 6 },
						{ "label": "astronomicTwilightStart", "value": 7 },
						{ "label": "astronomicTwilightEnd", "value": 8 },
						{ "label": "moonrise", "value": 9 },
						{ "label": "moonset", "value": 10 }
					]
				},
				{ "type": "Label", "label": "offset for the timer:" },
				{ "type": "NumberSpinner", "name": "offset", "caption": "minute" },
				{ "type": "Label", "label": "cutoff time (used instead of the astronomical time if limit is reached)" },
				{
                    "name": "cutoffselect",
                    "type": "CheckBox",
                    "caption": "use cutoff time"
                },
				{ "type": "ValidationTextBox", "name": "cutofftime", "caption": "cutoff time" },
				{ "type": "Label", "label": "create variable with the time of the event for the webfront" },
				{
                    "name": "varwebfrontselect",
                    "type": "CheckBox",
                    "caption": "webfront variable"
                },
				{ "type": "Label", "label": "choose trigger script" },
				{ "type": "Label", "label": "trigger script:" },
				{ "type": "SelectScript", "name": "triggerscript", "caption": "trigger script" },
				{ "type": "Label", "label": "alternative: change variable" },
				{ "type": "Label", "label": "check box for variable use" },
				{
                    "name": "varselect",
                    "type": "CheckBox",
                    "caption": "set variable"
                },
				{ "type": "SelectVariable", "name": "triggervariable", "caption": "trigger variable" },
				{ "type": "Label", "label": "type value to set for the variable, please use point not comma for float (4.3)" },
				{ "type": "ValidationTextBox", "name": "varvalue", "caption": "variable value" },
				{ "type": "Label", "label": "____________________________________________________________________" },
				{ "type": "Label", "label": "OPTIONAL (leave empty if not needed):" },
				{ "type": "Label", "label": "timer is valid only:" },
				{ "name": "monday", "type": "CheckBox", "caption": "monday" },
				{ "name": "tuesday", "type": "CheckBox", "caption": "tuesday" },
				{ "name": "wednesday", "type": "CheckBox", "caption": "wednesday" },
				{ "name": "thursday", "type": "CheckBox", "caption": "thursday" },
				{ "name": "friday", "type": "CheckBox", "caption": "friday" },
				{ "name": "saturday", "type": "CheckBox", "caption": "saturday" },
				{ "name": "sunday", "type": "CheckBox", "caption": "sunday" },';
			return $form;
		}
		
		protected function FormHead()
		{
			$form = '"elements":
            [
				{ "type": "Label", "label": "Astronomy Timer" },
				{ "type": "Label", "label": "choose type of event for the astronomical timer:" },';
			
			return $form;
		}
		
		protected function FormActions()
		{
			$form = '"actions":
			[
				{ "type": "Label", "label": "update timer" },
				{ "type": "Button", "label": "update", "onClick": "AstronomyTimer_Set($id);" }
			],';
			return  $form;
		}	
		
		protected function FormStatus()
		{
			$form = '"status":
            [
                {
                    "code": 101,
                    "icon": "inactive",
                    "caption": "Creating instance."
                },
				{
                    "code": 102,
                    "icon": "active",
                    "caption": "Astronomy ok"
                },
                {
                    "code": 104,
                    "icon": "inactive",
                    "caption": "interface closed."
                },
				{
                    "code": 210,
                    "icon": "inactive",
                    "caption": "check format cuttoff time"
                },
				{
                    "code": 211,
                    "icon": "inactive",
                    "caption": "select variable"
                },
				{
                    "code": 212,
                    "icon": "inactive",
                    "caption": "select script"
                },
				{
                    "code": 213,
                    "icon": "inactive",
                    "caption": "wrong value for Variable type"
                }
            ]';
			return $form;
		}
		
		protected function GetIPSVersion ()
		{
			$ipsversion = IPS_GetKernelVersion ( );
			$ipsversion = explode( ".", $ipsversion);
			$ipsminor = intval($ipsversion[1]);
			if($ipsminor < 10)
			{
			$ipsversion = 1;
			}
			else
			{
			$ipsversion = 2;
			}
			return $ipsversion;
		}
}




?>