<div class="mb-3 row">
    <label class="col-md-2" >Senarai Vot Hasil</label>
    <div class="col-md-7">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="bg-primary bg-gradient text-white">
                    <tr >
                        <th style="width:6%;" class="text-center">Bil.</th>
                        <th style="width:64%;" class="text-center">Kod Hasil</th>
                        <th style="width:30%;" class="text-center">Jumlah Terimaan (RM)</th>
                    </tr>
                </thead>
                <tbody >
                    @foreach($collectorStatementVotList as $votList)
                    <tr>
                        <td class="text-center" tabindex="0" >{{$loop->iteration}} </td>
                        <td class="text-left justify-between" >{{$votList->income_account?->income_code.' - '.$votList->income_account?->income_code_description}} </td>
                        <td class="text-center" >{{$votList->total_amount}} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-center" colspan="2"><b>JUMLAH KESELURUHAN (RM)</b></td>
                        <td class="text-center"><b>{{$collectorStatement->collection_amount}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
