

<div class="table-responsive">
    <table  width="100%" class="table align-middle table-bordered table-striped dt-responsive ">
        <thead class="bg-primary bg-gradient text-white">
            <tr>
                <th width="6%"  class="text-center">Bil.</th>
                <th width="15%" class="text-center">No. Notis Bayaran</th>
                <th width="15%" class="text-center">Tarikh Bayaran</th>
                <th width="24%" class="text-center">Butiran</th>
                <th width="20%" class="text-center">No. Resit</th>
                <th width="20%" class="text-center">Amaun (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tenantsPaymentNotice as $notice)
            <tr>
                <td class="text-center" tabindex="0" >{{$loop->iteration}} </td>
                <td class="text-center" >{{$notice->payment_notice_no}} </td>
                <td class="text-center" >{{convertDateSys($notice->payment_date)}} </td>
                <td class="text-center" >{{$notice->payment_description}} </td>
                <td class="text-center" >{{$notice->payment_receipt_no}} </td>
                <td class="text-center" >{{$notice->total_amount}} </td>
            </tr>
            @endforeach
            <tr>
                <td class="text-center" colspan="5"><b>JUMLAH KESELURUHAN (RM)</b></td>
                <td class="text-center"><b>{{$collectorStatement->collection_amount}}</b></td>
            </tr>
        </tbody>
    </table>
</div>
