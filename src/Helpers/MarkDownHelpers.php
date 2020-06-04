<?php

namespace App\Helpers;

use cebe\markdown\Markdown;

class MarkDownHelpers{

    private $_parser;
    public function __construct(Markdown $parser){
        $this->_parser = $parser;
    }


    public function parse(array $posts):array{
        $parsedPost = [];
        foreach($posts as $p){
            $parsedPost[]=[
                'title'  =>$p->getTitle(),
                'content'=>$this->_parser->parse($p->getContent())
            ];
        }
        return $parsedPost;
    }
}
