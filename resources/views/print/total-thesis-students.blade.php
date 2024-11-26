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
        <table width="920" border="0" align="center" cellpadding="0" cellspacing="0">
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
                                        <div align="center" class="style1"><strong>LAPORAN JUMLAH MAHASISWA TUGAS AKHIR </strong></div>
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
                                                    <td style="text-transform: capitalize">: {{ $academicPeriod }}</td>
                                                </td>
                                                <td> </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">

                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="1" cellspacing="0">
                                                            <tbody>
                                                                <tr bgcolor="#A8EEFF" id="render">
                                                                    <td width="1%">
                                                                        <div align="center"><strong>No</strong></div>
                                                                    </td>
                                                                    <td width="5%">
                                                                        <div align="center"><strong>Program Studi </strong></div>
                                                                    </td>
                                                                    <td width="4%">
                                                                        <div align="center"><strong>Angkatan</strong></div>
                                                                    </td>
                                                                    <td width="4%">
                                                                        <div align="center"><strong>Jumlah</strong></div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $i = 1;
                                                                $total = 0;
                                                                ?>
                                                                @foreach ($data as $thesis)
                                                                    @if ($loop->first || ($thesis->student->academicPeriod->academicYear->name !== $data[$loop->index - 1]->student->academicPeriod->academicYear->name) || ($thesis->student->studyProgram->name !== $data[$loop->index - 1]->student->studyProgram->name))
                                                                        <?php
                                                                        $count = $data->where('student.academicPeriod.academicYear.name', $thesis->student->academicPeriod->academicYear->name)
                                                                            ->where('student.studyProgram.name', $thesis->student->studyProgram->name)
                                                                            ->count();
                                                                        ?>
                                                                        <tr>
                                                                            <td bgcolor="#E0E0E0" rowspan="">
                                                                                <div align="center" style="font-size: 10px;">{{ $i }}</div>
                                                                            </td>
                                                                            <td bgcolor="#E0E0E0">
                                                                                <div align="center" style="font-size: 10px;">
                                                                                    {{ $thesis->student->studyProgram->educationLevel->code }} - {{ $thesis->student->studyProgram->name }}
                                                                                </div>
                                                                            </td>
                                                                            <td bgcolor="#E0E0E0">
                                                                                <div align="center" style="font-size: 10px;">
                                                                                    @if ($thesis->student && $thesis->student->academicPeriod && $thesis->student->academicPeriod->academicYear && $thesis->student->academicPeriod->academicYear->name)
                                                                                        {{ $thesis->student->academicPeriod->academicYear->name }}
                                                                                    @else
                                                                                        N/A
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                            <td bgcolor="#E0E0E0">
                                                                                <div align="center" style="font-size: 10px;">{{ $count }}</div>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @php
                                                                        $i++;
                                                                        $total = $data->count();
                                                                    @endphp
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot width="920" border="1" cellspacing="0">
                                                                <tr>
                                                                    <td colspan="3" align="center" bgcolor="#E0E0E0"  style="font-size: 10px;"><strong>Total</strong></td>
                                                                    <td align="center" bgcolor="#E0E0E0"  style="font-size: 10px;"><strong>{{ $total }}</strong></td>
                                                                </tr>
                                                            </tfoot>
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
