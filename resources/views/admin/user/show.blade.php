@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Customers</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop
@section('content')
<div class="row">
 
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">
                                            <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="user-image">
                                                            @if($data->is_provider == 1)
                                                            <img src="{{ $data->photo ? asset($data->photo):asset('assets/images/noimage.png')}}" alt="No Image">
                                                            @else
                                                            <img src="{{ $data->photo ? asset('assets/images/users/'.$data->photo):asset('assets/images/noimage.png')}}" alt="No Image">                                            
                                                            @endif
                                                        <!--<a href="javascript:;" class="mybtn1 send" data-email="{{ $data->email }}" data-toggle="modal" data-target="#vendorform">Send Message</a>
                                                        --></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="table-responsive show-table">
                                                        <table class="table">
                                                        <tr>
                                                            <th>ID#</th>
                                                            <td>{{$data->id}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Name</th>
                                                            <td>{{$data->first_name}} {{$data->last_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Email</th>
                                                            <td>{{$data->email}}</td>
                                                          </tr>
                                                            <tr>
                                                                <th>Phone</th>
                                                                <td>{{$data->phone}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Address</th>
                                                                <td>{{$data->address}}</td>
                                                            </tr> 
                                                        </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="table-responsive show-table">
                                                    <table class="table">
                                                            
                                                            @if($data->city != null)
                                                            <tr>
                                                                <th>City</th>
                                                                <td>{{$data->city}}</td>
                                                            </tr>
                                                            @endif
                                                            @if($data->fax != null)
                                                            <tr>
                                                                <th>Fax</th>
                                                                <td>{{$data->fax}}</td>
                                                            </tr>
                                                            @endif
                                                            @if($data->zip != null)
                                                            <tr>
                                                                <th>Zip Code</th>
                                                                <td>{{$data->zip}}</td>
                                                            </tr>
                                                            @endif
                                                            <tr>
                                                                <th>Joined</th>
                                                                <td>{{$data->created_at->diffForHumans()}}</td>
                                                            </tr>
                                                        </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-table-wrap">
                                                <div class="order-details-table">
                                                    <div class="mr-table">
                                                        <h4 class="title">Products Ordered</h4>
                                                        <div class="table-responsive">
                                                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Order ID</th>
                                                                            <th>Purchase Date</th>
                                                                            <th>Amount</th>
                                                                            <th>Status</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($data->orders as $order)
                                                                        <tr>
                                                                            <td><a href="{{ route('admin.invoice',$order->id) }}">{{sprintf("%'.08d", $order->id)}}</a></td>
                                                                            <td>{{ date('Y-m-d h:i:s a',strtotime($order->created_at)) }}</td>
                                                                            <td>{{ $order->currency_sign . round($order->pay_amount * $order->currency_value , 2) }}</td>
                                                                            <td>{{ $order->status }}</td>
                                                                            <td>
                                                                                <a href=" {{ route('admin-order-show',$order->id) }}" class="view-details">
                                                                                    <i class="fas fa-check"></i>Details
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                        
                                                                    </tbody>
                                                                </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

{{-- MESSAGE MODAL --}}

    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel">Send Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <div class="modal-body">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-form">
                                <form id="emailreply1">
                                    {{csrf_field()}}
                                    <ul>
                                        <li>
                                            <input type="email" class="input-field eml-val" id="eml1" name="to" placeholder="Email *" value="" required="">
                                        </li>
                                        <li>
                                            <input type="text" class="input-field" id="subj1" name="subject" placeholder="Subject *" required="">
                                        </li>
                                        <li>
                                            <textarea class="input-field textarea" name="message" id="msg1" placeholder="Your Message *" required=""></textarea>
                                        </li>
                                    </ul>
                                    <button class="submit-btn" id="emlsub1" type="submit">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>


{{-- MESSAGE MODAL ENDS --}}

@endsection

@section('scripts')

<script type="text/javascript">
$('#example2').dataTable( {
  "ordering": false,
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
      'responsive'  : true
} );
</script>


@endsection