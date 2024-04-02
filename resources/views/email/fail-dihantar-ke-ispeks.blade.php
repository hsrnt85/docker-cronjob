
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="mb-3 row">
                        <label for="from" class="col-md-2 col-form-label">From : {{ $maklumat_agensi->email }}</label>
                    </div>
                    <div class="mb-3 row">
                        <label for="from" class="col-md-2 col-form-label">Date : {{currentDateTimeZone()}} </label>
                    </div>
                    <div class="mb-3 row">
                        <label for="from" class="col-md-2 col-form-label">Subject : {{$email_subject}}</label>
                    </div>
                    <div class="mb-3 row">
                        <label for="from" class="col-md-2 col-form-label">To : {{$email_ispeks}} </label>
                    </div>
                </div><br>

                <div class="row">
                    <div class="text-justify">

                        <p>Salam Negaraku Malaysia,</p>
                        <p>Tuan / Puan,</p>
                        <p>Sukacita dimaklumkan bahawa fail Tuan/Puan telah dihantar ke iSPEKS Integration Hub dan sedia untuk dimuat turun. </p>
                        <p>2. Berikut adalah maklumat fail tersebut: - </p>

                        <table>
                            <tr>
                                <td width="20%"></td>
                                <td class="align-top"><span class="ml-4 mr-2">Nama Fail : {{ $maklumat_fail['nama_fail'] }}</span></td>
                            </tr
                            <tr>
                                <td width="20%"></td>
                                <td class="align-top"><span class="ml-4 mr-2">Tarikh : {{ convertDateSys($maklumat_fail['diproses_pada']) }}</span></td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td class="align-top"><span class="ml-4 mr-2">Agensi : {{ $maklumat_agensi->department_name }} </span></td>
                            </tr><br>
                        </table>

                        <p class="mt-3">Sekian, terima kasih.</p>
                        <p>{{ $maklumat_agensi->department_name }}</p><br>
                        <p class="mt-3"><b><i>Peringatan:<i></b> Ini adalah cetakan komputer. Tiada tandatangan dan maklum balas diperlukan.</p>

                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
 <!-- end row -->

