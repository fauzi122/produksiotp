@extends('layouts.master')

@section('konten')
<div class="page-content">
	<div class="container-fluid">
		@csrf

		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-sm-flex align-items-center justify-content-between">
					<a href="/production-ent-report-rm-aux-detail/{{ Request::segment(3) }}" class="btn btn-dark waves-effect waves-light mb-3">
						<i class="bx bx-list-ul" title="Back"></i> BACK TO DETAIL</a>
					<div class="page-title-right">
						<ol class="breadcrumb m-0">
							<li class="breadcrumb-item"><a href="javascript: void(0);">Production</a></li>
							<li class="breadcrumb-item active"> Edit Production Result</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		@if(!empty($data[0]) && !empty($data_pr[0]))
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title"><i data-feather="check-square"></i> Edit Production Result</h4>
					</div>
					<div class="card-body p-4">
						<form id="form-edit-result" method="post" action="/production-entry-report-rm-aux-detail-production-result-edit-save" class="form-material m-t-40" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="request_id" value="{{ Request::segment(3) }}">
							<input type="hidden" name="request_id_pr" value="{{ Request::segment(4) }}">
							
							<div class="row mb-4 field-wrapper">
								<label class="col-sm-3 col-form-label">Sales Order (SO)</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" value="{{ $data_pr[0]->so_number }}" readonly>
								</div>
							</div>

							<div class="row mb-4 field-wrapper">
								<label class="col-sm-3 col-form-label">Product Info</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="product_info" id="product_info" value="{{ $data_pr[0]->product_info }}" readonly>
								</div>
							</div>

							<div class="row mb-4 field-wrapper">
								<label class="col-sm-3 col-form-label">SO Quantity</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="so_qty_display" value="{{ $data[0]->so_qty ?? 0 }}" readonly>
								</div>
							</div>

							<div class="row mb-4 field-wrapper required-field">
								<label class="col-sm-3 col-form-label">Qty Usage</label>
								<div class="col-sm-9">
									<input type="number" step="any" class="form-control" name="qty_use" id="qty_use" required min="0.01" value="{{ $data_pr[0]->qty_use ?? 0 }}">
									<div class="text-muted mt-1 small">Maksimal Qty Usage (110% SO Qty): <span id="max_qty_use_label">0</span> {{ $unit_name }} (Detail Lainnya: {{ number_format($existing_qty_other ?? 0, 2) }} {{ $unit_name }})</div>
									<div id="qty-use-warning" style="display:none; color: #f46a6a;" class="small mt-1 font-weight-semibold"></div>
								</div>
							</div>

							<div class="row mb-4 field-wrapper required-field">
								<label class="col-sm-3 col-form-label">Start Time</label>
								<div class="col-sm-9">
									<input type="datetime-local" class="form-control" name="start_time" required value="{{ date('Y-m-d\TH:i', strtotime($data_pr[0]->start_time)) }}">
								</div>
							</div>

							<div class="row mb-4 field-wrapper required-field">
								<label class="col-sm-3 col-form-label">Finish Time</label>
								<div class="col-sm-9">
									<input type="datetime-local" class="form-control" name="finish_time" required value="{{ date('Y-m-d\TH:i', strtotime($data_pr[0]->finish_time)) }}">
								</div>
							</div>

							<input type="hidden" name="lot_number" id="form_lot_number" value="{{ $data_pr[0]->lot_number }}">

							<div class="row mb-4 field-wrapper">
								<label class="col-sm-3 col-form-label">External Lot</label>
								<div class="col-sm-9">
									<select class="form-select data-select2" name="product" id="product" required>
										<option value="">** Loading External Lots... **</option>
									</select>
								</div>
							</div>

							<div class="row mb-4 field-wrapper required-field">
								<label class="col-sm-3 col-form-label">Barcode End</label>
								<div class="col-sm-9">
									<select class="form-select data-select2" name="barcode_end" id="barcode_end" required>
										<option value="">** Please Select A Barcode **</option>
										@foreach($ms_barcodes_so as $barcode)
											<option value="{{ $barcode->barcode_number }}" {{ $data_pr[0]->barcode_end == $barcode->barcode_number ? 'selected' : '' }}>
												{{ $barcode->barcode_number }}
											</option>
										@endforeach
									</select>
								</div>
							</div>

							<script>
								$(document).ready(function(){
									var typeProduct = "{{ $data[0]->type_product }}";
									var idMasterProducts = "{{ $data[0]->id_master_products }}";
									var soQty = "{{ $data[0]->so_qty ?? 0 }}";
									var unitName = "{{ $unit_name }}";
									
									var soQtyVal = parseFloat(soQty || 0);
									if (soQtyVal > 0) {
										$('#max_qty_use_label').text((soQtyVal * 1.10).toFixed(2));
									}
									var existingQtyOther = parseFloat("{{ $existing_qty_other ?? 0 }}");

									function validateQtyUse() {
										var qtyUse = parseFloat($('#qty_use').val() || 0);
										var maxQtyUse = soQtyVal * 1.10;
										var totalQtyUse = existingQtyOther + qtyUse;
										if (soQtyVal > 0 && totalQtyUse > maxQtyUse) {
											$('#qty-use-warning').html('⚠️ Total Qty Usage (' + totalQtyUse.toFixed(2) + ' ' + unitName + ') tidak boleh melebihi 110% dari SO Quantity (Maksimal: ' + maxQtyUse.toFixed(2) + ' ' + unitName + ', Detail Lainnya: ' + existingQtyOther.toFixed(2) + ' ' + unitName + ')').show();
											return false;
										} else {
											$('#qty-use-warning').hide();
											return true;
										}
									}

									// Run validation once on load
									validateQtyUse();
									var currentLotNumber = "{{ $data_pr[0]->lot_number }}";
									var currentProduct = "{{ $data_pr[0]->product }}"; // Saved External Lot

									// Fetch External Lot options (all lots of the product directly)
									if(typeProduct && idMasterProducts) {
										$.ajax({
											type: "GET",
											url: "/json_get_barcodes_by_lot",
											data: { all_product_lots: true, type_product: typeProduct, id_master_products: idMasterProducts, so_qty: soQty },
											dataType: "json",
											success: function(response) {
												var html = '<option value="">** Please Select an External Lot **</option>';
												var productFound = false;

												if (response && response.length > 0) {
													$.each(response, function(idx, val) {
														var extLotDisplay = (val.ext_lot_number && val.ext_lot_number.trim() !== '') ? val.ext_lot_number : val.lot_number;
														var lotUnit = val.unit_code || unitName;
														var selected = (extLotDisplay === currentProduct || val.ext_lot_number === currentProduct || val.lot_number === currentProduct) ? 'selected' : '';
														if(extLotDisplay === currentProduct || val.ext_lot_number === currentProduct || val.lot_number === currentProduct) {
															productFound = true;
														}
														html += '<option value="' + extLotDisplay + '" data-sisa="' + val.sisa + '" data-lot_number="' + val.lot_number + '" ' + selected + '>';
														html += extLotDisplay + ' (Lot: ' + val.lot_number + ') | ' + val.description + ' (Sisa Per EXT: ' + val.sisa + ' ' + lotUnit + ')';
														html += '</option>';
													});
												}

												// Append current selected product if it is not in the active list
												if (currentProduct && !productFound) {
													html += '<option value="' + currentProduct + '" data-sisa="0" data-lot_number="' + currentLotNumber + '" selected>';
													html += currentProduct + ' (Lot: ' + currentLotNumber + ') [Selected]';
													html += '</option>';
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

									$('#form-edit-result').submit(function(e){
										if (!validateQtyUse()) {
											e.preventDefault();
											alert('Qty Usage melebihi batas maksimal 110% dari SO Quantity!');
											return false;
										}
									});
								});
							</script>

							<div class="row justify-content-end">
								<div class="col-sm-9">
									<div>
										<a href="/production-ent-report-rm-aux-detail/{{ Request::segment(3) }}" class="btn btn-danger waves-effect waves-light"><i class="bx bx-chevron-left"></i> BACK</a>
										<button type="submit" class="btn btn-success w-md" name="save"><i class="bx bx-save"></i> UPDATE RESULT</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
@endsection
