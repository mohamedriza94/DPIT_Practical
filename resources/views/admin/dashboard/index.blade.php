@extends('layouts.admin')

@section('content')

{{-- breadcrumb --}}
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">{{ $title }}</h4>
    </div>
    <div class="col-md-7 align-self-center text-end">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb justify-content-end">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
            </div>
        </div>
    </div>
    
    {{-- content --}}
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body d-flex">
                    <input type="text" id="searchRequests" class="form-control" placeholder="Search by Request No."> &nbsp;
                    <button class="btn btn-dark" id="btnRefresh">Refresh</button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title" id="dataCount">Purchase Requests</h4>
                    <div class="table-responsive">
                        <table class="table color-table purple-table">
                            <thead>
                                <tr>
                                    <th>Request No.</th>
                                    <th>Customer</th>
                                    <th>Item Code</th>
                                    <th>Status</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                    <th>Total</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="requestsTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modals --}}
    
    {{-- view modal --}}
    <div class="modal bs-example-modal-lg animated fadeIn" id="viewModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">View Request</h4>
                    <button class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form class="row" id="viewForm" enctype="multipart/form-data" method="post">

                                        <input type="hidden" name="id" id="view_id">
                                        <div class="form-group col-6">
                                            <label class="form-label">Item Name</label>
                                            <input type="text" readonly class="form-control" id="view_itemName">
                                        </div>
                                        
                                        <div class="form-group col-6">
                                            <label class="form-label">Item Code</label>
                                            <input type="text" readonly class="form-control" id="view_itemCode">
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Customer</label>
                                            <input type="text" readonly class="form-control" id="view_customer">
                                        </div>

                                        <div class="form-group col-4">
                                            <label class="form-label">Quantity</label>
                                            <input type="text" name="quantity" readonly class="form-control" id="view_quantity">
                                        </div>
                                        
                                        <div class="form-group col-4">
                                            <label class="form-label">Cost</label>
                                            <input type="text" readonly class="form-control" id="view_cost">
                                        </div>
                                        
                                        <div class="form-group col-4">
                                            <label class="form-label">Total</label>
                                            <input type="text" readonly class="form-control" id="view_total">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    
    {{-- disapprove modal --}}
    <div class="modal bs-example-modal-md animated fadeIn" id="disapproveModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Disapprove a Request</h4>
                    <button class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form class="row" id="viewForm" enctype="multipart/form-data" method="post">

                                        <div class="form-group col-12">
                                            <label class="form-label">Reason for Disapproving</label>
                                            <Select class="form-control" id="reason">
                                                <option value="noStock">No Stocks</option>
                                                <option value="other">Other</option>
                                            </Select>
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Message</label>
                                            <textarea class="form-control" readonly rows="5" name="message" id="message">- Due to unavailability of sufficient stocks of the Item you requested.
                                            </textarea>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-md-flex align-items-center">
                                                <button id="btnDisapprove" class="col-12 btn btn-info">Disapprove</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    
    @endsection
    
    
    
    
    
    @section('script')
    <script>
        $(document).ready(function(){
            
            var url = "{{ url('admin/dashboard/readPurchaseRequest') }}";
            function configureUrl()
            {
                var length = $('#searchRequests').val().length;
                if (length == 0) {
                    url = "{{ url('admin/dashboard/readPurchaseRequest') }}";
                }
            }

            readPurchaseRequest();
            
            //read requests
            function readPurchaseRequest()
            {
                configureUrl();
                $.ajax({
                    type: "GET", url:url, dataType:"json",
                    success:function(response){
                        $('#requestsTable').html(''); 
                        
                        $.each(response.data,function(key,item){
                            //display badges
                            var status_badge = ''; var status_button = ''; var nickname = '';
                            
                            //sorting STATUS
                            switch(item.status) {
                                case 'pending':
                                status_badge = '<span class="label font-16 p-2 label-warning">Pending</span>';
                                status_button = '<button id="btnApprove" value="'+item.id+'" class="btn btn-success">Approve</button>\
                                <button id="btnDisapproveModel" value="'+item.id+'" class="btn btn-danger">Disapprove</button>';
                                break;
                                case 'approved':
                                status_badge = '<span class="label font-16 p-2 label-success">Approved</span>';
                                status_button = '';
                                break;
                                case 'disapproved':
                                status_badge = '<span class="label font-16 p-2 label-danger">Disapproved</span>';
                                status_button = '';
                                break;
                            }

                            //format time
                            formatTime(item.createdAt);

                            var total = item.cost * item.quantity;
                            
                            $('#requestsTable').append('<tr>\
                                <td><b>'+item.no+'</b></td>\
                                <td>'+item.clientEmail+'</td>\
                                <td>'+item.itemCode+'</td>\
                                <td>'+status_badge+'</td>\
                                <td>'+item.quantity+'</td>\
                                <td>Rs. '+item.cost+'</td>\
                                <td>Rs. '+total+'</td>\
                                <td>'+date+''+time+'</td>\
                                <td>\
                                    <div class="btn-group m-b-10 m-r-10">\
                                        <button id="btnView" value="'+item.id+'" class="btn btn-primary">See</button>\
                                        '+status_button+'\
                                    </div>\
                                </td>\
                                </tr>'); 
                            });
                        }
                    });
            }
            
            //refresh
            $(document).on('click', '#btnRefresh', function(e){
                readPurchaseRequest();
            });
            
            var id = '';
            //View
            $(document).on('click', '#btnView', function(e){
                e.preventDefault();
                id = $(this).val(); //get request id
                var urlView = '{{ url("admin/dashboard/readOnePurchaseRequest/:id") }}'; urlView = urlView.replace(':id', id);
                $.ajax({
                    type:"GET", url:urlView, dataType:"json",
                    success: function(response)
                    {
                        $('#viewModal').modal('show');  //OPEN MODAL
                        
                        //format time
                        formatTime(response.data.created_at);
                        
                        var total = response.data.quantity * response.data.cost;

                        $('#view_itemName').val(response.data.itemName);
                        $('#view_itemCode').val(response.data.itemCode);
                        $('#view_quantity').val(response.data.quantity);
                        $('#view_customer').val(response.data.clientEmail);
                        $('#view_cost').val('Rs. '+response.data.cost);
                        $('#view_total').val('Rs. '+total);
                    }
                });
            });
            
            //search
            $(document).on('keyup', '#searchRequests', function(e) {
                url = "{{ url('admin/dashboard/searchPurchaseRequest/:search') }}";
                url = url.replace(':search', $(this).val());
                readPurchaseRequest();
            });
            
            //set reason for disapproval
            $(document).on('change', '#reason', function(e) {
                var selectedOption = $(this).val();
                
                if (selectedOption === 'noStock') {
                    $('#message').val('- Due to unavailability of sufficient stocks of the Item you requested.');
                    $('#message').prop('readonly', true);
                } else {
                    $('#message').val('');
                    $('#message').prop('readonly', false);
                }
            });

            //disapprove model open
            $(document).on('click', '#btnDisapproveModel', function(e) {
                e.preventDefault();

                id = $(this).val();
                $('#disapproveModal').modal('show');
            });

            //disapprove
            $(document).on('click', '#btnDisapprove', function(e) {
                e.preventDefault();

                var status = '0';
                var message = $('#message').val();
                var data = { 'id':id,'status':status, 'message':message }

                $("#btnDisapprove").prop("disabled", true).text("Disapproving...");

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/dashboard/updatePurchaseRequestStatus') }}",
                    data:data,
                    dataType:"json",
                    success: function(response){
                        if(response.status == 400)
                        {
                            $("#btnDisapprove").prop("disabled", false).text("Disapprove");
                            toastType = 'error'; toastMessage = response.message; showToast(); //TOAST ALERT
                            readPurchaseRequest();
                        }
                        else if(response.status == 200)
                        {
                            Swal.fire({ title: 'Success', text: "Request Disapproved and Notified to Customer",
                            icon: 'success', confirmButtonColor: '#3085d6', confirmButtonText: 'OK' });
                            readPurchaseRequest();
                            $("#btnDisapprove").prop("disabled", false).text("Disapprove");
                        }
                    }
                });
            });

            //approve
            $(document).on('click', '#btnApprove', function(e) {
                e.preventDefault();
                var status = '1';
                var data = { 'id':$(this).val(),'status':status,'message':'-' }

                $(this).prop("disabled", true).text("Approving...");

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/dashboard/updatePurchaseRequestStatus') }}",
                    data:data,
                    dataType:"json",
                    success: function(response){
                        if(response.status == 400)
                        {
                            $(this).prop("disabled", false).text("Approve");
                            Swal.fire({ title: 'Low on Stock', text: response.message,
                            icon: 'error', confirmButtonColor: '#3085d6', confirmButtonText: 'OK' });
                            readPurchaseRequest();
                        }
                        else if(response.status == 200)
                        {
                            $(this).prop("disabled", false).text("Approve");
                            toastType = 'success'; toastMessage = response.message; showToast(); //TOAST ALERT
                            readPurchaseRequest();
                        }
                    }
                });
            });
        });
    </script>
    @endsection