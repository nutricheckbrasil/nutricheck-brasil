-- Atualizar rota da página "Equipe Nutricionistas" para /equipe-nutricionistas (NutriCheck)
-- A permissão continua como equipe_anestesistas no banco; apenas a URL exibida no menu muda.
UPDATE paginas_sistema
SET rota = '/equipe-nutricionistas'
WHERE nome = 'equipe_anestesistas';
