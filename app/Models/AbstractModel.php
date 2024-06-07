<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    public function jsonSet(string $field, string $key, mixed $value): void
    {
        $current = $this->{$field};
        $current[$key] = $value;
        $this->{$field} = $current;
    }

    public function jsonUnset(string $field, string $key): void
    {
        $current = $this->{$field};
        unset($current[$key]);
        $this->{$field} = $current;
    }
}
