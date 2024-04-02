<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalJournal extends Model
{
    use HasFactory;
    protected $table    = 'internal_journal';
    public $timestamps  = false;

    public function payment_notice()
    {
        return $this->belongsTo(TenantsPaymentNotice::class, 'tenants_payment_notice_id');
    }

    public function transaction_status()
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id');
    }

    public static function get_internal_journal_by_id($id){

        $data = self:: select('*')->where('id' , $id)->whereIn('data_status', [1,2])->first();
        return $data;
    }

    public static function internal_journal_no($id)
    {
        $data = self::select('journal_no')->where(['id' => $id, 'data_status' => 1])->first()->journal_no;
        return $data;
    }

    public static function current_transaction_status($id)
    {
        $data = self::select('transaction_status_id')->where('id' , $id)->first()->transaction_status_id;

        return $data;
    }

    public function preparer()
    {
        return $this->belongsTo(FinanceOfficer::class, 'preparer_id');
    }

    public function checker()
    {
        return $this->belongsTo(FinanceOfficer::class, 'checker_id');
    }

    public function approver()
    {
        return $this->belongsTo(FinanceOfficer::class, 'approver_id');
    }

        //--------------------------------------------------------------------------------------------------------------------------------------------
    // PAGE INDEX
    //--------------------------------------------------------------------------------------------------------------------------------------------
    public static function get_internal_journal_active($search_jurnal_no, $search_date, $login_officer){ // untuk tindakan

        // SHOW REKOD KEPADA OFFICER YANG BERTUGAS PADA FASA TERSEBUT SAHAJA
        $data = self::from('internal_journal as j')->select('j.*', 'tpn.payment_status')
         //  SIMPAN & KUIRI : SHOW TO PEG. PENYEDIA
         ->where(function ($status) use ($login_officer) {
            $status->where(function ($query) use ($login_officer) {
                $query->where(function ($subquery) use ($login_officer) {
                    $subquery   ->whereIn('j.transaction_status_id', [1, 5])
                                ->where('j.data_status', 1)
                                ->where('j.preparer_id', $login_officer);
                });
            })
            // SAH SIMPAN : SHOW TO PEG. SEMAKAN
            ->orWhere(function ($subquery) use ($login_officer) {
                $subquery->where('j.transaction_status_id', 2)
                    ->where(function ($nestedSubquery) use ($login_officer) {
                        $nestedSubquery ->where('j.data_status', 1)
                                        ->where('j.checker_id', $login_officer);
                    });
            })
            // SEMAK: SHOW TO  PEG. LULUS
            ->orwhere(function ($subquery) use ($login_officer) {
                $subquery->where('j.transaction_status_id', 3)
                    ->where(function ($nestedSubquery) use ($login_officer) {
                        $nestedSubquery ->where('j.data_status', 1)
                                        ->where('j.approver_id', $login_officer);
                });
            });
        });

        if($search_jurnal_no)   $data = $data->where('j.journal_no', 'LIKE', '%' . $search_jurnal_no . '%');
        if($search_date)        $data = $data->where('j.journal_date' , $search_date);

        $data = $data ->join('tenants_payment_notice as tpn', 'tpn.id', '=', 'j.tenants_payment_notice_id')->get();

        return $data;
    }

    public static function get_internal_journal_history($search_jurnal_no, $search_date, $login_officer){ // senarai terdahulu

        //LEPAS PEGAWAI SIMPAN/HANTAR/KEMASKINI/BATAL > MASUK DKT TAB SENARAI TERDAHULU
        //KECUALI STATUS SIMPAN DAN KUIRI : 1 & 5
        $data = self::from('internal_journal as j')->select('j.*', 'tpn.payment_status')
        //SAH SIMPAN
        ->where(function ($status) use ($login_officer) {
            $status->where(function ($status) use ($login_officer) {
                $status->where(function ($query) use ($login_officer) {
                    $query->where('j.transaction_status_id', 2)
                        ->where(function ($subquery) use ($login_officer) {
                            $subquery->whereNot('j.checker_id', $login_officer)
                            ->where(function ($nestedSubquery) use ($login_officer) {
                                $nestedSubquery ->where('j.data_status', 1)
                                                ->where('j.preparer_id', $login_officer);
                            });
                        });
                })
                //SEMAK
                ->orWhere(function ($subquery) use ($login_officer) {
                    $subquery->where('j.transaction_status_id', 3)
                        ->where(function ($nestedSubquery) use ($login_officer) {
                            $nestedSubquery->whereNot('j.approver_id', $login_officer)
                            ->where(function ($q) use ($login_officer) {
                                $q  ->where('j.data_status', 1)
                                    ->where('j.preparer_id', $login_officer)
                                    ->orWhere('j.checker_id', $login_officer);
                            });
                        });
                })
                //LULUS
                ->orWhere(function ($subquery) use ($login_officer) {
                    $subquery->where('j.transaction_status_id', 4)
                        ->where(function ($nestedSubquery) use ($login_officer) {
                            $nestedSubquery ->where('j.data_status', 1)
                                            ->where('j.preparer_id', $login_officer)
                                            ->orWhere('j.checker_id', $login_officer)
                                            ->orWhere('j.approver_id', $login_officer);
                        });
                })
                // STATUS BATAL
                ->orwhere(function ($subquery) use ($login_officer) {
                    $subquery->where(function ($q) {
                            $q  ->where('j.transaction_status_id', 6)
                                ->orwhere('j.transaction_status_id', 7)
                                ->orwhere('j.transaction_status_id', 8);
                            })
                            ->where(function ($nestedSubquery) use ($login_officer) {
                                $nestedSubquery ->where('j.data_status', 2)
                                                ->where('j.preparer_id', $login_officer)
                                                ->orWhere('j.checker_id', $login_officer)
                                                ->orWhere('j.approver_id', $login_officer);
                            });
                });
            });
        });

        if($search_jurnal_no)   $data = $data->where('j.journal_no', 'LIKE', '%' . $search_jurnal_no . '%');
        if($search_date)        $data = $data->where('j.journal_date' , $search_date);

        $data = $data ->join('tenants_payment_notice as tpn', 'tpn.id', '=', 'j.tenants_payment_notice_id')->get();

       // $data = $data->get();
        return $data;
    }


    //--------------------------------------------------------------------------------------------------------------------------------------------
    // INTERNAL JOURNAL REPORT
    //--------------------------------------------------------------------------------------------------------------------------------------------

    public static function get_passed_internal_journal($district_id, $search_date_from, $search_date_to){ // telah lulus

        $data = self::select('journal_no', 'journal_date', 'tenants_payment_notice_id', 'tenants_name', 'description', 'payment_notice_amount', 'total_amount', 'adjustment_amount')
        ->where(['transaction_status_id' => 4, 'data_status' => 1]);

        if($district_id){ $data = $data->where('district_id', $district_id);  }
        if($search_date_from){ $data = $data->where('journal_date','>=' , $search_date_from); }
        if($search_date_to  ){ $data = $data->where('journal_date','<=' , $search_date_to); }

        $data = $data->get();
        return $data;
    }


}
