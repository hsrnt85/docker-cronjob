<html>
    <head>
        <link href="assets/css/document/mail-report.css" rel="stylesheet" type="text/css">
    </head>
    
    <body>
        <div ><label >From : {{ $maklumat_agensi->email }}</label></div>
        <div ><label >Date : {{ currentDateTimeZone() }} </label></div>
        <div ><label >Subject : {{ $nama_sistem_short }} - {{ $email_subject }}</label></div>
        <div ><label >To : {{ $email_ispeks }} </label></div>
        {{-- {{dd($dataIntegrasiIspeks)}}  --}}
        <div >
            <p><b>{{ $nama_sistem }}<br/>{{ $email_subject }}</b></p>
            <b>Tarikh :</b> {{ currentDateSys('d/m/Y') }}
            <br/><b>Jumlah Fail Penyata Pemungut Dihantar :</b> {{ $dataIntegrasiIspeks->sum('bil_penyata_pemungut') }}
            <br/><b>Jumlah Keseluruhan Amaun Penyata Pemungut (RM) :</b> {{ numberFormatComma($dataIntegrasiIspeks->sum('jumlah_penyata_pemungut')) }}<p>

            @foreach($dataIntegrasiIspeks as $data)

                @php $bil_fail = str_pad($loop -> iteration, 2, "0", STR_PAD_LEFT); @endphp
                <table style="width:100%;" >
                    <tr>
                        <th colspan="2" class="left no-border">FAIL <br/> CARA BAYAR</th>
                        <th colspan="4" class="left no-border">: #{{ $bil_fail }} <br/> : {{ $data['jenis_bayaran'] }}</th>
                        <th colspan="4" class="right no-border">NAMA FAIL <br/> JUMLAH PENYATA PEMUNGUT <br/> JUMLAH AMUAN PENYATA PEMUNGUT</th>
                        <th colspan="3" class="left no-border">: {{ $data['nama_fail'] }} <br/> : {{ $data['bil_penyata_pemungut'] }} <br/> : RM {{ numberFormatComma($data['jumlah_penyata_pemungut']) }}</th>
                    </tr>
                </table>
                Senarai Penyata Pemungut dalam Fail #{{ $bil_fail }}
                <table style="width:100%;">         
                    {{-- <tr>
                        <th style="width:5%;">BIL</th>
                        <th style="width:6%;">KOD JAB</th>
                        <th style="width:15%;">PERIHAL JAB</th>
                        <th style="width:6%;">KOD PTJ</th>
                        <th style="width:15%;">PERIHAL PTJ</th>
                        <th style="width:10%;">NO. <br/>PENYATA<br/>PEMUNGUT</th>
                        <th style="width:6%;">AMAUN<br/>PENYATA<br/>PEMUNGUT (RM)</th>  
                        <th style="width:8%;">TARIKH<br/>PENYATA<br/>PEMUNGUT</th>
                        <th style="width:8%;">TARIKH<br/>PUNGUTAN<br/>DARI</th>
                        <th style="width:8%;">TARIKH<br/>PUNGUTAN<br/>HINGGA</th>
                        <th style="width:8%;">TARIKH<br/>SEDIA</th>
                        <th style="width:8%;">TARIKH<br/>SEMAK</th>
                        <th style="width:8%;">TARIKH<br/>LULUS</th>
                    </tr> --}}
                    <tr>
                        <th style="width:3%;">BIL</th>
                        <th style="width:6%;">KOD JAB</th>
                        <th style="width:15%;">PERIHAL JAB</th>
                        <th style="width:6%;">KOD PTJ</th>
                        <th style="width:15%;">PERIHAL PTJ</th>
                        <th style="width:7%;">NO. PENYATA PEMUNGUT</th>
                        <th style="width:6%;">AMAUN PENYATA PEMUNGUT (RM)</th>  
                        <th style="width:7%;">TARIKH PENYATA PEMUNGUT</th>
                        <th style="width:7%;">TARIKH PUNGUTAN DARI</th>
                        <th style="width:7%;">TARIKH PUNGUTAN HINGGA</th>
                        <th style="width:7%;">TARIKH SEDIA</th>
                        <th style="width:7%;">TARIKH SEMAK</th>
                        <th style="width:7%;">TARIKH LULUS</th>
                    </tr>
                
                    @foreach($data['senarai_penyata_pemungut'] as $dataPP)
                        <tr>
                            <td class="center">{{ $loop -> iteration }}</td>                                        
                            <td class="center">{{ $dataPP['kod_jabatan'] }}</td>                            
                            <td class="center">{{ $dataPP['nama_jabatan'] }}</td>
                            <td class="center">{{ $dataPP['kod_ptj'] }}</td>                            
                            <td class="center">{{ $dataPP['nama_ptj'] }}</td>
                            <td class="center">{{ $dataPP['no_penyata_pemungut'] }}</td>
                            <td class="right">{{ numberFormatComma($dataPP['amaun_penyata_pemungut']) }}</td>                          
                            <td class="center">{{ $dataPP['tarikh_penyata_pemungut'] }}</td>
                            <td class="center">{{ $dataPP['tarikh_pungutan_dari'] }}</td>
                            <td class="center">{{ $dataPP['tarikh_pungutan_hingga'] }}</td>
                            <td class="center">{{ $dataPP['tarikh_sedia'] }}</td>
                            <td class="center">{{ $dataPP['tarikh_semak'] }}</td>
                            <td class="center">{{ $dataPP['tarikh_lulus'] }}</td>
                        </tr>
                    @endforeach 
                    
                </table>
                <br/>
                     
            @endforeach 
           
        </div>

    </body>

</html>