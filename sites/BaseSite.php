<?php
class BaseSite {

    public $database = [
        'host' => '127.0.0.1',
        'username' =>''
    ];

    public $html;
    private $elements = [];

    function __construct() {
        $this->html = new stdClass();

        // creates HTML title tag
        $this->createHTMLelement('title', function($site) {
            return "<title>" . $site->title . '</title>';
        });

        // creates HTML link tag
        $this->createHTMLelement('css' , function($site) {
            return "<link href='" . $site->cssFile . "' media='all' type='text/css' rel='stylesheet' />";
        });

        $this->compileElementObject();
    }

    // function to check domain name to domain given
    public function matches($domain) {
        return ($this->domain === $domain);
    }

    // store html element information in  this->elements
    public function createHTMLelement($name, $callback) {
        $this->elements[$name] = $callback;
    }

    //compiles elements from this->element into the this->html object
    private function compileElementObject() {
        foreach($this->elements as $name => $callback) {
            $this->html->{$name} = $callback($this);
        }
    }
}
?>
