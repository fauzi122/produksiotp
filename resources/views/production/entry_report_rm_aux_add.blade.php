@extends('layouts.master')

@section('konten')
<div class="page-content">
    <div class="container-fluid">
    <form method="post" action="/production-ent-report-rm-aux-save" class="form-material m-t-40" enctype="multipart/form-data">
    @csrf
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
					<a href="/production-ent-report-rm-aux" class="btn btn-dark waves-effect waves-light mb-3"> 
					<i class="bx bx-list-ul" title="Back"></i> REPORT RM, AUX, OTHERS</a>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Production</a></li>
                            <li class="breadcrumb-item active"> Add Report RM, Aux, Others</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Report RM, Aux, Others</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="col-sm-12">
                            <div class="mt-4 mt-lg-0">
                    
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-firstname-input" class="col-sm-3 col-form-label">Report Number</label>
									<div class="col-sm-9">
										<input type="text" name="request_number" class="form-control" value="{{ $formattedCode }}" readonly>
									</div>
								</div>
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-email-input" class="col-sm-3 col-form-label">Date</label>
									<div class="col-sm-9">
										<input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
										@if($errors->has('date'))
											<div class="text-danger"><b>{{ $errors->first('date') }}</b></div>
										@endif
									</div>
								</div>
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-password-input" class="col-sm-3 col-form-label">Customers </label>
									<div class="col-sm-9">
										<select class="form-select data-select2" name="id_master_customers" id="id_master_customers" required>
											<option value="">** Please Select A Customers</option>
										</select>
										@if($errors->has('id_master_customers'))
											<div class="text-danger"><b>{{ $errors->first('id_master_customers') }}</b></div>
										@endif
									</div>
								</div>  								
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-password-input" class="col-sm-3 col-form-label">Sales Order (SO) </label>
									<div class="col-sm-9">
										<select class="form-select" name="id_sales_orders" id="id_sales_orders" required>
											<option value="">** Please Select a Customer First</option>
										</select>
										@if($errors->has('id_sales_orders'))
											<div class="text-danger"><b>{{ $errors->first('id_sales_orders') }}</b></div>
										@endif
									</div>
								</div>
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-password-input" class="col-sm-3 col-form-label">Shift  </label>
									<div class="col-sm-9">
										<select class="form-select data-select2" name="shift" id="shift" required>
											<option value="">** Please Select A Shift</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
										</select>
										@if($errors->has('shift'))
											<div class="text-danger"><b>{{ $errors->first('shift') }}</b></div>
										@endif
									</div>
								</div>	
								<div class="row mb-4 field-wrapper">
									<label for="horizontal-firstname-input" class="col-sm-3 col-form-label">Note </label>
									<div class="col-sm-9">
										<textarea rows="5" class="form-control" name="note"></textarea>
										@if($errors->has('note'))
											<div class="text-danger"><b>{{ $errors->first('note') }}</b></div>
										@endif
									</div>
								</div> 	
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-password-input" class="col-sm-3 col-form-label">Ketua Regu </label>
									<div class="col-sm-9">
										<select class="form-select data-select2" name="id_ketua_regu" id="id_ketua_regu" required>
											<option value="">** Please Select A Ketua Regu</option>
											@foreach ($ms_ketua_regu as $data)
												<option value="{{ $data->id }}">{{ $data->name }}</option>
											@endforeach
										</select>
										@if($errors->has('id_ketua_regu'))
											<div class="text-danger"><b>{{ $errors->first('id_ketua_regu') }}</b></div>
										@endif
									</div>
								</div>	
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-password-input" class="col-sm-3 col-form-label">Operator </label>
									<div class="col-sm-9">
										<select class="form-select data-select2" name="id_operator" id="id_operator" required>
											<option value="">** Please Select A Operator</option>
											@foreach ($ms_operator as $data)
												<option value="{{ $data->id }}">{{ $data->name }}</option>
											@endforeach
										</select>
										@if($errors->has('id_operator'))
											<div class="text-danger"><b>{{ $errors->first('id_operator') }}</b></div>
										@endif
										
										<input type="hidden" name="id_cms_user" class="form-control" value="{{ Auth::user()->id }}">
									</div>
								</div>
								<div class="row mb-4 field-wrapper required-field">
									<label for="horizontal-password-input" class="col-sm-3 col-form-label">Known By </label>
									<div class="col-sm-9">
										<select class="form-select data-select2" name="id_known_by" id="id_known_by" required>
											<option value="">** Please Select A Known By</option>
											@foreach ($ms_known_by as $data)
												<option value="{{ $data->id }}">{{ $data->name }}</option>
											@endforeach
										</select>
										@if($errors->has('id_known_by'))
											<div class="text-danger"><b>{{ $errors->first('id_known_by') }}</b></div>
										@endif
									</div>
								</div>	
								<script>									
									$(document).ready(function(){
										$('#id_master_customers').prop('selectedIndex', 0);
										$('#shift').prop('selectedIndex', 0);
										
										// Load Customers list
										$.ajax({
											type: "GET",
											url: "/json_get_customer",
											dataType: "json",
											success: function(response){
												$("#id_master_customers").html(response.list_customers).show();
											},
											error: function (xhr, ajaxOptions, thrownError) {
												alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
											}
										});

										// Select2 for Sales Orders
										$('#id_sales_orders').select2({
											placeholder: "** Please Select a Sales Order",
											width: 'resolve',
											theme: "classic",
											minimumInputLength: 0,
											ajax: {
												url: '/json_get_sales_orders_rm_aux',
												dataType: 'json',
												delay: 250,
												data: function (params) {
													return {
														search: params.term,
														id_master_customers: $('#id_master_customers').val(),
														page: params.page || 1
													};
												},
												processResults: function (data, params) {
													params.page = params.page || 1;
													return {
														results: data.results,
														pagination: {
															more: data.pagination.more
														}
													};
												},
												cache: true
											}
										});

										$("#id_master_customers").change(function(){
											$('#id_sales_orders').val(null).trigger('change');
										});
									});
								</script>
								<div class="row justify-content-end">
									<div class="col-sm-9">
										<div>
											<a href="/production-ent-report-rm-aux" class="btn btn-danger waves-effect waves-light"><i class="bx bx-chevron-left" title="Back"></i> BACK</a>
											<button type="submit" class="btn btn-success w-md" name="save"><i class="bx bx-save" title="Save"></i> SAVE</button>
										</div>
									</div>
								</div>                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </form>
    </div>
</div>
@endsection
