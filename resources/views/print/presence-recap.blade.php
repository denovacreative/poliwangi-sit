
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
    @if (count($datas) == 0 )
    <br>
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
    @php
        die;
    @endphp
    @endif
    @foreach ($datas as $item)

    <form>
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td width="15%">
                        <div align="left">
                            <h2 align="center"><img src="{{ asset('storage/images/app/poliwangi.png') }}" width="133" height="124"></h2>
                        </div>
                    </td>
                    <td width="85%">
                        <div align="center" class="style1" style="margin-left: -14%"><strong  style="text-transform: uppercase;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN
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
                                        <div align="center" class="style1"><strong>LAPORAN REKAP PRESENSI MAHASISWA </strong></div>
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td><button type="button" name="cetak" id="cetak" class="print" onclick="Cetakan()"
                                        style="visibility: visible;">Cetak</button></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">PERIODE AKADEMIK</td>
                                                {{-- <td  width="20%"> --}}
                                                    <td>: {{ $item['academicPeriod'] }}</td>
                                                    <td ></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td width="20%">MATAKULIAH</td>
                                                {{-- <td  width="20%"> --}}
                                                    <td >: {{ $item['courseName'] }}</td>
                                                 <td  >SKS</td>
                                                <td>: {{ $item['credit'] }}</td>
                                            </tr>
                                            <tr>
                                                <td width="20%">NAMA PENGAJAR</td>
                                                {{-- <td  width="20%"> --}}
                                                    <td>
                                                        :
                                                        @foreach ($item['employees'] as $key => $employee)
                                                        {{ $employee['name'] }} {{str_replace(',', '., ', $employee['back_title']) }}
                                                        @if ($key < count($item['employees']) - 1 && $employee['name'] != $item['employees'][$key + 1]['name'])
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                    </td>
                                                {{-- </td> --}}
                                                <td  >PROGRAM STUDI</td>
                                                <td>: {{ $item['studyProgram'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <br>
                                <tr>
                                    <td colspan="2">
                                        <br>
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="1" cellspacing="0">
                                                            <tbody>
                                                                <tr bgcolor="#A8EEFF" id="render">
                                                                    <td width="1%">
                                                                        <div align="center"><strong>NO</strong></div>
                                                                    </td>
                                                                    <td width="2%">
                                                                        <div align="center"><strong>Nim</strong></div>
                                                                    </td>
                                                                    <td width="3%">
                                                                        <div align="center"><strong>Nama</strong></div>
                                                                    </td>
                                                                    <td width="3%">
                                                                        <div align="center"><strong>Angkatan</strong></div>
                                                                    </td>
                                                                    <td width="2%">
                                                                        <div align="center"><strong>A</strong></div>
                                                                    </td>
                                                                    {{-- <td width="2%">
                                                                        <div align="center"><strong>D</strong></div>
                                                                    </td> --}}
                                                                    <td width="2%">
                                                                        <div align="center"><strong>H</strong></div>
                                                                    </td>
                                                                    <td width="2%">
                                                                        <div align="center"><strong>I</strong></div>
                                                                    </td>
                                                                    <td width="2%">
                                                                        <div align="center"><strong>S</strong></div>
                                                                    </td>
                                                                    <td width="2%">
                                                                        <div align="center"><strong>Presentase Kehadiran %</strong></div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $i = 1;
                                                                $total = 0;
                                                                $students = []; // Membuat array untuk menyimpan nama mahasiswa yang ditampilkan
                                                                ?>
                                                                @foreach ($item['presence'] as $index => $presence)
                                                                    @if (!in_array($presence->student->name, $students))
                                                                        <?php
                                                                        $H =  \App\Models\Presence::where('student_id', $presence->student->id)->where('status', 'H')->count();
                                                                        $A =  \App\Models\Presence::where('student_id', $presence->student->id)->where('status', 'A')->count();
                                                                        // $D =  \App\Models\Presence::where('student_id', $presence->student->id)->where('status', 'D')->count();
                                                                        $I =  \App\Models\Presence::where('student_id', $presence->student->id)->where('status', 'I')->count();
                                                                        $S =  \App\Models\Presence::where('student_id', $presence->student->id)->where('status', 'I')->count();
                                                                        ?>
                                                                        <tr>
                                                                            <td style="text-align: center; font-size: 10px;">{{ $i }}</td>
                                                                            <td style="text-align: center; font-size: 10px;">{{ $presence->student->nim }}</td>
                                                                            <td style="text-align: center; font-size: 10px;">{{ $presence->student->name }}</td>
                                                                            <td style="text-align: center; font-size: 10px;">{{ $presence->student->academicPeriod->academicYear->name }}</td>
                                                                            <td style="text-align: center; font-size: 10px;">{{ $A }}</td>
                                                                            {{-- <td style="text-align: center; font-size: 10px;">{{ $D }}</td> --}}
                                                                            <td style="text-align: center; font-size: 10px;">{{ $H }}</td>
                                                                            <td style="text-align: center; font-size: 10px;">{{ $I }}</td>
                                                                            <td style="text-align: center; font-size: 10px;">{{ $S }}</td>
                                                                            <td style="text-align: center; font-size: 10px;">{{ ( $H/$item['number_of_meet']      ) * 100 }}</td>
                                                                        </tr>
                                                                        <?php
                                                                        $i++;
                                                                        array_push($students, $presence->student->name); // Menyimpan nama mahasiswa yang ditampilkan
                                                                        ?>
                                                                    @endif

                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>


                                        </td>
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
</form>
@endforeach
<script src="./KHS_files/jquery-3.6.0.min.js.download"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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
