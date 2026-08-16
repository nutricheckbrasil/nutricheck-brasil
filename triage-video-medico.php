<?php
$videos = [
    [
        'id' => 'video_1',
        'title' => 'Vídeo 1 - Introdução',
        'src' => 'assets/fallback/video_1.mp4',
        'questions' => [
            [
                'id' => 'v1_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 1',
                'text' => 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?',
                'instruction' => '',
                'yesLabel' => 'Vamos Começar!!',
                'yesValue' => 'Sim',
                'hideNoButton' => true
            ],
        ],
    ],
    [
        'id' => 'video_2',
        'title' => 'Vídeo 2 - Histórico Clínico',
        'src' => 'assets/fallback/video_2.mp4',
        'questions' => [
            [
                'id' => 'v2_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 2',
                'text' => 'Você realizou algum procedimento anterior?',
                'instruction' => ''
            ],
            [
                'id' => 'v2_q2',
                'type' => 'text',
                'title' => 'Pergunta 2 de 2',
                'text' => 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.',
                'instruction' => '',
                'showIf' => [
                    'questionIndex' => 0,
                    'equals' => 'Sim'
                ]
            ],
        ],
    ],
    [
        'id' => 'video_3',
        'title' => 'Vídeo 3 - Avaliação Cardiovascular',
        'src' => 'assets/fallback/video_3.mp4',
        'questions' => [
            [
                'id' => 'v3_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 4',
                'text' => 'Você tem pressão alta?',
                'instruction' => ''
            ],
            [
                'id' => 'v3_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 4',
                'text' => 'Você sente dor no peito?',
                'instruction' => ''
            ],
            [
                'id' => 'v3_q3',
                'type' => 'boolean',
                'title' => 'Pergunta 3 de 4',
                'text' => 'Você já teve infarto?',
                'instruction' => ''
            ],
            [
                'id' => 'v3_q4',
                'type' => 'boolean',
                'title' => 'Pergunta 4 de 4',
                'text' => 'Você já precisou colocar molinhas no coração?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_4',
        'title' => 'Vídeo 4 - Ritmo Cardíaco e Sintomas',
        'src' => 'assets/fallback/video_4.mp4',
        'questions' => [
            [
                'id' => 'v4_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 4',
                'text' => 'Você sente palpitação ou arritmia como descrito?',
                'instruction' => ''
            ],
            [
                'id' => 'v4_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 4',
                'text' => 'Você já teve desmaio no último mês?',
                'instruction' => ''
            ],
            [
                'id' => 'v4_q3',
                'type' => 'boolean',
                'title' => 'Pergunta 3 de 4',
                'text' => 'Você teve convulsão ou possui histórico de convulsão?',
                'instruction' => ''
            ],
            [
                'id' => 'v4_q4',
                'type' => 'boolean',
                'title' => 'Pergunta 4 de 4',
                'text' => 'Você usa marcapasso?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_5',
        'title' => 'Vídeo 5 - Revisão Cardiológica',
        'src' => 'assets/fallback/video_5.mp4',
        'questions' => [
            [
                'id' => 'v5_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 4',
                'text' => 'Você tem diabetes?',
                'instruction' => ''
            ],
            [
                'id' => 'v5_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 4',
                'text' => 'Você tem problema de tireoide?',
                'instruction' => ''
            ],
            [
                'id' => 'v5_q3',
                'type' => 'boolean',
                'title' => 'Pergunta 3 de 4',
                'text' => 'Você tem problema de rim (hemodiálise ou insuficiência renal)?',
                'instruction' => ''
            ],
            [
                'id' => 'v5_q4',
                'type' => 'boolean',
                'title' => 'Pergunta 4 de 4',
                'text' => 'Você já teve ou tem hepatite grave?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_6',
        'title' => 'Vídeo 6 - Outras Condições',
        'src' => 'assets/fallback/video_6.mp4',
        'questions' => [
            [
                'id' => 'v6_q1',
                'type' => 'text',
                'title' => 'Pergunta 1 de 1',
                'text' => 'Me conte sobre outras doenças que você pode ter.',
                'instruction' => 'Descreva com suas palavras'
            ],
        ],
    ],
    [
        'id' => 'video_7',
        'title' => 'Vídeo 7 - Avaliação Respiratória',
        'src' => 'assets/fallback/video_7.mp4',
        'questions' => [
            [
                'id' => 'v7_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 4',
                'text' => 'Você fuma atualmente?',
                'instruction' => ''
            ],
            [
                'id' => 'v7_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 4',
                'text' => 'Você já fumou no passado?',
                'instruction' => ''
            ],
            [
                'id' => 'v7_q3',
                'type' => 'boolean',
                'title' => 'Pergunta 3 de 4',
                'text' => 'Você tem asma ou bronquite?',
                'instruction' => ''
            ],
            [
                'id' => 'v7_q4',
                'type' => 'boolean',
                'title' => 'Pergunta 4 de 4',
                'text' => 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_8',
        'title' => 'Vídeo 8 - Sintomas Respiratórios Recentes',
        'src' => 'assets/fallback/video_8.mp4',
        'questions' => [
            [
                'id' => 'v8_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 2',
                'text' => 'No seu dia a dia, você tosse sempre?',
                'instruction' => ''
            ],
            [
                'id' => 'v8_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 2',
                'text' => 'Você teve gripe ou febre nos últimos 14 dias?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_9',
        'title' => 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica',
        'src' => 'assets/fallback/video_9.mp4',
        'questions' => [
            [
                'id' => 'v9_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 3',
                'text' => 'Você tem alguma doença neurológica?',
                'instruction' => ''
            ],
            [
                'id' => 'v9_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 3',
                'text' => 'Você já teve AVC (derrame)?',
                'instruction' => ''
            ],
            [
                'id' => 'v9_q3',
                'type' => 'boolean',
                'title' => 'Pergunta 3 de 3',
                'text' => 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_10',
        'title' => 'Vídeo 10 - Sangramento e Alergias',
        'src' => 'assets/fallback/video_10.mp4',
        'questions' => [
            [
                'id' => 'v10_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 3',
                'text' => 'Você já teve sangramento excessivo (no dentista ou cirurgia)?',
                'instruction' => ''
            ],
            [
                'id' => 'v10_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 3',
                'text' => 'Você tem alguma alergia?',
                'instruction' => ''
            ],
            [
                'id' => 'v10_q3',
                'type' => 'text',
                'title' => 'Pergunta 3 de 3',
                'text' => 'Qual alergia você tem?',
                'instruction' => '',
                'showIf' => [
                    'questionIndex' => 1,
                    'equals' => 'Sim'
                ]
            ],
        ],
    ],
    [
        'id' => 'video_11',
        'title' => 'Vídeo 11 - Capacidade Física e Medicamentos',
        'src' => 'assets/fallback/video_11.mp4',
        'questions' => [
            [
                'id' => 'v11_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 4',
                'text' => 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?',
                'instruction' => ''
            ],
            [
                'id' => 'v11_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 4',
                'text' => 'Você usa algum medicamento regularmente, todos os dias?',
                'instruction' => ''
            ],
            [
                'id' => 'v11_q3',
                'type' => 'text',
                'title' => 'Pergunta 3 de 4',
                'text' => 'Caso você use algum medicamento, descreva:',
                'instruction' => '',
                'showIf' => [
                    'questionIndex' => 1,
                    'equals' => 'Sim'
                ]
            ],
            [
                'id' => 'v11_q4',
                'type' => 'boolean',
                'title' => 'Pergunta 4 de 4',
                'text' => 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_12',
        'title' => 'Vídeo 12 - Histórico Oncológico e Hábitos',
        'src' => 'assets/fallback/video_12.mp4',
        'questions' => [
            [
                'id' => 'v12_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 4',
                'text' => 'Você já teve câncer?',
                'instruction' => ''
            ],
            [
                'id' => 'v12_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 4',
                'text' => 'Nos últimos seis meses, você perdeu peso?',
                'instruction' => ''
            ],
            [
                'id' => 'v12_q3',
                'type' => 'text',
                'title' => 'Pergunta 3 de 4',
                'text' => 'Caso tenha perdido peso, quanto quilos perdeu?',
                'instruction' => '',
                'showIf' => [
                    'questionIndex' => 1,
                    'equals' => 'Sim'
                ]
            ],
            [
                'id' => 'v12_q4',
                'type' => 'boolean',
                'title' => 'Pergunta 4 de 4',
                'text' => 'Faz uso de bebida alcoólica regularmente?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_13',
        'title' => 'Vídeo 13 - Avaliação Odontológica e Mobilidade',
        'src' => 'assets/fallback/video_13.mp4',
        'questions' => [
            [
                'id' => 'v13_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 2',
                'text' => 'Você usa prótese dentária, como dentadura ou chapa?',
                'instruction' => ''
            ],
            [
                'id' => 'v13_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 2',
                'text' => 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?',
                'instruction' => ''
            ],
        ],
    ],
    [
        'id' => 'video_14',
        'title' => 'Vídeo 14 - Classificação de Mallampati',
        'src' => 'assets/fallback/video_14.mp4',
        'questions' => [
            [
                'id' => 'v14_q1',
                'type' => 'choice',
                'title' => 'Pergunta 1 de 1',
                'text' => 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?',
                'instruction' => '',
                'options' => [
                    ['label' => 'Classe I', 'value' => 'Classe I'],
                    ['label' => 'Classe II', 'value' => 'Classe II'],
                    ['label' => 'Classe III', 'value' => 'Classe III'],
                    ['label' => 'Classe IV', 'value' => 'Classe IV']
                ]
            ],
        ],
    ],
    [
        'id' => 'video_15',
        'title' => 'Vídeo 15 - Exames Disponíveis',
        'src' => 'assets/fallback/video_15.mp4',
        'questions' => [
            [
                'id' => 'v15_q1',
                'type' => 'checkbox',
                'title' => 'Pergunta 1 de 1',
                'text' => 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?',
                'instruction' => '',
                'options' => [
                    ['label' => 'Hemograma', 'value' => 'Hemograma'],
                    ['label' => 'Creatinina', 'value' => 'Creatinina'],
                    ['label' => 'Tempo de Protrombina', 'value' => 'Tempo de Protrombina'],
                    ['label' => 'KPTT', 'value' => 'KPTT'],
                    ['label' => 'Glicemia de Jejum', 'value' => 'Glicemia de Jejum'],
                    ['label' => 'Eletrocardiograma', 'value' => 'Eletrocardiograma'],
                    ['label' => 'Nenhum', 'value' => 'Nenhum', 'special' => 'exclusive']
                ]
            ],
        ],
    ],
];

$videosJson = json_encode($videos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Entrevista Pré-anestésica com Dr. Avatar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #2c3e50;
        }
        
        
        .main-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .triage-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .card-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .card-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .progress-section {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .progress-label {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .progress-percentage {
            font-weight: 700;
            color: #667eea;
        }
        
        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 6px;
            transition: width 0.5s ease;
        }
        
        .video-section {
            padding: 40px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .video-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            max-height: 50vh;
        }
        
        .doctor-video {
            width: 100%;
            max-width: 100%;
            height: 300px;
            border-radius: 20px;
            display: block;
            object-fit: cover;
            -webkit-playsinline: true;
            playsinline: true;
            -webkit-media-controls: none;
            -webkit-media-controls-panel: none;
            -webkit-media-controls-play-button: none;
            -webkit-media-controls-timeline: none;
            -webkit-media-controls-current-time-display: none;
            -webkit-media-controls-time-remaining-display: none;
            -webkit-media-controls-mute-button: none;
            -webkit-media-controls-volume-slider: none;
            -webkit-media-controls-fullscreen-button: none;
        }
        
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .video-overlay.active {
            opacity: 1;
        }
        
        .play-button {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 30px;
            color: #667eea;
            transition: all 0.3s ease;
        }
        
        .play-button:hover {
            background: white;
            transform: scale(1.1);
        }
        
        .play-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .play-button:disabled:hover {
            transform: none;
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        }
        
        .video-controls {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }
        
        .time-display {
            font-size: 14px;
            font-weight: 500;
        }
        
        .progress-display {
            flex: 1;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill-video {
            height: 100%;
            background: #667eea;
            border-radius: 2px;
            transition: width 0.1s ease;
        }
        
        .start-section {
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
        }
        
        .start-button {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 auto;
            box-shadow: 0 6px 18px rgba(40, 167, 69, 0.28);
            min-width: 220px;
            justify-content: center;
            min-height: 52px;
        }
        
        .start-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
        }
        
        .start-button:active {
            transform: translateY(-2px);
        }
        
        .start-button:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .response-section {
            padding: 20px;
            background: #f8f9fa;
            display: none;
        }
        
        .response-section.active {
            display: block;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        
        .response-header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .response-header h3 {
            font-size: 20px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .response-header p {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .response-header p strong {
            font-size: 22px;
            font-weight: 700;
            display: block;
            color: #1a2a4a;
        }
        
        .response-buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 15px 0;
        }
        
        .response-input {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin: 15px 0;
        }
        
        .response-input textarea {
            width: 100%;
            max-width: 520px;
            min-height: 120px;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #ced4da;
            font-size: 16px;
            font-family: inherit;
            resize: vertical;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .response-input textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }
        
        .response-choices {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
            max-width: 600px;
            width: 100%;
        }
        
        .choice-btn {
            padding: 18px 25px;
            border: 3px solid #9b88d0;
            border-radius: 15px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 700;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            min-height: 55px;
            background: linear-gradient(135deg, #b8a8f0, #c5a5e0);
            color: #000;
        }
        
        .choice-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(184, 168, 240, 0.5);
            background: linear-gradient(135deg, #c5b5f5, #d2b5e5);
            border-color: #8b78c0;
        }
        
        .choice-btn:active {
            transform: translateY(-1px);
        }
        
        .checkbox-btn {
            padding: 16px 20px;
            border: 3px solid #b8a8f0;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            box-shadow: 0 3px 10px rgba(184, 168, 240, 0.2);
            min-height: 50px;
            background: linear-gradient(135deg, #d8c8f8, #e5d5f8);
            color: #000;
            text-align: center;
        }
        
        .checkbox-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(184, 168, 240, 0.4);
            border-color: #9b88d0;
            background: linear-gradient(135deg, #e5d5f8, #f2e5f8);
        }
        
        .checkbox-btn.selected {
            background: linear-gradient(135deg, #b8a8f0, #c5a5e0);
            border-color: #8b78c0;
            color: #000;
            font-weight: 700;
            box-shadow: 0 6px 20px rgba(184, 168, 240, 0.5);
        }
        
        .checkbox-btn.special {
            background: #fff3cd;
            border: 3px solid #ffc107;
            color: #856404;
        }
        
        .checkbox-btn.special:hover {
            border-color: #ffb300;
            background: #ffe69c;
        }
        
        .checkbox-btn.special.selected {
            background: #ffc107;
            border: 3px solid #ff9800;
            color: #000;
            font-weight: 700;
        }
        
        .checkbox-btn:active {
            transform: translateY(0);
        }
        
        .response-btn {
            padding: 15px 30px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 160px;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            min-height: 50px;
        }
        
        .btn-yes {
            background: linear-gradient(135deg, #b8a8f0, #c5a5e0);
            color: #000;
            border: 3px solid #9b88d0;
            font-weight: 700;
        }
        
        .btn-yes:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(184, 168, 240, 0.5);
            background: linear-gradient(135deg, #c5b5f5, #d2b5e5);
            border-color: #8b78c0;
        }
        
        .btn-no {
            background: linear-gradient(135deg, #b8a8f0, #c5a5e0);
            color: #000;
            border: 3px solid #9b88d0;
            font-weight: 700;
        }
        
        .btn-no:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(184, 168, 240, 0.5);
            background: linear-gradient(135deg, #c5b5f5, #d2b5e5);
            border-color: #8b78c0;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(41, 128, 185, 0.4);
        }
        
        .response-btn:active {
            transform: translateY(-2px);
        }
        
        .status-message {
            padding: 20px 25px;
            border-radius: 15px;
            margin: 25px 0;
            font-weight: 500;
            text-align: center;
            font-size: 16px;
        }
        
        .status-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 5px solid #17a2b8;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }
        
        .status-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 5px solid #ffc107;
        }
        
        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 0;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        
        .hidden {
            display: none;
        }
        
        @media (max-width: 768px) {
            body {
                font-size: 16px;
                line-height: 1.4;
            }
            
            
            .main-container {
                padding: 10px;
                min-height: 100vh;
            }
            
            .triage-card {
                border-radius: 15px;
                margin-bottom: 10px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            
            .card-header {
                padding: 20px 15px;
            }
            
            .card-header h2 {
                font-size: 20px;
                margin-bottom: 10px;
            }
            
            .card-header p {
                font-size: 14px;
            }
            
            .video-section {
                padding: 15px;
            }
            
            .video-container {
                margin: 10px 0;
                border-radius: 15px;
            }
            
            .doctor-video {
                height: 200px;
                border-radius: 15px;
            }
            
            .response-section {
                padding: 15px;
            }
            
            
            .response-header h3 {
                font-size: 16px;
                margin-bottom: 6px;
            }
            
            .response-header p {
                font-size: 18px;
            }
            
            .response-header p strong {
                font-size: 20px;
            }
            
            .response-buttons {
                flex-direction: column;
                gap: 15px;
                margin: 10px 0;
            }
            
            .response-input {
                width: 100%;
            }
            
            .response-input textarea {
                max-width: 100%;
                min-height: 100px;
            }
            
            .response-btn {
                width: 100%;
                padding: 15px 20px;
                font-size: 18px;
                min-height: 50px;
                border-radius: 12px;
                gap: 8px;
            }
            
            .response-choices {
                width: 100%;
                gap: 10px;
                grid-template-columns: 1fr 1fr;
                max-width: 100%;
            }
            
            .choice-btn {
                width: 100%;
                padding: 16px 15px;
                font-size: 16px;
                min-height: 50px;
            }
            
            .checkbox-btn {
                width: 100%;
                padding: 16px 15px;
                font-size: 16px;
                min-height: 50px;
            }
            
            .start-button {
                width: 85%;
                max-width: 260px;
                font-size: 17px;
                padding: 14px 24px;
                min-height: 48px;
                border-radius: 14px;
            }
            
            .progress-bar {
                height: 8px;
                margin: 15px 0;
            }
            
            .status-message {
                font-size: 14px;
                padding: 10px 15px;
                border-radius: 10px;
            }
        }
        
        @media (max-width: 480px) {
            
            .main-container {
                padding: 5px;
            }
            
            .triage-card {
                border-radius: 10px;
            }
            
            .card-header {
                padding: 15px 10px;
            }
            
            .card-header h2 {
                font-size: 18px;
            }
            
            .video-section {
                padding: 10px;
            }
            
            .doctor-video {
                height: 180px;
            }
            
            .response-section {
                padding: 10px;
            }
            
            .response-btn {
                padding: 12px 15px;
                font-size: 16px;
                min-height: 45px;
            }
            
            .response-choices {
                gap: 8px;
            }
            
            .choice-btn {
                padding: 14px 12px;
                font-size: 15px;
                min-height: 45px;
            }
            
            .checkbox-btn {
                padding: 14px 12px;
                font-size: 15px;
                min-height: 45px;
            }
            
            .start-button {
                padding: 12px 16px;
                font-size: 16px;
                min-height: 44px;
            }
        }
    </style>
</head>
<body>

    <main class="main-container">
        <div class="triage-card">
            <div class="card-header">
                <h2><i class="fas fa-stethoscope"></i> Entrevista Pré-anestésica com IA</h2>
                <p>Dr(a). Liege conduzirá uma entrevista pré-anestésica</p>
            </div>
            
            <div class="progress-section">
                <div class="progress-info">
                    <span class="progress-label" id="progressLabel">Progresso da Entrevista</span>
                    <span class="progress-percentage" id="progressPercentage">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                </div>
            </div>
            
            <div class="video-section">
                <div class="video-container">
                    <video class="doctor-video" id="doctorVideo" preload="metadata" 
                           playsinline webkit-playsinline 
                           disablepictureinpicture 
                           controlslist="nodownload nofullscreen noremoteplayback" 
                           x-webkit-airplay="deny">
                        <source src="assets/fallback/video_1.mp4" type="video/mp4">
                        Seu navegador não suporta vídeos HTML5.
                    </video>
                    
                    <div class="video-overlay" id="videoOverlay">
                        <button class="play-button" id="playButton">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    
                    <div class="video-controls" id="videoControls">
                        <div class="time-display" id="timeDisplay">00:00 / 00:00</div>
                        <div class="progress-display">
                            <div class="progress-fill-video" id="progressFillVideo"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="start-section" id="startSection">
                <button class="start-button" id="startButton">
                    <i class="fas fa-play"></i>
                    Vamos Iniciar a Entrevista
                </button>
            </div>
            
            <div class="response-section" id="responseSection">
                <div class="response-header">
                    <h3 id="responseTitle"><i class="fas fa-question-circle"></i> Sua resposta</h3>
                    <p id="responseInstruction">Clique em "Sim" ou "Não" para responder</p>
                </div>
                
                <div class="response-buttons" id="responseButtons">
                    <button class="response-btn btn-yes" id="yesBtn">
                        <i class="fas fa-check"></i>
                        Sim
                    </button>
                    <button class="response-btn btn-no" id="noBtn">
                        <i class="fas fa-times"></i>
                        Não
                    </button>
                </div>

                <div class="response-input hidden" id="responseInput">
                    <textarea id="textAnswer" placeholder="Digite sua resposta aqui..."></textarea>
                    <button class="response-btn btn-submit" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Enviar resposta
                    </button>
                </div>

                <div class="response-choices hidden" id="responseChoices">
                    <!-- Opções de múltipla escolha serão inseridas aqui dinamicamente -->
                </div>

                <div class="start-section hidden" id="continueSection">
                    <button class="start-button" id="continueButton">
                        <i class="fas fa-arrow-right"></i>
                        Continuar
                    </button>
                </div>
            </div>
        </div>
        
        <div class="status-message status-info" id="statusMessage">
            <i class="fas fa-info-circle"></i>
            Clique no botão para iniciar a entrevista pré-anestésica
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Sistema de Entrevista Pré-anestésica com IA</p>
    </footer>

    <script>
        const videosData = <?php echo $videosJson; ?>;
        
        let isStarted = false;
        let currentVideoIndex = 0;
        let currentQuestionIndex = 0;
        let completedVideos = 0;
        let currentPhase = 'waiting';
        let responses = [];
        
        const totalVideos = videosData.length;
        
        const video = document.getElementById('doctorVideo');
        const videoOverlay = document.getElementById('videoOverlay');
        const playButton = document.getElementById('playButton');
        const videoControls = document.getElementById('videoControls');
        const timeDisplay = document.getElementById('timeDisplay');
        const progressFillVideo = document.getElementById('progressFillVideo');
        const startSection = document.getElementById('startSection');
        const responseSection = document.getElementById('responseSection');
        const responseTitle = document.getElementById('responseTitle');
        const responseInstruction = document.getElementById('responseInstruction');
        const responseButtons = document.getElementById('responseButtons');
        const yesBtn = document.getElementById('yesBtn');
        const noBtn = document.getElementById('noBtn');
        const responseInput = document.getElementById('responseInput');
        const textAnswer = document.getElementById('textAnswer');
        const submitButton = document.getElementById('submitBtn');
        const responseChoices = document.getElementById('responseChoices');
        const continueSection = document.getElementById('continueSection');
        const continueButton = document.getElementById('continueButton');
        const statusMessage = document.getElementById('statusMessage');
        const progressLabel = document.getElementById('progressLabel');
        let currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
        let currentCheckboxSelections = [];
        
        document.getElementById('startButton').addEventListener('click', startInterview);
        document.getElementById('playButton').addEventListener('click', playVideo);
        yesBtn.addEventListener('click', () => respondQuestion(currentChoiceAnswers.yes));
        noBtn.addEventListener('click', () => respondQuestion(currentChoiceAnswers.no));
        document.getElementById('continueButton').addEventListener('click', continueToNextVideo);
        submitButton.addEventListener('click', submitTextResponse);
        textAnswer.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && (event.ctrlKey || event.metaKey)) {
                event.preventDefault();
                submitTextResponse();
            }
        });
        
        function startInterview() {
            if (isStarted) return;
            isStarted = true;
            currentPhase = 'video';
            startSection.style.display = 'none';
            updateProgress();
            prepareVideo(currentVideoIndex);
            setTimeout(() => {
                playVideo();
            }, 800);
        }
        
        function prepareVideo(index) {
            const videoData = videosData[index];
            if (!videoData) {
                finishTriagem();
                return;
            }
            
            currentVideoIndex = index;
            currentQuestionIndex = 0;
            currentPhase = 'video';
            
            videoOverlay.classList.add('active');
            playButton.disabled = false;
            playButton.style.opacity = '1';
            playButton.style.cursor = 'pointer';
            
            video.pause();
            video.currentTime = 0;
            progressFillVideo.style.width = '0%';
            timeDisplay.textContent = '00:00 / 00:00';
            
            const currentSource = video.querySelector('source');
            currentSource.src = videoData.src;
            video.load();
            
            progressLabel.textContent = 'Progresso da Entrevista';
            updateStatus(`🎬 ${videoData.title} pronto. Clique para assistir.`, 'info');
            
            hideQuestions();
        }
        
        function playVideo() {
            if (!videosData[currentVideoIndex]) return;
            videoOverlay.classList.remove('active');
            video.play().then(() => {
                currentPhase = 'video';
                updateStatus('🎬 Dr(a). Liege está falando...', 'info');
            }).catch(() => {
                updateStatus('❌ Não foi possível reproduzir o vídeo automaticamente. Clique em play.', 'error');
                videoOverlay.classList.add('active');
            });
        }
        
        function handleVideoEnd() {
            currentPhase = 'questions';
            playButton.disabled = true;
            playButton.style.opacity = '0.5';
            playButton.style.cursor = 'not-allowed';
            showQuestions();
        }
        
        function showQuestions() {
            const videoData = videosData[currentVideoIndex];
            const questions = videoData?.questions || [];
            
            if (questions.length === 0) {
                responses.push({
                    videoId: videoData.id,
                    videoTitle: videoData.title,
                    questionIndex: null,
                    questionText: null,
                    answer: null,
                    type: 'none',
                    timestamp: new Date().toISOString()
                });
                concludeVideo();
                return;
            }
            
            prepareQuestionInterface();
            updateStatus('📝 Responda às perguntas do vídeo para continuar.', 'warning');

            const firstQuestionIndex = getNextQuestionIndex(videoData, -1);
            if (firstQuestionIndex === null) {
                concludeVideo();
                return;
            }

            currentQuestionIndex = firstQuestionIndex;
            responseSection.classList.add('active');
            renderQuestion();
        }
        
        function renderQuestion() {
            const videoData = videosData[currentVideoIndex];
            const questions = videoData.questions || [];
            const question = questions[currentQuestionIndex];
            
            if (!question) {
                concludeVideo();
                return;
            }
            
            responseTitle.innerHTML = `<i class="fas fa-question-circle"></i> ${question.title || 'Pergunta'}`;
            if (question.instruction && question.instruction.trim() !== '') {
                responseInstruction.innerHTML = `<strong>${question.text || ''}</strong><br>${question.instruction}`;
            } else {
                responseInstruction.innerHTML = `<strong>${question.text || ''}</strong>`;
            }
            
            const questionType = question.type || 'boolean';
            
            if (questionType === 'boolean') {
                const yesLabel = question.yesLabel || 'Sim';
                const noLabel = question.noLabel || 'Não';
                const yesValue = question.yesValue || yesLabel;
                const noValue = question.noValue || noLabel;
                
                yesBtn.innerHTML = `<i class="fas fa-check"></i> ${yesLabel}`;
                noBtn.innerHTML = `<i class="fas fa-times"></i> ${noLabel}`;
                
                currentChoiceAnswers = {
                    yes: yesValue,
                    no: noValue
                };

                // Aplica cor verde se for "Vamos Começar!!"
                if (yesLabel === 'Vamos Começar!!') {
                    yesBtn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                    yesBtn.style.color = 'white';
                    yesBtn.style.border = '3px solid #218838';
                    yesBtn.style.boxShadow = '0 8px 25px rgba(40, 167, 69, 0.3)';
                    yesBtn.onmouseover = function() {
                        this.style.background = 'linear-gradient(135deg, #34ce57, #25d9a7)';
                        this.style.borderColor = '#1e7e34';
                        this.style.boxShadow = '0 15px 35px rgba(40, 167, 69, 0.4)';
                    };
                    yesBtn.onmouseout = function() {
                        this.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                        this.style.borderColor = '#218838';
                        this.style.boxShadow = '0 8px 25px rgba(40, 167, 69, 0.3)';
                    };
                } else {
                    // Remove estilos inline para voltar ao CSS padrão (roxo com texto preto)
                    yesBtn.style.background = '';
                    yesBtn.style.color = '';
                    yesBtn.style.border = '';
                    yesBtn.style.boxShadow = '';
                    yesBtn.onmouseover = null;
                    yesBtn.onmouseout = null;
                }

                if (question.hideNoButton) {
                    noBtn.classList.add('hidden');
                } else {
                    noBtn.classList.remove('hidden');
                }
            } else {
                currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
            }
            
            if (questionType === 'text') {
                responseButtons.classList.add('hidden');
                responseInput.classList.remove('hidden');
                responseChoices.classList.add('hidden');
                continueSection.classList.add('hidden');
                textAnswer.value = '';
                setTimeout(() => textAnswer.focus(), 100);
            } else if (questionType === 'choice') {
                responseButtons.classList.add('hidden');
                responseInput.classList.add('hidden');
                responseChoices.classList.remove('hidden');
                continueSection.classList.add('hidden');
                
                // Limpa opções anteriores
                responseChoices.innerHTML = '';
                
                // Cria botões para cada opção
                if (question.options && Array.isArray(question.options)) {
                    question.options.forEach((option, index) => {
                        const button = document.createElement('button');
                        button.className = 'choice-btn';
                        button.textContent = option.label || option.value || `Opção ${index + 1}`;
                        button.dataset.value = option.value || option.label;
                        button.addEventListener('click', () => respondQuestion(option.value || option.label));
                        responseChoices.appendChild(button);
                    });
                }
            } else if (questionType === 'checkbox') {
                responseButtons.classList.add('hidden');
                responseInput.classList.add('hidden');
                responseChoices.classList.remove('hidden');
                continueSection.classList.add('hidden');
                
                // Limpa seleções anteriores
                currentCheckboxSelections = [];
                
                // Limpa opções anteriores
                responseChoices.innerHTML = '';
                
                // Cria botões checkbox para cada opção
                if (question.options && Array.isArray(question.options)) {
                    question.options.forEach((option, index) => {
                        const button = document.createElement('button');
                        const isSpecial = option.special === 'exclusive';
                        button.className = isSpecial ? 'checkbox-btn special' : 'checkbox-btn';
                        button.textContent = option.label || option.value || `Opção ${index + 1}`;
                        button.dataset.value = option.value || option.label;
                        button.dataset.special = isSpecial ? 'true' : 'false';
                        button.addEventListener('click', () => toggleCheckboxOption(button, option));
                        responseChoices.appendChild(button);
                    });
                    
                    // Adiciona botão de confirmação que ocupa toda a largura
                    const confirmButton = document.createElement('button');
                    confirmButton.className = 'response-btn btn-submit';
                    confirmButton.innerHTML = '<i class="fas fa-check"></i> Confirmar';
                    confirmButton.style.marginTop = '20px';
                    confirmButton.style.width = '100%';
                    confirmButton.style.gridColumn = '1 / -1';
                    confirmButton.style.maxWidth = '600px';
                    confirmButton.style.marginLeft = 'auto';
                    confirmButton.style.marginRight = 'auto';
                    confirmButton.addEventListener('click', submitCheckboxAnswer);
                    confirmButton.id = 'checkboxConfirmBtn';
                    responseChoices.appendChild(confirmButton);
                }
            } else {
                responseButtons.classList.remove('hidden');
                responseInput.classList.add('hidden');
                responseChoices.classList.add('hidden');
                continueSection.classList.add('hidden');
            }
        }

        function toggleCheckboxOption(button, option) {
            const value = option.value || option.label;
            const isSpecial = option.special === 'exclusive';
            const isSelected = button.classList.contains('selected');
            
            if (isSpecial) {
                // Se é "nenhum" (exclusivo)
                if (!isSelected) {
                    // Selecionar "nenhum" - desmarcar todos os outros
                    currentCheckboxSelections = [value];
                    button.classList.add('selected');
                    
                    // Desmarcar todos os outros botões
                    const allButtons = responseChoices.querySelectorAll('.checkbox-btn:not(.special)');
                    allButtons.forEach(btn => {
                        btn.classList.remove('selected');
                        const btnValue = btn.dataset.value;
                        const index = currentCheckboxSelections.indexOf(btnValue);
                        if (index > -1) {
                            currentCheckboxSelections.splice(index, 1);
                        }
                    });
                } else {
                    // Desselecionar "nenhum"
                    button.classList.remove('selected');
                    currentCheckboxSelections = [];
                }
            } else {
                // Se é um exame normal
                if (!isSelected) {
                    // Selecionar exame - desmarcar "nenhum" se estiver selecionado
                    const nenhumButton = responseChoices.querySelector('.checkbox-btn.special');
                    if (nenhumButton && nenhumButton.classList.contains('selected')) {
                        nenhumButton.classList.remove('selected');
                        const nenhumValue = nenhumButton.dataset.value;
                        const index = currentCheckboxSelections.indexOf(nenhumValue);
                        if (index > -1) {
                            currentCheckboxSelections.splice(index, 1);
                        }
                    }
                    
                    button.classList.add('selected');
                    if (!currentCheckboxSelections.includes(value)) {
                        currentCheckboxSelections.push(value);
                    }
                } else {
                    // Desselecionar exame
                    button.classList.remove('selected');
                    const index = currentCheckboxSelections.indexOf(value);
                    if (index > -1) {
                        currentCheckboxSelections.splice(index, 1);
                    }
                }
            }
        }

        function submitCheckboxAnswer() {
            if (currentCheckboxSelections.length === 0) {
                updateStatus('⚠️ Por favor, selecione pelo menos uma opção.', 'warning');
                return;
            }
            
            respondQuestion(currentCheckboxSelections);
        }

        function respondQuestion(answer) {
            if (currentPhase !== 'questions') return;
            
            const videoData = videosData[currentVideoIndex];
            const questions = videoData.questions || [];
            const question = questions[currentQuestionIndex];
            
            if (!question) {
                concludeVideo();
                return;
            }
            
            const questionType = question.type || 'boolean';
            let processedAnswer;
            
            if (questionType === 'text') {
                processedAnswer = (answer || '').trim();
            } else if (questionType === 'checkbox') {
                processedAnswer = Array.isArray(answer) ? answer : [answer];
            } else {
                processedAnswer = answer;
            }
            
            if (questionType === 'text' && processedAnswer.length === 0) {
                updateStatus('✏️ Por favor, preencha sua resposta antes de continuar.', 'warning');
                textAnswer.focus();
                return;
            }
            
            if (questionType === 'checkbox' && processedAnswer.length === 0) {
                updateStatus('⚠️ Por favor, selecione pelo menos uma opção.', 'warning');
                return;
            }
            
            responses.push({
                videoId: videoData.id,
                videoTitle: videoData.title,
                questionIndex: currentQuestionIndex + 1,
                questionText: question.text,
                answer: processedAnswer,
                type: questionType,
                questionId: question.id || null,
                timestamp: new Date().toISOString()
            });
            
            let statusMessageText;
            if (questionType === 'text') {
                statusMessageText = '✅ Resposta registrada.';
            } else if (questionType === 'checkbox') {
                statusMessageText = `✅ ${processedAnswer.length} opção(ões) registrada(s): ${processedAnswer.join(', ')}`;
            } else {
                statusMessageText = `✅ Resposta registrada: ${processedAnswer}`;
            }
            updateStatus(statusMessageText, 'success');
            
            const nextQuestionIndex = getNextQuestionIndex(videoData, currentQuestionIndex);
            if (nextQuestionIndex !== null) {
                currentQuestionIndex = nextQuestionIndex;
                renderQuestion();
            } else {
                concludeVideo();
            }
        }
        
        function submitTextResponse() {
            respondQuestion(textAnswer.value);
        }

        function getVideoResponses(videoId) {
            return responses.filter(response => response.videoId === videoId);
        }

        function shouldShowQuestion(videoData, questionIndex) {
            const questions = videoData.questions || [];
            const question = questions[questionIndex];
            
            if (!question) return false;
            if (!question.showIf) return true;

            const condition = question.showIf;
            const videoResponses = getVideoResponses(videoData.id);
            let referenceResponse = null;

            if (condition.questionId) {
                referenceResponse = videoResponses.find(response => response.questionId === condition.questionId);
            } else if (typeof condition.questionIndex === 'number') {
                referenceResponse = videoResponses.find(response => response.questionIndex === condition.questionIndex + 1);
            }

            if (!referenceResponse) return false;

            if (condition.equals !== undefined) {
                return referenceResponse.answer === condition.equals;
            }

            if (condition.notEquals !== undefined) {
                return referenceResponse.answer !== condition.notEquals;
            }

            if (Array.isArray(condition.in)) {
                return condition.in.includes(referenceResponse.answer);
            }

            if (Array.isArray(condition.notIn)) {
                return !condition.notIn.includes(referenceResponse.answer);
            }

            return true;
        }

        function getNextQuestionIndex(videoData, fromIndex) {
            const questions = videoData.questions || [];
            let nextIndex = fromIndex + 1;

            while (nextIndex < questions.length) {
                if (shouldShowQuestion(videoData, nextIndex)) {
                    return nextIndex;
                }
                nextIndex++;
            }

            return null;
        }
        
        function concludeVideo() {
            completedVideos = Math.max(completedVideos, currentVideoIndex + 1);
            updateProgress();
            currentPhase = 'awaitingNext';
            
            responseButtons.classList.add('hidden');
            responseInput.classList.add('hidden');
            responseChoices.classList.add('hidden');
            continueSection.classList.remove('hidden');
            continueButton.focus();
            
            const hasNextVideo = completedVideos < totalVideos;
            continueButton.innerHTML = hasNextVideo
                ? '<i class="fas fa-arrow-right"></i> Continuar para o próximo vídeo'
                : '<i class="fas fa-flag-checkered"></i> Finalizar entrevista';
            
            responseSection.classList.add('active');
            responseTitle.innerHTML = '<i class="fas fa-check-circle"></i> Etapa concluída';
            responseInstruction.innerHTML = hasNextVideo
                ? 'Clique em continuar para assistir ao próximo vídeo.'
                : 'Clique em finalizar para encerrar a entrevista e revisar as respostas.';
            
            updateStatus(hasNextVideo
                ? '✅ Vídeo concluído. Avance para o próximo quando estiver pronto.'
                : '🏁 Todos os vídeos foram concluídos. Finalize para ver o resumo.', hasNextVideo ? 'success' : 'success');
            
            if (!hasNextVideo) {
                continueButton.dataset.action = 'finish';
            } else {
                continueButton.dataset.action = 'next';
            }
        }
        
        function prepareQuestionInterface() {
            responseButtons.classList.remove('hidden');
            responseInput.classList.add('hidden');
            responseChoices.classList.add('hidden');
            continueSection.classList.add('hidden');
            textAnswer.value = '';
            currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
            currentCheckboxSelections = [];
            noBtn.classList.remove('hidden');
        }
        
        function continueToNextVideo() {
            if (continueButton.dataset.action === 'finish') {
                finishTriagem();
                return;
            }
            
            if (completedVideos >= totalVideos) {
                finishTriagem();
                return;
            }
            
            continueSection.classList.add('hidden');
            responseSection.classList.remove('active');
            prepareVideo(completedVideos);
            
            setTimeout(() => {
                playVideo();
            }, 600);
        }
        
        function updateProgress() {
            const progress = Math.round((completedVideos / totalVideos) * 100);
            document.getElementById('progressFill').style.width = progress + '%';
            document.getElementById('progressPercentage').textContent = progress + '%';
            progressLabel.textContent = 'Progresso da Entrevista';
        }
        
        function hideQuestions() {
            responseSection.classList.remove('active');
            responseButtons.classList.remove('hidden');
            responseInput.classList.add('hidden');
            responseChoices.classList.add('hidden');
            continueSection.classList.add('hidden');
            textAnswer.value = '';
            currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
            currentCheckboxSelections = [];
            noBtn.classList.remove('hidden');
        }
        
        function updateStatus(message, type = 'info') {
            const icon = type === 'success' ? 'fas fa-check-circle' : 
                        type === 'error' ? 'fas fa-exclamation-circle' :
                        type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
            
            statusMessage.style.display = 'block';
            statusMessage.innerHTML = `<i class="${icon}"></i> ${message}`;
            statusMessage.className = `status-message status-${type}`;
        }
        
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        
        function updateVideoProgress() {
            if (video.duration) {
                const progress = (video.currentTime / video.duration) * 100;
                progressFillVideo.style.width = progress + '%';
                timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
            }
        }
        
        function finishTriagem() {
            currentPhase = 'finished';
            updateProgress();
            updateStatus('🎉 Entrevista concluída com sucesso!', 'success');
            
            playButton.disabled = true;
            playButton.style.opacity = '0.5';
            playButton.style.cursor = 'not-allowed';
            
            showResults();
        }
        
        function showResults() {
            const groupedResponses = responses.reduce((acc, item) => {
                if (!acc[item.videoId]) {
                    acc[item.videoId] = {
                        videoTitle: item.videoTitle,
                        answers: []
                    };
                }
                if (item.questionText) {
                    acc[item.videoId].answers.push(item);
                }
                return acc;
            }, {});
            
            const resultsHtml = Object.values(groupedResponses).map(group => {
                const answersHtml = group.answers.length > 0 ? group.answers.map(response => {
                    let answerDisplay;
                    let answerColor = '#2c3e50';
                    
                    if (response.type === 'boolean') {
                        answerColor = response.answer === 'Sim' ? '#28a745' : '#dc3545';
                        answerDisplay = response.answer;
                    } else if (response.type === 'checkbox' && Array.isArray(response.answer)) {
                        answerColor = '#2c3e50';
                        answerDisplay = response.answer.length > 0 
                            ? response.answer.join(', ') 
                            : 'Nenhuma opção selecionada';
                    } else {
                        answerDisplay = response.answer;
                    }
                    
                    return `
                        <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 12px; border-left: 5px solid #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <strong>Pergunta ${response.questionIndex}:</strong> ${response.questionText}<br>
                            <strong>Resposta:</strong> <span style="color: ${answerColor}; font-weight: bold;">${answerDisplay}</span><br>
                            <small style="color: #6c757d;">Data: ${new Date(response.timestamp).toLocaleString('pt-BR')}</small>
                        </div>
                    `;
                }).join('') : `
                    <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 12px; border-left: 5px solid #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <strong>Nenhuma pergunta associada a este vídeo.</strong>
                    </div>
                `;
                
                return `
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #2c3e50; margin-bottom: 10px;">${group.videoTitle}</h4>
                        ${answersHtml}
                    </div>
                `;
            }).join('');
            
            responseSection.innerHTML = `
                <div class="response-header">
                    <h3><i class="fas fa-chart-line"></i> Resultados da Entrevista</h3>
                    <p>Resumo das respostas do paciente</p>
                </div>
                <div style="text-align: left; margin: 20px 0;">
                    ${resultsHtml || '<p>Nenhuma resposta registrada.</p>'}
                </div>
            `;
            responseSection.classList.add('active');
        }
        
        video.addEventListener('timeupdate', updateVideoProgress);
        video.addEventListener('ended', handleVideoEnd);
        
        video.addEventListener('play', function() {
            videoControls.style.display = 'flex';
        });
        
        video.addEventListener('pause', function() {
            if (currentPhase === 'questions' || currentPhase === 'awaitingNext') {
                videoControls.style.display = 'flex';
            }
        });
        
        video.addEventListener('loadedmetadata', function() {
            updateVideoProgress();
            if (!isStarted) {
                updateStatus('📹 Vídeo carregado - Pronto para iniciar entrevista', 'success');
            }
        });
        
        video.addEventListener('error', function() {
            updateStatus('❌ Erro ao carregar o vídeo', 'error');
        });
        
        updateProgress();
    </script>
</body>
</html>

