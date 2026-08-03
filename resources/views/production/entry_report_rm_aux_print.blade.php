<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>LAPORAN RM, Aux / Others</title>
  <style type="text/css">
    th {
      background-color: #ecf0f1 !important;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    table.table-bordered, table.table-bordered th, table.table-bordered td {
      border: 1px solid #000 !important;
    }
    table {
      border-collapse: collapse !important;
    }
    @media print {
      @page {
        size: landscape;
      }
      body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      th {
        background-color: #ecf0f1 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      table.table-bordered, table.table-bordered th, table.table-bordered td {
        border: 1px solid #000 !important;
      }
    }
  </style>
</head>

<body cz-shortcut-listen="true">
  <div class="container-fluid mt-3">
    <div class="row">
      <div class="col-md-12 text-center">
        <table border="1" cellpadding="0" cellspacing="0" style="table-layout: auto; width: 100%; border-collapse: collapse;">
          <tbody>
            <tr>
              <td rowspan="4" style="width: 20%;" class="text-center">
                <img src="{{ asset('assets/images/icon-otp.png') }}" width="60" height="60">
                <br>
                <strong><small>PT OLEFINA TIFAPLAS POLIKEMINDO</small></strong>
              </td>
            </tr>
            <tr>
              <td class="text-center font-weight-bold">FORM</td>
            </tr>
            <tr>
              <td class="text-center font-weight-bold" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">LAPORAN RM, Aux / Others</td>
            </tr>
            <tr>
              <td class="text-center"><small>FM-SM-PO EXT 02, REV 03, 22 Januari 2018</small></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-6" style="font-size: 13px;">
        <table cellpadding="2">
          <tbody>
            <tr>
              <td style="width: 110px;">Hari/Tanggal</td>
              <td>: {{ $data[0]->date }}</td>
            </tr>
            <tr>
              <td>Customer</td>
              <td>: {{ $data[0]->customer_name }}</td>
            </tr>
            <tr>
              <td>Ketua Regu</td>
              <td>: {{ $data[0]->ketua_regu_name }}</td>
            </tr>
            <tr>
              <td>Operator</td>
              <td>: {{ $data[0]->operator_name }}</td>
            </tr>
            <tr>
              <td>Regu/Shift</td>
              <td>: {{ $data[0]->shift }}</td>
            </tr>
            <tr>
              <td>Lokasi</td>
              <td>: Warehouse</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-6" style="font-size: 13px;">
        <div class="float-right" style="width: 60%;">
          <table border="1" cellpadding="2" cellspacing="0" style="width: 100%; text-align: center; border-collapse: collapse;">
            <thead>
              <tr>
                <th style="width: 50%; background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Dibuat</th>
                <th style="width: 50%; background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Diketahui</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="height: 60px; vertical-align: bottom;"></td>
                <td style="height: 60px; vertical-align: bottom;"></td>
              </tr>
              <tr>
                <td class="font-weight-bold">{{ $data[0]->operator_name }}<br><small class="font-weight-normal">( Operator )</small></td>
                <td class="font-weight-bold">{{ $data[0]->known_by_name }}<br><small class="font-weight-normal">( Pengawas )</small></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <table border="1" cellpadding="5" cellspacing="0" class="table table-bordered mt-3" style="font-size: 12px; width: 100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th colspan="2" class="text-center align-middle" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Jam Kerja</th>
          <th colspan="8" class="text-center align-middle" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Deskripsi</th>
        </tr>
        <tr>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Mul</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Sel</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">No</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Deskripsi</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Satuan</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Generated Lot Number</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">No. Work Order</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Barcode End</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">Amount</th>
          <th class="text-center" style="background-color: #ecf0f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">No Batch</th>
        </tr>
      </thead>
      <tbody>
        @php $totalAmount = 0; @endphp
        @forelse ($data_detail_production as $result)
          @php $totalAmount += $result->qty_use; @endphp
          <tr>
            <td class="text-center">{{ !empty($result->start_time) ? date('H:i:s', strtotime($result->start_time)) : '-' }}</td>
            <td class="text-center">{{ !empty($result->finish_time) ? date('H:i:s', strtotime($result->finish_time)) : '-' }}</td>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $result->product_info }}</td>
            <td class="text-center">{{ $unit_name }}</td>
            <td class="text-center">{{ $result->lot_number }}</td>
            <td class="text-center"><b>{{ $result->so_number }}</b></td>
            <td class="text-center">{{ $result->barcode_end }}</td>
            <td class="text-right">{{ number_format($result->qty_use, 0, ',', '.') }}</td>
            <td class="text-center">{{ ($result->product && trim($result->product) !== '') ? $result->product : '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="text-center">Belum Ada Data Detail Yang Ditambahkan</td>
          </tr>
        @endforelse
      </tbody>
      @if(count($data_detail_production) > 0)
      <tfoot>
        <tr>
          <td colspan="7" class="text-right font-weight-bold">Jumlah</td>
          <td class="text-center font-weight-bold">{{ $unit_name }}</td>
          <td class="text-right font-weight-bold">{{ number_format($totalAmount, 0, ',', '.') }}</td>
          <td></td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>

  <!-- Optional JavaScript -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script type="text/javascript">
    window.onload = function() {
      window.print();
    }
  </script>
</body>

</html>
