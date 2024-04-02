<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengesahan</title>
    <style>
        p{
            margin: 0px;
        }

        .text-center{
            text-align : center;
        }

        .text-justify{
            text-align : justify;
        }

        .mr-20px{
            margin-right : 20px;
        }

        .ml-400px{
            margin-left : 400px;
        }

        .ml-20px{
            margin-left : 20px;
        }

        .mb-20px{
            margin-bottom : 20px;
        }

        .mb-70px{
            margin-bottom : 70px;
        }

        .align-top{
            vertical-align:top;
        }
    </style>
</head>
<body>
    <div class="text-center mb-20px"><strong>SURAT TAWARAN MENDUDUKI KUARTERS</strong></div>
    <div class="row">
        <div class="ml-400px">
            <p class="">Ruj Kami  :  {{ $letter_ref_no }} </p>
            <p class="">Tarikh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $letter_date }}</p>
        </div>
    </div>
    <div class="row">
        <div class="mb-20px">
            <p>{{ $current_address->address_1 }}</p>
            <p>{{ $current_address->address_2 }}</p>
            <p>{{ $current_address->address_3 }}</p>
        </div>
    </div>
    <div class="row">
        <div >
            <p class="mb-20px">Tuan/Puan</p>
            <p class="mb-20px"><strong>TAWARAN MENDUDUKI KUARTERS  KERAJAAN DI BAWAH KAWALAN KERJA RAYA MALAYSIA DI {{ $quarters_category }}</strong></p>
            <p class="mb-20px">Adalah saya dengan hormatnya diarah merujuk kepada perkara di atas.</p>
            <p class="mb-20px text-justify">2. Sukacita Dimaklumkan bahawa Jawatankuasa Perumahan Daerah Johor Bahru dalam Mesyuarat Jawatankuasa Perumahan Daerah Johor Bahru Bil 1/2021 pada 25 Mac 2021 telah bersetuju meluluskan permohonan untuk menduduki kuarters bagi penama untuk menduduki kuarters.</p>
            <p class="mb-20px text-justify">3. Pihak penama perlu mematuhi syarat-syarat seperti berikut:</p>
            <table>
                <tr>
                    <td class="align-top"><span class="ml-20px mr-20px">i.</span></td>
                    <td><p class="ml-20px text-justify">Kadar sewa adalah sebanyak RM {{ $rental_rate }} sebulan melalui potongan gaji (25% dan 50% BSH)</p></td>
                </tr>
                <tr>
                    <td class="align-top"><span class="ml-20px mr-20px">ii.</span></td>
                    <td>
                        <p class="ml-20px text-justify">Sebarang kerja pembakaran, pembersihan, kerja penamaan yang melibatkan kerja tanah hendaklah dirujuk dahulu kepada Ketua Setiausaha / Ketua Pengarah {{ $manager }} dan lain-lain Jabatan Kerajaan yang berkaitan.</p>
                    </td>
                </tr>
                <tr>
                    <td class="align-top"><span class="ml-20px mr-20px">iii.</span></td>
                    <td>
                        <p class="ml-20px text-justify">Pihak Jabatan boleh menamatkan perjanjian sewa ini atas apa tujuan sekali pun dengan memberi tiga puluh (30) hari notis tanpa dibayar apa-apa pampasan atau gantirugi;</p>
                    </td>
                </tr>
                <tr>
                    <td class="align-top"><span class="ml-20px mr-20px">iv.</span></td>
                    <td>
                        <p class="ml-20px text-justify">Penghuni tidak dibenarkan menyewa kecil kuarters ini kepada mana-mana pihak ketiga; dan</p>
                    </td>
                </tr>
                <tr>
                    <td class="align-top"><span class="ml-20px mr-20px">v.</span></td>
                    <td>
                        <p class="ml-20px mb-20px text-justify">Lain-lain syarat seperti yang dinyatakan di dalam perjanjian yang akan ditandatangani kelak.</p>
                    </td>
                </tr>
            </table>
            
            <p class="mb-20px text-justify">4. Sehubungan dengan itu, pihak penama perlu kemukakan maklumbalas sepertimana surat jawapan yang dilampirkan bersama surat ini dalam tempoh Empat Belas (14) hari bekerja daripada  tarikh surat ini iaitu sebelum atau pada {{ $final_confirmation_date }}.</p>
            <p class="mb-20px text-justify">5. Kegagalan pihak penama memberi maklumbalas di dalam tempoh yang diberikan akan menyebabkan kelulusan ini ditarik balik dan dibatalkan.</p>
            <p class="mb-20px">Kerjasama daripada pihak penama kami ucapkan ribuan terima kasih.</p>
            <p class="mb-20px">Sekian, terima kasih.</p>
            <p><strong>“BERKHIDMAT UNTUK NEGARA”</strong></p>
            <p class="mb-70px">Saya yang menjalankan amanah,</p>
            <p><strong>(DATO’ HAJI MOHD NOH BIN IBRAHIM)</strong></p>
            <p>Pengerusi Jawatankuasa Perumahan Daerah Johor Bahru</p>
            <p>Merangkap Timbalan Setiausaha Kerajaan (Pengurusan)</p>
            <p>Pejabat Setiausaha Kerajaan Johor</p>
        </div>
    </div>
</body>
</html>


<!-- <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
     
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="right">Ruj Kami  :  SUKJ.BKP </p>
                                <p class="right1">Tarikh &nbsp;&nbsp;: </p>
                            </div>
                        </div>
                </div>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label">Senarai Edaran Sebagaimana di&nbsp;<strong>Lampiran</strong></p> 
                            </div>
                        </div>
                </div>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label">Tuan/Puan,</p>
                            </div>
                        </div>
                </div>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label"><strong>MESYUARAT JAWATANKUASA PERUMAHAN DAERAH JOHOR BAHRU </strong></p>
                            </div>
                        </div>
                </div>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label">Dengan segala hormatnya saya merujuk perkara di atas. </p>
                            </div>
                        </div>
                </div>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah dimaklumkan bahawa mesyuarat tersebut akan diadakan sebagaimana ketetapan berikut :-  </p>
                            </div>
                        </div>
                </div>

                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="date"> <strong>Tarikh</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hello  </p>
                            </div>
                        </div>
                </div>

                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="date"> <strong>Masa</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </p>
                            </div>
                        </div>
                </div>

                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="date"> <strong>Tempat</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
                            </div>
                        </div>
                </div>

                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="date"> <strong>Pengerusi</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </p>
                            </div>
                        </div>
                </div>

                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sehubungan dengan itu, tuan/puan dipersilakan hadir di mesyuarat tersebut.</p>
                            </div>
                        </div>
                </div>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label">Sekian,terima kasih. </p>
                            </div>
                        </div>
                </div>
                <br>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label"><strong>"BERKHIDMAT UNTUK NEGARA"</strong></p>
                            </div>
                        </div>
                </div>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label">Saya yang menjalankan amanah,</p>
                            </div>
                        </div>
                </div>
                <br>
                <br>
                <br>
                <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="mb-3 row">
                                <p class="col-form-label"><strong>(RASIS BIN RUSTAM)<strong></p><p><strong>Penolong Setiausaha </strong></p><p><strong>Bahagian Khidmat Pengurusan</strong></p>
                                <p><strong> Setiausaha Kerajaan Johor</strong></p>


                            </div>
                        </div>
                </div>
    <div class="col-md-3"></div>
    <div class="clearfix"></div>
</div> -->