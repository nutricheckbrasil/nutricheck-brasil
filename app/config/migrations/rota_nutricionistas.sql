-- Atualizar rota da página "Nutricionistas" no menu para /nutricionistas (NutriCheck)
-- O nome da página no banco continua 'anestesistas'; apenas a URL do menu muda.
UPDATE paginas_sistema
SET rota = '/nutricionistas'
WHERE nome = 'anestesistas';
