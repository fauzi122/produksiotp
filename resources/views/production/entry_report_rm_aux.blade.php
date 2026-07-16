@extends('layouts.master')

@section('konten')

<div class="page-content">
        <div class="container-fluid">
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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18"> Report RM, Aux, Others</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Production</a></li>
                                <li class="breadcrumb-item active"> Report RM, Aux, Others</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="/production-ent-report-rm-aux-add" class="btn btn-success waves-effect waves-light">
										<i class="bx bx-plus" title="Add Data" ></i>
										ADD
									</a>                                   
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dt-responsive nowrap w-100 datatable-rra-json">
                                    <thead>
                                        <tr>
                                            <th width='25%'>Report Info</th>
                                            <th width='35%'>Order Info</th>
                                            <th width='25%'>Team Info</th>
                                            <th width='15%'>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
								<script type="text/javascript">
								  $(function () {
									var table = $('.datatable-rra-json').DataTable({
										processing: true,
										serverSide: true,
										ajax: '/production-ent-report-rm-aux-json',
										columns: [
											{data: 'report_info', name: 'report_info', orderable: true, searchable: true},
											{data: 'order_info', name: 'order_info', orderable: true, searchable: true},
											{data: 'team', name: 'team', orderable: true, searchable: true},
											{data: 'action', name: 'action', orderable: false, searchable: false},
										],
										aaSorting: [
											[0, 'desc']
										],
									});
								  });
								</script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
