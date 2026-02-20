// server.js
const WebSocket = require("ws");
const mysql = require("mysql2");
const { DateTime } = require("luxon");

// Buat server WebSocket
const wss = new WebSocket.Server({ port: 3000 }, () => {
  console.log("🚀 WebSocket Server running on ws://localhost:3000");
});

let currentPartai = null;
let currentBabak = 1; // default awal

// Fungsi untuk mendapatkan waktu sekarang (UTC+7)
function getNow() {
  return new Date(Date.now() + 7 * 60 * 60 * 1000);
}

function broadcast(data) {
  wss.clients.forEach((client) => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(JSON.stringify(data));
    }
  });
}

let db;

// Jalankan server
(() => {
  // Koneksi ke database menggunakan mysql2 (callback style)
  db = mysql.createConnection({
    host: "localhost",
    user: "skordigital",
    password: "skordigital",
    database: "skordigital",
  });

  db.connect((err) => {
    if (err) {
      console.error("❌ Gagal koneksi ke MySQL:", err);
      process.exit(1);
    }
    console.log("✅ Terkoneksi ke MySQL.");

    // Tangani koneksi WebSocket
    wss.on("connection", (ws) => {
      console.log("🟢 Client connected");

      ws.on("message", (data) => {
        try {
          const payload = JSON.parse(data);
          const { type } = payload;

          switch (type) {
            case "set_partai":
              console.log(payload);
              handleSetPartai(payload);
              sendHistoryDewan(ws, payload.partai, payload.babak, payload.bbk);
              getNilaiMonitor(ws, payload.partai, payload.bbk);
              HistoryNilaiJuriPemenang(db, payload.partai);
              break;

            case "dataPartai":
              console.log(payload);
              const { kelas, tanggal, semuaPartai } = payload;

              // Ambil max ID dari kedua tabel terpisah
              const getMaxIdsQuery = `
    SELECT 
      (SELECT MAX(id_partai) FROM jadwal_tanding_log) AS max_semifinal,
      (SELECT MAX(id_partai) FROM jadwal_tanding_final_log) AS max_final
  `;

              db.query(getMaxIdsQuery, (err, result) => {
                if (err) {
                  console.error("❌ Gagal ambil ID maksimal:", err);
                  return;
                }

                const row = result[0];
                let lastSemiId = row.max_semifinal || 0;
                let lastFinalId = row.max_final || 0;

                semuaPartai.forEach((partai) => {
                  const {
                    bagan_id,
                    babak,
                    bagan,
                    nm_biru,
                    kontingen_biru,
                    nm_merah,
                    kontingen_merah,
                  } = partai;

                  const values = [
                    0, // id_partai akan di-set sesuai babak
                    tanggal,
                    kelas,
                    "A", // gelanggang
                    "0", // partai no (akan di-set juga)

                    nm_biru || "-",
                    kontingen_biru || "-",

                    nm_merah || "-",
                    kontingen_merah || "-",

                    babak || null,
                    bagan_id || null,
                    bagan || null,
                  ];

                  let sql;
                  if (babak === "FINAL") {
                    lastFinalId += 1;
                    values[0] = lastFinalId;
                    values[4] = `${lastFinalId}`; // partai no
                    sql = `
          INSERT IGNORE INTO jadwal_tanding_final_log (
            id_partai, tgl, kelas, gelanggang, partai,
            nm_biru, kontingen_biru,
            nm_merah, kontingen_merah,
            status, skor_biru, skor_merah, pemenang,
            babak, id_bagan, bagan, medali, aktif, grup
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '-', NULL, NULL, '-', ?, ?, ?, '0', '0', NULL)
        `;
                  } else {
                    lastSemiId += 1;
                    values[0] = lastSemiId;
                    values[4] = `${lastSemiId}`; // partai no
                    sql = `
          INSERT IGNORE INTO jadwal_tanding_log (
            id_partai, tgl, kelas, gelanggang, partai,
            nm_biru, kontingen_biru,
            nm_merah, kontingen_merah,
            status, skor_biru, skor_merah, pemenang,
            babak, id_bagan, bagan, medali, aktif, grup
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '-', NULL, NULL, '-', ?, ?, ?, '0', '0', NULL)
        `;
                  }

                  db.query(sql, values, (err, result) => {
                    if (err) {
                      console.error(`❌ Gagal simpan partai ${babak}:`, err);
                    } else {
                      console.log(
                        `✅ Partai ${babak} (${values[0]}) disimpan ke ${babak === "FINAL"
                          ? "jadwal_tanding_final"
                          : "jadwal_tanding"
                        }.`
                      );
                    }
                  });
                });
              });
              break;

            case "selectKelas":
              // console.log(payload);
              const sqlpeserta = `
        SELECT peserta.nm_lengkap as nama,peserta.kontingen,kelastanding.nm_kelastanding,peserta.golongan,peserta.jenis_kelamin FROM peserta
INNER JOIN kelastanding 
    ON kelastanding.ID_kelastanding = peserta.kelas_tanding_FK
WHERE peserta.golongan = '${payload.golongan}'
  AND peserta.jenis_kelamin = '${payload.kategori}'
  AND peserta.kelas_tanding_FK = '${payload.kelas}'
ORDER BY peserta.nm_lengkap ASC;

    `;

              db.query(sqlpeserta, (error, results) => {
                if (error) {
                  broadcast({
                    type: "error",
                    message: "Database error",
                  });
                  return;
                }

                if (!results || results.length === 0) {
                  // Jika tidak ada data, kirim pesan khusus tapi jangan tutup koneksi
                  broadcast({
                    type: "info",
                    message: "Data peserta tidak ditemukan",
                  });
                  return;
                }

                const first = results[0]; // Ambil peserta pertama jika ada
                const kls = `${payload.golongan} ${payload.kategori} ${first.nm_kelastanding}`;

                console.log(first);
                broadcast({
                  type: "baganData",
                  kelas: kls,
                  peserta: results,
                });
              });
              break;

            case "history_pemenang":
              HistoryNilaiJuriPemenang(db, payload.partai);
              getNilaiMonitor(ws, payload.partai);
              break;

            case "history_pemenang":
              HistoryNilaiJuriPemenang(db, payload.partai);
              getNilaiMonitor(ws, payload.partai);
              break;

            case "set_jumlah_babak":
              console.log(payload);
              wss.clients.forEach(function each(client) {
                if (client.readyState === WebSocket.OPEN) {
                  client.send(JSON.stringify(payload));
                }
              });
              break;

            case "tukar_partai":
              handleTukarPartai();
              sendHistoryDewan(ws, 0);
              break;

            case "tukar_partai_tunggal":
              handleTukarPartaiTunggal();
              // sendHistoryDewan(ws, 0);
              break;

            case "simpan_data_seni_tunggal":
              simpan_nilai_seni_tunggal(db, payload);
              break;

            case "simpan_data_seni_regu":
              simpan_nilai_seni_regu(db, payload);
              break;

            case "penalty_add_tunggal":
              console.log(payload);
              simpan_nilai_seni_tunggal_dewan(db, payload);
              break;

            case "penalty_add_regu":
              console.log(payload);
              simpan_nilai_seni_regu_dewan(db, payload);
              break;

            case "ambil_nilai_terkini":
              console.log(payload);
              const sqlnilai = "SELECT * FROM nilai_seni_tunggal WHERE id_jadwal = ? AND sudut = ? AND id_juri = ?";
              db.query(sqlnilai, [payload.id_jadwal, payload.sudut, payload.juri], function (err, result) {
                if (err) {
                  console.error("❌ Gagal mengambil nilai terkini:", err);
                  return;
                }

                console.log("✅ Nilai terkini berhasil diambil:", result);

                ws.send(
                  JSON.stringify({
                    type: "ambil_nilai_terkini_success",
                    data: result,
                  })
                );
              });
              break;

            case "partai_finish":
              console.log(payload);
              const id_partai = payload.partai;

              // Update status ke "SELESAI" di tabel jadwal_tgr
              const sql = "UPDATE jadwal_tgr SET status = ? WHERE partai = ?";
              db.query(sql, ["selesai", id_partai], function (err, result) {
                if (err) {
                  console.error("❌ Gagal update status partai:", err);
                  return;
                }

                console.log(
                  `✅ Partai ${id_partai} berhasil di-set ke SELESAI`
                );

                // Kirim respons ke client (opsional)
                ws.send(
                  JSON.stringify({
                    type: "status_update_success",
                    partai: id_partai,
                    status: "SELESAI",
                  })
                );
              });
              break;

            case "partai_finish_regu":
              console.log(payload);
              const id_partai_regu = payload.partai_regu;

              // Update status ke "SELESAI" di tabel jadwal_tgr
              const sql1 = "UPDATE jadwal_tgr SET status = ? WHERE partai = ?";
              db.query(
                sql1,
                ["selesai", id_partai_regu],
                function (err, result) {
                  if (err) {
                    console.error("❌ Gagal update status partai:", err);
                    return;
                  }

                  console.log(
                    `✅ Partai ${id_partai_regu} berhasil di-set ke SELESAI`
                  );

                  // Kirim respons ke client (opsional)
                  ws.send(
                    JSON.stringify({
                      type: "status_update_success_regu",
                      partai: id_partai_regu,
                      status: "SELESAI",
                    })
                  );
                }
              );
              break;

            case "penalty_remove_tunggal":
              console.log(payload);
              clear_nilai_seni_tunggal_dewan(db, payload);
              break;

            case "penalty_remove_regu":
              console.log(payload);
              clear_nilai_seni_regu_dewan(db, payload);
              break;

            case "start":
            case "pause":
            case "resume":
            case "stop":
            case "set_round":
              // console.log("tes");
              handleTimer(ws, type, payload);
              // sendHistoryDewan(ws, payload.partai, payload.babak, payload.bbk);
              // getNilaiMonitor(ws, 1, 'SEMIFINAL'); // refresh monitor
              break;

            case "nilai":
              handleNilai(ws, payload);
              break;

            case "selesai_seni":
              console.log("selesai");
              const { partai, sudut } = payload;

              // Query ambil semua nilai jurus dan stamina
              const sqlNilai =
                "SELECT * FROM nilai_seni_tunggal WHERE id_jadwal = ? AND sudut = ?";
              // Query ambil penalty dari nilai_dewan_seni_tunggal
              const sqlPenalty =
                "SELECT * FROM nilai_dewan_seni_tunggal WHERE id_jadwal = ? AND sudut = ?";

              // Ambil nilai seni tunggal
              db.query(sqlNilai, [partai, sudut], (err, rows) => {
                if (err) {
                  console.error("Gagal ambil data nilai:", err.message);
                  return;
                }

                // Ambil penalty
                db.query(sqlPenalty, [partai, sudut], (err2, penaltyRows) => {
                  if (err2) {
                    console.error("Gagal ambil data penalty:", err2.message);
                    return;
                  }

                  // Hitung penalty total (jumlah hukum_1 s/d hukum_5)
                  let penaltyTotal = 0;
                  if (penaltyRows.length > 0) {
                    const p = penaltyRows[0]; // ambil record pertama (asumsi 1 row per jadwal + sudut)
                    penaltyTotal =
                      (parseFloat(p.hukum_1) || 0) +
                      (parseFloat(p.hukum_2) || 0) +
                      (parseFloat(p.hukum_3) || 0) +
                      (parseFloat(p.hukum_4) || 0) +
                      (parseFloat(p.hukum_5) || 0);
                  }

                  const rekap = rows.map((row) => {
                    let totalJurus = 0;
                    for (let i = 1; i <= 14; i++) {
                      totalJurus += parseFloat(row[`jurus${i}`] || 0);
                    }

                    // const rataJurus = totalJurus / 14;
                    const rataJurus = 9.9 - totalJurus;
                    console.log(rataJurus);
                    console.log(totalJurus);
                    const stamina = parseFloat(row.stamina || 0);
                    const total = parseFloat((rataJurus + stamina).toFixed(2));

                    return {
                      id_juri: row.id_juri,
                      rata_rata_jurus: parseFloat(rataJurus.toFixed(2)),
                      stamina,
                      total,
                    };
                  });

                  console.log(penaltyTotal);

                  // Siapkan data untuk dikirim ke client, termasuk penaltyTotal
                  const dataBroadcast = {
                    type: "broadcast_selesai_seni",
                    data: {
                      partai,
                      sudut,
                      rekap_nilai: rekap,
                      penalty: penaltyTotal,
                    },
                  };

                  // Kirim ke semua client WebSocket
                  wss.clients.forEach(function each(client) {
                    if (client.readyState === WebSocket.OPEN) {
                      client.send(JSON.stringify(dataBroadcast));
                    }
                  });
                });
              });

              break;

            case "nilai_dewan":
              handleNilaiDewan(ws, payload);
              sendHistoryDewan(ws, payload.partai, payload.bbk);
              break;

            case "history_dewan":
              console.log("bbk : ", payload.bbk);
              sendHistoryDewan(ws, payload.partai, payload.babak, payload.bbk);
              break;

            case "get_nilai_monitor":
              getNilaiMonitor(ws, payload.partai, payload.bbk);
              break;

            case "get_history_nilai_juri":
              handleHistoryNilai(db, payload.partai, payload.juri, payload.bbk);
              break;

            case "hapus_nilai":
              handleHapusNilai(ws, payload);
              break;

            case "hapus_nilai_dewan":
              handleHapusNilaiDewan(ws, payload);
              break;

            case "set_status":
              handleSetStatus(ws, payload.partai);
              break;

            case "set_status_stop":
              handleStopStatus(ws, payload.partai);
              break;

            case "selesai_seni_regu":
              console.log("selesai");
              const { partai_regu, sudut_regu } = payload;

              // Query ambil semua nilai jurus dan stamina
              const sqlNilai1 =
                "SELECT * FROM nilai_seni_regu WHERE id_jadwal = ? AND sudut = ?";
              // Query ambil penalty dari nilai_dewan_seni_tunggal
              const sqlPenalty1 =
                "SELECT * FROM nilai_dewan_seni_regu WHERE id_jadwal = ? AND sudut = ?";

              // Ambil nilai seni tunggal
              db.query(sqlNilai1, [partai_regu, sudut_regu], (err, rows) => {
                if (err) {
                  console.error("Gagal ambil data nilai:", err.message);
                  return;
                }

                // Ambil penalty
                db.query(
                  sqlPenalty1,
                  [partai_regu, sudut_regu],
                  (err2, penaltyRows) => {
                    if (err2) {
                      console.error("Gagal ambil data penalty:", err2.message);
                      return;
                    }

                    // Hitung penalty total (jumlah hukum_1 s/d hukum_5)
                    let penaltyTotal = 0;
                    if (penaltyRows.length > 0) {
                      const p = penaltyRows[0]; // ambil record pertama (asumsi 1 row per jadwal + sudut)
                      penaltyTotal =
                        (parseFloat(p.hukum_1) || 0) +
                        (parseFloat(p.hukum_2) || 0) +
                        (parseFloat(p.hukum_3) || 0) +
                        (parseFloat(p.hukum_4) || 0) +
                        (parseFloat(p.hukum_5) || 0);
                    }

                    const rekap = rows.map((row) => {
                      let totalJurus = 0;
                      for (let i = 1; i <= 14; i++) {
                        totalJurus += parseFloat(row[`jurus${i}`] || 0);
                      }

                      // const rataJurus = totalJurus / 14;
                      const rataJurus = 9.9 - totalJurus;
                      console.log(rataJurus);
                      console.log(totalJurus);
                      const stamina = parseFloat(row.stamina || 0);
                      const total = parseFloat(
                        (rataJurus + stamina).toFixed(2)
                      );

                      return {
                        id_juri: row.id_juri,
                        rata_rata_jurus: parseFloat(rataJurus.toFixed(2)),
                        stamina,
                        total,
                      };
                    });

                    console.log(penaltyTotal);

                    // Siapkan data untuk dikirim ke client, termasuk penaltyTotal
                    const dataBroadcast = {
                      type: "broadcast_selesai_seni_regu",
                      data: {
                        partai: partai_regu,
                        sudut: sudut_regu,
                        rekap_nilai: rekap,
                        penalty: penaltyTotal,
                      },
                    };

                    // Kirim ke semua client WebSocket
                    wss.clients.forEach(function each(client) {
                      if (client.readyState === WebSocket.OPEN) {
                        client.send(JSON.stringify(dataBroadcast));
                      }
                    });
                  }
                );
              });

              break;

            case "set_partai_tunggal":
              console.log("Menerima data partai, menyiarkan ke semua klien...");
              console.log(JSON.stringify(payload));
              broadcast({ type: "partai_data_tunggal", data: payload });
              break;

            case "kirim_verifikasi":
              console.log(
                `Verifikasi diterima dari juri untuk jenis: ${payload.jenis}`
              );
              // Simpan ke database jika perlu
              // Kirim broadcast ke semua klien
              broadcast({
                type: "verifikasi_masuk",
                data: {
                  jenis: payload.jenis,
                },
              });
              break;

            case "keputusan_dewan":
              broadcast({
                type: "keputusan_verifikasi",
                data: {
                  sudut: payload.sudut,
                  judul: payload.judul,
                },
              });
              break;

            case "tutup_verifikasi":
              console.log("Tutup");
              broadcast({
                type: "verifikasi_tutup",
              });
              break;

            case "verifikasi_juri":
              const { id_juri, pilihan } = payload.data;
              console.log(`Verifikasi dari Juri ${id_juri}: ${pilihan}`);

              // Broadcast ke DEWAN (atau semua klien, sesuaikan filter kalau perlu)
              broadcast({
                type: "verifikasi_keputusan",
                data: {
                  id_juri: id_juri,
                  sudut: pilihan,
                },
              });
              break;

            case "ready":
              wss.clients.forEach(function each(client) {
                if (client.readyState === WebSocket.OPEN) {
                  client.send(
                    JSON.stringify({
                      type: "juri_ready",
                      id_juri: payload.id_juri,
                    })
                  );
                }
              });
              break;

            case "winner":
              console.log(payload);
              let currentPartai = payload.currentPartai;

              if (typeof currentPartai === "string") {
                currentPartai = JSON.parse(currentPartai);
              }

              var pemenang = payload.sudut;
              db.query(
                `UPDATE jadwal_tanding SET status='selesai', pemenang=?,skor_biru=?,skor_merah=? WHERE partai=? AND babak=?`,
                [
                  payload.sudut,
                  payload.nilai_biru,
                  payload.nilai_merah,
                  currentPartai.partai,
                  currentPartai.bbk,
                ],
                (err) => {
                  if (err) {
                    console.error(err);
                    ws.send(
                      JSON.stringify({
                        type: "response",
                        status: "error",
                        message: "Database error saat insert nilai",
                      })
                    );
                    return;
                  }

                  broadcast({
                    type: "response",
                    message: 'selesai',
                    pemenang: payload.sudut,
                  });

                  console.log(currentPartai.bbk);
                  console.log(pemenang);

                  // BAGIAN INSERT MEDALI
                  const babak = currentPartai.bbk.toUpperCase();
                  const idPartai = currentPartai.partai;
                  console.log(`Partai selesai: ID ${idPartai}, Babak ${babak}, Pemenang: ${pemenang}`);

                  // Ambil data kontingen dan kelas dari jadwal_tanding
                  // PERBAIKAN: tambahkan kolom nm_biru dan nm_merah jika ada
                  db.query(
                    `SELECT kontingen_biru, kontingen_merah, kelas, nm_biru, nm_merah 
                 FROM jadwal_tanding 
                 WHERE partai=? AND babak=?`,
                    [idPartai, currentPartai.bbk],
                    (err, results) => {
                      if (err) {
                        console.error("Error mengambil data kontingen:", err);
                        return;
                      }

                      if (results.length > 0) {
                        const data = results[0];
                        const idPartai = currentPartai.partai;
                        const kontingenBiru = data.kontingen_biru;
                        const kontingenMerah = data.kontingen_merah;
                        const nama_biru = data.nm_biru || ""; // default kosong jika null
                        const nama_merah = data.nm_merah || ""; // default kosong jika null
                        const kelas = data.kelas;

                        // Tentukan kontingen pemenang dan kalah
                        let kontingenPemenang, kontingenKalah, namaPemenang, namaKalah;

                        // PERBAIKAN: sesuaikan dengan nilai pemenang yang sebenarnya
                        // payload.sudut mungkin "B" atau "biru" atau "merah"
                        if (pemenang === "B" || pemenang === "biru" || pemenang.toLowerCase() === "biru") {
                          kontingenPemenang = kontingenBiru;
                          kontingenKalah = kontingenMerah;
                          namaPemenang = nama_biru;
                          namaKalah = nama_merah;
                        } else {
                          kontingenPemenang = kontingenMerah;
                          kontingenKalah = kontingenBiru;
                          namaPemenang = nama_merah;
                          namaKalah = nama_biru;
                        }

                        // Handle medali berdasarkan babak
                        console.log('Id Partai yang selesai (semifinal):', idPartai);
                        console.log(`Pemenang semifinal: ${namaPemenang} (${kontingenPemenang})`);
                        if (babak === "SEMIFINAL") {
                          // Kalah di semifinal = Perunggu
                          db.query(
                            `INSERT INTO medali (nama, kontingen, kelas, medali, id_partai_FK) 
                                 VALUES (?, ?, ?, 'Perunggu', ?)`,
                            [namaKalah, kontingenKalah, kelas, idPartai],
                            (err) => {
                              if (err) {
                                console.error("Error insert medali Perunggu:", err);
                              } else {
                                console.log(`Medali Perunggu diberikan ke: ${namaKalah} (${kontingenKalah})`);
                              }
                            }
                          );

                          // Cek di tabel jadwal_tanding apakah ada nama yang menunggu pemenang di partai lain dengan babak SEMIFINAL yang sama (untuk menentukan lawan selanjutnya di partai semifinal yang lain)
                          // Setelah idPartai 1 menjadi pemenang semifinal
                          // const namaPemenang = namaPemenang; // Nama atlet pemenang
                          // const kontingenPemenang = kontingenPemenang; // Kontingen pemenang

                          // Cari partai FINAL yang menunggu pemenang dari partai 1
                          db.query(
                            `SELECT id_partai, 
            CASE 
                WHEN nm_biru LIKE ? THEN 'biru'
                WHEN nm_merah LIKE ? THEN 'merah'
                ELSE NULL
            END as posisi
     FROM jadwal_tanding 
     WHERE babak = 'FINAL' 
       AND (nm_biru LIKE ? OR nm_merah LIKE ?)`,
                            [
                              `%Pemenang Partai ${idPartai}%`,
                              `%Pemenang Partai ${idPartai}%`,
                              `%Pemenang Partai ${idPartai}%`,
                              `%Pemenang Partai ${idPartai}%`
                            ],
                            (err, results) => {
                              if (err) {
                                console.error("Error cek partai FINAL:", err);
                                return;
                              }

                              if (results.length > 0) {
                                const partaiFinal = results[0];
                                const posisi = partaiFinal.posisi; // 'biru' atau 'merah'

                                console.log(`Pemenang dari partai ${idPartai} akan masuk ke posisi ${posisi} di partai FINAL ${partaiFinal.id_partai}`);

                                // Update nama atlet di partai FINAL
                                let updateQuery = '';
                                if (posisi === 'biru') {
                                  updateQuery = `UPDATE jadwal_tanding SET nm_biru = ?, kontingen_biru = ? WHERE id_partai = ?`;
                                } else {
                                  updateQuery = `UPDATE jadwal_tanding SET nm_merah = ?, kontingen_merah = ? WHERE id_partai = ?`;
                                }

                                db.query(
                                  updateQuery,
                                  [namaPemenang, kontingenPemenang, partaiFinal.id_partai],
                                  (updateErr, updateResult) => {
                                    if (updateErr) {
                                      console.error("Error update partai FINAL:", updateErr);
                                    } else {
                                      console.log(`Berhasil mengupdate pemenang partai ${idPartai} ke partai FINAL ${partaiFinal.id_partai} sebagai ${posisi}`);
                                    }
                                  }
                                );

                              } else {
                                console.log(`Tidak ditemukan partai FINAL yang menunggu pemenang dari partai ${idPartai}`);
                              }
                            }
                          );
                        }
                        else if (babak === "FINAL") {
                          // Pemenang final = Emas
                          db.query(
                            `INSERT INTO medali (nama, kontingen, kelas, medali, id_partai_FK) 
                                 VALUES (?, ?, ?, 'Emas', ?)`,
                            [namaPemenang, kontingenPemenang, kelas, idPartai],
                            (err) => {
                              if (err) {
                                console.error("Error insert medali Emas:", err);
                              } else {
                                console.log(`Medali Emas diberikan ke: ${namaPemenang} (${kontingenPemenang})`);
                              }
                            }
                          );

                          // Kalah di final = Perak
                          db.query(
                            `INSERT INTO medali (nama, kontingen, kelas, medali, id_partai_FK) 
                                 VALUES (?, ?, ?, 'Perak', ?)`,
                            [namaKalah, kontingenKalah, kelas, idPartai],
                            (err) => {
                              if (err) {
                                console.error("Error insert medali Perak:", err);
                              } else {
                                console.log(`Medali Perak diberikan ke: ${namaKalah} (${kontingenKalah})`);
                              }
                            }
                          );
                        } else {
                          console.log(`Babak ${babak} - tidak ada medali yang diberikan`);
                        }
                      }
                    }
                  );
                }
              );

              broadcast({
                type: "winner",
                data: payload,
              });
              break;

              // case "set_round_selesai":
              console.log("Partai : " + payload.partai, payload.round);
              currentBabak = parseInt(payload.round);
              // const partai = payload.partai;

              if ([1, 2, 3].includes(currentBabak)) {
                const roundColumn = `round${currentBabak}`;
                console.log(roundColumn);

                db.query(
                  `UPDATE jadwal_tanding SET ${roundColumn} = 1 WHERE id_partai = ?`,
                  [payload.partai],
                  (err) => {
                    if (err) {
                      console.error(err);
                      ws.send(
                        JSON.stringify({
                          type: "response",
                          status: "error",
                          message: "Gagal set status aktif",
                        })
                      );
                      return;
                    }

                    ws.send(
                      JSON.stringify({
                        type: "response",
                        status: "success",
                        message: `Status partai diaktifkan dan ${roundColumn} diupdate`,
                      })
                    );
                  }
                );
              } else {
                ws.send(
                  JSON.stringify({
                    type: "response",
                    status: "error",
                    message: "Babak tidak valid",
                  })
                );
              }

              broadcast({ type: "babak_selesai", round: currentBabak });
              break;

            case "set_partai_selesai":
              // partai = payload.partai;
              console.log("partai selesai : " + payload.partai);
              db.query(
                `UPDATE jadwal_tanding SET status = ? WHERE id_partai = ?`,
                ["selesai", payload.partai],
                (err) => {
                  if (err) {
                    console.error(err);
                    ws.send(
                      JSON.stringify({
                        type: "response",
                        status: "error",
                        message: "Gagal set status selesai",
                      })
                    );
                    return;
                  }

                  broadcast({ type: "status_partai", status: "selesai" });
                  ws.send(
                    JSON.stringify({
                      type: "response",
                      status: "success",
                      message: `Status partai diupdate`,
                    })
                  );
                }
              );
              break;

            default:
              console.log("❓ Unknown message type:", type);
          }
        } catch (err) {
          console.error("❌ Error parsing message:", err);
        }
      });

      ws.on("close", () => {
        console.log("🔴 Client disconnected");
      });
    });
  });
})();

function getNilaiMonitor(ws, id_jadwal, bbk) {
  db.query(
    "SELECT COALESCE(SUM(nilai),0) as na FROM nilai_tanding WHERE id_jadwal = ? AND sudut = 'BIRU' AND bbk=?",
    [id_jadwal, bbk],
    (err, biruResult) => {
      if (err)
        return ws.send(
          JSON.stringify({ status: "error", message: "Gagal ambil nilai biru" })
        );

      db.query(
        "SELECT COALESCE(SUM(nilai),0) as na FROM nilai_tanding WHERE id_jadwal = ? AND sudut = 'MERAH' AND bbk=?",
        [id_jadwal, bbk],
        (err, merahResult) => {
          if (err)
            return ws.send(
              JSON.stringify({
                status: "error",
                message: "Gagal ambil nilai merah",
              })
            );

          db.query(
            "SELECT button,babak FROM nilai_dewan WHERE id_jadwal = ? AND sudut = 'BIRU' AND bbk=?",
            [id_jadwal, bbk],
            (err, biruHukumanResult) => {
              if (err)
                return ws.send(
                  JSON.stringify({
                    status: "error",
                    message: "Gagal ambil hukuman biru",
                  })
                );

              db.query(
                "SELECT button,babak FROM nilai_dewan WHERE id_jadwal = ? AND sudut = 'MERAH' AND bbk=?",
                [id_jadwal, bbk],
                (err, merahHukumanResult) => {
                  if (err)
                    return ws.send(
                      JSON.stringify({
                        status: "error",
                        message: "Gagal ambil hukuman merah",
                      })
                    );

                  const sekarang = DateTime.now().setZone("Asia/Jakarta");
                  const waktu_awal = sekarang.minus({ seconds: 3 });
                  const waktuFormat = [
                    waktu_awal.toFormat("yyyy-MM-dd HH:mm:ss"),
                    sekarang.toFormat("yyyy-MM-dd HH:mm:ss"),
                  ];
                  // console.log(waktuFormat);

                  db.query(
                    "SELECT id_juri, nilai FROM nilai_tanding_log WHERE id_jadwal = ? AND sudut = 'BIRU' AND bbk=? AND created_at BETWEEN ? AND ?",
                    [id_jadwal, bbk, ...waktuFormat],
                    (err, hasilbiruResult) => {
                      if (err)
                        return ws.send(
                          JSON.stringify({
                            status: "error",
                            message: "Gagal ambil log biru",
                          })
                        );

                      db.query(
                        "SELECT id_juri, nilai FROM nilai_tanding_log WHERE id_jadwal = ? AND sudut = 'MERAH' AND bbk=? AND created_at BETWEEN ? AND ?",
                        [id_jadwal, bbk, ...waktuFormat],
                        (err, hasilmerahResult) => {
                          if (err)
                            return ws.send(
                              JSON.stringify({
                                status: "error",
                                message: "Gagal ambil log merah",
                              })
                            );

                          db.query(
                            "SELECT COUNT(nilai) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 1 AND id_jadwal = ? AND bbk=?",
                            [id_jadwal, bbk],
                            (err, jatuhanBiruResult) => {
                              if (err)
                                return ws.send(
                                  JSON.stringify({
                                    status: "error",
                                    message: "Gagal ambil jatuhan biru",
                                  })
                                );

                              db.query(
                                "SELECT COUNT(nilai) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 1 AND id_jadwal = ? AND bbk=?",
                                [id_jadwal, bbk],
                                (err, jatuhanMerahResult) => {
                                  if (err)
                                    return ws.send(
                                      JSON.stringify({
                                        status: "error",
                                        message: "Gagal ambil jatuhan merah",
                                      })
                                    );

                                  // Nilai dasar
                                  let nilaiBiru = parseInt(biruResult[0].na);
                                  let nilaiMerah = parseInt(merahResult[0].na);

                                  // Tambah nilai dari jatuhan
                                  // console.log(nilaiBiru);
                                  // console.log('Jumlah Jatuhan Biru:', jatuhanBiruResult[0].jumlah);
                                  // console.log('Jumlah Jatuhan Merah:', jatuhanMerahResult[0].jumlah);
                                  const tambahanJatuhanBiru =
                                    jatuhanBiruResult[0].jumlah * 3;
                                  const tambahanJatuhanMerah =
                                    jatuhanMerahResult[0].jumlah * 3;
                                  // console.log('Tambahan Jatuhan Biru:', tambahanJatuhanBiru);
                                  nilaiBiru += tambahanJatuhanBiru;
                                  nilaiMerah += tambahanJatuhanMerah;

                                  // Hitung pengaruh hukuman
                                  const pengaruhHukuman = (button) => {
                                    switch (button) {
                                      case 3:
                                        return -1;
                                      case 4:
                                        return -2;
                                      case 5:
                                        return -5;
                                      case 6:
                                        return -10;
                                      default:
                                        return 0;
                                    }
                                  };

                                  // Total pengaruh hukuman biru
                                  let totalHukumanBiru =
                                    biruHukumanResult.reduce((total, item) => {
                                      return (
                                        total +
                                        pengaruhHukuman(parseInt(item.button))
                                      );
                                    }, 0);

                                  let totalHukumanMerah =
                                    merahHukumanResult.reduce((total, item) => {
                                      return (
                                        total +
                                        pengaruhHukuman(parseInt(item.button))
                                      );
                                    }, 0);

                                  // Tambahkan ke nilai
                                  nilaiBiru += totalHukumanBiru;
                                  nilaiMerah += totalHukumanMerah;

                                  // console.log('=== DEBUG monitor_data ===');
                                  // console.log('Nilai Biru (Final):', nilaiBiru);
                                  // console.log('Nilai Merah (Final):', nilaiMerah);
                                  // console.log('Hukuman Biru:', biruHukumanResult.map(b => b.button));
                                  // console.log('Hukuman Merah:', merahHukumanResult.map(b => b.button));
                                  // console.log('Juri Biru:', biruHukumanResult);
                                  // console.log('Juri Merah:', merahHukumanResult);
                                  // console.log('==========================');

                                  function groupAndSeparateButtons(data) {
                                    const grouped = {};
                                    const others = [];

                                    data.forEach((item) => {
                                      // Konversi nilai button ke angka
                                      const btn = Number(item.button);

                                      if ([2, 3, 4].includes(btn)) {
                                        const babak = item.babak;
                                        if (!grouped[babak])
                                          grouped[babak] = [];
                                        grouped[babak].push(btn);
                                      } else {
                                        others.push(btn);
                                      }
                                    });

                                    return { grouped, others };
                                  }

                                  const biru =
                                    groupAndSeparateButtons(biruHukumanResult);
                                  const merah =
                                    groupAndSeparateButtons(merahHukumanResult);

                                  //   console.log(
                                  //     "Hukuman Biru:\n",
                                  //     JSON.stringify(biru, null, 2)
                                  //   );
                                  //   console.log(
                                  //     "Hukuman Merah:\n",
                                  //     JSON.stringify(merah, null, 2)
                                  //   );

                                  broadcast({
                                    type: "monitor_data",
                                    nilai_biru: nilaiBiru,
                                    nilai_merah: nilaiMerah,
                                    hukuman_biru: biru,
                                    hukuman_merah: merah,
                                    juri_biru: hasilbiruResult,
                                    juri_merah: hasilmerahResult,
                                  });
                                }
                              );
                            }
                          );
                        }
                      );
                    }
                  );
                }
              );
            }
          );
        }
      );
    }
  );
}

function handleSetPartai(payload) {
  console.log("📡 Kirim Partai", payload);
  currentPartai = payload;
  handleHistoryNilai(db, payload.partai, 0, payload.bbk);
  broadcast({ type: "partai_data", data: currentPartai });
}

function handleTukarPartai() {
  const partaiKosong = {
    partai: "?",
    gelanggang: "?",
    babak: 0,
    bbk: "?",
    st: "?",
    kelas: "?",
    biru: { nama: "?", kontingen: "?", nilai: 0 },
    merah: { nama: "?", kontingen: "?", nilai: 0 },
  };

  handleHistoryNilai(db, 0, 0, 0);
  broadcast({ type: "partai_data", data: partaiKosong });
}

function handleTukarPartaiTunggal() {
  const partaiKosong = {
    partai: "?",
    kategori: "?",
    kelas: "?",
    peserta: {
      nama: "?",
      kontingen: "?",
      sudut: "?",
    },
  };

  // handleHistoryNilai(db, 0, 0);
  broadcast({ type: "partai_data_tunggal", data: partaiKosong });
}

let timerDuration = 120;
let remaining = timerDuration;
let timer = null;
let isRunning = false;

function handleTimer(ws, type, payload) {
  switch (type) {
    case "start":
      remaining = payload.remaining;
      if (!isRunning) {
        isRunning = true;
        if (remaining <= 0) remaining = timerDuration;
        timer = setInterval(tick, 1000);
        console.log("Mulai", remaining);
        broadcast({ type: "started", remaining });
        // getNilaiMonitor(ws, payload.partai); // refresh monitor
      }
      break;
    case "pause":
      if (isRunning) {
        isRunning = false;
        clearInterval(timer);
        broadcast({ type: "paused", remaining });
        // getNilaiMonitor(ws, payload.partai); // refresh monitor
      }
      break;
    case "resume":
      if (!isRunning && remaining > 0) {
        isRunning = true;
        timer = setInterval(tick, 1000);
        broadcast({ type: "resumed", remaining });
        // getNilaiMonitor(ws, payload.partai); // refresh monitor
      }
      break;
    case "stop":
      isRunning = false;
      clearInterval(timer);
      remaining = timerDuration;
      broadcast({ type: "stopped", remaining });
      // getNilaiMonitor(ws, payload.partai); // refresh monitor
      break;
    case "set_round":
      // console.log("Set Round", currentBabak);
      currentBabak = payload.round;
      broadcast({ type: "babak_data", round: currentBabak });
      // getNilaiMonitor(ws, 1, 'SEMIFINAL'); // refresh monitor
      break;
  }
}

function tick() {
  if (remaining > 0) {
    remaining--;
    broadcast({ type: "tick", remaining });
    if (remaining === 0) {
      isRunning = false;
      clearInterval(timer);
      broadcast({ type: "ended" });
    }
  }
}

function handleNilai(ws, payload) {
  const { id_juri, id_jadwal, nilai, sudut, babak, bbk } = payload;
  const button = payload.button || nilai;
  const sekarang = new Date();
  const waktu_awal = new Date(sekarang.getTime() - 1000); // Anti-spam 3 detik
  const waktu_awal_cek = new Date(sekarang.getTime() - 3000); // Window cek nilai sah 5 detik
  // console.log(bbk);
  // Cek anti-spam
  db.query(
    `SELECT * FROM nilai_tanding_log
        WHERE id_jadwal = ? AND id_juri = ? AND button = ? AND sudut = ?
        AND created_at BETWEEN ? AND ? AND ?
        ORDER BY created_at DESC LIMIT 1`,
    [id_jadwal, id_juri, button, sudut, waktu_awal, sekarang, bbk],
    (err, resultSpam) => {
      if (err) return sendError(ws, "Database error saat cek spam tombol", err);

      if (resultSpam.length > 0) {
        ws.send(
          JSON.stringify({
            type: "response",
            status: "ignored",
            message:
              "Tombol yang sama ditekan dalam waktu pendek, input diabaikan (spam protection)",
          })
        );
        return;
      }

      // Insert nilai_tanding_log sebagai pending
      db.query(
        `INSERT INTO nilai_tanding_log (id_jadwal, id_juri, button, nilai, sudut, babak,bbk, created_at, status_sah)
                VALUES (?, ?, ?, ?, ?, ?,?, ?, 'pending')`,
        [id_jadwal, id_juri, button, nilai, sudut, babak, bbk, sekarang],
        (err) => {
          if (err)
            return sendError(ws, "Database error saat insert nilai", err);

          handleHistoryNilai(db, id_jadwal, id_juri, bbk); // histori juri
          console.log(bbk);
          // Tahap 1: cek apakah sudah ada nilai sah → auto update log juri ini jika perlu
          cekExistingSah((bbk) => {
            // Tahap 2: jika belum sah → cek apakah ada 2 juri yang matching
            console.log(bbk);
            cekNilaiSah(bbk);
          });

          getNilaiMonitor(ws, id_jadwal, bbk); // refresh monitor
        }
      );
    }
  );

  // Tahap 1: cek apakah sudah ada nilai sah
  function cekExistingSah(callback) {
    db.query(
      `SELECT * FROM nilai_tanding 
            WHERE id_jadwal = ? AND babak = ? AND bbk=? AND sudut = ? AND button = ? AND nilai = ?
            AND created_at BETWEEN ? AND ? LIMIT 1`,
      [id_jadwal, babak, bbk, sudut, button, nilai, waktu_awal_cek, sekarang],
      (err, existingSah) => {
        if (err)
          return sendError(
            ws,
            "Database error saat cek existing nilai sah",
            err
          );

        if (existingSah.length > 0) {
          // Sudah sah → update log juri ini langsung jadi sah
          db.query(
            `UPDATE nilai_tanding_log SET status_sah = 'sah'
                        WHERE id_jadwal = ? AND babak = ? AND bbk=? AND sudut = ? AND button = ? AND nilai = ?
                        AND id_juri = ? AND status_sah = 'pending'`,
            [id_jadwal, babak, bbk, sudut, button, nilai, id_juri],
            (err) => {
              if (err)
                return sendError(
                  ws,
                  "Database error saat update existing log sah",
                  err
                );

              ws.send(
                JSON.stringify({
                  type: "response",
                  status: "success",
                  message: "Nilai sudah sah, log diperbarui",
                  nilai: nilai,
                  aksi: button,
                })
              );
              getNilaiMonitor(ws, id_jadwal, bbk);
            }
          );
        } else {
          // Belum ada sah → lanjut ke cekNilaiSah
          callback(bbk);
        }
      }
    );
  }

  // Tahap 2: cek apakah ada >=2 juri pending → insert nilai sah baru
  function cekNilaiSah(bbk) {
    db.query(
      `SELECT nilai, button, COUNT(DISTINCT id_juri) AS jumlah_juri
            FROM nilai_tanding_log
            WHERE id_jadwal = ? AND babak = ? AND sudut = ? AND status_sah = 'pending'
            AND created_at BETWEEN ? AND ?
            GROUP BY nilai, button HAVING jumlah_juri >= 2 LIMIT 1`,
      [id_jadwal, babak, sudut, waktu_awal_cek, sekarang],
      (err, cekSah) => {
        if (err) return sendError(ws, "Database error saat cek nilai sah", err);

        if (cekSah.length > 0) {
          const nilai_sah = cekSah[0].nilai;
          const aksi_sah = cekSah[0].button;

          db.query(
            `INSERT INTO nilai_tanding (id_jadwal, nilai, button, sudut, babak,bbk, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [id_jadwal, nilai_sah, aksi_sah, sudut, babak, bbk, sekarang],
            (err) => {
              if (err)
                return sendError(
                  ws,
                  "Database error saat insert nilai sah",
                  err
                );

              db.query(
                `UPDATE nilai_tanding_log SET status_sah = 'sah'
                                WHERE id_jadwal = ? AND babak = ? AND sudut = ? AND button = ? AND nilai = ?
                                AND created_at BETWEEN ? AND ?`,
                [
                  id_jadwal,
                  babak,
                  sudut,
                  aksi_sah,
                  nilai_sah,
                  waktu_awal_cek,
                  sekarang,
                ],
                (err) => {
                  if (err)
                    return sendError(
                      ws,
                      "Database error saat update log nilai sah",
                      err
                    );

                  ws.send(
                    JSON.stringify({
                      type: "response",
                      status: "success",
                      message: "Nilai sah dan disimpan",
                      nilai: nilai_sah,
                      aksi: aksi_sah,
                    })
                  );
                  getNilaiMonitor(ws, id_jadwal, bbk);
                }
              );
            }
          );
        } else {
          ws.send(
            JSON.stringify({
              type: "response",
              status: "pending",
              message:
                "Menunggu input juri lain dengan nilai dan aksi yang sama",
            })
          );
        }
      }
    );
  }

  // Helper untuk kirim error
  function sendError(ws, msg, err) {
    console.error(err);
    ws.send(
      JSON.stringify({
        type: "response",
        status: "error",
        message: msg,
      })
    );
  }
}

function handleNilaiDewan(ws, payload) {
  // if (msg.type !== 'nilai_dewan') return;

  const { id_jadwal, id_juri, button, sudut, babak, bbk } = payload;
  // Waktu sekarang (UTC+7)
  const sekarang = new Date(Date.now() + 7 * 3600 * 1000)
    .toISOString()
    .slice(0, 19)
    .replace("T", " ");

  // Tentukan nilai dan batas max berdasarkan button
  let nilai, maxCount;
  console.log("button" + button);
  switch (Number(button)) {
    case 2:
      nilai = 0;
      maxCount = 6;
      break;
    case 3:
      nilai = 1;
      maxCount = 1;
      break;
    case 4:
      nilai = 2;
      maxCount = 1;
      break;
    case 5:
      nilai = 5;
      maxCount = 1;
      break;
    case 6:
      nilai = 10;
      maxCount = 1;
      break;
    case 7:
      nilai = 0;
      maxCount = 1;
      broadcast({
        type: "set_diskualifikasi",
        sudut: sudut,
      });

      break;
    case 1:
      nilai = 3;
      maxCount = Infinity;
      break;
    default:
      ws.send(
        JSON.stringify({
          type: "nilai_dewan_response",
          status: "error",
          message: `Button ${button} tidak valid`,
          payload: payload,
        })
      );
      return;
  }

  db.query(
    `INSERT INTO nilai_dewan
           (id_jadwal, id_juri, button, nilai, sudut, babak,bbk, created_at)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
    [id_jadwal, id_juri, button, nilai, sudut, babak, bbk, sekarang],
    (err2) => {
      if (err2) {
        console.error(err2);
        ws.send(
          JSON.stringify({
            type: "nilai_dewan_response",
            status: "error",
            message: "Database error saat insert nilai",
          })
        );
        return;
      }

      // 3) Beri tahu client sukses
      ws.send(
        JSON.stringify({
          type: "nilai_dewan_response",
          status: "success",
          data: {
            id_jadwal,
            id_juri,
            button,
            nilai,
            sudut,
            babak,
            created_at: sekarang,
          },
        })
      );

      // 4) (Opsional) broadcast update ke semua klien
      broadcast({
        type: "update_nilai_dewan",
        data: {
          id_jadwal,
          id_juri,
          button,
          nilai,
          sudut,
          babak,
          created_at: sekarang,
        },
      });
      // Lalu langsung update history_dewan
      console.log("History Dewan ", bbk);
      sendHistoryDewan(ws, id_jadwal, babak, bbk);
      HistoryNilaiJuriPemenang(db, id_jadwal);
      getNilaiMonitor(ws, id_jadwal, bbk); // refresh monitor
    }
  );
}

// Fungsi bantu untuk query dan broadcast history_dewan
function sendHistoryDewan(ws, id_jadwal, babak, bbk) {
  console.log("Send History Dewan", bbk);
  db.query(
    `SELECT * FROM nilai_dewan
     WHERE id_jadwal=? AND bbk=?`,
    [id_jadwal, bbk],
    (err, results) => {
      if (err) {
        console.error("Error query history_dewan:", err);
        ws.send(
          JSON.stringify({
            type: "history_dewan_response",
            status: "error",
            message: "DB error saat ambil history",
          })
        );
        return;
      }

      broadcast({
        type: "update_history_dewan",
        data: { id_jadwal, babak, entries: results },
      });

      getNilaiMonitor(ws, id_jadwal, bbk); // refresh monitor
    }
  );
}

function handleHistoryNilai(db, id_jadwal, id_juri, bbk) {
  // console.log(id_jadwal, id_juri);
  db.query(
    "SELECT * FROM nilai_tanding_log WHERE id_jadwal=? AND bbk=?",
    [id_jadwal, bbk],
    (err, results) => {
      if (err) {
        console.error("Error query:", err);
        return;
      }
      // console.log('Data dari nilai_tanding_log:', results);

      // Kirim ke semua client menggunakan fungsi broadcast
      broadcast({
        type: "history_nilai",
        data: results,
      });
    }
  );
}

function handleHapusNilai(ws, payload) {
  const { id_juri, id_jadwal, sudut, babak, bbk } = payload;
  const sekarang = new Date();
  const waktu_awal_cek = new Date(sekarang.getTime() - 3000); // Window cek nilai sah 5 detik (bisa disesuaikan)

  db.query(
    `
        SELECT * FROM nilai_tanding_log
        WHERE id_juri = ? AND id_jadwal = ? AND sudut = ? AND babak = ? AND bbk=?
        ORDER BY created_at DESC LIMIT 1`,
    [id_juri, id_jadwal, sudut, babak, bbk],
    (err, rows) => {
      if (err || rows.length === 0) {
        ws.send(
          JSON.stringify({
            type: "response",
            status: "error",
            message: "Nilai tidak ditemukan untuk dihapus",
          })
        );
        return;
      }

      const log = rows[0];
      const { button, nilai } = log;

      // Cek berapa juri yang sahkan nilai ini, yang masih dalam window waktu 5 detik terakhir
      db.query(
        `
                SELECT COUNT(DISTINCT id_juri) AS jumlah_juri
                FROM nilai_tanding_log
                WHERE id_jadwal = ? AND sudut = ? AND babak = ? AND button = ? AND nilai = ?
                AND status_sah = 'sah' AND bbk=?
                AND created_at BETWEEN ? AND ?`,
        [id_jadwal, sudut, babak, button, nilai, bbk, waktu_awal_cek, sekarang],
        (err, result) => {
          if (err) {
            console.error(err);
            ws.send(
              JSON.stringify({
                type: "response",
                status: "error",
                message: "Gagal cek jumlah juri yang sahkan nilai",
              })
            );
            return;
          }

          const jumlah_juri = result[0].jumlah_juri;
          console.log(
            "Jumlah juri yang input sah (dalam window 5 detik): " + jumlah_juri
          );

          if (jumlah_juri == 2) {
            // Hapus semua log nilai ini → batal
            db.query(
              `
                            DELETE FROM nilai_tanding_log
                            WHERE id_jadwal = ? AND sudut = ? AND babak = ? AND button = ? AND nilai = ? AND status_sah = 'sah' AND bbk=?
                            AND created_at BETWEEN ? AND ?`,
              [
                id_jadwal,
                sudut,
                babak,
                button,
                nilai,
                bbk,
                waktu_awal_cek,
                sekarang,
              ],
              (err) => {
                if (err) {
                  console.error(err);
                  ws.send(
                    JSON.stringify({
                      type: "response",
                      status: "error",
                      message: "Gagal hapus log nilai sah",
                    })
                  );
                  return;
                }

                // Hapus dari nilai_tanding juga
                db.query(
                  `
                                    DELETE FROM nilai_tanding
                                    WHERE id_jadwal = ? AND sudut = ? AND babak = ? AND button = ? AND nilai = ? AND bbk=?
                                    AND created_at BETWEEN ? AND ?`,
                  [
                    id_jadwal,
                    sudut,
                    babak,
                    button,
                    nilai,
                    bbk,
                    waktu_awal_cek,
                    sekarang,
                  ],
                  (err) => {
                    if (err) {
                      console.error(err);
                      ws.send(
                        JSON.stringify({
                          type: "response",
                          status: "error",
                          message: "Gagal hapus nilai sah",
                        })
                      );
                      return;
                    }

                    ws.send(
                      JSON.stringify({
                        type: "response",
                        status: "success",
                        message:
                          "Nilai dibatalkan karena hanya 2 juri yang sahkan",
                      })
                    );

                    getNilaiMonitor(ws, id_jadwal, bbk); // refresh monitor
                    handleHistoryNilai(db, id_jadwal, id_juri, bbk); // histori juri
                  }
                );
              }
            );
          } else if (jumlah_juri >= 3) {
            // Hapus log juri ini saja → nilai sah tetap ada
            db.query(
              `
                            DELETE FROM nilai_tanding_log
                            WHERE id_juri = ? AND id_jadwal = ? AND sudut = ? AND babak = ? AND button = ? AND nilai = ? AND status_sah = 'sah' AND bbk=?
                            AND created_at BETWEEN ? AND ?`,
              [
                id_juri,
                id_jadwal,
                sudut,
                babak,
                button,
                nilai,
                bbk,
                waktu_awal_cek,
                sekarang,
              ],
              (err) => {
                if (err) {
                  console.error(err);
                  ws.send(
                    JSON.stringify({
                      type: "response",
                      status: "error",
                      message: "Gagal hapus log juri",
                    })
                  );
                  return;
                }

                ws.send(
                  JSON.stringify({
                    type: "response",
                    status: "success",
                    message: "Nilai sah tetap ada, log juri ini dihapus",
                  })
                );

                getNilaiMonitor(ws, id_jadwal, bbk); // refresh monitor
              }
            );
          } else {
            db.query(
              `
                            DELETE FROM nilai_tanding_log
                            WHERE id_juri = ? AND id_jadwal = ? AND sudut = ? AND babak = ? AND button = ? AND nilai = ? AND status_sah = 'pending' AND bbk=?
                            AND created_at BETWEEN ? AND ?`,
              [
                id_juri,
                id_jadwal,
                sudut,
                babak,
                button,
                nilai,
                bbk,
                waktu_awal_cek,
                sekarang,
              ],
              (err) => {
                if (err) {
                  console.error(err);
                  ws.send(
                    JSON.stringify({
                      type: "response",
                      status: "error",
                      message: "Gagal hapus log juri",
                    })
                  );
                  return;
                }

                ws.send(
                  JSON.stringify({
                    type: "response",
                    status: "success",
                    message: "Nilai sah tetap ada, log juri ini dihapus",
                  })
                );

                handleHistoryNilai(db, id_jadwal, id_juri, bbk); // histori juri
              }
            );
          }
        }
      );
    }
  );
}

function handleHapusNilaiDewan(ws, payload) {
  const { id_jadwal, sudut, babak, bbk } = payload;

  db.query(
    `
        SELECT id_nilai FROM nilai_dewan
        WHERE id_jadwal = ? AND sudut = ? AND babak = ? AND bbk=? ORDER BY created_at DESC LIMIT 1`,
    [id_jadwal, sudut, babak, bbk],
    (err, baris) => {
      if (err) {
        console.error(err);
        ws.send(
          JSON.stringify({
            type: "response",
            status: "error",
            message: "Database error saat mencari nilai",
          })
        );
        return;
      }

      if (baris.length > 0) {
        const id_nilai = baris[0].id_nilai;
        db.query(
          "DELETE FROM nilai_dewan WHERE id_nilai = ?",
          [id_nilai],
          (err) => {
            if (err) {
              console.error(err);
              ws.send(
                JSON.stringify({
                  type: "response",
                  status: "error",
                  message: "Database error saat hapus nilai",
                })
              );
              return;
            }
            ws.send(
              JSON.stringify({
                type: "response",
                status: "success",
                message: "Nilai berhasil dihapus",
              })
            );
          }
        );
        sendHistoryDewan(db, id_jadwal, babak, bbk);
        getNilaiMonitor(ws, id_jadwal, bbk);
        HistoryNilaiJuriPemenang(db, id_jadwal);
      } else {
        ws.send(
          JSON.stringify({
            type: "response",
            status: "error",
            message: "Nilai tidak ditemukan untuk dihapus",
          })
        );
      }
    }
  );
}

function handleSetStatus(ws, partai) {
  const sekarang = getNow();
  db.query(
    `UPDATE jadwal_tanding SET status = 'proses' WHERE partai = ?`,
    [partai],
    (err) => {
      if (err) {
        console.error(err);
        ws.send(
          JSON.stringify({
            type: "response",
            status: "error",
            message: "Gagal set status aktif",
          })
        );
        return;
      }
      ws.send(
        JSON.stringify({
          type: "response",
          status: "success",
          message: "proses",
        })
      );
    }
  );
}

function simpan_nilai_seni_tunggal(db, payload) {
  const { id_jadwal, id_juri, sudut, wrong, stamina, skor_akhir } = payload;

  // Validasi data yang diterima
  console.log('📥 Data diterima dari juri:', {
    id_jadwal,
    id_juri,
    sudut,
    wrong,
    stamina,
    skor_akhir
  });

  // Pastikan data ada
  if (!id_jadwal || !id_juri || sudut === undefined) {
    console.error('❌ Data tidak lengkap:', payload);
    return;
  }

  // Konversi tipe data - wrong adalah INTEGER (jumlah kesalahan)
  const wrongCount = parseInt(wrong) || 0;
  const staminaValue = parseFloat(stamina) || 0;

  // Wrong disimpan sebagai decimal di database (4,2)
  const wrongValue = wrongCount.toFixed(2);

  // Data untuk disimpan sesuai struktur tabel
  const data = [
    wrongValue,        // wrong (decimal 4,2)
    staminaValue,      // stamina (decimal 4,2)
    id_jadwal,
    id_juri,
    sudut
  ];

  console.log(`💾 Data akan disimpan: Wrong=${wrongValue}, Stamina=${staminaValue}`);

  // SQL untuk broadcast nilai ke monitor
  const totalSql = `
    SELECT id_juri, wrong, stamina
    FROM nilai_seni_tunggal
    WHERE id_jadwal = ? AND sudut = ?
  `;

  function broadcastNilai() {
    db.query(totalSql, [id_jadwal, sudut], function (err, rows) {
      if (err) {
        console.error("❌ Gagal menghitung total nilai:", err);
        return;
      }

      // Hitung total nilai untuk broadcast ke monitor
      const nilaiTerkini = rows.map((row) => {
        // Rumus: 9.90 - (wrong × 0.01) + stamina
        const wrongCount = parseFloat(row.wrong) || 0;
        const wrongPenalty = wrongCount * 0.01;
        const totalNilai = 9.90 - wrongPenalty + (parseFloat(row.stamina) || 0);

        return {
          juri: row.id_juri,
          total: totalNilai.toFixed(2),
          wrong: row.wrong,
          stamina: row.stamina
        };
      });

      const payloadBroadcast = JSON.stringify({
        type: "update_total_nilai",
        partai: id_jadwal,
        sudut: sudut,
        data: nilaiTerkini
      });

      // Kirim ke semua client yang terhubung
      wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
          client.send(payloadBroadcast);
        }
      });

      console.log("📡 Nilai total dikirim ke monitor:", nilaiTerkini);
    });
  }

  // Cek apakah data sudah ada
  const cekSql = `SELECT id_nilai FROM nilai_seni_tunggal WHERE id_jadwal = ? AND sudut = ? AND id_juri = ?`;
  db.query(cekSql, [id_jadwal, sudut, id_juri], function (err, results) {
    if (err) {
      console.error("❌ Gagal cek data:", err);
      return;
    }

    if (results.length === 0) {
      // INSERT data baru
      const insertSql = `
        INSERT INTO nilai_seni_tunggal 
        (wrong, stamina, id_jadwal, id_juri, sudut) 
        VALUES (?, ?, ?, ?, ?)
      `;
      db.query(insertSql, data, function (err, result) {
        if (err) {
          console.error("❌ Gagal insert data:", err);
          console.error("Error details:", err.message);
        } else {
          console.log(
            `✅ INSERT nilai_seni_tunggal (Partai ${id_jadwal}, Sudut ${sudut}, Juri ${id_juri})`
          );
          console.log(`   Wrong: ${wrongValue}, Stamina: ${staminaValue}`);
          broadcastNilai();
        }
      });
    } else {
      // UPDATE data yang sudah ada
      const updateSql = `
        UPDATE nilai_seni_tunggal SET 
          wrong = ?, 
          stamina = ?
        WHERE id_jadwal = ? AND id_juri = ? AND sudut = ?
      `;
      db.query(updateSql, data, function (err, result) {
        if (err) {
          console.error("❌ Gagal update data:", err);
          console.error("Error details:", err.message);
        } else {
          console.log(
            `🔄 UPDATE nilai_seni_tunggal (Partai ${id_jadwal}, Sudut ${sudut}, Juri ${id_juri})`
          );
          console.log(`   Wrong: ${wrongValue}, Stamina: ${staminaValue}`);
          broadcastNilai();
        }
      });
    }
  });
}

function simpan_nilai_seni_regu(db, payload) {
  const { id_jadwal, juri, sudut, selectedStamina, skorPerJurus } = payload;

  const skor = JSON.parse(skorPerJurus);

  const data = [
    skor.jurus1 || 0,
    skor.jurus2 || 0,
    skor.jurus3 || 0,
    skor.jurus4 || 0,
    skor.jurus5 || 0,
    skor.jurus6 || 0,
    skor.jurus7 || 0,
    skor.jurus8 || 0,
    skor.jurus9 || 0,
    skor.jurus10 || 0,
    skor.jurus11 || 0,
    skor.jurus12 || 0,
    skor.jurus13 || 0,
    skor.jurus14 || 0,
    selectedStamina,
    id_jadwal,
    juri,
    sudut,
  ];

  const totalSql = `
    SELECT id_juri, jurus1, jurus2, jurus3, jurus4, jurus5, jurus6, jurus7,
           jurus8, jurus9, jurus10, jurus11, jurus12, jurus13, jurus14, stamina
    FROM nilai_seni_regu
    WHERE id_jadwal = ? AND sudut = ?
  `;

  function broadcastNilai() {
    db.query(totalSql, [id_jadwal, sudut], function (err, rows) {
      if (err) {
        console.error("❌ Gagal menghitung total nilai:", err);
        return;
      }

      const nilaiTerkini = rows.map((row) => {
        const totalJurus =
          (parseFloat(row.jurus1) || 0) +
          (parseFloat(row.jurus2) || 0) +
          (parseFloat(row.jurus3) || 0) +
          (parseFloat(row.jurus4) || 0) +
          (parseFloat(row.jurus5) || 0) +
          (parseFloat(row.jurus6) || 0) +
          (parseFloat(row.jurus7) || 0) +
          (parseFloat(row.jurus8) || 0) +
          (parseFloat(row.jurus9) || 0) +
          (parseFloat(row.jurus10) || 0) +
          (parseFloat(row.jurus11) || 0) +
          (parseFloat(row.jurus12) || 0) +
          (parseFloat(row.jurus13) || 0) +
          (parseFloat(row.jurus14) || 0);
        // const rataRataJurus = totalJurus / 14;
        const rataRataJurus = 9.9 - totalJurus;
        const totalNilai = rataRataJurus + (parseFloat(row.stamina) || 0);
        console.log(totalNilai);
        return {
          juri: row.id_juri,
          total: totalNilai.toFixed(2),
        };
      });

      const payloadBroadcast = JSON.stringify({
        type: "update_total_nilai",
        partai: id_jadwal,
        sudut: sudut,
        data: nilaiTerkini,
      });

      wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
          client.send(payloadBroadcast);
        }
      });

      console.log("📡 Nilai total dikirim ke monitor:", payloadBroadcast);
    });
  }

  // Cek apakah data sudah ada
  const cekSql = `SELECT id_nilai FROM nilai_seni_regu WHERE id_jadwal = ? AND sudut = ? AND id_juri = ?`;
  db.query(cekSql, [id_jadwal, sudut, juri], function (err, results) {
    if (err) {
      console.error("❌ Gagal cek data:", err);
      return;
    }

    if (results.length === 0) {
      // 🔹 INSERT
      const insertSql = `
        INSERT INTO nilai_seni_regu 
        (jurus1, jurus2, jurus3, jurus4, jurus5, jurus6, jurus7, jurus8, jurus9, jurus10, 
         jurus11, jurus12, jurus13, jurus14, stamina, id_jadwal, id_juri, sudut) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
      `;
      db.query(insertSql, data, function (err, result) {
        if (err) {
          console.error("❌ Gagal insert data:", err);
        } else {
          console.log(
            `✅ INSERT nilai seni regu sukses (Partai ${id_jadwal}, Sudut ${sudut})`
          );
          broadcastNilai();
        }
      });
    } else {
      // 🔄 UPDATE
      const updateSql = `
        UPDATE nilai_seni_regu SET 
          jurus1 = ?, jurus2 = ?, jurus3 = ?, jurus4 = ?, jurus5 = ?, jurus6 = ?, jurus7 = ?, 
          jurus8 = ?, jurus9 = ?, jurus10 = ?, jurus11 = ?, jurus12 = ?, jurus13 = ?, jurus14 = ?, 
          stamina = ?
        WHERE id_jadwal = ? AND id_juri = ? AND sudut = ?
      `;
      db.query(updateSql, data, function (err, result) {
        if (err) {
          console.error("❌ Gagal update data:", err);
        } else {
          console.log(
            `🔄 UPDATE nilai seni regu sukses (Partai ${id_jadwal}, Sudut ${sudut})`
          );
          broadcastNilai();
        }
      });
    }
  });
}

function simpan_nilai_seni_tunggal_dewan(db, payload) {
  const {
    partai, // id_jadwal
    sudut,
    score,
    target, // angka: 1, 2, 3, 4, atau 5
  } = payload;

  const fieldName = `hukum_${target}`; // dinamis: hukum_1, hukum_2, dst.

  console.log(`➡️ Target: ${target}, Simpan ke ${fieldName} = ${score}`);

  // Cek apakah data sudah ada
  const cekSql = `
    SELECT id_nilai, hukum_1, hukum_2, hukum_3, hukum_4, hukum_5 
    FROM nilai_dewan_seni_tunggal 
    WHERE id_jadwal = ? AND sudut = ?
  `;

  db.query(cekSql, [partai, sudut], function (err, results) {
    if (err) {
      console.error("❌ Gagal cek data nilai_dewan_seni_tunggal:", err);
      return;
    }

    if (results.length === 0) {
      // 🔹 INSERT semua nilai 0 kecuali target
      const nilai = {
        hukum_1: 0,
        hukum_2: 0,
        hukum_3: 0,
        hukum_4: 0,
        hukum_5: 0,
      };
      nilai[fieldName] = score;

      const insertSql = `
        INSERT INTO nilai_dewan_seni_tunggal 
        (id_jadwal, sudut, hukum_1, hukum_2, hukum_3, hukum_4, hukum_5) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
      `;
      const insertData = [
        partai,
        sudut,
        nilai.hukum_1,
        nilai.hukum_2,
        nilai.hukum_3,
        nilai.hukum_4,
        nilai.hukum_5,
      ];

      db.query(insertSql, insertData, function (err) {
        if (err) {
          console.error("❌ Gagal insert nilai_dewan_seni_tunggal:", err);
        } else {
          console.log(
            `✅ INSERT sukses (Partai ${partai}, Sudut ${sudut}, ${fieldName} = ${score})`
          );
        }
      });
    } else {
      // 🔄 UPDATE — jika target 5, tambahkan ke nilai lama
      let finalScore = score;

      if (target == 5) {
        const nilaiLama = parseFloat(results[0].hukum_5 || 0);
        finalScore = parseFloat((nilaiLama + score).toFixed(2));
      }

      const updateSql = `
        UPDATE nilai_dewan_seni_tunggal 
        SET ${fieldName} = ?
        WHERE id_jadwal = ? AND sudut = ?
      `;
      db.query(updateSql, [finalScore, partai, sudut], function (err) {
        if (err) {
          console.error(`❌ Gagal update ${fieldName}:`, err);
        } else {
          console.log(
            `🔄 UPDATE sukses (Partai ${partai}, Sudut ${sudut}, ${fieldName} = ${finalScore})`
          );
        }
      });
    }
  });
}

function simpan_nilai_seni_regu_dewan(db, payload) {
  const {
    partai, // id_jadwal
    sudut,
    score,
    target, // angka: 1, 2, 3, 4, atau 5
  } = payload;

  const fieldName = `hukum_${target}`; // dinamis: hukum_1, hukum_2, dst.

  console.log(`➡️ Target: ${target}, Simpan ke ${fieldName} = ${score}`);

  // Cek apakah data sudah ada
  const cekSql = `
    SELECT id_nilai, hukum_1, hukum_2, hukum_3, hukum_4, hukum_5 
    FROM nilai_dewan_seni_regu 
    WHERE id_jadwal = ? AND sudut = ?
  `;

  db.query(cekSql, [partai, sudut], function (err, results) {
    if (err) {
      console.error("❌ Gagal cek data nilai_dewan_seni_regu:", err);
      return;
    }

    if (results.length === 0) {
      // 🔹 INSERT semua nilai 0 kecuali target
      const nilai = {
        hukum_1: 0,
        hukum_2: 0,
        hukum_3: 0,
        hukum_4: 0,
        hukum_5: 0,
      };
      nilai[fieldName] = score;

      const insertSql = `
        INSERT INTO nilai_dewan_seni_regu 
        (id_jadwal, sudut, hukum_1, hukum_2, hukum_3, hukum_4, hukum_5) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
      `;
      const insertData = [
        partai,
        sudut,
        nilai.hukum_1,
        nilai.hukum_2,
        nilai.hukum_3,
        nilai.hukum_4,
        nilai.hukum_5,
      ];

      db.query(insertSql, insertData, function (err) {
        if (err) {
          console.error("❌ Gagal insert nilai_dewan_seni_regu:", err);
        } else {
          console.log(
            `✅ INSERT sukses (Partai ${partai}, Sudut ${sudut}, ${fieldName} = ${score})`
          );
        }
      });
    } else {
      // 🔄 UPDATE — jika target 5, tambahkan ke nilai lama
      let finalScore = score;

      if (target == 5) {
        const nilaiLama = parseFloat(results[0].hukum_5 || 0);
        finalScore = parseFloat((nilaiLama + score).toFixed(2));
      }

      const updateSql = `
        UPDATE nilai_dewan_seni_regu 
        SET ${fieldName} = ?
        WHERE id_jadwal = ? AND sudut = ?
      `;
      db.query(updateSql, [finalScore, partai, sudut], function (err) {
        if (err) {
          console.error(`❌ Gagal update ${fieldName}:`, err);
        } else {
          console.log(
            `🔄 UPDATE sukses (Partai ${partai}, Sudut ${sudut}, ${fieldName} = ${finalScore})`
          );
        }
      });
    }
  });
}

function clear_nilai_seni_tunggal_dewan(db, payload) {
  const {
    partai, // id_jadwal
    sudut,
    target,
  } = payload;

  const fieldName = `hukum_${target}`;

  if (target == 5) {
    // 🔁 Khusus target 5 → kurangi 0.50 per klik
    const selectSql = `
      SELECT hukum_5 FROM nilai_dewan_seni_tunggal 
      WHERE id_jadwal = ? AND sudut = ?
    `;

    db.query(selectSql, [partai, sudut], function (err, results) {
      if (err) {
        console.error("❌ Gagal ambil hukum_5:", err);
        return;
      }

      if (results.length === 0) {
        console.warn(
          `⚠️ Data tidak ditemukan untuk Partai ${partai}, Sudut ${sudut}`
        );
        return;
      }

      let currentScore = parseFloat(results[0].hukum_5 || 0);
      let newScore = parseFloat((currentScore + 0.5).toFixed(2)); // Tambah karena nilai awal negatif

      // Jangan biarkan nilai positif
      if (newScore > 0) {
        newScore = 0;
      }

      const updateSql = `
        UPDATE nilai_dewan_seni_tunggal 
        SET hukum_5 = ? 
        WHERE id_jadwal = ? AND sudut = ?
      `;

      db.query(updateSql, [newScore, partai, sudut], function (err) {
        if (err) {
          console.error("❌ Gagal update hukum_5:", err);
        } else {
          console.log(
            `✅ hukum_5 dikurangi jadi ${newScore} (Partai ${partai}, Sudut ${sudut})`
          );
        }
      });
    });
  } else if (target >= 1 && target <= 4) {
    // 🧹 Target 1–4: langsung hapus (reset ke 0)
    const updateSql = `
      UPDATE nilai_dewan_seni_tunggal 
      SET ${fieldName} = 0 
      WHERE id_jadwal = ? AND sudut = ?
    `;

    db.query(updateSql, [partai, sudut], function (err) {
      if (err) {
        console.error(`❌ Gagal reset ${fieldName}:`, err);
      } else {
        console.log(
          `🧹 ${fieldName} dihapus/set 0 (Partai ${partai}, Sudut ${sudut})`
        );
      }
    });
  } else {
    console.warn(`⚠️ Target tidak valid: ${target}`);
  }
}

function clear_nilai_seni_regu_dewan(db, payload) {
  const {
    partai, // id_jadwal
    sudut,
    target,
  } = payload;

  const fieldName = `hukum_${target}`;

  if (target == 5) {
    // 🔁 Khusus target 5 → kurangi 0.50 per klik
    const selectSql = `
      SELECT hukum_5 FROM nilai_dewan_seni_regu 
      WHERE id_jadwal = ? AND sudut = ?
    `;

    db.query(selectSql, [partai, sudut], function (err, results) {
      if (err) {
        console.error("❌ Gagal ambil hukum_5:", err);
        return;
      }

      if (results.length === 0) {
        console.warn(
          `⚠️ Data tidak ditemukan untuk Partai ${partai}, Sudut ${sudut}`
        );
        return;
      }

      let currentScore = parseFloat(results[0].hukum_5 || 0);
      let newScore = parseFloat((currentScore + 0.5).toFixed(2)); // Tambah karena nilai awal negatif

      // Jangan biarkan nilai positif
      if (newScore > 0) {
        newScore = 0;
      }

      const updateSql = `
        UPDATE nilai_dewan_seni_regu 
        SET hukum_5 = ? 
        WHERE id_jadwal = ? AND sudut = ?
      `;

      db.query(updateSql, [newScore, partai, sudut], function (err) {
        if (err) {
          console.error("❌ Gagal update hukum_5:", err);
        } else {
          console.log(
            `✅ hukum_5 dikurangi jadi ${newScore} (Partai ${partai}, Sudut ${sudut})`
          );
        }
      });
    });
  } else if (target >= 1 && target <= 4) {
    // 🧹 Target 1–4: langsung hapus (reset ke 0)
    const updateSql = `
      UPDATE nilai_dewan_seni_regu 
      SET ${fieldName} = 0 
      WHERE id_jadwal = ? AND sudut = ?
    `;

    db.query(updateSql, [partai, sudut], function (err) {
      if (err) {
        console.error(`❌ Gagal reset ${fieldName}:`, err);
      } else {
        console.log(
          `🧹 ${fieldName} dihapus/set 0 (Partai ${partai}, Sudut ${sudut})`
        );
      }
    });
  } else {
    console.warn(`⚠️ Target tidak valid: ${target}`);
  }
}

function handleStopStatus(ws, partai) {
  const sekarang = getNow();
  db.query(
    `UPDATE jadwal_tanding SET status = '-' WHERE partai = ?`,
    [partai],
    (err) => {
      if (err) {
        console.error(err);
        ws.send(
          JSON.stringify({
            type: "response",
            status: "error",
            message: "Gagal set status stop",
          })
        );
        return;
      }
      ws.send(
        JSON.stringify({
          type: "response",
          status: "success",
          message: "Status partai stop",
        })
      );
    }
  );
}

function HistoryNilaiJuriPemenang(db, id_jadwal) {
  db.query(
    "SELECT * FROM nilai_tanding WHERE id_jadwal=?",
    [id_jadwal],
    (err, results) => {
      if (err) {
        console.error("Error query nilai_tanding:", err);
        return;
      }

      db.query(
        `
        SELECT sudut, nilai, COUNT(*) AS total 
        FROM nilai_tanding 
        WHERE id_jadwal=? AND nilai IN (1,2)
        GROUP BY sudut, nilai
    `,
        [id_jadwal],
        (err, nilai_tanding_counts) => {
          if (err) {
            console.error("Error nilai_tanding_counts:", err);
            return;
          }

          const countTanding = {
            pukulan: { BIRU: 0, MERAH: 0 },
            tendangan: { BIRU: 0, MERAH: 0 },
          };

          nilai_tanding_counts.forEach((item) => {
            if (item.nilai == 1) countTanding.pukulan[item.sudut] = item.total;
            else if (item.nilai == 2)
              countTanding.tendangan[item.sudut] = item.total;
          });

          // ✅ 1. Pukulan (nilai = 1)
          console.log("Jumlah Pukulan:", countTanding.pukulan);

          // ✅ 2. Tendangan (nilai = 2)
          console.log("Jumlah Tendangan:", countTanding.tendangan);

          db.query(
            `
            SELECT sudut, nilai, COUNT(*) AS total 
            FROM nilai_dewan 
            WHERE id_jadwal=? AND nilai IN (0,1,2,3,5,10)
            GROUP BY sudut, nilai
        `,
            [id_jadwal],
            (err, nilai_dewan_counts) => {
              if (err) {
                console.error("Error nilai_dewan_counts:", err);
                return;
              }

              const countDewan = {
                binaan: { BIRU: 0, MERAH: 0 },
                teguran1: { BIRU: 0, MERAH: 0 },
                teguran2: { BIRU: 0, MERAH: 0 },
                jatuhan: { BIRU: 0, MERAH: 0 },
                peringatan1: { BIRU: 0, MERAH: 0 },
                peringatan2: { BIRU: 0, MERAH: 0 },
              };

              nilai_dewan_counts.forEach((item) => {
                const { nilai, sudut, total } = item;
                if (nilai == 0) countDewan.binaan[sudut] = total;
                else if (nilai == 1) countDewan.teguran1[sudut] = total;
                else if (nilai == 2) countDewan.teguran2[sudut] = total;
                else if (nilai == 3) countDewan.jatuhan[sudut] = total;
                else if (nilai == 5) countDewan.peringatan1[sudut] = total;
                else if (nilai == 10) countDewan.peringatan2[sudut] = total;
              });

              // ✅ 3. Binaan (nilai = 0)
              console.log("Jumlah Binaan:", countDewan.binaan);

              // ✅ 4. Jatuhan (nilai = 3)
              console.log("Jumlah Jatuhan:", countDewan.jatuhan);

              // ✅ 5. Teguran 1 (nilai = 1)
              console.log("Jumlah Teguran 1:", countDewan.teguran1);

              // ✅ 6. Teguran 2 (nilai = 2)
              console.log("Jumlah Teguran 2:", countDewan.teguran2);

              // ✅ 7. Peringatan 1 (nilai = 5)
              console.log("Jumlah Peringatan 1:", countDewan.peringatan1);

              // ✅ 8. Peringatan 2 (nilai = 10)
              console.log("Jumlah Peringatan 2:", countDewan.peringatan2);

              // Kirim ke semua client via WebSocket
              broadcast({
                type: "history_nilai_pemenang",
                // data: results,
                nilai_tanding: countTanding,
                nilai_dewan: countDewan,
              });
            }
          );
        }
      );
    }
  );
}
