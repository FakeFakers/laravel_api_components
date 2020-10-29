<?php

declare(strict_types=1);

if (!function_exists('formatValidationErrors')) {
    function formatValidationErrors(array $failed): array
    {
        $errors = [];

        foreach ($failed as $field => $rules) {
            $errors[$field] = [];

            foreach ((array)$rules as $rule => $ruleData) {
                $ruleName = Illuminate\Support\Str::snake($rule);
                if ('unique' === $ruleName || 'exists' === $ruleName) {
                    $ruleData = [];
                }

                $newRule = ['name' => $ruleName];

                if (0 !== \count($ruleData)) {
                    $newRule['params'] = $ruleData;
                }

                $errors[$field][] = $newRule;
            }
        }

        return $errors;
    }
}

if (!function_exists('escapeStringForSqlLike')) {
    function escapeStringForSqlLike(string $str): string
    {
        return addcslashes($str, '%_\\');
    }
}
