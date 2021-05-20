@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
        </div><!-- /.col -->
    </div><!-- /.row -->
@stop
@section('content')
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif
    @if((session())->has('failures'))
        <table class="table table-danger">
            <tr>
                <th>Row</th>
                <th>Product</th>
                <th>Attribute</th>
                <th>Errors</th>
                <th>Value</th>
            </tr>
            @foreach (session()->get('failures') as $validation)
                <tr>
                    <td>{{ $validation->row()   }}</td>
                    <td>{{ $validation->values()['product_name'] }}</td>
                    <td>{{ $validation->attribute() }}</td>
                    <td>
                        <ul>
                            @foreach ($validation->errors() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $validation->values()['product_name'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif
    @if(session()->has('success') && session()->has('success') > 0)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i>Import Success</h5>
            {{ session()->get('success') }} products imported
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                {{--<div class="overlay" style="display: none;">--}}
                    {{--<i class="fas fa-2x fa-sync-alt fa-spin"></i>--}}
                {{--</div>--}}
                {{ Form::open(array('url' => 'admin/download-out-of-stock-report', 'enctype' => 'multipart/form-data')) }}

                <div class="card-header">
                    <h3 class="card-title">&nbsp;</h3>
                    <!-- tools box -->
                    <?php $user = Auth::user(); ?>
                    @if(Helper::get_guard() == "admin")
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success btn-sm"> <i class="fa fa-download" aria-hidden="true"></i> Download</button>
                            </div>
                        </div>
                @endif
                <!-- /. tools -->
                </div>
                <div class="card-body pad">
                    @if($guard_name == 'admin')
                    <div class="row input-daterange" id="report_date">
                            <div class="form-group col-4">
                                <label>Filter By Date</label>
                              
                                <input type="text" class="form-control" placeholder="Starts At" name="start_date" id="start_date" autocomplete="off">
                            
                            </div>
                        </div>
                    @endif
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="out-of-stock-products-table">
                        <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Name</th>
                            <th>Sku</th>
                            <th>Unit Price</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@stop
@section('js')
<link href="{{ asset('css/bootstrap-datepicker.css')}}" id="theme" rel="stylesheet">
<script src="{{ asset('js/bootstrap-datepicker.min.js')}}"></script>
    <?php
    $guardname = Helper::get_guard();
    $url = url($guardname.'/report-out-of-stock/dt');
    ?>
    <script type='text/javascript'>
        $("#report_date").datepicker({
                toggleActive: !0
            });
        $(function () {
            var oTable = $('#out-of-stock-products-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                //autoWidth: false,
                ajax: {
                    url: "{!! $url !!}",
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: function ( d ) {
                        if( $('#start_date').val() != undefined) {
                            d.start_date = $('#start_date').val();
                        }
                    }
                },
                columns: [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'product.name', name: 'product.name'},
                    {data: 'product.sku', name: 'product.sku'},
                    {data: 'unit_price', name: 'unit_price'},

                ]
            });
            $(document).on("change", "#report_date", function() {
                oTable.ajax.reload();
            });

        });
    </script>
@stop
