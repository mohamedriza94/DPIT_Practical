@extends('layouts.client')

@section('content')

{{-- breadcrumb --}}
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">{{ $title }} : Logged in as <b><i>{{ $customer }}</i></b></h4>
    </div>
    <div class="col-md-7 align-self-center text-end">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb justify-content-end">
                <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Customer</li>
            </ol>
            <button data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-info d-none d-lg-block m-l-15 text-white"><i
                class="fa fa-plus-circle"></i> New Request</button>
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
                    <h4 class="card-title" id="dataCount">My Purchase Requests</h4>
                    <div class="table-responsive">
                        <table class="table color-table purple-table">
                            <thead>
                                <tr>
                                    <th>Request No.</th>
                                    <th>Item Name</th>
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
    {{-- create modal --}}
    <div class="modal bs-example-modal-sm animated fadeIn" id="createModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Request</h4>
                    <button class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <form class="row" id="createForm" enctype="multipart/form-data" method="post">
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Choose Item</label>
                                            <select class="form-select" name="item" id="item">
                                            </select>
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Quantity</label>
                                            <input class="form-control" min="0" type="number" id="quantity" name="quantity">
                                        </div>
                                        <div class="col-12">
                                            <div class="d-md-flex align-items-center">
                                                <button type="submit" id="btnCreate" class="col-12 btn btn-info">Create</button>
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
                                            <label class="form-label">Choose New Item (Optional)</label>
                                            <select class="form-select" id="itemUpdate">
                                            </select>
                                        </div>

                                        <input type="hidden" name="item" id="updateItem">
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Quantity</label>
                                            <input type="text" name="quantity" class="form-control" id="view_quantity">
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Cost</label>
                                            <input type="text" readonly class="form-control" id="view_cost">
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Total</label>
                                            <input type="text" readonly class="form-control" id="view_total">
                                        </div>
                                        <div class="col-12">
                                            <div class="d-md-flex align-items-center">
                                                <button type="submit" id="btnUpdate" class="col-12 btn btn-info">Update</button>
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
            
            var url = "{{ url('client/dashboard/readPurchaseRequest') }}";
            function configureUrl()
            {
                var length = $('#searchRequests').val().length;
                if (length == 0) {
                    url = "{{ url('client/dashboard/readPurchaseRequest') }}";
                }
            }

            readPurchaseRequest();
            readItems();
            
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
                                status_button = 'N/A';
                                break;
                                case 'approved':
                                status_badge = '<span class="label font-16 p-2 label-success">Approved</span>';
                                status_button = 'N/A';
                                break;
                                case 'disapproved':
                                status_badge = '<span class="label font-16 p-2 label-danger">Disapproved</span>';
                                status_button = '<button id="btnView" value="'+item.id+'" class="btn btn-success">Modify Request</button>';
                                break;
                            }

                            //format time
                            formatTime(item.createdAt);

                            var total = item.cost * item.quantity;
                            
                            $('#requestsTable').append('<tr>\
                                <td><b>'+item.no+'</b></td>\
                                <td>'+item.itemName+'</td>\
                                <td>'+item.itemCode+'</td>\
                                <td>'+status_badge+'</td>\
                                <td>'+item.quantity+'</td>\
                                <td>Rs. '+item.cost+'</td>\
                                <td>Rs. '+total+'</td>\
                                <td>'+date+''+time+'</td>\
                                <td>\
                                    <div class="btn-group m-b-10 m-r-10">\
                                        '+status_button+'\
                                    </div>\
                                </td>\
                                </tr>'); 
                            });
                        }
                    });
            }

            //read items
            function readItems()
            {$.ajax({
                    type: "GET", url:"{{ url('client/dashboard/readItems') }}", dataType:"json",
                    success:function(response){
                        
                        $.each(response.data,function(key,item){
                            
                            $('#item').append('<option value="'+item.code+'">'+item.name+'</option>'); 
                            $('#itemUpdate').append('<option value="'+item.code+'">'+item.name+'</option>'); 
                            });
                        }
                    });
            }

            //refresh
            $(document).on('click', '#btnRefresh', function(e){
                readPurchaseRequest();
            });
            
            //Create
            $(document).on('click', '#btnCreate', function(e) {
                e.preventDefault();
                
                $("#btnCreate").prop("disabled", true).text("Creating...");
                
                let formData = new FormData($('#createForm')[0]);
                $.ajax({
                    type: "POST", url: "{{ url('client/dashboard/createPurchaseRequest') }}",
                    data: formData, contentType:false, processData:false,
                    success: function(response){
                        if(response.status==800)
                        {
                            $.each(response.errors,function(key,error)
                            {
                                toastMessage = '';
                                toastType = 'error'; toastMessage += error; showToast(); //TOAST ALERT
                            });
                            
                            $("#btnCreate").prop("disabled", false).text("Create");
                        }
                        else if(response.status == 400 || response.status == 600)
                        {
                            $("#btnCreate").prop("disabled", false).text("Create");
                            toastType = 'error'; toastMessage = response.message; showToast(); //TOAST ALERT
                        }
                        else if(response.status == 200)
                        {
                            $("#btnCreate").prop("disabled", false).text("Create");
                            $('#createForm')[0].reset(); //FORM RESET INPUT
                            readPurchaseRequest();
                            
                            Swal.fire({ title: 'Success', text: "Request Made",
                            icon: 'success', confirmButtonColor: '#3085d6', confirmButtonText: 'OK' });
                            
                        }
                    }
                });
            });

            //View
            $(document).on('click', '#btnView', function(e){
                e.preventDefault();
                var id = $(this).val(); //get message id
                var urlView = '{{ url("client/dashboard/readOnePurchaseRequest/:id") }}'; urlView = urlView.replace(':id', id);
                $.ajax({
                    type:"GET", url:urlView, dataType:"json",
                    success: function(response)
                    {
                        $('#viewModal').modal('show');  //OPEN MODAL
                        
                        //format time
                        formatTime(response.data.created_at);
                        
                        var total = response.data.quantity * response.data.cost;

                        $('#view_id').val(id);
                        $('#view_itemName').val(response.data.itemName);
                        $('#view_itemCode').val(response.data.itemCode);
                        $('#view_quantity').val(response.data.quantity);
                        $('#view_cost').val('Rs. '+response.data.cost);
                        $('#view_total').val('Rs. '+total);
                    }
                });
            });
            
            //Update
            $(document).on('click', '#btnUpdate', function(e) {
                e.preventDefault();
                
                $("#btnUpdate").prop("disabled", true).text("Updating...");
                
                //check if new item has been selected
                var selectedItem = $('#itemUpdate option:selected');
                
                if (selectedItem.length > 0) {
                    var item = $('#itemUpdate').val();

                    $('#updateItem').val(item);

                } else {
                    $('#updateItem').val($('#view_itemCode').val());
                }

                
                let formData = new FormData($('#viewForm')[0]);
                $.ajax({
                    type: "POST", url: "{{ url('client/dashboard/updatePurchaseRequest') }}",
                    data: formData, contentType:false, processData:false,
                    success: function(response){
                        if(response.status==800)
                        {
                            $.each(response.errors,function(key,error)
                            {
                                toastMessage = '';
                                toastType = 'error'; toastMessage += error; showToast(); //TOAST ALERT
                            });
                            
                            $("#btnUpdate").prop("disabled", false).text("Update");
                        }
                        else if(response.status == 400 || response.status == 600)
                        {
                            $("#btnUpdate").prop("disabled", false).text("Update");
                            toastType = 'error'; toastMessage = response.message; showToast(); //TOAST ALERT
                        }
                        else if(response.status == 200)
                        {
                            $("#btnUpdate").prop("disabled", false).text("Update");
                            $('#viewModal').modal('hide');  //HIDE MODAL
                            readPurchaseRequest();
                            
                            Swal.fire({ title: 'Success', text: "Request Updated",
                            icon: 'success', confirmButtonColor: '#3085d6', confirmButtonText: 'OK' });
                            
                        }
                    }
                });
            });
            
            //search
            $(document).on('keyup', '#searchRequests', function(e) {
                url = "{{ url('client/dashboard/searchPurchaseRequest/:search') }}";
                url = url.replace(':search', $(this).val());
                readPurchaseRequest();
            });
        });
    </script>
    @endsection