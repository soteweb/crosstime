<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PDO;

class DatabaseBackupService
{
    /**
     * Obtener estadísticas de la base de datos para mostrar en el panel.
     */
    public function getDatabaseStats(): array
    {
        $driver = DB::connection()->getDriverName();
        $dbName = DB::connection()->getDatabaseName();
        $tables = $this->getTablesList();
        $totalTables = count($tables);

        $sizeBytes = 0;
        $totalRows = 0;

        try {
            if (in_array($driver, ['mysql', 'mariadb'])) {
                $sizeData = DB::selectOne("
                    SELECT 
                        SUM(data_length + index_length) AS size_bytes,
                        SUM(table_rows) AS total_rows
                    FROM information_schema.TABLES 
                    WHERE table_schema = ?
                ", [$dbName]);

                $sizeBytes = $sizeData->size_bytes ?? 0;
                $totalRows = $sizeData->total_rows ?? 0;
            } elseif ($driver === 'pgsql') {
                $sizeData = DB::selectOne("SELECT pg_database_size(current_database()) as size_bytes");
                $sizeBytes = $sizeData->size_bytes ?? 0;

                // Contar filas de tablas principales
                foreach ($tables as $t) {
                    $totalRows += DB::table($t)->count();
                }
            } else {
                foreach ($tables as $t) {
                    $totalRows += DB::table($t)->count();
                }
            }
        } catch (\Throwable $e) {
            // Fallback silencioso si falla la consulta de tamaño
            $sizeBytes = 0;
        }

        return [
            'driver' => strtoupper($driver),
            'database' => $dbName,
            'tables_count' => $totalTables,
            'total_rows' => $totalRows,
            'size_formatted' => $this->formatBytes($sizeBytes),
            'size_bytes' => $sizeBytes,
        ];
    }

    /**
     * Generar archivo de respaldo y devolver la ruta del archivo generado.
     */
    public function createBackupFile(bool $compressGzip = false): string
    {
        $tempDir = storage_path('app/backups');
        File::ensureDirectoryExists($tempDir);

        $timestamp = date('Y-m-d_H-i-s');
        $fileName = "backup_itapuacross_{$timestamp}." . ($compressGzip ? 'sql.gz' : 'sql');
        $filePath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

        $handle = $compressGzip ? gzopen($filePath, 'wb9') : fopen($filePath, 'wb');
        if (!$handle) {
            throw new \RuntimeException("No se pudo abrir el archivo temporal de respaldo en: {$filePath}");
        }

        $writer = function (string $text) use ($handle, $compressGzip) {
            if ($compressGzip) {
                gzwrite($handle, $text);
            } else {
                fwrite($handle, $text);
            }
        };

        $driver = DB::connection()->getDriverName();
        $dbName = DB::connection()->getDatabaseName();
        $now = date('Y-m-d H:i:s');

        // 1. Cabecera SQL
        $writer("-- ===========================================================\n");
        $writer("-- ITAPUA CROSS / CROSSTIME - COPIA DE SEGURIDAD\n");
        $writer("-- Generado: {$now} (Servidor)\n");
        $writer("-- Motor de BD: {$driver}\n");
        $writer("-- Base de Datos: {$dbName}\n");
        $writer("-- ===========================================================\n\n");

        if (in_array($driver, ['mysql', 'mariadb'])) {
            $writer("SET FOREIGN_KEY_CHECKS = 0;\n");
            $writer("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n");
            $writer("SET time_zone = '+00:00';\n");
            $writer("SET NAMES utf8mb4;\n\n");
        }

        // 2. Tablas
        $tables = $this->getTablesList();

        foreach ($tables as $table) {
            $quotedTable = $this->quoteIdentifier($driver, $table);

            $writer("-- -----------------------------------------------------------\n");
            $writer("-- Estructura y datos de la tabla: {$quotedTable}\n");
            $writer("-- -----------------------------------------------------------\n");

            // DROP TABLE
            $writer("DROP TABLE IF EXISTS {$quotedTable};\n");

            // CREATE TABLE
            $createTableSql = $this->getCreateTableSql($driver, $table);
            if ($createTableSql) {
                $writer($createTableSql . ";\n\n");
            }

            // INSERT DATA
            $this->dumpTableData($driver, $table, $writer);

            $writer("\n\n");
        }

        // 3. Cierre
        if (in_array($driver, ['mysql', 'mariadb'])) {
            $writer("SET FOREIGN_KEY_CHECKS = 1;\n");
        }

        $writer("-- Fin del Respaldo\n");

        if ($compressGzip) {
            gzclose($handle);
        } else {
            fclose($handle);
        }

        return $filePath;
    }

    /**
     * Lista de tablas sin prefijos ni esquemas.
     */
    protected function getTablesList(): array
    {
        $rawTables = Schema::getTableListing();
        $clean = [];

        foreach ($rawTables as $t) {
            $name = preg_replace('/^(public|dbo)\./i', '', $t);
            if (!in_array($name, $clean)) {
                $clean[] = $name;
            }
        }

        sort($clean);
        return $clean;
    }

    /**
     * Obtener el DDL CREATE TABLE según el motor.
     */
    protected function getCreateTableSql(string $driver, string $table): ?string
    {
        try {
            if (in_array($driver, ['mysql', 'mariadb'])) {
                $row = DB::selectOne("SHOW CREATE TABLE `{$table}`");
                if ($row) {
                    $arr = (array)$row;
                    return $arr['Create Table'] ?? null;
                }
            } elseif ($driver === 'pgsql') {
                return $this->generatePgsqlCreateTable($table);
            }
        } catch (\Throwable $e) {
            // Ignorar y continuar con datos si la estructura no se puede extraer
        }

        return null;
    }

    /**
     * Generador básico de CREATE TABLE para PostgreSQL/fallback.
     */
    protected function generatePgsqlCreateTable(string $table): string
    {
        $columns = Schema::getColumns($table);
        $lines = [];

        foreach ($columns as $c) {
            $name = '"' . str_replace('"', '""', $c['name']) . '"';
            $type = $c['type'];
            $nullable = $c['nullable'] ? 'NULL' : 'NOT NULL';
            $default = !is_null($c['default']) ? 'DEFAULT ' . $c['default'] : '';
            $autoInc = $c['auto_increment'] ?? false;

            if ($autoInc && in_array($type, ['integer', 'bigint'])) {
                $type = $type === 'bigint' ? 'BIGSERIAL' : 'SERIAL';
                $lines[] = "    {$name} {$type} PRIMARY KEY";
            } else {
                $line = "    {$name} {$type} {$nullable}";
                if ($default) {
                    $line .= " {$default}";
                }
                $lines[] = trim($line);
            }
        }

        return "CREATE TABLE \"{$table}\" (\n" . implode(",\n", $lines) . "\n)";
    }

    /**
     * Volcar datos de una tabla en bloques para optimizar RAM.
     */
    protected function dumpTableData(string $driver, string $table, callable $writer): void
    {
        $pdo = DB::connection()->getPdo();
        $quotedTable = $this->quoteIdentifier($driver, $table);
        $batch = [];
        $batchSize = 100;

        foreach (DB::table($table)->cursor() as $record) {
            $batch[] = (array)$record;

            if (count($batch) >= $batchSize) {
                $this->writeInsertStatement($driver, $quotedTable, $batch, $pdo, $writer);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $this->writeInsertStatement($driver, $quotedTable, $batch, $pdo, $writer);
        }
    }

    /**
     * Escribir un bloque INSERT INTO masivo.
     */
    protected function writeInsertStatement(string $driver, string $quotedTable, array $batch, PDO $pdo, callable $writer): void
    {
        if (empty($batch)) {
            return;
        }

        $columns = array_keys($batch[0]);
        $quotedCols = array_map(fn($col) => $this->quoteIdentifier($driver, $col), $columns);
        $colsSql = implode(', ', $quotedCols);

        $rowsSql = [];

        foreach ($batch as $row) {
            $valStrings = [];
            foreach ($columns as $col) {
                $val = $row[$col] ?? null;

                if (is_null($val)) {
                    $valStrings[] = 'NULL';
                } elseif (is_bool($val)) {
                    $valStrings[] = $val ? '1' : '0';
                } elseif (is_int($val) || is_float($val)) {
                    $valStrings[] = (string)$val;
                } else {
                    $valStrings[] = $pdo->quote((string)$val);
                }
            }
            $rowsSql[] = '(' . implode(', ', $valStrings) . ')';
        }

        $sql = "INSERT INTO {$quotedTable} ({$colsSql}) VALUES\n" . implode(",\n", $rowsSql) . ";\n";
        $writer($sql);
    }

    /**
     * Escapar nombres de tablas y columnas según motor.
     */
    protected function quoteIdentifier(string $driver, string $identifier): string
    {
        if ($driver === 'pgsql') {
            return '"' . str_replace('"', '""', $identifier) . '"';
        }
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    /**
     * Formatear bytes a formato legible (KB, MB, GB).
     */
    protected function formatBytes(int|float $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $power), 2) . ' ' . ($units[$power] ?? 'B');
    }
}
