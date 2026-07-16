@extends('layouts.master')

@section('konten')
<div class="page-content">
	<div class="container-fluid">
		@csrf

		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-sm-flex align-items-center justify-content-between">
					<a href="/production-ent-report-rm-aux" class="btn btn-dark waves-effect waves-light mb-3">
						<i class="bx bx-list-ul" title="Back"></i> REPORT RM, AUX, OTHERS</a>
					<div class="page-title-right">
						<ol class="breadcrumb m-0">
							<li class="breadcrumb-item"><a href="javascript: void(0);">Production</a></li>
							<li class="breadcrumb-item active"> Report RM, Aux, Others Detail</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		@if (session('pesan'))
		<div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
			<i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('pesan') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		@endif
		@if (session('pesan_danger'))
		<div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
			<i class="mdi mdi-alert-octagon-outline label-icon"></i><strong>Dangers</strong> - {{ session('pesan_danger') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		@endif

		@if(!empty($data[0]))
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Report RM, Aux, Others</h4>
					</div>
					<div class="card-body p-4">
						<div class="col-sm-12">
							<div class="mt-4 mt-lg-0">
								<form method="post" action="/production-ent-report-rm-aux-update" class="form-material m-t-40" enctype="multipart/form-data">
									@csrf
									<input type="hidden" class="form-control" name="request_id" value="{{ Request::segment(2) }}">
									
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-firstname-input" class="col-sm-3 col-form-label">Report Number</label>
										<div class="col-sm-9">
											<input type="text" name="report_number" class="form-control" value="{{ $data[0]->report_number }}" readonly>
										</div>
									</div>
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-email-input" class="col-sm-3 col-form-label">Date</label>
										<div class="col-sm-9">
											<input type="date" name="date" class="form-control" value="{{ $data[0]->date }}">
											@if($errors->has('date'))
											<div class="text-danger"><b>{{ $errors->first('date') }}</b></div>
											@endif
										</div>
									</div>
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-password-input" class="col-sm-3 col-form-label">Customers</label>
										<div class="col-sm-9">
											<select class="form-select data-select2" name="id_master_customers" id="id_master_customers">
												<option value="">** Please Select A Customers</option>
											</select>
											@if($errors->has('id_master_customers'))
											<div class="text-danger"><b>{{ $errors->first('id_master_customers') }}</b></div>
											@endif
										</div>
									</div>
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-password-input" class="col-sm-3 col-form-label">Sales Order (SO)</label>
										<div class="col-sm-9">
											<select class="form-select" name="id_sales_orders" id="id_sales_orders" required>
												<option value="">** Please Select a Sales Order</option>
											</select>
											@if($errors->has('id_sales_orders'))
											<div class="text-danger"><b>{{ $errors->first('id_sales_orders') }}</b></div>
											@endif
										</div>
									</div>
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-password-input" class="col-sm-3 col-form-label">Shift</label>
										<div class="col-sm-9">
											<select class="form-select data-select2" name="shift" id="shift" required>
												<option value="">** Please Select A Shift</option>
												<option value="1" {{ $data[0]->shift == '1' ? 'selected' : '' }}>1</option>
												<option value="2" {{ $data[0]->shift == '2' ? 'selected' : '' }}>2</option>
												<option value="3" {{ $data[0]->shift == '3' ? 'selected' : '' }}>3</option>
											</select>
											@if($errors->has('shift'))
											<div class="text-danger"><b>{{ $errors->first('shift') }}</b></div>
											@endif
										</div>
									</div>
									<div class="row mb-4 field-wrapper">
										<label for="horizontal-firstname-input" class="col-sm-3 col-form-label">Note</label>
										<div class="col-sm-9">
											<textarea rows="5" class="form-control" name="note">{{ $data[0]->note }}</textarea>
											@if($errors->has('note'))
											<div class="text-danger"><b>{{ $errors->first('note') }}</b></div>
											@endif
										</div>
									</div>
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-password-input" class="col-sm-3 col-form-label">Ketua Regu</label>
										<div class="col-sm-9">
											<select class="form-select data-select2" name="id_ketua_regu" id="id_ketua_regu" required>
												<option value="">** Please Select A Ketua Regu</option>
												@foreach ($ms_ketua_regu as $employee)
												<option value="{{ $employee->id }}" {{ $data[0]->ketua_regu == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
												@endforeach
											</select>
											@if($errors->has('id_ketua_regu'))
											<div class="text-danger"><b>{{ $errors->first('id_ketua_regu') }}</b></div>
											@endif
										</div>
									</div>
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-password-input" class="col-sm-3 col-form-label">Operator</label>
										<div class="col-sm-9">
											<select class="form-select data-select2" name="id_operator" id="id_operator" required>
												<option value="">** Please Select A Operator</option>
												@foreach ($ms_operator as $employee)
												<option value="{{ $employee->id }}" {{ $data[0]->operator == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
												@endforeach
											</select>
											@if($errors->has('id_operator'))
											<div class="text-danger"><b>{{ $errors->first('id_operator') }}</b></div>
											@endif
										</div>
									</div>
									<div class="row mb-4 field-wrapper required-field">
										<label for="horizontal-password-input" class="col-sm-3 col-form-label">Known By</label>
										<div class="col-sm-9">
											<select class="form-select data-select2" name="id_known_by" id="id_known_by" required>
												<option value="">** Please Select A Known By</option>
												@foreach ($ms_known_by as $employee)
												<option value="{{ $employee->id }}" {{ $data[0]->known_by == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
												@endforeach
											</select>
											@if($errors->has('id_known_by'))
											<div class="text-danger"><b>{{ $errors->first('id_known_by') }}</b></div>
											@endif
										</div>
									</div>

									<script>
										$(document).ready(function(){
											// Load Customers
											$.ajax({
												type: "GET",
												url: "/json_get_customer",
												data: { id_master_customers: "{{ $data[0]->id_master_customers }}" },
												dataType: "json",
												success: function(response){
													$("#id_master_customers").html(response.list_customers).show();
												}
											});

											// Select2 Sales Orders
											$('#id_sales_orders').select2({
												placeholder: "** Please Select a Sales Order",
												width: 'resolve',
												theme: "classic",
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
															pagination: { more: data.pagination.more }
														};
													},
													cache: true
												}
											});

											// Prefill sales order select option on load
											var initialSoId = "{{ $data[0]->id_sales_orders }}";
											var initialSoNumber = "{{ $data[0]->so_number }}";
											if (initialSoId) {
												var newOption = new Option(initialSoNumber, initialSoId, true, true);
												$('#id_sales_orders').append(newOption).trigger('change');
											}

											$("#id_master_customers").change(function(){
												$('#id_sales_orders').val(null).trigger('change');
											});
										});
									</script>

									<div class="row justify-content-end">
										<div class="col-sm-9">
											<div>
												<button type="submit" class="btn btn-success w-md" name="rra_update"><i class="bx bx-save" title="Save"></i> UPDATE</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title"><i data-feather="check-square"></i> Production Result</h4>
					</div>
					<div class="card-body p-4" id="detailTableSection">
						<div class="row">
							<div class="col-lg-5">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">Form Add Result</h4>
									</div>
									<div class="card-body p-4">
										<form id="form-add-result" method="post" action="/production-entry-report-rm-aux-detail-production-result-add#detailTableSection" class="form-material m-t-40" enctype="multipart/form-data">
											@csrf
											<input type="hidden" class="form-control" name="request_id" value="{{ Request::segment(2) }}">
											
											<div class="row mb-4 field-wrapper">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">Sales Order (SO)</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" value="{{ $data[0]->so_number }}" readonly>
												</div>
											</div>

											<div class="row mb-4 field-wrapper">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">Product Info</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="product_info" id="product_info" readonly>
												</div>
											</div>

											<div class="row mb-4 field-wrapper">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">SO Quantity</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="so_qty_display" value="{{ $data[0]->so_qty ?? 0 }}" readonly>
												</div>
											</div>

											<div class="row mb-4 field-wrapper required-field">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">Qty Usage</label>
												<div class="col-sm-8">
													<input type="number" step="any" class="form-control" name="qty_use" id="qty_use" required min="0.01">
													<div class="text-muted mt-1 small">Maksimal Qty Usage (110% SO Qty): <span id="max_qty_use_label">0</span> Kg (Sudah Terpakai: {{ number_format($data_detail_production->sum('qty_use'), 2) }} Kg)</div>
													<div id="qty-use-warning" style="display:none; color: #f46a6a;" class="small mt-1 font-weight-semibold"></div>
												</div>
											</div>

											<div class="row mb-4 field-wrapper required-field">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">Start Time</label>
												<div class="col-sm-8">
													<input type="datetime-local" class="form-control" name="start_time" required value="{{ date('Y-m-d\TH:i') }}">
												</div>
											</div>

											<div class="row mb-4 field-wrapper required-field">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">Finish Time</label>
												<div class="col-sm-8">
													<input type="datetime-local" class="form-control" name="finish_time" required value="{{ date('Y-m-d\TH:i') }}">
												</div>
											</div>

											<input type="hidden" name="lot_number" id="form_lot_number">

											<div class="row mb-4 field-wrapper">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">External Lot</label>
												<div class="col-sm-8">
													<select class="form-select data-select2" name="product" id="product" required>
														<option value="">** Loading External Lots... **</option>
													</select>
												</div>
											</div>

											<div class="row mb-4 field-wrapper required-field">
												<label for="horizontal-firstname-input" class="col-sm-4 col-form-label">Barcode End</label>
												<div class="col-sm-8">
													<select class="form-select data-select2" name="barcode_end" id="barcode_end" required>
														<option value="">** Please Select A Barcode **</option>
														@foreach($ms_barcodes_so as $barcode)
															<option value="{{ $barcode->barcode_number }}">{{ $barcode->barcode_number }}</option>
														@endforeach
													</select>
												</div>
											</div>

											<script>
												$(document).ready(function(){
													var typeProduct = "{{ $data[0]->type_product }}";
													var idMasterProducts = "{{ $data[0]->id_master_products }}";
													var soQty = "{{ $data[0]->so_qty ?? 0 }}";
													
													var soQtyVal = parseFloat(soQty || 0);
													if (soQtyVal > 0) {
														$('#max_qty_use_label').text((soQtyVal * 1.10).toFixed(2));
													}
													var existingQtyUse = parseFloat("{{ $data_detail_production->sum('qty_use') ?? 0 }}");

													function validateQtyUse() {
														var qtyUse = parseFloat($('#qty_use').val() || 0);
														var maxQtyUse = soQtyVal * 1.10;
														var totalQtyUse = existingQtyUse + qtyUse;
														if (soQtyVal > 0 && totalQtyUse > maxQtyUse) {
															$('#qty-use-warning').html('⚠️ Total Qty Usage (' + totalQtyUse.toFixed(2) + ' Kg) tidak boleh melebihi 110% dari SO Quantity (Maksimal: ' + maxQtyUse.toFixed(2) + ' Kg, Sudah Terpakai: ' + existingQtyUse.toFixed(2) + ' Kg)').show();
															return false;
														} else {
															$('#qty-use-warning').hide();
															return true;
														}
													}
													
													// Fetch Product Info
													if(typeProduct && idMasterProducts) {
														$.ajax({
															type: "GET",
															url: "/json_get_produk_autofill",
															data: { type_product: typeProduct, id_master_products: idMasterProducts },
															dataType: "json",
															success: function(response){
																if(response.result && response.result[0]) {
																	$('#product_info').val(response.result[0].description);
																}
															}
														});

														// Fetch External Lot options (all lots of the product directly)
														$.ajax({
															type: "GET",
															url: "/json_get_barcodes_by_lot",
															data: { all_product_lots: true, type_product: typeProduct, id_master_products: idMasterProducts, so_qty: soQty },
															dataType: "json",
															success: function(response) {
																var html = '<option value="">** Please Select an External Lot **</option>';
																if (response && response.length > 0) {
																	$.each(response, function(idx, val) {
																		html += '<option value="' + val.ext_lot_number + '" data-sisa="' + val.sisa + '" data-lot_number="' + val.lot_number + '">';
																		html += val.ext_lot_number + ' (Lot: ' + val.lot_number + ') | ' + val.description + ' (Sisa Per EXT: ' + val.sisa + ' Kg)';
																		html += '</option>';
																	});
																} else {
																	html = '<option value="">** No External Lots Available for this Product **</option>';
																}
																$('#product').html(html).trigger('change');
															}
														});
													}

													// Set hidden lot_number
													$('#product').change(function(){
														var lotNumber = $('#product option:selected').attr('data-lot_number') || '';
														$('#form_lot_number').val(lotNumber);
													});

													$('#qty_use').on('input change', function(){
														validateQtyUse();
													});

													$('#form-add-result').submit(function(e){
														if (!validateQtyUse()) {
															e.preventDefault();
															alert('Qty Usage melebihi batas maksimal 110% dari SO Quantity!');
															return false;
														}
													});
												});
											</script>

											<div class="row justify-content-end">
												<div class="col-sm-8">
													<div>
														<button type="submit" class="btn btn-success w-md" name="save"><i class="bx bx-save"></i> ADD RESULT</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>

							<div class="col-lg-7">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">List Results</h4>
									</div>
									<div class="card-body p-4">
										<div class="table-responsive">
											<table class="table table-bordered dt-responsive nowrap w-100">
												<thead>
													<tr>
														<th>SO</th>
														<th>Product Info</th>
														<th>Times</th>
														<th>Lot Number</th>
														<th>Barcode End</th>
														<th>Ext Lot</th>
														<th>SO Qty</th>
														<th>Qty Usage</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
													@foreach($data_detail_production as $result)
													<tr>
														<td>{{ $result->so_number }}</td>
														<td>{{ $result->product_info }}</td>
														<td>
															<small>Start: {{ $result->start_time }}</small><br>
															<small>Finish: {{ $result->finish_time }}</small>
														</td>
														<td>{{ $result->lot_number }}</td>
														<td>{{ $result->barcode_end }}</td>
														<td>{{ $result->product }}</td>
														<td>{{ $data[0]->so_qty ?? 0 }}</td>
														<td><b>{{ $result->qty_use }}</b></td>
														<td>
															<a href="/production-entry-report-rm-aux-detail-production-result-edit/{{ Request::segment(2) }}/{{ sha1($result->id) }}" class="btn btn-outline-info btn-sm">
																<i class="bx bx-edit-alt" title="Edit"></i>
															</a>
															<form method="post" action="/production-entry-report-rm-aux-detail-production-result-delete" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus result ini?');">
																@csrf
																<input type="hidden" name="token_rra" value="{{ Request::segment(2) }}">
																<input type="hidden" name="hapus_production_result" value="{{ sha1($result->id) }}">
																<button type="submit" class="btn btn-outline-danger btn-sm">
																	<i class="bx bx-trash-alt" title="Delete"></i>
																</button>
															</form>
														</td>
													</tr>
													@endforeach
												</tbody>
												<tfoot>
													<tr class="table-light">
														<th colspan="7" class="text-end">Total Qty Usage:</th>
														<th>{{ number_format($data_detail_production->sum('qty_use'), 2) }}</th>
														<th></th>
													</tr>
												</tfoot>
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
		@endif
	</div>
</div>
@endsection
