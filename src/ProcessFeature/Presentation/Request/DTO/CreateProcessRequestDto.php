<?php 
namespace App\ProcessFeature\Presentation\Request\DTO;

use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProcessRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public readonly string $title,

        #[Assert\Length(max: 5000)]
        public readonly ?string $description = null,

        #[Assert\Choice(callback: [ProcessStatus::class, 'getValues'])]
        public readonly string $status,
    ) {
    }
}