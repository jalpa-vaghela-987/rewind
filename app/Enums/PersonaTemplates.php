<?php

namespace App\Enums;

use Doinc\PersonaKyc\Enums\IPersonaTemplates;

enum PersonaTemplates: string implements IPersonaTemplates {
    public function val(): string
    {
        return $this->value;
    }

	case GOVERNMENT_ID = "example_GOVERNMENT_ID_template_id";
	case GOVERNMENT_ID_AND_SELFIE = "example_GOVERNMENT_ID_AND_SELFIE_template_id";
}
