<?php

declare(strict_types=1);

namespace Empress\Test\Routing\Exception;

use Amp\PHPUnit\AsyncTestCase;
use Empress\Context;
use Empress\Internal\ContextInjector;
use Empress\Routing\Exception\ExceptionHandler;
use Empress\Routing\Exception\ExceptionMapper;
use Empress\Test\Helper\StubRequestTrait;
use Empress\Validation\Registry\ValidatorRegistryInterface;
use Error;
use Exception;
use Generator;

final class ExceptionMapperTest extends AsyncTestCase
{
    use StubRequestTrait;

    public function testHandleRequest(): Generator
    {
        $mapper = new ExceptionMapper();
        $mapper->addHandler(new ExceptionHandler(function (Context $ctx): void {
            $ctx->response('Foo');
        }, Exception::class));

        $request = $this->createStubRequest();
        $validatorRegistry = $this->createMock(ValidatorRegistryInterface::class);

        $context = new Context($request, $validatorRegistry);
        $injector = new ContextInjector($context);
        $injector->setThrowable(new Exception());

        yield $mapper->process($injector);

        self::assertSame('Foo', yield $injector->getResponse()->getBody()->read());
    }

    public function testHandleUncaughtException(): Generator
    {
        $this->expectException(Error::class);

        $mapper = new ExceptionMapper();
        $mapper->addHandler(new ExceptionHandler(fn () => null, Exception::class));

        $request = $this->createStubRequest();
        $validatorRegistry = $this->createMock(ValidatorRegistryInterface::class);

        $context = new Context($request, $validatorRegistry);
        $injector = new ContextInjector($context);
        $injector->setThrowable(new Error());

        yield $mapper->process($injector);
    }
}
