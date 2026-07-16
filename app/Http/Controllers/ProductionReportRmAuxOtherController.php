<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use Browser;
use DataTables;

// Model
use App\Models\ProductionReportRmAuxOther;
use App\Models\ProductionReportRmAuxOtherProductionResult;

class ProductionReportRmAuxOtherController extends Controller
{
	use AuditLogsTrait;

	//START ENTRY REPORT RM AUX OTHERS
	public function production_entry_report_rm_aux()
	{
		// Web-triggered migration to add qty_use column
		try {
			if (!\Illuminate\Support\Facades\Schema::hasColumn('report_rm_aux_other_production_results', 'qty_use')) {
				\Illuminate\Support\Facades\DB::statement('ALTER TABLE report_rm_aux_other_production_results ADD COLUMN qty_use DOUBLE DEFAULT 0 AFTER product');
			}
		} catch (\Exception $e) {
			// Ignore if already exists or other database errors
		}

		//Audit Log
		$username = auth()->user()->email;
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$location = '0';
		$access_from = Browser::browserName();
		$activity = 'View List Entry Report RM, Aux, Others';
		$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

		return view('production.entry_report_rm_aux');
	}

	public function production_entry_report_rm_aux_json()
	{
		$datas = ProductionReportRmAuxOther::leftJoin('master_customers AS e', 'report_rm_aux_others.id_master_customers', '=', 'e.id')
			->leftJoin('master_employees AS f', 'report_rm_aux_others.operator', '=', 'f.id')
			->leftJoin('master_employees AS g', 'report_rm_aux_others.ketua_regu', '=', 'g.id')
			->leftJoin('sales_orders AS h', 'report_rm_aux_others.id_sales_orders', '=', 'h.id')
			->select('report_rm_aux_others.*', 'e.name AS customer_name', 'h.so_number')
			->selectRaw('f.name AS operator_name')
			->selectRaw('g.name AS ketua_regu_name')
			->orderBy('report_rm_aux_others.created_at', 'desc')
			->get();

		return DataTables::of($datas)
			->addColumn('report_info', function ($data) {
				$report_info = '<p>Report Number : <b>' . $data->report_number . '</b><br><footer class="blockquote-footer">Date : <cite>' . $data->date . '</cite></footer></p>';
				return $report_info;
			})
			->addColumn('order_info', function ($data) {
				$operator = !empty($data->operator_name) ? '<br><span class="badge bg-success-subtle text-success">Operator : ' . $data->operator_name . '</span>' : '';
				$so = !empty($data->so_number) ? '<br><code>SO : ' . $data->so_number . '</code>' : '';
				$status = empty($data->status) ? "Tidak Tersedia" : $data->status;
				$order_info = '<p><code>Customer : </code><br>' . $data->customer_name . $so . '<br><footer class="blockquote-footer">Status : <cite>' . $status . '</cite>' . $operator . '</footer></p>';
				return $order_info;
			})
			->addColumn('team', function ($data) {
				$team = '<p>Ketua Regu : ' . $data->ketua_regu_name . '<br><code>Shift : ' . $data->shift . '</code></p>';
				return $team;
			})
			->addColumn('action', function ($data) {
				$return_delete = "return confirm('Are you sure to delete this item ?')";

				$tombol = '
					<center>
				';

				if ($data->status == 'Un Posted') {
					$tombol .= '
							<a target="_blank" href="/production-ent-report-rm-aux-detail/' . sha1($data->id) . '" class="btn btn-outline-info waves-effect waves-light">
								<i class="bx bx-edit-alt" title="Edit"></i> EDIT
							</a>
							<a onclick="' . $return_delete . '" href="/production-ent-report-rm-aux-delete/' . sha1($data->id) . '" class="btn btn-outline-danger waves-effect waves-light" onclick="return confirm(' . "'Anda yakin mau menghapus item ini ?'" . ')">
								<i class="bx bx-trash-alt" title="Delete" ></i> DELETE
							</a>
						</center>
					';
				} else {
					$tombol .= '
							<span class="badge bg-success">Closed</span>
						</center>
					';
				}

				return $tombol;
			})
			->rawColumns(array("report_info", "order_info", "team", "action"))
			->make(true);
	}

	public function production_entry_report_rm_aux_add(Request $request)
	{
		$ms_ketua_regu = DB::table('master_employees')
			->select('id', 'name')
			->whereRaw("status = 'Active'")
			->get();

		$ms_operator = DB::table('master_employees')
			->select('id', 'name')
			->whereRaw("status = 'Active'")
			->get();

		$ms_known_by = DB::table('master_employees')
			->select('id', 'name')
			->whereRaw("id_master_bagians IN('3','4')")
			->get();

		$formattedCode = $this->production_entry_report_rm_aux_create_code();

		//Audit Log
		$username = auth()->user()->email;
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$location = '0';
		$access_from = Browser::browserName();
		$activity = 'Add Entry Report RM, Aux, Others';
		$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

		return view('production.entry_report_rm_aux_add', compact('ms_ketua_regu', 'ms_operator', 'ms_known_by', 'formattedCode'));
	}

	private function production_entry_report_rm_aux_create_code()
	{
		$lastCode = ProductionReportRmAuxOther::whereRaw("left(report_number,3) = 'RRA'")
			->orderBy('created_at', 'desc')
			->value(DB::raw('RIGHT(report_number, 5)'));

		$lastCode = $lastCode ? $lastCode : 0;
		$nextCode = $lastCode + 1;
		$formattedCode = 'RRA' . str_pad($nextCode, 5, '0', STR_PAD_LEFT);

		return $formattedCode;
	}

	public function production_entry_report_rm_aux_save(Request $request)
	{
		if ($request->has('savemore')) {
			return "Tombol Save & Add More diklik.";
		} elseif ($request->has('save')) {
			$pesan = [
				'date.required' => 'Cannot Be Empty',
				'id_master_customers.required' => 'Cannot Be Empty',
				'id_sales_orders.required' => 'Cannot Be Empty',
				'shift.required' => 'Cannot Be Empty',
				'id_ketua_regu.required' => 'Cannot Be Empty',
				'id_operator.required' => 'Cannot Be Empty',
				'id_known_by.required' => 'Cannot Be Empty',
			];

			$validatedData = $request->validate([
				'date' => 'required',
				'id_master_customers' => 'required',
				'id_sales_orders' => 'required',
				'shift' => 'required',
				'id_ketua_regu' => 'required',
				'id_operator' => 'required',
				'id_known_by' => 'required',
			], $pesan);

			$validatedData['report_number'] = $this->production_entry_report_rm_aux_create_code();
			$validatedData['note'] = $_POST['note'];
			$validatedData['ketua_regu'] = $_POST['id_ketua_regu'];
			$validatedData['operator'] = $_POST['id_operator'];
			$validatedData['id_cms_users'] = $_POST['id_cms_user'];
			$validatedData['known_by'] = $_POST['id_known_by'];
			$validatedData['status'] = 'Un Posted';

			unset($validatedData['id_ketua_regu']);
			unset($validatedData['id_operator']);
			unset($validatedData['id_known_by']);

			$response = ProductionReportRmAuxOther::create($validatedData);

			//Audit Log
			$username = auth()->user()->email;
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$location = '0';
			$access_from = Browser::browserName();
			$activity = 'Save Entry Report RM, Aux, Others ID="' . $response->id . '"';
			$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

			return Redirect::to('/production-ent-report-rm-aux-detail/' . sha1($response->id))->with('pesan', 'Add Successfully.');
		}
	}

	public function production_entry_report_rm_aux_detail($response_id)
	{
		$data = ProductionReportRmAuxOther::leftJoin('sales_orders as s', 'report_rm_aux_others.id_sales_orders', '=', 's.id')
			->select("report_rm_aux_others.*", "s.so_number", "s.type_product", "s.id_master_products", "s.qty as so_qty")
			->whereRaw("sha1(report_rm_aux_others.id) = '$response_id'")
			->get();

		if (!empty($data[0])) {
			if ($data[0]->status == "Un Posted") {

				$data_detail_production = DB::table('report_rm_aux_other_production_results AS a')
					->leftJoin('sales_orders AS s', 'a.id_sales_orders', '=', 's.id')
					->select('a.*', 's.so_number')
					->whereRaw("sha1(a.id_report_rm_aux_others) = '$response_id'")
					->get();

				$id_master_customers = $data[0]->id_master_customers;
				$ms_sales_orders = DB::table('sales_orders AS a')
					->select('a.*')
					->whereRaw("a.id_master_customers = '$id_master_customers'")
					->get();

				$ms_ketua_regu = DB::table('master_employees')
					->select('id', 'name')
					->whereRaw("status = 'Active'")
					->get();
				$ms_operator = DB::table('master_employees')
					->select('id', 'name')
					->whereRaw("status = 'Active'")
					->get();
				$ms_known_by = DB::table('master_employees')
					->select('id', 'name')
					->whereRaw("id_master_bagians IN('3','4')")
					->get();

				// Get SO Barcodes from barcode_detail table linked through barcodes
				$ms_barcodes_so = DB::table('barcode_detail as a')
					->join('barcodes as b', 'a.id_barcode', '=', 'b.id')
					->where('b.id_sales_orders', '=', $data[0]->id_sales_orders)
					->select('a.barcode_number')
					->get();

				//Audit Log
				$username = auth()->user()->email;
				$ipAddress = $_SERVER['REMOTE_ADDR'];
				$location = '0';
				$access_from = Browser::browserName();
				$activity = 'Detail Entry Report RM, Aux, Others ID="' . $data[0]->id . '"';
				$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

				return view('production.entry_report_rm_aux_detail', compact('data', 'ms_sales_orders', 'data_detail_production', 'ms_operator', 'ms_ketua_regu', 'ms_known_by', 'ms_barcodes_so'));
			} else {
				return Redirect::to('/production-ent-report-rm-aux')->with('pesan_danger', 'There Is An Error.');
			}
		} else {
			return Redirect::to('/production-ent-report-rm-aux')->with('pesan_danger', 'There Is An Error.');
		}
	}

	public function production_entry_report_rm_aux_update(Request $request)
	{
		if ($request->has('rra_update')) {
			$request_id = $_POST['request_id'];
			$data = ProductionReportRmAuxOther::whereRaw("sha1(report_rm_aux_others.id) = '$request_id'")
				->select('id')
				->get();

			$pesan = [
				'date.required' => 'Cannot Be Empty',
				'id_master_customers.required' => 'Cannot Be Empty',
				'id_sales_orders.required' => 'Cannot Be Empty',
				'shift.required' => 'Cannot Be Empty',
				'id_ketua_regu.required' => 'Cannot Be Empty',
				'id_operator.required' => 'Cannot Be Empty',
				'id_known_by.required' => 'Cannot Be Empty',
			];

			$validatedData = $request->validate([
				'date' => 'required',
				'id_master_customers' => 'required',
				'id_sales_orders' => 'required',
				'shift' => 'required',
				'id_ketua_regu' => 'required',
				'id_operator' => 'required',
				'id_known_by' => 'required',
			], $pesan);

			$validatedData['note'] = $_POST['note'];

			$validatedData['known_by'] = $_POST['id_known_by'];
			unset($validatedData["id_known_by"]);

			$validatedData['ketua_regu'] = $_POST['id_ketua_regu'];
			unset($validatedData["id_ketua_regu"]);

			$validatedData['operator'] = $_POST['id_operator'];
			unset($validatedData["id_operator"]);

			ProductionReportRmAuxOther::where('id', $data[0]->id)
				->update($validatedData);

			//Audit Log
			$username = auth()->user()->email;
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$location = '0';
			$access_from = Browser::browserName();
			$activity = 'Update Entry Report RM, Aux, Others ID="' . $data[0]->id . '"';
			$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

			return Redirect::to('/production-ent-report-rm-aux-detail/' . $request_id)->with('pesan', 'Update Successfuly.');
		} else {
			return Redirect::to('/production-ent-report-rm-aux')->with('pesan_danger', 'There Is An Error.');
		}
	}

	public function production_entry_report_rm_aux_detail_production_result_add(Request $request)
	{
		if ($request->has('save')) {
			$request_id = $_POST['request_id'];

			$data = ProductionReportRmAuxOther::leftJoin('sales_orders as s', 'report_rm_aux_others.id_sales_orders', '=', 's.id')
				->select('report_rm_aux_others.id', 'report_rm_aux_others.id_sales_orders', 's.qty as so_qty')
				->whereRaw("sha1(report_rm_aux_others.id) = '$request_id'")
				->first();

			if (!empty($data)) {
				$pesan = [
					'start_time.required' => 'Cannot Be Empty',
					'finish_time.required' => 'Cannot Be Empty',
					'qty_use.required' => 'Cannot Be Empty',
				];

				$validatedData = $request->validate([
					'start_time' => 'required',
					'finish_time' => 'required',
					'qty_use' => 'required|numeric|min:0.01',
				], $pesan);

				$qty_use = floatval($validatedData['qty_use']);
				$so_qty = floatval($data->so_qty ?? 0);
				$max_qty_use = $so_qty * 1.10;

				// Calculate sum of existing qty_use for this report
				$existing_qty = DB::table('report_rm_aux_other_production_results')
					->where('id_report_rm_aux_others', '=', $data->id)
					->sum('qty_use') ?? 0;

				$total_qty_use = $existing_qty + $qty_use;

				if ($so_qty > 0 && $total_qty_use > $max_qty_use) {
					return Redirect::back()->withInput()->with('pesan_danger', 'Total Qty Usage tidak boleh melebihi 110% dari SO Quantity (Maksimal Total: ' . number_format($max_qty_use, 2) . ' Kg, Sudah Terpakai: ' . number_format($existing_qty, 2) . ' Kg).');
				}

				$validatedData['id_report_rm_aux_others'] = $data->id;
				$validatedData['id_sales_orders'] = $data->id_sales_orders;
				$validatedData['product_info'] = $_POST['product_info'] ?? null;
				$validatedData['lot_number'] = $_POST['lot_number'] ?? null;
				$validatedData['barcode_end'] = $_POST['barcode_end'] ?? null;
				$validatedData['product'] = $_POST['product'] ?? null; // External Lot

				$lot_number = $validatedData['lot_number'];
				$ext_lot_number = $validatedData['product'];
				$qty_use = floatval($validatedData['qty_use']);

				if (!empty($lot_number) && !empty($ext_lot_number)) {
					$dgrnd = DB::table('detail_good_receipt_note_details as a')
						->leftJoin('good_receipt_note_details as b', 'a.id_grn_detail', '=', 'b.id')
						->where('b.lot_number', '=', $lot_number)
						->where('a.ext_lot_number', '=', $ext_lot_number)
						->select('a.id')
						->first();

					if ($dgrnd) {
						DB::table('detail_good_receipt_note_details')
							->where('id', '=', $dgrnd->id)
							->update([
								'qty_out' => DB::raw("qty_out + $qty_use")
							]);
					}
				}

				$response = ProductionReportRmAuxOtherProductionResult::create($validatedData);

				//Audit Log
				$username = auth()->user()->email;
				$ipAddress = $_SERVER['REMOTE_ADDR'];
				$location = '0';
				$access_from = Browser::browserName();
				$activity = 'Add Production Result Report RM, Aux, Others ID="' . $response->id . '"';
				$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

				return Redirect::to('/production-ent-report-rm-aux-detail/' . $request_id)->with('pesan', 'Add Production Result Successfuly.');
			} else {
				return Redirect::to('/production-ent-report-rm-aux')->with('pesan_danger', 'There Is An Error.');
			}
		}
	}

	public function production_entry_report_rm_aux_detail_production_result_edit($response_id_rra, $response_id_rra_pr)
	{
		$data = ProductionReportRmAuxOther::leftJoin('sales_orders as s', 'report_rm_aux_others.id_sales_orders', '=', 's.id')
			->select("report_rm_aux_others.*", "s.qty as so_qty")
			->whereRaw("sha1(report_rm_aux_others.id) = '$response_id_rra'")
			->get();

		$data_pr = DB::table('report_rm_aux_other_production_results AS a')
			->leftJoin('sales_orders AS s', 'a.id_sales_orders', '=', 's.id')
			->select('a.*', 's.so_number')
			->whereRaw("sha1(a.id_report_rm_aux_others) = '$response_id_rra'")
			->whereRaw("sha1(a.id) = '$response_id_rra_pr'")
			->get();

		if (!empty($data[0]) && !empty($data_pr[0])) {
			// Get SO Barcodes from barcode_detail table linked through barcodes
			$ms_barcodes_so = DB::table('barcode_detail as a')
				->join('barcodes as b', 'a.id_barcode', '=', 'b.id')
				->where('b.id_sales_orders', '=', $data[0]->id_sales_orders)
				->select('a.barcode_number')
				->get();

			// Get sum of other qty_use for this report (excluding current result)
			$existing_qty_other = DB::table('report_rm_aux_other_production_results')
				->where('id_report_rm_aux_others', '=', $data[0]->id)
				->where('id', '!=', $data_pr[0]->id)
				->sum('qty_use') ?? 0;

			return view('production.entry_report_rm_aux_detail_edit_production_result', compact('data', 'data_pr', 'ms_barcodes_so', 'existing_qty_other'));
		} else {
			return Redirect::to('/production-ent-report-rm-aux')->with('pesan_danger', 'There Is An Error.');
		}
	}

	public function production_entry_report_rm_aux_detail_production_result_edit_save(Request $request)
	{
		$request_id = $_POST['request_id'];
		$request_id_pr = $_POST['request_id_pr'];

		$data_pr = ProductionReportRmAuxOtherProductionResult::leftJoin('sales_orders as s', 'report_rm_aux_other_production_results.id_sales_orders', '=', 's.id')
			->select('report_rm_aux_other_production_results.*', 's.qty as so_qty')
			->whereRaw("sha1(report_rm_aux_other_production_results.id) = '$request_id_pr'")
			->get();

		if (!empty($data_pr[0])) {
			$pesan = [
				'start_time.required' => 'Cannot Be Empty',
				'finish_time.required' => 'Cannot Be Empty',
				'qty_use.required' => 'Cannot Be Empty',
			];

			$validatedData = $request->validate([
				'start_time' => 'required',
				'finish_time' => 'required',
				'qty_use' => 'required|numeric|min:0.01',
			], $pesan);

			$new_qty_use = floatval($validatedData['qty_use']);
			$so_qty = floatval($data_pr[0]->so_qty ?? 0);
			$max_qty_use = $so_qty * 1.10;

			// Get sum of other qty_use for this report (excluding current result)
			$existing_qty_other = DB::table('report_rm_aux_other_production_results')
				->where('id_report_rm_aux_others', '=', $data_pr[0]->id_report_rm_aux_others)
				->where('id', '!=', $data_pr[0]->id)
				->sum('qty_use') ?? 0;

			$total_qty_use = $existing_qty_other + $new_qty_use;

			if ($so_qty > 0 && $total_qty_use > $max_qty_use) {
				return Redirect::back()->withInput()->with('pesan_danger', 'Total Qty Usage tidak boleh melebihi 110% dari SO Quantity (Maksimal Total: ' . number_format($max_qty_use, 2) . ' Kg, Detail Lainnya: ' . number_format($existing_qty_other, 2) . ' Kg).');
			}

			$validatedData['product_info'] = $_POST['product_info'] ?? null;
			$validatedData['lot_number'] = $_POST['lot_number'] ?? null;
			$validatedData['barcode_end'] = $_POST['barcode_end'] ?? null;
			$validatedData['product'] = $_POST['product'] ?? null; // External Lot

			// Handle stock qty_out adjustment based on qty_use changes
			$old_lot = $data_pr[0]->lot_number;
			$old_ext_lot = $data_pr[0]->product;
			$old_qty_use = floatval($data_pr[0]->qty_use);

			$new_lot = $validatedData['lot_number'];
			$new_ext_lot = $validatedData['product'];
			$new_qty_use = floatval($validatedData['qty_use']);

			// Restore old usage
			if (!empty($old_lot) && !empty($old_ext_lot)) {
				$old_dgrnd = DB::table('detail_good_receipt_note_details as a')
					->leftJoin('good_receipt_note_details as b', 'a.id_grn_detail', '=', 'b.id')
					->where('b.lot_number', '=', $old_lot)
					->where('a.ext_lot_number', '=', $old_ext_lot)
					->select('a.id')
					->first();

				if ($old_dgrnd) {
					DB::table('detail_good_receipt_note_details')
						->where('id', '=', $old_dgrnd->id)
						->update([
							'qty_out' => DB::raw("qty_out - $old_qty_use")
						]);
				}
			}

			// Apply new usage
			if (!empty($new_lot) && !empty($new_ext_lot)) {
				$new_dgrnd = DB::table('detail_good_receipt_note_details as a')
					->leftJoin('good_receipt_note_details as b', 'a.id_grn_detail', '=', 'b.id')
					->where('b.lot_number', '=', $new_lot)
					->where('a.ext_lot_number', '=', $new_ext_lot)
					->select('a.id')
					->first();

				if ($new_dgrnd) {
					DB::table('detail_good_receipt_note_details')
						->where('id', '=', $new_dgrnd->id)
						->update([
							'qty_out' => DB::raw("qty_out + $new_qty_use")
						]);
				}
			}

			ProductionReportRmAuxOtherProductionResult::where('id', $data_pr[0]->id)
				->update($validatedData);

			//Audit Log
			$username = auth()->user()->email;
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$location = '0';
			$access_from = Browser::browserName();
			$activity = 'Edit Production Result Report RM, Aux, Others ID="' . $data_pr[0]->id . '"';
			$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

			return Redirect::to('/production-ent-report-rm-aux-detail/' . $request_id)->with('pesan', 'Edit Production Result Successfuly.');
		} else {
			return Redirect::to('/production-ent-report-rm-aux-detail/' . $request_id)->with('pesan_danger', 'There Is An Error.');
		}
	}

	public function production_entry_report_rm_aux_detail_production_result_delete(Request $request)
	{
		$id_rra = $_POST['token_rra'];
		$id = $_POST['hapus_production_result'];

		$data = ProductionReportRmAuxOtherProductionResult::select("*")
			->whereRaw("sha1(id) = '$id'")
			->get();

		if (!empty($data[0])) {
			// Subtract qty_use from stock qty_out before deleting
			$lot_number = $data[0]->lot_number;
			$ext_lot = $data[0]->product;
			$qty_use = floatval($data[0]->qty_use);

			if (!empty($lot_number) && !empty($ext_lot)) {
				$dgrnd = DB::table('detail_good_receipt_note_details as a')
					->leftJoin('good_receipt_note_details as b', 'a.id_grn_detail', '=', 'b.id')
					->where('b.lot_number', '=', $lot_number)
					->where('a.ext_lot_number', '=', $ext_lot)
					->select('a.id')
					->first();

				if ($dgrnd) {
					DB::table('detail_good_receipt_note_details')
						->where('id', '=', $dgrnd->id)
						->update([
							'qty_out' => DB::raw("qty_out - $qty_use")
						]);
				}
			}

			$delete = ProductionReportRmAuxOtherProductionResult::whereRaw("sha1(id) = '$id'")->delete();

			if ($delete) {
				//Audit Log
				$username = auth()->user()->email;
				$ipAddress = $_SERVER['REMOTE_ADDR'];
				$location = '0';
				$access_from = Browser::browserName();
				$activity = 'Delete Production Result Report RM, Aux, Others ID="' . $data[0]->id . '"';
				$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

				return Redirect::to('/production-ent-report-rm-aux-detail/' . $id_rra)->with('pesan', 'Delete Successfuly.');
			} else {
				return Redirect::to('/production-ent-report-rm-aux-detail/' . $id_rra)->with('pesan_danger', 'There Is An Error.');
			}
		} else {
			return Redirect::to('/production-ent-report-rm-aux-detail/' . $id_rra)->with('pesan_danger', 'There Is An Error.');
		}
	}

	public function production_entry_report_rm_aux_delete($response_id)
	{
		$data = ProductionReportRmAuxOther::whereRaw("sha1(report_rm_aux_others.id) = '$response_id'")
			->select('id')
			->get();

		if (!empty($data[0])) {
			// Get all production results to adjust stock qty_out before deleting
			$results = DB::table('report_rm_aux_other_production_results')
				->where('id_report_rm_aux_others', '=', $data[0]->id)
				->get();

			foreach ($results as $result) {
				if (!empty($result->lot_number) && !empty($result->product)) {
					$dgrnd = DB::table('detail_good_receipt_note_details as a')
						->leftJoin('good_receipt_note_details as b', 'a.id_grn_detail', '=', 'b.id')
						->where('b.lot_number', '=', $result->lot_number)
						->where('a.ext_lot_number', '=', $result->product)
						->select('a.id')
						->first();

					if ($dgrnd) {
						DB::table('detail_good_receipt_note_details')
							->where('id', '=', $dgrnd->id)
							->update([
								'qty_out' => DB::raw("qty_out - " . floatval($result->qty_use))
							]);
					}
				}
			}

			// Delete production results first
			ProductionReportRmAuxOtherProductionResult::where('id_report_rm_aux_others', $data[0]->id)->delete();

			// Delete report
			ProductionReportRmAuxOther::where('id', $data[0]->id)->delete();

			//Audit Log
			$username = auth()->user()->email;
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$location = '0';
			$access_from = Browser::browserName();
			$activity = 'Delete Entry Report RM, Aux, Others ID="' . $data[0]->id . '"';
			$this->auditLogs($username, $ipAddress, $location, $access_from, $activity);

			return Redirect::to('/production-ent-report-rm-aux')->with('pesan', 'Delete Successfuly.');
		} else {
			return Redirect::to('/production-ent-report-rm-aux')->with('pesan_danger', 'There Is An Error.');
		}
	}

	// AJAX endpoint for Sales Orders Select2
	public function jsonGetSalesOrdersRmAux()
	{
		$search = request('search', '');
		$page = request('page', 1);
		$perPage = 10;
		$id_master_customers = request('id_master_customers', '');

		$query = DB::table('sales_orders AS a')
			->leftJoin('master_customers AS c', 'a.id_master_customers', '=', 'c.id')
			->select('a.id', 'a.so_number', 'a.type_product', 'a.id_master_products', 'c.id AS id_master_customers');

		if (!empty($id_master_customers)) {
			$query->where('a.id_master_customers', '=', $id_master_customers);
		}

		if (!empty($search)) {
			$query->where('a.so_number', 'LIKE', "%{$search}%");
		}

		$total = $query->count();
		$results = $query->skip(($page - 1) * $perPage)
			->take($perPage)
			->get();

		return response()->json([
			'results' => $results->map(function ($item) {
				return [
					'id' => $item->id,
					'text' => $item->so_number,
					'type_product' => $item->type_product,
					'id_master_products' => $item->id_master_products,
					'id_master_customers' => $item->id_master_customers
				];
			}),
			'pagination' => [
				'more' => ($page * $perPage) < $total
			]
		]);
	}

	public function jsonGetGlnByProduct()
	{
		$type_product = request('type_product');
		$id_master_products = request('id_master_products');

		$datas = DB::table('good_receipt_note_details')
			->where('type_product', '=', $type_product)
			->where('id_master_products', '=', $id_master_products)
			->where('qc_passed', '=', 'Y')
			->whereNotNull('lot_number')
			->where('lot_number', '!=', '')
			->where('lot_number', '!=', '0')
			->select('lot_number')
			->distinct()
			->get();

		return response()->json($datas);
	}

	public function jsonGetBarcodesByLot()
	{
		$lot_number = request('lot_number');
		$so_qty = request('so_qty', 0);
		$type_product = request('type_product');
		$id_master_products = request('id_master_products');
		$all_product_lots = request('all_product_lots', false);

		$query = DB::table('detail_good_receipt_note_details as a')
			->leftJoin('good_receipt_note_details as b', 'a.id_grn_detail', '=', 'b.id')
			->leftJoin('master_raw_materials as c', 'b.id_master_products', '=', 'c.id')
			->whereRaw("a.qty > a.qty_out");

		if ($all_product_lots || $all_product_lots === "true") {
			$query->where('b.type_product', '=', $type_product)
				->where('b.id_master_products', '=', $id_master_products);
		} else {
			$query->where('b.lot_number', '=', $lot_number);
		}

		$datas = $query->select('c.description', 'a.id', 'b.lot_number', 'a.ext_lot_number')
			->selectRaw('ROUND(a.qty-a.qty_out, 1) as sisa')
			->get();

		return response()->json($datas);
	}
	//END ENTRY REPORT RM AUX OTHERS
}
