<?
// Modul AstronomieTimer

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
    }

    public function ApplyChanges()
    {
	//Never delete this line!
        parent::ApplyChanges();
		
		$this->ValidateConfiguration(); 
		$this->RegisterCyclicTimer('AstroTimerUpdate', 0, 5, 0, 'AstronomyTimer_Set('.$this->InstanceID.')');
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
		$cutofftime = $this->GetCutoffTime();
		if ($cutoffselect == true && $cutofftime == false)
		{
			$this->SetStatus(210); //check format time
		}
		
		if($varselect)
		{
			if($triggervariable > 0)
			{	
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
		
	protected function RegisterCyclicTimer($ident, $Stunde, $Minute, $Sekunde, $script)
	{
		$id = @$this->GetIDForIdent($ident);
		$name = "Astrotimer Update";
		if ($id && IPS_GetEvent($id)['EventType'] <> 1)
		{
		  IPS_DeleteEvent($id);
		  $id = 0;
		}

		if (!$id)
		{
		  $id = IPS_CreateEvent(1);
		  IPS_SetParent($id, $this->InstanceID);
		  IPS_SetIdent($id, $ident);
		}

		IPS_SetName($id, $name);
		IPS_SetInfo($id, "Update AstroTimer");
		IPS_SetHidden($id, true);
		IPS_SetEventScript($id, "\$id = \$_IPS['TARGET'];\n$script;");

		if (!IPS_EventExists($id)) throw new Exception("Ident with name $ident is used for wrong object type");

		IPS_SetEventCyclic($id, 0, 0, 0, 0, 0, 0);
		IPS_SetEventCyclicTimeFrom($id, $Stunde, $Minute, $Sekunde );
		IPS_SetEventCyclicTimeTo($id, 0, 0, 0 );
		IPS_SetEventActive($id, true);
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
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetSunset(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 2;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetCivilTwilightStart(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 3;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetCivilTwilightEnd(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 4;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetNauticTwilightStart(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 5;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetNauticTwilightEnd(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 6;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetAstronomicTwilightStart(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 7;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetAstronomicTwilightEnd(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 8;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetMoonrise(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 9;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
	}
	
	public function SetMoonset(int $offset, string $settype, int $objectid, string $varvalue)
	{
		$CatID = $this->CreateAstroTimerCategory();
		$timertype = 10;
		$astrotimerobjid = $this->CreateAstroTimerInstance($timertype, $CatID, $offset, $settype, $objectid, $varvalue);
		AstronomyTimer_Set($astrotimerobjid);
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
	
	protected function GetCutoffTime()
	{
		$cutofftimeinput = $this->ReadPropertyString("cutofftime");
		$locationinfo = $this->getlocationinfo();
		$sunrisedate = date("d.m.Y", $locationinfo["Sunrise"]);
		$cutofftime = $sunrisedate." ".$cutofftimeinput;
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
		$cutoff = $this->GetCutoffTime();
		$timestamp = 0;
		$direction = "";
		$Stunde = 0;
		$Minute = 0;
		$Sekunde = 0;
		switch ($timertype)
			{
				case "Sunrise":
					$sunrise = $locationinfo["Sunrise"];
					$direction = "up";
					$timestamp = $sunrise + $offset;
					break;
				case "Sunset":
					$sunset = $locationinfo["Sunset"];
					$direction = "down";
					$timestamp = $sunset + $offset;
					break;
				case "CivilTwilightStart":
					$civiltwilightstart = $locationinfo["CivilTwilightStart"];
					$direction = "up";
					$timestamp = $civiltwilightstart + $offset;
					break;
				case "CivilTwilightEnd":
					$civiltwilightend = $locationinfo["CivilTwilightEnd"];
					$direction = "down";
					$timestamp = $civiltwilightend + $offset;
					break;
				case "NauticTwilightStart":
					$nautictwilightstart = $locationinfo["NauticTwilightStart"];
					$direction = "up";
					$timestamp = $nautictwilightstart + $offset;
					break;
				case "NauticTwilightEnd":
					$nautictwilightend = $locationinfo["NauticTwilightEnd"];
					$direction = "down";
					$timestamp = $nautictwilightend + $offset;
					break;
				case "AstronomicTwilightStart":
					$astronomictwilightstart = $locationinfo["AstronomicTwilightStart"];
					$direction = "up";
					$timestamp = $astronomictwilightstart + $offset;
					break;
				case "AstronomicTwilightEnd":
					$astronomictwilightend = $locationinfo["AstronomicTwilightEnd"];
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
			if (($cutoff > $timestamp && $direction == "up")||($cutoff > $timestamp && $direction == "down"))
			{
				$Stunde = intval(date("G", $cutoff));
				$Minute = intval(date("i", $cutoff));
				$Sekunde = intval(date("s", $cutoff));
				$timestamp = $cutoff;
				IPS_LogMessage("AstroTimer: ", "Cutoff Set");
			}
			if (($cutoff < $timestamp && $direction == "up") || ($cutoff < $timestamp && $direction == "down"))
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
		$ident = $timertype.$objectid;
		$name = $timertype." + ".$offset." Minuten";
		
		$timersettings = $this->GetTimerSettings($timertype);
		$timestamp = $timersettings["timestamp"];
		$direction = $timersettings["direction"];
		$Stunde = $timersettings["Stunde"];
		$Minute = $timersettings["Minute"];
		$Sekunde = $timersettings["Sekunde"];
		$cutofftime = $timersettings["cutofftime"];
		
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
		if($value >= 0)
			$roundvalue = floor($value);
		elseif($value < 0)
			$roundvalue = ceil($value);	
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
		return ($daygerman);
	}

	

	// Berechnung der Mondauf/untergangs Zeiten
	public function Mondaufgang()
	{
		$month = date("m");
		$day = date("d");
		$year = date("Y");
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
			$ipsmajor = intval($ipsversion[0]);
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

/******************************************************************************
* The following is a PHP implementation of the JavaScript code found at:      *
* http://bodmas.org/astronomy/riset.html                                      *
*                                                                             *
* Original maths and code written by Keith Burnett <bodmas.org>               *
* PHP port written by Matt "dxprog" Hackmann <dxprog.com>                     *
*                                                                             *
* This program is free software: you can redistribute it and/or modify        *
* it under the terms of the GNU General Public License as published by        *
* the Free Software Foundation, either version 3 of the License, or           *
* (at your option) any later version.                                         *
*                                                                             *
* This program is distributed in the hope that it will be useful,             *
* but WITHOUT ANY WARRANTY; without even the implied warranty of              *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               *
* GNU General Public License for more details.                                *
*                                                                             *
* You should have received a copy of the GNU General Public License           *
* along with this program.  If not, see <http://www.gnu.org/licenses/>.       *
******************************************************************************/

class Moon extends stdClass
{

    /**
     * Calculates the moon rise/set for a given location and day of year
     */
    public static function calculateMoonTimes($month, $day, $year, $lat, $lon) {

        $utrise = $utset = 0;

        $timezone = (int)($lon / 15);
        $date = self::modifiedJulianDate($month, $day, $year);
        $date -= $timezone / 24;
        $latRad = deg2rad($lat);
        $sinho = 0.0023271056;
        $sglat = sin($latRad);
        $cglat = cos($latRad);

        $rise = false;
        $set = false;
        $above = false;
        $hour = 1;
        $ym = self::sinAlt($date, $hour - 1, $lon, $cglat, $sglat) - $sinho;

        $above = $ym > 0;
        while ($hour < 25 && (false == $set || false == $rise)) {

            $yz = self::sinAlt($date, $hour, $lon, $cglat, $sglat) - $sinho;
            $yp = self::sinAlt($date, $hour + 1, $lon, $cglat, $sglat) - $sinho;

            $quadout = self::quad($ym, $yz, $yp);
            $nz = $quadout[0];
            $z1 = $quadout[1];
            $z2 = $quadout[2];
            $xe = $quadout[3];
            $ye = $quadout[4];

            if ($nz == 1) {
                if ($ym < 0) {
                    $utrise = $hour + $z1;
                    $rise = true;
                } else {
                    $utset = $hour + $z1;
                    $set = true;
                }
            }

            if ($nz == 2) {
                if ($ye < 0) {
                    $utrise = $hour + $z2;
                    $utset = $hour + $z1;
                } else {
                    $utrise = $hour + $z1;
                    $utset = $hour + $z2;
                }
            }

            $ym = $yp;
            $hour += 2.0;

        }
        // Convert to unix timestamps and return as an object
        $retVal = new stdClass();
        $utrise = self::convertTime($utrise);
        $utset = self::convertTime($utset);
        $summertime = date("I");
        if($summertime == 0){
        $retVal->moonrise = $rise ? mktime($utrise['hrs'], $utrise['min'], 0+3600, $month, $day, $year) : mktime(0, 0, 0, $month, $day + 1, $year);
        $retVal->moonset = $set ? mktime($utset['hrs'], $utset['min'], 0+3600, $month, $day, $year) : mktime(0, 0, 0, $month, $day + 1, $year);
        }
         else{
         $retVal->moonrise = $rise ? mktime($utrise['hrs'], $utrise['min'], 0+7200, $month, $day, $year) : mktime(0, 0, 0, $month, $day + 1, $year);
        $retVal->moonset = $set ? mktime($utset['hrs'], $utset['min'], 0+7200, $month, $day, $year) : mktime(0, 0, 0, $month, $day + 1, $year);
        }
        return $retVal;

    }

    /**
     *    finds the parabola throuh the three points (-1,ym), (0,yz), (1, yp)
     *  and returns the coordinates of the max/min (if any) xe, ye
     *  the values of x where the parabola crosses zero (roots of the self::quadratic)
     *  and the number of roots (0, 1 or 2) within the interval [-1, 1]
     *
     *    well, this routine is producing sensible answers
     *
     *  results passed as array [nz, z1, z2, xe, ye]
     */
    private static function quad($ym, $yz, $yp) {

        $nz = $z1 = $z2 = 0;
        $a = 0.5 * ($ym + $yp) - $yz;
        $b = 0.5 * ($yp - $ym);
        $c = $yz;
        $xe = -$b / (2 * $a);
        $ye = ($a * $xe + $b) * $xe + $c;
        $dis = $b * $b - 4 * $a * $c;
        if ($dis > 0) {
            $dx = 0.5 * sqrt($dis) / abs($a);
            $z1 = $xe - $dx;
            $z2 = $xe + $dx;
            $nz = abs($z1) < 1 ? $nz + 1 : $nz;
            $nz = abs($z2) < 1 ? $nz + 1 : $nz;
            $z1 = $z1 < -1 ? $z2 : $z1;
        }

        return array($nz, $z1, $z2, $xe, $ye);

    }

    /**
     *    this rather mickey mouse function takes a lot of
     *  arguments and then returns the sine of the altitude of the moon
     */
    private static function sinAlt($mjd, $hour, $glon, $cglat, $sglat) {

        $mjd += $hour / 24;
        $t = ($mjd - 51544.5) / 36525;
        $objpos = self::minimoon($t);

        $ra = $objpos[1];
        $dec = $objpos[0];
        $decRad = deg2rad($dec);
        $tau = 15 * (self::lmst($mjd, $glon) - $ra);

        return $sglat * sin($decRad) + $cglat * cos($decRad) * cos(deg2rad($tau));

    }

    /**
     *    returns an angle in degrees in the range 0 to 360
     */
    private static function degRange($x) {
        $b = $x / 360;
        $a = 360 * ($b - (int)$b);
        $retVal = $a < 0 ? $a + 360 : $a;
        return $retVal;
    }

    private static function lmst($mjd, $glon) {
        $d = $mjd - 51544.5;
        $t = $d / 36525;
        $lst = self::degRange(280.46061839 + 360.98564736629 * $d + 0.000387933 * $t * $t - $t * $t * $t / 38710000);
        return $lst / 15 + $glon / 15;
    }

    /**
     * takes t and returns the geocentric ra and dec in an array mooneq
     * claimed good to 5' (angle) in ra and 1' in dec
     * tallies with another approximate method and with ICE for a couple of dates
     */
    private static function minimoon($t) {

        $p2 = 6.283185307;
        $arc = 206264.8062;
        $coseps = 0.91748;
        $sineps = 0.39778;

        $lo = self::frac(0.606433 + 1336.855225 * $t);
        $l = $p2 * self::frac(0.374897 + 1325.552410 * $t);
        $l2 = $l * 2;
        $ls = $p2 * self::frac(0.993133 + 99.997361 * $t);
        $d = $p2 * self::frac(0.827361 + 1236.853086 * $t);
        $d2 = $d * 2;
        $f = $p2 * self::frac(0.259086 + 1342.227825 * $t);
        $f2 = $f * 2;

        $sinls = sin($ls);
        $sinf2 = sin($f2);

        $dl = 22640 /*[Visualization\Mobile\Beschattung\Beschattungselemente\Kueche\Programme]*/ * sin($l);
        $dl += -4586 * sin($l - $d2);
        $dl += 2370 * sin($d2);
        $dl += 769 * sin($l2);
        $dl += -668 * $sinls;
        $dl += -412 * $sinf2;
        $dl += -212 * sin($l2 - $d2);
        $dl += -206 * sin ($l + $ls - $d2);
        $dl += 192 * sin($l + $d2);
        $dl += -165 * sin($ls - $d2);
        $dl += -125 * sin($d);
        $dl += -110 * sin($l + $ls);
        $dl += 148 * sin($l - $ls);
        $dl += -55 * sin($f2 - $d2);

        $s = $f + ($dl + 412 * $sinf2 + 541 * $sinls) / $arc;
        $h = $f - $d2;
        $n = -526 * sin($h);
        $n += 44 * sin($l + $h);
        $n += -31 * sin(-$l + $h);
        $n += -23 * sin($ls + $h);
        $n += 11 * sin(-$ls + $h);
        $n += -25 * sin(-$l2 + $f);
        $n += 21 * sin(-$l + $f);

        $L_moon = $p2 * self::frac($lo + $dl / 1296000);
        $B_moon = (18520.0 * sin($s) + $n) / $arc;

        $cb = cos($B_moon);
        $x = $cb * cos($L_moon);
        $v = $cb * sin($L_moon);
        $w = sin($B_moon);
        $y = $coseps * $v - $sineps * $w;
        $z = $sineps * $v + $coseps * $w;
        $rho = sqrt(1 - $z * $z);
        $dec = (360 / $p2) * atan($z / $rho);
        $ra = (48 / $p2) * atan($y / ($x + $rho));
        $ra = $ra < 0 ? $ra + 24 : $ra;

        return array($dec, $ra);

    }

    /**
     *    returns the self::fractional part of x as used in self::minimoon and minisun
     */
    private static function frac($x) {
        $x -= (int)$x;
        return $x < 0 ? $x + 1 : $x;
    }

    /**
     * Takes the day, month, year and hours in the day and returns the
     * modified julian day number defined as mjd = jd - 2400000.5
     * checked OK for Greg era dates - 26th Dec 02
     */
    private static function modifiedJulianDate($month, $day, $year) {

        if ($month <= 2) {
            $month += 12;
            $year--;
        }

        $a = 10000 /*[Info\FritzBox Project\WLAN 2,4 GHz - Status\SqueezeboxTouch (192.168.55.20)\Signalstärke]*/ * $year + 100 * $month + $day;
        $b = 0;
        if ($a <= 15821004.1) {
            $b = -2 * (int)(($year + 4716) / 4) - 1179;
        } else {
            $b = (int)($year / 400) - (int)($year / 100) + (int)($year / 4);
        }

        $a = 365 * $year - 679004;
        return $a + $b + (int)(30.6001 * ($month + 1)) + $day;

    }

    /**
     * Converts an hours decimal to hours and minutes
     */
    private static function convertTime($hours) {

        $hrs = (int)($hours * 60 + 0.5) / 60.0;
        $h = (int)($hrs);
        $m = (int)(60 * ($hrs - $h) + 0.5);
        return array('hrs'=>$h, 'min'=>$m);

    }
}

class IPSVarType extends stdClass
{

    const vtNone = -1;
    const vtBoolean = 0;
    const vtInteger = 1;
    const vtFloat = 2;
    const vtString = 3;
    

}

?>