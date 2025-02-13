<?php

namespace App\Traits;

trait FilterRequestArrayTrait
{
    public function withValidator($validator): void
    {
        $validator->setRules($this->withoutDot($validator->getRules()));
    }

    protected function withoutDot($rules): array
    {
        $newRules = [];
        foreach ($rules as $key => $rule) {
            $newKey = str_replace(['[', ']'], ['.', ''], $key);
            $newRules[$newKey] = $rule;
        }

        return $newRules;
    }
}
