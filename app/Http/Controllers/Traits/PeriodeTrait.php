<?php
namespace App\Http\Controllers\Traits;

use App\Models\AbsensiPeriode;

trait PeriodeTrait
{
    public function getPeriodes()
    {
        return AbsensiPeriode::orderBy('tanggal_mulai', 'desc')->get();
    }

    public function resolvePeriodeFromRequest($request)
    {
        $periodeUuid = $request->get('periode_uuid');
        $periodes = $this->getPeriodes();

        if (!$periodeUuid && $periodes->isNotEmpty()) {
            $periodeUuid = $periodes->first()->uuid;
        }

        $periode = $periodeUuid ? AbsensiPeriode::where('uuid', $periodeUuid)->first() : null;
        return [$periode, $periodes, $periodeUuid];
    }
}
