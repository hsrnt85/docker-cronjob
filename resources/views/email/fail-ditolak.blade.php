
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
                        <label for="from" class="col-md-2 col-form-label">To : {{ArrayToString($email_ispeks)}}</label>
                    </div>
                </div><br>

                <div class="row">
                    <div class="text-justify">

                        <p>Salam Negaraku Malaysia,</p>
                        <p>Tuan / Puan,</p>
                        <p>Dukacita dimaklumkan bahawa fail Tuan/Puan tidak diterima untuk proses selanjutnya. Mohon semak ralat
                            berikut: -</p>
                        <p class="mt-2"><b>Mesej Error:</b></p><span class="ml-2 mr-2">
                        <table>
                            <tr>
                                <td width="10%"></td>
                                <td class="align-top">

                                    @foreach($errorCounter as $key => $counter)
                                        @foreach($counter as $indexLine => $line)
                                            @if($indexLine == 0)
                                                {!! '[-] - '.$line['message'].'<br>'.$line['line'].'<br>'.$line['actual_data_msg'].'<br><br>' !!}
                                                {{-- @php $counter_0s = session()->put('line', $counter_0); @endphp --}}
                                            @else
                                                {!! '[-] - '.$line['message'].'<br>'.$line['line'].'<br>'.$line['actual_data_msg'] !!}
                                                {{-- @php $counter_s1 = session()->put('counter_1', $counter_1); @endphp --}}
                                            @endif
                                        @endforeach
                                    @endforeach<br></span>
                                </td>
                            </tr>
                        </table>
                        <p class="mt-4"><b>Data Error:</b></p>
                        <table>
                            <tr>
                                <td width="10%"></td>
                                <td class="align-top">  <span class="ml-3 mr-2">
                                    @foreach($errorContent as $key => $content)     {{-- ALL ERROR --}}
                                        @foreach($content as $indexLine => $line)   {{-- BY LINE --}}
                                            @foreach($line as $i => $data)          {{-- BY DATA IN EACH LINE --}}
                                                @if($data != null || $data != [])
                                                    @foreach($data as $k => $msg)   {{-- BY MESSAGE IN EACH DATA --}}

                                                        @php
                                                            $array = explode("|", $msg['lengthData']);
                                                            $first_element = $array[0];
                                                         @endphp

                                                        {{--[[tapi tak jadi]]Check if the current item has already been processed --}}
                                                        {{-- @if (isset($first_element) == 0 || isset($first_element) == 1 )
                                                            {!!$msg['lengthData'].'<br>'!!}
                                                            @continue; {{--Ignore the item and move to the next one --}}

                                                         {{--@else
                                                            {!!$msg['lengthData'].'<br>'!!}
                                                        @endif --}}
                                                        {!!$msg['lengthData'].'<br>'!!}
                                                        {!!'<b>Data ke</b> : '.$msg['numbData'].'<br>'!!}
                                                        {!!'<b>Mesej</b> : '.$msg['lengthFormat'].'<br><br>'!!}

                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    </span>
                                </td>
                            </tr>


                        </table>

                        <p>2. Berikut adalah maklumat fail tersebut: - </p>

                        <table>
                            <tr>
                                <td width="25%"></td>
                                <td class="align-top"><span class="ml-4 mr-2">Nama Fail : {{ $maklumat_fail->file_name_gpg }}</span></td>
                            </tr>
                            <tr>
                                <td width="25%"></td>
                                <td class="align-top"><span class="ml-4 mr-2">Tarikh : {{ convertDateSys($maklumat_fail->action_on) }}</span></td>
                            </tr>
                            <tr>
                                <td width="25%"></td>
                                <td class="align-top"><span class="ml-4 mr-2">Agensi : {{ $maklumat_agensi->department_name }} </span></td>
                            </tr><br>
                        </table>

                        <p>3. Pihak Tuan/Puan perlu mengambil tindakan selanjutnya.</p>

                        <p class="mt-3">Sekian, terima kasih.</p>
                        <p class="mt-3">{{ $maklumat_agensi->department_name }}</p><br>
                        <p class="mt-3"><b><i>Peringatan:<i></b> Ini adalah cetakan komputer. Tiada tandatangan dan maklum balas diperlukan.</p>
                            {{-- @dd($indexLine) --}}
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
 <!-- end row -->

