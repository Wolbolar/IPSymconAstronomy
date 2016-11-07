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
		
        $this->RegisterPropertyString("IPFireTV", "");
		$this->RegisterPropertyInteger("system", 0);
		$this->RegisterPropertyString("folder", "C:\\adb-tools\\");
    }

    public function ApplyChanges()
    {
	//Never delete this line!
        parent::ApplyChanges();
		
		
		$FireTVAss =  Array(
				Array(0, "Up",  "", -1),
				Array(1, "Down",  "", -1),
				Array(2, "Left",  "", -1),
				Array(3, "Right",  "", -1),
				Array(4, "Enter",  "", -1),
				Array(5, "Back",  "", -1),
				Array(6, "Home",  "", -1),
				Array(7, "Menu",  "", -1),
				Array(8, "Play",  "", -1),
				Array(9, "Previous",  "", -1),
				Array(10, "Next",  "", -1)				
				);
		$this->RegisterProfileIntegerFireTVAss("Amazon.FireTVRemote", "Execute", "", "", 0, 0, 0, 0, $FireTVAss);
		$this->RegisterVariableInteger("AmazonFireTVRemote", "Amazon FireTV Remote", "Amazon.FireTVRemote", 1);
		$this->EnableAction("AmazonFireTVRemote");	
		
		$this->ValidateConfiguration();	
	
    }

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        *
        */
		
	private function ValidateConfiguration()
	{
		$change = false;
		
		
		$ip = $this->ReadPropertyString('IPFireTV');
				
		//IP prüfen
		if (!filter_var($ip, FILTER_VALIDATE_IP) === false)
			{
				//IP ok
			}
		else
			{
			$this->SetStatus(203); //IP Adresse ist ungültig 
			}
		$change = false;	
		//User und Passwort prüfen
		if ($ip == "")
			{
				$this->SetStatus(202); //Felder dürfen nicht leer sein
			}
		elseif ($ip !== "" &&  (!filter_var($ip, FILTER_VALIDATE_IP) === false))
			{
				$change = true;
				//$this->StartADB();		
				
				// Status Aktiv
				$this->SetStatus(102);	
			}
	}
			
	
	
	public function RequestAction($Ident, $Value)
    {
        switch($Ident) {
            case "AmazonFireTVRemote":
                SetValue($this->GetIDForIdent($Ident), $Value); 
      
				if ($Value == 0)
					{
						$this->Up(); //Up      
					}
				elseif($Value == 1)
					{		    
						$this->Down();  //Down    
					}
				elseif($Value == 2)
					{		    
						$this->Left();  //Left    
					}
				elseif($Value == 3)
					{		    
						$this->Right();  //Right    
					}
				elseif($Value == 4)
					{		    
						$this->Enter();  //Enter    
					}
				elseif($Value == 5)
					{		    
						$this->Back();  //Back    
					}	
				elseif($Value == 6)
					{		    
						$this->Home();  //Home    
					}	
				elseif($Value == 7)
					{		    
						$this->Menu();  //Menu    
					}
				elseif($Value == 8)
					{		    
						$this->Play();  //Play / Pause    
					}
				elseif($Value == 9)
					{		    
						$this->Previous();  //Previous   
					}
				elseif($Value == 10)
					{		    
						$this->Next();  //Next    
					}
				break;	
            default:
                throw new Exception("Invalid ident");
        }
    }
	
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
	
}

?>