<?php 
namespace App\ProcessFeature\Presentation\Request\DTO;

use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateProcessRequestDto
{
    public function __construct(
        #[Assert\Length(min: 1, max: 255)]
        public readonly ?string $title = null,

        #[Assert\Length(max: 5000)]
        public readonly ?string $description = null,

        #[Assert\Choice(callback: [ProcessStatus::class, 'getValues'])]
        public readonly ?string $status = null,
    ) {
    }
}