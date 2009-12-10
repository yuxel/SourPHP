<?
/**
 * SourPHP
 *
 * LICENSE
 *
 * This file can be distributed under terms of LGPL
 *
 * @package    SourPHP
 * @copyright  Osman Yuksel
 * @LICENSE    http://www.gnu.org/licenses/lgpl-3.0.txt
 * @version    0.1
 */



/**
 * SourPHP 
 *
 * PHP Client to fetch data from http://sozluk.sourtimes.org
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class sourPHP{

    /**
     * Default request address
     *
     * @var String 
     */
    protected static $url = null;

    /**
     * Init default parameters
     */
    public function __construct() {
        $this->url = "http://sozluk.sourtimes.org/";
    }



    /**
     * fetchs data from Url
     *
     * @param  string $url
     * @return string Content of url, returns false if fails
     */
    function fetchUrl($url) {
        if( $data = file_get_contents($url) ) {
            return $data;
        }
        return false;
    }

    /**
     * fetchs an entry id's data
     *
     * @param  int $entryId
     * @return string Content of url
     */
    function fetchId( $entryId ) {
        $entryId = (int) $entryId;
        if($entryId < 1) {
            /**
             * if entry id is not valid
             */
            return false;
        }
        $url = $this->url."show.asp?id=".$entryId;

        return $this->fetchUrl($url);
    }

    /**
     * fetchs an entry per page
     *
     * @param  string $entryName
     * @param  int $page
     * @return string Content of entry
     */
    function fetchEntry( $entryName , $page=1) {
        $page = (int) $page;
        $entryName = (string) $entryName;
        if(strlen($entryName) < 1 ) {
            /**
             * if entry name is not valid
             */
            return false;
        }
        $encodedEntryName = urlencode( trim($entryName) );

        $pageToGo = 1;
        if($page > 1 ) {
            $pageToGo = $page;
        }

        $url = $this->url."show.asp?t=".$encodedEntryName."&p=".$pageToGo;

        return $this->fetchUrl($url);
    }



   /**
     * creates new DomDocument from data retreived from url
     *
     * @param string $data
     * @return DomDucument object
     */
    function createDomDocumentFromData($data) {
        $doc = new DOMDocument();
        $doc->loadHTML ($data);
        return $doc;
    }


    /**
     * executes and XPathQuery to Document and returns results
     * as array
     *
     * @param DomDocument $doc
     * @param string XPathQuery like "//div"
     * @return array $results
     */
    function XPathQueryToDoc($doc, $queryString){
        $xpath = new DOMXpath($doc);
        $elements = $xpath->query($queryString);
        
        return $elements; 

    }

    /**
     * finds entry title from read document
     *
     * @param DomDucument $doc
     * @return string title
     */
    function getEntryTitleFromDoc($doc) {
       
        $query = "//html/body/h1[@class='title']";
        $nodeList = $this->XPathQueryToDoc($doc, $query);

        foreach($nodeList as $node) {
            return trim($node->nodeValue);
        }
        
        return false;
    }


    /**
     * returns number of total pages from read document
     *
     * @param DomDucument $doc
     * @return int number of pages
     */
    function getNumberOfPages($doc) {

        $query = "//select[@class='pagis']/option";
        $nodeList = $this->XPathQueryToDoc($doc, $query);

        foreach($nodeList as $node) {
            $totalPageNum = $node->nodeValue;
        }

        return (int) $totalPageNum;
    }

    /**
     * returns content of entry from read document
     *
     * @param DomDucument $doc
     * @return array entries[content, author, id, order, datetime]
     */
    function getContentOfEntriesFromDoc($doc) {
        $xpath = new DOMXPath($doc);
   
        $query = "//ol[@id='el']/li";
        $metaDivQuery = "//div[@class='aul']";
        $authorQuery = "//div[@class='aul']/a/text()";
        $dateTimeQuery = "//div[@class='aul']/text()";

        $nodeList = $this->XPathQueryToDoc($doc, $query);
        
        foreach($nodeList as $node) {

            $entryId = $node->getAttribute('id');
            $entryId = preg_replace ( '/[^0-9]/', '', $entryId );

            $order = (int) $node->getAttribute("value");

            $authorNodes = $xpath->evaluate ($authorQuery, $node);
            foreach($authorNodes as $authorNode) {
                $author = $authorNode->nodeValue;
            }
           
            $dateTimeNodes = $xpath->evaluate ($dateTimeQuery, $node);
            foreach($dateTimeNodes as $dateTimeNode) {
                $dateTime = explode( "~", trim($dateTimeNode->nodeValue,")") );
            
                $dateCreated = trim(trim($dateTime[0],","));
                $dateEdited = trim($dateTime[1]);

            }

            $results[] = array("entryId"=>$entryId,
                               "order"=>$order,
                               "author"=>$author,
                               "dateCreated"=>$dateCreated,
                               "dateEdited"=>$dateEdited);

            var_dump($results);
            return true;
        }





    }
}
