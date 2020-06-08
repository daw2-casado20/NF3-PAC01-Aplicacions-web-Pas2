<?php

require("class.pdofactory.php");
require("abstract.databoundobject.php");

class postgresLoggerBackend {

        
        public function __construct($urlData) {
            $strDSN = "pgsql:dbname=aplicaweb;host=localhost;port=5432;user=postgres;password=";
            $objPDO = PDOFactory::GetPDO($strDSN, "aplicaweb", "", 
            array());
            $objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $objBakend = new LogData($objPDO);

            $objBakend->setMessage("Mensaje prueba")->setLoglevel(2)->setLogdate(date("2020-02-19"))->setModule("Modulo1")->Save();
            print "El Mensaje es: " . $objBakend->getMessage() . "<br />"; 
            print "El lvl es: " . $objBakend->getLoglevel() . "<br />"; 
            print "la fecha es: " . $objBakend->getLogdate() . "<br />"; 
            print "el modulo es: " . $objBakend->getModule() . "<br />"; 
        }
   
        
}
class LogData extends DataBoundObject{
public $Message;
        public $Loglevel;
        public $Logdate;
        public $Module;
        public function DefineTableName() {
                return("logdata");
        }

        public function DefineRelationMap() {
                return(array(
                        "message" => "Message",
                        "loglevel" => "Loglevel",
                        "logdate" => "Logdate",
                        "module" => "Module"));
        }      
}


?>