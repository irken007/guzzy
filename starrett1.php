<?php
set_time_limit (0);

class CsvIterator implements Iterator
{
    const ROW_SIZE = 4096;
    /**
     * The pointer to the cvs file.
     * @var resource
     * @access private
     */
    private $filePointer = null;
    /**
     * The current element, which will 
     * be returned on each iteration.
     * @var array
     * @access private
     */
    private $currentElement = null;
    /**
     * The row counter. 
     * @var int
     * @access private
     */
    private $rowCounter = null;
    /**
     * The delimiter for the csv file. 
     * @var str
     * @access private
     */
    private $delimiter = null;

    /**
     * This is the constructor.It try to open the csv file.The method throws an exception
     * on failure.
     *
     * @access public
     * @param str $file The csv file.
     * @param str $delimiter The delimiter.
     *
     * @throws Exception
     */
    public function __construct($file, $delimiter=',')
    {
        try {
            $this->filePointer = fopen($file, 'r');
            $this->delimiter = $delimiter;
        }
        catch (Exception $e) {
            throw new Exception('The file "'.$file.'" cannot be read.');
        } 
    }

    /**
     * This method resets the file pointer.
     *
     * @access public
     */
    public function rewind() {
        $this->rowCounter = 0;
        rewind($this->filePointer);
    }

    /**
     * This method returns the current csv row as a 2 dimensional array
     *
     * @access public
     * @return array The current csv row as a 2 dimensional array
     */
    public function current() {
        $this->currentElement = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
        $this->rowCounter++; 
        return $this->currentElement;
    }

    /**
     * This method returns the current row number.
     *
     * @access public
     * @return int The current row number
     */
    public function key() {
        return $this->rowCounter;
    }

    /**
     * This method checks if the end of file is reached.
     *
     * @access public
     * @return boolean Returns true on EOF reached, false otherwise.
     */
    public function next() {
        return !feof($this->filePointer);
    }

    /**
     * This method checks if the next row is a valid row.
     *
     * @access public
     * @return boolean If the next row is a valid row.
     */
    public function valid() {
        if (!$this->next()) {
            fclose($this->filePointer);
            return false;
        }
        return true;
    }
}

function arrayToCsv( array &$fields, $delimiter = ',', $enclosure = '"', $encloseAll = true, $nullToMysqlNull = false ) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ( $fields as $field ) {
        if ($field === null && $nullToMysqlNull) {
            $output[] = 'NULL';
            continue;
        }

        // Enclose fields containing $delimiter, $enclosure or whitespace
        if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
            $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
        }
        else {
            $output[] = $field;
        }
    }

    return implode( $delimiter, $output );
}

// INSTALA: "guzzlehttp/guzzle": "~5.0" con composer
require ('vendor/autoload.php');

function copyRemote($fromUrl, $toFile) {
    try {
        $client2 = new GuzzleHttp\Client();
        $response2 = $client2->get($fromUrl, ['save_to' => $toFile]);
            //->setAuth('login', 'password') // in case your resource is under protection
            //->save_to($toFile)
            //->send();
        return true;
    } catch (Exception $e) {
        // Log the error or something
        return false;
    }
}

$raiz_fuente = 'http://www.starrett.com/metrology/product-detail/';
$miruta = 'catalogo/';
$list = array();
$list1 = array();
$edp = 'T469HXSP';

$client = new GuzzleHttp\Client();
$response = $client->get($raiz_fuente . $edp);
//var_dump((string)$response->getBody());

$regex = '/<div class=\"image\"(.*?)<\/div>/s';
$regex1= '/a href=\"(.*?)\"/';

//new
$csvIterator = new CsvIterator('starrett0.csv');
foreach ($csvIterator as $row => $data) {
    $edp = $data[1];
    break;

    }



if ( preg_match_all($regex, $response, $list) )
    {
    	if ( preg_match($regex1, $list[0][0], $list1) )
    		{ /*echo $i;*/ $a1=$list1[1];
                copyRemote($a1, $miruta . $edp . ".jpg");
            }
		else
    		{ print "Not found 1"; $a1="";}
    	//echo '<img src="' . $a1 . '">';
    	
    }
    else
    	print "Not found 0";



?>