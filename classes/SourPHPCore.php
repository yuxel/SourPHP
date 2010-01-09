<?
/**
 * SourPHPCore
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
 * SourPHPCore
 *
 * PHP Client to fetch data from http://sozluk.sourtimes.org
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class SourPHPCore{

    /**
     * Default request address
     *
     * @var String 
     */
    protected static $url = null;

    /**
     * Num of entries per page
     *
     * @var String 
     */
    protected static $contentPerPage = null;


    /**
     * cache handler, instance of SourPHPUrlCacheInterface
     *
     * @var object
     */
    protected $_cacheHandler = null;

    /**
     * prefix for cached hashs
     *
     * @var string
     */
    protected $_cachePrefix = null;



    /**
     * cache lifetime for handler
     *
     * @var int
     */
    protected $_cacheLifeTime;


    /**
     * Init default parameters
     */
    public function __construct() {
        $this->url = "http://sozluk.sourtimes.org/";
        $this->contentPerPage = 25; 
    }



    /**
     * fetchs data from Url
     *
     * @param  string $url
     * @return string Content of url, returns false if fails
     */
    function fetchUrl($url) {
        if( $this->_cacheHandler ) {
            $hash = $this->_cacheHandler->hashUrl ( $url, $this->_cachePrefix );

            //check if cache expired, if so regenerate it
            if( $this->_cacheHandler->isExpired($hash, $this->_cacheLifeTime ) ) {
                $data = @file_get_contents($url);
                $this->_cacheHandler->regenerateData ( $hash, $data );
            }
            
            return $this->_cacheHandler->getCurrentData($hash);
        }
        //if cache handler not set
        else {

            if( $data = @file_get_contents($url) ) {
                return $data;
            }
            return false;
    
        }
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
     * @param  int $page, -1 for last page
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

        if($page>0) {
            $url = $this->url."show.asp?t=".$encodedEntryName."&p=".$pageToGo;
        }
        else{
            //if page < 1, fetch last page
            $url = $this->url."show.asp?t=".$encodedEntryName."&i=2099999999";
        }

        return $this->fetchUrl($url);
    }



   /**
     * creates new DomDocument from data retreived from url
     *
     * @param string $data
     * @return DomDucument object
     */
    function createDomDocumentFromData($data) {
        if(!$data) {
            return false;
        }
        $doc = new DOMDocument();
        @$doc->loadHTML ($data);
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
        
        if($doc) {
            $nodeList = $this->XPathQueryToDoc($doc, $query);

            foreach($nodeList as $node) {
                $totalPageNum = $node->nodeValue;
            }
        }

        return (int) $totalPageNum;
    }


    /**
     * parses author, dateCreated and dateEdited from $string
     */
    function parseAuthorAndDateFromString($string){
        $trimSpaces = trim($string);
        $trimmed = trim($trimSpaces, "(");
        $exploded = explode(")", $trimmed);
        $content = $exploded[0];

        list($author, $dates) = explode(",", $content);
        list($dateCreated, $dateEdited) = explode("~", $dates);

        $author = trim($author);
        $dateCreated = strtotime( trim( $dateCreated) );
        $dateEdited = strtotime( trim ( $dateEdited) );

        return array($author, $dateCreated, $dateEdited);
    }


    /**
     * returns content of entry from read document
     *
     * @param DomDucument $doc
     * @return array entries[content, author, id, order, datetime]
     */
    function getContentOfEntriesFromDoc($doc) {
        if(!$doc) {
            return false;
        }

        // this xpath query etches li element which as all details of entry
        $query = "//ol[@id='el']/li";

        //find title
        $title = $this->getEntryTitleFromDoc($doc);

        $nodeList = $this->XPathQueryToDoc($doc, $query);
        
        foreach($nodeList as $node) {
            $childCount = $node->childNodes->length;

            //lastNode has information about author and dates
            $lastNode = $node->childNodes->item($childCount-1);
            list($author, $dateCreated, $dateEdited) = $this->parseAuthorAndDateFromString($lastNode->textContent);

            //if we remove lastChild, our node is our content
            if( $lastNode ) {
                $node->removeChild ($lastNode );
            }
            $contentNode = $node;

            $content = $contentNode->textContent;

            $entryId = $node->getAttribute('id');
            $entryId = (int) preg_replace ( '/[^0-9]/', '', $entryId );

            $order = (int) $node->getAttribute("value");

            $results[] = array("entryId"=>$entryId,
                               "title"=>$title,
                               "content"=>$content,
                               "order"=>$order,
                               "author"=>$author,
                               "dateCreated"=>$dateCreated,
                               "dateEdited"=>$dateEdited 
                               );

        }

        return empty($results)?false:$results;

    }



    /**
     * sets cache handler
     *
     * @param object $handler 
     */
    function setCacheHandler($handler) {
        $implements = class_implements($handler);

        if( !in_array( "SourPHPUrlCacheInterface", $implements ) ) {
            return false;
        }
       
        $this->_cacheHandler = $handler; 
        return true;
    }


    /**
     * sets cache life time _cacheLifeTime
     *
     * @param int $lifeTime
     */
    function setCacheLifeTime($lifeTime) {
        $this->_cacheLifeTime = $lifeTime;
        
        return $this->_cacheLifeTime;
    }


    /**
     * sets cachePrefix
     *
     * @param string $prefix
     */
    function setCachePrefix($prefix){
        $this->_cachePrefix = $prefix;
    }

}
