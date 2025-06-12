<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UniqueWithoutDeleted implements Rule
{
    protected $model;
    protected $fields;
    protected $inputData;

    public function __construct(string $modelClass, array $fields, array $inputData)
    {
        $this->model = new $modelClass;
        $this->fields = $fields;
        $this->inputData = $inputData;
    }

    public function passes($attribute, $value)
    {
        $query = $this->model::query()->where('is_deleted', 0);

        foreach ($this->fields as $field) {
            $query->where($field, $this->inputData[$field] ?? null);
        }

        return !$query->exists(); // ✅ Return true if NOT duplicated
    }

    public function message()
    {
        return 'Data dengan nilai yang sama sudah ada dan masih aktif.';
    }
}

