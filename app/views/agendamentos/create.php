<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-plus-circle me-2"><<?= BASE_URL ?>/i>Novo Agendamento<<?= BASE_URL ?>/h2>
                <a href="<?= BASE_URL ?>/agendamentos" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"><<?= BASE_URL ?>/i>Voltar
                <<?= BASE_URL ?>/a>
            <<?= BASE_URL ?>/div>
        <<?= BASE_URL ?>/div>
    <<?= BASE_URL ?>/div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-plus me-2"><<?= BASE_URL ?>/i>Dados do Agendamento<<?= BASE_URL ?>/h5>
                <<?= BASE_URL ?>/div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-exclamation-triangle me-1"><<?= BASE_URL ?>/i>Corrija os seguintes erros:<<?= BASE_URL ?>/h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?><<?= BASE_URL ?>/li>
                            <?php endforeach; ?>
                        <<?= BASE_URL ?>/ul>
                    <<?= BASE_URL ?>/div>
                    <?php endif; ?>

                    <form method="POST" id="formAgendamento">
                        <div class="row g-3">
                            <!-- Seleção de Paciente -->
                            <div class="col-md-6">
                                <label for="paciente_id" class="form-label">
                                    Paciente <span class="text-danger">*<<?= BASE_URL ?>/span>
                                <<?= BASE_URL ?>/label>
                                <select class="form-select" id="paciente_id" name="paciente_id" required>
                                    <option value="">Selecione um paciente<<?= BASE_URL ?>/option>
                                    <?php foreach ($pacientes as $paciente): ?>
                                    <?php
                                    $idade = '';
                                    if ($paciente['data_nascimento']) {
                                        $nasc = new DateTime($paciente['data_nascimento']);
                                        $hoje = new DateTime();
                                        $idade = $hoje->diff($nasc)->y;
                                    }
                                    $display = $paciente['nome'];
                                    if ($idade) {
                                        $display .= " ({$idade} anos, " . ucfirst($paciente['sexo']) . ")";
                                    }
                                    ?>
                                    <option value="<?= $paciente['id'] ?>" 
                                            <?= $values['paciente_id'] == $paciente['id'] ? 'selected' : '' ?>
                                            data-idade="<?= $idade ?>"
                                            data-sexo="<?= $paciente['sexo'] ?>">
                                        <?= htmlspecialchars($display) ?>
                                    <<?= BASE_URL ?>/option>
                                    <?php endforeach; ?>
                                <<?= BASE_URL ?>/select>
                            <<?= BASE_URL ?>/div>

                            <!-- Seleção de Nutricionista -->
                            <div class="col-md-6">
                                <label for="anestesista_id" class="form-label">
                                    Nutricionista <span class="text-danger">*<<?= BASE_URL ?>/span>
                                <<?= BASE_URL ?>/label>
                                <select class="form-select" id="anestesista_id" name="anestesista_id" required>
                                    <option value="">Selecione um nutricionista<<?= BASE_URL ?>/option>
                                    <?php foreach ($anestesistas as $anestesista): ?>
                                    <option value="<?= $anestesista['id'] ?>" 
                                            <?= $values['anestesista_id'] == $anestesista['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($anestesista['nome']) ?>
                                    <<?= BASE_URL ?>/option>
                                    <?php endforeach; ?>
                                <<?= BASE_URL ?>/select>
                            <<?= BASE_URL ?>/div>

                            <!-- Seleção de Procedimento -->
                            <div class="col-md-6">
                                <label for="procedimento_id" class="form-label">
                                    Procedimento <span class="text-danger">*<<?= BASE_URL ?>/span>
                                <<?= BASE_URL ?>/label>
                                <select class="form-select" id="procedimento_id" name="procedimento_id" required>
                                    <option value="">Selecione um procedimento<<?= BASE_URL ?>/option>
                                    <?php foreach ($procedimentos as $procedimento): ?>
                                    <option value="<?= $procedimento['id'] ?>" 
                                            <?= $values['procedimento_id'] == $procedimento['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($procedimento['nome']) ?>
                                    <<?= BASE_URL ?>/option>
                                    <?php endforeach; ?>
                                <<?= BASE_URL ?>/select>
                            <<?= BASE_URL ?>/div>

                            <!-- Data do Agendamento -->
                            <div class="col-md-3">
                                <label for="data_agendamento" class="form-label">
                                    Data <span class="text-danger">*<<?= BASE_URL ?>/span>
                                <<?= BASE_URL ?>/label>
                                <input type="date" 
                                       class="form-control" 
                                       id="data_agendamento" 
                                       name="data_agendamento" 
                                       value="<?= htmlspecialchars($values['data_agendamento']) ?>"
                                       min="<?= date('Y-m-d') ?>"
                                       required>
                            <<?= BASE_URL ?>/div>

                            <!-- Horário do Agendamento -->
                            <div class="col-md-3">
                                <label for="hora_agendamento" class="form-label">
                                    Horário <span class="text-danger">*<<?= BASE_URL ?>/span>
                                <<?= BASE_URL ?>/label>
                                <input type="time" 
                                       class="form-control" 
                                       id="hora_agendamento" 
                                       name="hora_agendamento" 
                                       value="<?= htmlspecialchars($values['hora_agendamento']) ?>"
                                       required>
                            <<?= BASE_URL ?>/div>

                            <!-- Observações -->
                            <div class="col-12">
                                <label for="observacoes" class="form-label">Observações<<?= BASE_URL ?>/label>
                                <textarea class="form-control" 
                                          id="observacoes" 
                                          name="observacoes" 
                                          rows="3" 
                                          placeholder="Informações adicionais sobre o agendamento..."><?= htmlspecialchars($values['observacoes']) ?><<?= BASE_URL ?>/textarea>
                            <<?= BASE_URL ?>/div>
                        <<?= BASE_URL ?>/div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"><<?= BASE_URL ?>/i>Salvar Agendamento
                                    <<?= BASE_URL ?>/button>
                                    <a href="<?= BASE_URL ?>/agendamentos" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"><<?= BASE_URL ?>/i>Cancelar
                                    <<?= BASE_URL ?>/a>
                                <<?= BASE_URL ?>/div>
                            <<?= BASE_URL ?>/div>
                        <<?= BASE_URL ?>/div>
                    <<?= BASE_URL ?>/form>
                <<?= BASE_URL ?>/div>
            <<?= BASE_URL ?>/div>
        <<?= BASE_URL ?>/div>

        <!-- Painel de Informações -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"><<?= BASE_URL ?>/i>Informações<<?= BASE_URL ?>/h5>
                <<?= BASE_URL ?>/div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="bi bi-lightbulb me-1"><<?= BASE_URL ?>/i>Dicas:<<?= BASE_URL ?>/h6>
                        <ul class="mb-0 small">
                            <li>Selecione um paciente já cadastrado no sistema<<?= BASE_URL ?>/li>
                            <li>Escolha um nutricionista disponível<<?= BASE_URL ?>/li>
                            <li>Defina o procedimento que será realizado<<?= BASE_URL ?>/li>
                            <li>Agende para uma data futura<<?= BASE_URL ?>/li>
                            <li>Verifique a disponibilidade do horário<<?= BASE_URL ?>/li>
                        <<?= BASE_URL ?>/ul>
                    <<?= BASE_URL ?>/div>

                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle me-1"><<?= BASE_URL ?>/i>Atenção:<<?= BASE_URL ?>/h6>
                        <ul class="mb-0 small">
                            <li>Não é possível agendar no mesmo horário para o mesmo nutricionista<<?= BASE_URL ?>/li>
                            <li>O sistema verificará conflitos automaticamente<<?= BASE_URL ?>/li>
                            <li>Agendamentos podem ser editados posteriormente<<?= BASE_URL ?>/li>
                        <<?= BASE_URL ?>/ul>
                    <<?= BASE_URL ?>/div>
                <<?= BASE_URL ?>/div>
            <<?= BASE_URL ?>/div>

            <!-- Resumo do Agendamento -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-eye me-2"><<?= BASE_URL ?>/i>Resumo<<?= BASE_URL ?>/h5>
                <<?= BASE_URL ?>/div>
                <div class="card-body">
                    <div id="resumoAgendamento">
                        <p class="text-muted">Preencha os campos para ver o resumo do agendamento.<<?= BASE_URL ?>/p>
                    <<?= BASE_URL ?>/div>
                <<?= BASE_URL ?>/div>
            <<?= BASE_URL ?>/div>
        <<?= BASE_URL ?>/div>
    <<?= BASE_URL ?>/div>
<<?= BASE_URL ?>/div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formAgendamento');
    const resumo = document.getElementById('resumoAgendamento');
    
    function atualizarResumo() {
        const paciente = document.getElementById('paciente_id');
        const anestesista = document.getElementById('anestesista_id');
        const procedimento = document.getElementById('procedimento_id');
        const data = document.getElementById('data_agendamento');
        const hora = document.getElementById('hora_agendamento');
        const observacoes = document.getElementById('observacoes');
        
        let html = '<div class="row g-2">';
        
        if (paciente.selectedIndex > 0) {
            const option = paciente.options[paciente.selectedIndex];
            html += '<div class="col-12"><strong>Paciente:<<?= BASE_URL ?>/strong><br>' + option.text + '<<?= BASE_URL ?>/div>';
        }
        
        if (anestesista.selectedIndex > 0) {
            html += '<div class="col-12"><strong>Nutricionista:<<?= BASE_URL ?>/strong><br>' + anestesista.options[anestesista.selectedIndex].text + '<<?= BASE_URL ?>/div>';
        }
        
        if (procedimento.selectedIndex > 0) {
            html += '<div class="col-12"><strong>Procedimento:<<?= BASE_URL ?>/strong><br>' + procedimento.options[procedimento.selectedIndex].text + '<<?= BASE_URL ?>/div>';
        }
        
        if (data.value) {
            const dataFormatada = new Date(data.value).toLocaleDateString('pt-BR');
            html += '<div class="col-12"><strong>Data:<<?= BASE_URL ?>/strong><br>' + dataFormatada + '<<?= BASE_URL ?>/div>';
        }
        
        if (hora.value) {
            html += '<div class="col-12"><strong>Horário:<<?= BASE_URL ?>/strong><br>' + hora.value + '<<?= BASE_URL ?>/div>';
        }
        
        if (observacoes.value) {
            html += '<div class="col-12"><strong>Observações:<<?= BASE_URL ?>/strong><br>' + observacoes.value + '<<?= BASE_URL ?>/div>';
        }
        
        html += '<<?= BASE_URL ?>/div>';
        
        if (paciente.selectedIndex === 0 && anestesista.selectedIndex === 0 && 
            procedimento.selectedIndex === 0 && !data.value && !hora.value) {
            html = '<p class="text-muted">Preencha os campos para ver o resumo do agendamento.<<?= BASE_URL ?>/p>';
        }
        
        resumo.innerHTML = html;
    }
    
    <?= BASE_URL ?>/<?= BASE_URL ?>/ Adicionar listeners para atualizar o resumo
    ['paciente_id', 'anestesista_id', 'procedimento_id', 'data_agendamento', 'hora_agendamento', 'observacoes'].forEach(id => {
        document.getElementById(id).addEventListener('change', atualizarResumo);
        document.getElementById(id).addEventListener('input', atualizarResumo);
    });
    
    <?= BASE_URL ?>/<?= BASE_URL ?>/ Listener específico para buscar dados do paciente
    document.getElementById('paciente_id').addEventListener('change', function() {
        const pacienteId = this.value;
        console.log('Paciente selecionado:', pacienteId);
        if (pacienteId) {
            buscarDadosPaciente(pacienteId);
        } else {
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Limpar campos se nenhum paciente for selecionado
            document.getElementById('anestesista_id').value = '';
            document.getElementById('procedimento_id').value = '';
            atualizarResumo();
        }
    });
    
    <?= BASE_URL ?>/<?= BASE_URL ?>/ Função para buscar dados do paciente via AJAX
    function buscarDadosPaciente(pacienteId) {
        console.log('Buscando dados do paciente:', pacienteId);
        const url = `<?= BASE_URL ?>/agendamentos<?= BASE_URL ?>/getPacienteDados?paciente_id=${pacienteId}`;
        console.log('URL da requisição:', url);
        
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Dados recebidos:', data);
                if (data.success) {
                    <?= BASE_URL ?>/<?= BASE_URL ?>/ Preencher anestesista se existir
                    if (data.anestesista && data.anestesista.id) {
                        const anestesistaSelect = document.getElementById('anestesista_id');
                        anestesistaSelect.value = data.anestesista.id;
                        
                        <?= BASE_URL ?>/<?= BASE_URL ?>/ Adicionar opção se não existir
                        let optionExists = false;
                        for (let option of anestesistaSelect.options) {
                            if (option.value == data.anestesista.id) {
                                optionExists = true;
                                break;
                            }
                        }
                        
                        if (!optionExists) {
                            const option = document.createElement('option');
                            option.value = data.anestesista.id;
                            option.textContent = data.anestesista.nome;
                            anestesistaSelect.appendChild(option);
                        }
                    }
                    
                    <?= BASE_URL ?>/<?= BASE_URL ?>/ Preencher procedimento se existir
                    if (data.procedimento && data.procedimento.id) {
                        const procedimentoSelect = document.getElementById('procedimento_id');
                        procedimentoSelect.value = data.procedimento.id;
                        
                        <?= BASE_URL ?>/<?= BASE_URL ?>/ Adicionar opção se não existir
                        let optionExists = false;
                        for (let option of procedimentoSelect.options) {
                            if (option.value == data.procedimento.id) {
                                optionExists = true;
                                break;
                            }
                        }
                        
                        if (!optionExists) {
                            const option = document.createElement('option');
                            option.value = data.procedimento.id;
                            option.textContent = data.procedimento.nome;
                            procedimentoSelect.appendChild(option);
                        }
                    }
                    
                    <?= BASE_URL ?>/<?= BASE_URL ?>/ Mostrar mensagem informativa se houver dados associados
                    if ((data.anestesista && data.anestesista.id) || (data.procedimento && data.procedimento.id)) {
                        mostrarMensagemInfo('Dados do paciente carregados automaticamente!', 'success');
                    }
                    
                    <?= BASE_URL ?>/<?= BASE_URL ?>/ Atualizar resumo
                    atualizarResumo();
                } else {
                    mostrarMensagemInfo('Paciente não encontrado ou sem dados associados.', 'warning');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar dados do paciente:', error);
                mostrarMensagemInfo('Erro ao carregar dados do paciente.', 'danger');
            });
    }
    
    <?= BASE_URL ?>/<?= BASE_URL ?>/ Função para mostrar mensagens informativas
    function mostrarMensagemInfo(mensagem, tipo) {
        <?= BASE_URL ?>/<?= BASE_URL ?>/ Remover mensagens anteriores
        const mensagensExistentes = document.querySelectorAll('.mensagem-info');
        mensagensExistentes.forEach(msg => msg.remove());
        
        <?= BASE_URL ?>/<?= BASE_URL ?>/ Criar nova mensagem
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} mensagem-info`;
        alertDiv.innerHTML = `<i class="bi bi-info-circle me-1"><<?= BASE_URL ?>/i>${mensagem}`;
        
        <?= BASE_URL ?>/<?= BASE_URL ?>/ Inserir antes do formulário
        const form = document.getElementById('formAgendamento');
        form.parentNode.insertBefore(alertDiv, form);
        
        <?= BASE_URL ?>/<?= BASE_URL ?>/ Remover mensagem após 5 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    
    <?= BASE_URL ?>/<?= BASE_URL ?>/ Atualizar resumo inicial
    atualizarResumo();
    
    <?= BASE_URL ?>/<?= BASE_URL ?>/ Validação de conflito de horário
    form.addEventListener('submit', function(e) {
        const anestesista = document.getElementById('anestesista_id').value;
        const data = document.getElementById('data_agendamento').value;
        const hora = document.getElementById('hora_agendamento').value;
        
        if (anestesista && data && hora) {
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Aqui poderia fazer uma verificação AJAX para conflitos
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Por enquanto, deixamos o backend fazer a validação
        }
    });
});
<<?= BASE_URL ?>/script>
