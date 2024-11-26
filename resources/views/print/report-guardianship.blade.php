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
        <table width="1130" border="0" align="center" cellpadding="0" cellspacing="0">
            @if ($error == true)
            <br>
            <tr>
                <td width="80%">
                    <div align="center" class="style1"><strong  style="text-transform: uppercase;">
                           Data Tidak Di Temukan</strong><br>
                        <span class="style2">coba cek kembali data form yang anda masukan</span><br>
                        <span class="style2">Website : {{ $universitasProfile[0]->website }} E-Mail :
                            {{ $universitasProfile[0]->email }}</span>
                    </div>
                </td>
            </tr>
            <?php die; ?>
         @endif
            <tbody>
                <tr>
                    <td width="15%">
                        <div align="left">
                            <h2 align="center" style="margin-right: -12%;"><img src="{{ asset('storage/images/app/poliwangi.png') }}" width="133" height="124"></h2>
                        </div>
                    </td>
                    <td width="85%">
                        <div align="center"  style="margin-right: 12%;" class="style1"><strong  style="text-transform: uppercase;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN
                                TINGGI<br>
                                 {{ $universitasProfile[0]->name }}</strong><br>
                            <span class="style2">{{ $universitasProfile[0]->street}}</span><br>
                            <span class="style2">Website : {{ $universitasProfile[0]->website }} E-Mail :
                                {{ $universitasProfile[0]->email }}</span>
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
                                            <div align="center" class="style1" style=" margin-bottom: 1%;"><strong>LAPORAN PERWALIAN MAHASISWA</strong></div>
                                            {{-- <br> --}}
                                            @if ($periodAcadmic == null)
                                            <div align="center" class="style1" style="font-size: 17px;"><strong>SEMUA PERIODE AKADEMIK</strong></div>
                                            @else
                                            <div align="center" class="style1" style="font-size: 17px;"><strong>PERIODE AKADEMIK {{ $periodAcadmic->name }}</strong></div>
                                            @endif
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

                                <br>
                                <tr>
                                    <td colspan="2">

                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="1" cellspacing="0">
                                                            <tbody>
                                                                <tr bgcolor="#A8EEFF" id="render">
                                                                    <td width="5%">
                                                                        <div align="center"><strong>No</strong></div>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <div align="center"><strong>NIM</strong></div>
                                                                    </td>
                                                                    <td width="13%">
                                                                        <div align="center"><strong>Nama</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <div align="center"><strong>Program Studi</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td width="7%">
                                                                        <div align="center"><strong>Kelas</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <div align="center"><strong>Semester</strong></div>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <div align="center"><strong>SKS</strong></div>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <div align="center"><strong>IPK</strong></div>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <div align="center"><strong>IPS</strong></div>
                                                                    </td>
                                                                    <td width="26%">
                                                                        <div align="center"><strong>Dosen Wali</strong></div>
                                                                    </td>
                                                                    @if ($isAcc == true)
                                                                    <td width="17%">
                                                                        <div align="center"><strong>Status</strong></div>
                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                                @php
                                                                    $number = 1;
                                                                @endphp
                                                                @foreach ($data as $report)
                                                                <tr>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center" style="font-size: 10px;">{{ $number }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left" style="font-size: 10px;">{{ $report->student->nim }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left" style="font-size: 10px;">{{ $report->student->name }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left" style="font-size: 10px;">{{$report->student->studyProgram->educationLevel->code . ' - ' . $report->student->studyProgram->name }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left" style="font-size: 10px;">{{ $report->student->classGroup->name }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center" style="font-size: 10px;">{{ $report->credit_semester  }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center" style="font-size: 10px;">{{ $report->credit_total  }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center" style="font-size: 10px;">{{ $report->grade }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center" style="font-size: 10px;">{{ $report->grade_semester }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left" style="font-size: 10px;">{{  str_replace(',', ', ', $report->employee->front_title)  }}{{ $report->employee->name }}{{  str_replace(',', ', ', $report->employee->back_title)  }}</div>
                                                                    </td>
                                                                    @if ($isAcc == true)
                                                                    <td bgcolor="#E0E0E0">
                                                                        @if ($report->is_acc == true)
                                                                        <div align="center"  style="font-size: 10px;">{{ "Sudah" }}</div>
                                                                        @else
                                                                        <div align="center"  style="font-size: 10px;">{{ "Belum " }}</div>
                                                                        @endif
                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                                @php
                                                                    $number++;
                                                                @endphp
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
                <tr bgcolor="#FFFFFF">
                    <td colspan="3">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
    <script src="./KHS_files/jquery-3.6.0.min.js.download"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
          function Cetakan() {
                var x = document.getElementById('cetak');
                x.style.visibility = "hidden";

                var style = document.createElement('style');
                style.innerHTML = '@page { size: landscape; }';
                document.head.appendChild(style);

                window.print();
                alert("Jangan ditekan tombol OK sebelum dokumen selesai dicetak");

                x.style.visibility = "visible";
            }

            var x = document.getElementById('cetak');
            x.style.visibility = "hidden";

            var style = document.createElement('style');
            style.innerHTML = '@page { size: landscape; }';
            document.head.appendChild(style);



            setTimeout(() => {
                alert("Jangan ditekan tombol OK sebelum dokumen selesai dicetak");
                x.style.visibility = "visible";
            }, 1000);

        </script>
</body>

</html>
