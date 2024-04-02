<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $table = 'journal';
    public $timestamps = false;

    public function collector_statement()
    {
        return $this->belongsTo(CollectorStatement::class, 'collector_statement_id');
    }

    public function transaction_status()
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id');
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

    public function journal_type()
    {
        return $this->belongsTo(JournalType::class, 'journal_type_id');
    }

    public static function journal_no($id)
    {
        $data = self::select('journal_no')->where(['id' => $id, 'data_status' => 1])->first()->journal_no;
        return $data;
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------
    // PAGE INDEX
    //--------------------------------------------------------------------------------------------------------------------------------------------
    public static function get_journal_active($search_jurnal_no, $search_date, $login_officer){ // untuk tindakan

        // SHOW REKOD KEPADA OFFICER YANG BERTUGAS PADA FASA TERSEBUT SAHAJA
        $data = self::select('*')
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

        if($search_jurnal_no)   $data = $data->where('journal_no', 'LIKE', '%' . $search_jurnal_no . '%');
        if($search_date)        $data = $data->where('journal_date' , $search_date);

        $data = $data->orderBy('journal_date','DESC')->get();
        return $data;
    }

    public static function get_journal_history($search_jurnal_no, $search_date, $login_officer){ // senarai terdahulu

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

        if($search_jurnal_no)   $data = $data->where('journal_no', 'LIKE', '%' . $search_jurnal_no . '%');
        if($search_date)        $data = $data->where('journal_date' , $search_date);

        $data = $data->orderBy('journal_date','DESC')->get();
        return $data;
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------
    // PAGE EDIT/VIEW
    //--------------------------------------------------------------------------------------------------------------------------------------------

    public static function get_journal_by_id($id){

        $data = self:: select('*')->where('id' , $id)->whereIn('data_status', [1,2])->first();
        return $data;
    }




}
