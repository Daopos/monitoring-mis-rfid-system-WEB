<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueRfid implements ValidationRule
{
    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the RFID exists in home_owners, ignoring the current record if $ignoreId is provided
        $existsInHomeOwners = DB::table('home_owners')
            ->where('rfid', $value)
            ->when($this->ignoreId, fn($query) => $query->where('id', '!=', $this->ignoreId))
            ->exists();

        $existsInHouseholds = DB::table('households')->where('rfid', $value)->exists();
        $existsInVisitors = DB::table('visitors')->where('rfid', $value)->exists();

        if ($existsInHomeOwners || $existsInHouseholds || $existsInVisitors) {
            $fail('The :attribute must be unique across home owners, households, and visitors.');
        }
    }
}