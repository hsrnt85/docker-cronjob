@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                @if($complaint->complaint_type == 1)
                    <p class="card-title text-primary mt-2 mb-3">Maklumat Aduan (Kerosakan)</p>
                @else
                    <p class="card-title text-primary mt-2 mb-3">Maklumat Aduan (Awam)</p>
                @endif

                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $complaint->id }}">

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">No. Aduan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9" >
                            <p class="col-form-label">{{ $complaint->ref_no ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Nama Pengadu</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->user->name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">No Telefon</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->user->phone_no_hp ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Tarikh Aduan</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->complaint_date-> format("d/m/Y") ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->quarters->category->name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Alamat Kuarters</label>
                    <div class="col-md-1 col-form-label">:</div>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $complaint->quarters->unit_no.',' ??''}} {{ $complaint->quarters->address_1.',' ??''}} {{ $complaint->quarters->address_2.',' ??''}} {{ $complaint->quarters->address_3 ??''}}</p>
                    </div>
                </div>

                @if($complaint->complaint_type == 1)
                   {{--------------------------------------------------------DATA ADUAN KEROSAKAN-----------------------------------------------------------------------------------------}}
                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Senarai Inventori</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-8 col-form-label">
                            <table class="table table-bordered">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr class="text-center">
                                        <th  class="text-center" width="8%">Bil</th>
                                        <th  class="text-center" width="22%">Inventori</th>
                                        <th  class="text-center" width="35%">Butiran Kerosakan</th>
                                        <th  class="text-center" width="15%">Gambar Aduan</th>
                                        <th  class="text-center" width="20%" >Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($complaintInventoryAll->count() != 0)
                                        @foreach ($complaintInventoryAll as  $complaint_inventory)
                                        <tr class="odd">
                                            <th scope="row" class="text-center">
                                                <input type="hidden" name="complaint_inventory_id"  value="{{$complaint_inventory->id ??''}}" >
                                                {{$loop->iteration}}.
                                            </th>
                                            <td class="text-center"> {{$complaint_inventory->inventory->name}}</td>
                                            <td class="text-center">{{$complaint_inventory->description}}</td>
                                            <td class="text-center">
                                                <a onclick="showInventoryDamageList({{ $complaint_inventory->id }});" data-route="{{ route('complaintMonitoring.ajaxGetComplaintInventoryAttachmentList') }}" id="btn-show-inventory-damage" class="btn btn-outline-primary tooltip-icon">
                                                <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">{{ ($complaint_inventory->flag_action == 1) ? 'Selenggara' : 'Ditolak' }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="name" class="col-md-2 col-form-label">Butiran Aduan (lain-lain)</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-8 col-form-label" >
                            <table class="table table-bordered" id="table-other-complaint" data-list="{{$complaint_others->count()}}">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr>
                                        <th class="text-center" width="8%">Bil</th>
                                        <th class="text-center" width="57%">Butiran Aduan</th>
                                        <th class="text-center" width="15%">Gambar Aduan</th>
                                        <th class="text-center" width="20%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($complaint_others->count() != 0)
                                        @foreach($complaint_others as $complaintOthers)
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    <input type="hidden" name="complaint_others_id"  value="{{$complaintOthers->id}}" >
                                                    {{$loop->iteration}}.
                                                </th>
                                                <td class="text-center">{{$complaintOthers->description}}</td>
                                                <td class="text-center">
                                                    <a onclick="showComplaintOthersList({{ $complaintOthers->id }});" data-route="{{ route('complaintMonitoring.ajaxGetComplaintOthersAttachmentList') }}"  id="btn-show-complaint-others" class="btn btn-outline-primary tooltip-icon">
                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_image') }}"></i>
                                                    </a>
                                                </td>
                                                <td class="text-center">{{($complaintOthers->flag_action == 1) ? 'Selenggara' : 'Ditolak'}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3 row">
                        <h4 class="card-title text-primary mt-2 mb-3">Maklumat Temujanji Aduan</h4>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tarikh Temujanji</label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ convertDateSys($complaint_appointment_latest->appointment_date) ?? ''}}</p>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Masa Temujanji</label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $complaint_appointment_latest->appointment_time->format("h:i A") ?? ''}}</p>
                            </div>
                        </div>
                    </div>

                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pemantauan Aduan</h4>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Butiran Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4">
                            <p class="col-form-label">{{ strtoupper($complaint_appointment_latest?->monitoring_remarks) ?? '-'}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Gambar Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9 col-form-label">
                            <a href="#monitoringPictureKerosakan" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Status Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint->status->complaint_status}}</p>
                        </div>
                    </div>

                @else
                    {{--------------------------------------------------------DATA ADUAN AWAM-----------------------------------------------------------------------------------------}}
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Butiran Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ strtoupper($complaint->complaint_description ?? '') }}</p>

                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Gambar Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9 col-form-label">
                            <a href="#complaintPicture" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>
                @endif

                {{-----------------------------PEMANTAUAN (PERTAMA)---------------------------------------}}
                @if($complaint_monitoring && $complaint_monitoring->monitoring_counter >= 1)
                    <hr><h4 class="card-title text-primary mt-3 mb-3">Maklumat Pemantauan Aduan</h4>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Butiran Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4">
                            <p class="col-form-label">{{ upperText($complaint_monitoring->monitoring_remarks) ?? ''}}</p>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Gambar Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9 col-form-label">
                            <a href="#monitoringPicture1" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>
                    @if($complaint_monitoring && $complaint_monitoring->monitoring_counter == 1)
                        <div class="mb-2 row">
                            <label class="col-md-2 col-form-label">Status Pemantauan Aduan</label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $complaint->status->complaint_status ??''}}</p>
                            </div>
                        </div>
                    @endif
                @endif

                {{-----------------------------PEMANTAUAN BERULANG (KEDUA)---------------------------------------}}
                @if($complaint_monitoring && $complaint_monitoring->monitoring_counter >= 2)
                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pemantauan Berulang (Kedua)</h4>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Butiran Pemantauan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4">
                            <p class="col-form-label">{{ strtoupper($complaint_monitoring->monitoring_remarks_repeat ?? '') }}</p>

                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Gambar Pemantauan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9 col-form-label">
                            <a href="#monitoringPicture2" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>

                    @if($complaint_monitoring && $complaint_monitoring->monitoring_counter == 2)
                        <div class="mb-2 row">
                            <label class="col-md-2 col-form-label">Status Pemantauan Aduan</label>
                            <div class="col-md-1 col-form-label">:</div>
                            <div class="col-md-9">
                                <p class="col-form-label">{{ $complaint_monitoring->status->monitoring_status ??''}}</p>
                            </div>
                        </div>
                    @endif
                @endif

                {{-----------------------------PEMANTAUAN BERULANG (KETIGA)---------------------------------------}}
                @if($complaint_monitoring && $complaint_monitoring->monitoring_counter == 3)
                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pemantauan Berulang (Akhir)</h4>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Butiran Pemantauan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-4">
                            <p class="col-form-label">{{ $complaint_monitoring->monitoring_remarks_final ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Gambar Pemantauan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9 col-form-label">
                            <a href="#monitoringPicture3" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Status Pemantauan Aduan</label>
                        <div class="col-md-1 col-form-label">:</div>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $complaint_monitoring->status->monitoring_status ??''}}</p>
                        </div>
                    </div>

                @endif
                <div class="mb-3 row">
                    <div class="col-sm-11 offset-sm-1">
                        <a href="{{ route('complaintMonitoring.index') . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>


                {{--------------------------------------------------------MODAL KEROSAKAN INVENTORI ------------------------------------------------------------------------------------------}}
                <div class="modal fade"  id="view-complaint-inventory-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Inventori</h5>
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body"  id="modal-body" style=" margin: 0;">
                                {{-- view-complaint-inventory-attachment --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{--------------------------------------------------------MODAL KEROSAKAN LAIN2------------------------------------------------------------------------------------------}}
                <div class="modal fade" id="view-complaint-others-attachment" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Kerosakan Lain-lain</h5>
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body" id="complaint-others-modal-body" style=" margin: 0;">
                                {{-- view-complaint-others-attachment --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{--------------------------------------------------------MODAL ADUAN AWAM-----------------------------------------------------------------------------------------}}
                 <div class="modal fade" id="complaintPicture" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Aduan Awam</h5>
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body">

                                @if ($complaint_attachment->isEmpty() || $complaint_attachment == null )
                                    <p id="clickingText_A">Tiada bukti aduan dijumpai.</p>
                                @else
                                    <p id="clickingText_A">Sila klik gambar di bawah:</p>
                                @endif

                                <div class="container" >
                                    <img id="expandedImg_A" class="mb-4" >
                                    <div id="imgtext_A"></div>
                                </div>
                                <div  style="text-align: center;">

                                    @if ($complaint_attachment->isEmpty() || $complaint_attachment == null)
                                        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                    @else
                                        @foreach($complaint_attachment as $attachment)
                                            <img src="{{getCdn().'/'.$attachment->path_document}}" class="mb-1" width= "50px" height="50px"  onclick="showImagesAwam(this);" style="border: 1px solid black"  >
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

               {{--------------------------------------------------------MODAL PEMANTAUAN ADUAN KEROSAKAN-----------------------------------------------------------------------------------------}}
                <div class="modal fade" id="monitoringPictureKerosakan" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gambar Pemantauan</h5>
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body">
                                @if ($complaint_appointment_attachment->isEmpty() || $complaint_appointment_attachment == null )
                                    <p id="clickingText_K">Tiada gambar pemantauan dijumpai.</p>
                                @else
                                    <p id="clickingText_K">Sila klik gambar di bawah:</p>
                                @endif

                                <div class="container" >
                                <img id="expandedImg_K" class="mb-4" >
                                    <div id="imgtext_K"></div>
                                </div>
                                <div  style="text-align: center;">
                                    @if ($complaint_appointment_attachment->isEmpty() || $complaint_appointment_attachment == null)
                                        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                    @else
                                        @foreach($complaint_appointment_attachment as $monitoring_attachment)
                                            <img src="{{getCdn().'/'.$monitoring_attachment->path_document}}" class="mb-1" width= "50px" height="50px"  onclick="showImagesMonitoringKerosakan(this);" style="border: 1px solid black"  >
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
                {{----------------------------------------------------------- MODAL BUKTI PEMANTAUAN PERTAMA ( ADUAN AWAM ) ----------------------------------------------------------------}}
                <div class="modal fade" id="monitoringPicture1" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gambar Pemantauan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body">
                                @if ($monitoring_attachment_1->isEmpty() || $monitoring_attachment_1 == null )
                                    <p id="clickingText_A1">Tiada gambar pemantauan dijumpai.</p>
                                @else
                                    <p id="clickingText_A1">Sila klik gambar di bawah:</p>
                                @endif
                                <div class="container">
                                    <img id="expandedImg_A1" class="mb-4" >
                                </div>
                                <div  style="text-align: center;">
                                    @if ($monitoring_attachment_1->isEmpty() || $monitoring_attachment_1 == null)
                                        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                    @else
                                        @foreach($monitoring_attachment_1 as $monitoring_attachment)
                                            <img src="{{getCdn().'/'.$monitoring_attachment->path_document}}" class="mb-1" width= "50px" height="50px"  onclick="showImagesMonitoringAwam1(this);" style="border: 1px solid black"  >
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

                 {{----------------------------------------------------------- MODAL BUKTI PEMANTAUAN KEDUA ( ADUAN AWAM ) ----------------------------------------------------------------}}
                 <div class="modal fade" id="monitoringPicture2" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gambar Pemantauan</h5>
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body">
                                @if ($monitoring_attachment_2->isEmpty() || $monitoring_attachment_2 == null )
                                    <p id="clickingText_A2">Tiada gambar pemantauan dijumpai.</p>
                                @else
                                    <p id="clickingText_A2">Sila klik gambar di bawah:</p>
                                @endif
                                <div class="container" >
                                <img id="expandedImg_A2" class="mb-4" >
                                    <div id="imgtext_A2"></div>
                                </div>
                                <div  style="text-align: center;">
                                    @if ($monitoring_attachment_2->isEmpty() || $monitoring_attachment_2 == null)
                                        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                    @else
                                        @foreach($monitoring_attachment_2 as $monitoring_attachment)
                                            <img src="{{getCdn().'/'.$monitoring_attachment->path_document}}" class="mb-1" width= "50px" height="50px"  onclick="showImagesMonitoringAwam2(this);" style="border: 1px solid black"  >
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

                 {{----------------------------------------------------------- MODAL BUKTI PEMANTAUAN KETIGA ( ADUAN AWAM ) ----------------------------------------------------------------}}
                 <div class="modal fade" id="monitoringPicture3" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gambar Pemantauan</h5>
                                <button type = "button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                            </div>
                            <div class="modal-body">
                                @if ($monitoring_attachment_3->isEmpty() || $monitoring_attachment_3 == null )
                                    <p id="clickingText_A3">Tiada gambar pemantauan dijumpai.</p>
                                @else
                                    <p id="clickingText_A3">Sila klik gambar di bawah:</p>
                                @endif
                                <div class="container" >
                                <img id="expandedImg_A3" class="mb-4" >
                                    <div id="imgtext_A3"></div>
                                </div>
                                <div  style="text-align: center;">
                                    @if ($monitoring_attachment_3->isEmpty() || $monitoring_attachment_3 == null)
                                        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
                                    @else
                                        @foreach($monitoring_attachment_3 as $monitoring_attachment)
                                            <img src="{{getCdn().'/'.$monitoring_attachment->path_document}}" class="mb-1" width= "50px" height="50px"  onclick="showImagesMonitoringAwam3(this);" style="border: 1px solid black"  >
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
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/ComplaintMonitoring/complaintMonitoring.js')}}"></script>
@endsection
