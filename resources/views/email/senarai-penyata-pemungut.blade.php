<html>
    <head>
        <link href="assets/css/document/mail-report.css" rel="stylesheet" type="text/css">
    </head>
    <body>
   
        <div ><label >From : {{ $maklumat_agensi->emel_agensi }}</label></div>
        <div ><label >Date : {{ currentDateTimeZone() }} </label></div>
        <div ><label >Subject : {{ $nama_sistem_short }} - {{ $email_subject }}</label></div>
        <div ><label >To : {{ $email_ispeks }} </label></div>

        <div >

            <p><b>{{ $nama_sistem }}<br/>{{ $email_subject }}</b></p>
            <b>Tarikh :</b> {{ currentDateSys('d/m/Y') }}
            <br/><b>Jumlah Fail Penyata Pemungut Dihantar :</b> {{ $dataIntegrasiIspeks->sum('bil_penyata_pemungut') }}<p>

            <table width="100%" cellspacing ="0" cellpadding="5">
                <tr class="info_content_border">
                    <th style="width:5%;">Bil</th>
                    <th style="width:10%;">Nama Fail</th>
                    <th style="width:10%;">Bil. PP</th>
                    <th style="width:10%;">No. PP</th>
                    <th style="width:10%;">Cara Bayar</th>
                    <th style="width:10%;" class="right">Amaun PP (RM)</th>  
                </tr>

                @php $bil=1; @endphp
                @foreach($dataIntegrasiIspeks as $i => $data)

                    @php 
                        $totalPP = count($data['senarai_penyata_pemungut']);
                    @endphp

                    @php $bil_pp = 1;  $iPayment = 0; @endphp
                    @foreach($data['senarai_penyata_pemungut'] as $dataPP)

                        @if($bil_pp == 1)
                            <tr class="info_content_border">
                                <td class="center" rowspan="{{ $totalPP }}">{{ $bil }}</td>                                        
                                <td class="center" rowspan="{{ $totalPP }}">{{ $data['nama_fail'] }}</td>                            
                                <td class="center" rowspan="{{ $totalPP }}">{{ $totalPP }}</td>
                                <td class="center">{{ $dataPP['no_penyata_pemungut'] }}</td>
                                <td class="center">{{ $data['jenis_bayaran'] }}</td>                          
                                <td class="right" >{{ numberFormatComma($dataPP['amaun_penyata_pemungut']) }}</td>
                            </tr>
                        @else
                            <tr>                                                               
                                <td class="center">{{ $dataPP['no_penyata_pemungut'] }}</td>
                                <td class="center">{{ $data['jenis_bayaran'] }}</td>                          
                                <td class="right" >{{ numberFormatComma($dataPP['amaun_penyata_pemungut']) }}</td>
                            </tr>
                        @endif
                        @php $bil_pp++; $iPayment++; @endphp
                    @endforeach 

                    @php $bil++; @endphp
                    
                @endforeach 

                <tr class="info_content_border">
                    <td colspan="5" class="center bold">Jumlah Keseluruhan Amaun PP (RM)</td>                          
                    <td class="right bold">{{ numberFormatComma($dataIntegrasiIspeks->sum('jumlah_penyata_pemungut')) }}</td>
                </tr>

            </table>

        </div>

</body>
</html>