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
        </td>
        </tr>
        <tr bgcolor="#FFFFFF">
            <td colspan="3">&nbsp;</td>
        </tr>
        </tbody>
        </table>
            @foreach ($data as $item)
        
            <table width="910" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="20%">PERIODE AKADEMIK</td>
                    {{-- <td  width="20%"> --}}
                        <td>:</td>
                        <td>{{ $item->academicPeriod->name }}</td>
                        <td ></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="20%">MATAKULIAH</td>
                    {{-- <td  width="20%"> --}}
                        <td >:</td>
                        <td >{{ $item->course->name }}</td>
                     <td  >SKS</td>
                    <td>: {{$item->course->credit_total}}</td>
                </tr>
                <tr>
                    <td width="20%" valign="top">NAMA PENGAJAR</td>
                    <td valign="top">:</td>
                    <td> 
                            @php
                                $data_dosen = \App\Models\ClassSchedule::with(['employee'])->where('college_class_id', $item->id)->groupBy('employee_id')->select('employee_id')->get();
                            @endphp
                            @foreach ($data_dosen as $dos)
                            <span>{{$dos->employee->name}} {{str_replace(',', '., ', $dos->employee->back_title) }}</span>
                            <br>
                            @endforeach
                    </td>
                    <td  >PROGRAM STUDI</td>
                    <td>: {{ $item->studyProgram->name }}</td>
                </tr>
                
            </table>
            <br>
            <table width="910" border="1" align="center" cellpadding="0" cellspacing="0">
                <tbody>
                    @php
                        $presence = \App\Models\Presence::with('student')->where('college_class_id', $item->id)->groupBy('student_id')->where('status', 'H')->select('student_id');
                        if($class_group != null){
                            $presence->whereHas('student', function ($q) use ($class_group) {
                                $q->where('class_group_id', $class_group);
                            });
                        }
                        // dd($presence->count('student_id'));
                    @endphp
                    <tr  bgcolor="#A8EEFF" >
                        <td><div align="center"><strong>No</strong></div></td>
                        <td><div align="center"><strong>NIM</strong></div></td>
                        <td><div align="center"><strong>Nama</strong></div></td>
                        {{-- <td><div align="center"><strong>Pertemuan</strong></div></td> --}}
                        <td><div align="center"><strong>Angkatan</strong></div></td>
                        <td width="10%"><div align="center"><strong>A</strong></div></td>
                        <td width="10%"><div align="center"><strong>H</strong></div></td>
                        <td width="10%"><div align="center"><strong>I</strong></div></td>
                        <td width="10%"><div align="center"><strong>S</strong></div></td>
                        <td width="10%"><div align="center"><strong>Presentase %</strong></div></td>
                    </tr>
                    @foreach ($presence->get() as $key)  
                    @php
                        $count = \App\Models\Presence::with('student')->where('college_class_id', $item->id)->where('student_id', $key->student->id)->where('status', 'H')->select('student_id')->count();
                        $countA = \App\Models\Presence::with('student')->where('college_class_id', $item->id)->where('student_id', $key->student->id)->where('status', 'A')->select('student_id')->count();
                        $countI = \App\Models\Presence::with('student')->where('college_class_id', $item->id)->where('student_id', $key->student->id)->where('status', 'I')->select('student_id')->count();
                        $countS = \App\Models\Presence::with('student')->where('college_class_id', $item->id)->where('student_id', $key->student->id)->where('status', 'S')->select('student_id')->count();
                    @endphp
                    <tr>
                        <td style="font-size: 14px;"><div align="center">{{$loop->iteration}}</div></td>
                        <td style="font-size: 14px;"><center>{{$key->student->nim}}</center></td>
                        <td style="font-size: 14px;"><center>{{$key->student->name}}</center></td>
                        {{-- <td><center>{{$item->number_of_meeting}}</center></td> --}}
                        <td style="font-size: 14px;"><center>{{$key->student->academicPeriod->academicYear->name}}</center></td>
                        <td style="font-size: 14px;"><center>{{$countA}}</center></td>
                        <td style="font-size: 14px;"><center>{{$count}}</center></td>
                        <td style="font-size: 14px;"><center>{{$countI}}</center></td>
                        <td style="font-size: 14px;"><center>{{$countS}}</center></td>
                        <td style="font-size: 14px;">
                            <center>
                            {{
                                (($count / $item->number_of_meeting) * 100)
                            }}
                            </center>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endforeach
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
