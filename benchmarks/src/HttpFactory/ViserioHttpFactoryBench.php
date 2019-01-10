<?php
declare(strict_types=1);
namespace Narrowspark\Benchmark\HttpFactory;

use Viserio\Component\HttpFactory\RequestFactory;
use Viserio\Component\HttpFactory\ResponseFactory;
use Viserio\Component\HttpFactory\StreamFactory;
use Viserio\Component\HttpFactory\UriFactory;

class ViserioHttpFactoryBench extends AbstractHttpFactoryBenchCase
{
    public function classSetUp(): void
    {
        $this->requestFactory  = new RequestFactory();
        $this->responseFactory = new ResponseFactory();
        $this->streamFactory   = new StreamFactory();
        $this->uriFactory      = new UriFactory();
    }
}