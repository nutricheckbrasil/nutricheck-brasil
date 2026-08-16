-- Corrige a view que veio com DEFINER de outra conta (anestesioreal).
-- Execute no phpMyAdmin da Hostinger após importar o dump.
-- Substitua u633289092_nutricheck pelo usuário do seu banco NutriCheck, se for diferente.

DROP VIEW IF EXISTS view_paciente_video_desempenho;

CREATE VIEW view_paciente_video_desempenho AS
SELECT
    p.id AS paciente_id,
    p.nome AS nome,
    p.email AS email,
    v.id AS video_id,
    v.titulo AS video_titulo,
    vs.status AS status_sessao,
    vs.percentual_conclusao AS percentual_conclusao,
    COUNT(vr.id) AS total_respostas,
    SUM(CASE WHEN vr.correta = 1 THEN 1 ELSE 0 END) AS respostas_corretas,
    CASE
        WHEN COUNT(vr.id) > 0 THEN SUM(CASE WHEN vr.correta = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(vr.id)
        ELSE 0
    END AS percentual_acerto,
    vs.started_at AS started_at,
    vs.completed_at AS completed_at
FROM pacientes p
JOIN video_sessoes vs ON p.id = vs.paciente_id
JOIN videos_interativos v ON vs.video_id = v.id
LEFT JOIN video_respostas vr ON vs.id = vr.sessao_id
GROUP BY p.id, p.nome, p.email, v.id, v.titulo, vs.id, vs.status, vs.percentual_conclusao, vs.started_at, vs.completed_at;
