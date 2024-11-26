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
        <table width="910" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td width="15%">
                        <div align="left">
                            <h2 align="center"><img src="{{ asset('storage/images/app/poliwangi.png') }}" width="133" height="124"></h2>
                        </div>
                    </td>
                    <td width="85%">
                        <div align="center" class="style1"><strong  style="text-transform: uppercase;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN
                                TINGGI<br>
                                {{ $nama_univ }} </strong><br>
                            <span class="style2">{{ $alamat_univ }}</span><br>
                            <span class="style2">Website : {{ $website_univ }} E-Mail :
                                {{ $email_univ }}</span>
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
                                        <div align="center" class="style1"><strong>KARTU HASIL STUDI </strong></div>
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
                                                <td width="10%">NOMOR</td>
                                                <td >: -</td>
                                                <td  width="20%">
                                                    PROGRAM STUDI
                                                </td>
                                                <td>: {{ $study_program }}</td>
                                            </tr>
                                            <tr>
                                                <td>NIM</td>
                                                <td>: {{ $nim }}</td>
                                                <td > THN / SEMESTER</td>
                                                <td >: {{ $academic_periode_name }}</td>
                                            </tr>
                                            <tr>
                                                <td >
                                                   NAMA
                                                </td>
                                                <td>: {{ $name }}</td>
                                                <td>KELAS</td>
                                                <td>: {{ $class_group }}</td>
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
                                                                    <td width="5%">
                                                                        <div align="center"><strong>No</strong></div>
                                                                    </td>
                                                                    <td width="14%">
                                                                        <div align="center"><strong>Kode</strong></div>
                                                                    </td>
                                                                    <td width="40%">
                                                                        <div align="center"><strong>Mata Kuliah</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <div align="center"><strong>HM</strong></div>
                                                                    </td>
                                                                    <td width="11%">
                                                                        <div align="center"><strong>AM</strong></div>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <div align="center"><strong>K</strong></div>
                                                                    </td>
                                                                    <td width="11%">
                                                                        <div align="center"><strong>M</strong></div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $i=1;
                                                                $mutu = 0;
                                                                $credit = 0;
                                                                ?>
                                                                @foreach ($datas as $khs)
                                                                <?php
                                                                $credit += $khs->credit_total;
                                                                $mutu += $khs->index_score * $khs->credit_total;
                                                                ?>
                                                                <tr>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $i }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->code }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left">{{ $khs->name }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->final_grade }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->index_score }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->credit_total }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->index_score * $khs->credit_total }}</div>
                                                                    </td>
                                                                </tr>
                                                                <?php $i++; ?>
                                                                @endforeach

                                                                <tr bgcolor="#0099FF">
                                                                    <td colspan="5">
                                                                        <div align="center"><strong>Jumlah</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div align="center" id="jumlahK">{{ $credit }}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div align="center" id="jumlahM">{{ $mutu }}</div>
                                                                    </td>
                                                                </tr>

                                                                <tr bgcolor="#0099FF">
                                                                    <td colspan="5">
                                                                        <div align="center"><strong>Indeks Prestasi
                                                                                Semester
                                                                                (IPS)</strong> </div>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <div align="center" id="mk">{{ number_format($mutu / $credit, 2) }}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr bgcolor="#0099FF">
                                                                    <td colspan="5">
                                                                        <div align="center"><strong>Indeks Prestasi
                                                                                Kumulatif
                                                                                (IPK)</strong> </div>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <div align="center" id="mk">{{ number_format($total_mutu / $total_credit, 2) }}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr valign="top" bgcolor="#0099FF">
                                                                    <td height="60" colspan="7">
                                                                        <p>Catatan :</p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td height="19" colspan="2">Keterangan : </td>
                                                                    <td width="456">Banyuwangi, {{ date('Y-m-d'); }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="32">HM</td>
                                                                    <td width="412" height="19"> = Huruf Mutu </td>
                                                                    <td style="text-transform: uppercase;">
                                                                        KETUA JURUSAN  {{ $major }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>AM</td>
                                                                    <td height="19"> = Angka Mutu </td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>K</td>
                                                                    <td height="19"> = Kredit </td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>M</td>
                                                                    <td height="19"> = Mutu </td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>
                                                                        <br>
                                                                      {{ $ketua_jurusan->front_title == null ? '' : $ketua_jurusan->front_title .' '}}{{ $ketua_jurusan->name }}, {{  str_replace(',', '., ', $ketua_jurusan->back_title); }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>

                                                                    <td>NIP.  {{ $ketua_jurusan->nip }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>DOSEN WALI </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>
                                                                        <br>
                                                                      {{  $employee }}.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>NIP. {{ $nip }}</td>
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
