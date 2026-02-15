<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Bagan Permasalan & Final Pencak Silat</title>
    <link href="../../assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../assets/jquery/jquery.bracket.min.css" rel="stylesheet" />
    <style>
        body {
            background: #111;
            color: #eee;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            margin-bottom: 25px;
            font-weight: 700;
            color: #ddd;
        }

        .bracket-group {
            background: #3b3b3b;
            color: #eee;
            margin: 10px;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
            overflow-x: auto;
        }

        h5 {
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 20px;
            color: #ccc;
        }

        #bracketContainer {
            display: flex;
            flex-wrap: wrap;
        }

        .jQBracket .team {
            text-transform: uppercase !important;
            color: #2d2d2d;
        }
    </style>
</head>

<body>
    <div class="text-center mb-5">
        <button id="fullscreenBtn" class="btn btn-primary">Fullscreen</button>
        <div id="container"></div>
        <hr>
        <div class="d-flex">
            <select id="golongan" class="form-control my-3 me-2">
                <option value="">Golongan</option>
                <option value="Usia Dini 2A">Usia Dini 2A</option>
                <option value="Usia Dini 2B">Usia Dini 2B</option>
                <option value="Pra Remaja">Pra Remaja</option>
                <option value="Remaja">Remaja</option>
            </select>
            <select id="kategori" class="form-control my-3 me-2">
                <option value="">Kategori</option>
                <option value="Putra">Putra</option>
                <option value="Putri">Putri</option>
            </select>
            <select id="kelas" class="form-control my-3 me-2">
                <option value="">Kelas</option>
                <option value="1">UNDER</option>
                <option value="2">KELAS A</option>
                <option value="3">KELAS B</option>
                <option value="4">KELAS C</option>
                <option value="5">KELAS D</option>
                <option value="6">KELAS E</option>
                <option value="7">KELAS F</option>
                <option value="8">KELAS G</option>
                <option value="9">KELAS H</option>
                <option value="10">KELAS I</option>
                <option value="11">KELAS J</option>
                <option value="12">KELAS K</option>
                <option value="13">KELAS L</option>
                <option value="14">KELAS M</option>
                <option value="15">KELAS N</option>
                <option value="16">KELAS O</option>
                <option value="17">KELAS P</option>
                <option value="18">KELAS Q</option>
                <option value="19">KELAS R</option>
            </select>
            <button id="bagan" class="btn btn-secondary my-3">Tampil</button>
        </div>
        <hr>
    </div>

    <h2 class="text-uppercase text-center judul"></h2>
    <div id="bracketContainer"></div>

    <script src="../../assets/jquery/jquery-3.6.0.min.js"></script>
    <script src="../../assets/jquery/jquery.bracket.min.js"></script>

    <script>
        // Baca dari localStorage
        const savedGolongan = localStorage.getItem('golongan');
        const savedKategori = localStorage.getItem('kategori');
        const savedKelas = localStorage.getItem('kelas');
        console.log(savedKelas);

        if (savedGolongan) $('#golongan').val(savedGolongan);
        if (savedKategori) $('#kategori').val(savedKategori);
        const kls = localStorage.getItem('kls');
        if (kls) $('#kelas').val(kls); // ✅ pakai value, bukan text
        const socket = new WebSocket('ws://192.168.20.254:3000');

        socket.onopen = () => console.log('WebSocket connected');
        socket.onerror = err => console.error('WebSocket error:', err);
        socket.onclose = () => console.log('WebSocket closed');

        // ---------------- UTILS ----------------
        function shuffleZigzag(peserta) {
            // 1. Kelompokkan peserta berdasarkan kontingen
            const kontingenMap = {};
            peserta.forEach(p => {
                const key = p.kontingen.trim().toUpperCase();
                if (!kontingenMap[key]) kontingenMap[key] = [];
                kontingenMap[key].push(p);
            });

            // 2. Buat array kontingen yang diacak urutannya
            let kontingenKeys = Object.keys(kontingenMap);

            // 3. Hasil shuffle zigzag
            const result = [];

            // 4. Loop sampai semua peserta habis
            while (true) {
                let added = false;
                // Acak kontingenKeys untuk variasi setiap putaran
                shuffleArray(kontingenKeys);
                for (let k of kontingenKeys) {
                    if (kontingenMap[k].length > 0) {
                        // Cek agar tidak ada kontingen sama berturut-turut
                        if (result.length === 0 || result[result.length - 1].kontingen.trim().toUpperCase() !== k) {
                            result.push(kontingenMap[k].shift());
                            added = true;
                        }
                    }
                }
                if (!added) break; // tidak ada yang tersisa
            }

            return result;
        }

        // Fungsi shuffle biasa (Fisher–Yates)
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }


        function indexToHuruf(idx) {
            let huruf = "";
            while (idx >= 0) {
                huruf = String.fromCharCode((idx % 26) + 65) + huruf;
                idx = Math.floor(idx / 26) - 1;
            }
            return huruf;
        }

        // function buatPasangan(peserta) {
        //     // shuffleArray(peserta);
        //     // console.log(peserta);
        //     const pasangan = [];
        //     for (let i = 0; i < peserta.length; i += 2) {
        //         const p1 = peserta[i];
        //         let p2 = peserta[i + 1] || null;
        //         // Swap untuk beda kontingen
        //         // console.log(p1.kontingen);
        //         // console.log(p2.kontingen);
        //         if (p2 && p1.kontingen === p2.kontingen) {
        //             for (let j = i + 2; j < peserta.length; j++) {
        //                 if (peserta[j] && peserta[j].kontingen !== p1.kontingen) {
        //                     [peserta[i + 1], peserta[j]] = [peserta[j], peserta[i + 1]];
        //                     p2 = peserta[i + 1];
        //                     break;
        //                 }
        //             }
        //         }
        //         pasangan.push([
        //             `${p1.nama} (${p1.kontingen})`,
        //             p2 ? `${p2.nama} (${p2.kontingen})` : null
        //         ]);
        //     }
        //     return pasangan;
        // }

        // function buatPasangan(peserta) {
        //     let remaining = [...peserta].sort(() => Math.random() - 0.5); // acak peserta
        //     const pasangan = [];

        //     while (remaining.length > 0) {
        //         const p1 = remaining.shift();
        //         let p2Index = remaining.findIndex(p => p.kontingen !== p1.kontingen);

        //         let p2 = null;
        //         if (p2Index >= 0) {
        //             p2 = remaining.splice(p2Index, 1)[0];
        //         } else if (remaining.length > 0) {
        //             // jika tidak ada yang beda kontingen, ambil peserta pertama tersisa
        //             p2 = remaining.shift();
        //         }

        //         pasangan.push([
        //             `${p1.nama} (${p1.kontingen})`,
        //             p2 ? `${p2.nama} (${p2.kontingen})` : null
        //         ]);
        //     }

        //     return pasangan;
        // }

        function buatPasangan(peserta) {
            // 1. Acak peserta awal
            let remaining = [...peserta].sort(() => Math.random() - 0.5);
            const pasangan = [];

            // 2. Bentuk pasangan awal
            while (remaining.length > 0) {
                const p1 = remaining.shift();

                // Cari semua kandidat p2 berbeda kontingen
                let p2Index = remaining.findIndex(p => p.kontingen !== p1.kontingen);

                let p2 = null;
                if (p2Index >= 0) {
                    p2 = remaining.splice(p2Index, 1)[0];
                } else if (remaining.length > 0) {
                    // jika tidak ada beda kontingen, ambil peserta pertama tersisa
                    p2 = remaining.shift();
                }

                pasangan.push([p1, p2]);
            }

            // 3. Swap global untuk memperbaiki pasangan sesama kontingen
            for (let i = 0; i < pasangan.length; i++) {
                let [p1, p2] = pasangan[i];
                if (!p2) continue; // skip jika pasangan null
                if (p1.kontingen === p2.kontingen) {
                    // coba swap dengan pasangan lain
                    for (let j = i + 1; j < pasangan.length; j++) {
                        let [q1, q2] = pasangan[j];
                        if (!q2) continue;
                        // cek swap pertama p2 dengan q1
                        if (p1.kontingen !== q1.kontingen && q2.kontingen !== p2.kontingen) {
                            // lakukan swap
                            pasangan[i][1] = q1;
                            pasangan[j][0] = p2;
                            break;
                        }
                        // cek swap pertama p2 dengan q2
                        if (p1.kontingen !== q2.kontingen && q1.kontingen !== p2.kontingen) {
                            pasangan[i][1] = q2;
                            pasangan[j][1] = p2;
                            break;
                        }
                    }
                }
            }

            return pasangan.map(pair => [
                `${pair[0].nama} (${pair[0].kontingen})`,
                pair[1] ? `${pair[1].nama} (${pair[1].kontingen})` : null
            ]);
        }

        function generateEmptyResults(pairs) {
            const totalTeams = Math.pow(2, Math.ceil(Math.log2(pairs.length)));
            const rounds = Math.log2(totalTeams);
            let results = [];
            for (let r = 0; r < rounds; r++) {
                const matches = Math.pow(2, rounds - r - 1);
                results.push(Array(matches).fill([0, 0]));
            }
            return [results];
        }

        // function bagiKeGrup(peserta, ukuranGrup = 4) {
        //     console.log(peserta);
        //     let grups = [],
        //         finalByes = [];
        //     const total = peserta.length;
        //     if (total <= 2) {
        //         finalByes.push(...peserta);
        //         return {
        //             grups: [],
        //             finalByes
        //         };
        //     }

        //     let remaining = [...peserta];
        //     const grupCount = Math.ceil(total / ukuranGrup);
        //     // console.log(grupCount);
        //     let baseSize = Math.floor(total / grupCount),
        //         extra = total % grupCount;
        //     for (let i = 0; i < grupCount; i++) {
        //         let sz = baseSize + (extra > 0 ? 1 : 0);
        //         grups.push(remaining.splice(0, sz));
        //         if (extra > 0) extra--;
        //     }
        //     // Pisahkan grup 1 orang
        //     for (let i = 0; i < grups.length; i++) {
        //         if (grups[i].length === 1) {
        //             finalByes.push(grups[i][0]);
        //             grups.splice(i, 1);
        //             i--;
        //         }
        //     }
        //     return {
        //         grups,
        //         finalByes
        //     };
        // }
        function bagiKeGrup(peserta, ukuranGrup = 4) {
            let grups = [],
                finalByes = [];

            if (peserta.length <= 2) {
                finalByes.push(...peserta);
                return {
                    grups: [],
                    finalByes
                };
            }

            // 1. Kelompokkan peserta per kontingen
            const kontingenMap = {};
            peserta.forEach(p => {
                const key = p.kontingen.trim().toUpperCase();
                if (!kontingenMap[key]) kontingenMap[key] = [];
                kontingenMap[key].push(p);
            });

            // 2. Acak peserta di tiap kontingen
            for (let key in kontingenMap) {
                kontingenMap[key] = kontingenMap[key].sort(() => Math.random() - 0.5);
            }

            // 3. Hitung jumlah grup
            const grupCount = Math.ceil(peserta.length / ukuranGrup);
            for (let i = 0; i < grupCount; i++) grups.push([]);

            // 4. Bagi peserta ke grup secara bergiliran
            let grupIndex = 0;
            for (let key in kontingenMap) {
                kontingenMap[key].forEach(p => {
                    grups[grupIndex].push(p);
                    grupIndex = (grupIndex + 1) % grupCount;
                });
            }

            // 5. Pisahkan grup 1 orang menjadi finalByes
            for (let i = 0; i < grups.length; i++) {
                if (grups[i].length === 1) {
                    finalByes.push(grups[i][0]);
                    grups.splice(i, 1);
                    i--;
                }
            }

            return {
                grups,
                finalByes
            };
        }

        // ---------------- Simpan otomatis ke TXT ----------------
        function saveToTxt(dataObj, kelasSlug) {
            const blob = new Blob([JSON.stringify(dataObj, null, 2)], {
                type: 'text/plain'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `bagan_${kelasSlug}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function setItemAndSave(key, value, kelasSlug) {
            localStorage.setItem(key, JSON.stringify(value));
            // Ambil semua key penting
            const initKey = `baganInit_${kelasSlug}`;
            const resultsKey = `baganResults_${kelasSlug}`;
            const partaiKey = `baganPartai_${kelasSlug}`;
            const data = {
                [initKey]: JSON.parse(localStorage.getItem(initKey) || 'null'),
                [resultsKey]: JSON.parse(localStorage.getItem(resultsKey) || 'null'),
                [partaiKey]: JSON.parse(localStorage.getItem(partaiKey) || 'null')
            };
            saveToTxt(data, kelasSlug);
        }

        // ---------------- RENDER ----------------
        function renderBracket(kelas, peserta) {
            const kelasSlug = (kelas).trim().toLowerCase().replace(/\s+/g, '_');
            const initKey = `baganInit_${kelasSlug}`;
            const resultsKey = `baganResults_${kelasSlug}`;
            const partaiKey = `baganPartai_${kelasSlug}`;
            // shuffleArray(peserta);
            // shuffleZigzag(peserta);
            // console.log(shuffleZigzag(peserta));

            localStorage.setItem(`kelas`, kelas);
            $(".judul").text(localStorage.getItem(`kelas`));
            // --- HAPUS CACHE KELAS LAIN ---
            Object.keys(localStorage).forEach(k => {
                // if (k.startsWith('baganInit_') && k !== initKey) localStorage.removeItem(k);
                // if (k.startsWith('baganPartai_') && k !== partaiKey) localStorage.removeItem(k);
                // if (k.startsWith('baganResults_') && k !== resultsKey) localStorage.removeItem(k);
            });

            let cachedInit = null;
            try {
                cachedInit = JSON.parse(localStorage.getItem(initKey) || 'null');
            } catch {}
            let groupsInit = [],
                semuaPartai = [];

            if (cachedInit) {
                groupsInit = cachedInit.groups;
                semuaPartai = JSON.parse(localStorage.getItem(partaiKey) || '[]');
            } else {
                const {
                    grups,
                    finalByes
                } = bagiKeGrup([...peserta], 4);
                // Buat grup utama
                console.log(grups);

                groupsInit = grups.map((g, i) => {
                    // shuffleArray(g);
                    // console.log(g);
                    const pasangan = buatPasangan(g);
                    const results = generateEmptyResults(pasangan);
                    const huruf = indexToHuruf(i);
                    const judul = (pasangan.length === 1) ? "FINAL" : "SEMIFINAL";

                    pasangan.forEach((team, idx) => {
                        // console.log(team.length);
                        let biru = team[0] || "BYE",
                            merah = team[1] || "BYE";
                        let babak = "SEMIFINAL";
                        if (biru === "BYE" || merah === "BYE") {
                            babak = "FINAL";
                            if (biru === "BYE") biru = `Pemenang SEMIFINAL BAGAN ${huruf}`;
                            if (merah === "BYE") merah = `Pemenang SEMIFINAL BAGAN ${huruf}`;
                        }

                        // Ekstrak nama & kontingen dari string format "Nama (Kontingen)"
                        const parsePeserta = (label) => {
                            if (label.includes("Pemenang") || label === "BYE") {
                                return {
                                    nama: null,
                                    kontingen: null
                                };
                            }
                            const match = label.match(/^(.+?) \((.+?)\)$/);
                            return match ? {
                                nama: match[1],
                                kontingen: match[2]
                            } : {
                                nama: label,
                                kontingen: null
                            };
                        };

                        const pBiru = parsePeserta(biru);
                        const pMerah = parsePeserta(merah);

                        semuaPartai.push({
                            bagan_id: idx + 1, // auto-increment
                            babak,
                            bagan: huruf,
                            nm_biru: biru,
                            kontingen_biru: pBiru.kontingen,
                            nm_merah: merah,
                            kontingen_merah: pMerah.kontingen,
                        });
                    });

                    return {
                        teams: pasangan,
                        results,
                        judul,
                        huruf
                    };
                });

                // Tambahkan finalByes
                if (finalByes.length > 0) {
                    if (finalByes.length === 1 && peserta.length === 3) {
                        const byePeserta = finalByes[0];
                        const semifinalPeserta = peserta.filter(p => p !== byePeserta);
                        const huruf = indexToHuruf(groupsInit.length);

                        const team = [
                            [`${semifinalPeserta[0].nama} (${semifinalPeserta[0].kontingen})`,
                                `${semifinalPeserta[1].nama} (${semifinalPeserta[1].kontingen})`
                            ],
                            [null, `${byePeserta.nama} (${byePeserta.kontingen})`]
                        ];

                        groupsInit.push({
                            teams: team,
                            results: generateEmptyResults(team),
                            judul: "SEMIFINAL",
                            huruf
                        });
                    } else if (finalByes.length === 2) {
                        const huruf = indexToHuruf(groupsInit.length);
                        // kalau tinggal 2 peserta, buat 1 FINAL saja
                        const team = [
                            [`${finalByes[0].nama} (${finalByes[0].kontingen})`, `${finalByes[1].nama} (${finalByes[1].kontingen})`]
                        ];
                        groupsInit.push({
                            teams: team,
                            results: generateEmptyResults(team),
                            judul: "FINAL",
                            huruf: huruf
                        });

                        semuaPartai.push({
                            bagan_id: 1,
                            nm_biru: `${finalByes[1].nama} (${finalByes[1].kontingen})`,
                            kontingen_biru: `${finalByes[1].kontingen}`,
                            nm_merah: `${finalByes[0].nama} (${finalByes[0].kontingen})`,
                            kontingen_merah: `${finalByes[0].kontingen}`,
                            babak: "FINAL",
                            bagan: huruf
                        });
                    } else {
                        // kalau lebih dari 2 peserta finalByes, buat bagan per peserta
                        finalByes.forEach((p, idx) => {
                            const huruf = indexToHuruf(idx);
                            const team = [
                                [`${p.nama} (${p.kontingen})`, null]
                            ];
                            groupsInit.push({
                                teams: team,
                                results: generateEmptyResults(team),
                                judul: "FINAL",
                                huruf: huruf
                            });

                            semuaPartai.push({
                                bagan_id: idx + 1,
                                nm_biru: null,
                                kontingen_biru: null,
                                nm_merah: `${finalByes[0].nama} (${finalByes[0].kontingen})`,
                                kontingen_merah: `${finalByes[0].kontingen}`,
                                babak: "FINAL",
                                bagan: huruf
                            });
                        });
                    }
                }

                localStorage.setItem(initKey, JSON.stringify({
                    groups: groupsInit
                }));
                localStorage.setItem(partaiKey, JSON.stringify(semuaPartai));
            }

            // Render ke HTML
            $("#bracketContainer").empty();
            groupsInit.forEach((grp, i) => {
                if (!grp || !grp.teams) return;
                const divId = `bracket-${kelasSlug}-${i}`;
                $("#bracketContainer").append(`
            <div class="bracket-group">
                <h5>Bagan ${grp.huruf} ${grp.judul}</h5>
                <div id="${divId}" style="height:${grp.teams.length*60}px;"></div>
            </div>
        `);

                // let savedAllResults = null;
                // try {
                //     savedAllResults = JSON.parse(localStorage.getItem(resultsKey) || 'null');
                // } catch {}
                // const initialResults = savedAllResults && savedAllResults[i] ? savedAllResults[i] : grp.results;
                let savedAllResults = null;
                try {
                    savedAllResults = JSON.parse(localStorage.getItem(resultsKey) || 'null');
                } catch {}

                // Kalau belum ada di storage, pakai default 0 dan langsung simpan
                if (!savedAllResults) {
                    savedAllResults = [];
                }
                if (!savedAllResults[i]) {
                    savedAllResults[i] = grp.results; // default hasil 0,0 dari generateEmptyResults
                    localStorage.setItem(resultsKey, JSON.stringify(savedAllResults));
                }

                const initialResults = savedAllResults[i];


                $(`#${divId}`).bracket({
                    init: {
                        teams: grp.teams,
                        results: initialResults
                    },
                    save: function(data) {
                        let cur = [];
                        try {
                            cur = JSON.parse(localStorage.getItem(resultsKey) || '[]');
                        } catch {}
                        cur[i] = data.results;
                        localStorage.setItem(resultsKey, JSON.stringify(cur));

                        // --- Ambil pemenang semifinal ---
                        const semifinalMatches = data.teams;
                        const semifinalScores = data.results[0][0];

                        let winners = [];

                        semifinalMatches.forEach((match, i) => {
                            if (!match || match.length < 2) return;

                            const merah = match[0];
                            const biru = match[1];

                            // Kalau ada yang null (BYE), otomatis yang tidak null menang
                            if (merah && !biru) {
                                winners.push(merah);
                                return;
                            }
                            if (!merah && biru) {
                                winners.push(biru);
                                return;
                            }

                            // Kalau dua-duanya ada → bandingkan skor
                            const skorMerah = semifinalScores[i][0];
                            const skorBiru = semifinalScores[i][1];

                            if (skorMerah != null && skorBiru != null) {
                                const pemenang = skorMerah > skorBiru ? merah : biru;
                                winners.push(pemenang);
                            }
                            // ✅ Ambil informasi dari grup
                            const baganHuruf = grp.huruf || "-";
                            const baganId = grp.bagan_id || i + 1; // kalau disimpan, ambil dari sana
                            // console.log("✅ Bagan:", baganHuruf);
                            localStorage.setItem('bagan', baganHuruf);
                            // console.log("✅ ID Bagan:", baganId);
                            // console.log("✅ Pemenang ke FINAL:", winners);


                            const kelas = $('.judul').text(); // "Usia Dini 2A Putra Under"
                            // --- Kirim ke server untuk buat partai final ---
                        });
                        // console.log(localStorage.getItem('kelas'));
                        if (winners.length === 2) {
                            let [nm_biru, kontingen_biru] = winners[0].match(/(.+?) \((.+)\)/).slice(1);
                            let [nm_merah, kontingen_merah] = winners[1].match(/(.+?) \((.+)\)/).slice(1);
                            // console.log(kelas);
                            // console.log(localStorage.getItem('bagan'));

                            $.ajax({
                                url: "save_final_match.php",
                                type: "POST",
                                data: {
                                    nm_merah,
                                    kontingen_merah,
                                    nm_biru,
                                    kontingen_biru,
                                    kelas,
                                    bagan: localStorage.getItem('bagan')
                                },
                                dataType: "json", // ❗ Force browser to expect JSON
                                success: function(res) {
                                    console.log("✅ Respons server:", res);
                                },
                                error: function(xhr, status, err) {
                                    console.error("❌ Gagal membuat final:", xhr.responseText); // ❗ lihat isi error
                                }
                            });

                        }

                    },
                    skipConsolationRound: true,
                    disableToolbar: true,
                    disableTeamEdit: true,
                    teamWidth: 600,
                    scoreWidth: 50,
                    matchMargin: 40,
                    roundMargin: 80
                });
            });
            // console.log(semuaPartai);
            // Pastikan koneksi WebSocket sudah terbuka
            if (socket.readyState === WebSocket.OPEN) {
                socket.send(JSON.stringify({
                    type: "dataPartai",
                    kelas: $('.judul').text(),
                    tanggal: new Date().toISOString().split("T")[0], // format YYYY-MM-DD
                    semuaPartai: semuaPartai
                }));
            } else {
                socket.addEventListener('open', () => {
                    socket.send(JSON.stringify({
                        type: "dataPartai",
                        kelas: $('.judul').text(),
                        tanggal: new Date().toISOString().split("T")[0], // format YYYY-MM-DD
                        semuaPartai: semuaPartai
                    }));
                }, {
                    once: true
                }); // supaya tidak kirim berkali-kali
            }

        }
        // ---------------- WEBSOCKET ----------------
        socket.onmessage = (evt) => {
            const data = JSON.parse(evt.data);
            if (data.type === 'baganData' && data.kelas) {
                // console.log('Received baganData:', data);
                const peserta = data.peserta || [];
                // let peserta = [{
                //         nama: "Ahmad",
                //         kontingen: "Perisai Diri"
                //     },
                //     {
                //         nama: "Budi",
                //         kontingen: "Tapak Suci"
                //     },
                //     {
                //         nama: "Citra",
                //         kontingen: "Merpati Putih"
                //     },
                //     {
                //         nama: "Dewi",
                //         kontingen: "Persinas ASAD"
                //     },
                //     {
                //         nama: "Eka",
                //         kontingen: "Setia Hati"
                //     },
                //     {
                //         nama: "Fajar",
                //         kontingen: "Kera Sakti"
                //     },
                //     {
                //         nama: "Gilang",
                //         kontingen: "Pagar Nusa"
                //     },
                //     {
                //         nama: "Hani",
                //         kontingen: "Perisai Diri"
                //     },
                //     {
                //         nama: "Indra",
                //         kontingen: "Tapak Suci"
                //     },
                //     {
                //         nama: "Joko",
                //         kontingen: "Merpati Putih"
                //     },
                //     {
                //         nama: "Kiki",
                //         kontingen: "Persinas ASAD"
                //     },
                //     {
                //         nama: "Lina",
                //         kontingen: "Setia Hati"
                //     },
                //     {
                //         nama: "Mira",
                //         kontingen: "Kera Sakti"
                //     },
                //     {
                //         nama: "Nina",
                //         kontingen: "Pagar Nusa"
                //     },
                //     {
                //         nama: "Omar",
                //         kontingen: "Perisai Diri"
                //     },
                //     {
                //         nama: "Putri",
                //         kontingen: "Tapak Suci"
                //     },
                //     {
                //         nama: "Qori",
                //         kontingen: "Merpati Putih"
                //     },
                //     {
                //         nama: "Raka",
                //         kontingen: "Persinas ASAD"
                //     },
                //     {
                //         nama: "Sinta",
                //         kontingen: "Setia Hati"
                //     },
                //     {
                //         nama: "Taufik",
                //         kontingen: "Kera Sakti"
                //     },
                //     {
                //         nama: "Umar",
                //         kontingen: "Pagar Nusa"
                //     },
                //     {
                //         nama: "Vina",
                //         kontingen: "Perisai Diri"
                //     },
                //     {
                //         nama: "Wawan",
                //         kontingen: "Tapak Suci"
                //     },
                //     {
                //         nama: "Xena",
                //         kontingen: "Merpati Putih"
                //     },
                //     {
                //         nama: "Yoga",
                //         kontingen: "Persinas ASAD"
                //     },
                //     {
                //         nama: "Zahra",
                //         kontingen: "Setia Hati"
                //     },
                //     {
                //         nama: "Agus",
                //         kontingen: "Kera Sakti"
                //     },
                //     {
                //         nama: "Bella",
                //         kontingen: "Pagar Nusa"
                //     },
                //     {
                //         nama: "Chandra",
                //         kontingen: "Perisai Diri"
                //     },
                //     {
                //         nama: "Eko",
                //         kontingen: "Perisai Diri"
                //     }
                // ];

                renderBracket(data.kelas, peserta);
            } else if (data.type === 'error') {
                console.log('Error from server:', data.message);
            }

            if (data.type === 'info') {
                console.log(data.message);
            }
        };

        window.addEventListener("load", () => {
            // Render cache semua kelas yg tersimpan
            const allKeys = Object.keys(localStorage).filter(k => k.startsWith("baganInit_"));
            // $('.judul').text(localStorage.getItem('kelas'));
            allKeys.forEach(initKey => {
                try {
                    const kelasSlug = initKey.replace("baganInit_", "");
                    let kelasTitle = localStorage.getItem(`kelas_${kelasSlug}`);
                    if (!kelasTitle) {
                        kelasTitle = kelasSlug.replace(/_/g, " "); // ubah _ jadi spasi
                    }
                    const initData = JSON.parse(localStorage.getItem(initKey) || "{}");
                    const peserta = [];
                    (initData.groups || []).forEach(g => {
                        g.teams.forEach(t => {
                            if (t[0]) peserta.push({
                                nama: t[0].split('(')[0].trim(),
                                kontingen: t[0].split('(')[1]?.replace(')', '') || ''
                            });
                            if (t[1]) peserta.push({
                                nama: t[1].split('(')[0].trim(),
                                kontingen: t[1].split('(')[1]?.replace(')', '') || ''
                            });
                        });
                    });
                    renderBracket(localStorage.getItem('kelas'), peserta);
                } catch (e) {
                    console.error("Gagal render cache:", e);
                }
            });
        });

        // Tombol Fullscreen
        const fullscreenBtn = document.getElementById("fullscreenBtn");

        fullscreenBtn.addEventListener("click", () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`Gagal masuk fullscreen: ${err.message}`);
                });
                fullscreenBtn.textContent = "Exit Fullscreen";
            } else {
                document.exitFullscreen();
                fullscreenBtn.textContent = "Fullscreen";
            }
        });

        // Ubah teks tombol sesuai status
        document.addEventListener("fullscreenchange", () => {
            if (!document.fullscreenElement) {
                fullscreenBtn.textContent = "Fullscreen";
            } else {
                fullscreenBtn.textContent = "Exit Fullscreen";
            }
        });
    </script>
    <script>
        // ================= EXPORT =================
        function exportAllBaganToJSON() {
            const allKeys = Object.keys(localStorage);
            const baganData = {};

            allKeys.forEach(key => {
                if (key.startsWith("baganInit_") || key.startsWith("baganResults_") || key.startsWith("baganPartai_") || key.startsWith("baganGolongan_") || key.startsWith("baganKategori_") || key.startsWith("baganKelas_") || key === "kelas") {
                    try {
                        baganData[key] = JSON.parse(localStorage.getItem(key));
                    } catch (e) {
                        console.warn("Gagal parse key:", key, e);
                        baganData[key] = localStorage.getItem(key);
                    }
                }
            });

            const blob = new Blob([JSON.stringify(baganData, null, 2)], {
                type: "application/json"
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "all_bagan_data.json";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // ================= IMPORT =================
        function importBaganFromJSON(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = JSON.parse(e.target.result);
                    Object.keys(data).forEach(key => {
                        localStorage.setItem(key, JSON.stringify(data[key]));
                    });

                    localStorage.setItem('golongan', 'Usia Dini 2A');
                    localStorage.setItem('kategori', 'Putra');
                    localStorage.setItem('kelas', 'Usia Dini 2A Putra UNDER');
                    localStorage.setItem('kls', 1);

                    const kelas = localStorage.getItem('kelas');
                    alert("✅ Semua data bagan berhasil dikembalikan ke localStorage.");

                    const initKey = `baganInit_${kelas.toLowerCase().replace(/\s+/g, "_")}`;
                    const initData = JSON.parse(localStorage.getItem(initKey) || "{}");

                    if (!initData.groups || initData.groups.length === 0) {
                        alert("⚠️ Data bagan tidak ditemukan untuk kelas: " + kelas);
                        return;
                    }

                    const peserta = [];
                    initData.groups.forEach(g => {
                        g.teams.forEach(t => {
                            if (t[0]) peserta.push({
                                nama: t[0].split('(')[0].trim(),
                                kontingen: t[0].split('(')[1]?.replace(')', '') || ''
                            });
                            if (t[1]) peserta.push({
                                nama: t[1].split('(')[0].trim(),
                                kontingen: t[1].split('(')[1]?.replace(')', '') || ''
                            });
                        });
                    });

                    // Render bracket langsung
                    renderBracket(kelas, peserta);

                } catch (err) {
                    console.error("❌ Gagal load JSON:", err);
                    alert("Gagal membaca file JSON. Pastikan format benar.");
                }
            };
            reader.readAsText(file);
        }


        $('#bagan').click(function() {
            const golongan = $('#golongan').val();
            const kategori = $('#kategori').val();
            const kelasText = $('#kelas option:selected').text();
            const kelas = `${golongan} ${kategori} ${kelasText}`;
            const kls = $('#kelas').val();
            localStorage.setItem('golongan', golongan);
            localStorage.setItem('kategori', kategori);
            localStorage.setItem('kelas', kelas);
            localStorage.setItem('kls', kls);

            location.reload();
        })

        // ================= TAMBAHKAN TOMBOL =================
        const container = document.getElementById("container");

        // Export
        const exportBtn = document.createElement("button");
        exportBtn.textContent = "Export Semua Bagan";
        exportBtn.className = "btn btn-success ms-2";
        exportBtn.addEventListener("click", exportAllBaganToJSON);
        container.parentNode.insertBefore(exportBtn, container);

        // Import
        const importInput = document.createElement("input");
        importInput.type = "file";
        importInput.accept = "application/json";
        importInput.style.display = "none";
        importInput.addEventListener("change", function() {
            if (this.files.length > 0) {
                importBaganFromJSON(this.files[0]);
            }
        });

        const importBtn = document.createElement("button");
        importBtn.textContent = "Import Bagan JSON";
        importBtn.className = "btn btn-warning ms-2";
        importBtn.addEventListener("click", () => importInput.click());

        container.parentNode.insertBefore(importBtn, container);
        container.parentNode.insertBefore(importInput, container);
    </script>


</body>

</html>