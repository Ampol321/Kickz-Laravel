@extends('layouts.tables')
@extends('layouts.app')
@section('content')
    {{-- Form Validation --}}
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    {{-- JQuery DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
                {{ session()->get('message') }}
            </div>
        @endif

        <center>
            <h1><b>Payments</b></h1>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                Stored Images</button>
            <button {{-- href="{{ url('/payment/create') }}" --}} id="create" type="button" data-bs-toggle="modal"
                class="btn btn-success btn-sm" data-bs-target="#paymentModal"> Add Payment </button>
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#importModal">
                Import Payments</button>
        </center></br>

        <div class="container">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                        {{-- @if (empty($paymentChart))
                            <div></div>
                        @else
                            <div style="padding:50px">
                                {!! $paymentChart->container() !!}
                                {!! $paymentChart->script() !!}
                            </div>
                        @endif --}}
                        <h4 style="text-align: center">Payment Option Count</h4>
                        <div class="container">
                            <canvas id="paymentChart" style="height: 500px; padding: 20px"></canvas>
                        </div>
                    </div>

                    <div class="col-md-8" style="padding:5px; border:2px solid #cecece;">
                        {{-- {{ $dataTable->table() }}
                        {{ $dataTable->scripts() }} --}}
                        <table id="paymentsTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Payment Image</th>
                                    <th>Payment Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for images-->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Payment Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        use App\Models\Payment;
                        $payments = payment::all();
                    @endphp

                    @foreach ($payments as $payment)
                        @php
                            $images = explode(',', $payment->payment_img);
                        @endphp
                        @foreach ($images as $item)
                            <img src="{{ $item }}" style="height:100px; width:100px">
                        @endforeach
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for CRUD-->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLongTitle">Payments</h5>
                    <button id="close" type="button" data-bs-toggle="modal" data-bs-target="#paymentModal"
                        class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="paymentForm" action="{{ url('payment') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <label>Image:</label></br>
                        <input type="file" name="payment_img[]" multiple id="payment_img" class="form-control"
                            accept=".jpg, .jpeg, .png" required>
                        </br>

                        <label>Payment Name:</label>
                        <input type="text" name="payment_name" id="payment_name" class="form-control" required>
                    </form>
                </div>

                <div class="modal-footer">
                    <button id="update" type="button" class="btn btn-dark" data-dismiss="modal">Update</button>
                    <button id="save" type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for Import --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLongTitle">Payment Import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="buttonClose">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="importForm" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="importFile" name="importFile"
                                    style="cursor: pointer">
                                <label class="custom-file-label" for="inputGroupFile04" id="labelFile"
                                    style="cursor: pointer">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="import">Import</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('jquery_datatables/payments.js') }}"></script>
@endsection
