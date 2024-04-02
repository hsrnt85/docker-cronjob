@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body pt-4">
                <h4 class="card-title mb-3">Maklumat Pengguna</h4>
                <div class="border-top row">

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td scope="row" colspan="2" class="text-center">
                                        <div class="col-sm-2 p-4 mb-3">
                                            <img src="{{ isset(Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('/assets/images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Nama</th>
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Jawatan</th>
                                    <td>{{ Auth::user()->position->position_name }} - {{ Auth::user()->position_grade_code?->grade_type }} {{ Auth::user()->position_grade->grade_no }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Peranan</th>
                                    <td>{{ Auth::user()->roles->name ?? ''}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Emel</th>
                                    <td>{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Jabatan/Agensi Bertugas</th>
                                    <td>{{ Auth::user()->office->organization->name ?? '' }} </td>
                                </tr>
                                <tr>
                                    <th scope="row">Daerah</th>
                                    <td>{{ Auth::user()->office->district->district_name ?? '' }} </td>
                                </tr>
                                <tr>
                                    <th scope="row">Jenis Perkhidmatan</th>
                                    <td>{{ Auth::user()->services_type->services_type ?? '' }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
