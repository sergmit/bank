<?php

namespace App\Services;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CurrencyService
{
    /**
     * @param array $data
     * @return Currency
     */
    public function create(array $data): Currency
    {
        $data['begins_at'] = Carbon::createFromFormat('d.m.Y H:i:s', $data['begins_at']);
        return Currency::updateOrCreate([
            'currency' => $data['currency'],
            'begins_at' => $data['begins_at'],
            'office_id' => $data['office_id']], $data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getCurrency(array $data): ?Currency
    {
        $date = Carbon::createFromFormat('d.m.Y H:i:s', $data['at_date']);

        return Currency::where(function (Builder $query) use ($data) {
            $query->where('office_id', $data['office_id'])
                ->orWhereNull('office_id');
        })->where('begins_at', '<=', $date)
            ->orderBy('begins_at', 'desc')->first();
    }
}
