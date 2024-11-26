<!-- saved from url=(0100)https://sit.poliwangi.ac.id/admin/kuliah/tampilkan-khs-per-mahasiswa/27838/direktur/tampilkan/2020/2 -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        .style1 {
            font-size: large
        }

        .style2 {
            font-size: medium
        }
    </style>
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ asset('storage/images/app/poliwangi.png') }}" type="image/x-icon">
    <style type="text/css">
        @font-face {
            font-weight: 400;
            font-style: normal;
            font-family: circular;

            src: url('chrome-extension://liecbddmkiiihnedobmlmillhodjkdmb/fonts/CircularXXWeb-Book.woff2') format('woff2');
        }

        @font-face {
            font-weight: 700;
            font-style: normal;
            font-family: circular;

            src: url('chrome-extension://liecbddmkiiihnedobmlmillhodjkdmb/fonts/CircularXXWeb-Bold.woff2') format('woff2');
        }
    </style>
</head>

<body>
    <form>
        @php
            // dd($data);
        @endphp
        @if (count($data) > 0)
        <table width="910" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td width="15%">
                        <div align="left">
                            <h2 align="center"><img src="{{ asset('storage/images/app/poliwangi.png') }}" width="133"
                                    height="124"></h2>
                        </div>
                    </td>
                    <td width="85%">
                        <div align="center" class="style1" style="margin-left: -14%"><strong
                                style="text-transform: uppercase;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN
                                TINGGI<br>
                                {{ $universitasProfile->name }} </strong><br>
                            <span class="style2">{{ $universitasProfile->street }}</span><br>
                            <span class="style2">Website : {{ $universitasProfile->website }} E-Mail :
                                {{ $universitasProfile->email }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr noshade="">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div align="center" class="style1"><strong>PRESENTASE KEHADIRAN MAHASISWA
                                            </strong></div>
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td><button type="button" name="cetak" id="cetak" class="print"
                                            onclick="Cetakan()" style="visibility: visible;">Cetak</button></td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        
            <table width="910" border="1" align="center" cellpadding="0" cellspacing="0">
            
                <thead bgcolor="#A8EEFF">
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>SKS</th>
                        <th>Kelas</th>
                        <th>Program Studi</th>
                        <th>Pengajar</th>
                        <th>Jadwal</th>
                        <th>Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item) 
                    <tr style="text-align: center; font-size: 12px;">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->course->code}}</td>
                        <td>{{$item->course->name}}</td>
                        <td>{{$item->credit_total}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->studyProgram->name}}</td>
                        @php
                            $teaching = \App\Models\TeachingLecturer::with(['weeklySchedule','employee'])->where('college_class_id', $item->id);
                            if (isset($employee) && $employee != '' && $employee != 'all') {
                                $teaching->where('employee_id', $employee);
                            }
                        @endphp
                        <td style="text-align: left;">
                            @foreach ($teaching->get() as $val)
                                <li style="list-style: none; padding-left: 5px;">{{$val->employee->name}}</li>
                            @endforeach
                        </td>
                        <td style="text-align: left;">
                            @foreach ($teaching->get() as $val)
                                <li style="list-style: none; padding-left: 5px;">{{(isset($val->weeklySchedule->time_start) ? ($val->weeklySchedule->time_start . ' - ' . $val->weeklySchedule->time_end) : 'Belum Ada Jadwal')}}</li>
                            @endforeach
                        </td>
                        <td>{{$item->capacity}}</td>
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
        @else
        <table width="910" border="1" align="center" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td width="80%">
                        <div align="center" class="style1"><strong  style="text-transform: uppercase;">
                               Data Tidak Di Temukan</strong><br>
                            <span class="style2">coba cek kembali data form yang anda masukan</span><br>
                            <span class="style2">Website : {{ $universitasProfile->website }} E-Mail :
                                {{ $universitasProfile->email }}</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        @endif
    </form>
    <script src="./KHS_files/jquery-3.6.0.min.js.download" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script>
        function Cetakan() {
            var x = document.getElementsByName("cetak");
            for (i = 0; i < x.length; i++) {
                x[i].style.visibility = "hidden";
            }
            alert("Jangan di tekan tombol OK sebelum dokumen selesai tercetak!");
            window.print();
            for (i = 0; i < x.length; i++) {
                x[i].style.visibility = "visible";
            }
        }
    </script>
</body>

</html>
