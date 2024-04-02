<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CollectorStatement extends Model
{
    use HasFactory;
    protected $table = 'collector_statement';
    protected $primaryKey = 'id';
    public $timestamps = false;

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

    public function transit_bank()
    {
        return $this->belongsTo(BankAccount::class, 'transit_bank_id');
    }


    public static function collector_statement_by_id($id){

        $data = self::where('id' , $id)->whereIn('data_status', [1,2])->first();

        return $data;
    }

    public static function current_transaction_status($id)
    {
        $data = self::select('transaction_status_id')->where('id' , $id)->first()->transaction_status_id;

        return $data;
    }

    public function transaction_status()
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id');
    }

    public static function collector_statement_ref_no($id)
    {
        $data = self::select('collector_statement_no')->where(['id' => $id, 'data_status' => 1])->first()->collector_statement_no;
        return $data;
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    // PENYATA PEMUNGUT INDEX PAGE ------------------------------------------------------------------------------------------------------------------
    public static function get_collector_statement_active($search_ref_no, $search_date, $login_officer ){ /// untuk tindakan

        // SHOW REKOD KEPADA OFFICER YANG BERTUGAS PADA FASA TERSEBUT SAHAJA
        // KEMASKINI DI TAB SENARAI TERDAHULU
        $data = self::select( '*')
         //  SIMPAN & KUIRI : SHOW TO PEG. PENYEDIA
         ->where(function ($status) use ($login_officer) {
            $status->where(function ($query) use ($login_officer) {
                $query->where(function ($subquery) use ($login_officer) {
                    $subquery   ->whereIn('transaction_status_id', [1, 5])
                                ->where('data_status', 1)
                                ->where('preparer_id', $login_officer);
                });
            })
            // SAH SIMPAN : SHOW TO PEG. SEMAKAN
            ->orWhere(function ($subquery) use ($login_officer) {
                $subquery->where('transaction_status_id', 2)
                    ->where(function ($nestedSubquery) use ($login_officer) {
                        $nestedSubquery ->where('data_status', 1)
                                        ->where('checker_id', $login_officer);
                    });
            })
            // SEMAK: SHOW TO  PEG. LULUS
            ->orwhere(function ($subquery) use ($login_officer) {
                $subquery->where('transaction_status_id', 3)
                    ->where(function ($nestedSubquery) use ($login_officer) {
                        $nestedSubquery ->where('data_status', 1)
                                        ->where('approver_id', $login_officer);
                });
            });
        });

        if($search_ref_no)   $data = $data->where('collector_statement_no', 'LIKE', '%' . $search_ref_no . '%');
        if($search_date) $data = $data->where('collector_statement_date' , $search_date);

        $data =  $data->orderBy('collector_statement_date', 'desc')->get();
        return $data;
    }

    public static function get_collector_statement_history($search_ref_no, $search_date, $login_officer){ /// senarai terdahulu

        //LEPAS PEGAWAI SIMPAN/HANTAR/KEMASKINI/BATAL > MASUK DKT TAB SENARAI TERDAHULU
        //KECUALI STATUS SIMPAN DAN KUIRI : 1 & 5
        $data = self::select('*')
        //SAH SIMPAN
        ->where(function ($status) use ($login_officer) {
            $status->where(function ($status) use ($login_officer) {
                $status->where(function ($query) use ($login_officer) {
                    $query->where('transaction_status_id', 2)
                        ->where(function ($subquery) use ($login_officer) {
                            $subquery->whereNot('checker_id', $login_officer)
                            ->where(function ($nestedSubquery) use ($login_officer) {
                                $nestedSubquery ->where('data_status', 1)
                                                ->where('preparer_id', $login_officer);
                            });
                        });
                })
                //SEMAK
                ->orWhere(function ($subquery) use ($login_officer) {
                    $subquery->where('transaction_status_id', 3)
                        ->where(function ($nestedSubquery) use ($login_officer) {
                            $nestedSubquery->whereNot('approver_id', $login_officer)
                            ->where(function ($q) use ($login_officer) {
                                $q  ->where('data_status', 1)
                                    ->where('preparer_id', $login_officer)
                                    ->orWhere('checker_id', $login_officer);
                            });
                        });
                })
                //LULUS
                ->orWhere(function ($subquery) use ($login_officer) {
                    $subquery->where('transaction_status_id', 4)
                        ->where(function ($nestedSubquery) use ($login_officer) {
                            $nestedSubquery ->where('data_status', 1)
                                            ->where('preparer_id', $login_officer)
                                            ->orWhere('checker_id', $login_officer)
                                            ->orWhere('approver_id', $login_officer);
                        });
                })
                // STATUS BATAL
                ->orwhere(function ($subquery) use ($login_officer) {
                    $subquery->where(function ($q) {
                            $q  ->where('transaction_status_id', 6)
                                ->orwhere('transaction_status_id', 7)
                                ->orwhere('transaction_status_id', 8);
                            })
                            ->where(function ($nestedSubquery) use ($login_officer) {
                                $nestedSubquery ->where('data_status', 2)
                                                ->where('preparer_id', $login_officer)
                                                ->orWhere('checker_id', $login_officer)
                                                ->orWhere('approver_id', $login_officer);
                            });
                });
            });
        });

        if($search_ref_no)   $data = $data->where('collector_statement_no', 'LIKE', '%' . $search_ref_no . '%');
        if($search_date)     $data = $data->where('collector_statement_date' , $search_date);

        $data = $data->orderBy('collector_statement_date', 'desc')->get();
        return $data;
    }

    // TAB PENYATA PEMUNGUT (1) ---------------------------------------------------------------------------------------------------------------
    public static function get_vot_hasil_by_tarikh_and_vot($tarikh_dari, $tarikh_hingga, $district_id, $kaedah_bayaran){ // ajax vot hasil

        $senariRekod = DB::table('tenants_payment_transaction as tpt')
                        ->join('tenants_payment_transaction_vot_list as tpt_vot', function ($join){
                            $join->on('tpt_vot.tenants_payment_transaction_id' , '=', 'tpt.id')
                            ->where(['tpt_vot.data_status'=> 1, 'tpt_vot.flag_ispeks' => 1]);
                        })
                        ->where(function($query) use ($tarikh_dari, $tarikh_hingga){
                            $query ->where('tpt.payment_date', '>=',  $tarikh_dari );
                            $query ->where('tpt.payment_date', '<=',  $tarikh_hingga );
                        })
                        ->where(function($query) {
                            $query ->where('tpt.collector_statement_id' , 0);
                            $query ->orwhereNull('tpt.collector_statement_id');
                        })
                        ->where(['tpt.data_status' => 1, 'tpt.data_status' => 1,  'tpt.district_id' => $district_id, 'tpt.payment_method_id' => $kaedah_bayaran])
                        ->select('tpt.id as tpt_id','tpt.payment_date', 'tpt.payment_method_id','tpt_vot.income_code', 'tpt_vot.income_code_description', 'tpt_vot.income_account_code_id', 'tpt_vot.amount',  DB::raw('SUM(tpt_vot.amount) as total_amount'))
                        ->groupBy('tpt_vot.income_account_code_id')
                        ->get();

        return $senariRekod;
   }

    // TAB SENARAI TERIMAAN HASIL (2) --------------------------------------------------------------------------------------------------------------
    public static function get_senarai_rekod_terimaan($tarikh_dari, $tarikh_hingga, $district_id, $kaedah_bayaran){ // ajax senarai kutipan

        $data = DB::table('tenants_payment_transaction as tpt')
                    ->join('tenants_payment_transaction_vot_list as tpt_vl', function ($join){
                        $join->on('tpt_vl.tenants_payment_transaction_id' , '=', 'tpt.id');
                    })
                    ->where(function($query) use ($tarikh_dari, $tarikh_hingga){
                        $query ->where('tpt.payment_date', '>=',  $tarikh_dari );
                        $query ->where('tpt.payment_date', '<=',  $tarikh_hingga );
                    })
                    ->where(function($query) {
                        $query ->where('tpt.collector_statement_id' , 0);
                        $query ->orwhereNull('tpt.collector_statement_id');
                    })
                    ->where([ 'tpt_vl.data_status' => 1, 'tpt.data_status' => 1,  'tpt_vl.flag_ispeks' => 1, 'tpt.district_id' => $district_id, 'tpt.payment_method_id' => $kaedah_bayaran])
                    ->select('tpt.id as tpt_id', 'tpt.id' , 'tpt.payment_notice_no', 'tpt.payment_date',  'tpt.payment_method_id', 'tpt.payment_description', 'tpt.payment_receipt_no', 'tpt_vl.tenants_payment_transaction_id', 'tpt.total_payment')
                    ->groupBy('tpt.id')
                    ->get();

        return $data;
    }

   // END TAB SENARAI TERIMAAN HASIL ---------------------------------------------------------------------------------------------------------------------------


   // JURNAL PELARASAN ---------------------------------------------------------------------------------------------------------------------------
   public static function get_passed_collector_statement(){ // TELAH LULUS & BELUM MASUK JURNAL

        $data = self:: select('collector_statement.id', 'collector_statement.collector_statement_no', 'collector_statement.transaction_status_id')
                ->where(['collector_statement.transaction_status_id' => 4, 'collector_statement.data_status' =>  1])
                //->leftJoin('journal', 'journal.collector_statement_id' , '=', 'collector_statement.id')
                ->whereRaw("collector_statement.id NOT IN (SELECT collector_statement_id FROM journal where journal.data_status=1)")
                ->get();
        return $data;
    }

    

}
