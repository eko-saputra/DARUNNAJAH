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
    <h2 class="text-uppercase text-center judul"></h2>
    <div id="bracketContainer"></div>

    <script src="../../assets/jquery/jquery-3.6.0.min.js"></script>
    <script src="../../assets/jquery/jquery.bracket.min.js"></script>

    <script>
        const socket = new WebSocket('ws://192.168.100.133:3000');

        socket.onopen = () => console.log('WebSocket connected');
        socket.onerror = err => console.error('WebSocket error:', err);
        socket.onclose = () => console.log('WebSocket closed');

        // ---------------- UTILS ----------------
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

        function buatPasangan(peserta) {
            const pasangan = [];
            for (let i = 0; i < peserta.length; i += 2) {
                const p1 = peserta[i];
                let p2 = peserta[i + 1] || null;
                // Swap untuk beda kontingen
                if (p2 && p1.kontingen === p2.kontingen) {
                    for (let j = i + 2; j < peserta.length; j++) {
                        if (peserta[j] && peserta[j].kontingen !== p1.kontingen) {
                            [peserta[i + 1], peserta[j]] = [peserta[j], peserta[i + 1]];
                            p2 = peserta[i + 1];
                            break;
                        }
                    }
                }
                pasangan.push([
                    `${p1.nama} (${p1.kontingen})`,
                    p2 ? `${p2.nama} (${p2.kontingen})` : null
                ]);
            }
            return pasangan;
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

        function bagiKeGrup(peserta, ukuranGrup = 4) {
            let grups = [],
                finalByes = [];
            const total = peserta.length;
            if (total <= 2) {
                finalByes.push(...peserta);
                return {
                    grups: [],
                    finalByes
                };
            }
            // if (total === 3) {
            //     grups.push([peserta[0], peserta[1]]);
            //     finalByes.push(peserta[2]);
            //     return {
            //         grups,
            //         finalByes
            //     };
            // }

            let remaining = [...peserta];
            const grupCount = Math.ceil(total / ukuranGrup);
            let baseSize = Math.floor(total / grupCount),
                extra = total % grupCount;
            for (let i = 0; i < grupCount; i++) {
                let sz = baseSize + (extra > 0 ? 1 : 0);
                grups.push(remaining.splice(0, sz));
                if (extra > 0) extra--;
            }
            // Pisahkan grup 1 orang
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

        // ---------------- RENDER ----------------
        function renderBracket(kelas, peserta) {
            const kelasSlug = (kelas).trim().toLowerCase().replace(/\s+/g, '_');
            const initKey = `baganInit_${kelasSlug}`;
            const resultsKey = `baganResults_${kelasSlug}`;
            const partaiKey = `baganPartai_${kelasSlug}`;

            localStorage.setItem(`kelas`, kelas);
            $(".judul").text(localStorage.getItem(`kelas`));
            // --- HAPUS CACHE KELAS LAIN ---
            Object.keys(localStorage).forEach(k => {
                if (k.startsWith('baganInit_') && k !== initKey) localStorage.removeItem(k);
                if (k.startsWith('baganPartai_') && k !== partaiKey) localStorage.removeItem(k);
                if (k.startsWith('baganResults_') && k !== resultsKey) localStorage.removeItem(k);
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
                // console.log(grups);

                groupsInit = grups.map((g, i) => {
                    const pasangan = buatPasangan(g);
                    const results = generateEmptyResults(pasangan);
                    const huruf = indexToHuruf(i);
                    const judul = (pasangan.length === 1) ? "FINAL" : "SEMIFINAL";

                    pasangan.forEach((team, idx) => {
                        console.log(team.length);
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
                // console.log(finalByes);

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
                            nm_biru: `${finalByes[1].nama}`,
                            kontingen_biru: `${finalByes[1].kontingen}`,
                            nm_merah: `${finalByes[0].nama}`,
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
                                nm_merah: `${finalByes[0].nama}`,
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

                let savedAllResults = null;
                try {
                    savedAllResults = JSON.parse(localStorage.getItem(resultsKey) || 'null');
                } catch {}
                const initialResults = savedAllResults && savedAllResults[i] ? savedAllResults[i] : grp.results;

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
                    teamWidth: 220,
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
                console.log('Received baganData:', data);
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
            // Ambil kelas dari localStorage atau tentukan default
            const kelas = 'Usia Dini 2A PUTRA UNDER';

            // Panggil get_bagan.php dengan parameter kelas
            fetch(`get_bagan.php?kelas=${encodeURIComponent(kelas)}`)
                .then(response => response.json())
                .then(data => {
                    const peserta = [];

                    // Iterasi semua groups dari response JSON
                    (data.groups || []).forEach(g => {
                        g.teams.forEach((t, index) => {
                            const skor = g.results[index][0]; // misal [[skorBiru, skorMerah]]
                            console.log(`${t[0]} vs ${t[1]} → Skor: ${skor[0]} - ${skor[1]}`);
                            if (t[0]) peserta.push({
                                nama: t[0].split('(')[0].trim(),
                                kontingen: t[0].split('(')[1]?.replace(')', '') || '',
                                skor: skor[0] // skor biru
                            });
                            if (t[1]) peserta.push({
                                nama: t[1].split('(')[0].trim(),
                                kontingen: t[1].split('(')[1]?.replace(')', '') || '',
                                skor: skor[1] // skor merah
                            });
                        });
                    });

                    // Panggil fungsi renderBracket dengan data peserta dari server
                    console.log(peserta);
                    renderBracket(kelas, peserta);
                })
                .catch(err => {
                    console.error("Gagal load data dari server:", err);
                });
        });
    </script>
</body>

</html>