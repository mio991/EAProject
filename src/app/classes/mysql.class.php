<?php
/**
 * Class MySQl
 */
class MySql
{
	/**
	 * Fetch Types
	 */
	const FETCH_ASSOC = 1;
    const FETCH_ROW = 2;
    const FETCH_OBJECT = 3;
    
    /**
     * Query Counter
     */
    private $qcount = 0;
	
	private $_resource = null;
	private $_selected_db = null;
	private static $_unique_instance = null;
	
	public function __construct($host=null, $uname=null, $pass=null, $dbName='information_schema', $port = 3306)
	{
		$this->_resource = mysqli_connect($host, $uname, $pass, $dbName, $port);
		
		if (!$this->_resource)
        {
            $msg = mysqli_connect_error();
            $msg .= ' :: ' .sprintf("\nhost: %s, user: %s, pass: %s, db: %s, port: %s", $host, $uname, $pass, $dbName, $port);
            throw new Exception($msg);
        }

        $this->_selected_db = $dbName;
	}
	
	public static function getInstance($createNew = false)
    {
    	if ($createNew === true )
        {
            $_unique_instance = new MySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
            return $_unique_instance;
        }
        else
        {
            if ( self :: $_unique_instance === NULL )
            {
                self :: $_unique_instance = new MySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
            }
            /*else
            {
                if ( defined("DB_NAME") && self :: $_unique_instance->getSelectedDB() != DB_NAME )
                {
                    self :: $_unique_instance->selectDB(DB_NAME);
                }
            }*/
            return self :: $_unique_instance;
        }
    }
    
    public function selectDB($db_name)
    {
        if ( mysqli_select_db($this->_resource, $db_name) )
        {
            $this->_selected_db = $db_name;
            return true;
        }

        $msg = sprintf("Could not select database %s.\n%s: %s", $db_name, mysqli_errno($this->_resource), mysqli_error($this->_resource));
        throw new Exception($msg);
        return false;
    }
    
	private function getSelectedDB()
    {
        return $this->_selected_db;
    }

	public function escape($value)
    {
        return mysqli_real_escape_string($this->_resource, $value);
    }
    
	public function fetch($res, $type = self :: FETCH_ASSOC)
    {
        if ($this->isResult($res))
        {
            $return = false;
            switch ($type)
            {
                case self :: FETCH_ROW :
                    $return = mysqli_fetch_row($res);
                    break;
                case self :: FETCH_OBJECT :
                    $return = mysqli_fetch_object($res);
                    break;
                default :
                case self :: FETCH_ASSOC :
                    $return = mysqli_fetch_assoc($res);
                    break;
            }
            return $return;
        }
        else
        {
            return false;
        }
    }
    
	public function fetchAll($res, $type = self :: FETCH_ASSOC, $keyfield = null)
    {
        if ($this->isResult($res))
        {
            $array = array ();
            while ($row = $this->fetch($res, $type))
            {
                if ($keyfield != null && isset ($row[$keyfield]))
                {
                    $array[$row[$keyfield]] = $row;
                }
                else
                {
                    $array[] = $row;
                }
            }
            return $array;
        }
        else
        {
            return false;
        }
    }
    
	public function isResult($res)
    {
        if ($res instanceof mysqli_result || $res === true)
        {
            return true;
        }
        else
        {
            $msg= 'No database resultset';
            throw new Exception($msg);
            return false;
        }
    }
    
	public function insertId()
    {
        return mysqli_insert_id($this->_resource);
    }
    
	public function numrows($res)
    {
        if ($this->isResult($res))
        {
            return mysqli_num_rows($res);
        }
        else
        {
            return false;
        }
    }
    
	function numfields($res)
    {
        if ($this->isResult($res))
        {
            return (mysqli_num_fields($res));
        }
        else
        {
            return false;
        }
    }

    public function free($res)
    {
        if ($this->isResult($res))
        {
            mysqli_free_result($res);
        }
    }

    public function query($query)
    {
        $this->qcount++;
        return mysqli_query($this->_resource, $query); // or $this->logAndThrowError();
    }

    public function queryf($query)
    {
        $arg = array ();
        for ($i = 1; $i < func_num_args(); $i++)
        {
            $targ = func_get_arg($i);
            $targ = $this->escape($targ);
            $arg[] = $targ;
        }
        
        return $this->query(vsprintf($query, $arg));
    }

    public function getError($sql = '')
    {
        $return = $this->getErrorNo();
        $return .= ' - ';

        $return .= mysqli_error($this->_resource);
        return $return;
    }

    public function getErrorNo()
    {
        return mysqli_errno($this->_resource);
    }

    public function getAffected()
    {
        return mysqli_affected_rows($this->_resource);
    }
    
	public function getLastId()
	{
		return $this->_resource->insert_id;
	}
}
?>