<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
<?php $this->load->view('templates/script_foundation'); ?>
<?php $this->load->view('templates/head_loggedin'); ?>
<?php $this->load->view('templates/flashmessage'); ?>
<?php $this->load->helper('url'); ?>
<body>
<?php $this->load->view('templates/topbar_loggedin'); ?>
<br>
<div class="container">
    <div class="card">
        <div class="card-header">Tambah Jadwal</div>
        <div class="card-body row">
            <div class="col-lg-4 form-group">
                <form method="POST" action="/EntriJadwalDosen/add">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                    <label>Hari</label>
                    <select class="form-control" name="hari">
                        <?php
                        $hariValue = 0;
                        foreach ($namaHari as $hari) {
                            ?>
                            <option value="<?= $hariValue ?>"> <?= $hari ?> </option>

                            <?php
                            $hariValue++;
                        }
                        ?>
                    </select><br>
                    <label>Jam Mulai</label>
                    <select class="form-control" name="jam_mulai">
                        <?php for ($i = 7; $i <= 16; $i++) { ?>
                            <option value="<?php echo $i ?>"> <?php echo $i ?>:00 </option>
                        <?php } ?>
                    </select>
            </div>
            <div class="col-lg-4 form-group">
                <label>Durasi</label>
                <select class="form-control" name="durasi">
                    <?php for ($i = 1; $i <= 9; $i++) { ?>
                        <option value="<?php echo $i ?>"> <?php echo $i ?> jam </option>
                    <?php } ?>
                </select><br>
                <label>Jenis</label>
                <select class="form-control" name="jenis_jadwal">
                    <option value="konsultasi" style="background-color:yellow"> Konsultasi </option>
                    <option value="terjadwal" style="background-color:green;color:white"> Terjadwal</option>
                    <option value="kelas" style="background-color:white"> Kelas </option>
                </select>
            </div>
            <div class="col-lg-4 form-group">
                <label>Label</label>
                <input class="form-control" type="text" name="label_jadwal"><br><br>
                <input class="btn btn-primary" type="submit" class="button" value="Tambah" class="btn btn-primary">
                </form>
            </div>
        </div>
        <!-- ===================================================================== Pembentukan Tabel ============================================================================= -->
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            Daftar Jadwal
        </div>
        <div class="card-body">
            <div id="jadwal_table">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th></th>
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                echo "<th style='width:18%'> $namaHari[$i] </th>"; //Membuat Header Tabel yang berisi daftar hari
                            }
                            ?>
                        </tr>
                        <?php
                        //GENERATE BODY UTAMA TABEL
                        $cellRowID = 1;
                        for ($i = 7; $i < 17; $i++) {
                            echo "<tr><th>" . $i . "-" . ($i + 1);
                            $cellColID = 1;
                            for ($j = 0; $j < 5; $j++) {
                                echo"<td align='center' id='cell" . $cellRowID . "-" . $cellColID . "'>" . "</td>";
                                $cellColID++;
                            }
                            $cellRowID++;
                        }
                        // MEWARNAI TABEL
                        $rowIdx = 1;
                        $spanCounter = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                        $cellRowID = 0;
                        foreach ($dataJadwal as $dataHariIni) {
                            $colIdx = $dataHariIni->hari + 1;   // + 1 karena perbedaan selisih index tabel dan value hari di database
                            $rowIdx = $dataHariIni->jam_mulai - 6;  // + 1 karena perbedaan selisih index tabel dan value jam_mulai di database
                            $border = "border border-secondary align-middle";
                            if ($dataHariIni->jenis == "konsultasi") {
                                $color = "#FEFF00";
                            } else if ($dataHariIni->jenis == "kelas") {
                                $color = "#FFFFFF";
                            } else {
                                $color = "#92D14F";
                            }
                            ?>
                            <script type="text/javascript">
                                var table = document.getElementById('jadwal_table');
                                var rows = table.getElementsByTagName('tr');
                                var $cellLocation = "#cell<?php echo $rowIdx; ?>-<?php echo $colIdx; ?>";

                                $($cellLocation).css('background-color', '<?php echo $color; ?>');
                                $($cellLocation).attr('rowspan', <?php echo $dataHariIni->durasi ?>);
                                $($cellLocation).addClass('<?php echo $border; ?>');

                                //menghapus cell-cell yang tergeser karena rowspan
                                for (i = <?php echo ($rowIdx + 1); ?>; i < <?php echo ($rowIdx + $dataHariIni->durasi); ?>; i++) {
                                    $("#cell" + i + "-" +<?php echo $colIdx; ?>).remove();
                                }
                                $($cellLocation).html("<?php echo $dataHariIni->label ?>");

                                //membuat cell-cell yang telah diwarnai jadi memunculkan pop-up untuk mengedit jadwal ketika diklik oleh mouse
                                $(document).on("click", $cellLocation, function () {
                                    var $menuName = "#edit_menu<?php echo $dataHariIni->id ?>";
                                    $($menuName).modal();
                                });
                            </script>
                            <?php
                        }
                        $cellRowID++;
                        $rowIdx++;
                        ?>
                    </table>
                </div>

            </div>
            <?php
            if($dataJadwal!=null){
                $palingBaru=$dataJadwal[0]->lastUpdate;
                foreach($dataJadwal as $perJadwal){
                    if($palingBaru<$perJadwal->lastUpdate){
                        $palingBaru=$perJadwal->lastUpdate;
                    }
                }
                $tgl = date('d',strtotime($palingBaru));
                $tgl = $tgl.' '.$namaBulan[date('m',strtotime($palingBaru)) - 1]; // minus 1 karena index dimulai dari 0
                $tgl = $tgl.' '.date('Y',strtotime($palingBaru));
                ?>
                Terakhir diupdate pada : <?=$tgl?><br>
                <?php
            }
            else{
                $palingBaru=FALSE;
                ?>
                Terakhir diupdate pada : Belum ada jadwal <br>
                <?php
            }
            ?>
            <a href="/EntriJadwalDosen/deleteAll/export/" class="btn btn-danger" onClick="return konfirmasi();">Delete All</a>
            <a href="/EntriJadwalDosen/export/" class="btn btn-primary">Ekspor ke XLS</a>
            <script>
                function konfirmasi()
                {
                    yakin = confirm("Anda yakin mau menghapus semua data jadwal? Aksi ini tidak dapat dibatalkan");
                    if(yakin!=true)
                    {
                        return false;
                    }
                }
            </script>
        </div>
    </div>


    <!--  ===================================================================== MENU EDIT JADWAL =================================================================================== -->
    <?php foreach ($dataJadwal as $dataHariIni) { ?>
    <div class="modal fade" id="edit_menu<?php echo $dataHariIni->id ?>" tabindex="-1" role="dialog" aria-labelledby="edit_menu<?php echo $dataHariIni->id ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_menu<?php echo $dataHariIni->id ?>">Edit Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="form<?php echo $dataHariIni->id ?>" method="POST" action="/EntriJadwalDosen/update/<?php echo $dataHariIni->id ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                        <input type="hidden" name="id_jadwal_parameter" value="<?php echo $dataHariIni->id ?>">
                        <div class="form-group">
                            <label>Hari</label>
                            <select class="form-control" name="hari">
                                <?php
                                $hariValue = 0;
                                foreach ($namaHari as $hari) {
                                    if ($hariValue == $dataHariIni->hari) {
                                        ?>
                                        <option value="<?= $hariValue ?>" selected> <?= $hari ?> </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?= $hariValue ?>"> <?= $hari ?> </option>
                                        <?php
                                    }
                                    $hariValue++;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jam Mulai</label>
                            <select class="form-control" name="jam_mulai">
                                <?php
                                for ($i = 7; $i <= 16; $i++) {
                                    if ($i == $dataHariIni->jam_mulai) {
                                        ?>
                                        <option value="<?php echo $i ?>" selected> <?php echo $i ?>:00 </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo $i ?>"> <?php echo $i ?>:00 </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Durasi</label>
                            <select class="form-control" name="durasi">
                                <?php
                                for ($i = 1; $i <= 9; $i++) {
                                    if ($i == $dataHariIni->durasi) {
                                        ?>
                                        <option value="<?php echo $i ?>" selected> <?php echo $i ?> jam </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo $i ?>"> <?php echo $i ?> jam </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis</label>
                            <select class="form-control" name="jenis_jadwal">
                                <option style="background-color:yellow" value="konsultasi" <?php if ($dataHariIni->jenis == 'konsultasi') echo "selected"; ?> > Konsultasi </option>
                                <option style="background-color:green" value="terjadwal" <?php if ($dataHariIni->jenis == 'terjadwal') echo "selected"; ?>> Terjadwal</option>
                                <option style="background-color:white" value="kelas" <?php if ($dataHariIni->jenis == 'kelas') echo "selected"; ?>> Kelas </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Label</label>
                            <input class="form-control" type="text" name="label_jadwal" value="<?php echo $dataHariIni->label; ?>">
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-2">
                                <input type="submit" name="submitId<?php echo $dataHariIni->id ?>" class="btn btn-primary" value="Save  ">
                    </form>
                            </div>
                            <div class="col-lg-2">
                                <form name="formDelete<?php echo $dataHariIni->id ?>" method="POST" action="/EntriJadwalDosen/delete/<?php echo $dataHariIni->id ?>">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                    <input type="submit" id="deletebtn<?php echo $dataHariIni->id ?>" name="deletebtn<?php echo $dataHariIni->id ?>" class="btn btn-danger" value="Delete">
                                </form>
                            </div>


                        </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php } ?>

</div>
<!--  =====================================================================END MENU EDIT JADWAL =================================================================================== -->
<?php $this->load->view('templates/script_foundation'); ?>

</body>
</html>																													