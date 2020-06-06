<?php
    namespace App\Service;
    
        class AntispamService{
            Private $_maxLenght;
            public function __construct($maxLengt)
                {
                    $this->_maxLenght=$maxLengt;
                }
            public function isSpam($text)
                {
                    return strlen($text) < $this->_maxLenght;
                }         
        }

?>