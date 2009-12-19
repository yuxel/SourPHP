<?
require_once("classes/SourPHP.php");

/**
 * An example aggregator
 *
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class EksiAggregator extends SourPHP{


    /**
     * prints an array in pre tags
     */
    function preArray($array){
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        echo "<hr/>";
    }

    /**
     * fetch entry id 
     */
    function fetchEntryById($entryId){
        $output= $this->getEntryById($entryId);
        $this->preArray($output);
    }


    /**
     * fetch entries of title, which are created after $date
     */
    function fetchEntriesAfterThisDate($entryTitle, $date){
        $time = strtotime($date);
        $output = $this->getEntriesByTitleAfterGivenTime($entryTitle, $time);
        $this->preArray($output);
    }
}


$aggregator = new EksiAggregator();
$aggregator->fetchEntryById(145878);
$aggregator->fetchEntriesAfterThisDate("neşet ertaş","20.09.2009 21:49");

