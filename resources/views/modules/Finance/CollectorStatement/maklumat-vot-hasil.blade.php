<div class="mb-3 row">
    <label class="col-md-2" >Senarai Vot Hasil</label>
    <div class="col-md-7">                        
        <p id="msg-vot-hasil" class="text-danger"></p>
        <div class="table-responsive">
            <table class="table table-bordered table-striped listDatatableVotAkaun">
                <thead class="bg-primary bg-gradient text-white">
                    <tr >
                        <th style="width:6%;" class="text-center">Bil.</th>
                        <th style="width:64%;" class="text-center">Kod Hasil</th>
                        <th style="width:30%;" class="text-center">Jumlah Terimaan (RM)</th>
                    </tr>
                </thead>
                <tbody >

                </tbody>
            </table>
            <input type="hidden" id="jumlah_kutipan" name="jumlah_kutipan">
        </div>
        <div class="spinner-wrapper"></div>
    </div>
</div>
@section('script')
<script src="{{ URL::asset('assets/js/pages/CollectorStatement/collectorStatement.js')}}"></script>
{{-- <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script> --}}
@endsection
