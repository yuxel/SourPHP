<?

//@todo comments

require_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once ("../classes/SourPHP.php");

class SourPHPScenario extends PHPUnit_Extensions_Story_TestCase
{
    /**
     * @scenario
     */
    public function getEntryTitleById()
    {
        $this->given('Entry id')
             ->when("id equals",1)
             ->then('Title should be', 'pena');
    }


    public function runGiven(&$world, $action, $arguments)
    {
        switch($action) {
            case 'Entry id': {
                $world['obj']  = new SourPHP;
                $world['result'] = null;
            }
            break;
 
            default: {
                return $this->notImplemented($action);
            }
        }
    }
 
    public function runWhen(&$world, $action, $arguments)
    {
        switch($action) {
            case 'id equals': {

                $entryId = $arguments[0];
                $world['result'] = $world['obj']->getEntryById($entryId);

            }
            break;
 
            default: {
                return $this->notImplemented($action);
            }
        }
    }
 
    public function runThen(&$world, $action, $arguments)
    {
        switch($action) {
            case 'Title should be': {
                $title = $world['result']['title'];
                $this->assertEquals($arguments[0], $title);
            }
            break;
 
            default: {
                return $this->notImplemented($action);
            }
        }
    }
}
