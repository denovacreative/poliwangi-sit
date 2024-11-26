<?php

namespace App\Rules;

use App\Models\Region;
use Illuminate\Contracts\Validation\InvokableRule;

class DistrictParent implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $isDistrictExist = Region::where('level', 2)->where('id', $value)->first();
        if (!$isDistrictExist) $fail('No district found for this ID');
    }
}
