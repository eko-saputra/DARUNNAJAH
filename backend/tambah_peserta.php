    <?php
        include('includes/connection.php');
        require_once("functions/function.php");
        get_header();
        get_sidebar();
        get_bread_four();

        //Ambil Data 
        include('includes/connection.php'); 
        $query = mysqli_query($koneksi, "SELECT * FROM kelastanding ORDER BY nm_kelastanding ASC");
    ?>
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h2><i class="halflings-icon white edit"></i><span class="break"></span>Tambah Data Peserta</h2>
                            <div class="box-icon">
                                <a href="users.php" class="btn-close"><i class="halflings-icon white remove"></i></a>
                            </div>
                        </div>
                        <div class="box-content">
                            <form class="form-horizontal" method="post" action="do_tambah_peserta.php">
                                <fieldset>

                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Nama Lengkap:</label>
                                    <div class="controls">
                                    <input class="input-xlarge focused" name="nm_lengkap" id="focusedInput" type="text">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Jenis Kelamin</label>
                                    <div class="controls">
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                                        <option value="Putra">Laki-laki</option>
                                        <option value="Putri">Perempuan</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Tempat Lahir</label>
                                    <div class="controls">
                                    <input type="text" name="tpt_lahir" id="tpt_lahir" class="form-control">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Tgl Lahir </label>
                                    <div class="controls">
                                    <input type="date" name="tgl_lahir" id="tgl_lahir" value="<?php echo $today; ?>" class="form-control">
                                    <small class="form-text text-muted">Format (YYYY-MM-DD)</small>
                                    </div>
                                </div>

                                <div class="control-group">
                                <label class="control-label" for="focusedInput">Tinggi Badan</label>
                                    <div class="col-sm-9">
                                    <div class="controls">
                                        <input type="number" name="tb" id="tb" class="form-control">
                                        <span class="input-group-text">CM</span>
                                    </div>
                                </div>
                                </div>

                                <div class="control-group">
                                <label class="control-label" for="focusedInput">Berat Badan</label>
                                    <div class="col-sm-9">
                                    <div class="controls">
                                        <input type="number" name="bb" id="bb" class="form-control">
                                        <span class="input-group-text">KG</span>
                                    </div>
                                </div>
                                </div>

                                <div class="control-group">
                                <label class="control-label" for="focusedInput">Kelas</label>
                                    <div class="col-sm-9">
                                    <div class="controls">
                                        <input type="number" name="kelas" id="kelas" class="form-control">
                                    </div>
                                </div>
                                </div>

                                <div class="control-group">
                                <label class="control-label" for="focusedInput">Asal Sekolah</label>
                                    <div class="col-sm-9">
                                    <div class="controls">
                                        <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control">
                                    </div>
                                </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Golongan:</label>
                                    <div class="controls">
                                        <select class="form-control" name="golongan" id="golongan">
                                            <option value="Usia Dini 2A" >Usia Dini 2A</option>
                                            <option value="Usia Dini 2B" >Usia Dini 2B</option>
                                            <option value="Pra Remaja">Pra Remaja</option>
                                            <option value="Remaja">Remaja</option>
                                            <option value="Dewasa">Dewasa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Kelas Tanding:</label>
                                    <div class="controls">
                                        <select class="form-control" name="kelas_tanding" id="kelas_tanding">
                                            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                                                <option value="<?= $row['ID_kelastanding']; ?>">
                                                    <?= $row['nm_kelastanding']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Kontingen:</label>
                                    <div class="controls">
                                    <input class="input-xlarge focused" name="kontingen" id="focusedInput" type="text">
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                                </fieldset>
                            </form>
                        
                        </div>
                    </div><!--/span-->
                
                </div><!--/row-->
    <?php
        get_footer();
    ?>