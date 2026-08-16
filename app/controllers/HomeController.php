<?php

class HomeController extends BaseController {
    
    protected function requiresAuth() {
        return false; // Página inicial não requer autenticação
    }
    
    public function index() {
        $this->render('home/index', [
            'title' => 'NutriCheck - Jornada do Paciente na Nutrição Pré-Operatória'
        ]);
    }
} 