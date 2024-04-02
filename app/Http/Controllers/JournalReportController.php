<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\FinanceDepartment;
use Barryvdh\DomPDF\Facade\Pdf;

class JournalReportController extends Controller
{
    public function index(Request $request){

        $district_id = $this->getDistrictId();

        $search_journal_no  = ($request->search_journal_no) ? ($request->search_journal_no) : null;
        $search_from   = ($request->date_from) ? convertDatepickerDb($request->date_from) : null;
        $search_to     = ($request->date_to)   ? convertDatepickerDb($request->date_to)   : null;

        $recordList = $this->getFilteredJournalRecords($district_id, $search_journal_no, $search_from, $search_to);
        $recordListAll = $recordList->get();

        $totalDebit = numberFormatComma($recordListAll->sum('debit_amount'));
        $totalCredit = numberFormatComma($recordListAll->sum('credit_amount'));

        if ($request->input('muat_turun_pdf')) {
            return $this->generatePdf($recordListAll, $search_from, $search_to, $district_id, $totalCredit, $totalDebit);
        } else {
            return view(getFolderPath() . '.index', compact('search_journal_no', 'search_from', 'search_to', 'recordListAll', 'totalCredit', 'totalDebit'));
        }
    }

    private function getDistrictId()
    {
        return (!is_all_district()) ? districtId() : null;
    }

    private function getFilteredJournalRecords($district_id, $search_journal_no, $search_from, $search_to)
    {
        $recordList = Journal::from('journal as j')
        ->join('collector_statement as cs','cs.id','=','j.collector_statement_id')
        ->join('journal_vot_list as jvl', 'jvl.journal_id','=','j.id')
        ->join('income_account_code as iac','iac.id','=','jvl.income_account_code_id')
        ->select('j.district_id','j.journal_no','j.journal_date','j.description','cs.collector_statement_no','iac.ispeks_account_code','jvl.debit_amount','jvl.credit_amount',)
        ->where(['j.data_status'=>1,'cs.data_status'=>1,'jvl.data_status'=>1,'iac.data_status'=>1, 'j.transaction_status_id' => 4]); // telah lulus

        if($district_id) $recordList = $recordList->where('j.district_id', $district_id);

        //searching
        if($search_journal_no) $recordList = $recordList->where('j.journal_no', 'LIKE', '%' . $search_journal_no . '%');
        if($search_from) $recordList = $recordList->where('j.journal_date','>=' , $search_from);
        if($search_to) $recordList = $recordList->where('j.journal_date', '<=' , $search_to);

        return $recordList;
    }

    private function generatePdf($recordListAll, $search_from, $search_to , $district_id, $totalCredit, $totalDebit)
    {
        //convert search dates and get the year
        $search_from_convert = convertDateSys($search_from);
        $search_to_convert = convertDateSys($search_to);

        //get finance department by district
        $fd = FinanceDepartment::finance_department_by_district($district_id);

        //calculate sums for each journal number
        $journalSums = [];
        foreach ($recordListAll as $j)
        {
            $journalNo = $j->journal_no ?? '';
            $ispeksAccountCode = $j->ispeks_account_code ?? '';
            $debitAmount = $j->debit_amount ?? 0;
            $creditAmount = $j->credit_amount ?? 0;

            $key = $journalNo ;
            if (!isset($journalSums[$key])) {
                $journalSums[$key] =
                [
                    'debit' => $debitAmount,
                    'credit' => $creditAmount,
                ];
            } else {
                $journalSums[$key]['debit'] += $debitAmount;
                $journalSums[$key]['credit'] += $creditAmount;
            }
        }

        //group record list by journal number
        $groupedRecords = $recordListAll->groupBy('journal_no');
        foreach ($groupedRecords as $key => $group)
        {
            $firstRecord = $group->first();
            $rowspan = $group->count();

            foreach ($group as $j) {
                $j->rowspan = $rowspan;
                $j->journal_date = convertDateSys($j->journal_date ?? '');
                $j->debit_amount_formatted = numberFormatComma($j->debit_amount ?? 0);
                $j->credit_amount_formatted = numberFormatComma($j->credit_amount ?? 0);
            }

            $journalSums[$key]['debit'] = numberFormatComma($group->sum('debit_amount'));
            $journalSums[$key]['credit'] = numberFormatComma($group->sum('credit_amount'));
        }

        //generate the PDF
        $dataReturn =  compact('search_from_convert', 'search_to_convert', 'fd', 'journalSums','groupedRecords' ,'recordListAll', 'totalCredit', 'totalDebit');
        //------------------------------------------------------------------------------------------------------
        $tempPdf = PDF::loadview(getFolderPath() . '.cetak-pdf', $dataReturn);
        $tempPdf->setPaper('A4', 'landscape');
        $tempPdf->output();
        // Get the total page count
        $totalPages = $tempPdf->getCanvas()->get_page_count();
        //------------------------------------------------------------------------------------------------------

        $pdf = PDF::loadview(getFolderPath() . '.cetak-pdf', array_merge($dataReturn, compact('totalPages')));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Jurnal_'.date("dmY-His").'.pdf');
    }
}
