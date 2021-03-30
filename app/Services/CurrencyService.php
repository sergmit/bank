<?php

namespace App\Services;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
    public function getCurrency(array $data): ?Collection
    {
        $date = Carbon::createFromFormat('d.m.Y H:i:s', $data['at_date']);
        $subQuery = DB::table('currencies')->select('currency', DB::raw('MAX(begins_at) as begins_at'))
            ->groupBy('currency');

        return DB::table('currencies', 'cur1')->select('cur1.*')->joinSub($subQuery, 'cur2', function ($join) {
            $join->on('cur1.currency', '=', 'cur2.currency')->on('cur1.begins_at', '=', 'cur2.begins_at');
        })->where(function ($query) use ($data) {
            $query->where('office_id', $data['office_id'])
                ->orWhereNull('office_id');
        })->where('cur1.begins_at', '<=', $date)
            ->get();

    }
}
