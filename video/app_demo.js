// ✅ SISTEMA DE VÍDEO INTERATIVO COMPLETO
console.log('🚀 Sistema de Vídeo Interativo com Perguntas carregado!');

// ✅ VARIÁVEIS GLOBAIS
let currentVideo = null;
let currentSession = null;
let questions = [];
let currentQuestionIndex = -1;
let videoPlayer = null;
let isQuestionActive = false;

// ✅ FUNÇÃO PARA MOSTRAR ALERTAS
function showAlert(message, title = 'Aviso', type = 'info') {
    document.getElementById('alertTitle').textContent = title;
    document.getElementById('alertMessage').textContent = message;
    new bootstrap.Modal(document.getElementById('alertModal')).show();
}

// ✅ FUNÇÃO PARA FORMATAR TEMPO
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

// ✅ FUNÇÃO PARA CARREGAR VÍDEOS
async function loadVideos() {
    try {
        const response = await fetch('api_demo.php?action=list');
        const data = await response.json();
        
        const videosList = document.getElementById('videosList');
        const videoSelect = document.getElementById('videoSelect');
        const editorVideoSelect = document.getElementById('editorVideoSelect');
        const embedVideoSelect = document.getElementById('embedVideoSelect');
        
        // Limpar selects
        if (videoSelect) videoSelect.innerHTML = '<option value="">Selecione um vídeo...</option>';
        if (editorVideoSelect) editorVideoSelect.innerHTML = '<option value="">Selecione um vídeo para editar...</option>';
        if (embedVideoSelect) embedVideoSelect.innerHTML = '<option value="">Selecione um vídeo...</option>';
        
        if (data.success && data.videos.length > 0) {
            videosList.innerHTML = data.videos.map(video => `
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">${video.title}</h6>
                            <p class="card-text text-muted">${video.description || 'Sem descrição'}</p>
                            <small class="text-muted">Por: ${video.author || 'Anônimo'}</small>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-sm" onclick="playVideo(${video.id})">
                                <i class="bi bi-play"></i> Assistir
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="editVideo(${video.id})">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="generateEmbed(${video.id})">
                                <i class="bi bi-code-slash"></i> Embed
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Adicionar aos selects
            data.videos.forEach(video => {
                if (videoSelect) videoSelect.innerHTML += `<option value="${video.id}">${video.title}</option>`;
                if (editorVideoSelect) editorVideoSelect.innerHTML += `<option value="${video.id}">${video.title}</option>`;
                if (embedVideoSelect) embedVideoSelect.innerHTML += `<option value="${video.id}">${video.title}</option>`;
            });
        } else {
            videosList.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Nenhum vídeo encontrado. Faça upload do seu primeiro vídeo!
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Erro ao carregar vídeos:', error);
        document.getElementById('videosList').innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    Erro ao carregar vídeos: ${error.message}
                </div>
            </div>
        `;
    }
}

// ✅ FUNÇÃO PARA CARREGAR ESTATÍSTICAS
async function loadStats() {
    try {
        const response = await fetch('api_demo.php?action=stats');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalVideos').textContent = data.stats.videos || 0;
            document.getElementById('totalQuestions').textContent = data.stats.questions || 0;
            document.getElementById('totalResponses').textContent = data.stats.responses || 0;
            document.getElementById('totalSessions').textContent = data.stats.sessions || 0;
        }
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

// ✅ FUNÇÃO PARA REPRODUZIR VÍDEO
function playVideo(videoId) {
    document.getElementById('videoSelect').value = videoId;
    document.getElementById('startSession').disabled = false;
    
    // Mudar para aba do player
    const playerTab = new bootstrap.Tab(document.getElementById('player-tab'));
    playerTab.show();
}

// ✅ FUNÇÃO PARA EDITAR VÍDEO
function editVideo(videoId) {
    document.getElementById('editorVideoSelect').value = videoId;
    document.getElementById('loadEditor').disabled = false;
    
    // Mudar para aba do editor
    const editorTab = new bootstrap.Tab(document.getElementById('editor-tab'));
    editorTab.show();
}

// ✅ FUNÇÃO PARA INICIAR SESSÃO DE VÍDEO
async function startVideoSession() {
    const videoId = document.getElementById('videoSelect').value;
    if (!videoId) {
        showAlert('Selecione um vídeo primeiro!', 'Erro');
        return;
    }
    
    try {
        // Criar sessão
        const sessionResponse = await fetch('api_demo.php?action=create_session', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ video_id: videoId })
        });
        
        const sessionData = await sessionResponse.json();
        if (!sessionData.success) {
            throw new Error(sessionData.message);
        }
        
        currentSession = sessionData.session_id;
        
        // Carregar perguntas do vídeo
        const questionsResponse = await fetch(`api_demo.php?action=get_questions&video_id=${videoId}`);
        const questionsData = await questionsResponse.json();
        
        if (questionsData.success) {
            questions = questionsData.questions.sort((a, b) => a.time_position - b.time_position);
            console.log('📋 Perguntas carregadas:', questions.length);
            questions.forEach((q, i) => {
                console.log(`  ${i + 1}. "${q.question_text.substring(0, 30)}..." em ${q.time_position}s`);
            });
        }
        
        // Carregar vídeo
        const videoResponse = await fetch(`api_demo.php?action=get_video&id=${videoId}`);
        const videoData = await videoResponse.json();
        
        if (videoData.success) {
            currentVideo = videoData.video;
            setupVideoPlayer();
        }
        
    } catch (error) {
        showAlert('Erro ao iniciar sessão: ' + error.message, 'Erro');
    }
}

// ✅ FUNÇÃO PARA CONFIGURAR O PLAYER
function setupVideoPlayer() {
    const playerContainer = document.getElementById('playerContainer');
    const noVideoSelected = document.getElementById('noVideoSelected');
    
    playerContainer.style.display = 'block';
    noVideoSelected.style.display = 'none';
    
    videoPlayer = document.getElementById('videoPlayer');
    videoPlayer.src = `uploads/demo_video.html`;
    
    // Configurar marcadores de perguntas na timeline
    setupQuestionMarkers();
    
    // Event listeners do player
    videoPlayer.addEventListener('timeupdate', handleTimeUpdate);
    videoPlayer.addEventListener('loadedmetadata', updateTimeDisplay);
    videoPlayer.addEventListener('play', () => {
        document.querySelector('#playPauseBtn i').className = 'bi bi-pause-fill';
    });
    videoPlayer.addEventListener('pause', () => {
        document.querySelector('#playPauseBtn i').className = 'bi bi-play-fill';
    });
    videoPlayer.addEventListener('ended', handleVideoEnded);
    
    // Controles customizados
    document.getElementById('playPauseBtn').onclick = togglePlayPause;
    document.getElementById('fullscreenBtn').onclick = toggleFullscreen;
}

// ✅ FUNÇÃO PARA CONFIGURAR MARCADORES DE PERGUNTAS
function setupQuestionMarkers() {
    const markersContainer = document.getElementById('questionMarkers');
    markersContainer.innerHTML = '';
    
    if (videoPlayer && questions.length > 0) {
        questions.forEach((question, index) => {
            const marker = document.createElement('div');
            marker.className = 'timeline-marker';
            marker.title = `Pergunta ${index + 1}: ${question.question_text.substring(0, 50)}...`;
            
            // Posicionar marcador baseado no tempo
            const percentage = (question.time_position / videoPlayer.duration) * 100;
            marker.style.left = percentage + '%';
            
            marker.onclick = () => {
                videoPlayer.currentTime = question.time_position;
            };
            
            markersContainer.appendChild(marker);
        });
    }
}

// ✅ FUNÇÃO PARA LIDAR COM ATUALIZAÇÃO DE TEMPO
function handleTimeUpdate() {
    if (!videoPlayer || isQuestionActive) return;
    
    const currentTime = videoPlayer.currentTime;
    const duration = videoPlayer.duration;
    
    // Atualizar barra de progresso
    const progressBar = document.getElementById('progressBar');
    const percentage = (currentTime / duration) * 100;
    progressBar.style.width = percentage + '%';
    
    // Atualizar display de tempo
    updateTimeDisplay();
    
    // Verificar se deve mostrar pergunta
    checkForQuestions(currentTime);
}

// ✅ FUNÇÃO PARA VERIFICAR PERGUNTAS
function checkForQuestions(currentTime) {
    console.log('🕐 Verificando perguntas - Tempo atual:', currentTime);
    
    for (let i = 0; i < questions.length; i++) {
        const question = questions[i];
        console.log(`📝 Pergunta ${i + 1}: tempo=${question.time_position}, respondida=${question.answered}`);
        
        // Verificar se chegou no tempo da pergunta (com tolerância de 1s)
        // Usar >= para garantir que não perca o momento
        if (currentTime >= question.time_position && 
            currentTime <= (question.time_position + 1) && 
            !question.answered) {
            
            console.log('🎯 Mostrando pergunta:', question.question_text);
            showQuestion(question, i);
            break;
        }
    }
}

// ✅ FUNÇÃO PARA MOSTRAR PERGUNTA
function showQuestion(question, index) {
    isQuestionActive = true;
    currentQuestionIndex = index;
    
    // Pausar vídeo
    videoPlayer.pause();
    
    // Configurar overlay da pergunta
    const overlay = document.getElementById('questionOverlay');
    const questionText = document.getElementById('questionText');
    const questionOptions = document.getElementById('questionOptions');
    const submitBtn = document.getElementById('submitAnswer');
    
    questionText.textContent = question.question_text;
    submitBtn.disabled = true;
    
    // Limpar opções anteriores
    questionOptions.innerHTML = '';
    
    // Configurar opções baseado no tipo
    if (question.question_type === 'multiple_choice') {
        const options = JSON.parse(question.options);
        options.forEach((option, i) => {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'question-option';
            optionDiv.textContent = option;
            optionDiv.onclick = () => selectOption(i, optionDiv);
            questionOptions.appendChild(optionDiv);
        });
    } else if (question.question_type === 'true_false') {
        ['Verdadeiro', 'Falso'].forEach((option, i) => {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'question-option';
            optionDiv.textContent = option;
            optionDiv.onclick = () => selectOption(i === 0 ? 'true' : 'false', optionDiv);
            questionOptions.appendChild(optionDiv);
        });
    } else if (question.question_type === 'text') {
        const textarea = document.createElement('textarea');
        textarea.className = 'form-control';
        textarea.placeholder = 'Digite sua resposta...';
        textarea.rows = 3;
        textarea.onchange = () => {
            submitBtn.disabled = !textarea.value.trim();
            submitBtn.onclick = () => submitTextAnswer(textarea.value);
        };
        questionOptions.appendChild(textarea);
    }
    
    // Mostrar overlay
    overlay.style.display = 'flex';
}

// ✅ FUNÇÃO PARA SELECIONAR OPÇÃO
function selectOption(value, element) {
    // Remover seleção anterior
    document.querySelectorAll('.question-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    // Selecionar opção atual
    element.classList.add('selected');
    
    // Habilitar botão de submit
    const submitBtn = document.getElementById('submitAnswer');
    submitBtn.disabled = false;
    submitBtn.onclick = () => submitAnswer(value);
}

// ✅ FUNÇÃO PARA ENVIAR RESPOSTA
async function submitAnswer(answer) {
    const question = questions[currentQuestionIndex];
    
    try {
        const response = await fetch('api_demo.php?action=submit_answer', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                question_id: question.id,
                session_id: currentSession,
                answer: answer
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAnswerFeedback(data.is_correct, question.correct_answer);
            questions[currentQuestionIndex].answered = true;
        } else {
            throw new Error(data.message);
        }
        
    } catch (error) {
        showAlert('Erro ao enviar resposta: ' + error.message, 'Erro');
    }
}

// ✅ FUNÇÃO PARA ENVIAR RESPOSTA DE TEXTO
async function submitTextAnswer(answer) {
    await submitAnswer(answer);
}

// ✅ FUNÇÃO PARA MOSTRAR FEEDBACK DA RESPOSTA
function showAnswerFeedback(isCorrect, correctAnswer) {
    const feedback = document.getElementById('answerFeedback');
    const submitBtn = document.getElementById('submitAnswer');
    
    if (isCorrect) {
        feedback.innerHTML = `
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> Resposta correta!
            </div>
        `;
    } else {
        feedback.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-x-circle"></i> Resposta incorreta. A resposta correta era: ${correctAnswer}
            </div>
        `;
    }
    
    // Alterar botão para continuar
    submitBtn.textContent = 'Continuar';
    submitBtn.className = 'btn btn-success';
    submitBtn.onclick = continueVideo;
}

// ✅ FUNÇÃO PARA CONTINUAR VÍDEO
function continueVideo() {
    const overlay = document.getElementById('questionOverlay');
    overlay.style.display = 'none';
    
    isQuestionActive = false;
    videoPlayer.play();
    
    // Limpar feedback
    document.getElementById('answerFeedback').innerHTML = '';
}

// ✅ FUNÇÃO PARA ALTERNAR PLAY/PAUSE
function togglePlayPause() {
    if (videoPlayer.paused) {
        videoPlayer.play();
    } else {
        videoPlayer.pause();
    }
}

// ✅ FUNÇÃO PARA TELA CHEIA
function toggleFullscreen() {
    if (videoPlayer.requestFullscreen) {
        videoPlayer.requestFullscreen();
    }
}

// ✅ FUNÇÃO PARA ATUALIZAR DISPLAY DE TEMPO
function updateTimeDisplay() {
    if (videoPlayer) {
        const current = formatTime(videoPlayer.currentTime);
        const duration = formatTime(videoPlayer.duration || 0);
        document.getElementById('timeDisplay').textContent = `${current} / ${duration}`;
    }
}

// ✅ FUNÇÃO PARA CARREGAR EDITOR
async function loadQuestionEditor() {
    const videoId = document.getElementById('editorVideoSelect').value;
    if (!videoId) {
        showAlert('Selecione um vídeo primeiro!', 'Erro');
        return;
    }
    
    try {
        // Carregar dados do vídeo
        const videoResponse = await fetch(`api_demo.php?action=get_video&id=${videoId}`);
        const videoData = await videoResponse.json();
        
        if (videoData.success) {
            const editorContainer = document.getElementById('editorContainer');
            const noVideoSelected = document.getElementById('noVideoSelectedEditor');
            
            editorContainer.style.display = 'block';
            noVideoSelected.style.display = 'none';
            
            // Configurar preview
            const preview = document.getElementById('editorPreview');
            preview.src = `uploads/${videoData.video.filename}`;
            
            // Configurar preview interativo
            setupInteractivePreview();
            
            // Configurar atualização de tempo formatado
            setupTimeFormatting();
            
            // Carregar perguntas existentes
            loadExistingQuestions(videoId);
        }
        
    } catch (error) {
        showAlert('Erro ao carregar editor: ' + error.message, 'Erro');
    }
}

// ✅ FUNÇÃO PARA CONFIGURAR FORMATAÇÃO DE TEMPO
function setupTimeFormatting() {
    const timeInput = document.getElementById('questionTime');
    const timeFormatted = document.getElementById('timeFormatted');
    
    if (timeInput && timeFormatted) {
        timeInput.addEventListener('input', function() {
            const seconds = parseInt(this.value) || 0;
            timeFormatted.textContent = formatTime(seconds);
        });
    }
}

// ✅ FUNÇÃO PARA CARREGAR PERGUNTAS EXISTENTES
async function loadExistingQuestions(videoId) {
    try {
        const response = await fetch(`api_demo.php?action=get_questions&video_id=${videoId}`);
        const data = await response.json();
        
        const container = document.getElementById('existingQuestions');
        
        if (data.success && data.questions.length > 0) {
            container.innerHTML = data.questions.map(question => `
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-clock"></i> ${formatTime(question.time_position)}
                                <span class="badge bg-secondary ms-2">${question.question_type}</span>
                            </h6>
                            <p class="card-text">${question.question_text}</p>
                            <button class="btn btn-sm btn-danger" onclick="deleteQuestion(${question.id})">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Nenhuma pergunta adicionada ainda.
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Erro ao carregar perguntas:', error);
    }
}

// ✅ FUNÇÃO PARA OBTER TEMPO ATUAL DO PREVIEW
function getCurrentTimeFromPreview() {
    const preview = document.getElementById('editorPreview');
    const timeInput = document.getElementById('questionTime');
    const timeDisplay = document.getElementById('currentTimeDisplay');
    const timeFormatted = document.getElementById('timeFormatted');
    
    if (preview && !isNaN(preview.currentTime)) {
        const currentTime = Math.floor(preview.currentTime);
        timeInput.value = currentTime;
        
        // Atualizar displays de tempo
        timeDisplay.textContent = `Tempo selecionado: ${formatTime(currentTime)}`;
        if (timeFormatted) {
            timeFormatted.textContent = formatTime(currentTime);
        }
        
        // Destacar visualmente que o tempo foi capturado
        timeDisplay.className = 'text-success fw-bold';
        timeInput.className = 'form-control border-success';
        
        setTimeout(() => {
            timeDisplay.className = 'text-muted';
            timeInput.className = 'form-control';
        }, 3000);
        
        showAlert(`✅ Tempo ${formatTime(currentTime)} capturado com sucesso!`, 'success');
    } else {
        showAlert('⚠️ Reproduza o vídeo primeiro para selecionar um momento!', 'warning');
    }
}

// ✅ FUNÇÃO PARA CONFIGURAR PREVIEW INTERATIVO
function setupInteractivePreview() {
    const preview = document.getElementById('editorPreview');
    const timeDisplay = document.getElementById('currentTimeDisplay');
    
    if (!preview) return;
    
    // Atualizar display de tempo em tempo real
    preview.addEventListener('timeupdate', function() {
        if (timeDisplay) {
            const currentTime = Math.floor(preview.currentTime);
            const duration = Math.floor(preview.duration || 0);
            timeDisplay.textContent = `Tempo atual: ${formatTime(currentTime)} / ${formatTime(duration)}`;
        }
    });
    
    // Permitir clique na timeline para navegar
    preview.addEventListener('click', function(e) {
        if (preview.duration) {
            const rect = preview.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const clickPercent = clickX / rect.width;
            const newTime = clickPercent * preview.duration;
            preview.currentTime = newTime;
            
            // Atualizar campo de tempo automaticamente
            const timeInput = document.getElementById('questionTime');
            const timeFormatted = document.getElementById('timeFormatted');
            
            if (timeInput) {
                const roundedTime = Math.floor(newTime);
                timeInput.value = roundedTime;
                
                // Destacar visualmente
                timeInput.className = 'form-control border-primary';
                setTimeout(() => {
                    timeInput.className = 'form-control';
                }, 2000);
                
                if (timeFormatted) {
                    timeFormatted.textContent = formatTime(roundedTime);
                }
            }
            
            showAlert(`🎯 Navegado para ${formatTime(newTime)} - Tempo capturado!`, 'info');
        }
    });
    
    // Adicionar controles de teclado
    preview.addEventListener('keydown', function(e) {
        switch(e.key) {
            case ' ': // Espaço para play/pause
                e.preventDefault();
                if (preview.paused) {
                    preview.play();
                } else {
                    preview.pause();
                }
                break;
            case 'ArrowLeft': // Voltar 5 segundos
                e.preventDefault();
                preview.currentTime = Math.max(0, preview.currentTime - 5);
                break;
            case 'ArrowRight': // Avançar 5 segundos
                e.preventDefault();
                preview.currentTime = Math.min(preview.duration, preview.currentTime + 5);
                break;
        }
    });
    
    // Focar no vídeo para permitir controles de teclado
    preview.setAttribute('tabindex', '0');
}

// ✅ FUNÇÃO PARA ADICIONAR OPÇÃO
function addQuestionOption() {
    const optionsList = document.getElementById('optionsList');
    const optionCount = optionsList.children.length;
    
    const optionDiv = document.createElement('div');
    optionDiv.className = 'input-group mb-2';
    optionDiv.innerHTML = `
        <input type="text" class="form-control option-input" placeholder="Opção ${optionCount + 1}">
        <div class="input-group-text">
            <input type="radio" name="correctOption" value="${optionCount}" title="Resposta correta">
        </div>
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="bi bi-trash"></i>
        </button>
    `;
    
    optionsList.appendChild(optionDiv);
}

// ✅ FUNÇÃO PARA SALVAR PERGUNTA
async function saveQuestion(formData) {
    const videoId = document.getElementById('editorVideoSelect').value;
    
    try {
        const response = await fetch('api_demo.php?action=save_question', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                video_id: videoId,
                ...formData
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('Pergunta adicionada com sucesso!', 'Sucesso');
            document.getElementById('questionForm').reset();
            loadExistingQuestions(videoId);
        } else {
            throw new Error(data.message);
        }
        
    } catch (error) {
        showAlert('Erro ao salvar pergunta: ' + error.message, 'Erro');
    }
}

// ✅ FUNÇÃO PARA EXCLUIR PERGUNTA
async function deleteQuestion(questionId) {
    if (!confirm('Tem certeza que deseja excluir esta pergunta?')) {
        return;
    }
    
    try {
        const response = await fetch('api_demo.php?action=delete_question', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question_id: questionId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('Pergunta excluída com sucesso!', 'Sucesso');
            const videoId = document.getElementById('editorVideoSelect').value;
            loadExistingQuestions(videoId);
        } else {
            throw new Error(data.message);
        }
        
    } catch (error) {
        showAlert('Erro ao excluir pergunta: ' + error.message, 'Erro');
    }
}

// ✅ EVENT LISTENERS
document.addEventListener('DOMContentLoaded', function() {
    // Carregar dados iniciais
    loadVideos();
    loadStats();
    
    // Upload form
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const progressDiv = document.getElementById('uploadProgress');
        const progressBar = progressDiv.querySelector('.progress-bar');
        
        progressDiv.style.display = 'block';
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        
        try {
            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                }
            });
            
            xhr.addEventListener('load', function() {
                progressDiv.style.display = 'none';
                
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showAlert('Vídeo enviado com sucesso!', 'Sucesso');
                            document.getElementById('uploadForm').reset();
                            loadVideos();
                            loadStats();
                        } else {
                            showAlert(response.message || 'Erro no upload', 'Erro');
                        }
                    } catch (e) {
                        showAlert('Vídeo enviado com sucesso!', 'Sucesso');
                        document.getElementById('uploadForm').reset();
                        loadVideos();
                        loadStats();
                    }
                } else {
                    showAlert('Erro no servidor: ' + xhr.status, 'Erro');
                }
            });
            
            xhr.open('POST', 'api_demo.php?action=upload');
            xhr.send(formData);
            
        } catch (error) {
            progressDiv.style.display = 'none';
            showAlert('Erro: ' + error.message, 'Erro');
        }
    });
    
    // Video select change
    document.getElementById('videoSelect').addEventListener('change', function() {
        document.getElementById('startSession').disabled = !this.value;
    });
    
    // Editor video select change
    document.getElementById('editorVideoSelect').addEventListener('change', function() {
        document.getElementById('loadEditor').disabled = !this.value;
    });
    
    // Start session button
    document.getElementById('startSession').addEventListener('click', startVideoSession);
    
    // Load editor button
    document.getElementById('loadEditor').addEventListener('click', loadQuestionEditor);
    
    // Get current time button
    document.getElementById('getCurrentTime').addEventListener('click', getCurrentTimeFromPreview);
    
    // Add option button
    document.getElementById('addOption').addEventListener('click', addQuestionOption);
    
    // Question type change
    document.getElementById('questionType').addEventListener('change', function() {
        const multipleChoice = document.getElementById('multipleChoiceOptions');
        const trueFalse = document.getElementById('trueFalseAnswer');
        
        multipleChoice.style.display = this.value === 'multiple_choice' ? 'block' : 'none';
        trueFalse.style.display = this.value === 'true_false' ? 'block' : 'none';
    });
    
    // Question form submit
    document.getElementById('questionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            time_position: parseInt(document.getElementById('questionTime').value),
            question_type: document.getElementById('questionType').value,
            question_text: document.getElementById('questionTextInput').value
        };
        
        if (formData.question_type === 'multiple_choice') {
            const options = Array.from(document.querySelectorAll('.option-input')).map(input => input.value).filter(val => val.trim());
            const correctIndex = document.querySelector('input[name="correctOption"]:checked')?.value;
            
            if (options.length < 2) {
                showAlert('Adicione pelo menos 2 opções', 'Erro');
                return;
            }
            
            if (correctIndex === undefined) {
                showAlert('Selecione a resposta correta', 'Erro');
                return;
            }
            
            formData.options = JSON.stringify(options);
            formData.correct_answer = options[parseInt(correctIndex)];
            
        } else if (formData.question_type === 'true_false') {
            const answer = document.querySelector('input[name="tfAnswer"]:checked')?.value;
            
            if (!answer) {
                showAlert('Selecione a resposta correta', 'Erro');
                return;
            }
            
            formData.correct_answer = answer;
        }
        
        saveQuestion(formData);
    });
    
    // Tab change events
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            if (this.id === 'videos-tab') {
                loadVideos();
            } else if (this.id === 'dashboard-tab') {
                loadStats();
            }
        });
    });
});

console.log('✅ Sistema de Vídeo Interativo inicializado com sucesso!');



// ✅ FUNÇÃO PARA GERAR EMBED
function generateEmbed(videoId) {
    // Abrir gerador de embed em nova aba
    window.open(`embed_generator.php?video_id=${videoId}`, '_blank');
}

// ✅ FUNÇÃO PARA ABRIR INTEGRAÇÃO EXTERNA
function openExternalIntegration() {
    // Abrir página de integração em nova aba
    window.open('external_db.php', '_blank');
}

// ✅ FUNÇÃO PARA COPIAR CÓDIGO EMBED
async function copyEmbedCode(videoId, type = 'iframe') {
    try {
        const response = await fetch(`embed_generator.php?action=generate&video_id=${videoId}&width=800&height=450`);
        const data = await response.json();
        
        if (data.success) {
            let code = '';
            switch (type) {
                case 'iframe':
                    code = data.codes.iframe;
                    break;
                case 'javascript':
                    code = data.codes.javascript;
                    break;
                case 'wordpress':
                    code = data.codes.wordpress;
                    break;
                case 'react':
                    code = data.codes.react;
                    break;
            }
            
            await navigator.clipboard.writeText(code);
            showAlert('Código copiado para a área de transferência!', 'Sucesso', 'success');
        } else {
            showAlert('Erro ao gerar código embed', 'Erro', 'error');
        }
    } catch (error) {
        console.error('Erro ao copiar código embed:', error);
        showAlert('Erro ao copiar código embed', 'Erro', 'error');
    }
}

// ✅ FUNÇÃO PARA SINCRONIZAR COM BANCO EXTERNO
async function syncWithExternalDB(connectionName, dataType = 'videos') {
    try {
        const response = await fetch('external_db.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: dataType === 'videos' ? 'sync_videos' : 'sync_responses',
                connection_name: connectionName
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(`Sincronização concluída! ${result.synced} registros sincronizados.`, 'Sucesso', 'success');
        } else {
            showAlert(`Erro na sincronização: ${result.message}`, 'Erro', 'error');
        }
    } catch (error) {
        console.error('Erro na sincronização:', error);
        showAlert('Erro na sincronização com banco externo', 'Erro', 'error');
    }
}

// ✅ FUNÇÃO PARA EXPORTAR DADOS
async function exportSystemData(format = 'json') {
    try {
        const response = await fetch('external_db.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'export_data',
                format: format
            })
        });
        
        const data = await response.text();
        
        // Criar download
        const blob = new Blob([data], { 
            type: format === 'json' ? 'application/json' : 
                  format === 'xml' ? 'application/xml' : 'text/csv'
        });
        
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `video_interactive_data_${new Date().toISOString().split('T')[0]}.${format}`;
        a.click();
        URL.revokeObjectURL(url);
        
        showAlert(`Dados exportados em formato ${format.toUpperCase()}`, 'Sucesso', 'success');
        
    } catch (error) {
        console.error('Erro na exportação:', error);
        showAlert('Erro ao exportar dados', 'Erro', 'error');
    }
}

// ✅ FUNÇÃO PARA PREVIEW DO EMBED
function previewEmbed(videoId) {
    const embedUrl = `embed.php?video_id=${videoId}&theme=default&width=800px&height=450px`;
    
    // Criar modal para preview
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview do Embed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <iframe src="${embedUrl}" width="100%" height="450" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="generateEmbed(${videoId})">
                        Gerar Código
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    new bootstrap.Modal(modal).show();
    
    // Remover modal após fechar
    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
}


// ✅ FUNÇÕES PARA ABA DE INTEGRAÇÃO

// Gerar código embed
function generateEmbedCode() {
    const videoId = document.getElementById('embedVideoSelect').value;
    const width = document.getElementById('embedWidth').value;
    const height = document.getElementById('embedHeight').value;
    const theme = document.getElementById('embedTheme').value;
    
    if (!videoId) {
        showAlert('Selecione um vídeo primeiro', 'Aviso', 'warning');
        return;
    }
    
    console.log('🔗 Gerando embed para vídeo:', videoId);
    
    // Gerar códigos embed
    const baseUrl = window.location.origin;
    const embedUrl = `${baseUrl}/embed.php?video_id=${videoId}&theme=${theme}`;
    
    const codes = {
        iframe: `<iframe src="${embedUrl}" width="${width}" height="${height}" frameborder="0" allowfullscreen></iframe>`,
        javascript: `<div id="video-player-${videoId}"></div>
<script>
(function() {
    var iframe = document.createElement('iframe');
    iframe.src = '${embedUrl}';
    iframe.width = '${width}';
    iframe.height = '${height}';
    iframe.frameBorder = '0';
    iframe.allowFullscreen = true;
    document.getElementById('video-player-${videoId}').appendChild(iframe);
})();
</script>`,
        wordpress: `[video_interativo id="${videoId}" width="${width}" height="${height}" theme="${theme}"]`,
        react: `<VideoInterativo 
    videoId={${videoId}} 
    width={${width}} 
    height={${height}} 
    theme="${theme}" 
/>`
    };
    
    // Exibir modal com códigos
    showEmbedModal(codes);
}

// Exibir modal com códigos embed
function showEmbedModal(codes) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-code-slash"></i> Códigos de Embed Gerados
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                Copie o código desejado e cole no seu site para incorporar o vídeo interativo.
                            </div>
                        </div>
                    </div>
                    
                    <!-- IFRAME -->
                    <div class="mb-4">
                        <h6><i class="bi bi-window"></i> Código iframe (Mais simples)</h6>
                        <div class="input-group">
                            <textarea class="form-control" rows="3" readonly id="iframeCode">${codes.iframe}</textarea>
                            <button class="btn btn-outline-primary" onclick="copyToClipboard('iframeCode')">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                    </div>
                    
                    <!-- JAVASCRIPT -->
                    <div class="mb-4">
                        <h6><i class="bi bi-code"></i> Código JavaScript (Mais flexível)</h6>
                        <div class="input-group">
                            <textarea class="form-control" rows="8" readonly id="jsCode">${codes.javascript}</textarea>
                            <button class="btn btn-outline-primary" onclick="copyToClipboard('jsCode')">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                    </div>
                    
                    <!-- WORDPRESS -->
                    <div class="mb-4">
                        <h6><i class="bi bi-wordpress"></i> Shortcode WordPress</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" readonly id="wpCode" value="${codes.wordpress}">
                            <button class="btn btn-outline-primary" onclick="copyToClipboard('wpCode')">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                    </div>
                    
                    <!-- REACT -->
                    <div class="mb-4">
                        <h6><i class="bi bi-code-square"></i> Componente React</h6>
                        <div class="input-group">
                            <textarea class="form-control" rows="5" readonly id="reactCode">${codes.react}</textarea>
                            <button class="btn btn-outline-primary" onclick="copyToClipboard('reactCode')">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="previewEmbedCode()">
                        <i class="bi bi-eye"></i> Visualizar Preview
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Remover modal após fechar
    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
}

// Copiar para clipboard
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Feedback visual
    const button = element.nextElementSibling;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i> Copiado!';
    button.classList.remove('btn-outline-primary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-primary');
    }, 2000);
}

// Preview do embed
function previewEmbedCode() {
    const videoId = document.getElementById('embedVideoSelect').value;
    const width = document.getElementById('embedWidth').value;
    const height = document.getElementById('embedHeight').value;
    const theme = document.getElementById('embedTheme').value;
    
    if (!videoId) {
        showAlert('Selecione um vídeo primeiro', 'Aviso', 'warning');
        return;
    }
    
    previewEmbed(videoId);
}

// Sincronização rápida
async function quickSync(type) {
    const connection = document.getElementById('quickSyncConnection').value;
    
    if (!connection) {
        showAlert('Selecione uma conexão primeiro', 'Aviso', 'warning');
        return;
    }
    
    await syncWithExternalDB(connection, type);
}

// Carregar conexões para sincronização rápida
async function loadQuickSyncConnections() {
    try {
        const response = await fetch('external_db.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'list_connections' })
        });
        
        const result = await response.json();
        
        if (result.success) {
            const select = document.getElementById('quickSyncConnection');
            select.innerHTML = '<option value="">Selecione uma conexão...</option>';
            
            result.connections.forEach(conn => {
                select.innerHTML += `<option value="${conn.name}">${conn.name} (${conn.type})</option>`;
            });
        }
    } catch (error) {
        console.error('Erro ao carregar conexões:', error);
    }
}

// Atualizar select de vídeos para embed
function updateEmbedVideoSelect() {
    const embedSelect = document.getElementById('embedVideoSelect');
    const videoSelect = document.getElementById('videoSelect');
    
    if (embedSelect && videoSelect) {
        embedSelect.innerHTML = videoSelect.innerHTML;
    }
}

// Inicializar aba de integração quando carregada
document.addEventListener('DOMContentLoaded', function() {
    // Carregar conexões quando a aba de integração for clicada
    const integrationTab = document.getElementById('integration-tab');
    if (integrationTab) {
        integrationTab.addEventListener('click', function() {
            console.log('🔗 Aba de integração clicada');
            loadQuickSyncConnections();
            // Recarregar vídeos para garantir que o select seja populado
            loadVideos();
        });
    }
});



// ✅ VARIÁVEIS GLOBAIS PARA CONSENTIMENTO
let videoStartTime = null;
let totalWatchedTime = 0;
let lastTimeUpdate = 0;

// ✅ FUNÇÃO PARA LIDAR COM O FIM DO VÍDEO
function handleVideoEnded() {
    console.log('🎬 Vídeo terminou - Iniciando processo de consentimento');
    
    // Calcular estatísticas da sessão
    const completionPercentage = 100;
    const duration = videoPlayer.duration || 0;
    totalWatchedTime = Math.min(totalWatchedTime, duration);
    
    // Mostrar modal de consentimento
    showConsentModal();
}

// ✅ FUNÇÃO PARA MOSTRAR MODAL DE CONSENTIMENTO
function showConsentModal() {
    // Criar modal se não existir
    if (!document.getElementById('consentModal')) {
        createConsentModal();
    }
    
    // Mostrar modal
    const consentModal = new bootstrap.Modal(document.getElementById('consentModal'));
    consentModal.show();
}

// ✅ FUNÇÃO PARA CRIAR MODAL DE CONSENTIMENTO
function createConsentModal() {
    const modalHTML = `
    <div class="modal fade" id="consentModal" tabindex="-1" aria-labelledby="consentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="consentModalLabel">
                        <i class="bi bi-check-circle"></i> Vídeo Concluído
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-trophy text-warning" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Parabéns! Você concluiu o vídeo!</h4>
                        <p class="text-muted">Obrigado por assistir até o final.</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-clock text-primary"></i>
                                    <h6>Tempo Assistido</h6>
                                    <span id="watchedTimeDisplay">--:--</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-percent text-success"></i>
                                    <h6>Conclusão</h6>
                                    <span id="completionDisplay">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Informações Coletadas</h6>
                        <p class="mb-2">Para fins de registro e melhoria do conteúdo, coletamos as seguintes informações:</p>
                        <ul class="mb-0">
                            <li>Endereço IP: <code id="userIpDisplay">Carregando...</code></li>
                            <li>Data e hora de conclusão: <code id="completionTimeDisplay">--</code></li>
                            <li>Tempo total assistido e estatísticas de engajamento</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Informações Opcionais (Recomendado)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="userName" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="userName" placeholder="Seu nome (opcional)">
                            </div>
                            <div class="col-md-6">
                                <label for="userEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="userEmail" placeholder="seu@email.com (opcional)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="consentCheckbox">
                        <label class="form-check-label" for="consentCheckbox">
                            <strong>Eu concordo</strong> com o registro das informações acima e confirmo que assisti ao vídeo até o final.
                            Uma notificação será enviada registrando meu consentimento.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="skipConsent()">
                        <i class="bi bi-x-circle"></i> Pular
                    </button>
                    <button type="button" class="btn btn-success" id="confirmConsentBtn" onclick="confirmConsent()" disabled>
                        <i class="bi bi-check-circle"></i> Confirmar Consentimento
                    </button>
                </div>
            </div>
        </div>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Configurar eventos
    document.getElementById('consentCheckbox').addEventListener('change', function() {
        document.getElementById('confirmConsentBtn').disabled = !this.checked;
    });
    
    // Atualizar informações no modal
    updateConsentModalInfo();
}

// ✅ FUNÇÃO PARA ATUALIZAR INFORMAÇÕES NO MODAL
function updateConsentModalInfo() {
    // Atualizar tempo assistido
    const watchedTimeDisplay = document.getElementById('watchedTimeDisplay');
    if (watchedTimeDisplay) {
        watchedTimeDisplay.textContent = formatTime(totalWatchedTime);
    }
    
    // Atualizar data/hora
    const completionTimeDisplay = document.getElementById('completionTimeDisplay');
    if (completionTimeDisplay) {
        completionTimeDisplay.textContent = new Date().toLocaleString('pt-BR');
    }
    
    // Obter e exibir IP do usuário
    fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            const ipDisplay = document.getElementById('userIpDisplay');
            if (ipDisplay) {
                ipDisplay.textContent = data.ip;
            }
        })
        .catch(() => {
            const ipDisplay = document.getElementById('userIpDisplay');
            if (ipDisplay) {
                ipDisplay.textContent = 'Não disponível';
            }
        });
}

// ✅ FUNÇÃO PARA CONFIRMAR CONSENTIMENTO
async function confirmConsent() {
    const userName = document.getElementById('userName').value.trim();
    const userEmail = document.getElementById('userEmail').value.trim();
    const consentCheckbox = document.getElementById('consentCheckbox').checked;
    
    if (!consentCheckbox) {
        showAlert('Por favor, marque a caixa de consentimento.', 'Aviso');
        return;
    }
    
    try {
        // Detectar tipo de dispositivo
        const deviceType = detectDeviceType();
        
        // Calcular estatísticas finais
        const completionPercentage = 100;
        const duration = videoPlayer.duration || 0;
        
        const sessionData = {
            session_id: currentSession,
            user_consent: true,
            user_name: userName || null,
            user_email: userEmail || null,
            completion_percentage: completionPercentage,
            total_time_watched: Math.floor(totalWatchedTime),
            device_type: deviceType
        };
        
        // Enviar dados para API
        const response = await fetch('api_demo.php?action=complete_session', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(sessionData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showConsentSuccessMessage(data);
        } else {
            throw new Error(data.message);
        }
        
    } catch (error) {
        console.error('Erro ao confirmar consentimento:', error);
        showAlert('Erro ao registrar consentimento: ' + error.message, 'Erro');
    }
}

// ✅ FUNÇÃO PARA PULAR CONSENTIMENTO
async function skipConsent() {
    try {
        const deviceType = detectDeviceType();
        
        const sessionData = {
            session_id: currentSession,
            user_consent: false,
            completion_percentage: 100,
            total_time_watched: Math.floor(totalWatchedTime),
            device_type: deviceType
        };
        
        const response = await fetch('api_demo.php?action=complete_session', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(sessionData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Fechar modal
            const consentModal = bootstrap.Modal.getInstance(document.getElementById('consentModal'));
            consentModal.hide();
            
            showAlert('Sessão finalizada. Obrigado por assistir!', 'Concluído');
        } else {
            throw new Error(data.message);
        }
        
    } catch (error) {
        console.error('Erro ao finalizar sessão:', error);
        showAlert('Erro ao finalizar sessão: ' + error.message, 'Erro');
    }
}

// ✅ FUNÇÃO PARA MOSTRAR MENSAGEM DE SUCESSO DO CONSENTIMENTO
function showConsentSuccessMessage(data) {
    // Fechar modal de consentimento
    const consentModal = bootstrap.Modal.getInstance(document.getElementById('consentModal'));
    consentModal.hide();
    
    // Criar modal de sucesso
    const successModalHTML = `
    <div class="modal fade" id="consentSuccessModal" tabindex="-1" aria-labelledby="consentSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="consentSuccessModalLabel">
                        <i class="bi bi-check-circle"></i> Consentimento Registrado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 text-success">Sucesso!</h4>
                        <p>Seu consentimento foi registrado com sucesso.</p>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="bi bi-info-circle"></i> Resumo da Sessão</h6>
                        <ul class="mb-0">
                            <li><strong>Conclusão:</strong> ${data.session_data?.completion_percentage || 100}%</li>
                            <li><strong>Perguntas respondidas:</strong> ${data.session_data?.questions_answered || 0}</li>
                            <li><strong>Respostas corretas:</strong> ${data.session_data?.questions_correct || 0}</li>
                            <li><strong>Email enviado:</strong> ${data.email_sent ? '✅ Sim' : '❌ Não'}</li>
                        </ul>
                    </div>
                    
                    ${data.email_sent ? 
                        '<div class="alert alert-info"><i class="bi bi-envelope"></i> Uma notificação foi enviada registrando seu consentimento.</div>' : 
                        '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Não foi possível enviar a notificação por email, mas seu consentimento foi registrado.</div>'
                    }
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="bi bi-check"></i> Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>`;
    
    // Remover modal anterior se existir
    const existingModal = document.getElementById('consentSuccessModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    document.body.insertAdjacentHTML('beforeend', successModalHTML);
    
    // Mostrar modal de sucesso
    const successModal = new bootstrap.Modal(document.getElementById('consentSuccessModal'));
    successModal.show();
}

// ✅ FUNÇÃO PARA DETECTAR TIPO DE DISPOSITIVO
function detectDeviceType() {
    const userAgent = navigator.userAgent.toLowerCase();
    
    if (/tablet|ipad|playbook|silk/i.test(userAgent)) {
        return 'tablet';
    } else if (/mobile|iphone|ipod|android|blackberry|opera|mini|windows\sce|palm|smartphone|iemobile/i.test(userAgent)) {
        return 'mobile';
    } else {
        return 'desktop';
    }
}

// ✅ FUNÇÃO PARA RASTREAR TEMPO ASSISTIDO
function trackWatchedTime() {
    if (videoPlayer && !videoPlayer.paused && !isQuestionActive) {
        const currentTime = videoPlayer.currentTime;
        
        if (lastTimeUpdate > 0) {
            const timeDiff = currentTime - lastTimeUpdate;
            
            // Só contar se a diferença for razoável (não houve seek)
            if (timeDiff > 0 && timeDiff < 2) {
                totalWatchedTime += timeDiff;
            }
        }
        
        lastTimeUpdate = currentTime;
    }
}

// ✅ MODIFICAR FUNÇÃO handleTimeUpdate PARA INCLUIR RASTREAMENTO
const originalHandleTimeUpdate = handleTimeUpdate;
handleTimeUpdate = function() {
    originalHandleTimeUpdate();
    trackWatchedTime();
};

// ✅ INICIALIZAR RASTREAMENTO QUANDO VÍDEO INICIA
function initializeVideoTracking() {
    videoStartTime = Date.now();
    totalWatchedTime = 0;
    lastTimeUpdate = 0;
}

// ✅ MODIFICAR FUNÇÃO playVideo PARA INICIALIZAR RASTREAMENTO
const originalPlayVideo = playVideo;
playVideo = async function(videoId) {
    await originalPlayVideo(videoId);
    initializeVideoTracking();
};

