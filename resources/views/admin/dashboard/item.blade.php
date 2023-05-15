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
            <button data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-info d-none d-lg-block m-l-15 text-white"><i
                class="fa fa-plus-circle"></i> New Item</button>
            </div>
        </div>
    </div>
    
    {{-- content --}}
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <input type="text" id="searchItem" class="form-control" placeholder="Search by Item Code">
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title" id="dataCount">Items</h4>
                    <div class="table-responsive">
                        <table class="table color-table purple-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTable">
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
                    <h4 class="modal-title" id="myLargeModalLabel">New Item</h4>
                    <button class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <form class="row" id="createForm" enctype="multipart/form-data" method="post">
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Name</label>
                                            <input class="form-control" id="name" name="name">
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Quantity</label>
                                            <input class="form-control" min="0" type="number" id="quantity" name="quantity">
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label class="form-label">Unit Cost (LKR)</label>
                                            <input class="form-control" min="0" type="number" id="unitCost" name="unitCost">
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
    
    @endsection
    
    
    
    
    
    @section('script')
    <script>
        $(document).ready(function(){
            
            var url = "{{ url('admin/dashboard/readItem') }}";
            function configureUrl()
            {
                var length = $('#searchItem').val().length;
                if (length == 0) {
                    url = "{{ url('admin/dashboard/readItem') }}";
                }
            }

            readItems();
            
            //read requests
            function readItems()
            {
                configureUrl();
                $.ajax({
                    type: "GET", url:url, dataType:"json",
                    success:function(response){
                        $('#itemsTable').html(''); 
                        
                        $.each(response.data,function(key,item){
                            //display badges
                            var status_badge = ''; var status_button = ''; var nickname = '';
                            
                            //sorting STATUS
                            switch(item.isAvailable) {
                                case 1:
                                status_badge = '<span class="label label-success">In Stock</span>';
                                break;
                                case 0:
                                status_badge = '<span class="label label-danger">Out of Stock</span>'; 
                                break;
                            }
                            
                            var total = item.unitCost * item.quantity;
                            
                            $('#itemsTable').append('<tr>\
                                <td>'+item.code+'</td>\
                                <td>'+item.name+'</td>\
                                <td>'+status_badge+'</td>\
                                <td>'+item.quantity+'</td>\
                                <td>Rs. '+item.unitCost+'</td>\
                                <td>Rs. '+total+'</td>\
                                <td>\
                                    <div class="btn-group m-b-10 m-r-10">\
                                        <button class="btn btn-warning" id="btnUpdateQuantity" value="'+item.id+'">+ 1</button>\
                                    </div>\
                                </td>\
                                </tr>'); 
                            });
                        }
                    });
            }
            
            //Create
            $(document).on('click', '#btnCreate', function(e) {
                e.preventDefault();
                
                $("#btnCreate").prop("disabled", true).text("Creating...");
                
                let formData = new FormData($('#createForm')[0]);
                $.ajax({
                    type: "POST", url: "{{ url('admin/dashboard/createItem') }}",
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
                            readItems();
                            
                            Swal.fire({ title: 'Success', text: "Item Saved",
                            icon: 'success', confirmButtonColor: '#3085d6', confirmButtonText: 'OK' });
                            
                        }
                    }
                });
            });

            //update quantity
            $(document).on('click', '#btnUpdateQuantity', function(e){
                e.preventDefault();
                var id = $(this).val(); //get message id
                var urlView = '{{ url("admin/dashboard/updateQuantity/:id") }}'; urlView = urlView.replace(':id', id);
                $.ajax({
                    type:"GET", url:urlView, dataType:"json",
                    success: function(response)
                    {
                        readItems();
                    }
                });
            });
           
            //search
            $(document).on('keyup', '#searchItem', function(e) {
                url = "{{ url('admin/dashboard/searchItem/:search') }}";
                url = url.replace(':search', $(this).val());
                readItems();
            });
        });
    </script>
    @endsection