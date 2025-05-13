<?php

class Post {
    public $id_question;
    public $questions;
    public $date_creation;
    public $id;
    public $type;
    public $imagePath;

    public function __construct($data) {
        $this->id_question = $data['id_question'] ?? null;
        $this->questions = $data['questions'] ?? '';
        $this->date_creation = $data['date_creation'] ?? '';
        $this->id = $data['id'] ?? null;
        $this->type = $data['type'] ?? '';
        $this->imagePath = $data['imagePath'] ?? '';
    }

    public function toArray() {
        return [
            'id_question' => $this->id_question,
            'questions' => $this->questions,
            'date_creation' => $this->date_creation,
            'id' => $this->id,
            'type' => $this->type,
            'imagePath' => $this->imagePath,
        ];
    }
}
