<?php

namespace Package\Cli;

function document()
{
    $doc = <<<DOC

    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
    
    Options:
      -h --help                     Show this screen
      -v --version                  Show version
    
    DOC;
    
    $result = Docopt::handle($doc, array('version'=>'1.0'));
    foreach ($result as $k=>$v)
        echo $k.': '.json_encode($v).PHP_EOL;
}
