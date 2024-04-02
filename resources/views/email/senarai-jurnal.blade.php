<html>
    <head>
        <link href="assets/css/document/mail-report.css" rel="stylesheet" type="text/css">
    </head>
    <body>
   
        <div ><label >From : {{ $maklumat_agensi->email }}</label></div>
        <div ><label >Date : {{ currentDateTimeZone() }} </label></div>
        <div ><label >Subject : {{ $nama_sistem_short }} - {{ $email_subject }}</label></div>
        <div ><label >To : {{ $email_ispeks }} </label></div>

        <div >

            <p><b>{{ $nama_sistem }}<br/>{{ $email_subject }}</b></p>
            <p>Tarikh : {{ currentDateSys('d-m-Y') }}</p>
            <p>Jumlah Fail Jurnal Dihantar : {{ $dataIntegrasiIspeks->count('journal_no') }}</p>

            <table>
                <tr>
                    <th style="width:5%;">Bil</th>
                    <th style="width:10%;">Nama Fail</th>
                    {{-- <th style="width:10%;">Bil. Jurnal</th> --}}
                    <th style="width:10%;">No. Jurnal</th>
                    <th style="width:10%;" class="right">Amaun Debit (RM)</th>  
                    <th style="width:10%;" class="right">Amaun Kredit (RM)</th>  
                </tr>

                @php 
                    //$totalJurnal = count($dataIntegrasiIspeks);
                    //$rowSpan = $totalJurnal-1; 
                @endphp

                @foreach($dataIntegrasiIspeks as $i => $data)
                 
                    {{-- @if($i == 0) --}}
                        <tr>
                            <td class="center">{{ $loop -> iteration }}</td>                                        
                            <td class="center">{{ $data['nama_fail'] }}</td> 
                            {{-- <td class="center">{{ $totalJurnal }}</td> --}}
                            <td class="center">{{ $data['no_jurnal'] }}</td>            
                            <td class="right">{{ numberFormatComma($data['amaun_debit']) }}</td>
                            <td class="right">{{ numberFormatComma($data['amaun_kredit']) }}</td>
                        </tr>
                    {{-- @else
                        <tr>
                            @if($i == 1) <td class="center" colspan="3" rowspan="{{ $rowSpan }}"></td> @endif                                        
                            <td class="center">{{ $data['no_jurnal'] }}</td>            
                            <td class="right">{{ numberFormatComma($data['amaun_debit']) }}</td>
                            <td class="right">{{ numberFormatComma($data['amaun_kredit']) }}</td>
                        </tr>
                    @endif --}}
                   
                @endforeach 

                <tr>
                    <td colspan="3" class="center bold">Jumlah Keseluruhan Amaun Jurnal (RM)</td>                          
                    <td class="right bold">{{ numberFormatComma($dataIntegrasiIspeks->sum('amaun_debit')) }}</td>
                    <td class="right bold">{{ numberFormatComma($dataIntegrasiIspeks->sum('amaun_kredit')) }}</td>
                </tr>

            </table>

        </div>

</body>
</html>