<?php

namespace App\Exports;

use App\Models\AdmissionList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class GetAdmissionList implements FromCollection
{
    use Exportable;

    /**
     * @param: type=All, faculty_id, department, session
     */
    private $param;

    public function __construct(array $param)
    {
        $this->param = $param;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = AdmissionList::select(
            'candidates.rg_num AS RegNo',
            'candidates.fullname AS Fullname',
            'candidates.rg_sex AS Gender',
            'candidates.aggregate AS Aggregate',
            'candidates.course AS Course',
            'admission_lists.category AS Admission',
            'candidates.session_updated AS Session'
        )
            ->join('candidates', 'candidates.rg_num', '=', 'admission_lists.rg_num')
            ->where('candidates.session_updated', $this->param['session']);

        if ($this->param['type'] != 'All') {
            $query = $query->where('candidates.course', $this->param['type']);
        }

        $query = $query->orderBy('admission_lists.category', 'DESC')->get();

        return $query;
    }
}
