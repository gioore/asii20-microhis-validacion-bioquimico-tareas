-- Esquema SQLite del micro-HIS (datos ficticios)
-- Tabla principal de resultados de laboratorio del módulo ASII-20.

CREATE TABLE IF NOT EXISTS lab_results (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    tenant_id      TEXT    NOT NULL,
    patient_name   TEXT    NOT NULL,
    test_name      TEXT    NOT NULL,
    value          TEXT    NOT NULL,
    unit           TEXT    NOT NULL,
    reference_low  TEXT,
    reference_high TEXT,
    is_critical    INTEGER NOT NULL DEFAULT 0,
    status         TEXT    NOT NULL DEFAULT 'pendiente',
    validated_by   TEXT,
    validated_at   TEXT,
    rejected_reason TEXT,
    created_at     TEXT    NOT NULL,
    updated_at     TEXT    NOT NULL
);

-- Alertas críticas generadas al confirmar un valor crítico (CU-05).
CREATE TABLE IF NOT EXISTS critical_alerts (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    result_id   INTEGER NOT NULL,
    patient_name TEXT   NOT NULL,
    message     TEXT    NOT NULL,
    created_at  TEXT    NOT NULL,
    FOREIGN KEY (result_id) REFERENCES lab_results(id)
);

-- Índice para la consulta de pendientes (RF-01).
CREATE INDEX IF NOT EXISTS idx_lab_results_status ON lab_results(status);