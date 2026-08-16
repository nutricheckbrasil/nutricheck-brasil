<?php
/**
 * Definições das perguntas do questionário em vídeo.
 * 
 * Esta estrutura replica a mesma utilizada no frontend
 * para que possamos calcular o total de perguntas esperadas
 * de forma consistente no back-end (relatório PDF).
 */

return [
    'video_1' => [
        'title' => 'Vídeo 1 - Introdução',
        'questions' => [
            ['id' => 'v1_q1', 'type' => 'boolean'],
        ],
    ],
    'video_2' => [
        'title' => 'Vídeo 2 - Histórico Clínico',
        'questions' => [
            ['id' => 'v2_q1', 'type' => 'boolean'],
            [
                'id' => 'v2_q2',
                'type' => 'text',
                'showIf' => [
                    'questionId' => 'v2_q1',
                    'equals' => 'Sim',
                ],
            ],
        ],
    ],
    'video_3' => [
        'title' => 'Vídeo 3 - Avaliação Cardiovascular',
        'questions' => [
            ['id' => 'v3_q1', 'type' => 'boolean'],
            ['id' => 'v3_q2', 'type' => 'boolean'],
            ['id' => 'v3_q3', 'type' => 'boolean'],
            ['id' => 'v3_q4', 'type' => 'boolean'],
        ],
    ],
    'video_4' => [
        'title' => 'Vídeo 4 - Ritmo Cardíaco e Sintomas',
        'questions' => [
            ['id' => 'v4_q1', 'type' => 'boolean'],
            ['id' => 'v4_q2', 'type' => 'boolean'],
            ['id' => 'v4_q3', 'type' => 'boolean'],
            ['id' => 'v4_q4', 'type' => 'boolean'],
        ],
    ],
    'video_5' => [
        'title' => 'Vídeo 5 - Revisão Cardiológica',
        'questions' => [
            ['id' => 'v5_q1', 'type' => 'boolean'],
            ['id' => 'v5_q2', 'type' => 'boolean'],
            ['id' => 'v5_q3', 'type' => 'boolean'],
            ['id' => 'v5_q4', 'type' => 'boolean'],
        ],
    ],
    'video_6' => [
        'title' => 'Vídeo 6 - Outras Condições',
        'questions' => [
            ['id' => 'v6_q1', 'type' => 'text'],
        ],
    ],
    'video_7' => [
        'title' => 'Vídeo 7 - Avaliação Respiratória',
        'questions' => [
            ['id' => 'v7_q1', 'type' => 'boolean'],
            ['id' => 'v7_q2', 'type' => 'boolean'],
            ['id' => 'v7_q3', 'type' => 'boolean'],
            ['id' => 'v7_q4', 'type' => 'boolean'],
        ],
    ],
    'video_8' => [
        'title' => 'Vídeo 8 - Sintomas Respiratórios Recentes',
        'questions' => [
            ['id' => 'v8_q1', 'type' => 'boolean'],
            ['id' => 'v8_q2', 'type' => 'boolean'],
        ],
    ],
    'video_9' => [
        'title' => 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica',
        'questions' => [
            ['id' => 'v9_q1', 'type' => 'boolean'],
            ['id' => 'v9_q2', 'type' => 'boolean'],
            ['id' => 'v9_q3', 'type' => 'boolean'],
        ],
    ],
    'video_10' => [
        'title' => 'Vídeo 10 - Sangramento e Alergias',
        'questions' => [
            ['id' => 'v10_q1', 'type' => 'boolean'],
            ['id' => 'v10_q2', 'type' => 'boolean'],
            [
                'id' => 'v10_q3',
                'type' => 'text',
                'showIf' => [
                    'questionId' => 'v10_q2',
                    'equals' => 'Sim',
                ],
            ],
        ],
    ],
    'video_11' => [
        'title' => 'Vídeo 11 - Capacidade Física e Medicamentos',
        'questions' => [
            ['id' => 'v11_q1', 'type' => 'boolean'],
            ['id' => 'v11_q2', 'type' => 'boolean'],
            [
                'id' => 'v11_q3',
                'type' => 'text',
                'showIf' => [
                    'questionId' => 'v11_q2',
                    'equals' => 'Sim',
                ],
            ],
            ['id' => 'v11_q4', 'type' => 'boolean'],
        ],
    ],
    'video_12' => [
        'title' => 'Vídeo 12 - Histórico Oncológico e Hábitos',
        'questions' => [
            ['id' => 'v12_q1', 'type' => 'boolean'],
            ['id' => 'v12_q2', 'type' => 'boolean'],
            [
                'id' => 'v12_q3',
                'type' => 'text',
                'showIf' => [
                    'questionId' => 'v12_q2',
                    'equals' => 'Sim',
                ],
            ],
            ['id' => 'v12_q4', 'type' => 'boolean'],
        ],
    ],
    'video_13' => [
        'title' => 'Vídeo 13 - Avaliação Odontológica e Mobilidade',
        'questions' => [
            ['id' => 'v13_q1', 'type' => 'boolean'],
            ['id' => 'v13_q2', 'type' => 'boolean'],
        ],
    ],
    'video_14' => [
        'title' => 'Vídeo 14 - Classificação de Mallampati',
        'questions' => [
            ['id' => 'v14_q1', 'type' => 'choice'],
        ],
    ],
    'video_15' => [
        'title' => 'Vídeo 15 - Exames Disponíveis',
        'questions' => [
            ['id' => 'v15_q1', 'type' => 'checkbox'],
        ],
    ],
];

