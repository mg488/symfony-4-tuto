<?php
    namespace App\Service;
    
    class AntispamService {

        Private $_maxLenght; // la valeur de _maxLenght est définie dnas le services.yaml afin qu'elle soit accéssible


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