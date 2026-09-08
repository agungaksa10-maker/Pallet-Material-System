#!/usr/bin/env node

/**
 * Pallet Material System - One-shot Database Migration Script
 * Migrasi seluruh database MySQL saat ini (skema + data) ke server tujuan.
 * 
 * Penggunaan:
 *   node migrate-db.js
 *   node migrate-db.js "mysql://u268_0rzI2utqDN:vBg7%5EhbAcH!lYf9wjz5%2BVlhI@127.0.0.1:3306/s268_pallet-material"
 */

import { spawn, execSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';

// URL Default yang diberikan user
const DEFAULT_DEST_URL = 'mysql://u268_0rzI2utqDN:vBg7%5EhbAcH!lYf9wjz5%2BVlhI@127.0.0.1:3306/s268_pallet-material';
const KNOWN_REMOTE_HOST = '38.45.72.91';

// Membaca file .env untuk kredensial database sumber
function loadSourceConfig() {
    const envPath = path.resolve(process.cwd(), '.env');
    const env = {};
    if (fs.existsSync(envPath)) {
        const lines = fs.readFileSync(envPath, 'utf-8').split(/\r?\n/);
        for (const line of lines) {
            const trimmed = line.trim();
            if (!trimmed || trimmed.startsWith('#')) continue;
            const idx = trimmed.indexOf('=');
            if (idx > 0) {
                const key = trimmed.slice(0, idx).trim();
                let val = trimmed.slice(idx + 1).trim();
                if ((val.startsWith('"') && val.endsWith('"')) || (val.startsWith("'") && val.endsWith("'"))) {
                    val = val.slice(1, -1);
                }
                env[key] = val;
            }
        }
    }

    return {
        host: env.DB_HOST || '127.0.0.1',
        port: env.DB_PORT || '3306',
        database: env.DB_DATABASE || 'material_dressing',
        user: env.DB_USERNAME || 'root',
        password: env.DB_PASSWORD || ''
    };
}

// Parsing MySQL Connection URL
function parseMysqlUrl(urlStr) {
    if (!urlStr.startsWith('mysql://')) {
        urlStr = 'mysql://' + urlStr;
    }
    const parsed = new URL(urlStr);
    return {
        user: decodeURIComponent(parsed.username || 'root'),
        password: decodeURIComponent(parsed.password || ''),
        host: parsed.hostname || '127.0.0.1',
        port: parsed.port || '3306',
        database: parsed.pathname.replace(/^\//, '') || ''
    };
}

// Mencari binary mysqldump dan mysql secara otomatis
function findBinary(binName) {
    const isWin = process.platform === 'win32';
    const exeName = isWin ? `${binName}.exe` : binName;

    // 1. Cek di system PATH
    try {
        const checkCmd = isWin ? `where ${exeName}` : `which ${exeName}`;
        const output = execSync(checkCmd, { stdio: 'pipe', encoding: 'utf-8' }).trim();
        if (output) {
            return output.split(/\r?\n/)[0].trim();
        }
    } catch {}

    // 2. Cek di Laragon (Windows)
    if (isWin) {
        const laragonDir = 'C:\\laragon\\bin\\mysql';
        if (fs.existsSync(laragonDir)) {
            const dirs = fs.readdirSync(laragonDir);
            for (const d of dirs) {
                const candidate = path.join(laragonDir, d, 'bin', exeName);
                if (fs.existsSync(candidate)) return candidate;
            }
        }

        const xamppPath = path.join('C:\\xampp\\mysql\\bin', exeName);
        if (fs.existsSync(xamppPath)) return xamppPath;
    }

    // 3. Cek di Linux
    const linuxPaths = [
        `/usr/bin/${binName}`,
        `/usr/local/bin/${binName}`,
        `/bin/${binName}`
    ];
    for (const lp of linuxPaths) {
        if (fs.existsSync(lp)) return lp;
    }

    return exeName;
}

// Uji koneksi ke MySQL server
function testConnection(mysqlBin, config) {
    try {
        const args = [
            `-u${config.user}`,
            `-h${config.host}`,
            `-P${config.port}`
        ];
        if (config.password) {
            args.push(`-p${config.password}`);
        }
        args.push('-e', 'SELECT 1;');

        execSync(`"${mysqlBin}" ${args.map(a => `"${a}"`).join(' ')}`, {
            stdio: 'pipe',
            timeout: 7000
        });
        return true;
    } catch {
        return false;
    }
}

async function runMigration() {
    console.log('====================================================');
    console.log('  🚀 Pallet Material System - Database Migrator');
    console.log('====================================================\n');

    const rawDestUrl = process.argv[2] || DEFAULT_DEST_URL;
    const srcConfig = loadSourceConfig();
    const destConfig = parseMysqlUrl(rawDestUrl);

    console.log(`📌 Sumber Database:`);
    console.log(`   Host:     ${srcConfig.host}:${srcConfig.port}`);
    console.log(`   Database: ${srcConfig.database}`);
    console.log(`   User:     ${srcConfig.user}\n`);

    console.log(`🎯 Tujuan Database:`);
    console.log(`   Host:     ${destConfig.host}:${destConfig.port}`);
    console.log(`   Database: ${destConfig.database}`);
    console.log(`   User:     ${destConfig.user}\n`);

    const mysqldumpBin = findBinary('mysqldump');
    const mysqlBin = findBinary('mysql');

    console.log(`🔍 Mendeteksi Binary MySQL:`);
    console.log(`   mysqldump: ${mysqldumpBin}`);
    console.log(`   mysql:     ${mysqlBin}\n`);

    // Uji koneksi ke sumber
    process.stdout.write('🔌 Menguji koneksi database sumber... ');
    if (testConnection(mysqlBin, srcConfig)) {
        console.log('✅ OK');
    } else {
        console.log('❌ GAGAL');
        console.error(`Error: Tidak dapat terhubung ke database sumber (${srcConfig.host}:${srcConfig.port}/${srcConfig.database}). Pastikan server MySQL lokal aktif.`);
        process.exit(1);
    }

    // Uji koneksi ke tujuan
    process.stdout.write('🔌 Menguji koneksi database tujuan... ');
    if (testConnection(mysqlBin, destConfig)) {
        console.log('✅ OK');
    } else {
        // Jika 127.0.0.1 gagal (misal dijalankan dari komputer luar/bukan di dalam container hosting), coba remote IP
        if (destConfig.host === '127.0.0.1' || destConfig.host === 'localhost') {
            console.log(`⚠️ (127.0.0.1 tidak dapat diakses langsung dari host ini)`);
            process.stdout.write(`   Mencoba beralih ke remote host ${KNOWN_REMOTE_HOST}... `);
            const fallbackConfig = { ...destConfig, host: KNOWN_REMOTE_HOST };
            if (testConnection(mysqlBin, fallbackConfig)) {
                console.log('✅ OK');
                destConfig.host = KNOWN_REMOTE_HOST;
            } else {
                console.log('❌ GAGAL');
                console.error(`Error: Gagal terhubung ke database tujuan pada ${destConfig.host} maupun ${KNOWN_REMOTE_HOST}.`);
                process.exit(1);
            }
        } else {
            console.log('❌ GAGAL');
            console.error(`Error: Tidak dapat terhubung ke database tujuan (${destConfig.host}:${destConfig.port}/${destConfig.database}). Periksa kredensial.`);
            process.exit(1);
        }
    }

    console.log(`\n📦 Memulai proses migrasi data dari [${srcConfig.database}] -> [${destConfig.database}]...`);
    const startTime = Date.now();

    // Persiapan argumen mysqldump
    const dumpArgs = [
        `-u${srcConfig.user}`,
        `-h${srcConfig.host}`,
        `-P${srcConfig.port}`,
        '--single-transaction',
        '--quick',
        '--default-character-set=utf8mb4'
    ];
    if (srcConfig.password) {
        dumpArgs.push(`-p${srcConfig.password}`);
    }
    dumpArgs.push(srcConfig.database);

    // Persiapan argumen mysql import
    const importArgs = [
        `-u${destConfig.user}`,
        `-h${destConfig.host}`,
        `-P${destConfig.port}`,
        '--default-character-set=utf8mb4'
    ];
    if (destConfig.password) {
        importArgs.push(`-p${destConfig.password}`);
    }
    importArgs.push(destConfig.database);

    const dumpProcess = spawn(mysqldumpBin, dumpArgs, { stdio: ['ignore', 'pipe', 'pipe'] });
    const importProcess = spawn(mysqlBin, importArgs, { stdio: ['pipe', 'inherit', 'pipe'] });

    dumpProcess.stdout.pipe(importProcess.stdin);

    let dumpErr = '';
    dumpProcess.stderr.on('data', (d) => { dumpErr += d.toString(); });

    let importErr = '';
    importProcess.stderr.on('data', (d) => { importErr += d.toString(); });

    const dumpPromise = new Promise((resolve, reject) => {
        dumpProcess.on('close', (code) => {
            if (code === 0) resolve();
            else reject(new Error(`mysqldump gagal (exit ${code}): ${dumpErr}`));
        });
    });

    const importPromise = new Promise((resolve, reject) => {
        importProcess.on('close', (code) => {
            if (code === 0) resolve();
            else reject(new Error(`mysql import gagal (exit ${code}): ${importErr}`));
        });
    });

    try {
        await Promise.all([dumpPromise, importPromise]);
        const elapsed = ((Date.now() - startTime) / 1000).toFixed(2);
        console.log(`✨ Migrasi database BERHASIL dalam ${elapsed} detik!\n`);

        // Verifikasi tabel dan baris di database tujuan
        console.log('📊 Ringkasan Data di Server Tujuan:');
        try {
            const verifyArgs = [
                `-u${destConfig.user}`,
                `-h${destConfig.host}`,
                `-P${destConfig.port}`
            ];
            if (destConfig.password) {
                verifyArgs.push(`-p${destConfig.password}`);
            }
            verifyArgs.push(
                destConfig.database,
                '-e',
                "SELECT 'master_materials' AS `Tabel`, count(*) AS `Jumlah Baris` FROM master_materials UNION ALL SELECT 'pallet_stickers', count(*) FROM pallet_stickers UNION ALL SELECT 'pallet_components', count(*) FROM pallet_components UNION ALL SELECT 'users', count(*) FROM users UNION ALL SELECT 'migrations', count(*) FROM migrations;"
            );

            const result = execSync(`"${mysqlBin}" ${verifyArgs.map(a => `"${a}"`).join(' ')}`, {
                stdio: 'pipe',
                encoding: 'utf-8'
            });

            // Filter warning insecure password dari mysql cli
            const cleanOutput = result
                .split(/\r?\n/)
                .filter(line => !line.toLowerCase().includes('insecure'))
                .join('\n')
                .trim();

            console.log(cleanOutput);
        } catch (vErr) {
            console.log('(Tidak dapat mengambil ringkasan baris, tetapi proses transfer telah selesai)');
        }

        console.log('\n====================================================');
        console.log('🎉 Selesai! Database siap digunakan pada server tujuan.');
        console.log('====================================================');
    } catch (err) {
        console.error('\n❌ Terjadi kesalahan saat migrasi:');
        console.error(err.message);
        process.exit(1);
    }
}

runMigration();
