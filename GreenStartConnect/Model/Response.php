<?php

class Response {
    // Properties corresponding to database columns
    private $id;
    private $feedback_id;
    private $user_id;
    private $response_type;
    private $content;
    private $is_public;
    private $created_at;
    private $updated_at;

    // Valid response types
    private static $validResponseTypes = ['solution', 'information', 'rejection', 'follow_up'];

    // Constructor
    public function __construct(
        $feedback_id = null, 
        $user_id = null, 
        $response_type = null, 
        $content = null, 
        $is_public = 0
    ) {
        $this->feedback_id = $feedback_id;
        $this->user_id = $user_id;
        $this->response_type = $response_type;
        $this->content = $content;
        $this->is_public = $is_public;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getFeedbackId() {
        return $this->feedback_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getResponseType() {
        return $this->response_type;
    }

    public function getContent() {
        return $this->content;
    }

    public function getIsPublic() {
        return $this->is_public;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setFeedbackId($feedback_id) {
        $this->feedback_id = $feedback_id;
        return $this;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function setResponseType($response_type) {
        if (!in_array($response_type, self::$validResponseTypes)) {
            throw new InvalidArgumentException('Invalid response type');
        }
        $this->response_type = $response_type;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setIsPublic($is_public) {
        $this->is_public = (bool)$is_public;
        return $this;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
        return $this;
    }

    // Utility methods
    public function toArray() {
        return [
            'id' => $this->id,
            'feedback_id' => $this->feedback_id,
            'user_id' => $this->user_id,
            'response_type' => $this->response_type,
            'content' => $this->content,
            'is_public' => $this->is_public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    // Method to validate the response data
    public function validate() {
        $errors = [];

        if (empty($this->feedback_id)) {
            $errors[] = "Feedback ID is required";
        }

        if (empty($this->user_id)) {
            $errors[] = "User ID is required";
        }

        if (empty($this->response_type)) {
            $errors[] = "Response type is required";
        } elseif (!in_array($this->response_type, self::$validResponseTypes)) {
            $errors[] = "Invalid response type";
        }

        if (empty($this->content)) {
            $errors[] = "Content is required";
        }

        return $errors;
    }
}