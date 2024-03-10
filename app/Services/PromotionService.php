<?php

namespace App\Services;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PromotionService
{
    public function search(Request $request): Collection
    {
        return Promotion::query()->where(function (Builder $query) use ($request) {
            foreach ($request->all() as $key => $value) {
                $this->applyFilter($query, $key, $value);
            }
        })->get();
    }

    protected function applyFilter(Builder $query, string $key, string $value): void
    {
        match ($key) {
            'ids' => $query->whereIn('id', explode(',', $value)),
            'type' => $query->where('type', $value),
            'name' => $query->where('name', 'like', '%'.$value.'%'),
            'start_date', 'end_date' => $this->applyDateFilter($query, $key, $value),
            'is_active' => $this->applyBooleanFilter($query, $key, $value),
            default => null,
        };
    }

    protected function applyDateFilter(Builder $query, string $key, string $value): void
    {
        if (strlen($value) === 4) {
            $query->whereYear($key, $value);

            return;
        }

        $query->whereDate($key, $value);
    }

    protected function applyBooleanFilter(Builder $query, string $key, string $value): void
    {
        $query->where($key, filter_var($value, FILTER_VALIDATE_BOOLEAN));
    }
}
