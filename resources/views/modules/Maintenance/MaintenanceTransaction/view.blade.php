@extends('layouts.master')

@section('content')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body m-2">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1)}}</h4></div>

                <h4 class="card-title text-primary mt-2 mb-3">Maklumat Aduan Kerosakan</h4>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No. Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->ref_no ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Pengadu</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->user->name ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No. Telefon</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->user->phone_no_hp ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Tarikh Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->complaint_date-> format("d/m/Y") ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->quarters->category->name ??''}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Alamat Kuarters</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <p class="col-md-9 col-form-label">{{ $complaint->quarters->unit_no.',' ??''}} {{ $complaint->quarters->address_1.',' ??''}} {{ $complaint->quarters->address_2.',' ??''}} {{ $complaint->quarters->address_3 ??''}}</p>
                    </div>

                  <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Senarai Kerosakan Inventori</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <div id="datatable_wrapper">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table  class="table table-bordered dt-responsive wrap w-100" role="grid" >
                                            <thead class="bg-primary bg-gradient text-white">
                                                <tr role="row">
                                                    <th class="text-center" width="5%">Bil.</th>
                                                    <th class="text-center" width="30%">Inventori</th>
                                                    <th class="text-center" width="30%">Butiran Kerosakan</th>
                                                    <th class="text-center" width="15%">Gambar Aduan</th>
                                                    <th class="text-center" width="20%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($complaintInventory -> isEmpty())
                                                    <tr class="odd">
                                                        <td class="text-center" colspan="5">Tiada Rekod</td>
                                                    </tr>
                                                @else
                                                    @foreach($complaintInventory as $c_inventory)
                                                        <tr class="odd">
                                                            <td class="text-center" tabindex="0">{{$loop->iteration}}.</td>
                                                            <td class="text-center">{{$c_inventory->inventory->name}}</td>
                                                            <td class="text-center">{{$c_inventory->description}}</td>
                                                            <td class="text-center">
                                                                <a onclick="showInventoryDamageList({{ $c_inventory->id }});" data-route="{{ route('maintenanceTransaction.ajaxGetComplaintInventoryAttachmentList') }}" id="btn-show-inventory-damage"  data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center" >{{$complaint->status->complaint_status}} </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Senarai Kerosakan Lain-lain</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <div id="datatable_wrapper">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table  class="table table-bordered dt-responsive wrap w-100" role="grid" >
                                            <thead class="bg-primary bg-gradient text-white">
                                                <tr role="row">
                                                    <th class="text-center" width="5%">Bil.</th>
                                                    <th class="text-center" width="60%">Butiran Kerosakan</th>
                                                    <th class="text-center" width="15%">Gambar Aduan</th>
                                                    <th class="text-center" width="20%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($complaintOthers -> isEmpty())
                                                    <tr class="odd">
                                                        <td class="text-center" colspan="5">Tiada Rekod</td>
                                                    </tr>
                                                @else
                                                    @foreach($complaintOthers as $c_others)
                                                        <tr class="odd">
                                                            <td class="text-center" tabindex="0">{{$loop->iteration}}.</td>
                                                            <td class="text-center">{{$c_others->description}}</td>
                                                            <td class="text-center">
                                                                <a onclick="showComplaintOthersList({{ $c_others->id }});" data-route="{{ route('maintenanceTransaction.ajaxGetComplaintOthersAttachmentList') }}"  id="btn-show-complaint-others" data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center" >{{$complaint->status->complaint_status}} </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Transaksi Penyelenggaraan</h4>

                    <div class="mb-3 row">
                        <label for="start_date" class="col-md-2 col-form-label">Tarikh Selenggara</label>
                        <div class="col-md-1 col-form-label mt-1">:</div>
                        <div class="col-md-4">
                            {{convertDateSys($maintenanceTransaction->maintenance_date)}}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="monitoring_officer_id" class="col-md-2 col-form-label">Pegawai Pemantau</label>
                        <div class="col-md-1 col-form-label mt-1">:</div>
                        <div class="col-md-4">
                            {{$maintenanceTransaction->officer->user->name}}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label mt-1">Status Penyelenggaraan</label>
                        <div class="col-md-1 col-form-label mt-1">:</div>
                        <div class="col-md-4 col-form-label">
                            {{$maintenanceTransaction->status->status}}
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label class="col-md-2 col-form-label">Gambar Penyelenggaraan </label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4 col-form-label">
                            <a href="#maintenancePict" data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon">Lihat Gambar
                                <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                            </a>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Catatan </label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4 col-form-label">
                            {{$maintenanceTransaction->remarks}}
                        </div>
                    </div>

                    @if( ! $maintenanceTransactionHistory->isEmpty() )
                        <hr>
                        <div class="mb-3 row">
                            <h4 class="card-title text-primary mt-2 mb-3">Sejarah Transaksi Penyelenggaraan</h4>
                            <label class="col-md-2 col-form-label">Sejarah Transaksi Penyelenggaraan </label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-9">
                                <table class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr>
                                            <th class="text-center">Bil.</th>
                                            <th class="text-center">Tarikh</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Catatan</th>
                                            <th class="text-center">Gambar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($maintenanceTransactionHistory as $history)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle">{{ convertDateSys($history->maintenance_date) }} </td>
                                            <td class="text-center align-middle">{{ $history->status->status ?? '' }} </td>
                                            <td class="text-center align-middle">{{ $history->remarks  }} </td>
                                            <td class="text-center align-middle">
                                                <a onclick="showImgMaintenance({{$history->id}});" data-route="{{ route('maintenanceTransaction.ajaxGetMaintenanceTransactionAttachment') }}" id="btn-show-maintenance-image"  data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon">
                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('maintenanceTransaction.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>

                {{------------------------------------------------------------- MODAL GAMBAR PENYELENGGARAAN -------------------------------------------------------------------------}}
                <div class="modal fade" id="maintenancePict" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gambar Penyelenggaraan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body" style=" margin: 0;">
                                @if ($maintenanceAttachment->isEmpty() || $maintenanceAttachment == null )
                                    <p id="clickingText1">Tiada gambar dijumpai.</p>
                                @else
                                    <p id="clickingText1">Sila klik gambar di bawah:</p>
                                @endif

                                <div class="container" >
                                    <img id="expandedImg1" class="mb-4" >
                                </div>
                                <div  style="text-align: center;">
                                    @if ($maintenanceAttachment->isEmpty() || $maintenanceAttachment == null)
                                        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                    @else
                                        @foreach($maintenanceAttachment as $attachment)
                                            <img src="{{ getCdn().'/'.$attachment->path_document }}" class="mb-1" width= "50px" height="50px"  onclick="showImagesMaintenance(this, {{ $attachment->id }});" style="border: 1px solid black"  >
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-------------------------------------------------------MODAL GAMBAR SEJARAH TRANSAKSI PENYELENGGARAAN ----------------------------------------------------------------}}

                <div class="modal fade" id="view-image-maintenance-transaction" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gambar Penyelenggaraan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body" id="maintenance-body" style=" margin: 0;">
                                <!--  modal-maintenance-transaction-attachment -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-----------------------------------------------------------MODAL GAMBAR ADUAN ---------------------------------------------------------------------------------}}

                <!-- Modal Kerosakan Inventori -->
                <div class="modal fade"  id="view-complaint-inventory-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Inventori</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body"  id="modal-body" style=" margin: 0;">
                                {{-- modal-complaint-inventory-attachment --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Aduan Lain2 -->
                <div class="modal fade" id="view-complaint-others-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Lain-lain</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="modal-body" id="complaint-others-modal-body" style=" margin: 0;">
                                {{-- modal-complaint-others-attachment --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/MaintenanceTransaction/maintenanceTransaction.js')}}"></script>
@endsection
