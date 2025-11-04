<?php

namespace App\ProcessFeature\Presentation\Request\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class StatusFilterResolver implements ValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getName() === 'statusFilter';
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $request->query->get('statusFilter');
    }
}