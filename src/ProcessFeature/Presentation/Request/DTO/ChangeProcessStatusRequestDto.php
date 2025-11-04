<?php 
namespace App\ProcessFeature\Presentation\Request\DTO;

use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeProcessStatusRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [ProcessStatus::class, 'getValues'])]
        public readonly string $status,
    ) {
    }
}