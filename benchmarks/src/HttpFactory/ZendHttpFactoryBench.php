<?php
declare(strict_types=1);
namespace Narrowspark\Benchmark\HttpFactory;

use Zend\Diactoros\RequestFactory;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\StreamFactory;
use Zend\Diactoros\UriFactory;

class ZendHttpFactoryBench extends AbstractHttpFactoryBenchCase
{
    public function classSetUp(): void
    {
        $this->requestFactory  = new RequestFactory();
        $this->responseFactory = new ResponseFactory();
        $this->streamFactory   = new StreamFactory();
        $this->uriFactory      = new UriFactory();
    }
}