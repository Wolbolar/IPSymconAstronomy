<?
// Modul Astronomie
// Formeln aus "Practical Astronomy" von Peter Duffett-Smith und Jonathan Zwart, Fourth Edition
// basiert auf den Skripten von ChokZul https://www.symcon.de/forum/threads/31467-Astronomische-Berechnungen?highlight=astronomie 

class Astronomy extends IPSModule
{

    public function Create()
    {
//Never delete this line!
        parent::Create();
		
		//These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
		
		$this->RegisterPropertyFloat("UTC", 1);
        $this->RegisterPropertyBoolean("juliandate", false);
		$this->RegisterPropertyBoolean("moonazimut", false);
		$this->RegisterPropertyBoolean("moondistance", false);
		$this->RegisterPropertyBoolean("moonaltitude", false);
		$this->RegisterPropertyBoolean("moonbrightlimbangle", false);
		$this->RegisterPropertyBoolean("moondirection", false);
		$this->RegisterPropertyBoolean("moonvisibility", false);
		$this->RegisterPropertyBoolean("moonrise", false);
		$this->RegisterPropertyBoolean("moonset", false);
		$this->RegisterPropertyBoolean("moonphase", false);
		$this->RegisterPropertyBoolean("newmoon", false);
		$this->RegisterPropertyBoolean("firstquarter", false);
		$this->RegisterPropertyBoolean("fullmoon", false);
		$this->RegisterPropertyBoolean("lastquarter", false);
		$this->RegisterPropertyBoolean("sunazimut", false);
		$this->RegisterPropertyBoolean("sundistance", false);
		$this->RegisterPropertyBoolean("sunaltitude", false);
		$this->RegisterPropertyBoolean("sundirection", false);
		$this->RegisterPropertyBoolean("season", false);
		$this->RegisterPropertyBoolean("picturemoon", false);
		$this->RegisterPropertyBoolean("sunmoonview", false);
    }

    public function ApplyChanges()
    {
	//Never delete this line!
        parent::ApplyChanges();
		
		$this->ValidateConfiguration();
		$this->RegisterTimer('Update', 360000, 'Astrononmy_SetAstronomyValues($id)');	
		$this->SetAstronomyValues();
	
    }

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        *
        */
		
	private function ValidateConfiguration()
	{
		
		if($this->ReadPropertyBoolean("juliandate") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Julianisches_Datum", "Calendar", "", " Tage", 0, 0, 0, 1, $associations);
			$this->SetupVariable("juliandate", "Julianisches Datum", "Astronomie.Julianisches_Datum", 1, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("juliandate", "Julianisches Datum", "Astronomie.Julianisches_Datum", 1, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("moonazimut") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Mond_Azimut", "Moon", "", "°", 0, 0, 0, 2, $associations);
			$this->SetupVariable("moonazimut", "Mond Azimut", "Astronomie.Mond_Azimut", 2, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("moonazimut", "Mond Azimut", "Astronomie.Mond_Azimut", 2, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("moondistance") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Mond_Entfernung", "Moon", "", " km", 0, 0, 0, 0, $associations);
			$this->SetupVariable("moondistance", "Mond Entfernung", "Astronomie.Mond_Entfernung", 3, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("moondistance", "Mond Entfernung", "Astronomie.Mond_Entfernung", 3, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("moonaltitude") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Mond_Hoehe", "Moon", "", "°", 0, 0, 0, 2, $associations);
			$this->SetupVariable("moonaltitude", "Mond Höhe", "Astronomie.Mond_Hoehe", 4, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("moonaltitude", "Mond Höhe", "Astronomie.Mond_Hoehe", 4, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("moonbrightlimbangle") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Mond_Positionswinkel", "Moon", "", "°", 0, 0, 0, 2, $associations);
			$this->SetupVariable("moonbrightlimbangle", "Mond Positionswinkel der beleuchteten Fläche", "Astronomie.Mond_Positionswinkel", 5, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("moonbrightlimbangle", "Mond Positionswinkel der beleuchteten Fläche", "Astronomie.Mond_Positionswinkel", 5, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("moondirection") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Mond_Himmelsrichtung", "Moon", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("moondirection", "Mond Richtung", "Astronomie.Mond_Himmelsrichtung", 6, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("moondirection", "Mond Richtung", "Astronomie.Mond_Himmelsrichtung", 6, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("moonvisibility") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Mond_Sichtbarkeit", "Moon", "", " %", 0, 0, 0, 1, $associations);
			$this->SetupVariable("moonvisibility", "Mond Sichtbarkeit", "Astronomie.Mond_Sichtbarkeit", 7, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("moonvisibility", "Mond Sichtbarkeit", "Astronomie.Mond_Sichtbarkeit", 7, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("moonrise") == true) // int
		{
			$objid = $this->SetupVariable("moonrise", "Mondaufgang", "~UnixTimestampTime", 8, IPSVarType::vtInteger, true);
			IPS_SetIcon($objid, "Moon");
		}
		else
		{
			$this->SetupVariable("moonrise", "Mondaufgang", "~UnixTimestampTime", 8, IPSVarType::vtInteger, false);
		}
		if($this->ReadPropertyBoolean("moonset") == true) // int
		{
			$objid = $this->SetupVariable("moonset", "Monduntergang", "~UnixTimestampTime", 9, IPSVarType::vtInteger, true);
			IPS_SetIcon($objid, "Moon");
		}
		else
		{
			$this->SetupVariable("moonset", "Monduntergang", "~UnixTimestampTime", 9, IPSVarType::vtInteger, false);
		}
		if($this->ReadPropertyBoolean("moonphase") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Mond_Phase", "Moon", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("moonphase", "Mond Phase", "Astronomie.Mond_Phase", 10, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("moonphase", "Mond Phase", "Astronomie.Mond_Phase", 10, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("newmoon") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Mond_Neumond", "Moon", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("newmoon", "Neumond", "Astronomie.Mond_Neumond", 11, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("newmoon", "Neumond", "Astronomie.Mond_Neumond", 11, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("firstquarter") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Mond_ErstesViertel", "Moon", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("firstquarter", "Erstes Viertel", "Astronomie.Mond_ErstesViertel", 12, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("firstquarter", "Erstes Viertel", "Astronomie.Mond_ErstesViertel", 12, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("fullmoon") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Mond_Vollmond", "Moon", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("fullmoon", "Vollmond", "Astronomie.Mond_Vollmond", 13, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("fullmoon", "Vollmond", "Astronomie.Mond_Vollmond", 13, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("lastquarter") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Mond_LetztesViertel", "Moon", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("lastquarter", "Letztes Viertel", "Astronomie.Mond_LetztesViertel", 14, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("lastquarter", "Letztes Viertel", "Astronomie.Mond_LetztesViertel", 14, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("sunazimut") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Sonne_Azimut", "Sun", "", "°", 0, 0, 0, 2, $associations);
			$this->SetupVariable("sunazimut", "Sonne Azimut", "Astronomie.Sonne_Azimut", 15, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("sunazimut", "Sonne Azimut", "Astronomie.Sonne_Azimut", 15, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("sundistance") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Sonne_Entfernung", "Sun", "", " km", 0, 0, 0, 0, $associations);
			$this->SetupVariable("sundistance", "Sonne Entfernung", "Astronomie.Sonne_Entfernung", 16, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("sundistance", "Sonne Entfernung", "Astronomie.Sonne_Entfernung", 16, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("sunaltitude") == true) // float
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtFloat, "Astronomie.Sonne_Hoehe", "Sun", "", "°", 0, 0, 0, 2, $associations);
			$this->SetupVariable("sunaltitude", "Sonne Höhe", "Astronomie.Sonne_Hoehe", 17, IPSVarType::vtFloat, true);
		}
		else
		{
			$this->SetupVariable("sunaltitude", "Sonne Höhe", "Astronomie.Sonne_Hoehe", 17, IPSVarType::vtFloat, false);
		}
		if($this->ReadPropertyBoolean("sundirection") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Sonne_Himmelsrichtung", "Sun", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("sundirection", "Sonne Richtung", "Astronomie.Sonne_Himmelsrichtung", 18, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("sundirection", "Sonne Richtung", "Astronomie.Sonne_Himmelsrichtung", 18, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("season") == true) // string
		{
			$associations =  Array(	);
			$this->SetupProfile(IPSVarType::vtString, "Astronomie.Jahreszeit", "Sun", "", "", 0, 0, 0, 0, $associations);
			$this->SetupVariable("season", "Jahreszeit", "Astronomie.Jahreszeit", 20, IPSVarType::vtString, true);
		}
		else
		{
			$this->SetupVariable("season", "Jahreszeit", "Astronomie.Jahreszeit", 20, IPSVarType::vtString, false);
		}
		if($this->ReadPropertyBoolean("picturemoon") == true) // string
		{
			$mondphase = $this->MoonphasePercent();
			$picture = $this->GetMoonPicture($mondphase);
			$objid = $this->UpdateMedia($picture["picid"]);
			IPS_SetIcon($objid, "Moon");
		}
		else
		{
			$MediaID = @$this->GetIDForIdent('picturemoon');
			if($MediaID > 0)
				IPS_DeleteMedia($MediaID, true);
		}
		if($this->ReadPropertyBoolean("sunmoonview") == true) // string
		{
			$objid = $this->SetupVariable("sunmoonview", "Position Sonne und Mond", "~HTMLBox", 22, IPSVarType::vtString, true);
			IPS_SetIcon($objid, "Sun");
		}
		else
		{
			$this->SetupVariable("sunmoonview", "Position Sonne und Mond", "~HTMLBox", 22, IPSVarType::vtString, false);
		}
			
		// Status Aktiv
		$this->SetStatus(102);	
		
	}
	
	protected function UpdateMedia($picid)
	{
		//testen ob im Medienpool existent
			$modulid = $this->InstanceID;
			$repository = "github"; //bitbucket, github
			if ($repository == "github")
			{
				$ImageFile = IPS_GetKernelDir()."modules".DIRECTORY_SEPARATOR."IPSymconAstronomy".DIRECTORY_SEPARATOR."Astronomy".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."mond".DIRECTORY_SEPARATOR."mond".$picid.".gif";  // Image-Datei
			}
			elseif($repository == "bitbucket")
			{
				$ImageFile = IPS_GetKernelDir()."modules".DIRECTORY_SEPARATOR."ipsymconastronomy".DIRECTORY_SEPARATOR."Astronomy".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."mond".DIRECTORY_SEPARATOR."mond".$picid.".gif";  // Image-Datei		
			}
			$Content = @Sys_GetURLContent($ImageFile); 
			$MediaID = @$this->GetIDForIdent('picturemoon');
			if ($MediaID === false)
				{
					$MediaID = IPS_CreateMedia(1);                  // Image im MedienPool anlegen
					IPS_SetParent($MediaID, $modulid); // Medienobjekt einsortieren unter dem Modul
					IPS_SetIdent ($MediaID, "picturemoon");
					IPS_SetPosition($MediaID, 21);
					IPS_SetMediaCached($MediaID, true);
					// Das Cachen für das Mediaobjekt wird aktiviert.
					// Beim ersten Zugriff wird dieses von der Festplatte ausgelesen
					// und zukünftig nur noch im Arbeitsspeicher verarbeitet.
					IPS_SetName($MediaID, "Mond Ansicht"); // Medienobjekt benennen
				}
			
			IPS_SetMediaFile($MediaID, $ImageFile, False);    // Image im MedienPool mit Image-Datei verbinden
			IPS_SetInfo ($MediaID, $picid);
			IPS_SetMediaContent($MediaID, base64_encode($Content));  //Bild Base64 codieren und ablegen
			IPS_SendMediaEvent($MediaID); //aktualisieren
			return $MediaID;
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
	
	protected function SunMoonView($sunazimut, $sunaltitude, $moonazimut, $moonaltitude)
	{
		// Anzeige der Position von Sonne und Mond im WF
		// Erstellung der Grafik mit "Canvas" (HTML-Element)
		// siehe https://de.wikipedia.org/wiki/Canvas_(HTML-Element)
		// 2016-04-25 Bernd Hoffmann

		//Daten für Nullpunkt usw.------------------------------------------------------
		$npx = 50;        //Nullpunkt x-achse
		$npy = 50;        //Nullpunkt y-achse
		$z = 40;           //Offset y-achse

		$lWt = 2;         //Linienstärke Teilstriche
		$lWh = 2;         //Linienstärke Horizontlinie

		//Waagerechte Linie-------------------------------------------------------------
		$l1 = 360;        //Länge der Horizontlinie

		$x1 = $npx;            //Nullpunkt waagerecht
		$y1 = $npy+$z;        //Nullpunkt senkrecht
		$x2 = $x1+$l1;        //Nullpunkt + Länge = waagerechte Linie
		$y2 = $npy+$z;

		//Teilstriche-------------------------------------------------------------------
		$l2 = 10;         //Länge der Teilstriche
		//N 0°
		$x3 = $npx;           //Nullpunkt waagerecht
		$y3 = $y1-$l2/2;    //Nullpunkt senkrecht
		$x4 = $x3;
		$y4 = $y3+$l2;        //Nullpunkt + Länge = senkrechte Linie
		//O
		$x5 = $npx+90;
		$y5 = $y1-$l2/2;
		$x6 = $x5;
		$y6 = $y5+$l2;
		//S
		$x7 = $npx+180;
		$y7 = $y1-$l2/2;
		$x8 = $x7;
		$y8 = $y7+$l2;
		//W
		$x9 = $npx+270;
		$y9 = $y1-$l2/2;
		$x10 = $x9;
		$y10 = $y9+$l2;
		//N 360°
		$x11 = $npx+360;
		$y11 = $y1-$l2/2;
		$x12 = $x11;
		$y12 = $y11+$l2;

		//Daten von Sonne und Mond holen------------------------------------------------
		$xsun = round($npx + $sunazimut);
		$ysun = round($npy + $z - $sunaltitude);

		$xmoon = round($npx + $moonazimut);
		$ymoon = round($npy + $z - $moonaltitude);

		//Erstellung der Html Datei-----------------------------------------------------
		$html =
		'<html>

		<head>
		<script type="text/javascript">

		function draw(){
		var canvas = document.getElementById("canvas1");
		if(canvas.getContext){
			var ctx = canvas.getContext("2d");

			ctx.lineWidth = '.$lWt.'; //Teilstriche
			ctx.strokeStyle = "rgb(51,102,255)";
			ctx.beginPath();
			ctx.moveTo('.$x3.','.$y3.');
			ctx.lineTo('.$x4.','.$y4.');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo('.$x5.','.$y5.');
			ctx.lineTo('.$x6.','.$y6.');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo('.$x7.','.$y7.');
			ctx.lineTo('.$x8.','.$y8.');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo('.$x9.','.$y9.');
			ctx.lineTo('.$x10.','.$y10.');
			ctx.stroke();
			ctx.beginPath();
			ctx.moveTo('.$x11.','.$y11.');
			ctx.lineTo('.$x12.','.$y12.');
			ctx.stroke();
			
			ctx.lineWidth = 2; //Text
			ctx.fillStyle = "rgb(139,115,85)";
			ctx.beginPath();
			ctx.font = "18px calibri";
		   ctx.fillText("N", '.$x4.'-6,'.$y4.'+15);
		   ctx.fillText("O", '.$x6.'-6,'.$y6.'+15);
		   ctx.fillText("S", '.$x8.'-5,'.$y8.'+15);
		   ctx.fillText("W", '.$x10.'-8,'.$y10.'+15);
		   ctx.fillText("N", '.$x12.'-6,'.$y12.'+15);
		   ctx.font = "16px calibri";
		   ctx.fillText("Horizont", '.$x1.'+368,'.$y1.'+5);
		   
			ctx.lineWidth = '.$lWh.'; //Horizontlinie
			ctx.strokeStyle = "rgb(51,102,255)";
			ctx.beginPath();
			ctx.moveTo('.$x1.','.$y1.');
			ctx.lineTo('.$x2.','.$y2.');
			ctx.stroke();
			
			ctx.lineWidth = 1; //Mond
			ctx.fillStyle = "rgb(255,255,255)";
			ctx.beginPath();
		   ctx.arc('.$xmoon.','.$ymoon.',10,0,Math.PI*2,true);
		   ctx.fill();
		   
		   ctx.lineWidth = 1; //Sonne
			ctx.fillStyle = "rgb(255,255,102)";
			ctx.beginPath();
		   ctx.arc('.$xsun.','.$ysun.',18,0,Math.PI*2,true);
		   ctx.fill();
			}
		}

		</script>
		</head>

		<body onload="draw()">
		<canvas id="canvas1" width="800" height="250" > //style="border:1px solid yellow;"
		</canvas>
		</body>

		</html>';

		//Erstellen des Dateinamens, abspeichern und Aufruf in <iframe>-----------------
		$filename = "sunmoonline.php";
		$fullFilename = IPS_GetKernelDir()."webfront".DIRECTORY_SEPARATOR."user".DIRECTORY_SEPARATOR.$filename;
		$handle = fopen($fullFilename, "w");
		fwrite($handle, $html);
		fclose($handle);
		$HTMLData = '<iframe src="user'.DIRECTORY_SEPARATOR.'sunmoonline.php" border="0" frameborder="0" style= "width: 100%; height:280px;"/></iframe>';
		if($this->ReadPropertyBoolean("sunmoonview") == true)
		{
			SetValue($this->GetIDForIdent("sunmoonview"), $HTMLData);
		}
	}
	
	public function SetAstronomyValues()
	{
		$location = $this->getlocation();
		$Latitude = $location["Latitude"];
		$Longitude = $location["Longitude"];
		$P = $Latitude;
		$L = $Longitude;
		$day = date("d");
		$month = date("m");
		$year = date("Y");
		$Hour = date("H");
		$Minute = date("i");
		$Second = date("s");
		$summer = date("I");
		if ($summer == 0)
			{   //Summertime
				$DS = 0;
			}         //$DS = Daylight Saving
			else
			{
				$DS = 1;
			}
		$ZC = $this->ReadPropertyFloat("UTC"); // Zone Correction to Greenwich: 1 = UTC+1
		
	
		$timestamp = time();
		$mondphase = $this->moon_phase(date('Y', $timestamp), date('n', $timestamp), date('j', $timestamp));
		$this->Mondaufgang();
		$this->Monduntergang();
		$mondphase = $this->MoonphasePercent();
		$picture = $this->GetMoonPicture($mondphase);
		$this->CalculateMoonphase();
		$this->MoonphaseText();
		$this->UpdateMedia($picture["picid"]);
		
		
		$HMSDec = $this->HMSDH($Hour, $Minute, $Second); //Local Time HMS in Decimal Hours
		$UTDec = $this->LctUT($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year)[0];
		$GD = $this->LctUT($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year)[1];
		$GM = $this->LctUT($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year)[2];
		$GY = $this->LctUT($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year)[3];
		$JD = $this->CDJD($GD, $GM, $GY);  //UT Julian Date
		if($this->ReadPropertyBoolean("juliandate") == true)
		{
			SetValue($this->GetIDForIdent("juliandate"), $JD);
		}
		$LCH = $this->DHHour($HMSDec);  //LCT Hour --> Local Time
		$LCM = $this->DHMin($HMSDec);   //LCT Minute
		$LCS = $this->DHSec($HMSDec);   //LCT Second
		//Universal Time
		$UH = $this->DHHour($UTDec);      //UT Hour
		$UM = $this->DHMin($UTDec);    //UT Minute
		$US = $this->DHSec($UTDec);    //UT Second
		$UT_value = $UH.":".$UM.":".$US;
		$UDate_value = $GD.":".$GM.":".$GY;

		
		//Calculation Sun---------------------------------------------------------------

		//Sun's ecliptic longitude in decimal degrees
		$Sunlong = $this->SunLong($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);
		//echo $Sunlong."\n";
		$SunlongDeg = $this->DDDeg($Sunlong);
		$SunlongMin = $this->DDMin($Sunlong);
		$SunlongSec = $this->DDSec($Sunlong);

		//Sun's RA
		$SunRAh = $this->DDDH($this->ECRA($SunlongDeg, $SunlongMin, $SunlongSec, 0, 0, 0, $GD, $GM, $GY));    //returns RA in hours
		$SunRAhour = $this->DHHour($SunRAh);
		$SunRAm = $this->DHmin($SunRAh);
		$SunRAs = $this->DHSec($SunRAh);
		$SunRAhms = $SunRAhour.":".$SunRAm.":".$SunRAs;

		$season = "";
		if(($SunRAh>=0)and($SunRAh<6)){$season = "Frühling";}        //Frühling
		if(($SunRAh>=6)and($SunRAh<12)){$season = "Sommer";}        //Sommer
		if(($SunRAh>=12)and($SunRAh<18)){$season = "Herbst";}        //Herbst
		if(($SunRAh>=18)and($SunRAh<24)){$season = "Winter";}        //Winter
		if($this->ReadPropertyBoolean("season") == true) // float
		{
			SetValue($this->GetIDForIdent("season"), $season); 
		}

		//Sun's Dec
		$SunDec = $this->ECDec($SunlongDeg, $SunlongMin, $SunlongSec, 0, 0, 0, $GD, $GM, $GY);            //returns declination in decimal degrees
		$SunDecd = $this->DDDeg($SunDec);
		$SunDecm = $this->DDmin($SunDec);
		$SunDecs = $this->DDSec($SunDec);
		$SunDecdms = $SunDecd.":".$SunDecm.":".$SunDecs;
		//echo $SunDecdms."\n";

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


		$SunDist = $this->SunDist($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year);
		$SunTA = $this->Radians($this->SunTrueAnomaly($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year));
		$SunEcc = $this->SunEcc($GD, $GM, $GY);
		$fSun = (1 + $SunEcc * cos($SunTA))/(1 - $SunEcc * $SunEcc);
		$rSun = round(149598500/$fSun,-2);

		if($this->ReadPropertyBoolean("sunazimut") == true) // float
		{
			SetValue($this->GetIDForIdent("sunazimut"), $sunazimut); 
		}
		if($this->ReadPropertyBoolean("sundirection") == true) // float
		{
			SetValue($this->GetIDForIdent("sundirection"), $SunDazimut); 
		}
		if($this->ReadPropertyBoolean("sunaltitude") == true) // float
		{
			SetValue($this->GetIDForIdent("sunaltitude"), $sunaltitude);
		}
		if($this->ReadPropertyBoolean("sundistance") == true) // float
		{
			SetValue($this->GetIDForIdent("sundistance"), $rSun);
		}
		
		//Calculation Moon--------------------------------------------------------------

		$MoonLong = $this->MoonLong($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year); //Moon ecliptic longitude (degrees)
		//echo $MoonLong."\n";
		$MoonLat = $this->MoonLat($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year); //Moon elciptic latitude (degrees)
		//echo $MoonLat."\n";
		$Nutation = $this->NutatLong($GD, $GM, $GY); //nutation in longitude (degrees)
		//echo $Nutation."\n";
		$Moonlongcorr = $MoonLong + $Nutation; //corrected longitude (degrees)
		$MoonHP = $this->MoonHP($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);    //Moon's horizontal parallax (degrees)
		//echo $MoonHP."\n";
		$MoonDist = $this->MoonDist($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);   //Moon Distance to Earth
		//echo round($MoonDist)."\n";
		$Moonphase = $this->MoonPhase($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year); //Moonphase in %
		//echo $Moonphase."\n";
		$Moonpabl = $this->MoonPABL($LCH, $LCM, $LCS, $DS, $ZC, $day, $month, $year);   //Moon Bright Limb Angle (degrees)

		if($Moonpabl<0){$Moonpabl = $Moonpabl+360;}
		else{$Moonpabl = $Moonpabl;}
		
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
		//echo $MoonRA."\n";
		$MoonRAh = $this->DHHour($MoonRA);    //Right Ascension Hours
		$MoonRAm = $this->DHMin($MoonRA);
		$MoonRAs = $this->DHSec($MoonRA);
		//echo $MoonRAh.":".$MoonRAm.":".$MoonRAs."\n";
		$MoonDECd = $this->DDDeg($MoonDec);  //Declination Degrees
		$MoonDECm = $this->DDMin($MoonDec);
		$MoonDECs = $this->DDSec($MoonDec);
		//echo $MoonDECd.":".$MoonDECm.":".$MoonDECs."\n";

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

		if($this->ReadPropertyBoolean("moonazimut") == true) // float
		{
			SetValue($this->GetIDForIdent("moonazimut"), $moonazimut);
		}
		if($this->ReadPropertyBoolean("moonaltitude") == true) // float
		{
			SetValue($this->GetIDForIdent("moonaltitude"), $moonaltitude);
		}
		if($this->ReadPropertyBoolean("moondirection") == true) // float
		{
			SetValue($this->GetIDForIdent("moondirection"), $dazimut);
		}
		if($this->ReadPropertyBoolean("moondistance") == true) // float
		{
			SetValue($this->GetIDForIdent("moondistance"), $MoonDist);
		}
		if($this->ReadPropertyBoolean("moonvisibility") == true) // float
		{
			SetValue($this->GetIDForIdent("moonvisibility"), $Moonphase);
		}
		if($this->ReadPropertyBoolean("moonbrightlimbangle") == true) // float
		{
			SetValue($this->GetIDForIdent("moonbrightlimbangle"), $Moonpabl);
		}
		
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

	//FormelScript zur Berechnung von Astronomischen Ereignissen
	//nach dem Buch "Practical Astronomy with your Calculator or Spreadsheet"
	//von Peter Duffet-Smith und Jonathan Zwart
	



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


	protected function direction($degree)
	{
		
		if(($degree >= 0)and($degree < 22.5)){
			$direction = "N";
			}
		if(($degree >= 22.5)and($degree < 45)){
		   $direction = "NNE";
			}
		if(($degree >= 45)and($degree < 67.5)){
		   $direction = "NE";
			}
		if(($degree >= 67.5)and($degree < 90)){
			$direction = "ENE";
			}
		if(($degree >= 90)and($degree < 112.5)){
		   $direction = "E";
			}
		if(($degree >= 112.5)and($degree < 135)){
		   $direction = "ESE";
			}
		if(($degree >= 135)and($degree < 157.5)){
		   $direction = "SE";
			}
		if(($degree >= 157.5)and($degree < 180)){
		   $direction = "SSE";
			}
		if(($degree >= 180)and($degree < 202.5)){
		   $direction = "S";
			}
		if(($degree >= 202.5)and($degree < 225)){
		   $direction = "SSW";
			}
		if(($degree >= 225)and($degree < 247.5)){
		   $direction = "SW";
			}
		if(($degree >= 247.5)and($degree < 270)){
		   $direction = "WSW";
			}
		if(($degree >= 270)and($degree < 292.5)){
		   $direction = "W";
			}
		if(($degree >= 292.5)and($degree < 315)){
		   $direction = "WNW";
			}
		if(($degree >= 315)and($degree < 337.5)){
			$direction = "NW";
			}
		if(($degree >= 337.5)and($degree <= 360)){
		   $direction = "NNW";
			}
		return ($direction);
	}


	// Greenwich calendar date to Julian date conversion
	public function CDJD(int $day, int $month, int $year)
	{
		if ($month < 3){
			$Y = $year - 1;
			$M = $month + 12;}
		else{
			$Y = $year;
			$M = $month;}

		if ($year > 1582){
			$A = $this->roundvariantfix($Y / 100);
			$B = 2 - $A + $this->roundvariantfix($A / 4);}
		else{
		 if (($year == 1582) And ($month > 10)){
			$A = $this->roundvariantfix($Y / 100);
			$B = 2 - $A + $this->roundvariantfix($A / 4);}
		 else{
		  if (($year == 1582) And ($month == 10) And ($day >= 15)){
			$A = $this->roundvariantfix($Y / 100);
			$B = 2 - $A + $this->roundvariantfix($A / 4);}
		  else{
			$B = 0;}
				}
				}

		if ($Y < 0){
			$C = $this->roundvariantfix((365.25 * $Y) - 0.75);}
		else{
			$C = $this->roundvariantfix(365.25 * $Y);}

		$D = $this->roundvariantfix(30.6001 * ($M + 1));
		$JD = $B + $C + $D + $day + 1720994.5;
		return ($JD);
	}
	
	

	// Julian date to Greenwich calendar date conversion
	public function JDCD(float $JD)
	{
		$day = $this->JDCDay($JD);
		$month = $this->JDCMonth($JD);
		$year = $this->JDCYear($JD);
		$dateCD = array ("day" => $day, "month" => $month, "year" => $year);
		return $dateCD;
	}
	
	protected function JDCDay(float $JD)
	{
		$I = $this->roundvariantfix($JD + 0.5);
		$F = $JD + 0.5 - $I;
		$A = $this->roundvariantfix(($I - 1867216.25) / 36524.25);

		if ($I > 2299160){
			$B = $I + 1 + $A - $this->roundvariantfix($A / 4);}
		else{
			$B = $I;}

		$C = $B + 1524;
		$D = $this->roundvariantfix(($C - 122.1) / 365.25);
		$E = $this->roundvariantfix(365.25 * $D);
		$G = $this->roundvariantfix(($C - $E) / 30.6001);
		$JDCDay = $C - $E + $F - $this->roundvariantfix(30.6001 * $G);
		return ($JDCDay);
	}

	protected function JDCMonth(float $JD)
	{
		$I = $this->roundvariantfix($JD + 0.5);
		$F = $JD + 0.5 - $I;
		$A = $this->roundvariantfix(($I - 1867216.25) / 36524.25);

		if ($I > 2299160){
			$B = $I + 1 + $A - $this->roundvariantfix($A / 4);}
		else{
			$B = $I;}

		$C = $B + 1524;
		$D = $this->roundvariantfix(($C - 122.1) / 365.25);
		$E = $this->roundvariantfix(365.25 * $D);
		$G = $this->roundvariantfix(($C - $E) / 30.6001);

		if ($G < 13.5){
			$JDCMonth = $G - 1;}
		else{
			$JDCMonth = $G - 13;}
		return ($JDCMonth);
	}

	protected function JDCYear($JD)
	{
		$I = $this->roundvariantfix($JD + 0.5);
		$F = $JD + 0.5 - $I;
		$A = $this->roundvariantfix(($I - 1867216.25) / 36524.25);

		if ($I > 2299160){
			$B = $I + 1 + $A - $this->roundvariantfix($A / 4);}
		else{
			$B = $I;}

		$C = $B + 1524;
		$D = $this->roundvariantfix(($C - 122.1) / 365.25);
		$E = $this->roundvariantfix(365.25 * $D);
		$G = $this->roundvariantfix(($C - $E) / 30.6001);

		if ($G < 13.5){
			$H = $G - 1;}
		else{
			$H = $G - 13;}

		if ($H > 2.5){
			$JDCYear = $D - 4716;}
		else{
			$JDCYear = $D - 4715;}
		return ($JDCYear);
	}

	// Converting hours, minutes and seconds to decimal hours
	public function HMSDH(int $Hour, int $Minute, int $Second)
	{
		$A = abs($Second) / 60;
		$B = (abs($Minute) + $A) / 60;
		$C = abs($Hour) + $B;

		if (($Hour < 0) or ($Minute < 0) or ($Second < 0)){
			$HMSDH = $C * (-1);}
		else{
			$HMSDH = $C;}
		return ($HMSDH);
	}
	
	// Converting decimal hours to hours, minutes and seconds
	public function DHHMS(float $DH)
	{
		$hours = $this->DHHour($DH);
		$minutes = $this->DHMin($DH);
		$seconds = $this->DHSec($DH);
		$HMS = array ("hours" => $hours, "minutes" => $minutes, "seconds" => $seconds);
		return $HMS;
	}
	
	//Decimal Hours to Hours
	protected function DHHour(float $DH)
	{
		$A = abs($DH);
		$B = $A * 3600;
		$C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

		if ($C == 60){
			$D = 0;
			$E = $B + 60;}
		else{
			$D = $C;
			$E = $B;}

		if ($DH < 0){
			$DHHour = $this->roundvariantfix($E / 3600) * (-1);}
		else{
			$DHHour = $this->roundvariantfix($E / 3600);}
		return ($DHHour);
	}

	protected function DHMin($DH)
	{
		$A = abs($DH);
		$B = $A * 3600;
		$C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

		if ($C == 60){
			$D = 0;
			$E = $B + 60;}
		else{
			$D = $C;
			$E = $B;}
			
		$DHMin = fmod(floor($E / 60), 60);
		return($DHMin);
	}

	protected function DHSec($DH)
	{
		$A = abs($DH);
		$B = $A * 3600;
		$C = round($B - 60 * $this->roundvariantfix($B / 60), 2);
		if ($C == 60){
			$D = 0;}
		else{
			$D = $C;}

		$DHSec = $D;
		return ($DHSec);
	}

	// Conversion of Local Civil Time to UT (Universal Time) --- Achtung: hier wird ein Array ausgegeben !!!
	public function LctUT($Hour, $Minute, $Second, $DS, $ZC, $day, $month, $year)
	{
		$A = $this->HMSDH($Hour, $Minute, $Second);     //LCT
		$B = $A - $DS - $ZC;                   //UT
		$C = $day + ($B / 24);                 //G day
		$D = $this->CDJD($C, $month, $year);  //JD
		$E = $this->JDCDay($D);                       //G day
		$F = $this->JDCMonth($D);                    //G month
		$G = $this->JDCYear($D);                      //G year
		$E1 = $this->roundvariantfix($E);
		$LctUT = 24 * ($E - $E1);
		return array($LctUT,$E1,$F,$G);
	}

	// Conversion of UT (Universal Time) to Local Civil Time --- Achtung: hier wird ein Array ausgegeben !!!
	public function UTLct($UH, $UM, $US, $DS, $ZC, $GD, $GM, $GY)
	{
		$A = $this->HMSDH($UH, $UM, $US);
		$B = $A + $ZC;
		$C = $B + $DS;
		$D = $this->CDJD($GD, $GM, $GY) + ($C / 24);
		$E = $this->JDCDay($D);
		$F = $this->JDCMonth($D);
		$G = $this->JDCYear($D);
		$E1 = $this->roundvariantfix($E);
		$UTLct = 24 * ($E - $E1);
		return array($UTLct,$E1,$F,$G);
	}

	//Conversion of UT to GST (Greenwich Sideral Time)
	public function UTGST($UH, $UM, $US, $GD, $GM, $GY)
	{
		$A = $this->CDJD($GD, $GM, $GY);
		$B = $A - 2451545;
		$C = $B / 36525;
		$D = 6.697374558 + (2400.051336 * $C) + (0.000025862 * $C * $C);
		$E = $D - (24 * $this->roundvariantint($D / 24));
		$F = $this->HMSDH($UH, $UM, $US);
		$G = $F * 1.002737909;
		$H = $E + $G;
		$UTGST = $H - (24 * $this->roundvariantint($H / 24));
		return ($UTGST);
	}

	//Conversion of GST to UT --- Achtung: hier wird ein Array ausgegeben !!!
	public function GSTUT($GSH, $GSM, $GSS, $GD, $GM, $GY)
	{
		$A = $this->CDJD($GD, $GM, $GY);
		$B = $A - 2451545;
		$C = $B / 36525;
		$D = 6.697374558 + (2400.051336 * $C) + (0.000025862 * $C * $C);
		$E = $D - (24 * $this->roundvariantint($D / 24));
		$F = $this->HMSDH($GSH, $GSM, $GSS);
		$G = $F - $E;
		$H = $G - (24 * $this->roundvariantint($G / 24));
		$GSTUT = $H * 0.9972695663;
		if ($GSTUT < (4 / 60)){
			$eGSTUT = "Warning";}
		else{
			$eGSTUT = "OK";}
		return array($GSTUT,$eGSTUT);
	}

	//Conversion of GST to LST (Local Sideral Time)
	public function GSTLST($GH, $GM, $GS, $L)
	{
		$A = $this->HMSDH($GH, $GM, $GS);
		$B = $L / 15;
		$C = $A + $B;
		$GSTLST = $C - (24 * $this->roundvariantint($C / 24));
		return ($GSTLST);
	}

	//Conversion of LST to GST (Greenwich Sideral Time)
	public function LSTGST($LH, $LM, $LS, $L)
	{
		$A = $this->HMSDH($LH, $LM, $LS);
		$B = $L / 15;
		$C = $A - $B;
		$LSTGST = $C - (24 * $this->roundvariantint($C / 24));
		return ($LSTGST);
	}

	public function UTDayAdjust($UT, $G1)
	{
			$UTDayAdjust = $UT;

			if (($UT - $G1) < -6){
				$UTDayAdjust = $UT + 24;
					}

			if (($UT - $G1) > 6){
				$UTDayAdjust = $UT - 24;
					}
		return ($UTDayAdjust);
	}

	//Converting degrees, minutes and seconds to decimal degrees
	public function DMSDD($D, $M, $S)
	{
		$A = abs($S) / 60;
		$B = (abs($M) + $A) / 60;
		$C = abs($D) + $B;

		if (($D < 0) Or ($M < 0) Or ($S < 0)){
			$DMSDD = $C * (-1);}
		else{
			$DMSDD = $C;}
		return ($DMSDD);
	}

	//decimal degrees to degrees
	protected function DDDeg($DD)
	{
		$A = abs($DD);
		$B = $A * 3600;
		$C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

		if ($C == 60){
			$D = 0;
			$E = $B + 60;}
		else{
			$D = $C;
			$E = $B;}

		if ($DD < 0){
			$DDDeg = $this->roundvariantfix($E / 3600) * (-1);}
		else{
			$DDDeg = $this->roundvariantfix($E / 3600);}
		return ($DDDeg);
	}

	//decimal degrees to minutes
	protected function DDMin($DD)
	{
		$A = abs($DD);
		$B = $A * 3600;
		$C = round($B - 60 * $this->roundvariantfix($B / 60), 2);

		if ($C == 60){
			$D = 0;
			$E = $B + 60;}
		else{
			$D = $C;
			$E = $B;}

		$DDMin = fmod(floor($E / 60), 60);
		return ($DDMin);
	}

	//decimal degrees to seconds
	protected function DDSec($DD)
	{
		$A = abs($DD);
		$B = $A * 3600;
		$C = round($B - 60 * $this->roundvariantfix($B / 60), 2);
		if ($C == 60){
			$D = 0;}
		else{
			$D = $C;}

		$DDSec = $D;
		return ($DDSec);
	}

	//Decimal Degrees to Decimal Hours
	protected function DDDH($DD)
	{
		$DDDH = $DD / 15;
		return ($DDDH);
	}

	//Decimal Hours to Decimal Degrees
	protected function DHDD($DH)
	{
		$DHDD = $DH * 15;
		return ($DHDD);
	}

	protected function LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
	{
		$A = $this->HMSDH($LCH, $LCM, $LCS);
		$B = $A - $DS - $ZC;
		$C = $LD + ($B / 24);
		$D = $this->CDJD($C, $LM, $LY);
		$E = $this->JDCDay($D);
		$LctGDay = $this->roundvariantfix($E);
		return ($LctGDay);
	}

	protected function LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
	{
		$A = $this->HMSDH($LCH, $LCM, $LCS);
		$B = $A - $DS - $ZC;
		$C = $LD + ($B / 24);
		$D = $this->CDJD($C, $LM, $LY);
		$LctGMonth = $this->JDCMonth($D);
		return ($LctGMonth);
	}

	protected function LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
	{
		$A = $this->HMSDH($LCH, $LCM, $LCS);
		$B = $A - $DS - $ZC;
		$C = $LD + ($B / 24);
		$D = $this->CDJD($C, $LM, $LY);
		$LctGYear = $this->JDCYear($D);
		return ($LctGYear);
	}

	//Conversion of right ascension to hour angle
	//RH Right Ascension in HMS, LH Local Civil Time in HMS, DS Daylight saving, ZC Zonecorrection,
	//LD Local Calender Date in DMY, L geographical Longitude in Degrees
	protected function RAHA($RH, $RM, $RS, $LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY, $L)
	{
		$A = $this->LctUT($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)[0];
		$B = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
		$C = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
		$D = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
		$E = $this->UTGST($A, 0, 0, $B, $C, $D);
		$F = $this->GSTLST($E, 0, 0, $L);
		$G = $this->HMSDH($RH, $RM, $RS);
		$H = $F - $G;
		if ($H < 0){
			$RAHA = 24 + $H;}
		else{
			$RAHA = $H;}
		return ($RAHA);
	}

	//Conversion of hour angle to right ascension
	protected function HARA($HH, $HM, $HS, $LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY, $L)
	{
		$A = $this->LctUT($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)[0];
		$B = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
		$C = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
		$D = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
		$E = $this->UTGST($A, 0, 0, $B, $C, $D);
		$F = $this->GSTLST($E, 0, 0, $L);
		$G = $this->HMSDH($HH, $HM, $HS);
		$H = $F - $G;
		if ($H < 0){
			$HARA = 24 + $H;}
		else{
			$HARA = $H;}
		return ($HARA);
	}

	protected function Radians($W)
		{
		$Radians = $W * 0.01745329252;
		return ($Radians);
		}

	protected function Degrees($W)
		{
		$Degrees = $W * 57.29577951;
		return ($Degrees);
		}



	//Equatorial to Horizon coordinate conversion (Az)
	//HH HourAngle in HMS, DD Declination in DMS, P Latitude in decimal Degrees
	protected function EQAz($HH, $HM, $HS, $DD, $DM, $DS, $P)
	{
		$A = $this->HMSDH($HH, $HM, $HS);
		$B = $A * 15;
		$C = $this->Radians($B);
		$D = $this->DMSDD($DD, $DM, $DS);
		$E = $this->Radians($D);
		$F = $this->Radians($P);
		$G = sin($E) * sin($F) + cos($E) * cos($F) * cos($C);
		$H = -cos($E) * cos($F) * sin($C);
		$I = sin($E) - (sin($F) * $G);
		$J = $this->Degrees(atan2($H, $I));
		$EQAz = $J - 360 * $this->roundvariantint($J / 360);
		return ($EQAz);
	}

	////Equatorial to Horizon coordinate conversion (Alt)
	protected function EQAlt($HH, $HM, $HS, $DD, $DM, $DS, $P)
	{
		$A = $this->HMSDH($HH, $HM, $HS);
		$B = $A * 15;
		$C = $this->Radians($B);
		$D = $this->DMSDD($DD, $DM, $DS);
		$E = $this->Radians($D);
		$F = $this->Radians($P);
		$G = sin($E) * sin($F) + cos($E) * cos($F) * cos($C);
		$EQAlt = $this->Degrees(asin($G));
		return ($EQAlt);
	}

	protected function HORDec($AZD, $AZM, $AZS, $ALD, $ALM, $ALS, $P)
	{
		$A = $this->DMSDD($AZD, $AZM, $AZS);
		$B = $this->DMSDD($ALD, $ALM, $ALS);
		$C = $this->Radians($A);
		$D = $this->Radians($B);
		$E = $this->Radians($P);
		$F = sin($D) * sin($E) + cos($D) * cos($E) * cos($C);
		$HORDec = $this->Degrees(asin($F));
		return ($HORDec);
	}

	protected function HORHa($AZD, $AZM, $AZS, $ALD, $ALM, $ALS, $P)
	{
		$A = $this->DMSDD($AZD, $AZM, $AZS);
		$B = $this->DMSDD($ALD, $ALM, $ALS);
		$C = $this->Radians($A);
		$D = $this->Radians($B);
		$E = $this->Radians($P);
		$F = sin($D) * sin($E) + cos($D) * cos($E) * cos($C);
		$G = -cos($D) * cos($E) * sin($C);
		$H = sin($D) - sin($E) * $F;
		$I = $this->DDDH($this->Degrees(atan2($G, $H)));
		$HORHa = $I - 24 * $this->roundvariantint($I / 24);
		return ($HORHa);
	}

	protected function Obliq($GD, $GM, $GY)
	{
		$A = $this->CDJD($GD, $GM, $GY);
		$B = $A - 2415020;
		$C = ($B / 36525) - 1;
		$D = $C * (46.815 + $C * (0.0006 - ($C * 0.00181)));
		$E = $D / 3600;
		$Obliq = 23.43929167 - $E + $this->NutatObl($GD, $GM, $GY);
		return ($Obliq);
	}

	protected function ECDec($ELD, $ELM, $ELS, $BD, $BM, $BS, $GD, $GM, $GY)
	{
		$A = $this->Radians($this->DMSDD($ELD, $ELM, $ELS));                      //eclon
		$B = $this->Radians($this->DMSDD($BD, $BM, $BS));                         //eclat
		$C = $this->Radians($this->Obliq($GD, $GM, $GY));                         //obliq
		$D = sin($B) * cos($C) + cos($B) * sin($C) * sin($A);   //sin Dec
		$ECDec = $this->Degrees(asin($D));                             //Dec Deg
		return ($ECDec);
	}

	protected function ECRA($ELD, $ELM, $ELS, $BD, $BM, $BS, $GD, $GM, $GY)
	{
		$A = $this->Radians($this->DMSDD($ELD, $ELM, $ELS));       //eclon
		$B = $this->Radians($this->DMSDD($BD, $BM, $BS));          //eclat
		$C = $this->Radians($this->Obliq($GD, $GM, $GY));          //obliq
		$D = sin($A) * cos($C) - tan($B) * sin($C); //y
		$E = cos($A);                                //x
		$F = $this->Degrees(atan2($D, $E));                //RA Deg
		$ECRA = $F - 360 * $this->roundvariantint($F / 360);   //RA Deg
		return ($ECRA);
	}

	protected function EQElat($RAH, $RAM, $RAS, $DD, $DM, $DS, $GD, $GM, $GY)
	{
		$A = $this->Radians($this->DHDD($this->HMSDH($RAH, $RAM, $RAS)));
		$B = $this->Radians($this->DMSDD($DD, $DM, $DS));
		$C = $this->Radians($this->Obliq($GD, $GM, $GY));
		$D = sin($B) * cos($C) - cos($B) * sin($C) * sin($A);
		$EQElat = $this->Degrees(asin($D));
		return ($EQElat);
	}

	protected function EQElong($RAH, $RAM, $RAS, $DD, $DM, $DS, $GD, $GM, $GY)
	{
		$A = $this->Radians($this->DHDD($this->HMSDH($RAH, $RAM, $RAS)));
		$B = $this->Radians($this->DMSDD($DD, $DM, $DS));
		$C = $this->Radians($this->Obliq($GD, $GM, $GY));
		$D = sin($A) * cos($C) + tan($B) * sin($C);
		$E = cos($A);
		$F = $this->Degrees(atan2($D, $E));
		$EQElong = $F - 360 * $this->roundvariantint($F / 360);
		return ($EQElong);
	}

	protected function EQGlong($RAH, $RAM, $RAS, $DD, $DM, $DS)
	{
		$A = $this->Radians($this->DHDD($this->HMSDH($RAH, $RAM, $RAS)));
		$B = $this->Radians($this->DMSDD($DD, $DM, $DS));
		$C = cos($this->Radians(27.4));
		$D = sin($this->Radians(27.4));
		$E = $this->Radians(192.25);
		$F = cos($B) * $C * cos($A - $E) + sin($B) * $D;
		$G = sin($B) - $F * $D;
		$H = cos($B) * sin($A - $E) * $C;
		$I = $this->Degrees(atan2($G, $H)) + 33;
		$EQGlong = $I - 360 * $this->roundvariantint($I / 360);
		return ($EQGlong);
	}

	protected function EQGlat($RAH, $RAM, $RAS, $DD, $DM, $DS)
	{
		$A = $this->Radians($this->DHDD($this->HMSDH($RAH, $RAM, $RAS)));
		$B = $this->Radians($this->DMSDD($DD, $DM, $DS));
		$C = cos($this->Radians(27.4));
		$D = sin($this->Radians(27.4));
		$E = $this->Radians(192.25);
		$F = cos($B) * $C * cos($A - $E) + sin($B) * $D;
		$EQGlat = $this->Degrees(asin($F));
		return ($EQGlat);
	}

	protected function EqOfTime($GD, $GM, $GY)
		{
		$A = $this->SunLong(12, 0, 0, 0, 0, $GD, $GM, $GY);
		$B = $this->DDDH($this->ECRA($A, 0, 0, 0, 0, 0, $GD, $GM, $GY));
		$C = $this->GSTUT($B, 0, 0, $GD, $GM, $GY)[0];
		$EqOfTime = $C - 12;
		return ($EqOfTime);
		}

	protected function NutatLong($GD, $GM, $GY)
	{
		$DJ = $this->CDJD($GD, $GM, $GY) - 2415020;
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
		return ($NutatLong);
	}

	protected function NutatObl($GD, $GM, $GY)
	{
	   $DJ = $this->CDJD($GD, $GM, $GY) - 2415020;
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
	   return ($NutatObl);
	}

	protected function MoonDist($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
	{
			$HP = $this->Radians($this->MoonHP($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR));
			$R = 6378.14 / sin($HP);
			$MoonDist = $R;
		return ($MoonDist);
	}

	protected function MoonSize($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
		{
			$HP = $this->Radians($this->MoonHP($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR));
			$R = 6378.14 / sin($HP);
			$TH = 384401 * 0.5181 / $R;
			$MoonSize = $TH;
		return ($MoonSize);
		}

	protected function sign($number) {
		return ($number > 0) ? 1 : (($number < 0) ? -1 : 0);
	}

	protected function IINT($W)
		{
			$IINT = $this->sign($W) * $this->roundvariantint(abs($W));
		return ($IINT);
		}

	protected function LINT($W)
		{
			$LINT = $this->IINT($W) + $this->IINT(((1 * sign($W)) - 1) / 2);
	   return ($LINT);
		}

	protected function FRACT($W)
		{
			$FRACT = $W - $this->LINT($W);
		return ($FRACT);
		}

	protected function FullMoon($DS, $ZC, $DY, $MN, $YR)
	{
			$D0 = $this->LctGDay(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
			$M0 = $this->LctGMonth(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
			$Y0 = $this->LctGYear(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);

			if ($Y0 < 0){
				$Y0 = $Y0 + 1;}

			$J0 = $this->CDJD(0, 1, $Y0) - 2415020;
			$DJ = $this->CDJD($D0, $M0, $Y0) - 2415020;
			$K = $this->LINT((($Y0 - 1900 + (($DJ - $J0) / 365)) * 12.3685) + 0.5);
			$TN = $K / 1236.85;
			  $TF = ($K + 0.5) / 1236.85;
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
			$F = 21.2964 + 360 * FRACT($A) - (0.0016528 + 0.00000239 * $T) * $T2;
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
			  $NB = $F;
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
			  $FB = $F;
			  $FullMoon = $FI + 2415020 + $FF;
		return ($FullMoon);
	}

	protected function Fpart($W)
	{
			$Fpart = $W - $this->LINT($W);
		return ($Fpart);
	}

	function MoonHP($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
	{
			$UT = $this->LctUT($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)[0];
			$GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$T = (($this->CDJD($GD, $GM, $GY) - 2415020) / 36525) + ($UT / 876600);
			$T2 = $T * $T;

			$M1 = 27.32158213;
			  $M2 = 365.2596407;
			  $M3 = 27.55455094;
			$M4 = 29.53058868;
			  $M5 = 27.21222039;
			  $M6 = 6798.363307;
			$Q = $this->CDJD($GD, $GM, $GY) - 2415020 + ($UT / 24);
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
			  $NA = $this->Radians($NA);
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
		return ($MoonHP);
    }

	protected function CRN($GD, $GM, $GY)
	{
		$A = $this->CDJD($GD, $GM, $GY);
		$CRN = 1690 + round(($A - 2444235.34) / 27.2753, 0);
		return ($CRN);
	}

	protected function UnwindDeg($W)
	{
			$UnwindDeg = $W - 360 * $this->roundvariantint($W / 360);
		return ($UnwindDeg);
	}

	protected function UnwindRad($W)
		{
		$UnwindRad = $W - 6.283185308 * $this->roundvariantint($W / 6.283185308);
		return ($UnwindRad);
		}

	protected function Unwind($W)
		{
		$Unwind = $W - 6.283185308 * $this->roundvariantint($W / 6.283185308);
		return ($Unwind);
		}

	protected function MoonMeanAnomaly($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
	{
			$UT = $this->LctUT($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)[0];
			$GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$T = (($this->CDJD($GD, $GM, $GY) - 2415020) / 36525) + ($UT / 876600);
			$T2 = $T * $T;

			$M1 = 27.32158213;
			  $M2 = 365.2596407;
			  $M3 = 27.55455094;
			$M4 = 29.53058868;
			  $M5 = 27.21222039;
			  $M6 = 6798.363307;
			$Q = $this->CDJD($GD, $GM, $GY) - 2415020 + ($UT / 24);
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

			$MoonMeanAnomaly = $this->Radians($MD);
		return ($MoonMeanAnomaly);
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
			$MoonPhase = number_format($K*100,1,',',''); //*100 is %
		return ($MoonPhase);
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
		return ($MoonPABL);
	}

	protected function MoonLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
		{
			$UT = $this->LctUT($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)[0];
			$GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$T = (($this->CDJD($GD, $GM, $GY) - 2415020) / 36525) + ($UT / 876600);
			$T2 = $T * $T;

			$M1 = 27.32158213;
			  $M2 = 365.2596407;
			  $M3 = 27.55455094;
			$M4 = 29.53058868;
			  $M5 = 27.21222039;
			  $M6 = 6798.363307;
			$Q = $this->CDJD($GD, $GM, $GY) - 2415020 + ($UT / 24);
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
			$S3 = 0.003964 * Sin($this->Radians($B));
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
			  $NA = $this->Radians($NA);
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
		return ($MoonLong);
		}

	protected function MoonLat($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
		{
			$UT = $this->LctUT($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)[0];
			$GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$T = (($this->CDJD($GD, $GM, $GY) - 2415020) / 36525) + ($UT / 876600);
			$T2 = $T * $T;

			$M1 = 27.32158213;
			  $M2 = 365.2596407;
			  $M3 = 27.55455094;
			$M4 = 29.53058868;
			  $M5 = 27.21222039;
			  $M6 = 6798.363307;
			$Q = $this->CDJD($GD, $GM, $GY) - 2415020 + ($UT / 24);
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
		return($MoonLat);
		}

	protected function MoonNodeLong($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)
	{
			$UT = $this->LctUT($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR)[0];
			$GD = $this->LctGDay($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GM = $this->LctGMonth($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$GY = $this->LctGYear($LH, $LM, $LS, $DS, $ZC, $DY, $MN, $YR);
			$T = (($this->CDJD($GD, $GM, $GY) - 2415020) / 36525) + ($UT / 876600);
			$T2 = $T * $T;

			$M1 = 27.32158213;
			  $M2 = 365.2596407;
			  $M3 = 27.55455094;
			$M4 = 29.53058868;
			  $M5 = 27.21222039;
			  $M6 = 6798.363307;
			$Q = $this->CDJD($GD, $GM, $GY) - 2415020 + ($UT / 24);
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

			$MoonNodeLong = $NA;
		return ($MoonNodeLong);
		}

	protected function NewMoon($DS, $ZC, $DY, $MN, $YR)
		{
			$D0 = $this->LctGDay(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
			$M0 = $this->LctGMonth(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);
			$Y0 = $this->LctGYear(12, 0, 0, $DS, $ZC, $DY, $MN, $YR);

			if ($Y0 < 0){
				$Y0 = $Y0 + 1;}

			$J0 = $this->CDJD(0, 1, $Y0) - 2415020;
			$DJ = $this->CDJD($D0, $M0, $Y0) - 2415020;
			$K = $this->LINT((($Y0 - 1900 + (($DJ - $J0) / 365)) * 12.3685) + 0.5);
			$TN = $K / 1236.85;
			  $TF = ($K + 0.5) / 1236.85;
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
			  $NB = $F;
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
			  $FB = $F;
			  $NewMoon = $NI + 2415020 + $NF;
		return ($NewMoon);
		}

	//$Y2 = altitude of the star in degrees, $W = 'true' or all strings without 'true', $PR = Atmospheric Pressure, $TR = Temperature
	protected function Refract($Y2, $SW, $PR, $TR)
		{
			$Y = $this->Radians($Y2);
			if (preg_match("/true/i",$SW) == 1){
				$D = -1;}
			else{
				$D = 1;}

			if ($D == -1){
				$Y3 = $Y;
					$Y1 = $Y;
					$R1 = 0;
			  step1: //3020
					$Y = $Y1 + $R1;
				   $Q = $Y;
					if ($Y < 0.2617994){
					if ($Y < -0.087){
						$Q = 0;
						$RF = 0;
						goto ends; //3075
						}

					$YD = $this->Degrees($Y);
					$A = ((0.00002 * $YD + 0.0196) * $YD + 0.1594) * $PR;
					$B = (273 + $TR) * ((0.0845 * $YD + 0.505) * $YD + 1);
					$RF = $this->Radians(-($A / $B) * $D);
				}
					else{
					$RF = -$D * 0.00007888888 * $PR / ((273 + $TR) * tan($Y));
						}
					$R2 = $RF;
				if (($R2 == 0) or (abs($R2 - $R1) < 0.000001)){
					$Q = $Y3;
					goto ends; //3075
					}
				$R1 = $R2;
					goto step1; //3020
					}
			else{
				if ($Y < 0.2617994){
					if ($Y < -0.087){
						$Q = 0;
						$RF = 0;
						goto ends; //3075
						}

						$YD = $this->Degrees($Y);
						$A = ((0.00002 * $YD + 0.0196) * $YD + 0.1594) * $PR;
						$B = (273 + $TR) * ((0.0845 * $YD + 0.505) * $YD + 1);
						$RF = $this->Radians(-($A / $B) * $D);
					}
					else{
					$RF = -$D * 0.00007888888 * $PR / ((273 + $TR) * tan($Y));
						}
				$Q = $Y;
				goto ends; //3075
				}

			  ends: //3075
			  $Refract = $this->Degrees($Q + $RF);
		return ($Refract);
		}

	protected function SunMeanAnomaly($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
	{
			$AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$UT = $this->LctUT($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)[0];
			$DJ = $this->CDJD($AA, $BB, $CC) - 2415020;
			$T = ($DJ / 36525) + ($UT / 876600);
			  $T2 = $T * $T;
			$A = 100.0021359 * $T;
			  $B = 360 * ($A - $this->roundvariantint($A));
			$M1 = 358.47583 - (0.00015 + 0.0000033 * $T) * $T2 + $B;
			$AM = $this->Unwind($this->Radians($M1));
			$SunMeanAnomaly = $AM;
		return ($SunMeanAnomaly);
	}

	protected function SunLong($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
	{
			$AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$UT = $this->LctUT($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)[0];
			$DJ = $this->CDJD($AA, $BB, $CC) - 2415020;
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
			$E1 = $this->Radians(231.19 + 20.2 * $T);
			$A = 183.1353208 * $T;
			  $B = 360 * ($A - $this->roundvariantint($A));
			$H1 = $this->Radians(353.4 + $B);

			$D2 = 0.00134 * cos($A1) + 0.00154 * cos($B1) + 0.002 * cos($C1);
			$D2 = $D2 + 0.00179 * sin($D1) + 0.00178 * sin($E1);
			$D3 = 0.00000543 * sin($A1) + 0.00001575 * sin($B1);
			$D3 = $D3 + 0.00001627 * sin($C1) + 0.00003076 * cos($D1);
			$D3 = $D3 + 0.00000927 * sin($H1);

			$SR = $AT + $this->Radians($L - $M1 + $D2);
			  $TP = 6.283185308;
			$SR = $SR - $TP * $this->roundvariantint($SR / $TP);
			$SunLong = $this->Degrees($SR);
		return ($SunLong);
	}

	function SunDist($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
	{
			$AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$UT = $this->LctUT($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)[0];
			$DJ = $this->CDJD($AA, $BB, $CC) - 2415020;
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
			$E1 = $this->Radians(231.19 + 20.2 * $T);
			$A = 183.1353208 * $T;
			  $B = 360 * ($A - $this->roundvariantint($A));
			$H1 = $this->Radians(353.4 + $B);

			$D2 = 0.00134 * cos($A1) + 0.00154 * cos($B1) + 0.002 * cos($C1);
			$D2 = $D2 + 0.00179 * sin($D1) + 0.00178 * sin($E1);
			$D3 = 0.00000543 * sin($A1) + 0.00001575 * sin($B1);
			$D3 = $D3 + 0.00001627 * sin($C1) + 0.00003076 * cos($D1);
			$D3 = $D3 + 0.00000927 * sin($H1);

			$RR = 1.0000002 * (1 - $EC * cos($AE)) + $D3;
			$SunDist = $RR;
		return ($SunDist);
	}

	protected function SunTrueAnomaly($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)
	{
			$AA = $this->LctGDay($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$BB = $this->LctGMonth($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$CC = $this->LctGYear($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY);
			$UT = $this->LctUT($LCH, $LCM, $LCS, $DS, $ZC, $LD, $LM, $LY)[0];
			$DJ = $this->CDJD($AA, $BB, $CC) - 2415020;
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
			$SunTrueAnomaly = $this->Degrees($this->TrueAnomaly($AM, $EC));
		return ($SunTrueAnomaly);
	}

	protected function SunEcc($GD, $GM, $GY)
	{
		$T = ($this->CDJD($GD, $GM, $GY) - 2415020) / 36525;
		$T2 = $T * $T;
		$SunEcc = 0.01675104 - 0.0000418 * $T - 0.000000126 * $T2;
		return ($SunEcc);
	}

	protected function TrueAnomaly($AM, $EC)
	{
			$TP = 6.283185308;
			  $M = $AM - $TP * $this->roundvariantint($AM / $TP);
			  $AE = $M;
			  step1: //3305
			  $D = $AE - ($EC * sin($AE)) - $M;

			if (abs($D) < 0.000001){
				goto step2; //3320
					}

			$D = $D / (1 - ($EC * cos($AE)));
			  $AE = $AE - $D;
			  goto step1; //3305

			  step2: //3320
			  $A = sqrt((1 + $EC) / (1 - $EC)) * tan($AE / 2);
			$AT = 2 * atan($A);
			$TrueAnomaly = $AT;
		return ($TrueAnomaly);
	}

	protected function EccentricAnomaly($AM, $EC)
	{
			$TP = 6.283185308;
			  $M = $AM - $TP * $this->roundvariantint($AM / $TP);
			  $AE = $M;
			  step1: //3305
			  $D = $AE - ($EC * sin($AE)) - $M;

			if (abs($D) < 0.000001){
				goto step2; //3320
			}

			$D = $D / (1 - ($EC * cos($AE)));
			  $AE = $AE - $D;
			  goto step1; //3305

			  step2: //3320
			  $EccentricAnomaly = $AE;
		return ($EccentricAnomaly);
	}

	//Funktion um zu Prüfen ob der UNIX Time Stamp heute ist 
	protected function isToday($time)
	{
			$begin = mktime(0, 0, 0);
			$end = mktime(23, 59, 59);
			// check if given time is between begin and end
			if($time >= $begin && $time <= $end)
				return true;
			else
				return false;
	}
    
	protected function moon_phase($year, $month, $day)
	{
		/*    modified from http://www.voidware.com/moon_phase.htm    */
		$c = $e = $jd = $b = 0;
		if ($month < 3)
		{
			$year--;
			$month += 12;
		}
		++$month;
		$c = 365.25 * $year;
		$e = 30.6 * $month;
		$jd = $c + $e + $day - 694039.09;    //jd is total days elapsed
		$jd /= 29.5305882;                    //divide by the moon cycle
		$b = (int) $jd;                        //int(jd) -> b, take integer part of jd
		$jd -= $b;                            //subtract integer part to leave fractional part of original jd
		$b = round($jd * 8);                //scale fraction from 0-8 and round
		if ($b >= 8 )
		{
			$b = 0;//0 and 8 are the same so turn 8 into 0
		}
		switch ($b)
		{
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
	
	protected function CalculateMoonphase()
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

		// aktuelles Datum in Jahre umrechnen
		$year = ((((((date("s") / 60)+ date("i")) / 60)+date("G")) / 24) + date("z") - 1) / (365 + (date("L"))) + date("Y");
		//print_r(date("z"));
		$rads = 3.14159265359/180;

		$table = "";
		$name = "";

		for ($phase = 0; $phase < 1; $phase += 0.25){
		   // Anzahl der Mondphasen seit 2000
		   $k = floor(($year-2000)*12.36853087)+$phase;
		   // Mittlerer JDE Wert des Ereignisses
		   $JDE = 2451550.09766+29.530588861*$k;
		   // Relevante Winkelwerte in [Radiant]
		   $M = (2.5534+29.10535670*$k)*$rads;
		   $Ms = (201.5643+385.81693528*$k)*$rads;
		   $F = (160.7108+390.67050284*$k)*$rads;

		   if ($phase == 0){
		   // Korrekturterme JDE für Neumond
			  $JDE += -0.40720*Sin($Ms);
			  $JDE += 0.17241*Sin($M);
			  $JDE += 0.01608*Sin(2*$Ms);
			  $JDE += 0.01039*Sin(2*$F);
			  $JDE += 0.00739*Sin($Ms-$M);
			  $JDE += -0.00514*Sin($Ms+$M);
			  $JDE += 0.00208*Sin(2*$M);
			  $JDE += -0.00111*Sin($Ms-2*$F);
			  }
		   elseif ($phase == 0.5) {
		   // Korrekturterme JDE für Vollmond
			  $JDE += -0.40614*Sin($Ms);
			  $JDE += 0.17302*Sin($M);
			  $JDE += 0.01614*Sin(2*$Ms);
			  $JDE += 0.01043*Sin(2*$F);
			  $JDE += 0.00734*Sin($Ms-$M);
			  $JDE += -0.00515*Sin($Ms+$M);
			  $JDE += 0.00209*Sin(2*$M);
			  $JDE += -0.00111*Sin($Ms-2*$F);
			  }

		   if ($phase == 0.25 || $phase == 0.75){
		   // Korrekturterme für JDE für das  1. bzw. letzte Viertel
			  $JDE += -0.62801*Sin($Ms);
			  $JDE += 0.17172*Sin($M);
			  $JDE += -0.01183*Sin($Ms+$M);
			  $JDE += 0.00862*Sin(2*$Ms);
			  $JDE += 0.00804*Sin(2*$F);
			  $JDE += 0.00454*Sin($Ms-$M);
			  $JDE += 0.00204*Sin(2*$M);
			  $JDE += -0.00180*Sin($Ms-2*$F);

		   // Weiterer Korrekturterm für Viertelphasen
		   if ($phase == 0.25){
			  $JDE += 0.00306;
			  } else {
				$JDE += -0.00306;
				}
		   }

		   // Konvertierung von Julianischem Datum auf Gregorianisches Datum
		   $z = floor($JDE + 0.5);
		   $f = ($JDE + 0.5) - floor($JDE + 0.5);
		   if ($z < 2299161){
			  $a = $z;
			  }
			  else {
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
			  $monat = $e -1;
			  }
			  else {
			  $monat = $e - 13;
			  }
		   if ($monat > 2) {
			  $jahr = $c - 4716;
			  }
			  else {
			  $jahr = $c - 4715;
			  }

			$sommerzeit = date("I");
		   if($sommerzeit == 0){
			  $datum = mktime($stunde,$minute,$sekunde+3600,$monat,$tag,$jahr);
			  }
				else{
				 $datum = mktime($stunde,$minute,$sekunde+7200,$monat,$tag,$jahr);
			  }

		   switch ($phase){
			  case 0:
			  $ausgabe = 'Neumond';
			  break;
			  case 0.25:
			  $ausgabe = 'erstes Viertel';
			  break;
			  case 0.5:
			  $ausgabe = 'Vollmond';
			  break;
			  case 0.75:
			  $ausgabe = 'letztes Viertel';
			  break;
			  }
			  
			$date = date("D",($datum));
			if($date == "Mon"){
				  $wt = "Mo";}
				 elseif ($date == "Tue"){
				  $wt = "Di";}
				  elseif ($date == "Wed"){
				  $wt = "Mi";}
				  elseif ($date == "Thu"){
				  $wt = "Do";}
				  elseif ($date == "Fri"){
				  $wt = "Fr";}
				  elseif ($date == "Sat"){
				  $wt = "Sa";}
				  elseif ($date == "Sun"){
				  $wt = "So";}
		  
		   $table .= ($wt.", ".date("d.m.Y H:i",($datum)).";");
		   $name .= ($ausgabe.";");
		   }

		
		$var = explode(";",$table);
		$var_name = explode(";",$name);
		if($this->ReadPropertyBoolean("newmoon") == true)
		{
			//IPS_SetName($this->GetIDForIdent("newmoon"), $var_name[0]);
			SetValue($this->GetIDForIdent("newmoon"), $var[0]);
		}
		if($this->ReadPropertyBoolean("firstquarter") == true)
		{
			//IPS_SetName($this->GetIDForIdent("firstquarter"), $var_name[1]);
			SetValue($this->GetIDForIdent("firstquarter"), $var[1]);
		}
		if($this->ReadPropertyBoolean("fullmoon") == true)
		{
			//IPS_SetName($this->GetIDForIdent("fullmoon"), $var_name[2]);
			SetValue($this->GetIDForIdent("fullmoon"), $var[2]);
		}
		if($this->ReadPropertyBoolean("lastquarter") == true)
		{
			//IPS_SetName($this->GetIDForIdent("lastquarter"), $var_name[3]);
			SetValue($this->GetIDForIdent("lastquarter"), $var[3]);
		}
		
		$moonphase = array ("newmoon" => $var[0], "firstquarter" => $var[1], "fullmoon" => $var[2], "lastquarter" => $var[3]);
		return $moonphase;
	}
	
	public function MoonphasePercent()
	{
		// Formel nach http://www.die-seite.eu/wm-mondphasen.php

		$ursprung = mktime(19,19,54,02,22,2016);
		$akt_date = time(); //mktime(18,19,54,04,24,2016);//
		$mondphase = round(((($akt_date - $ursprung) / (floor(29.530588861 * 86400))) - floor(($akt_date - $ursprung) / (floor(29.530588861 * 86400)))) * 100, 0);
		
		return $mondphase;
	}
	
	public function MoonphaseText()
	{
		$mondphase = $this->MoonphasePercent();
		$picture = $this->GetMoonPicture($mondphase);
		$phase = $picture["phase"];
		if($this->ReadPropertyBoolean("moonphase") == true)
		{
			SetValue($this->GetIDForIdent("moonphase"), $phase." - ".$mondphase."%");
		}
		$phasetext = $phase." - ".$mondphase."%";
		return $phasetext;
	}
	
	public function Moon_FirstQuarter()
	{
		$moonphase = $this->CalculateMoonphase();
		$firstquarter = $moonphase["firstquarter"];
		return $firstquarter;
	}
	
	public function Moon_Newmoon()
	{
		$moonphase = $this->CalculateMoonphase();
		$newmoon = $moonphase["newmoon"];
		return $newmoon;
	}
	
	public function Moon_Fullmoon()
	{
		$moonphase = $this->CalculateMoonphase();
		$fullmoon = $moonphase["fullmoon"];
		return $fullmoon;
	}
	
	public function Moon_LastQuarter()
	{
		$moonphase = $this->CalculateMoonphase();
		$lastquarter = $moonphase["lastquarter"];
		return $lastquarter;
	}
	
	protected function GetMoonPicture($mondphase)
	{
		if ($mondphase <= 1 || $mondphase >= 99 ){  //--Vollmond
		$phase_text = 'Vollmond';
		if($mondphase>=99){
			$pic = $this->rescale([99,100],[172,177]);} // ([Mondphasen von,bis],[Bildnummern von,bis])
			else{
			$pic = $this->rescale([0,1],[178,182]);}
			$pic_n = floor($pic($mondphase));
			if($pic_n<10){
			   $pic_n = "00".$pic_n;}
			elseif($pic_n<100){
			   $pic_n = "0".$pic_n;}
			else{$pic_n = $pic_n;}
		}
		elseif ($mondphase > 1 && $mondphase < 49){  //--abnehmender Mond
			$phase_text = 'abnehmender Mond';
			$pic = $this->rescale([2,48],[183,352]);
			$pic_n = floor($pic($mondphase));
			if($pic_n<10){
			   $pic_n = "00".$pic_n;}
			elseif($pic_n<100){
			   $pic_n = "0".$pic_n;}
			else{$pic_n = $pic_n;}
		}
		elseif ($mondphase >= 49 && $mondphase <= 51){  //--Neumond
			$phase_text = 'Neumond';
			$pic = $this->rescale([49,51],[353,362]);
			$pic_n = floor($pic($mondphase));
			if($pic_n<10){
			   $pic_n = "00".$pic_n;}
			elseif($pic_n<100){
			   $pic_n = "0".$pic_n;}
			else{$pic_n = $pic_n;}
		}
		else{  //--zunehmender Mond
			$phase_text = 'zunehmender Mond';
			$pic = $this->rescale([52,98],[008,171]);
			$pic_n = floor($pic($mondphase));
			if($pic_n<10){
			   $pic_n = "00".$pic_n;}
			elseif($pic_n<100){
			   $pic_n = "0".$pic_n;}
			else{$pic_n = $pic_n;}
		}
		
		$picture = array("picid" => $pic_n, "phase" => $phase_text);
		return $picture;
	}

	protected function rescale($ab, $cd) //--Funktion zum anpassen der Mondphase 0-100 an Bildnummer 001-362 (Bilder der Seite http://www.avgoe.de)
	{
		list($a1,$b1) = $ab;
		list($c1,$d1) = $cd;
		if($a1 == $b1)
		{
		   trigger_error("Invalid scale",E_USER_WARNING);
		   return false;
		}
		$o = ($b1*$c1-$a1*$d1)/($b1-$a1);
		$s = ($d1-$c1)/($b1-$a1);
		return function($x)use($o,$s)
		{
		   return $s*$x+$o;
		};
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
		if($this->ReadPropertyBoolean("moonrise") == true) // float
		{
			SetValue($this->GetIDForIdent("moonrise"), $moonrise); 
		}
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
		if($this->ReadPropertyBoolean("moonset") == true) // float
		{
			SetValue($this->GetIDForIdent("moonset"), $moonset); 
		}
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
			$UTC = $this->ReadPropertyFloat("UTC");
			$formhead = $this->FormHead();
			$formselection = $this->FormSelection();
			$formstatus = $this->FormStatus();
			$formactions = $this->FormActions();
			$formutctext = $this->FormUTCText($UTC);
			$formelementsend = '{ "type": "Label", "label": "__________________________________________________________________________________________________" }';
			
			return	'{ '.$formhead.$formutctext.$formselection.$formelementsend.'],'.$formactions.$formstatus.' }';
		}
		
		protected function FormUTCText($UTC)
		{
			$form = '';
			if($UTC == 14)
			{
				$form .= '{ "type": "Label", "label": "UTC +14 Tonga und 2 weitere LINT Kiritimati" },';
			}
			elseif($UTC == 13.75)
			{
				$form .= '{ "type": "Label", "label": "UTC +13:45 Chatham-Inseln/Neuseeland CHADT Chatham-Inseln" },';
			}
			elseif($UTC == 13)
			{
				$form .= '{ "type": "Label", "label": "UTC +13 Neuseeland mit Ausnahmen und 4 weitere NZDT Auckland" },';
			}
			elseif($UTC == 12)
			{
				$form .= '{ "type": "Label", "label": "UTC +12 Kleines Gebiet in Russland und 6 weitere ANAT Anadyr" },';
			}
			elseif($UTC == 11)
			{
				$form .= '{ "type": "Label", "label": "UTC +11 Großteil von Australien und 8 weitere AEDT Melbourne" },';
			}
			elseif($UTC == 10.5)
			{
				$form .= '{ "type": "Label", "label": "UTC +10:30 Kleines Gebiet in Australien ACDT Adelaide" },';
			}
			elseif($UTC == 10)
			{
				$form .= '{ "type": "Label", "label": "UTC +10 Queensland/Australien und 6 weitere AEST Brisbane" },';
			}
			elseif($UTC == 9.5)
			{
				$form .= '{ "type": "Label", "label": "UTC +9:30 Northern Territory/Australien ACST Darwin" },';
			}
			elseif($UTC == 9)
			{
				$form .= '{ "type": "Label", "label": "UTC +9 Japan, Südkorea und 4 weitere JST Tokio" },';
			}
			elseif($UTC == 8.75)
			{
				$form .= '{ "type": "Label", "label": "UTC +8:45 Western Australia/Australien ACWST Eucla" },';
			}
			elseif($UTC == 8.5)
			{
				$form .= '{ "type": "Label", "label": "UTC +8:30 Nordkorea PYT Pjöngjang" },';
			}
			elseif($UTC == 8)
			{
				$form .= '{ "type": "Label", "label": "UTC +8 China, Philippinen und 10 weitere CST Peking" },';
			}
			elseif($UTC == 7)
			{
				$form .= '{ "type": "Label", "label": "UTC +7 Großteil von Indonesien und 8 weitere WIB Jakarta" },';
			}
			elseif($UTC == 6.5)
			{
				$form .= '{ "type": "Label", "label": "UTC +6:30 Myanmar und Kokosinseln MMT Rangun" },';
			}
			elseif($UTC == 6)
			{
				$form .= '{ "type": "Label", "label": "UTC +6 Bangladesch und 6 weitere BST Dhaka },';
			}
			elseif($UTC == 5.75)
			{
				$form .= '{ "type": "Label", "label": "UTC +5:45 Nepal NPT Kathmandu" },';
			}
			elseif($UTC == 5.5)
			{
				$form .= '{ "type": "Label", "label": "UTC +5:30 Indien und Sri Lanka IST Neu-Delhi" },';
			}
			elseif($UTC == 5)
			{
				$form .= '{ "type": "Label", "label": "UTC +5 Pakistan und 8 weitere UZT Taschkent" },';
			}
			elseif($UTC == 4.5)
			{
				$form .= '{ "type": "Label", "label": "UTC +4:30 Afghanistan AFT Kabul" },';
			}
			elseif($UTC == 4)
			{
				$form .= '{ "type": "Label", "label": "UTC +4 Aserbaidschan und 8 weitere GST Dubai" },';
			}
			elseif($UTC == 3.5)
			{
				$form .= '{ "type": "Label", "label": "UTC +3:30 Iran IRST Teheran" },';
			}
			elseif($UTC == 3)
			{
				$form .= '{ "type": "Label", "label": "UTC +3 Moskau/Russland und 24 weitere MSK Moskau" },';
			}
			elseif($UTC == 2)
			{
				$form .= '{ "type": "Label", "label": "UTC +2 Griechenland und 30 weitere OEZ Kairo" },';
			}
			elseif($UTC == 1)
			{
				$form .= '{ "type": "Label", "label": "UTC +1 Deutschland und 43 weitere MEZ Berlin" },';
			}
			elseif($UTC == 0)
			{
				$form .= '{ "type": "Label", "label": "UTC +0 Großbritannien und 26 weitere GMT London" },';
			}
			elseif($UTC == -1)
			{
				$form .= '{ "type": "Label", "label": "UTC -1 Cabo Verde und 2 weitere CVT Praia" },';
			}
			elseif($UTC == -2)
			{
				$form .= '{ "type": "Label", "label": "UTC -2 Brasilien (manche Regionen) und Südgeorgien und die Südlichen Sandwichinseln BRST Rio de Janeiro" },';
			}
			elseif($UTC == -3)
			{
				$form .= '{ "type": "Label", "label": "UTC -3 Brasilien (manche Regionen) und 10 weitere ART Buenos Aires" },';
			}
			elseif($UTC == -3.5)
			{
				$form .= '{ "type": "Label", "label": "UTC -3:30 Neufundland und Labrador/Kanada NST St. John\'s" },';
			}
			elseif($UTC == -4)
			{
				$form .= '{ "type": "Label", "label": "UTC -4 einige Gebiete von Kanada und 29 weitere VET Caracas" },';
			}
			elseif($UTC == -5)
			{
				$form .= '{ "type": "Label", "label": "UTC -5 Vereinigte Staaten (manche Regionen) und 13 weitere EST New York" },';
			}
			elseif($UTC == -6)
			{
				$form .= '{ "type": "Label", "label": "UTC -6 Vereinigte Staaten (manche Regionen) und 9 weitere CST Mexiko-Stadt" },';
			}
			elseif($UTC == -7)
			{
				$form .= '{ "type": "Label", "label": "UTC -7 einige Gebiete von Vereinigte Staaten und 2 weitere MST Calgary" },';
			}
			elseif($UTC == -8)
			{
				$form .= '{ "type": "Label", "label": "UTC -8 Vereinigte Staaten (manche Regionen) und 3 weitere PST Los Angeles" },';
			}
			elseif($UTC == -9)
			{
				$form .= '{ "type": "Label", "label": "UTC -9 Alaska/Vereinigte Staaten und Französisch-Polynesien (manche Regionen) AKST Anchorage" },';
			}
			elseif($UTC == -9.5)
			{
				$form .= '{ "type": "Label", "label": "UTC -9:30 Marquesas/Französisch-Polynesien MART Taiohae" },';
			}
			elseif($UTC == -10)
			{
				$form .= '{ "type": "Label", "label": "UTC -10 Kleines Gebiet in Vereinigte Staaten und 2 weitere HAST Honolulu" },';
			}
			elseif($UTC == -11)
			{
				$form .= '{ "type": "Label", "label": "UTC -11 American Samoa und 2 weitere NUT Alofi" },';
			}
			elseif($UTC == -12)
			{
				$form .= '{ "type": "Label", "label": "UTC -12 Großteil von US Minor Outlying Islands AoE Bakerinsel" },';
			}
			return $form;
		}
		
		protected function FormSelection()
		{			 
			$form = '{ "type": "Select", "name": "UTC", "caption": "UTC",
					"options": [
						{ "label": "UTC +14 LINT Kiritimati", "value": 14 },
						{ "label": "UTC +13:45 CHADT Chatham-Inseln", "value": 13.75 },
						{ "label": "UTC +13 NZDT Auckland", "value": 13 },
						{ "label": "UTC +12 ANAT Anadyr", "value": 12 },
						{ "label": "UTC +11 AEDT Melbourne", "value": 11 },
						{ "label": "UTC +10:30 ACDT Adelaide", "value": 10.5 },
						{ "label": "UTC +10 AEST Brisbane", "value": 10 },
						{ "label": "UTC +9:30 ACST Darwin", "value": 9.5 },
						{ "label": "UTC +9 JST Tokio", "value": 9 },
						{ "label": "UTC +8:45 ACWST Eucla", "value": 8.75 },
						{ "label": "UTC +8:30 PYT Pjöngjang", "value": 8.5 },
						{ "label": "UTC +8 CST Peking", "value": 8 },
						{ "label": "UTC +7 WIB Jakarta", "value": 7 },
						{ "label": "UTC +6:30 MMT Rangun", "value": 6.5 },
						{ "label": "UTC +6 BST Dhaka", "value": 6 },
						{ "label": "UTC +5:45 NPT Kathmandu", "value": 5.75 },
						{ "label": "UTC +5:30 IST Neu-Delhi", "value": 5.5 },
						{ "label": "UTC +5 UZT Taschkent", "value": 5 },
						{ "label": "UTC +4:30 AFT Kabul", "value": 4.5 },
						{ "label": "UTC +4 GST Dubai", "value": 4 },
						{ "label": "UTC +3:30 IRST Teheran", "value": 3.5 },
						{ "label": "UTC +3 MSK Moskau", "value": 3 },
						{ "label": "UTC +2 OEZ Kairo", "value": 2 },
						{ "label": "UTC +1 MEZ Berlin", "value": 1 },
						{ "label": "UTC +0 GMT London", "value": 0 },
						{ "label": "UTC -1 CVT Praia", "value": -1 },
						{ "label": "UTC -2 BRST Rio de Janeiro", "value": -2 },
						{ "label": "UTC -3 ART Buenos Aires", "value": -3 },
						{ "label": "UTC -3:30 NST St. John\'s", "value": -3.5 },
						{ "label": "UTC -4 VET Caracas", "value": -4 },
						{ "label": "UTC -5 EST New York", "value": -5 },
						{ "label": "UTC -6 CST Mexiko-Stadt", "value": -6 },
						{ "label": "UTC -7 MST Calgary", "value": -7 },
						{ "label": "UTC -8 PST Los Angeles", "value": -8 },
						{ "label": "UTC -9 AKST Anchorage", "value": -9 },
						{ "label": "UTC -9:30 MART Taiohae", "value": -9.5 },
						{ "label": "UTC -10 HAST Honolulu", "value": -10 },
						{ "label": "UTC -11 NUT Alofi", "value": -11 },
						{ "label": "UTC -12 AoE Bakerinsel", "value": -12 }
					]
				},
				{ "type": "Label", "label": "select values for display:" },
				{
                    "name": "juliandate",
                    "type": "CheckBox",
                    "caption": "Julian Date"
                },
				{
                    "name": "moonazimut",
                    "type": "CheckBox",
                    "caption": "moon azimut"
                },
				{
                    "name": "moondistance",
                    "type": "CheckBox",
                    "caption": "moon distance"
                },	
				{
                    "name": "moonaltitude",
                    "type": "CheckBox",
                    "caption": "moon altitude"
                },	
				{
                    "name": "moonbrightlimbangle",
                    "type": "CheckBox",
                    "caption": "moon bright limb angle"
                },	
				{
                    "name": "moondirection",
                    "type": "CheckBox",
                    "caption": "moon direction"
                },		
				{
                    "name": "moonvisibility",
                    "type": "CheckBox",
                    "caption": "moon visibility"
                },	
				{
                    "name": "moonrise",
                    "type": "CheckBox",
                    "caption": "moon rise"
                },	
				{
                    "name": "moonset",
                    "type": "CheckBox",
                    "caption": "moon set"
                },	
				{
                    "name": "moonphase",
                    "type": "CheckBox",
                    "caption": "moon phase"
                },	
				{
                    "name": "newmoon",
                    "type": "CheckBox",
                    "caption": "new moon"
                },	
				{
                    "name": "firstquarter",
                    "type": "CheckBox",
                    "caption": "first quarter"
                },	
				{
                    "name": "fullmoon",
                    "type": "CheckBox",
                    "caption": "full moon"
                },	
				{
                    "name": "lastquarter",
                    "type": "CheckBox",
                    "caption": "last quarter"
                },	
				{
                    "name": "sunazimut",
                    "type": "CheckBox",
                    "caption": "sun azimut"
                },	
				{
                    "name": "sundistance",
                    "type": "CheckBox",
                    "caption": "sun distance"
                },	
				{
                    "name": "sunaltitude",
                    "type": "CheckBox",
                    "caption": "sun altitude"
                },	
				{
                    "name": "sundirection",
                    "type": "CheckBox",
                    "caption": "sun direction"
                },
				{
                    "name": "season",
                    "type": "CheckBox",
                    "caption": "season"
                },
				{
                    "name": "picturemoon",
                    "type": "CheckBox",
                    "caption": "picture moon"
                },
				{
                    "name": "sunmoonview",
                    "type": "CheckBox",
                    "caption": "view position sun and moon"
                },';
			return $form;
		}
		
		protected function FormHead()
		{
			$form = '"elements":
            [
				{ "type": "Label", "label": "Astronomy values" },
				{ "type": "Label", "label": "Coordinated Universal Time (UTC):" },';
			
			return $form;
		}
		
		protected function FormActions()
		{
			$form = '"actions":
			[
				{ "type": "Label", "label": "update values" },
				{ "type": "Button", "label": "update", "onClick": "Astronomy_SetAstronomyValues($id);" }
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
                }
            ]';
			return $form;
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