<?php

class db {
    /**
     * @name $con
     * @var object Data object
     */
    var $con;
    /**
     * @name $m_sClassname
     * @var string Name for the current object to control data for
     */
    var $m_sClassname;
    /**
     * @name $m_aVars
     */
    var $m_aVars;
	var $tableprefix;
    var $dbuser;
    var $dbhost;
    var $dbpassword;
    var $selecteddb;

    public function  __construct() {
        $this->tableprefix  = "peliwash_";//Prefix to be used preceding the data tables (do not change after installation!)
        $this->dbuser       = "peliwash";
        $this->dbpassword   = "";
        $this->dbhost       = "localhost";
        $this->selecteddb   = "peliwash";
        //Connect mysql
        $this->con          = mysql_connect($this->dbhost,$this->dbuser,$this->dbpassword);
        if(!$this->con){
            die('Could not connect: ' . mysql_error());
        }
        // make pelikaanhofnl the current db
        $db_selected = mysql_select_db($this->selecteddb, $this->con);
        if (!$db_selected) {
            die ('Can\'t use pelikaanhofnl : ' . mysql_error());
        }
    }
    private function loopRows($result){
        if (mysql_num_rows($result) != 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $resultarray[]=$row;
            }
            //return array containing results
            return $resultarray;
        }else{
            //return nothing
            return FALSE;
        }

    }
    private function createTable($classname){
        //Check if table exists
        if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$this->tableprefix.$classname."'"))){
            //Get table columns
            $this->m_aVars=get_class_vars($classname);
            //build sql statement
            $sql='CREATE TABLE '.$this->tableprefix.$classname.'(';
            $sql.='ID int NOT NULL AUTO_INCREMENT,';
            foreach($this->m_aVars as $key=>$val){
                    $sql.=' '.$key.' varchar(255),';
            }
            $sql.=" PRIMARY KEY (ID))";
            //Execute query
            $result=mysql_query($sql);
            //Process result
            if($result){
                return TRUE;
            }else{
                echo "TABLE ".$classname."COULD NOT BE CREATED: ".  mysql_error();
            }
        }
    }
    public function listall($selectedtable,$limit=""){
        //Create table if it does not exist
        $this->createTable($selectedtable);
        //Build query
        $result=mysql_query("SELECT * FROM ".$this->tableprefix.$selectedtable);
        //Return array of rows
        return $this->loopRows($result);
    }
    public function entry_by_two_properties($selectedtable, $property1,$needle1,$property2,$needle2){
        //Create table if it does not exist
        $this->createTable($selectedtable);
        //Build query
        $result=mysql_query("SELECT * FROM ".$this->tableprefix.$selectedtable." WHERE ".$property1."='".$needle1."' AND ".$property2."='".$needle2."'");
        //Process results
        return $this->loopRows($result);
    }

    public function entry_by_property($selectedtable, $property, $needle){
        //Create table if it does not exist
        $this->createTable($selectedtable);
        //Build query
        $result=mysql_query("SELECT * FROM ".$this->tableprefix.$selectedtable." WHERE ".$property."='".$needle."'");
        //Process results
        return $this->loopRows($result);
    }

    public function entry_by_attribute($class, $attribute,$needle){
        //Attributes are now properties so entries can be found with entry by property
	return $this->entry_by_property($class, $attribute, $needle);
    }

    public function new_entry($object,$ID="",$alternateclass=""){
		//Add mtime for checks
		file_put_contents("mtime.txt",Date("U"));
        //Get object vars
        $this->m_aVars = get_object_vars($object);
		$this->m_aVars[ID]=$ID;
        //Get object classname
        $selectedtable=get_class($object);
        //Create table if it does not exist
        $this->createTable($selectedtable);
        //Build query
        $sql = sprintf('INSERT INTO '.$this->tableprefix.$selectedtable.' (%s) VALUES ("%s")', implode(',',array_keys($this->m_aVars)), implode('","',array_values($this->m_aVars)));
        //Execute query
        $result=mysql_query($sql);
		echo mysql_error($this->con);
		$ID=mysql_insert_id($this->con);
        //Return result (boolean)
        return $ID;
    }

    public function delete_entry($selectedtable,$ID){
		//Add mtime for checks
		file_put_contents("mtime.txt",Date("U"));
        //Create table if it does not exist
        $this->createTable($selectedtable);
        //Build sql query
        $sql='DELETE FROM '.$this->tableprefix.$selectedtable." WHERE ID='".$ID."'";
        //Execute query
        $result=mysql_query($sql);
        //Return results
        return $result;
    }

    public function update_entry($object, $ID){
		//Add mtime for checks
		file_put_contents("mtime.txt",Date("U"));
        //Get object classname
        $selectedtable=get_class($object);
        //Create table if it does not exist
        $this->createTable($selectedtable);
        //Get object vars
        $this->m_aVars = get_object_vars($object);
        //Build query
        $sql = 'UPDATE '.$this->tableprefix.$selectedtable.' SET';
        foreach($this->m_aVars as $key=>$val){
                if($val){
                        $sql.=' '.$key."='".$val."',";
                }
        }
        //Remove trailing comma
        $sql=substr($sql,0,-1);
        //Set update destination
        $sql.=" WHERE ID='".$ID."'";
        //Execute query
        $result=mysql_query($sql);
        //Return result (boolean)
        return $result;
    }
}
?>
