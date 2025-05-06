<?php

class Commentaire {
    public $id_reponse;
    public $contenu;
    public $date_reponse;
    public $id_question;

    public function __construct($data) {
        $this->id_reponse = $data['id_reponse'] ?? null;
        $this->contenu = $data['contenu'] ?? '';
        $this->date_reponse = $data['date_reponse'] ?? '';
        $this->id_question = $data['id_question'] ?? null;
    }

    public function toArray() {
        return [
            'id_reponse' => $this->id_reponse,
            'contenu' => $this->contenu,
            'date_reponse' => $this->date_reponse,
            'id_question' => $this->id_question,
        ];
    }
}
