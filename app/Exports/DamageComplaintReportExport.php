<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ComplaintAppointment;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintAppointmentAttachment;
use App\Models\ComplaintOthers;
use App\Models\ComplaintInventory;
use App\Models\ComplaintInventoryAttachment;
use App\Models\QuartersCategory;
use Maatwebsite\Excel\Concerns\WithHeadings;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DamageComplaintReportExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder  implements WithCustomValueBinder, FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $quarters_catname;
    protected $complaintData;
    protected $headername;


    function __construct($quarters_catname,$complaintData,$headername) {
        $this->complaintData = $complaintData;
        $this->quarters_catname = $quarters_catname;
        $this->headername = $headername;
    }

    public function headings():array{
        return[
            'BIL.',
            'NAMA',
            'JAWATAN',
            'JABATAN',
            'ALAMAT',
            'BUTIRAN KEROSAKAN', 
            'BUTIRAN LAIN-LAIN',
            'TARIKH ADUAN',
            'TARIKH TEMUJANJI'
        ];
    }

    public function collection()
    {
       return $this->complaintData;
    }
}
