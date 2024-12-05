<?php

class Question {
    private String $enonce;
    private String $reponses;
    private String $answer;

    public function __construct($enonce, $reponses, $answer) {
        $this->enonce = $enonce;
        $this->reponses = $reponses;
        $this->answer = $answer;
    }

    public function getEnonce() {
        return $this->enonce;
    }

    public function getReponses() {
        return $this->reponses;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setEnonce($enonce) {
        $this->enonce = $enonce;
    }

    public function setReponses($reponses) {
        $this->reponses = $reponses;
    }

    public function setAnswer($answer) {
        $this->answer = $answer;
    }

    public function toArray() {
        return [
            'enonce' => $this->enonce,
            'reponses' => $this->reponses,
            'answer' => $this->answer
        ];
    }
}
?>