<?php

namespace App\Services;

use App\Repository\DocumentationRepository;

class Template
{

    private $dr;

    public function __construct(DocumentationRepository $dr)
    {
        $this->dr = $dr;
    }

    public function getNavigation()
    {
        $docs = $this->dr->findBy([], ['category' => 'ASC']);
        $return = [];
        foreach ($docs as $doc) {
            $return[$doc->getCategory()->getPath()]['root'] = $doc->getCategory()->getName();
            $return[$doc->getCategory()->getPath()][$doc->getPath()] = $doc->getTitle();
        }
        return $return;
    }

}